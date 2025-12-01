<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FreshInvoice $freshInvoice
 */
$this->assign('title', 'Fresh Invoice #' . $freshInvoice->invoice_number);
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
@media print {
    .no-print { display: none !important; }
    body { margin: 0; padding: 0; }
}

/* Modal must be hidden by default */
.modal {
    display: none;
}

.modal.show {
    display: block;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: #000;
}

.modal-backdrop.show {
    opacity: 0.5;
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
            <?php if ($freshInvoice->status === 'draft'): ?>
                <?= $this->Html->link('<i class="fas fa-edit"></i> Edit', ['action' => 'edit', $freshInvoice->id], ['class' => 'btn btn-warning', 'escape' => false]) ?>
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
        <strong><?= $freshInvoice->invoice_date ? $freshInvoice->invoice_date->format('jS F, Y') : date('jS F, Y') ?></strong>
    </div>

    <!-- Client Information -->
    <div class="client-info">
        <strong><?= h($freshInvoice->client->name ?? 'CLIENT NAME') ?></strong><br>
        <?php if ($freshInvoice->has('client') && isset($freshInvoice->client->address)): ?>
            <?= h($freshInvoice->client->address) ?><br>
        <?php endif; ?>
        <?php if ($freshInvoice->has('client') && isset($freshInvoice->client->city)): ?>
            <?= h($freshInvoice->client->city) ?><br>
        <?php endif; ?>
        <?php if ($freshInvoice->has('client') && isset($freshInvoice->client->phone)): ?>
            <?= h($freshInvoice->client->phone) ?>
        <?php endif; ?>
    </div>

    <!-- Vessel Information and BL/Invoice Numbers in one row -->
    <div style="padding:0.5rem 2rem;display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:1rem">
        <div style="font-size:0.9rem">
            <strong style="font-weight:700;color:#111827">Vessel:</strong> <?= h($freshInvoice->vessel->name ?? $freshInvoice->vessel_name ?? 'N/A') ?> - <?= h($freshInvoice->contract->contract_id ?? 'N/A') ?>
        </div>
        <div style="text-align:right;font-size:0.9rem">
            <strong style="font-weight:700;color:#111827">BL No.</strong> <?= h($freshInvoice->bl_number) ?><br>
            <strong style="font-weight:700;color:#111827">Invoice No:</strong> <?= h($freshInvoice->invoice_number) ?>
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
                <td style="font-weight:600"><?= h($freshInvoice->contract->contract_id ?? 'N/A') ?></td>
                <td style="font-weight:600"><?= number_format($freshInvoice->quantity, 2) ?></td>
                <td class="desc-cell"><?= h($freshInvoice->product->name ?? 'Product Name') ?></td>
                <td style="font-weight:600"><?= h($freshInvoice->bulk_or_bag) ?></td>
                <td class="price-cell">$<?= number_format($freshInvoice->unit_price, 2) ?></td>
                <td class="price-cell">$<?= number_format($freshInvoice->quantity * $freshInvoice->unit_price, 2) ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="text-align:right;font-weight:700">TOTAL</td>
                <td style="font-weight:600"><?= number_format($freshInvoice->quantity, 2) ?></td>
                <td colspan="2"></td>
                <td class="price-cell" style="font-size:.95rem">$<?= number_format($freshInvoice->quantity * $freshInvoice->unit_price, 2) ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Amount Payable Section -->
    <div class="amount-section">
        <div class="amount-row">
            <span class="label">TOTAL VALUE:</span>
            <span class="value">$<?= number_format($freshInvoice->quantity * $freshInvoice->unit_price, 2) ?></span>
        </div>
        <div class="amount-row" style="font-size:1.05rem;margin-top:.5rem">
            <span class="label">AMOUNT PAYABLE @<?= number_format($freshInvoice->payment_percentage, 0) ?>%</span>
            <span class="value" style="font-size:1.1rem">$<?= number_format($freshInvoice->total_value, 2) ?></span>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="payment-details">
        <strong>Please Remit To:</strong><br>
        <?php if ($freshInvoice->has('sgc_account')): ?>
            <strong>Beneficiary Bank:</strong> <?= h($freshInvoice->sgc_account->bank_name ?? 'THE ACCESS BANK UK LIMITED') ?><br>
            <strong>Swift Code:</strong> <?= h($freshInvoice->sgc_account->swift_code ?? 'ABNGGB2L') ?><br>
            <strong>Beneficiary Name:</strong> <?= h($freshInvoice->sgc_account->account_name) ?><br>
            <strong>Currency:</strong> <?= h($freshInvoice->sgc_account->currency) ?><br>
            <strong>Account No:</strong> <?= h($freshInvoice->sgc_account->account_id) ?><br>
            <?php if (isset($freshInvoice->sgc_account->iban)): ?>
                <strong>IBAN:</strong> <?= h($freshInvoice->sgc_account->iban) ?><br>
            <?php endif; ?>
            <?php if (isset($freshInvoice->sgc_account->intermediary_bank)): ?>
                <strong>Intermediary Bank:</strong> <?= h($freshInvoice->sgc_account->intermediary_bank) ?><br>
            <?php endif; ?>
            <?php if (isset($freshInvoice->sgc_account->swift_bic)): ?>
                <strong>Swift BIC Code:</strong> <?= h($freshInvoice->sgc_account->swift_bic) ?><br>
            <?php endif; ?>
            <?php if (isset($freshInvoice->sgc_account->fedwire_aba)): ?>
                <strong>FEDWIRE/ABA:</strong> <?= h($freshInvoice->sgc_account->fedwire_aba) ?><br>
            <?php endif; ?>
        <?php endif; ?>
        <strong>Purpose:</strong> 
        <span id="purpose-display"><?= h($freshInvoice->notes ?: 'Cocoa export proceeds') ?></span>
        <button type="button" class="btn btn-sm no-print" 
                style="padding:.25rem .5rem;font-size:.8rem;margin-left:.5rem;background:#ff5722;color:#fff;border:none;border-radius:4px;cursor:pointer"
                data-bs-toggle="modal" data-bs-target="#editPurposeModal">
            <i class="fas fa-edit"></i> Edit
        </button>
    </div>

    <!-- Action Buttons (Print Hidden) -->
    <div class="action-buttons no-print">
        <?php if ($freshInvoice->status === 'draft'): ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-paper-plane"></i> Submit for Approval',
                ['action' => 'submitForApproval', $freshInvoice->id],
                [
                    'class' => 'btn btn-primary btn-lg js-swal-post',
                    'data-swal-title' => 'Submit for Approval?',
                    'data-swal-text' => 'Send this invoice to the Treasurer for approval.',
                    'escape' => false
                ]
            ) ?>
        <?php endif; ?>

        <?php 
            $currentRole = $authUser['role'] ?? ($this->request->getSession()->read('Auth.User.role') ?? null);
            if ($freshInvoice->status === 'pending_treasurer_approval' && $currentRole === 'treasurer'): 
        ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-check"></i> Approve Invoice',
                ['action' => 'treasurerApprove', $freshInvoice->id],
                [
                    'class' => 'btn btn-success btn-lg js-swal-post',
                    'data-swal-title' => 'Approve Invoice?',
                    'data-swal-text' => 'This will approve the invoice.',
                    'escape' => false
                ]
            ) ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-times"></i> Reject Invoice',
                ['action' => 'treasurerReject', $freshInvoice->id],
                [
                    'class' => 'btn btn-danger btn-lg js-swal-post',
                    'data-swal-title' => 'Reject Invoice?',
                    'data-swal-text' => 'This will reject the invoice.',
                    'data-require-comment' => '1',
                    'data-comment-name' => 'treasurer_comments',
                    'data-comment-title' => 'Rejection reason (required)',
                    'data-comment-placeholder' => 'Enter the reason for rejection...',
                    'escape' => false
                ]
            ) ?>
        <?php endif; ?>
        
        <?php if ($freshInvoice->status === 'approved'): ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-share"></i> Send to Export Team',
                ['action' => 'sendToExport', $freshInvoice->id],
                [
                    'class' => 'btn btn-primary btn-lg js-swal-post',
                    'data-swal-title' => 'Send to Export Team?',
                    'data-swal-text' => 'This will forward the invoice to the Export team.',
                    'escape' => false
                ]
            ) ?>
        <?php endif; ?>
    </div>
</div>

<!-- Status Badge (Print Hidden) -->
<?php if ($freshInvoice->status !== 'draft'): ?>
<div class="no-print" style="max-width:900px;margin:1rem auto;padding:1rem;background:#f9fafb;border-radius:8px">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1rem">
        <div>
            <strong>Invoice Status:</strong><br>
            <span class="status-badge status-<?= $freshInvoice->status === 'approved' ? 'success' : ($freshInvoice->status === 'rejected' ? 'danger' : 'warning') ?>" style="font-size:1rem;margin-top:.5rem">
                <?= h(ucfirst(str_replace('_', ' ', $freshInvoice->status))) ?>
            </span>
        </div>
        <div>
            <strong>Treasurer Approval:</strong><br>
            <span class="status-badge status-<?= $freshInvoice->treasurer_approval_status === 'approved' ? 'success' : ($freshInvoice->treasurer_approval_status === 'rejected' ? 'danger' : 'info') ?>" style="font-size:1rem;margin-top:.5rem">
                <?= h(ucfirst($freshInvoice->treasurer_approval_status)) ?>
            </span>
        </div>
        <?php if ($freshInvoice->treasurer_approval_date): ?>
        <div>
            <strong>Approval Date:</strong><br>
            <?= h($freshInvoice->treasurer_approval_date->format('M d, Y')) ?>
        </div>
        <?php endif; ?>
        <?php if ($freshInvoice->sent_to_export_date): ?>
        <div>
            <strong>Sent to Export:</strong><br>
            <?= h($freshInvoice->sent_to_export_date->format('M d, Y')) ?>
        </div>
        <?php endif; ?>
    </div>
    <?php if ($freshInvoice->treasurer_comments): ?>
    <div style="margin-top:1rem;padding:1rem;background:#fff;border-left:4px solid var(--warning);border-radius:4px">
        <strong>Treasurer Comments:</strong><br>
        <?= h($freshInvoice->treasurer_comments) ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Edit Purpose Modal -->
<div class="modal fade" id="editPurposeModal" tabindex="-1" aria-labelledby="editPurposeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg, #0c5343 0%, #083d2f 100%);color:#fff">
                <h5 class="modal-title" id="editPurposeModalLabel">
                    <i class="fas fa-edit"></i> Edit Purpose
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= $this->Form->create($freshInvoice, [
                'url' => ['action' => 'updatePurpose', $freshInvoice->id],
                'id' => 'purposeForm'
            ]) ?>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="purposeTextarea" class="form-label" style="font-weight:600;color:#374151">
                        Purpose of Payment:
                    </label>
                    <?= $this->Form->textarea('notes', [
                        'id' => 'purposeTextarea',
                        'class' => 'form-control',
                        'rows' => 4,
                        'placeholder' => 'Enter purpose of payment (e.g., Cocoa export proceeds)',
                        'style' => 'font-size:.9rem;border:1px solid #d1d5db;border-radius:6px;color:#374151',
                        'value' => $freshInvoice->notes,
                        'maxlength' => 500
                    ]) ?>
                    <small class="text-muted">Maximum 500 characters</small>
                </div>
            </div>
            <div class="modal-footer" style="background:#f9fafb">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <?= $this->Form->button('<i class="fas fa-check"></i> Save Purpose', [
                    'class' => 'btn',
                    'style' => 'background:#0c5343;color:#fff',
                    'escapeTitle' => false
                ]) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle JS (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
