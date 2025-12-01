<?php
/** @var \App\View\AppView $this */
$this->assign('title', 'Banks Management');
?>

<!-- Premium DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
:root { --primary:#0c5343; --primary-dark:#083d2f; --success:#10b981; }
.page-header-sleek{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);padding:1.5rem 1.75rem;border-radius:8px;box-shadow:0 2px 8px rgba(12,83,67,0.15);margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}
.page-title-sleek{color:#fff;font-size:1.5rem;font-weight:600;margin:0;display:flex;align-items:center;gap:.75rem}
.page-subtitle-sleek{color:rgba(255,255,255,.85);font-size:.875rem;margin:.25rem 0 0}
.data-card-sleek{background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1);overflow:hidden}
.card-toolbar-sleek{background:#f9fafb;padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;gap:.75rem;flex-wrap:wrap}
.toolbar-title-sleek{font-size:1rem;font-weight:600;color:#1f2937;margin:0;display:flex;align-items:center;gap:.5rem}
.btn-sleek{padding:.5rem 1rem;border-radius:6px;font-weight:500;font-size:.875rem;display:inline-flex;align-items:center;gap:.5rem;transition:all .2s;border:none;text-decoration:none;cursor:pointer}
.btn-sleek:hover{transform:translateY(-1px);box-shadow:0 2px 8px rgba(0,0,0,.15)}
.btn-success-sleek{background:var(--success);color:#fff !important}
.btn-outline-sleek{background:#fff;color:var(--primary);border:1px solid #e5e7eb}
.btn-outline-sleek:hover{background:#f9fafb;border-color:var(--primary)}
.card-body-sleek{padding:1.25rem}
table.dataTable thead th{background:#f9fafb;font-weight:600;text-transform:uppercase;font-size:.6875rem;letter-spacing:.5px;color:#6b7280;border-bottom:1px solid #e5e7eb;padding:.875rem .75rem}
.badge{padding:.25rem .625rem;border-radius:4px;font-weight:500;font-size:.75rem;display:inline-flex;align-items:center;gap:.375rem}
.badge-active{background:#d1fae5;color:#065f46}
.badge-inactive{background:#f3f4f6;color:#6b7280}
.btn-action{width:30px;height:30px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;transition:all .2s;border:none;cursor:pointer;text-decoration:none;color:#fff}
.btn-view{background:#3b82f6}.btn-edit{background:#ff5722}.btn-delete{background:#ef4444}
</style>

<div class="page-header-sleek">
  <div>
    <h1 class="page-title-sleek"><i class="fas fa-landmark"></i> Banks Management</h1>
    <p class="page-subtitle-sleek">Manage company bank details and beneficiaries</p>
  </div>
  <div>
    <?= $this->Html->link('<i class="fas fa-plus"></i> Add Bank', ['action'=>'add'], ['class'=>'btn-sleek btn-success-sleek','escape'=>false]) ?>
  </div>
</div>

<div class="data-card-sleek">
  <div class="card-toolbar-sleek">
    <h3 class="toolbar-title-sleek"><i class="fas fa-list"></i> Bank List</h3>
    <div class="toolbar-actions-sleek">
      <button class="btn-sleek btn-outline-sleek" onclick="$('#banksTable').DataTable().button('.buttons-excel').trigger();"><i class="fas fa-file-excel"></i> Excel</button>
      <button class="btn-sleek btn-outline-sleek" onclick="$('#banksTable').DataTable().button('.buttons-pdf').trigger();"><i class="fas fa-file-pdf"></i> PDF</button>
    </div>
  </div>
  <div class="card-body-sleek">
    <table id="banksTable" class="table table-hover" style="width:100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Type</th>
          <th>Account</th>
          <th>Currency</th>
          <th>Status</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php /** @var \App\Model\Entity\Bank $bank */ ?>
        <?php foreach ($banks as $bank): ?>
          <tr>
            <td><strong>#<?= (int)$bank->id ?></strong></td>
            <td><?= h($bank->bank_name) ?></td>
            <td><?= h(ucfirst($bank->bank_type)) ?></td>
            <td><?= h($bank->account_number ?: '—') ?></td>
            <td><?= h($bank->currency) ?></td>
            <td>
              <?php if ($bank->is_active): ?>
                <span class="badge badge-active"><i class="fas fa-check"></i> Active</span>
              <?php else: ?>
                <span class="badge badge-inactive"><i class="fas fa-times"></i> Inactive</span>
              <?php endif; ?>
            </td>
            <td><?= $bank->created ? h($bank->created->format('M d, Y')) : '—' ?></td>
            <td>
              <div style="display:flex;gap:.375rem;">
                <?= $this->Html->link('<i class="fas fa-eye"></i>', ['action'=>'view', $bank->id], ['class'=>'btn-action btn-view','escape'=>false,'title'=>'View']) ?>
                <?= $this->Html->link('<i class="fas fa-edit"></i>', ['action'=>'edit', $bank->id], ['class'=>'btn-action btn-edit','escape'=>false,'title'=>'Edit']) ?>
                <?= $this->Form->postLink('<i class="fas fa-trash"></i>', ['action'=>'delete', $bank->id], ['class'=>'btn-action btn-delete','escape'=>false,'confirm'=>__('Delete {0}?', $bank->bank_name),'title'=>'Delete']) ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script>
$(function(){
  $('#banksTable').DataTable({
    dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>Brtip',
    buttons: [
      {extend:'excel',text:'<i class="fas fa-file-excel"></i> Excel',className:'btn-sm',exportOptions:{columns:[0,1,2,3,4,5,6]}},
      {extend:'pdf',text:'<i class="fas fa-file-pdf"></i> PDF',className:'btn-sm',orientation:'landscape',exportOptions:{columns:[0,1,2,3,4,5,6]}},
    ],
    pageLength: 25,
    order: [[1,'asc']],
    responsive: true,
    columnDefs: [{ orderable:false, targets: -1 }],
    language: {
      search: "_INPUT_",
      searchPlaceholder: "🔍 Search banks...",
      lengthMenu: "Display _MENU_ records",
      info: "Showing _START_ to _END_ of _TOTAL_ banks",
      zeroRecords: "No matching banks found"
    }
  });
});
</script>
