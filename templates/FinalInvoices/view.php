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
.company-info{text-align:right;font-size:.85rem;line-height:1.8;color:#374151}
.company-info strong{font-weight:700}
.invoice-date{padding:.5rem 2rem;text-align:left;font-size:.9rem;font-weight:600;color:#374151}
.client-info{padding:1rem 2rem;font-size:.9rem;line-height:1.8}
.client-info strong{display:block;font-weight:700;color:#111827}
.invoice-title{text-align:center;font-size:1.5rem;font-weight:700;padding:1rem 0;text-decoration:underline;margin:1rem 0}
.invoice-table{width:100%;border-collapse:collapse;margin:1rem 0}
.invoice-table th,.invoice-table td{border:2px solid #0c5343;padding:.75rem;text-align:center;font-size:.85rem}
.invoice-table th{background:linear-gradient(135deg,#0c5343 0%,#0a4636 100%);color:#fff;font-weight:700;text-transform:uppercase}
.invoice-table td{background:#fff;color:#111827}
.invoice-table .desc-cell{text-align:left;font-weight:600}
.invoice-table .price-cell{text-align:right;font-weight:600}
.invoice-table .total-row td{font-weight:700;background:#f9fafb}
.amount-section{padding:1rem 2rem;text-align:right}
.amount-row{display:flex;justify-content:flex-end;padding:.25rem 0;font-size:.95rem}
.amount-row .label{margin-right:2rem;font-weight:600}
.amount-row .value{font-weight:700;min-width:150px;text-align:right}
.payment-details{padding:1rem 2rem 2rem;font-size:.85rem;line-height:1.8}
.payment-details strong{font-weight:700;margin-right:.35rem}
.action-buttons{padding:1rem 2rem;background:#f9fafb;border-top:2px solid #e5e7eb;display:flex;gap:1rem;justify-content:center}
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
        <strong><?= h($fresh->client->name ?? 'CLIENT NAME') ?></strong><br>
        <?php if ($fresh && $fresh->has('client') && isset($fresh->client->address)): ?>
            <?= h($fresh->client->address) ?><br>
        <?php endif; ?>
        <?php if ($fresh && $fresh->has('client') && isset($fresh->client->city)): ?>
            <?= h($fresh->client->city) ?><br>
        <?php endif; ?>
        <?php if ($fresh && $fresh->has('client') && isset($fresh->client->phone)): ?>
            <?= h($fresh->client->phone) ?>
        <?php endif; ?>
    </div>

    <!-- Vessel Information and BL/Invoice Numbers in one row -->
    <div style="padding:0.5rem 2rem;display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:1rem">
        <div style="font-size:0.9rem">
            <strong style="font-weight:700;color:#111827">Vessel:</strong> <?= h($finalInvoice->vessel_name ?: ($fresh->vessel->name ?? $fresh->vessel_name ?? 'N/A')) ?> - <?= h($fresh->contract->contract_id ?? 'N/A') ?>
        </div>
        <div style="text-align:right;font-size:0.9rem">
            <strong style="font-weight:700;color:#111827">BL No.</strong> <?= h($finalInvoice->bl_number ?: ($fresh->bl_number ?? '')) ?><br>
            <strong style="font-weight:700;color:#111827">Invoice No:</strong> <?= h($finalInvoice->invoice_number) ?>
        </div>
    </div>

    <!-- Invoice Title -->
    <div class="invoice-title">INVOICE</div>

    <!-- Invoice Table -->
    <table class="invoice-table" style="margin:0 2rem;width:calc(100% - 4rem)">
        <thead>
            <tr>
                <th style="width:18%">CONTRACT NO</th>
                <th style="width:10%">QTY<br>(MT)</th>
                <th style="width:37%">DESCRIPTION</th>
                <th style="width:10%">BULK</th>
                <th style="width:12%">PRICE ($)</th>
                <th style="width:13%">TOTAL VALUE ($)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight:600"><?= h($fresh->contract->contract_id ?? 'N/A') ?></td>
                <td style="font-weight:600"><?= number_format($finalInvoice->landed_quantity, 3) ?></td>
                <td class="desc-cell"><?= h($fresh->product->name ?? 'Product Name') ?></td>
                <td style="font-weight:600"><?= h($fresh->bulk_or_bag ?? '-') ?></td>
                <td class="price-cell">$<?= number_format($finalInvoice->unit_price, 2) ?></td>
                <?php $totalValue = $finalInvoice->landed_quantity * $finalInvoice->unit_price; ?>
                <td class="price-cell">$<?= number_format($totalValue, 2) ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="text-align:right;font-weight:700">TOTAL</td>
                <td style="font-weight:600"><?= number_format($finalInvoice->landed_quantity, 2) ?></td>
                <td colspan="2"></td>
                <td class="price-cell" style="font-size:.95rem">$<?= number_format($totalValue, 2) ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Amount Section with Deductions -->
    <div class="amount-section">
        <div class="amount-row">
            <span class="label">TOTAL VALUE:</span>
            <span class="value">$<?= number_format($totalValue, 2) ?></span>
        </div>
        <?php 
        // Use the stored amount_paid field (editable during creation)
        $amountPaid = $finalInvoice->amount_paid ?? 0;
        $amountDue = $totalValue - $amountPaid;
        ?>
        <div class="amount-row" style="margin-top:.5rem">
            <span class="label">LESS AMOUNT PAID :</span>
            <span class="value">$<?= number_format($amountPaid, 2) ?></span>
        </div>
        <div class="amount-row" style="font-size:1.05rem;margin-top:.5rem;border-top:2px solid #111827;padding-top:.5rem">
            <span class="label" style="font-weight:700">AMOUNT DUE :</span>
            <span class="value" style="font-size:1.1rem;color:<?= $amountDue >= 0 ? '#ff5722' : '#ef4444' ?>">$<?= number_format($amountDue, 2) ?></span>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="payment-details">
        <strong>Please Remit To:</strong><br>
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
        
        <?php 
            $currentRole = $authUser['role'] ?? ($this->request->getSession()->read('Auth.User.role') ?? null);
            if ($finalInvoice->status === 'pending_treasurer_approval' && $currentRole === 'treasurer'): 
        ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-check"></i> Approve Invoice',
                ['action' => 'treasurerApprove', $finalInvoice->id],
                [
                    'class' => 'btn btn-success btn-lg js-swal-post',
                    'data-swal-title' => 'Approve Final Invoice?',
                    'data-swal-text' => 'This will approve the final invoice.',
                    'escape' => false
                ]
            ) ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-times"></i> Reject Invoice',
                ['action' => 'treasurerReject', $finalInvoice->id],
                [
                    'class' => 'btn btn-danger btn-lg js-swal-post',
                    'data-swal-title' => 'Reject Final Invoice?',
                    'data-swal-text' => 'This will reject the final invoice.',
                    'data-require-comment' => '1',
                    'data-comment-name' => 'treasurer_comments',
                    'data-comment-title' => 'Rejection reason (required)',
                    'data-comment-placeholder' => 'Enter the reason for rejection...',
                    'escape' => false
                ]
            ) ?>
        <?php endif; ?>
        
        <?php if ($finalInvoice->status === 'approved'): ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-share"></i> Send to Sales Team',
                ['action' => 'sendToSales', $finalInvoice->id],
                [
                    'class' => 'btn btn-primary btn-lg js-swal-post',
                    'data-swal-title' => 'Send to Sales Team?',
                    'data-swal-text' => 'This will forward the final invoice to the Sales team.',
                    'escape' => false
                ]
            ) ?>
        <?php endif; ?>
    </div>
</div>

<!-- Status Badge (Print Hidden) -->
<?php if ($finalInvoice->status !== 'draft'): ?>
<div class="no-print" style="max-width:900px;margin:1rem auto;padding:1rem;background:#f9fafb;border-radius:8px">
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
