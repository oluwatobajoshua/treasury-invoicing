<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
$this->assign('title', 'Edit Client');
?>

<style>
.page-header-sleek{background:linear-gradient(135deg,#0c5343 0%,#083d2f 100%);padding:1.25rem 1.5rem;border-radius:8px;margin-bottom:1.25rem;color:#fff;box-shadow:0 2px 8px rgba(12,83,67,.15)}
.page-header-sleek h1{margin:0;font-size:1.5rem;font-weight:700;display:flex;gap:.5rem;align-items:center}
.form-card{background:#fff;border-radius:10px;box-shadow:0 1px 3px rgba(0,0,0,.1);overflow:hidden}
.form-card-header{background:linear-gradient(to bottom,#f9fafb,#f3f4f6);padding:1rem 1.25rem;border-bottom:2px solid #e5e7eb}
.form-card-header h2{margin:0;font-size:1.05rem;font-weight:700;color:#111827}
.form-card-body{padding:1.25rem}
.btn-group-sleek{display:flex;gap:.75rem;padding-top:1rem;border-top:1px solid #e5e7eb;margin-top:1rem}
.form-label{font-weight:600;color:#374151;margin-bottom:.5rem}
.form-control:focus{border-color:#0c5343;box-shadow:0 0 0 .25rem rgba(12,83,67,.25)}
</style>

<div class="page-header-sleek">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h1><i class="fas fa-user-edit"></i> Edit Client</h1>
    <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back', ['action' => 'index'], ['class' => 'btn btn-light','escape' => false]) ?>
  </div>
  <p class="page-subtitle-sleek" style="margin: .25rem 0 0; opacity: .9">Update the client record</p>
 </div>

<div class="form-card">
  <div class="form-card-header">
    <h2><i class="fas fa-id-card"></i> Client Details</h2>
  </div>
  <div class="form-card-body">
    <?= $this->Form->create($client, ['class' => 'needs-validation','novalidate' => true]) ?>
    <div class="row mb-3">
      <div class="col-md-6">
        <?= $this->Form->control('name', [
          'label' => ['text' => 'Client Name *', 'class' => 'form-label'],
          'class' => 'form-control',
          'required' => true
        ]) ?>
      </div>
      <div class="col-md-6">
        <?= $this->Form->control('email', [
          'label' => ['text' => 'Email', 'class' => 'form-label'],
          'class' => 'form-control'
        ]) ?>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-12">
        <?= $this->Form->control('address', [
          'label' => ['text' => 'Address', 'class' => 'form-label'],
          'class' => 'form-control',
          'type' => 'textarea',
          'rows' => 3
        ]) ?>
      </div>
    </div>

    <div class="btn-group-sleek">
      <?= $this->Form->button('<i class="fas fa-check"></i> Update Client', ['class' => 'btn btn-primary','escapeTitle' => false]) ?>
      <?= $this->Html->link('<i class="fas fa-times"></i> Cancel', ['action' => 'index'], ['class' => 'btn btn-secondary','escape' => false]) ?>
    </div>
    <?= $this->Form->end() ?>
  </div>
</div>
