<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
$this->assign('title', 'Client Details');
?>

<style>
.page-header-sleek{background:linear-gradient(135deg,#0c5343 0%,#083d2f 100%);padding:1.25rem 1.5rem;border-radius:8px;margin-bottom:1.25rem;color:#fff;box-shadow:0 2px 8px rgba(12,83,67,.15)}
.page-header-sleek h1{margin:0;font-size:1.5rem;font-weight:700;display:flex;gap:.5rem;align-items:center}
.data-card{background:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.08);overflow:hidden}
.data-card-header{padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;background:linear-gradient(to right,#f9fafb,#fff)}
.data-card-header h2{margin:0;font-size:1.05rem;font-weight:700;color:#111827}
.data-card-body{padding:1.25rem}
.detail-row{display:grid;grid-template-columns:220px 1fr;gap:.75rem;padding:.75rem 0;border-bottom:1px dashed #e5e7eb}
.detail-row:last-child{border-bottom:0}
.detail-label{font-weight:600;color:#374151}
.btn-group-sleek{display:flex;gap:.5rem;margin-top:1rem}
</style>

<div class="page-header-sleek">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h1><i class="fas fa-user"></i> Client</h1>
    <div class="btn-group-sleek">
      <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back', ['action' => 'index'], ['class' => 'btn btn-light','escape' => false]) ?>
      <?= $this->Html->link('<i class="fas fa-edit"></i> Edit', ['action' => 'edit', $client->id], ['class' => 'btn btn-warning','escape' => false]) ?>
      <?= $this->Form->postLink('<i class="fas fa-trash"></i> Delete', ['action' => 'delete', $client->id], [
          'class' => 'btn btn-danger js-swal-post','escape' => false,
          'data-swal-title' => 'Delete this client?','data-swal-text' => 'This action cannot be undone.'
      ]) ?>
    </div>
  </div>
</div>

<div class="data-card">
  <div class="data-card-header"><h2><i class="fas fa-id-card"></i> Details</h2></div>
  <div class="data-card-body">
    <div class="detail-row">
      <div class="detail-label">Name</div>
      <div><?= h($client->name) ?></div>
    </div>
    <div class="detail-row">
      <div class="detail-label">Email</div>
      <div><?= h($client->email ?? '') ?></div>
    </div>
    <div class="detail-row">
      <div class="detail-label">Address</div>
      <div><?= nl2br(h($client->address ?? '')) ?></div>
    </div>
    <div class="detail-row">
      <div class="detail-label">Created</div>
      <div><?= $client->created ? $client->created->format('M d, Y H:i') : '' ?></div>
    </div>
    <div class="detail-row">
      <div class="detail-label">Modified</div>
      <div><?= $client->modified ? $client->modified->format('M d, Y H:i') : '' ?></div>
    </div>
  </div>
</div>
