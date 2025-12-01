<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Vessel $vessel
 */
$this->assign('title', 'Edit Vessel');
?>

<style>
.page-header-sleek{background:linear-gradient(135deg,#0c5343 0%,#083d2f 100%);padding:1.25rem 1.5rem;border-radius:8px;margin-bottom:1.25rem;color:#fff;box-shadow:0 2px 8px rgba(12,83,67,.15)}
.page-header-sleek h1{margin:0;font-size:1.5rem;font-weight:700;display:flex;gap:.5rem;align-items:center}
.form-card{background:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.08);overflow:hidden}
.form-card-header{padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;background:linear-gradient(to right,#f9fafb,#fff)}
.form-card-header h2{margin:0;font-size:1.05rem;font-weight:700;color:#111827}
.form-card-body{padding:1.25rem}
.btn-group-sleek{display:flex;gap:.5rem}
</style>

<div class="page-header-sleek">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h1><i class="fas fa-ship"></i> Edit Vessel</h1>
    <div class="btn-group-sleek">
      <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back', ['action' => 'index'], ['class' => 'btn btn-light','escape' => false]) ?>
      <?= $this->Html->link('<i class="fas fa-eye"></i> View', ['action' => 'view', $vessel->id], ['class' => 'btn btn-primary','escape' => false]) ?>
    </div>
  </div>
</div>

<div class="form-card">
  <div class="form-card-header"><h2><i class="fas fa-edit"></i> Details</h2></div>
  <div class="form-card-body">
    <?= $this->Form->create($vessel) ?>
    <div class="row g-3">
      <div class="col-md-6"><?= $this->Form->control('name', ['class' => 'form-control', 'label' => 'Vessel Name']) ?></div>
      <div class="col-md-6"><?= $this->Form->control('flag_country', ['class' => 'form-control', 'label' => 'Flag Country']) ?></div>
      <div class="col-md-6"><?= $this->Form->control('dwt', ['class' => 'form-control', 'label' => 'DWT (tons)']) ?></div>
    </div>
    <div class="mt-3">
      <?= $this->Form->button('<i class="fas fa-save"></i> Update', ['class' => 'btn btn-success','escapeTitle' => false]) ?>
    </div>
    <?= $this->Form->end() ?>
  </div>
</div>
