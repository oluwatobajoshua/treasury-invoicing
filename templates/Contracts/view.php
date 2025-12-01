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
.page-header{background:linear-gradient(135deg,var(--primary),#0a3d30);color:#fff;padding:1rem 1.5rem;border-radius:12px;margin-bottom:1.5rem}
.page-header h2{font-size:1.25rem;font-weight:700;margin:0;display:flex;align-items:center;gap:.5rem}
.page-header .action-buttons{display:flex;gap:.75rem;margin-top:.75rem}
.content-wrapper{display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:1.5rem}
.content-card{background:#fff;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.08)}
.section-title{font-size:.875rem;font-weight:600;margin:0 0 1rem;padding-bottom:.5rem;border-bottom:2px solid var(--gray-200);color:var(--gray-900);display:flex;align-items:center;gap:.5rem}
.detail-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem}
.detail-grid.full{grid-template-columns:1fr}
.detail-item{margin-bottom:.75rem}
.detail-label{font-size:.7rem;font-weight:600;color:var(--gray-600);text-transform:uppercase;letter-spacing:.5px;margin-bottom:.25rem}
.detail-value{font-size:.875rem;color:var(--gray-900);line-height:1.4}
.detail-value strong{font-weight:700;font-size:.9375rem}
.info-row{display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid var(--gray-200)}
.info-row:last-child{border-bottom:none}
.info-label{font-weight:600;color:var(--gray-700);font-size:.8125rem}
.info-value{color:var(--gray-900);font-size:.875rem}
.badge{display:inline-flex;align-items:center;gap:.25rem;padding:.25rem .625rem;border-radius:6px;font-size:.75rem;font-weight:600}
.badge.success{background:#d1fae5;color:#065f46}
.badge.warning{background:#fef3c7;color:#92400e}
.badge.danger{background:#fee2e2;color:#991b1b}
.badge.primary{background:#dbeafe;color:#1e40af}
.utilization-bar{background:var(--gray-200);border-radius:8px;height:20px;overflow:hidden;position:relative;margin:.75rem 0}
.utilization-fill{background:linear-gradient(90deg,var(--success),#059669);height:100%;transition:width .3s;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.75rem;font-weight:600}
.utilization-stats{display:flex;justify-content:space-between;font-size:.75rem;color:var(--gray-600);margin-top:.5rem}
.invoice-table{width:100%;border-collapse:separate;border-spacing:0;margin-top:1rem}
.invoice-table th{background:var(--gray-100);padding:.75rem;text-align:left;font-size:.75rem;font-weight:600;color:var(--gray-700);border-bottom:2px solid var(--gray-300)}
.invoice-table td{padding:.75rem;border-bottom:1px solid var(--gray-200);font-size:.8125rem}
.invoice-table tr:hover td{background:var(--gray-50)}
.btn{padding:.5rem 1rem;border:none;border-radius:8px;font-size:.8125rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.375rem;transition:all .2s;text-decoration:none}
.btn-primary{background:linear-gradient(135deg,var(--primary),#094d3d);color:#fff}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(12,83,67,.3)}
.btn-outline{background:#fff;color:#6b7280;border:2px solid #e5e7eb}
.btn-outline:hover{background:#f9fafb;border-color:#d1d5db}
.btn-warning{background:#ff5722;color:#fff}
.btn-warning:hover{background:#f4511e}
.btn-danger{background:#ef4444;color:#fff}
.btn-danger:hover{background:#dc2626}
.btn-icon{padding:.375rem;font-size:.8125rem;color:var(--primary);background:transparent;border:none;cursor:pointer;transition:all .2s}
.btn-icon:hover{background:var(--gray-100);border-radius:6px}
.empty-state{text-align:center;padding:2rem;color:var(--gray-500)}
.sidebar-card{display:flex;flex-direction:column;gap:1rem}
.status-badge{display:inline-flex;align-items:center;gap:.375rem;padding:.375rem .75rem;border-radius:6px;font-size:.8125rem;font-weight:600}
.status-success{background:#d1fae5;color:#065f46}
.status-warning{background:#fef3c7;color:#92400e}
.status-danger{background:#fee2e2;color:#991b1b}
.status-info{background:#dbeafe;color:#1e40af}
@media (max-width:1024px){
    .content-wrapper{grid-template-columns:1fr}
    .detail-grid{grid-template-columns:1fr}
}
</style>

<div style="max-width:1400px;margin:0 auto;padding:1rem">
    <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back', ['action' => 'index'], ['class' => 'btn btn-outline', 'escape' => false]) ?>
    
    <!-- Page Header -->
    <div class="page-header" style="margin-top:1rem">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <h2>
                <i class="fas fa-file-contract"></i>
                <?= h($contract->contract_id) ?>
                <?php if ($contract->status === 'active'): ?>
                    <span class="badge success">Active</span>
                <?php elseif ($isExpiring): ?>
                    <span class="badge warning">Expiring Soon</span>
                <?php else: ?>
                    <span class="badge danger">Expired</span>
                <?php endif; ?>
            </h2>
            <div class="action-buttons">
                <?= $this->Html->link('<i class="fas fa-edit"></i> Edit', ['action' => 'edit', $contract->id], ['class' => 'btn btn-warning', 'escape' => false]) ?>
                <?= $this->Form->postLink(
                    '<i class="fas fa-trash"></i>',
                    ['action' => 'delete', $contract->id],
                    [
                        'confirm' => 'Delete contract ' . $contract->contract_id . '?',
                        'class' => 'btn btn-danger',
                        'escape' => false,
                        'title' => 'Delete'
                    ]
                ) ?>
                <?= $this->Html->link('<i class="fas fa-plus"></i> New Invoice', ['controller' => 'FreshInvoices', 'action' => 'add', '?' => ['contract' => $contract->contract_id]], ['class' => 'btn btn-primary', 'escape' => false]) ?>
            </div>
        </div>
        <?php if ($contract->has('client') || $contract->has('product')): ?>
        <div style="margin-top:.75rem;font-size:.875rem;opacity:.9">
            <?php if ($contract->has('client')): ?>
                <i class="fas fa-building"></i> <?= h($contract->client->name) ?>
            <?php endif; ?>
            <?php if ($contract->has('product')): ?>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <i class="fas fa-box"></i> <?= h($contract->product->name) ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="content-wrapper">
        <!-- Main Content (Left) -->
        <div>
            <!-- Utilization Overview -->
            <?php if ($contract->quantity > 0): ?>
            <div class="content-card" style="margin-bottom:1.5rem">
                <h3 class="section-title">
                    <i class="fas fa-chart-pie"></i> Utilization
                </h3>
                <div class="utilization-bar">
                    <div class="utilization-fill" style="width:<?= $utilization ?>%">
                        <?= number_format($utilization, 1) ?>%
                    </div>
                </div>
                <div class="utilization-stats">
                    <span><i class="fas fa-check-circle"></i> Used: <?= number_format($contract->quantity - ($contract->remaining_quantity ?? $contract->quantity), 3) ?> MT</span>
                    <span><i class="fas fa-hourglass-half"></i> Left: <?= number_format($contract->remaining_quantity ?? $contract->quantity, 3) ?> MT</span>
                    <span><i class="fas fa-box"></i> Total: <?= number_format($contract->quantity, 3) ?> MT</span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Contract Details -->
            <div class="content-card" style="margin-bottom:1.5rem">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i> Contract Details
                </h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Contract ID</div>
                        <div class="detail-value"><strong><?= h($contract->contract_id) ?></strong></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Contract Date</div>
                        <div class="detail-value"><?= $contract->contract_date ? h($contract->contract_date->format('M d, Y')) : '—' ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Start Date</div>
                        <div class="detail-value"><?= $contract->start_date ? h($contract->start_date->format('M d, Y')) : '—' ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">End Date</div>
                        <div class="detail-value">
                            <?= $contract->end_date ? h($contract->end_date->format('M d, Y')) : '—' ?>
                            <?php if ($isExpiring): ?>
                                <br><small style="color:var(--warning)">
                                    <i class="fas fa-exclamation-triangle"></i> <?= $daysRemaining ?> days left
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Details -->
            <div class="content-card" style="margin-bottom:1.5rem">
                <h3 class="section-title">
                    <i class="fas fa-dollar-sign"></i> Financial Details
                </h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Quantity</div>
                        <div class="detail-value"><strong><?= number_format($contract->quantity, 3) ?> MT</strong></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Unit Price</div>
                        <div class="detail-value"><strong>$<?= number_format($contract->unit_price, 2) ?>/MT</strong></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Total Value</div>
                        <div class="detail-value">
                            <strong style="color:var(--primary);font-size:1rem">
                                $<?= number_format($contract->total_value ?? ($contract->quantity * $contract->unit_price), 2) ?>
                            </strong>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Remaining Value</div>
                        <div class="detail-value">
                            $<?= number_format(($contract->remaining_quantity ?? $contract->quantity) * $contract->unit_price, 2) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms & Notes -->
            <?php if ($contract->payment_terms || $contract->delivery_terms || $contract->notes): ?>
            <div class="content-card">
                <h3 class="section-title">
                    <i class="fas fa-file-alt"></i> Terms & Notes
                </h3>
                <?php if ($contract->payment_terms): ?>
                <div class="detail-item">
                    <div class="detail-label">Payment Terms</div>
                    <div class="detail-value" style="background:var(--gray-50);padding:.75rem;border-radius:6px">
                        <?= nl2br(h($contract->payment_terms)) ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($contract->delivery_terms): ?>
                <div class="detail-item">
                    <div class="detail-label">Delivery Terms</div>
                    <div class="detail-value" style="background:var(--gray-50);padding:.75rem;border-radius:6px">
                        <?= nl2br(h($contract->delivery_terms)) ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($contract->notes): ?>
                <div class="detail-item">
                    <div class="detail-label">Notes</div>
                    <div class="detail-value" style="background:var(--gray-50);padding:.75rem;border-radius:6px">
                        <?= nl2br(h($contract->notes)) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar (Right) -->
        <div class="sidebar-card">
            <!-- Status Card -->
            <div class="content-card">
                <h3 class="section-title">
                    <i class="fas fa-toggle-on"></i> Status
                </h3>
                <div style="text-align:center;padding:1rem 0">
                    <?php if ($contract->status === 'active'): ?>
                        <span class="status-badge status-success" style="font-size:.9375rem;padding:.5rem 1.25rem">
                            <i class="fas fa-check-circle"></i> Active
                        </span>
                    <?php elseif ($contract->status === 'completed'): ?>
                        <span class="status-badge status-info" style="font-size:.9375rem;padding:.5rem 1.25rem">
                            <i class="fas fa-flag-checkered"></i> Completed
                        </span>
                    <?php elseif ($contract->status === 'cancelled'): ?>
                        <span class="status-badge status-danger" style="font-size:.9375rem;padding:.5rem 1.25rem">
                            <i class="fas fa-ban"></i> Cancelled
                        </span>
                    <?php else: ?>
                        <span class="status-badge status-warning" style="font-size:.9375rem;padding:.5rem 1.25rem">
                            <i class="fas fa-clock"></i> <?= h($contract->status) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Record Info -->
            <div class="content-card">
                <h3 class="section-title">
                    <i class="fas fa-clock"></i> Record Info
                </h3>
                <div class="detail-item">
                    <div class="detail-label">Created</div>
                    <div class="detail-value" style="font-size:.8125rem">
                        <?= h($contract->created->format('M j, Y')) ?><br>
                        <small style="color:#9ca3af"><?= h($contract->created->format('g:i A')) ?></small>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Last Modified</div>
                    <div class="detail-value" style="font-size:.8125rem">
                        <?= h($contract->modified->format('M j, Y')) ?><br>
                        <small style="color:#9ca3af"><?= h($contract->modified->format('g:i A')) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Fresh Invoices -->
    <div class="content-card">
        <h3 class="section-title">
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
            <i class="fas fa-inbox" style="font-size:2.5rem;color:var(--gray-300);margin-bottom:.75rem"></i>
            <p style="font-size:1rem;font-weight:600;margin:0 0 .5rem">No invoices yet</p>
            <p style="margin:0 0 1rem;font-size:.875rem">Create the first invoice for this contract</p>
            <?= $this->Html->link('<i class="fas fa-plus"></i> Create Fresh Invoice', ['controller' => 'FreshInvoices', 'action' => 'add', '?' => ['contract' => $contract->contract_id]], ['class' => 'btn btn-primary', 'escape' => false]) ?>
        </div>
        <?php endif; ?>
    </div>
</div>
