<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\SustainabilityInvoice> $sustainabilityInvoices
 * @var array $kpis
 */
$this->assign('title', 'Sustainability Invoices');
?>

<style>
.page-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    padding: 1.5rem 2rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}
.page-header h2 {
    margin: 0 0 .5rem;
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.page-header p {
    margin: 0;
    font-size: .875rem;
    color: rgba(255, 255, 255, 0.9);
}
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.kpi-card {
    background: white;
    border-radius: 10px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border-left: 4px solid;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
}
.kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 80px;
    height: 80px;
    opacity: 0.05;
    border-radius: 50%;
    background: currentColor;
    transform: translate(30%, -30%);
}
.kpi-card.total { border-color: #10b981; color: #10b981; }
.kpi-card.draft { border-color: #9e9e9e; color: #9e9e9e; }
.kpi-card.sent { border-color: #0288d1; color: #0288d1; }
.kpi-card.paid { border-color: #2e7d32; color: #2e7d32; }
.kpi-card.cancelled { border-color: #c62828; color: #c62828; }
.kpi-card.value { border-color: #455a64; color: #455a64; }
.kpi-card.investment { border-color: #7c3aed; color: #7c3aed; }
.kpi-card.differential { border-color: #f59e0b; color: #f59e0b; }

.kpi-label {
    font-size: .75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #666;
    margin-bottom: .5rem;
}
.kpi-value {
    font-size: 1.75rem;
    font-weight: 800;
    line-height: 1;
    position: relative;
    z-index: 1;
}
.kpi-icon {
    position: absolute;
    top: 1rem;
    right: 1rem;
    font-size: 1.5rem;
    opacity: 0.2;
}
.toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    gap: 1rem;
    flex-wrap: wrap;
}
.search-box {
    flex: 1;
    max-width: 400px;
}
.search-box input {
    width: 100%;
    padding: .75rem 1rem .75rem 3rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: .9375rem;
    transition: all 0.3s ease;
    background: white url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="%239ca3af" viewBox="0 0 24 24"%3E%3Cpath d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0 .41-.41.41-1.08 0-1.49L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/%3E%3C/svg%3E') no-repeat 12px center;
}
.search-box input:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}
.btn {
    padding: .75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: .9375rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    transition: all 0.3s ease;
    text-decoration: none;
}
.btn-primary {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}
.btn-primary:hover {
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    transform: translateY(-2px);
}
.invoice-table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
}
.invoice-table table {
    width: 100%;
    border-collapse: collapse;
}
.invoice-table thead {
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
}
.invoice-table th {
    padding: 1rem 1.5rem;
    text-align: left;
    font-size: .8125rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #374151;
    border-bottom: 2px solid #e5e7eb;
}
.invoice-table td {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f3f4f6;
    color: #1f2937;
    font-size: .9375rem;
}
.invoice-table tbody tr {
    transition: all 0.2s ease;
}
.invoice-table tbody tr:hover {
    background: #f9fafb;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: .375rem;
    padding: .375rem .875rem;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.status-badge.draft { background: #f3f4f6; color: #6b7280; }
.status-badge.sent { background: #dbeafe; color: #1e40af; }
.status-badge.paid { background: #d1fae5; color: #065f46; }
.status-badge.cancelled { background: #fee2e2; color: #991b1b; }
.status-badge i {
    font-size: .625rem;
}
.actions {
    display: flex;
    gap: .5rem;
}
.action-btn {
    padding: .5rem;
    border: none;
    background: transparent;
    color: #6b7280;
    cursor: pointer;
    border-radius: 6px;
    transition: all 0.2s ease;
    font-size: .875rem;
}
.action-btn:hover {
    background: #f3f4f6;
    color: #10b981;
}
.no-data {
    text-align: center;
    padding: 4rem 2rem;
    color: #6b7280;
}
.no-data i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.3;
}
.no-data h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: .5rem;
    color: #374151;
}
.no-data p {
    font-size: .9375rem;
}
</style>

<div class="page-header">
    <h2>
        <i class="fas fa-leaf"></i>
        Sustainability Invoices
    </h2>
    <p>Track cocoa exports with sustainability investments and differentials</p>
</div>

<!-- KPI Cards -->
<div class="kpi-grid">
    <div class="kpi-card total">
        <i class="fas fa-file-invoice kpi-icon"></i>
        <div class="kpi-label">Total Invoices</div>
        <div class="kpi-value"><?= number_format($kpis['total']) ?></div>
    </div>
    <div class="kpi-card sent">
        <i class="fas fa-paper-plane kpi-icon"></i>
        <div class="kpi-label">Sent</div>
        <div class="kpi-value"><?= number_format($kpis['sent']) ?></div>
    </div>
    <div class="kpi-card paid">
        <i class="fas fa-check-circle kpi-icon"></i>
        <div class="kpi-label">Paid</div>
        <div class="kpi-value"><?= number_format($kpis['paid']) ?></div>
    </div>
    <div class="kpi-card cancelled">
        <i class="fas fa-times-circle kpi-icon"></i>
        <div class="kpi-label">Cancelled</div>
        <div class="kpi-value"><?= number_format($kpis['cancelled']) ?></div>
    </div>
    <div class="kpi-card investment">
        <i class="fas fa-seedling kpi-icon"></i>
        <div class="kpi-label">Investment</div>
        <div class="kpi-value">$<?= number_format($kpis['investment_sum'], 0) ?></div>
    </div>
    <div class="kpi-card differential">
        <i class="fas fa-chart-line kpi-icon"></i>
        <div class="kpi-label">Differential</div>
        <div class="kpi-value">$<?= number_format($kpis['differential_sum'], 0) ?></div>
    </div>
    <div class="kpi-card value">
        <i class="fas fa-money-bill-wave kpi-icon"></i>
        <div class="kpi-label">Total Value</div>
        <div class="kpi-value"><?= $this->Number->currency($kpis['total_value_sum'], 'USD') ?></div>
    </div>
</div>

<!-- Toolbar -->
<div class="toolbar">
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search invoices..." onkeyup="filterTable()">
    </div>
    <?= $this->Html->link(
        '<i class="fas fa-plus"></i> Create Sustainability Invoice',
        ['action' => 'add'],
        ['class' => 'btn btn-primary', 'escapeTitle' => false]
    ) ?>
</div>

<!-- Invoices Table -->
<div class="invoice-table">
    <?php if (count($sustainabilityInvoices) > 0): ?>
    <table id="invoiceTable">
        <thead>
            <tr>
                <th>Invoice No.</th>
                <th>Date</th>
                <th>Seller ID</th>
                <th>Client</th>
                <th>Buyer ID</th>
                <th>Quantity (MT)</th>
                <th>Investment</th>
                <th>Differential</th>
                <th>Total Value</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sustainabilityInvoices as $invoice): ?>
            <tr>
                <td><strong><?= h($invoice->invoice_number) ?></strong></td>
                <td><?= h($invoice->invoice_date->format('M d, Y')) ?></td>
                <td><?= h($invoice->seller_id) ?></td>
                <td><?= h($invoice->client->name ?? 'N/A') ?></td>
                <td><?= h($invoice->buyer_id) ?></td>
                <td><?= $this->Number->format($invoice->quantity_mt, ['places' => 3]) ?></td>
                <td><?= $this->Number->currency($invoice->sustainability_investment, 'USD') ?></td>
                <td><?= $this->Number->currency($invoice->sustainability_differential, 'USD') ?></td>
                <td><strong><?= $this->Number->currency($invoice->total_value, 'USD') ?></strong></td>
                <td>
                    <span class="status-badge <?= h($invoice->status) ?>">
                        <?php if ($invoice->status === 'draft'): ?>
                            <i class="fas fa-circle"></i> Draft
                        <?php elseif ($invoice->status === 'sent'): ?>
                            <i class="fas fa-paper-plane"></i> Sent
                        <?php elseif ($invoice->status === 'paid'): ?>
                            <i class="fas fa-check-circle"></i> Paid
                        <?php elseif ($invoice->status === 'cancelled'): ?>
                            <i class="fas fa-times-circle"></i> Cancelled
                        <?php endif; ?>
                    </span>
                </td>
                <td>
                    <div class="actions">
                        <?= $this->Html->link(
                            '<i class="fas fa-eye"></i>',
                            ['action' => 'view', $invoice->id],
                            ['class' => 'action-btn', 'escape' => false, 'title' => 'View']
                        ) ?>
                        <?= $this->Html->link(
                            '<i class="fas fa-edit"></i>',
                            ['action' => 'edit', $invoice->id],
                            ['class' => 'action-btn', 'escape' => false, 'title' => 'Edit']
                        ) ?>
                        <?= $this->Form->postLink(
                            '<i class="fas fa-trash"></i>',
                            ['action' => 'delete', $invoice->id],
                            [
                                'confirm' => 'Are you sure you want to delete this sustainability invoice?',
                                'class' => 'action-btn',
                                'escape' => false,
                                'title' => 'Delete'
                            ]
                        ) ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="no-data">
        <i class="fas fa-leaf"></i>
        <h3>No Sustainability Invoices Yet</h3>
        <p>Get started by creating your first sustainability invoice</p>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> Create Sustainability Invoice',
            ['action' => 'add'],
            ['class' => 'btn btn-primary', 'escapeTitle' => false, 'style' => 'margin-top: 1rem;']
        ) ?>
    </div>
    <?php endif; ?>
</div>

<script>
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('invoiceTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < td.length - 1; j++) { // Skip last column (actions)
            if (td[j] && td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        
        tr[i].style.display = found ? '' : 'none';
    }
}
</script>
