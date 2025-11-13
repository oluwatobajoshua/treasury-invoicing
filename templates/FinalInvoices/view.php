<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FinalInvoice $finalInvoice
 */
$this->assign('title', 'Final Invoice #' . $finalInvoice->invoice_number);
?>

<style>
@media print {
    .no-print { display: none !important; }
    body { margin: 0; padding: 0; }
}
.invoice-document{max-width:900px;margin:2rem auto;background:#fff;box-shadow:0 0 20px rgba(0,0,0,.1);font-family:Arial,sans-serif}
.company-header{display:flex;justify-content:space-between;align-items:flex-start;padding:2rem 2rem 1rem;border-bottom:4px solid #ff5722}
.company-logo img{max-height:80px;width:auto}
.company-info{text-align:right;font-size:.85rem;line-height:1.8;color:#333}
.company-info strong{font-weight:700}
.invoice-date{padding:.5rem 2rem;text-align:left;font-size:.9rem;font-weight:600;color:#333}
.client-info{padding:1rem 2rem;font-size:.9rem;line-height:1.8}
.client-info strong{display:block;font-weight:700;color:#000}
.vessel-info{padding:.5rem 2rem;display:flex;justify-content:space-between;align-items:center}
.vessel-info div{font-size:.9rem}
.vessel-info strong{font-weight:700;color:#000}
.invoice-title{text-align:center;font-size:1.5rem;font-weight:700;padding:1rem 0;text-decoration:underline;margin:1rem 0}
.bl-invoice-row{padding:0 2rem;display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:1rem}
.bl-invoice-row div{font-size:.9rem}
.bl-invoice-row strong{font-weight:700;color:#000}
.invoice-table{width:100%;border-collapse:collapse;margin:1rem 0}
.invoice-table th,.invoice-table td{border:2px solid #0c5343;padding:.75rem;text-align:center;font-size:.85rem}
.invoice-table th{background:linear-gradient(135deg,#0c5343 0%,#0a4636 100%);color:#fff;font-weight:700;text-transform:uppercase}
.invoice-table td{background:#fff;color:#000}
.invoice-table .desc-cell{text-align:left;font-weight:600}
.invoice-table .price-cell{text-align:right;font-weight:600}
.invoice-table .total-row td{font-weight:700;background:#f5f5f5}
.amount-section{padding:1rem 2rem;text-align:right}
.amount-row{display:flex;justify-content:flex-end;padding:.25rem 0;font-size:.95rem}
.amount-row .label{margin-right:2rem;font-weight:600}
.amount-row .value{font-weight:700;min-width:150px;text-align:right}
.payment-details{padding:1rem 2rem 2rem;font-size:.85rem;line-height:1.8}
.payment-details strong{display:block;font-weight:700;margin-top:.5rem}
.action-buttons{padding:1rem 2rem;background:#f9f9f9;border-top:2px solid #e0e0e0;display:flex;gap:1rem;justify-content:center}
</style>

<div class="no-print" style="max-width:900px;margin:0 auto;padding:1rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
        <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to List', ['action' => 'index'], ['class' => 'btn btn-outline', 'escape' => false]) ?>
        <div style="display:flex;gap:.5rem">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Invoice
            </button>
            <?php if ($finalInvoice->status === 'draft'): ?>
                <?= $this->Html->link('<i class="fas fa-edit"></i> Edit', ['action' => 'edit', $finalInvoice->id], ['class' => 'btn btn-warning', 'escape' => false]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="invoice-document">
    <!-- Company Header -->
    <div class="company-header">
        <div class="company-logo">
            <?php if (isset($settings) && $settings->company_logo): ?>
                <?= $this->Html->image($settings->company_logo, ['alt' => 'Company Logo', 'style' => 'max-height:80px']) ?>
            <?php else: ?>
                <div style="width:200px;height:60px;background:#ff5722;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.2rem">
                    SUNBETH LOGO
                </div>
            <?php endif; ?>
        </div>
        <div class="company-info">
            <strong>Email:</strong> <?= h($settings->email ?? 'info@sunbeth.net') ?><br>
            <strong>Telephone:</strong> <?= h($settings->telephone ?? '+234(0)805 6666 266') ?><br>
            <strong>Corporate Office:</strong> First Floor, Churchgate Towers 2,<br>
            Victoria Island, Lagos State, Nigeria.
        </div>
    </div>

    <!-- Date -->
    <div class="invoice-date">
        <strong><?= $finalInvoice->invoice_date ? $finalInvoice->invoice_date->format('jS F, Y') : date('jS F, Y') ?></strong>
    </div>

    <!-- Client Information -->
    <div class="client-info">
        <?php $fresh = $finalInvoice->fresh_invoice ?? null; ?>
        <strong><?= h($fresh->client->name ?? 'CLIENT NAME') ?></strong>
        <?php if ($fresh && $fresh->has('client') && isset($fresh->client->address)): ?>
            <?= h($fresh->client->address) ?><br>
        <?php endif; ?>
        <?php if ($fresh && $fresh->has('client') && isset($fresh->client->phone)): ?>
            <?= h($fresh->client->phone) ?>
        <?php endif; ?>
    </div>

    <!-- Vessel Information -->
    <div class="vessel-info">
        <div>
            <strong>Vessel:</strong> <?= h($finalInvoice->vessel_name ?: ($fresh->vessel->name ?? $fresh->vessel_name ?? 'N/A')) ?>
        </div>
    </div>

    <!-- Invoice Title -->
    <div class="invoice-title">FINAL INVOICE</div>

    <!-- BL and Invoice Number -->
    <div class="bl-invoice-row">
        <div>
            <strong>BL No.</strong> <?= h($finalInvoice->bl_number ?: ($fresh->bl_number ?? '')) ?>
        </div>
        <div style="text-align:right">
            <strong>Invoice No:</strong> <?= h($finalInvoice->invoice_number) ?>
        </div>
    </div>

    <!-- Invoice Table -->
    <table class="invoice-table" style="margin:0 2rem;width:calc(100% - 4rem)">
        <thead>
            <tr>
                <th style="width:16%">CONTRACT NO</th>
                <th style="width:12%">ORIGINAL QTY<br>(MT)</th>
                <th style="width:12%">LANDED QTY<br>(MT)</th>
                <th style="width:12%">VARIANCE<br>(MT)</th>
                <th style="width:16%">BULK/BAG</th>
                <th style="width:12%">PRICE ($)</th>
                <th style="width:20%">TOTAL VALUE ($)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight:600"><?= h($fresh->contract->contract_id ?? 'N/A') ?></td>
                <td style="font-weight:600"><?= isset($fresh->quantity) ? number_format($fresh->quantity, 3) : '0.000' ?></td>
                <td style="font-weight:600"><?= number_format($finalInvoice->landed_quantity, 3) ?></td>
                <?php $variance = ($finalInvoice->landed_quantity - ($fresh->quantity ?? 0)); ?>
                <td style="font-weight:700;<?= $variance >= 0 ? 'color:#0c5343' : 'color:#b71c1c' ?>">
                    <?= number_format($variance, 3) ?>
                </td>
                <td style="font-weight:600"><?= h($fresh->bulk_or_bag ?? '-') ?></td>
                <td class="price-cell">$<?= number_format($finalInvoice->unit_price, 2) ?></td>
                <?php $subtotal = $finalInvoice->landed_quantity * $finalInvoice->unit_price; ?>
                <td class="price-cell">$<?= number_format($subtotal, 2) ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="5" style="text-align:right;font-weight:700">AMOUNT PAYABLE @ <?= number_format($finalInvoice->payment_percentage, 0) ?>%</td>
                <?php $payable = $subtotal * ($finalInvoice->payment_percentage / 100); ?>
                <td colspan="2" class="price-cell" style="font-size:1rem;color:#ff5722;font-weight:700">$<?= number_format($payable, 2) ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Payment Details -->
    <div class="payment-details">
        <strong>Please Remit To:</strong>
        <?php if ($finalInvoice->has('sgc_account')): ?>
            <strong>Beneficiary Bank:</strong> <?= h($finalInvoice->sgc_account->bank_name ?? 'THE ACCESS BANK UK LIMITED') ?><br>
            <strong>Swift Code:</strong> <?= h($finalInvoice->sgc_account->swift_code ?? 'ABNGGB2L') ?><br>
            <strong>Beneficiary Name:</strong> <?= h($finalInvoice->sgc_account->account_name) ?><br>
            <strong>Currency:</strong> <?= h($finalInvoice->sgc_account->currency) ?><br>
            <strong>Account No:</strong> <?= h($finalInvoice->sgc_account->account_id) ?><br>
            <?php if (isset($finalInvoice->sgc_account->iban)): ?>
                <strong>IBAN:</strong> <?= h($finalInvoice->sgc_account->iban) ?><br>
            <?php endif; ?>
            <?php if (isset($finalInvoice->sgc_account->intermediary_bank)): ?>
                <strong>Intermediary Bank:</strong> <?= h($finalInvoice->sgc_account->intermediary_bank) ?><br>
            <?php endif; ?>
            <?php if (isset($finalInvoice->sgc_account->swift_bic)): ?>
                <strong>Swift BIC Code:</strong> <?= h($finalInvoice->sgc_account->swift_bic) ?><br>
            <?php endif; ?>
            <?php if (isset($finalInvoice->sgc_account->fedwire_aba)): ?>
                <strong>FEDWIRE/ABA:</strong> <?= h($finalInvoice->sgc_account->fedwire_aba) ?><br>
            <?php endif; ?>
        <?php endif; ?>
        <strong>Purpose:</strong> <?= h($finalInvoice->notes ?? 'Cocoa export proceeds - Final invoice based on landed quantity (CWT)') ?>
    </div>

    <!-- Action Buttons (Print Hidden) -->
    <div class="action-buttons no-print">
        <?php if ($finalInvoice->status === 'draft'): ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-paper-plane"></i> Submit for Approval',
                ['action' => 'submitForApproval', $finalInvoice->id],
                [
                    'confirm' => 'Submit this final invoice to Treasurer for approval?',
                    'class' => 'btn btn-primary btn-lg',
                    'escape' => false
                ]
            ) ?>
        <?php endif; ?>
        
        <?php if ($finalInvoice->status === 'pending_treasurer_approval' && $this->request->getAttribute('identity')->role === 'treasurer'): ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-check"></i> Approve Invoice',
                ['action' => 'treasurerApprove', $finalInvoice->id],
                [
                    'confirm' => 'Approve this final invoice?',
                    'class' => 'btn btn-success btn-lg',
                    'escape' => false
                ]
            ) ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-times"></i> Reject Invoice',
                ['action' => 'treasurerReject', $finalInvoice->id],
                [
                    'confirm' => 'Reject this final invoice?',
                    'class' => 'btn btn-danger btn-lg',
                    'escape' => false
                ]
            ) ?>
        <?php endif; ?>
        
        <?php if ($finalInvoice->status === 'approved'): ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-share"></i> Send to Sales Team',
                ['action' => 'sendToSales', $finalInvoice->id],
                [
                    'confirm' => 'Send this final invoice to Sales team?',
                    'class' => 'btn btn-primary btn-lg',
                    'escape' => false
                ]
            ) ?>
        <?php endif; ?>
    </div>
</div>

<!-- Status Badge (Print Hidden) -->
<?php if ($finalInvoice->status !== 'draft'): ?>
<div class="no-print" style="max-width:900px;margin:1rem auto;padding:1rem;background:#f9f9f9;border-radius:8px">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1rem">
        <div>
            <strong>Invoice Status:</strong><br>
            <span class="status-badge status-<?= $finalInvoice->status === 'approved' ? 'success' : ($finalInvoice->status === 'rejected' ? 'danger' : ($finalInvoice->status === 'sent_to_sales' ? 'info' : 'warning')) ?>" style="font-size:1rem;margin-top:.5rem">
                <?= h(ucfirst(str_replace('_', ' ', $finalInvoice->status))) ?>
            </span>
        </div>
        <div>
            <strong>Treasurer Approval:</strong><br>
            <span class="status-badge status-<?= $finalInvoice->treasurer_approval_status === 'approved' ? 'success' : ($finalInvoice->treasurer_approval_status === 'rejected' ? 'danger' : 'info') ?>" style="font-size:1rem;margin-top:.5rem">
                <?= h(ucfirst($finalInvoice->treasurer_approval_status)) ?>
            </span>
        </div>
        <?php if ($finalInvoice->treasurer_approval_date): ?>
        <div>
            <strong>Approval Date:</strong><br>
            <?= h($finalInvoice->treasurer_approval_date->format('M d, Y')) ?>
        </div>
        <?php endif; ?>
        <?php if ($finalInvoice->sent_to_sales_date): ?>
        <div>
            <strong>Sent to Sales:</strong><br>
            <?= h($finalInvoice->sent_to_sales_date->format('M d, Y')) ?>
        </div>
        <?php endif; ?>
    </div>
    <?php if ($finalInvoice->treasurer_comments): ?>
    <div style="margin-top:1rem;padding:1rem;background:#fff;border-left:4px solid var(--warning);border-radius:4px">
        <strong>Treasurer Comments:</strong><br>
        <?= h($finalInvoice->treasurer_comments) ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
