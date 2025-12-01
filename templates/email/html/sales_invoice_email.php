<?php
/**
 * Inline-styled Sales Invoice for email body
 * Vars: $invoice, $settings, $logoDataUri
 */
?>
<div style="max-width:760px;margin:0 auto;background:#ffffff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden">
  <div style="padding:16px 20px;border-bottom:4px solid #ff5722;display:flex;justify-content:space-between;align-items:flex-start">
    <div>
      <?php if (!empty($logoDataUri)): ?>
        <img src="<?= h($logoDataUri) ?>" alt="Company Logo" style="max-height:56px;display:block"/>
      <?php else: ?>
        <div style="width:160px;height:48px;background:#ff5722;color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;border-radius:6px">LOGO</div>
      <?php endif; ?>
    </div>
    <div style="text-align:right;color:#374151;font-size:12px;line-height:1.5">
      <div><strong>Email:</strong> <?= h($settings->email ?? 'info@example.com') ?></div>
      <div><strong>Telephone:</strong> <?= h($settings->telephone ?? '') ?></div>
      <div><strong>Address:</strong> <?= h($settings->corporate_address ?? '') ?></div>
    </div>
  </div>

  <div style="padding:14px 20px;color:#111827;font-family:Arial,Helvetica,sans-serif">
    <div style="font-size:13px;font-weight:600;margin-bottom:6px;">Invoice Date: <span style="font-weight:700;"><?= $invoice->invoice_date ? $invoice->invoice_date->format('jS F, Y') : date('jS F, Y') ?></span></div>

    <div style="margin:8px 0 12px;color:#111827">
      <div style="font-size:14px;font-weight:700;">Bill To:</div>
      <div style="font-size:13px;line-height:1.5">
        <?= h($invoice->client->name ?? 'CLIENT NAME') ?>
      </div>
    </div>

    <div style="display:flex;justify-content:space-between;gap:12px;margin-bottom:10px;font-size:13px">
      <div><strong>Description:</strong> <?= h($invoice->description ?? '-') ?></div>
      <div style="text-align:right">
        <div><strong>Invoice No:</strong> <?= h($invoice->invoice_number) ?></div>
        <div><strong>Currency:</strong> <?= h($invoice->currency ?? 'NGN') ?></div>
      </div>
    </div>

    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;font-size:12px">
      <thead>
        <tr>
          <th style="border:2px solid #0c5343;background:#0c5343;color:#fff;padding:8px;text-transform:uppercase">Qty</th>
          <th style="border:2px solid #0c5343;background:#0c5343;color:#fff;padding:8px;text-transform:uppercase">Unit Price</th>
          <th style="border:2px solid #0c5343;background:#0c5343;color:#fff;padding:8px;text-transform:uppercase">Total Value</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="border:2px solid #0c5343;padding:8px;font-weight:700;text-align:center"><?= $this->Number->format($invoice->quantity ?? 0, ['places' => 2]) ?></td>
          <td style="border:2px solid #0c5343;padding:8px;font-weight:700;text-align:center"><?= $this->Number->currency($invoice->unit_price ?? 0, $invoice->currency ?? 'NGN') ?></td>
          <td style="border:2px solid #0c5343;padding:8px;font-weight:700;text-align:center"><?= $this->Number->currency($invoice->total_value ?? 0, $invoice->currency ?? 'NGN') ?></td>
        </tr>
      </tbody>
    </table>

    <div style="margin-top:12px;font-size:12px;color:#111827;line-height:1.6">
      <div style="font-weight:700;margin-bottom:4px">Payment Details:</div>
      <?php if ($invoice->has('bank')): ?>
        <div><strong>Bank:</strong> <?= h($invoice->bank->bank_name ?? '') ?></div>
        <div><strong>Account Name:</strong> <?= h($invoice->bank->account_name ?? '') ?></div>
        <div><strong>Account Number:</strong> <?= h($invoice->bank->account_number ?? '') ?></div>
        <div><strong>Currency:</strong> <?= h($invoice->bank->currency ?? '') ?></div>
      <?php endif; ?>
    </div>
  </div>
</div>