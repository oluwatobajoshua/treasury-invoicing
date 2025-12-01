<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SalesInvoice $salesInvoice
 * @var \App\Model\Entity\Setting $settings
 */
$this->assign('title', 'Sales Invoice #' . h($salesInvoice->invoice_number));
?>

<style>
.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    gap: 1rem;
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
    background: linear-gradient(135deg, #0c5343 0%, #083d2f 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(12, 83, 67, 0.3);
}
.btn-primary:hover {
    box-shadow: 0 6px 16px rgba(12, 83, 67, 0.4);
    transform: translateY(-2px);
}
.btn-outline {
    background: white;
    color: #6b7280;
    border: 2px solid #e5e7eb;
}
.btn-outline:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}
.invoice-container {
    background: white;
    border-radius: 12px;
    padding: 3rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    max-width: 900px;
    margin: 0 auto;
}
.invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 3px solid #ff5722;
}
.company-logo {
    max-width: 200px;
}
.company-info {
    text-align: left;
}
.company-name {
    font-size: 2rem;
    font-weight: 800;
    color: #ff5722;
    margin-bottom: .5rem;
}
.company-tagline {
    font-size: .875rem;
    color: #6b7280;
    margin-bottom: 1rem;
}
.company-details {
    font-size: .875rem;
    color: #374151;
    line-height: 1.6;
}
.invoice-meta {
    text-align: right;
}
.invoice-date {
    font-size: .875rem;
    color: #6b7280;
    margin-bottom: 2rem;
}
.invoice-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: .5rem;
}
.invoice-number {
    font-size: 1rem;
    color: #6b7280;
}
.client-section {
    margin-bottom: 2rem;
}
.client-name {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: .25rem;
}
.client-address {
    font-size: .9375rem;
    color: #6b7280;
    line-height: 1.6;
}
.invoice-table {
    width: 100%;
    margin: 2rem 0;
    border-collapse: collapse;
}
.invoice-table thead {
    background: #1f2937;
    color: white;
}
.invoice-table th {
    padding: 1rem;
    text-align: left;
    font-size: .875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.invoice-table th:last-child,
.invoice-table td:last-child {
    text-align: right;
}
.invoice-table tbody tr {
    border-bottom: 1px solid #e5e7eb;
}
.invoice-table tbody tr:last-child {
    border-bottom: 2px solid #1f2937;
    font-weight: 700;
}
.invoice-table td {
    padding: 1rem;
    font-size: .9375rem;
    color: #374151;
}
.invoice-total {
    margin: 2rem 0;
    text-align: right;
}
.total-row {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 2rem;
    margin: .5rem 0;
}
.total-label {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
}
.total-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #ff5722;
}
.bank-details {
    background: #f9fafb;
    border-radius: 8px;
    padding: 2rem;
    margin: 2rem 0;
}
.bank-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
}
.bank-info {
    font-size: .875rem;
    color: #374151;
    line-height: 1.8;
}
.bank-info strong {
    color: #1f2937;
    display: inline-block;
    min-width: 150px;
}
@media print {
    .action-bar {
        display: none;
    }
    .invoice-container {
        box-shadow: none;
        padding: 0;
    }
}
</style>

<div class="action-bar">
    <div>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Back to List',
            ['action' => 'index'],
            ['class' => 'btn btn-outline', 'escape' => false]
        ) ?>
    </div>
    <div style="display: flex; gap: 1rem;">
        <?= $this->Html->link(
            '<i class="fas fa-edit"></i> Edit',
            ['action' => 'edit', $salesInvoice->id],
            ['class' => 'btn btn-outline', 'escape' => false]
        ) ?>
        <?= $this->Form->postLink(
            '<i class="fas fa-paper-plane"></i> Send Email',
            ['action' => 'send', $salesInvoice->id],
            [
                'class' => 'btn btn-primary js-swal-post',
                'data-swal-title' => 'Send Sales Invoice?',
                'data-swal-text' => 'This will email the invoice to the client with a PDF attached.',
                'escapeTitle' => false
            ]
        ) ?>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<div class="invoice-container">
    <!-- Invoice Header -->
    <div class="invoice-header">
        <div class="company-info">
            <?php if (!empty($settings->company_logo_url)): ?>
                <img src="<?= h($settings->company_logo_url) ?>" alt="Company Logo" class="company-logo">
            <?php else: ?>
                <div class="company-name">
                    <i class="fas fa-dollar-sign" style="background: #ff5722; color: white; padding: .5rem; border-radius: 8px;"></i>
                    Sunbeth<br>
                    <span style="font-size: 1rem; color: #6b7280;">Global Concepts</span>
                </div>
            <?php endif; ?>
            <div class="company-details">
                <strong>Email:</strong> info@sunbeth.net<br>
                <strong>Telephone:</strong> +234(0)805 6666 266<br>
                <strong>Corporate Office:</strong> First Floor, Churchgate<br>
                Towers 2, Victoria Island, Lagos State, Nigeria.
            </div>
        </div>
        <div class="invoice-meta">
            <div class="invoice-date">
                <?= h($salesInvoice->invoice_date->format('jS F, Y')) ?>
            </div>
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">Invoice No: <?= h($salesInvoice->invoice_number) ?></div>
        </div>
    </div>

    <!-- Client Information -->
    <div class="client-section">
        <div class="client-name"><?= h($salesInvoice->client->name) ?></div>
        <div class="client-address">
            <?php if (!empty($salesInvoice->client->address)): ?>
                <?= h($salesInvoice->client->address) ?><br>
            <?php endif; ?>
            <?php if (!empty($salesInvoice->client->city)): ?>
                <?= h($salesInvoice->client->city) ?>
            <?php endif; ?>
            <?php if (!empty($salesInvoice->client->country)): ?>
                , <?= h($salesInvoice->client->country) ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Invoice Table -->
    <table class="invoice-table">
        <thead>
            <tr>
                <th>QTY</th>
                <th>DESCRIPTION</th>
                <th>PRICE (<?= h($salesInvoice->currency) ?>)</th>
                <th>TOTAL VALUE (<?= h($salesInvoice->currency) ?>)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $this->Number->format($salesInvoice->quantity, ['places' => 2]) ?></td>
                <td><?= h($salesInvoice->description) ?></td>
                <td><?= $this->Number->format($salesInvoice->unit_price, ['places' => 2]) ?></td>
                <td><?= $this->Number->format($salesInvoice->total_value, ['places' => 2]) ?></td>
            </tr>
            <tr style="font-weight: 700;">
                <td><?= $this->Number->format($salesInvoice->quantity, ['places' => 2]) ?></td>
                <td></td>
                <td><?= $this->Number->format($salesInvoice->unit_price, ['places' => 2]) ?></td>
                <td><?= $this->Number->format($salesInvoice->total_value, ['places' => 2]) ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Total -->
    <div class="invoice-total">
        <div class="total-row">
            <span class="total-label">TOTAL VALUE (<?= h($salesInvoice->currency) ?>):</span>
            <span class="total-value"><?= $this->Number->currency($salesInvoice->total_value, $salesInvoice->currency) ?></span>
        </div>
    </div>

    <!-- Bank Details -->
    <?php if (!empty($salesInvoice->bank_account)): ?>
    <div class="bank-details">
        <div class="bank-title">Please Remit To:</div>
        <div class="bank-info">
            <strong>Beneficiary Account Name:</strong> <?= h($salesInvoice->bank_account->account_name) ?><br>
            <strong>Currency:</strong> <?= h($salesInvoice->bank_account->currency) ?><br>
            <strong>Account no:</strong> <?= h($salesInvoice->bank_account->account_number) ?><br>
            <strong>Beneficiary Bank:</strong> <?= h($salesInvoice->bank_account->bank_name) ?><br>
            <?php if (!empty($salesInvoice->purpose)): ?>
                <strong>Purpose:</strong> <?= h($salesInvoice->purpose) ?><br>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Notes -->
    <?php if (!empty($salesInvoice->notes)): ?>
    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #e5e7eb;">
        <strong style="color: #1f2937;">Notes:</strong>
        <p style="margin-top: .5rem; color: #6b7280; font-size: .9375rem;"><?= nl2br(h($salesInvoice->notes)) ?></p>
    </div>
    <?php endif; ?>
</div>
