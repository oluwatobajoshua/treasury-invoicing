<?php
/**
 * Static PDF template for Fresh Invoice
 * Variables: $freshInvoice, $settings
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

  <div class="invoice-date"><strong><?= $freshInvoice->invoice_date ? $freshInvoice->invoice_date->format('jS F, Y') : date('jS F, Y') ?></strong></div>

  <div class="client-info">
    <strong><?= h($freshInvoice->client->name ?? 'CLIENT NAME') ?></strong><br>
    <?php if (!empty($freshInvoice->client->address)): ?><?= h($freshInvoice->client->address) ?><br><?php endif; ?>
    <?php if (!empty($freshInvoice->client->city)): ?><?= h($freshInvoice->client->city) ?><br><?php endif; ?>
    <?php if (!empty($freshInvoice->client->phone)): ?><?= h($freshInvoice->client->phone) ?><?php endif; ?>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin:.25rem 0">
    <div style="font-size:.9rem"><strong>Vessel:</strong> <?= h($freshInvoice->vessel->name ?? $freshInvoice->vessel_name ?? 'N/A') ?> - <?= h($freshInvoice->contract->contract_id ?? 'N/A') ?></div>
    <div style="text-align:right;font-size:.9rem">
      <strong>BL No.</strong> <?= h($freshInvoice->bl_number) ?><br>
      <strong>Invoice No:</strong> <?= h($freshInvoice->invoice_number) ?>
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
        <td class="price-cell" style="font-size:.9rem">$<?= number_format($freshInvoice->quantity * $freshInvoice->unit_price, 2) ?></td>
      </tr>
    </tbody>
  </table>

  <div class="amount-section">
    <div class="amount-row">
      <span class="label">TOTAL VALUE:</span>
      <span class="value">$<?= number_format($freshInvoice->quantity * $freshInvoice->unit_price, 2) ?></span>
    </div>
    <div class="amount-row" style="margin-top:.25rem">
      <span class="label">AMOUNT PAYABLE @<?= number_format($freshInvoice->payment_percentage, 0) ?>%</span>
      <span class="value">$<?= number_format($freshInvoice->total_value ?? ($freshInvoice->quantity * $freshInvoice->unit_price * ($freshInvoice->payment_percentage/100)), 2) ?></span>
    </div>
  </div>

  <div class="payment-details">
    <strong>Please Remit To:</strong><br>
    <?php if ($freshInvoice->has('sgc_account')): ?>
      <strong>Beneficiary Bank:</strong> <?= h($freshInvoice->sgc_account->bank_name ?? 'THE ACCESS BANK UK LIMITED') ?><br>
      <strong>Swift Code:</strong> <?= h($freshInvoice->sgc_account->swift_code ?? 'ABNGGB2L') ?><br>
      <strong>Beneficiary Name:</strong> <?= h($freshInvoice->sgc_account->account_name) ?><br>
      <strong>Currency:</strong> <?= h($freshInvoice->sgc_account->currency) ?><br>
      <strong>Account No:</strong> <?= h($freshInvoice->sgc_account->account_id) ?><br>
      <?php if (!empty($freshInvoice->sgc_account->iban)): ?>
        <strong>IBAN:</strong> <?= h($freshInvoice->sgc_account->iban) ?><br>
      <?php endif; ?>
    <?php endif; ?>
    <strong>Purpose:</strong> <?= h($freshInvoice->notes ?: 'Cocoa export proceeds') ?>
  </div>
</div>
