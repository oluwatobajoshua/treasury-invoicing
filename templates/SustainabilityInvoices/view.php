<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SustainabilityInvoice $sustainabilityInvoice
 */
$this->assign('title', 'Sustainability Invoice #' . h($sustainabilityInvoice->invoice_number));
?>

<style>
@media print {
    .action-bar {
        display: none !important;
    }
    body {
        margin: 0;
        padding: 0;
    }
}

.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 8px;
}
.action-bar .btn-group {
    display: flex;
    gap: .75rem;
}
.btn {
    padding: .625rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    text-decoration: none;
    transition: all 0.3s ease;
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
.btn-success {
    background: #10b981;
    color: white;
}
.btn-success:hover {
    background: #059669;
}
.btn-print {
    background: #6366f1;
    color: white;
}
.btn-print:hover {
    background: #4f46e5;
}

.invoice-wrapper {
    background: white;
    max-width: 8.5in;
    margin: 0 auto;
    padding: 1in;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 3px solid #10b981;
}
.company-info h1 {
    margin: 0 0 .5rem;
    font-size: 1.75rem;
    font-weight: 800;
    color: #1f2937;
}
.company-info p {
    margin: 0;
    font-size: .875rem;
    color: #6b7280;
    line-height: 1.5;
}
.invoice-meta {
    text-align: right;
}
.invoice-meta h2 {
    margin: 0 0 1rem;
    font-size: 1.5rem;
    font-weight: 800;
    color: #10b981;
}
.invoice-meta p {
    margin: .25rem 0;
    font-size: .875rem;
    color: #374151;
}
.invoice-meta strong {
    color: #1f2937;
}

.invoice-ids {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-radius: 8px;
}
.id-box {
    text-align: center;
}
.id-box .label {
    display: block;
    font-size: .75rem;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: .25rem;
}
.id-box .value {
    display: block;
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
}

.client-section {
    margin-bottom: 2rem;
}
.client-section h3 {
    margin: 0 0 .75rem;
    font-size: 1rem;
    font-weight: 700;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: .05em;
}
.client-section p {
    margin: .25rem 0;
    font-size: .875rem;
    color: #6b7280;
    line-height: 1.6;
}

.invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 2rem;
}
.invoice-table thead {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}
.invoice-table th {
    padding: .875rem .75rem;
    text-align: left;
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
}
.invoice-table th:last-child,
.invoice-table td:last-child {
    text-align: right;
}
.invoice-table tbody tr {
    border-bottom: 1px solid #e5e7eb;
}
.invoice-table td {
    padding: 1rem .75rem;
    font-size: .875rem;
    color: #374151;
}
.invoice-table .total-row {
    background: #f9fafb;
    font-weight: 700;
    font-size: .9375rem;
}
.invoice-table .total-row td {
    padding: 1.25rem .75rem;
    color: #1f2937;
    border-top: 2px solid #10b981;
}

.net-receivable-section {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 2px solid #10b981;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    text-align: center;
}
.net-receivable-section h3 {
    margin: 0 0 .5rem;
    font-size: 1rem;
    font-weight: 700;
    color: #065f46;
    text-transform: uppercase;
    letter-spacing: .05em;
}
.net-receivable-section .amount {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    color: #10b981;
}

.banking-section {
    background: #f9fafb;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}
.banking-section h3 {
    margin: 0 0 1rem;
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    text-transform: uppercase;
    letter-spacing: .05em;
}
.bank-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}
.bank-group h4 {
    margin: 0 0 .75rem;
    font-size: .875rem;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: .05em;
}
.bank-group p {
    margin: .375rem 0;
    font-size: .8125rem;
    color: #374151;
    line-height: 1.5;
}
.bank-group strong {
    color: #1f2937;
    font-weight: 600;
}

.notes-section {
    margin-top: 2rem;
    padding: 1.5rem;
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
    border-radius: 4px;
}
.notes-section h4 {
    margin: 0 0 .75rem;
    font-size: .875rem;
    font-weight: 700;
    color: #92400e;
    text-transform: uppercase;
}
.notes-section p {
    margin: 0;
    font-size: .875rem;
    color: #78350f;
    line-height: 1.6;
}
</style>

<div class="action-bar">
    <div class="btn-group">
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Back to List',
            ['action' => 'index'],
            ['class' => 'btn btn-outline', 'escapeTitle' => false]
        ) ?>
    </div>
    <div class="btn-group">
        <?= $this->Html->link(
            '<i class="fas fa-edit"></i> Edit',
            ['action' => 'edit', $sustainabilityInvoice->id],
            ['class' => 'btn btn-success', 'escapeTitle' => false]
        ) ?>
        <?= $this->Form->postLink(
            '<i class="fas fa-paper-plane"></i> Send Email',
            ['action' => 'send', $sustainabilityInvoice->id],
            [
                'class' => 'btn btn-success js-swal-post',
                'data-swal-title' => 'Send Sustainability Invoice?',
                'data-swal-text' => 'This will email the invoice to the client with a PDF attached.',
                'escapeTitle' => false
            ]
        ) ?>
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<div class="invoice-wrapper">
    <!-- Header -->
    <div class="invoice-header">
        <div class="company-info">
            <h1><?= h($companyName) ?></h1>
            <p>
                <?= h($companyAddress) ?><br>
                <?= h($companyPhone) ?><br>
                <?= h($companyEmail) ?>
            </p>
        </div>
        <div class="invoice-meta">
            <h2>INVOICE</h2>
            <p><strong>Invoice No:</strong> <?= h($sustainabilityInvoice->invoice_number) ?></p>
            <p><strong>Date:</strong> <?= h($sustainabilityInvoice->invoice_date->format('F j, Y')) ?></p>
        </div>
    </div>

    <!-- IDs Section -->
    <div class="invoice-ids">
        <div class="id-box">
            <span class="label">Seller ID</span>
            <span class="value"><?= h($sustainabilityInvoice->seller_id) ?></span>
        </div>
        <div class="id-box">
            <span class="label">Client</span>
            <span class="value"><?= h($sustainabilityInvoice->client->name) ?></span>
        </div>
        <div class="id-box">
            <span class="label">Buyer ID</span>
            <span class="value"><?= h($sustainabilityInvoice->buyer_id) ?></span>
        </div>
    </div>

    <!-- Client Details -->
    <div class="client-section">
        <h3>Bill To:</h3>
        <p><strong><?= h($sustainabilityInvoice->client->name) ?></strong></p>
        <?php if (!empty($sustainabilityInvoice->client->address)): ?>
        <p><?= h($sustainabilityInvoice->client->address) ?></p>
        <?php endif; ?>
        <?php if (!empty($sustainabilityInvoice->client->email)): ?>
        <p><?= h($sustainabilityInvoice->client->email) ?></p>
        <?php endif; ?>
    </div>

    <!-- Invoice Table -->
    <table class="invoice-table">
        <thead>
            <tr>
                <th>BUYER ID</th>
                <th>QTY (MT)</th>
                <th>DESCRIPTION</th>
                <th>SUSTAINABILITY INVESTMENT ($)</th>
                <th>SUSTAINABILITY DIFFERENTIAL ($)</th>
                <th>TOTAL VALUE ($)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= h($sustainabilityInvoice->buyer_id) ?></td>
                <td><?= $this->Number->format($sustainabilityInvoice->quantity_mt, ['places' => 3]) ?></td>
                <td><?= h($sustainabilityInvoice->description) ?></td>
                <td>$<?= $this->Number->format($sustainabilityInvoice->sustainability_investment, ['places' => 2]) ?></td>
                <td>$<?= $this->Number->format($sustainabilityInvoice->sustainability_differential, ['places' => 2]) ?></td>
                <td>$<?= $this->Number->format($sustainabilityInvoice->total_value, ['places' => 2]) ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="5"><strong>TOTAL</strong></td>
                <td><strong>$<?= $this->Number->format($sustainabilityInvoice->total_value, ['places' => 2]) ?></strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Net Receivable -->
    <div class="net-receivable-section">
        <h3>Net Receivable Against Value<br>(100% of Contract Value)</h3>
        <span class="amount">$<?= $this->Number->format($sustainabilityInvoice->net_receivable, ['places' => 2]) ?></span>
    </div>

    <!-- Banking Details -->
    <div class="banking-section">
        <h3>Please Remit To:</h3>
        <div class="bank-details">
            <div class="bank-group">
                <h4>Correspondent Bank</h4>
                <?php if ($sustainabilityInvoice->correspondent_bank): ?>
                <p><strong>Bank:</strong> <?= h($sustainabilityInvoice->correspondent_bank) ?></p>
                <?php endif; ?>
                <?php if ($sustainabilityInvoice->correspondent_address): ?>
                <p><strong>Address:</strong> <?= h($sustainabilityInvoice->correspondent_address) ?></p>
                <?php endif; ?>
                <?php if ($sustainabilityInvoice->correspondent_swift): ?>
                <p><strong>SWIFT Code:</strong> <?= h($sustainabilityInvoice->correspondent_swift) ?></p>
                <?php endif; ?>
                <?php if ($sustainabilityInvoice->aba_routing): ?>
                <p><strong>ABA:</strong> <?= h($sustainabilityInvoice->aba_routing) ?></p>
                <?php endif; ?>
            </div>
            <div class="bank-group">
                <h4>Beneficiary Bank</h4>
                <?php if ($sustainabilityInvoice->beneficiary_bank): ?>
                <p><strong>Bank:</strong> <?= h($sustainabilityInvoice->beneficiary_bank) ?></p>
                <?php endif; ?>
                <?php if ($sustainabilityInvoice->beneficiary_account_no): ?>
                <p><strong>Account No:</strong> <?= h($sustainabilityInvoice->beneficiary_account_no) ?></p>
                <?php endif; ?>
                <?php if ($sustainabilityInvoice->beneficiary_name): ?>
                <p><strong>Beneficiary Name:</strong> <?= h($sustainabilityInvoice->beneficiary_name) ?></p>
                <?php endif; ?>
                <?php if ($sustainabilityInvoice->beneficiary_acct_no): ?>
                <p><strong>Beneficiary's Account No:</strong> <?= h($sustainabilityInvoice->beneficiary_acct_no) ?></p>
                <?php endif; ?>
                <?php if ($sustainabilityInvoice->beneficiary_swift): ?>
                <p><strong>SWIFT Code:</strong> <?= h($sustainabilityInvoice->beneficiary_swift) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($sustainabilityInvoice->purpose): ?>
        <p style="margin-top: 1rem; font-size: .875rem; color: #374151;">
            <strong>Purpose:</strong> <?= h($sustainabilityInvoice->purpose) ?>
        </p>
        <?php endif; ?>
    </div>

    <!-- Notes -->
    <?php if (!empty($sustainabilityInvoice->notes)): ?>
    <div class="notes-section">
        <h4>Notes:</h4>
        <p><?= nl2br(h($sustainabilityInvoice->notes)) ?></p>
    </div>
    <?php endif; ?>
</div>
