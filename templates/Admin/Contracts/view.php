<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Contract $contract
 */
$this->assign('title', 'Contract Details - ' . $contract->contract_id);
?>

<style>
/* Page Header */
.page-header-sleek {
    background: linear-gradient(135deg, #0c5343 0%, #083d2f 100%);
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    color: white;
    box-shadow: 0 2px 8px rgba(12,83,67,0.15);
}

.page-header-sleek h1 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Detail Card */
.detail-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.detail-card-header {
    background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
    padding: 1.25rem;
    border-bottom: 2px solid #e5e7eb;
}

.detail-card-header h2 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-card-body {
    padding: 1.5rem;
}

.detail-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.detail-field {
    padding: 0;
}

.detail-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1rem;
    font-weight: 500;
    color: #111827;
}

.detail-value.large {
    font-size: 1.5rem;
    font-weight: 700;
}

/* Status Badge */
.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.active { background: #d1fae5; color: #065f46; }
.status-badge.inactive { background: #fee2e2; color: #991b1b; }
.status-badge.pending { background: #fef3c7; color: #92400e; }
.status-badge.completed { background: #dbeafe; color: #1e40af; }

/* Related Records */
.related-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.related-table thead th {
    background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
    color: #374151;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    padding: 0.875rem;
    border-bottom: 2px solid #e5e7eb;
    text-align: left;
}

.related-table tbody td {
    padding: 0.875rem;
    color: #374151;
    font-size: 0.875rem;
    border-bottom: 1px solid #f3f4f6;
}

.related-table tbody tr:hover {
    background-color: #f9fafb;
}

/* Action Buttons */
.btn-group-sleek {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}
</style>

<!-- Page Header -->
<div class="page-header-sleek">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h1>
            <i class="fas fa-file-contract"></i>
            Contract Details
        </h1>
        <div class="btn-group-sleek">
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Back to List',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-edit"></i> Edit Contract',
                ['action' => 'edit', $contract->id],
                ['class' => 'btn btn-warning', 'escape' => false]
            ) ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-trash"></i> Delete',
                ['action' => 'delete', $contract->id],
                [
                    'confirm' => __('Are you sure you want to delete contract {0}?', $contract->contract_id),
                    'class' => 'btn btn-danger',
                    'escape' => false
                ]
            ) ?>
        </div>
    </div>
</div>

<!-- Contract Information -->
<div class="detail-card">
    <div class="detail-card-header">
        <h2><i class="fas fa-info-circle"></i> Contract Information</h2>
    </div>
    <div class="detail-card-body">
        <div class="detail-row">
            <div class="detail-field">
                <div class="detail-label">Contract ID</div>
                <div class="detail-value large"><?= h($contract->contract_id) ?></div>
            </div>
            <div class="detail-field">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="status-badge <?= h($contract->status) ?>">
                        <?= h(ucfirst($contract->status)) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-field">
                <div class="detail-label">Client</div>
                <div class="detail-value">
                    <?php if ($contract->has('client')): ?>
                        <?= $this->Html->link(
                            h($contract->client->name),
                            ['controller' => 'Clients', 'action' => 'view', $contract->client->id],
                            ['style' => 'color: #0c5343; text-decoration: none;']
                        ) ?>
                    <?php else: ?>
                        <span class="text-muted">N/A</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="detail-field">
                <div class="detail-label">Product</div>
                <div class="detail-value">
                    <?php if ($contract->has('product')): ?>
                        <?= $this->Html->link(
                            h($contract->product->name),
                            ['controller' => 'Products', 'action' => 'view', $contract->product->id],
                            ['style' => 'color: #0c5343; text-decoration: none;']
                        ) ?>
                    <?php else: ?>
                        <span class="text-muted">N/A</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-field">
                <div class="detail-label">Contract Date</div>
                <div class="detail-value">
                    <?= $contract->contract_date ? h($contract->contract_date->format('F d, Y')) : '<span class="text-muted">Not set</span>' ?>
                </div>
            </div>
            <div class="detail-field">
                <div class="detail-label">Expiry Date</div>
                <div class="detail-value">
                    <?= $contract->expiry_date ? h($contract->expiry_date->format('F d, Y')) : '<span class="text-muted">Not set</span>' ?>
                </div>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-field">
                <div class="detail-label">Quantity</div>
                <div class="detail-value"><?= $this->Number->format($contract->quantity) ?> MT</div>
            </div>
            <div class="detail-field">
                <div class="detail-label">Unit Price</div>
                <div class="detail-value">$<?= $this->Number->format($contract->unit_price, ['places' => 2]) ?></div>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-field">
                <div class="detail-label">Total Value</div>
                <div class="detail-value large" style="color: #0c5343;">
                    $<?= $this->Number->format($contract->quantity * $contract->unit_price, ['places' => 2]) ?>
                </div>
            </div>
            <div class="detail-field">
                <div class="detail-label">Payment Terms</div>
                <div class="detail-value"><?= h($contract->payment_terms ?: 'Not specified') ?></div>
            </div>
        </div>

        <?php if ($contract->notes): ?>
        <div class="detail-row">
            <div class="detail-field" style="grid-column: 1 / -1;">
                <div class="detail-label">Notes</div>
                <div class="detail-value"><?= h($contract->notes) ?></div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Related Fresh Invoices -->
<?php if (!empty($contract->fresh_invoices)): ?>
<div class="detail-card">
    <div class="detail-card-header">
        <h2><i class="fas fa-file-invoice"></i> Related Fresh Invoices (<?= count($contract->fresh_invoices) ?>)</h2>
    </div>
    <div class="detail-card-body">
        <table class="related-table">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>BL Number</th>
                    <th>Quantity</th>
                    <th>Total Value</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contract->fresh_invoices as $invoice): ?>
                <tr>
                    <td><strong><?= h($invoice->invoice_number) ?></strong></td>
                    <td><?= h($invoice->bl_number) ?></td>
                    <td><?= $this->Number->format($invoice->quantity) ?> MT</td>
                    <td>$<?= $this->Number->format($invoice->total_value, ['places' => 2]) ?></td>
                    <td>
                        <span class="status-badge <?= h($invoice->status) ?>" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                            <?= h(ucfirst(str_replace('_', ' ', $invoice->status))) ?>
                        </span>
                    </td>
                    <td><?= $invoice->invoice_date ? h($invoice->invoice_date->format('M d, Y')) : 'N/A' ?></td>
                    <td>
                        <?= $this->Html->link(
                            '<i class="fas fa-eye"></i>',
                            ['controller' => 'FreshInvoices', 'action' => 'view', $invoice->id, 'prefix' => false],
                            ['class' => 'btn btn-sm btn-primary', 'escape' => false, 'title' => 'View Invoice']
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Metadata -->
<div class="detail-card">
    <div class="detail-card-header">
        <h2><i class="fas fa-clock"></i> Record Information</h2>
    </div>
    <div class="detail-card-body">
        <div class="detail-row">
            <div class="detail-field">
                <div class="detail-label">Created</div>
                <div class="detail-value"><?= h($contract->created->format('F d, Y H:i:s')) ?></div>
            </div>
            <div class="detail-field">
                <div class="detail-label">Last Modified</div>
                <div class="detail-value"><?= h($contract->modified->format('F d, Y H:i:s')) ?></div>
            </div>
        </div>
    </div>
</div>
