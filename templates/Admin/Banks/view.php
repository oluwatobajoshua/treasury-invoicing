<?php
/** @var \App\View\AppView $this */
/** @var \App\Model\Entity\Bank $bank */
$this->assign('title', 'View Bank');
?>

<style>
:root{--primary:#0c5343;--primary-dark:#083d2f}
.page-header-sleek{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);padding:1.5rem 1.75rem;border-radius:8px;box-shadow:0 2px 8px rgba(12,83,67,.15);margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.page-title-sleek{color:#fff;font-size:1.5rem;font-weight:600;margin:0;display:flex;align-items:center;gap:.75rem}
.page-subtitle-sleek{color:rgba(255,255,255,.85);font-size:.875rem;margin:.25rem 0 0 0}
.data-card-sleek{background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1);overflow:hidden}
.card-toolbar-sleek{background:#f9fafb;padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;gap:.75rem;flex-wrap:wrap}
.toolbar-title-sleek{font-size:1rem;font-weight:600;color:#1f2937;margin:0;display:flex;align-items:center;gap:.5rem}
.card-body-sleek{padding:1.25rem}
.label{color:#6b7280;font-size:.875rem}
.value{font-weight:600;color:#111827}
</style>

<div class="page-header-sleek">
  <div>
    <h1 class="page-title-sleek"><i class="fas fa-landmark"></i> <?= h($bank->bank_name) ?></h1>
    <p class="page-subtitle-sleek">Bank details</p>
  </div>
  <div>
    <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back', ['action'=>'index'], ['class'=>'btn btn-outline-secondary','escape'=>false]) ?>
    <?= $this->Html->link('<i class="fas fa-edit"></i> Edit', ['action'=>'edit', $bank->id], ['class'=>'btn btn-primary','escape'=>false]) ?>
  </div>
</div>

<div class="data-card-sleek">
  <div class="card-toolbar-sleek">
    <h3 class="toolbar-title-sleek"><i class="fas fa-info-circle"></i> Details</h3>
  </div>
  <div class="card-body-sleek">
    <div class="row g-3">
      <div class="col-md-6">
        <div class="label">Type</div>
        <div class="value"><?= h(ucfirst((string)$bank->bank_type)) ?></div>
      </div>
      <div class="col-md-3">
        <div class="label">Currency</div>
        <div class="value"><?= h((string)$bank->currency) ?></div>
      </div>
      <div class="col-md-3">
        <div class="label">Active</div>
        <div class="value"><?= $bank->is_active ? 'Yes' : 'No' ?></div>
      </div>
      <div class="col-md-6">
        <div class="label">Account Number</div>
        <div class="value"><?= h((string)$bank->account_number ?: '—') ?></div>
      </div>
      <div class="col-md-6">
        <div class="label">SWIFT Code</div>
        <div class="value"><?= h((string)$bank->swift_code ?: '—') ?></div>
      </div>
      <div class="col-md-12">
        <div class="label">Bank Address</div>
        <div class="value"><?= nl2br(h((string)$bank->bank_address ?: '—')) ?></div>
      </div>
      <div class="col-md-12">
        <div class="label">Notes</div>
        <div class="value"><?= nl2br(h((string)$bank->notes ?: '—')) ?></div>
      </div>
    </div>
  </div>
</div>
