<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Contract $contract
 */
$utilization = 0;
if ($contract->quantity > 0) {
    $utilized = $contract->quantity - ($contract->remaining_quantity ?? $contract->quantity);
    $utilization = ($utilized / $contract->quantity) * 100;
}

$isExpiring = false;
$daysRemaining = null;
if ($contract->end_date) {
    $today = new \DateTime();
    $endDate = new \DateTime($contract->end_date->format('Y-m-d'));
    $diff = $today->diff($endDate);
    $daysRemaining = (int)$diff->format('%r%a');
    $isExpiring = $daysRemaining > 0 && $daysRemaining <= 30;
}
?>

<style>
.detail-header{background:linear-gradient(135deg,var(--primary),#0a3d30);color:#fff;padding:2rem;border-radius:16px 16px 0 0;margin-bottom:0}
.detail-title{font-size:2rem;font-weight:700;margin:0 0 .5rem}
.detail-subtitle{font-size:1rem;opacity:.9;margin:0}
.detail-actions{display:flex;gap:1rem;margin-top:1.5rem}
.grid-2{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.5rem;margin-bottom:1.5rem}
.info-card{background:#fff;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.08)}
.info-row{display:flex;justify-content:space-between;padding:.75rem 0;border-bottom:1px solid var(--gray-200)}
.info-row:last-child{border-bottom:none}
.info-label{font-weight:600;color:var(--gray-700);font-size:.9rem}
.info-value{color:var(--gray-900);font-size:.95rem}
.utilization-bar{background:var(--gray-200);border-radius:8px;height:24px;overflow:hidden;position:relative}
.utilization-fill{background:linear-gradient(90deg,var(--success),#059669);height:100%;transition:width .3s;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.875rem;font-weight:600}
.invoice-list{background:#fff;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-top:1.5rem}
.invoice-table{width:100%;border-collapse:separate;border-spacing:0}
.invoice-table th{background:var(--gray-100);padding:1rem;text-align:left;font-size:.875rem;font-weight:600;color:var(--gray-700);border-bottom:2px solid var(--gray-300)}
.invoice-table td{padding:1rem;border-bottom:1px solid var(--gray-200);font-size:.9rem}
.invoice-table tr:hover td{background:var(--gray-50)}
.empty-state{text-align:center;padding:3rem;color:var(--gray-500)}
</style>

<div style="max-width:1400px;margin:0 auto">
    <div style="margin-bottom:2rem">
        <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to Contracts', ['action' => 'index'], ['class' => 'btn btn-outline', 'escape' => false]) ?>
    </div>

    <div style="background:#fff;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,.1);overflow:hidden">
        <!-- Header -->
        <div class="detail-header">
            <div class="detail-title">
                <i class="fas fa-file-contract"></i> <?= h($contract->contract_id) ?>
                <?php if ($contract->status === 'active'): ?>
                    <span class="status-badge status-success" style="font-size:1rem;margin-left:1rem">Active</span>
                <?php elseif ($isExpiring): ?>
                    <span class="status-badge status-warning" style="font-size:1rem;margin-left:1rem">Expiring Soon</span>
                <?php else: ?>
                    <span class="status-badge status-danger" style="font-size:1rem;margin-left:1rem">Expired</span>
                <?php endif; ?>
            </div>
            <div class="detail-subtitle">
                <?php if ($contract->has('client')): ?>
                    <i class="fas fa-building"></i> <?= h($contract->client->name) ?>
                <?php endif; ?>
                <?php if ($contract->has('product')): ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <i class="fas fa-box"></i> <?= h($contract->product->name) ?>
                <?php endif; ?>
            </div>
            <div class="detail-actions">
                <?= $this->Html->link('<i class="fas fa-edit"></i> Edit Contract', ['action' => 'edit', $contract->id], ['class' => 'btn btn-warning', 'escape' => false]) ?>
                <?= $this->Form->postLink(
                    '<i class="fas fa-trash"></i> Delete Contract',
                    ['action' => 'delete', $contract->id],
                    [
                        'confirm' => 'Are you sure you want to delete contract ' . $contract->contract_id . '?',
                        'class' => 'btn btn-danger',
                        'escape' => false
                    ]
                ) ?>
                <?= $this->Html->link('<i class="fas fa-plus"></i> New Fresh Invoice', ['controller' => 'FreshInvoices', 'action' => 'add', '?' => ['contract' => $contract->contract_id]], ['class' => 'btn btn-primary', 'escape' => false]) ?>
            </div>
        </div>

        <div style="padding:2rem">
            <!-- Utilization Overview -->
            <?php if ($contract->quantity > 0): ?>
            <div class="info-card" style="margin-bottom:1.5rem">
                <h3 style="font-size:1.125rem;font-weight:600;margin:0 0 1rem;color:var(--gray-900)">
                    <i class="fas fa-chart-pie"></i> Contract Utilization
                </h3>
                <div class="utilization-bar">
                    <div class="utilization-fill" style="width:<?= $utilization ?>%">
                        <?= number_format($utilization, 1) ?>%
                    </div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:.75rem;font-size:.9rem;color:var(--gray-600)">
                    <span><i class="fas fa-check-circle"></i> Utilized: <?= number_format($contract->quantity - ($contract->remaining_quantity ?? $contract->quantity), 3) ?> MT</span>
                    <span><i class="fas fa-hourglass-half"></i> Remaining: <?= number_format($contract->remaining_quantity ?? $contract->quantity, 3) ?> MT</span>
                    <span><i class="fas fa-box"></i> Total: <?= number_format($contract->quantity, 3) ?> MT</span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Details Grid -->
            <div class="grid-2">
                <!-- Contract Information -->
                <div class="info-card">
                    <h3 style="font-size:1.125rem;font-weight:600;margin:0 0 1rem;color:var(--gray-900)">
                        <i class="fas fa-info-circle"></i> Contract Details
                    </h3>
                    <div class="info-row">
                        <span class="info-label">Contract ID:</span>
                        <strong class="info-value"><?= h($contract->contract_id) ?></strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Contract Date:</span>
                        <span class="info-value"><?= $contract->contract_date ? h($contract->contract_date->format('M d, Y')) : '—' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Start Date:</span>
                        <span class="info-value"><?= $contract->start_date ? h($contract->start_date->format('M d, Y')) : '—' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">End Date:</span>
                        <span class="info-value">
                            <?= $contract->end_date ? h($contract->end_date->format('M d, Y')) : '—' ?>
                            <?php if ($isExpiring): ?>
                                <span style="color:var(--warning);margin-left:.5rem">
                                    <i class="fas fa-exclamation-triangle"></i> <?= $daysRemaining ?> days left
                                </span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <?php if ($contract->status === 'active'): ?>
                                <span class="status-badge status-success"><?= h($contract->status) ?></span>
                            <?php else: ?>
                                <span class="status-badge status-danger"><?= h($contract->status) ?></span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <!-- Financial Details -->
                <div class="info-card">
                    <h3 style="font-size:1.125rem;font-weight:600;margin:0 0 1rem;color:var(--gray-900)">
                        <i class="fas fa-dollar-sign"></i> Financial Details
                    </h3>
                    <div class="info-row">
                        <span class="info-label">Quantity:</span>
                        <strong class="info-value"><?= number_format($contract->quantity, 3) ?> MT</strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Unit Price:</span>
                        <strong class="info-value">$<?= number_format($contract->unit_price, 2) ?>/MT</strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total Contract Value:</span>
                        <strong class="info-value" style="color:var(--primary);font-size:1.125rem">
                            $<?= number_format($contract->total_value ?? ($contract->quantity * $contract->unit_price), 2) ?>
                        </strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Remaining Quantity:</span>
                        <span class="info-value"><?= number_format($contract->remaining_quantity ?? $contract->quantity, 3) ?> MT</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Remaining Value:</span>
                        <span class="info-value">
                            $<?= number_format(($contract->remaining_quantity ?? $contract->quantity) * $contract->unit_price, 2) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Terms -->
            <?php if ($contract->payment_terms || $contract->delivery_terms || $contract->notes): ?>
            <div class="info-card" style="margin-top:1.5rem">
                <h3 style="font-size:1.125rem;font-weight:600;margin:0 0 1rem;color:var(--gray-900)">
                    <i class="fas fa-file-alt"></i> Terms & Notes
                </h3>
                <?php if ($contract->payment_terms): ?>
                <div style="margin-bottom:1rem">
                    <strong style="display:block;margin-bottom:.5rem;color:var(--gray-700);font-size:.9rem">Payment Terms:</strong>
                    <div style="padding:1rem;background:var(--gray-50);border-radius:8px;color:var(--gray-800);font-size:.9rem">
                        <?= nl2br(h($contract->payment_terms)) ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($contract->delivery_terms): ?>
                <div style="margin-bottom:1rem">
                    <strong style="display:block;margin-bottom:.5rem;color:var(--gray-700);font-size:.9rem">Delivery Terms:</strong>
                    <div style="padding:1rem;background:var(--gray-50);border-radius:8px;color:var(--gray-800);font-size:.9rem">
                        <?= nl2br(h($contract->delivery_terms)) ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($contract->notes): ?>
                <div>
                    <strong style="display:block;margin-bottom:.5rem;color:var(--gray-700);font-size:.9rem">Additional Notes:</strong>
                    <div style="padding:1rem;background:var(--gray-50);border-radius:8px;color:var(--gray-800);font-size:.9rem">
                        <?= nl2br(h($contract->notes)) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Related Fresh Invoices -->
            <div class="invoice-list">
                <h3 style="font-size:1.125rem;font-weight:600;margin:0 0 1.5rem;color:var(--gray-900)">
                    <i class="fas fa-file-invoice"></i> Fresh Invoices (<?= count($contract->fresh_invoices ?? []) ?>)
                </h3>
                
                <?php if (!empty($contract->fresh_invoices)): ?>
                <div style="overflow-x:auto">
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>BL Number</th>
                                <th>Quantity</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contract->fresh_invoices as $invoice): ?>
                            <tr>
                                <td><strong><?= h($invoice->invoice_number) ?></strong></td>
                                <td><?= $invoice->invoice_date ? h($invoice->invoice_date->format('M d, Y')) : '—' ?></td>
                                <td><?= h($invoice->bl_number) ?></td>
                                <td><?= number_format($invoice->quantity, 3) ?> MT</td>
                                <td>$<?= number_format($invoice->total_value, 2) ?></td>
                                <td>
                                    <?php
                                    $statusClass = 'status-info';
                                    if ($invoice->status === 'approved') $statusClass = 'status-success';
                                    elseif ($invoice->status === 'rejected') $statusClass = 'status-danger';
                                    elseif ($invoice->status === 'pending') $statusClass = 'status-warning';
                                    ?>
                                    <span class="status-badge <?= $statusClass ?>"><?= h($invoice->status) ?></span>
                                </td>
                                <td>
                                    <?= $this->Html->link('<i class="fas fa-eye"></i>', ['controller' => 'FreshInvoices', 'action' => 'view', $invoice->id], ['class' => 'btn-icon', 'escape' => false, 'title' => 'View']) ?>
                                    <?= $this->Html->link('<i class="fas fa-edit"></i>', ['controller' => 'FreshInvoices', 'action' => 'edit', $invoice->id], ['class' => 'btn-icon', 'escape' => false, 'title' => 'Edit']) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox" style="font-size:3rem;color:var(--gray-300);margin-bottom:1rem"></i>
                    <p style="font-size:1.125rem;font-weight:600;margin:0 0 .5rem">No invoices yet</p>
                    <p style="margin:0 0 1.5rem">Create the first invoice for this contract</p>
                    <?= $this->Html->link('<i class="fas fa-plus"></i> Create Fresh Invoice', ['controller' => 'FreshInvoices', 'action' => 'add', '?' => ['contract' => $contract->contract_id]], ['class' => 'btn btn-primary', 'escape' => false]) ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Audit Trail -->
            <div style="margin-top:1.5rem;padding:1rem;background:var(--gray-50);border-radius:8px;font-size:.85rem;color:var(--gray-600)">
                <i class="fas fa-history"></i>
                Created: <?= h($contract->created->format('M d, Y g:i A')) ?>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                Last Modified: <?= h($contract->modified->format('M d, Y g:i A')) ?>
            </div>
        </div>
    </div>
</div>
