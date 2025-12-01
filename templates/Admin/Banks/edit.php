<?php
/** @var \App\View\AppView $this */
/** @var \App\Model\Entity\Bank $bank */
$this->assign('title', 'Edit Bank');
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
</style>

<div class="page-header-sleek">
  <div>
    <h1 class="page-title-sleek"><i class="fas fa-landmark"></i> Edit Bank</h1>
    <p class="page-subtitle-sleek">Update bank information</p>
  </div>
  <div>
    <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back', ['action'=>'index'], ['class'=>'btn btn-outline-secondary','escape'=>false]) ?>
  </div>
</div>

<div class="data-card-sleek">
  <div class="card-toolbar-sleek">
    <h3 class="toolbar-title-sleek"><i class="fas fa-pen"></i> Bank Details</h3>
  </div>
  <div class="card-body-sleek">
    <?= $this->Form->create($bank) ?>
      <div class="row g-3">
        <div class="col-md-6">
          <?= $this->Form->control('bank_name', ['label'=>'Bank Name','class'=>'form-control']) ?>
        </div>
        <div class="col-md-3">
          <?= $this->Form->control('bank_type', [
              'label'=>'Type',
              'options'=>['sales'=>'Sales','sustainability'=>'Sustainability','shipment'=>'Shipment','both'=>'Both'],
              'class'=>'form-select'
          ]) ?>
        </div>
        <div class="col-md-3">
          <?= $this->Form->control('currency', ['label'=>'Currency','class'=>'form-control']) ?>
        </div>
        <div class="col-md-6">
          <?= $this->Form->control('account_number', ['label'=>'Account Number','class'=>'form-control']) ?>
        </div>
        <div class="col-md-6">
          <?= $this->Form->control('swift_code', ['label'=>'SWIFT Code','class'=>'form-control']) ?>
        </div>
        <div class="col-md-12">
          <?= $this->Form->control('bank_address', ['label'=>'Bank Address','type'=>'textarea','rows'=>2,'class'=>'form-control']) ?>
        </div>
        <div class="col-md-12">
          <?= $this->Form->control('notes', ['label'=>'Notes','type'=>'textarea','rows'=>2,'class'=>'form-control']) ?>
        </div>
        <div class="col-md-3">
          <label class="form-label">Active</label><br>
          <?= $this->Form->control('is_active', ['type'=>'checkbox','label'=>false]) ?>
        </div>
      </div>
      <div class="mt-3">
        <?= $this->Form->button('<i class="fas fa-save"></i> Update Bank', ['class'=>'btn btn-success', 'escapeTitle'=>false]) ?>
      </div>
    <?= $this->Form->end() ?>
  </div>
</div>
