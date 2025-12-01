<?php
/** @var \App\View\AppView $this */
/** @var \App\Model\Entity\SgcAccount $sgcAccount */
$this->assign('title', 'SGC Account Details');
?>
<style>
.page-header-sleek{background:linear-gradient(135deg,#0c5343 0%,#083d2f 100%);padding:1.1rem 1.4rem;border-radius:8px;margin-bottom:1.1rem;color:#fff;box-shadow:0 2px 8px rgba(12,83,67,.15)}
.page-header-sleek h1{margin:0;font-size:1.4rem;font-weight:700;display:flex;align-items:center;gap:.55rem}
.data-card{background:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.08);overflow:hidden}
.data-card-header{padding:.9rem 1.15rem;border-bottom:1px solid #e5e7eb;background:linear-gradient(to right,#f9fafb,#fff)}
.data-card-header h2{margin:0;font-size:1rem;font-weight:700;color:#111827}
.data-card-body{padding:1.1rem}
.detail-row{display:grid;grid-template-columns:200px 1fr;gap:.75rem;padding:.7rem 0;border-bottom:1px dashed #e5e7eb}
.detail-row:last-child{border-bottom:0}
.detail-label{font-weight:600;color:#374151}
.btn-group-sleek{display:flex;gap:.5rem}
</style>

<div class="page-header-sleek">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h1><i class="fas fa-wallet"></i> SGC Account</h1>
    <div class="btn-group-sleek">
      <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back', ['action' => 'index'], ['class' => 'btn btn-light','escape' => false]) ?>
      <?= $this->Html->link('<i class="fas fa-edit"></i> Edit', ['action' => 'edit', $sgcAccount->id], ['class' => 'btn btn-warning','escape' => false]) ?>
      <?= $this->Form->postLink('<i class="fas fa-trash"></i> Delete', ['action' => 'delete', $sgcAccount->id], [
          'class' => 'btn btn-danger js-swal-post','escape' => false,
          'data-swal-title' => 'Delete this SGC account?','data-swal-text' => 'This action cannot be undone.'
      ]) ?>
    </div>
  </div>
</div>

<div class="data-card">
  <div class="data-card-header"><h2><i class="fas fa-id-card"></i> Details</h2></div>
  <div class="data-card-body">
    <div class="detail-row"><div class="detail-label">Account ID</div><div><?= h($sgcAccount->account_id) ?></div></div>
    <div class="detail-row"><div class="detail-label">Account Name</div><div><?= h($sgcAccount->account_name) ?></div></div>
    <div class="detail-row"><div class="detail-label">Currency</div><div><?= h($sgcAccount->currency) ?></div></div>
    <div class="detail-row"><div class="detail-label">Created</div><div><?= $sgcAccount->created ? $sgcAccount->created->format('M d, Y H:i') : '' ?></div></div>
    <div class="detail-row"><div class="detail-label">Modified</div><div><?= $sgcAccount->modified ? $sgcAccount->modified->format('M d, Y H:i') : '' ?></div></div>
  </div>
</div>
