<?php
/**
 * Inline-styled Fresh Invoice HTML for email body
 * Vars: $freshInvoice, $settings, $logoDataUri
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
    <div style="font-size:13px;font-weight:600;margin-bottom:6px;">Invoice Date: <span style="font-weight:700;"><?= $freshInvoice->invoice_date ? $freshInvoice->invoice_date->format('jS F, Y') : date('jS F, Y') ?></span></div>

    <div style="margin:8px 0 12px;color:#111827">
      <div style="font-size:14px;font-weight:700;">Bill To:</div>
      <div style="font-size:13px;line-height:1.5">
        <?= h($freshInvoice->client->name ?? 'CLIENT NAME') ?><br/>
        <?php if (!empty($freshInvoice->client->address)): ?><?= h($freshInvoice->client->address) ?><br/><?php endif; ?>
        <?php if (!empty($freshInvoice->client->city)): ?><?= h($freshInvoice->client->city) ?><br/><?php endif; ?>
        <?php if (!empty($freshInvoice->client->phone)): ?><?= h($freshInvoice->client->phone) ?><?php endif; ?>
      </div>
    </div>

    <div style="display:flex;justify-content:space-between;gap:12px;margin-bottom:10px;font-size:13px">
      <div><strong>Vessel:</strong> <?= h($freshInvoice->vessel->name ?? $freshInvoice->vessel_name ?? 'N/A') ?> - <?= h($freshInvoice->contract->contract_id ?? 'N/A') ?></div>
      <div style="text-align:right">
        <div><strong>BL No.</strong> <?= h($freshInvoice->bl_number) ?></div>
        <div><strong>Invoice No:</strong> <?= h($freshInvoice->invoice_number) ?></div>
      </div>
    </div>

    <div style="text-align:center;font-size:16px;font-weight:800;margin:6px 0 10px;text-decoration:underline;color:#111827">INVOICE</div>

    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;font-size:12px">
      <thead>
        <tr>
          <th style="border:2px solid #0c5343;background:#0c5343;color:#fff;padding:8px;text-transform:uppercase">Contract No</th>
          <th style="border:2px solid #0c5343;background:#0c5343;color:#fff;padding:8px;text-transform:uppercase">Qty (MT)</th>
          <th style="border:2px solid #0c5343;background:#0c5343;color:#fff;padding:8px;text-transform:uppercase;text-align:left">Description</th>
          <th style="border:2px solid #0c5343;background:#0c5343;color:#fff;padding:8px;text-transform:uppercase">Bulk</th>
          <th style="border:2px solid #0c5343;background:#0c5343;color:#fff;padding:8px;text-transform:uppercase">Price ($)</th>
          <th style="border:2px solid #0c5343;background:#0c5343;color:#fff;padding:8px;text-transform:uppercase">Total Value ($)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="border:2px solid #0c5343;padding:8px;font-weight:700;text-align:center"><?= h($freshInvoice->contract->contract_id ?? 'N/A') ?></td>
          <td style="border:2px solid #0c5343;padding:8px;font-weight:700;text-align:center"><?= number_format($freshInvoice->quantity, 2) ?></td>
          <td style="border:2px solid #0c5343;padding:8px;font-weight:600;text-align:left"><?= h($freshInvoice->product->name ?? 'Product') ?></td>
          <td style="border:2px solid #0c5343;padding:8px;font-weight:700;text-align:center"><?= h($freshInvoice->bulk_or_bag) ?></td>
          <td style="border:2px solid #0c5343;padding:8px;font-weight:700;text-align:center">$<?= number_format($freshInvoice->unit_price, 2) ?></td>
          <td style="border:2px solid #0c5343;padding:8px;font-weight:700;text-align:center">$<?= number_format($freshInvoice->quantity * $freshInvoice->unit_price, 2) ?></td>
        </tr>
        <tr>
          <td colspan="2" style="border:2px solid #0c5343;padding:8px;text-align:right;font-weight:700;background:#f8fafc">TOTAL</td>
          <td style="border:2px solid #0c5343;padding:8px;font-weight:700;text-align:center"><?= number_format($freshInvoice->quantity, 2) ?></td>
          <td colspan="2" style="border:2px solid #0c5343;padding:8px"></td>
          <td style="border:2px solid #0c5343;padding:8px;font-weight:700;text-align:center">$<?= number_format($freshInvoice->quantity * $freshInvoice->unit_price, 2) ?></td>
        </tr>
      </tbody>
    </table>

    <div style="margin-top:10px;color:#111827">
      <div style="display:flex;justify-content:flex-end;gap:24px;font-size:13px">
        <div><strong>Total Value:</strong></div>
        <div style="min-width:140px;text-align:right;font-weight:800">$<?= number_format($freshInvoice->quantity * $freshInvoice->unit_price, 2) ?></div>
      </div>
      <div style="display:flex;justify-content:flex-end;gap:24px;font-size:13px;margin-top:6px">
        <div><strong>Amount Payable @<?= number_format($freshInvoice->payment_percentage, 0) ?>%:</strong></div>
        <div style="min-width:140px;text-align:right;font-weight:800">$<?= number_format($freshInvoice->total_value ?? ($freshInvoice->quantity * $freshInvoice->unit_price * ($freshInvoice->payment_percentage/100)), 2) ?></div>
      </div>
    </div>

    <div style="margin-top:12px;font-size:12px;color:#111827;line-height:1.6">
      <div style="font-weight:700;margin-bottom:4px">Please Remit To:</div>
      <?php if ($freshInvoice->has('sgc_account')): ?>
        <?php
          $benefBank = $freshInvoice->sgc_account->bank_name ?? null;
          $swiftCode = $freshInvoice->sgc_account->swift_code ?? null;
        ?>
        <div><strong>Beneficiary Bank:</strong> <?= h($benefBank ?: 'THE ACCESS BANK UK LIMITED') ?></div>
        <div><strong>Swift Code:</strong> <?= h($swiftCode ?: 'ABNGGB2L') ?></div>
        <div><strong>Beneficiary Name:</strong> <?= h($freshInvoice->sgc_account->account_name ?? '') ?></div>
        <div><strong>Currency:</strong> <?= h($freshInvoice->sgc_account->currency ?? '') ?></div>
        <div><strong>Account No:</strong> <?= h($freshInvoice->sgc_account->account_id ?? '') ?></div>
        <?php if (!empty($freshInvoice->sgc_account->iban)): ?>
          <div><strong>IBAN:</strong> <?= h($freshInvoice->sgc_account->iban) ?></div>
        <?php endif; ?>
      <?php endif; ?>
      <div><strong>Purpose:</strong> <?= h($freshInvoice->notes ?: 'Cocoa export proceeds') ?></div>
    </div>
  </div>
</div>