<?php
/** @var \App\View\AppView $this */
/** @var \App\Model\Entity\SgcAccount $sgcAccount */
$this->assign('title', 'Add SGC Account');
?>
<style>
.page-header-sleek{background:linear-gradient(135deg,#0c5343 0%,#083d2f 100%);padding:1.1rem 1.4rem;border-radius:8px;margin-bottom:1.1rem;color:#fff;box-shadow:0 2px 8px rgba(12,83,67,.15)}
.page-header-sleek h1{margin:0;font-size:1.4rem;font-weight:700;display:flex;align-items:center;gap:.55rem}
.form-card{background:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.08);overflow:hidden}
.form-card-header{padding:.9rem 1.15rem;border-bottom:1px solid #e5e7eb;background:linear-gradient(to right,#f9fafb,#fff)}
.form-card-header h2{margin:0;font-size:1rem;font-weight:700;color:#111827}
.form-card-body{padding:1.1rem}
.btn-group-sleek{display:flex;gap:.5rem}
</style>

<div class="page-header-sleek">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h1><i class="fas fa-wallet"></i> Add SGC Account</h1>
    <div class="btn-group-sleek">
      <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back', ['action' => 'index'], ['class' => 'btn btn-light','escape' => false]) ?>
    </div>
  </div>
</div>

<div class="form-card">
  <div class="form-card-header"><h2><i class="fas fa-edit"></i> Details</h2></div>
  <div class="form-card-body">
    <?= $this->Form->create($sgcAccount) ?>
    <div class="row g-3">
      <div class="col-md-4"><?= $this->Form->control('account_id', ['class' => 'form-control', 'label' => 'Account ID']) ?></div>
      <div class="col-md-5"><?= $this->Form->control('account_name', ['class' => 'form-control', 'label' => 'Account Name']) ?></div>
      <div class="col-md-3"><?= $this->Form->control('currency', ['class' => 'form-control', 'label' => 'Currency']) ?></div>
    </div>
    <div class="mt-3">
      <?= $this->Form->button('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success','escapeTitle' => false]) ?>
    </div>
    <?= $this->Form->end() ?>
  </div>
</div>
