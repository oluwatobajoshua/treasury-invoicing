<?php
/**
 * PDF template for Sales Invoice
 * Vars: $invoice, $settings, $logoDataUri
 */
?>
<style>
.doc{max-width:900px;margin:0 auto;background:#fff;font-family:Arial,sans-serif}
.hdr{display:flex;justify-content:space-between;align-items:flex-start;padding:1rem 0 .5rem;border-bottom:4px solid #ff5722}
.info{text-align:right;font-size:.85rem;line-height:1.6;color:#374151}
.title{text-align:center;font-size:1.3rem;font-weight:700;padding:.5rem 0;text-decoration:underline;margin:.5rem 0}
.tbl{width:100%;border-collapse:collapse;margin:.5rem 0}
.tbl th,.tbl td{border:2px solid #0c5343;padding:.5rem;text-align:center;font-size:.85rem}
.tbl th{background:#0c5343;color:#fff;font-weight:700;text-transform:uppercase}
.right{display:flex;justify-content:flex-end;padding:.15rem 0;font-size:.9rem}
.right .lbl{margin-right:1.5rem;font-weight:600}
.right .val{font-weight:700;min-width:160px;text-align:right}
.logo{width:180px;height:56px;background:#ff5722;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700}
</style>

<div class="doc">
  <div class="hdr">
    <div>
      <?php if (!empty($logoDataUri)): ?>
        <img src="<?= h($logoDataUri) ?>" alt="Logo" style="max-height:60px"/>
      <?php else: ?>
        <div class="logo">LOGO</div>
      <?php endif; ?>
    </div>
    <div class="info">
      <strong>Email:</strong> <?= h($settings->email ?? '') ?><br>
      <strong>Telephone:</strong> <?= h($settings->telephone ?? '') ?><br>
      <strong>Address:</strong> <?= h($settings->corporate_address ?? '') ?>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin:.25rem 0">
    <div style="font-size:.9rem"><strong>Invoice No:</strong> <?= h($invoice->invoice_number) ?></div>
    <div style="text-align:right;font-size:.9rem"><strong>Date:</strong> <?= $invoice->invoice_date ? $invoice->invoice_date->format('jS F, Y') : date('jS F, Y') ?></div>
  </div>

  <div class="title">SALES INVOICE</div>

  <table class="tbl">
    <thead>
      <tr>
        <th>Description</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total Value</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="text-align:left;font-weight:600"><?= h($invoice->description ?? '-') ?></td>
        <td><?= number_format($invoice->quantity ?? 0, 2) ?></td>
        <td><?= h(($invoice->currency ?? 'NGN')) ?> <?= number_format($invoice->unit_price ?? 0, 2) ?></td>
        <td><?= h(($invoice->currency ?? 'NGN')) ?> <?= number_format($invoice->total_value ?? 0, 2) ?></td>
      </tr>
    </tbody>
  </table>

  <div class="right"><span class="lbl">TOTAL:</span><span class="val"><?= h(($invoice->currency ?? 'NGN')) ?> <?= number_format($invoice->total_value ?? 0, 2) ?></span></div>

  <div style="font-size:.85rem;line-height:1.6;margin-top:.5rem">
    <strong>Payment Details:</strong>
    <?php if ($invoice->has('bank')): ?>
      <strong>Bank:</strong> <?= h($invoice->bank->bank_name ?? '') ?>,
      <strong>Account:</strong> <?= h($invoice->bank->account_name ?? '') ?> (<?= h($invoice->bank->account_number ?? '') ?>),
      <strong>Currency:</strong> <?= h($invoice->bank->currency ?? '') ?>
    <?php endif; ?>
  </div>
</div>