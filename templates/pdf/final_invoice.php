<?php
/**
 * Static PDF template for Final Invoice
 * Variables: $finalInvoice, $settings
 */
?>
<style>
.invoice-document{max-width:900px;margin:0 auto;background:#fff;font-family:Arial,sans-serif}
.company-header{display:flex;justify-content:space-between;align-items:flex-start;padding:1rem 0 0.5rem;border-bottom:4px solid #ff5722}
.company-info{text-align:right;font-size:.85rem;line-height:1.6;color:#374151}
.invoice-date{padding:.5rem 0;text-align:left;font-size:.9rem;font-weight:600;color:#374151}
.client-info{padding:.5rem 0;font-size:.9rem;line-height:1.6}
.invoice-title{text-align:center;font-size:1.3rem;font-weight:700;padding:.5rem 0;text-decoration:underline;margin:.5rem 0}
.invoice-table{width:100%;border-collapse:collapse;margin:.5rem 0}
.invoice-table th,.invoice-table td{border:2px solid #0c5343;padding:.5rem;text-align:center;font-size:.8rem}
.invoice-table th{background:#0c5343;color:#fff;font-weight:700;text-transform:uppercase}
.invoice-table .desc-cell{text-align:left;font-weight:600}
.invoice-table .price-cell{text-align:right;font-weight:600}
.invoice-table .total-row td{font-weight:700;background:#f5f7f9}
.amount-section{text-align:right}
.amount-row{display:flex;justify-content:flex-end;padding:.15rem 0;font-size:.9rem}
.amount-row .label{margin-right:1.5rem;font-weight:600}
.amount-row .value{font-weight:700;min-width:130px;text-align:right}
.payment-details{font-size:.85rem;line-height:1.6;margin-top:.5rem}
.logo-box{width:180px;height:56px;background:#ff5722;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700}
</style>

<div class="invoice-document">
  <div class="company-header">
    <div>
      <?php if (!empty($logoDataUri)): ?>
        <img src="<?= h($logoDataUri) ?>" alt="Company Logo" style="max-height:60px" />
      <?php else: ?>
        <div class="logo-box">SUNBETH LOGO</div>
      <?php endif; ?>
    </div>
    <div class="company-info">
      <strong>Email:</strong> <?= h($settings->email ?? 'info@sunbeth.net') ?><br>
      <strong>Telephone:</strong> <?= h($settings->telephone ?? '+234(0)805 6666 266') ?><br>
      <strong>Corporate Office:</strong> First Floor, Churchgate Towers 2,<br>
      Victoria Island, Lagos State, Nigeria.
    </div>
  </div>

  <div class="invoice-date"><strong><?= $finalInvoice->invoice_date ? $finalInvoice->invoice_date->format('jS F, Y') : date('jS F, Y') ?></strong></div>

  <div class="client-info">
    <?php $fresh = $finalInvoice->fresh_invoice ?? null; ?>
    <strong><?= h($fresh->client->name ?? 'CLIENT NAME') ?></strong><br>
    <?php if ($fresh && !empty($fresh->client->address)): ?><?= h($fresh->client->address) ?><br><?php endif; ?>
    <?php if ($fresh && !empty($fresh->client->city)): ?><?= h($fresh->client->city) ?><br><?php endif; ?>
    <?php if ($fresh && !empty($fresh->client->phone)): ?><?= h($fresh->client->phone) ?><?php endif; ?>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin:.25rem 0">
    <div style="font-size:.9rem"><strong>Vessel:</strong> <?= h($finalInvoice->vessel_name ?: ($fresh->vessel->name ?? $fresh->vessel_name ?? 'N/A')) ?> - <?= h($fresh->contract->contract_id ?? 'N/A') ?></div>
    <div style="text-align:right;font-size:.9rem">
      <strong>BL No.</strong> <?= h($finalInvoice->bl_number ?: ($fresh->bl_number ?? '')) ?><br>
      <strong>Invoice No:</strong> <?= h($finalInvoice->invoice_number) ?>
    </div>
  </div>

  <div class="invoice-title">INVOICE</div>

  <table class="invoice-table">
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
        <?php $totalValue = ($finalInvoice->landed_quantity ?? 0) * ($finalInvoice->unit_price ?? 0); ?>
        <td class="price-cell">$<?= number_format($totalValue, 2) ?></td>
      </tr>
      <tr class="total-row">
        <td colspan="2" style="text-align:right;font-weight:700">TOTAL</td>
        <td style="font-weight:600"><?= number_format($finalInvoice->landed_quantity, 2) ?></td>
        <td colspan="2"></td>
        <td class="price-cell" style="font-size:.9rem">$<?= number_format($totalValue, 2) ?></td>
      </tr>
    </tbody>
  </table>

  <?php $amountPaid = $finalInvoice->amount_paid ?? 0; $amountDue = $totalValue - $amountPaid; ?>
  <div class="amount-section">
    <div class="amount-row"><span class="label">TOTAL VALUE:</span><span class="value">$<?= number_format($totalValue, 2) ?></span></div>
    <div class="amount-row" style="margin-top:.25rem"><span class="label">LESS AMOUNT PAID :</span><span class="value">$<?= number_format($amountPaid, 2) ?></span></div>
    <div class="amount-row" style="margin-top:.25rem;border-top:2px solid #111827;padding-top:.25rem"><span class="label" style="font-weight:700">AMOUNT DUE :</span><span class="value">$<?= number_format($amountDue, 2) ?></span></div>
  </div>

  <div class="payment-details">
    <strong>Please Remit To:</strong><br>
    <?php if ($finalInvoice->has('sgc_account')): ?>
      <strong>Beneficiary Bank:</strong> <?= h($finalInvoice->sgc_account->bank_name ?? 'THE ACCESS BANK UK LIMITED') ?><br>
      <strong>Swift Code:</strong> <?= h($finalInvoice->sgc_account->swift_code ?? 'ABNGGB2L') ?><br>
      <strong>Beneficiary Name:</strong> <?= h($finalInvoice->sgc_account->account_name) ?><br>
      <strong>Currency:</strong> <?= h($finalInvoice->sgc_account->currency) ?><br>
      <strong>Account No:</strong> <?= h($finalInvoice->sgc_account->account_id) ?><br>
      <?php if (!empty($finalInvoice->sgc_account->iban)): ?><strong>IBAN:</strong> <?= h($finalInvoice->sgc_account->iban) ?><br><?php endif; ?>
    <?php endif; ?>
    <strong>Purpose:</strong> <?= h($finalInvoice->notes ?? 'Cocoa export proceeds - Final invoice based on landed quantity (CWT)') ?>
  </div>
</div>
