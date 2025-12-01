<?php
/** @var \App\View\AppView $this */
$this->assign('title', 'User Management');
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
:root{--primary:#0c5343;--primary-dark:#083d2f;--sunbeth-orange:#ff5722;--success:#10b981}
.page-header-sleek{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);padding:1.5rem 1.75rem;border-radius:8px;box-shadow:0 2px 8px rgba(12,83,67,.15);margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.page-title-sleek{color:#fff;font-size:1.5rem;font-weight:600;margin:0;display:flex;align-items:center;gap:.75rem}
.page-subtitle-sleek{color:rgba(255,255,255,.85);font-size:.875rem;margin:.25rem 0 0 0}
.stats-row-sleek{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem}
.stat-card-sleek{background:#fff;border-radius:8px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.1);transition:all .2s ease;border-left:3px solid var(--primary)}
.stat-card-sleek:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.12)}
.stat-icon-sleek{width:40px;height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.125rem;margin-bottom:.75rem}
.stat-label-sleek{color:#6b7280;font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.25rem}
.stat-value-sleek{font-size:1.5rem;font-weight:700;color:#1f2937}
.data-card-sleek{background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1);overflow:hidden}
.card-toolbar-sleek{background:#f9fafb;padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.75rem}
.toolbar-title-sleek{font-size:1rem;font-weight:600;color:#1f2937;margin:0;display:flex;align-items:center;gap:.5rem}
.toolbar-actions-sleek{display:flex;gap:.5rem;flex-wrap:wrap}
.card-body-sleek{padding:1.25rem}
.btn-sleek{padding:.5rem 1rem;border-radius:6px;font-weight:500;font-size:.875rem;display:inline-flex;align-items:center;gap:.5rem;transition:all .2s ease;border:none;text-decoration:none;cursor:pointer}
.btn-sleek:hover{transform:translateY(-1px);box-shadow:0 2px 8px rgba(0,0,0,.15)}
.btn-success-sleek{background:var(--success);color:#fff !important}
.btn-outline-sleek{background:#fff;color:var(--primary);border:1px solid #e5e7eb}
.btn-outline-sleek:hover{background:#f9fafb;border-color:var(--primary)}
table.dataTable thead th{background:#f9fafb;font-weight:600;text-transform:uppercase;font-size:.6875rem;letter-spacing:.5px;color:#6b7280;border-bottom:1px solid #e5e7eb;padding:.875rem .75rem}
.role-badge{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .625rem;border-radius:4px;font-weight:500;font-size:.75rem;text-transform:uppercase}
.btn-action-sleek{width:30px;height:30px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;transition:all .2s;border:none;cursor:pointer;text-decoration:none;font-size:.8125rem}
.btn-view-sleek{background:#3b82f6;color:#fff !important}
.btn-edit-sleek{background:var(--sunbeth-orange);color:#fff !important}
</style>

<div class="page-header-sleek">
  <div>
    <h1 class="page-title-sleek"><i class="fas fa-users"></i> User Management</h1>
    <p class="page-subtitle-sleek">Manage system users and role assignments</p>
  </div>
  <div>
    <?= $this->Html->link('<i class="fas fa-plus"></i> Add User', ['action' => 'add'], ['class' => 'btn-sleek btn-success-sleek', 'escape' => false]) ?>
  </div>
</div>

<?php if (isset($roleCounts) && !empty($roleCounts)): ?>
<div class="stats-row-sleek">
  <div class="stat-card-sleek">
    <div class="stat-icon-sleek" style="background: var(--primary); color: white;"><i class="fas fa-check-circle"></i></div>
    <div class="stat-label-sleek">Active Users</div>
    <div class="stat-value-sleek"><?= $activeCount ?? 0 ?></div>
  </div>
  <div class="stat-card-sleek" style="border-left-color:#ef4444;">
    <div class="stat-icon-sleek" style="background:#ef4444;color:#fff;"><i class="fas fa-times-circle"></i></div>
    <div class="stat-label-sleek">Inactive</div>
    <div class="stat-value-sleek"><?= $inactiveCount ?? 0 ?></div>
  </div>
  <?php if (!empty($roleCounts['admin'])): ?>
  <div class="stat-card-sleek" style="border-left-color:#3b82f6;">
    <div class="stat-icon-sleek" style="background:#3b82f6;color:#fff;"><i class="fas fa-crown"></i></div>
    <div class="stat-label-sleek">Admins</div>
    <div class="stat-value-sleek"><?= $roleCounts['admin'] ?></div>
  </div>
  <?php endif; ?>
  <?php if (!empty($roleCounts['auditor'])): ?>
  <div class="stat-card-sleek" style="border-left-color:#f59e0b;">
    <div class="stat-icon-sleek" style="background:#f59e0b;color:#fff;"><i class="fas fa-clipboard-check"></i></div>
    <div class="stat-label-sleek">Auditors</div>
    <div class="stat-value-sleek"><?= $roleCounts['auditor'] ?></div>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<div class="data-card-sleek">
  <div class="card-toolbar-sleek">
    <h3 class="toolbar-title-sleek"><i class="fas fa-list"></i> User List</h3>
    <div class="toolbar-actions-sleek">
      <button class="btn-sleek btn-outline-sleek" onclick="$('#admin-users-table').DataTable().button('.buttons-excel').trigger();"><i class="fas fa-file-excel"></i> Excel</button>
      <button class="btn-sleek btn-outline-sleek" onclick="$('#admin-users-table').DataTable().button('.buttons-pdf').trigger();"><i class="fas fa-file-pdf"></i> PDF</button>
    </div>
  </div>
  <div class="card-body-sleek">
    <table id="admin-users-table" class="table table-hover" style="width:100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Email</th>
          <th>Name</th>
          <th>Department</th>
          <th>Role</th>
          <th>Status</th>
          <th>Last Login</th>
          <th>Registered</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
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
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(function(){
  var $table = $('#admin-users-table');
  if ($.fn.DataTable.isDataTable($table)) {
    $table.DataTable().clear().destroy();
  }

  $table.DataTable({
    processing: true,
    serverSide: true,
    ajax: { url: '<?= $this->Url->build(["action" => "index"]) ?>', type: 'GET', headers: {'X-Requested-With':'XMLHttpRequest'} },
    columns: [
      { data: 'id', render: function(d){ return '<strong>#'+d+'</strong>'; } },
      { data: 'email', render: function(d){ return '<i class="fas fa-envelope"></i> '+d; } },
      { data: 'name', render: function(d){ return d ? '<i class="fas fa-user"></i> '+d : '<span class="text-muted">—</span>'; } },
      { data: 'department', render: function(d){ return d ? '<i class="fas fa-building"></i> '+d : '<span class="text-muted">—</span>'; } },
      { data: 'role', render: function(d){
          if (!d) return '<span class="text-muted">—</span>';
          const icons={admin:'fa-crown',user:'fa-user',auditor:'fa-clipboard-check',risk_assessment:'fa-shield-alt',treasurer:'fa-dollar-sign',export:'fa-truck',sales:'fa-handshake'};
          const icon=icons[d]||'fa-user';
          return '<span class="role-badge role-'+d+'"><i class="fas '+icon+'"></i> '+d.replace('_',' ')+'</span>';
      } },
      { data: 'is_active', render: function(d){ return d ? '<span style="color:#10b981;font-weight:600;"><i class="fas fa-circle" style="font-size:.5rem;"></i> Active</span>' : '<span style="color:#ef4444;font-weight:600;"><i class="fas fa-circle" style="font-size:.5rem;"></i> Inactive</span>'; } },
      { data: 'last_login', render: function(d){ return d ? '<i class="fas fa-clock"></i> '+d : '<span class="text-muted">Never</span>'; } },
      { data: 'created', render: function(d){ return d ? '<i class="fas fa-calendar-alt"></i> '+d.substring(0,10) : ''; } },
      { data: null, orderable:false, searchable:false, render: function(row){
          const viewUrl='<?= $this->Url->build(["action"=>"view"]) ?>/'+row.id;
          const editUrl='<?= $this->Url->build(["action"=>"edit"]) ?>/'+row.id;
          return '<div style="display:flex;gap:.375rem;">'
            +'<a href="'+viewUrl+'" class="btn-action-sleek btn-view-sleek" title="View"><i class="fas fa-eye"></i></a>'
            +'<a href="'+editUrl+'" class="btn-action-sleek btn-edit-sleek" title="Edit"><i class="fas fa-edit"></i></a>'
            +'</div>';
      } }
    ],
    // Keep built-in buttons hidden from UI but available for programmatic triggers
    buttons: [
      { extend: 'excelHtml5', title: 'Users' },
      { extend: 'pdfHtml5', title: 'Users' }
    ],
    dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"bottom"ip><"clear">',
    pageLength: 25,
    lengthMenu: [[10,25,50,100],[10,25,50,100]],
    responsive: true,
    columnDefs: [{ orderable:false, targets: -1 }],
    language: { search:"_INPUT_", searchPlaceholder:"🔍 Search users...", lengthMenu:"Display _MENU_ records", info:"Showing _START_ to _END_ of _TOTAL_ users", zeroRecords:"No matching users found", emptyTable:"No users found" },
    order: [[0,'desc']]
  });
});
</script>
<?php return; ?>

<!-- Premium DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
:root{--primary:#0c5343;--primary-dark:#083d2f;--sunbeth-orange:#ff5722;--success:#10b981}
.page-header-sleek{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);padding:1.5rem 1.75rem;border-radius:8px;box-shadow:0 2px 8px rgba(12,83,67,.15);margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.page-title-sleek{color:#fff;font-size:1.5rem;font-weight:600;margin:0;display:flex;align-items:center;gap:.75rem}
.page-subtitle-sleek{color:rgba(255,255,255,.85);font-size:.875rem;margin:.25rem 0 0 0}
.stats-row-sleek{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem}
.stat-card-sleek{background:#fff;border-radius:8px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.1);transition:all .2s ease;border-left:3px solid var(--primary)}
.stat-card-sleek:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.12)}
.stat-icon-sleek{width:40px;height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.125rem;margin-bottom:.75rem}
.stat-label-sleek{color:#6b7280;font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.25rem}
.stat-value-sleek{font-size:1.5rem;font-weight:700;color:#1f2937}
.data-card-sleek{background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1);overflow:hidden}
.card-toolbar-sleek{background:#f9fafb;padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.75rem}
.toolbar-title-sleek{font-size:1rem;font-weight:600;color:#1f2937;margin:0;display:flex;align-items:center;gap:.5rem}
.toolbar-actions-sleek{display:flex;gap:.5rem;flex-wrap:wrap}
.card-body-sleek{padding:1.25rem}
.btn-sleek{padding:.5rem 1rem;border-radius:6px;font-weight:500;font-size:.875rem;display:inline-flex;align-items:center;gap:.5rem;transition:all .2s ease;border:none;text-decoration:none;cursor:pointer}
.btn-sleek:hover{transform:translateY(-1px);box-shadow:0 2px 8px rgba(0,0,0,.15)}
.btn-success-sleek{background:var(--success);color:#fff !important}
.btn-outline-sleek{background:#fff;color:var(--primary);border:1px solid #e5e7eb}
.btn-outline-sleek:hover{background:#f9fafb;border-color:var(--primary)}
table.dataTable thead th{background:#f9fafb;font-weight:600;text-transform:uppercase;font-size:.6875rem;letter-spacing:.5px;color:#6b7280;border-bottom:1px solid #e5e7eb;padding:.875rem .75rem}
.role-badge{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .625rem;border-radius:4px;font-weight:500;font-size:.75rem;text-transform:uppercase}
.btn-action-sleek{width:30px;height:30px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;transition:all .2s;border:none;cursor:pointer;text-decoration:none;font-size:.8125rem}
.btn-view-sleek{background:#3b82f6;color:#fff !important}
.btn-edit-sleek{background:var(--sunbeth-orange);color:#fff !important}
</style>

<div class="page-header-sleek">
  <div>
    <h1 class="page-title-sleek"><i class="fas fa-users"></i> User Management</h1>
    <p class="page-subtitle-sleek">Manage system users and role assignments</p>
  </div>
  <div>
    <?= $this->Html->link('<i class="fas fa-plus"></i> Add User', ['action' => 'add'], ['class' => 'btn-sleek btn-success-sleek', 'escape' => false]) ?>
  </div>
</div>

<?php if (!empty($roleCounts)): ?>
<div class="stats-row-sleek">
  <div class="stat-card-sleek">
    <div class="stat-icon-sleek" style="background: var(--primary); color: white;"><i class="fas fa-check-circle"></i></div>
    <div class="stat-label-sleek">Active Users</div>
    <div class="stat-value-sleek"><?= h($activeCount ?? 0) ?></div>
  </div>

  <div class="stat-card-sleek" style="border-left-color:#ef4444;">
    <div class="stat-icon-sleek" style="background:#ef4444;color:#fff;"><i class="fas fa-times-circle"></i></div>
    <div class="stat-label-sleek">Inactive</div>
    <div class="stat-value-sleek"><?= h($inactiveCount ?? 0) ?></div>
  </div>

  <?php if (!empty($roleCounts['admin'])): ?>
  <div class="stat-card-sleek" style="border-left-color:#3b82f6;">
    <div class="stat-icon-sleek" style="background:#3b82f6;color:#fff;"><i class="fas fa-crown"></i></div>
    <div class="stat-label-sleek">Admins</div>
    <div class="stat-value-sleek"><?= h($roleCounts['admin']) ?></div>
  </div>
  <?php endif; ?>

  <?php if (!empty($roleCounts['auditor'])): ?>
  <div class="stat-card-sleek" style="border-left-color:#f59e0b;">
    <div class="stat-icon-sleek" style="background:#f59e0b;color:#fff;"><i class="fas fa-clipboard-check"></i></div>
    <div class="stat-label-sleek">Auditors</div>
    <div class="stat-value-sleek"><?= h($roleCounts['auditor']) ?></div>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<div class="data-card-sleek">
  <div class="card-toolbar-sleek">
    <h3 class="toolbar-title-sleek"><i class="fas fa-list"></i> User List</h3>
    <div class="toolbar-actions-sleek">
      <button class="btn-sleek btn-outline-sleek" onclick="$('#admin-users-table').DataTable().button('.buttons-excel').trigger();"><i class="fas fa-file-excel"></i> Excel</button>
      <button class="btn-sleek btn-outline-sleek" onclick="$('#admin-users-table').DataTable().button('.buttons-pdf').trigger();"><i class="fas fa-file-pdf"></i> PDF</button>
    </div>
  </div>
  <div class="card-body-sleek">
    <table id="admin-users-table" class="table table-hover" style="width:100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Email</th>
          <th>Name</th>
          <th>Department</th>
          <th>Role</th>
          <th>Status</th>
          <th>Last Login</th>
          <th>Registered</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<!-- Premium DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(function(){
  $('#admin-users-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: { url: '<?= $this->Url->build(["action" => "index"]) ?>', type: 'GET', headers: {'X-Requested-With':'XMLHttpRequest'} },
    columns: [
      { data: 'id', render: function(d){ return '<strong>#'+d+'</strong>'; } },
      { data: 'email', render: function(d){ return '<i class="fas fa-envelope"></i> '+d; } },
      { data: 'name', render: function(d){ return d ? '<i class="fas fa-user"></i> '+d : '<span class="text-muted">—</span>'; } },
      { data: 'department', render: function(d){ return d ? '<i class="fas fa-building"></i> '+d : '<span class="text-muted">—</span>'; } },
      { data: 'role', render: function(d){
          if (!d) return '<span class="text-muted">—</span>';
          const icons={admin:'fa-crown',user:'fa-user',auditor:'fa-clipboard-check',risk_assessment:'fa-shield-alt',treasurer:'fa-dollar-sign',export:'fa-truck',sales:'fa-handshake'};
          const icon=icons[d]||'fa-user';
          return '<span class="role-badge role-'+d+'"><i class="fas '+icon+'"></i> '+d.replace('_',' ')+'</span>';
      } },
      { data: 'is_active', render: function(d){ return d ? '<span style="color:#10b981;font-weight:600;"><i class="fas fa-circle" style="font-size:.5rem;"></i> Active</span>' : '<span style="color:#ef4444;font-weight:600;"><i class="fas fa-circle" style="font-size:.5rem;"></i> Inactive</span>'; } },
      { data: 'last_login', render: function(d){ return d ? '<i class="fas fa-clock"></i> '+d : '<span class="text-muted">Never</span>'; } },
      { data: 'created', render: function(d){ return d ? '<i class="fas fa-calendar-alt"></i> '+d.substring(0,10) : ''; } },
      { data: null, orderable:false, searchable:false, render: function(row){
          const viewUrl='<?= $this->Url->build(["action"=>"view"]) ?>/'+row.id;
          const editUrl='<?= $this->Url->build(["action"=>"edit"]) ?>/'+row.id;
          return '<div style="display:flex;gap:.375rem;">'
            +'<a href="'+viewUrl+'" class="btn-action-sleek btn-view-sleek" title="View"><i class="fas fa-eye"></i></a>'
            +'<a href="'+editUrl+'" class="btn-action-sleek btn-edit-sleek" title="Edit"><i class="fas fa-edit"></i></a>'
            +'</div>';
      } }
    ],
    dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"bottom"ip><"clear">',
    pageLength: 25,
    lengthMenu: [[10,25,50,100],[10,25,50,100]],
    responsive: true,
    columnDefs: [{ orderable:false, targets: -1 }],
    language: { search:"_INPUT_", searchPlaceholder:"🔍 Search users...", lengthMenu:"Display _MENU_ records", info:"Showing _START_ to _END_ of _TOTAL_ users", zeroRecords:"No matching users found", emptyTable:"No users found" },
    order: [[0,'desc']]
  });
});
</script>
<?php __halt_compiler(); ?>
<?php
/** @var \App\View\AppView $this */
$this->assign('title', 'User Management');
?>

<!-- Premium DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
:root{--primary:#0c5343;--primary-dark:#083d2f;--sunbeth-orange:#ff5722;--success:#10b981}
.page-header-sleek{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);padding:1.5rem 1.75rem;border-radius:8px;box-shadow:0 2px 8px rgba(12,83,67,.15);margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.page-title-sleek{color:#fff;font-size:1.5rem;font-weight:600;margin:0;display:flex;align-items:center;gap:.75rem}
.page-subtitle-sleek{color:rgba(255,255,255,.85);font-size:.875rem;margin:.25rem 0 0 0}
.stats-row-sleek{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem}
.stat-card-sleek{background:#fff;border-radius:8px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.1);transition:all .2s ease;border-left:3px solid var(--primary)}
.stat-card-sleek:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.12)}
.stat-icon-sleek{width:40px;height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.125rem;margin-bottom:.75rem}
.stat-label-sleek{color:#6b7280;font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.25rem}
.stat-value-sleek{font-size:1.5rem;font-weight:700;color:#1f2937}
.data-card-sleek{background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1);overflow:hidden}
.card-toolbar-sleek{background:#f9fafb;padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.75rem}
.toolbar-title-sleek{font-size:1rem;font-weight:600;color:#1f2937;margin:0;display:flex;align-items:center;gap:.5rem}
.card-body-sleek{padding:1.25rem}
.btn-sleek{padding:.5rem 1rem;border-radius:6px;font-weight:500;font-size:.875rem;display:inline-flex;align-items:center;gap:.5rem;transition:all .2s ease;border:none;text-decoration:none;cursor:pointer}
.btn-sleek:hover{transform:translateY(-1px);box-shadow:0 2px 8px rgba(0,0,0,.15)}
.btn-success-sleek{background:var(--success);color:#fff !important}
.btn-outline-sleek{background:#fff;color:var(--primary);border:1px solid #e5e7eb}
.btn-outline-sleek:hover{background:#f9fafb;border-color:var(--primary)}
table.dataTable thead th{background:#f9fafb;font-weight:600;text-transform:uppercase;font-size:.6875rem;letter-spacing:.5px;color:#6b7280;border-bottom:1px solid #e5e7eb;padding:.875rem .75rem}
.role-badge{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .625rem;border-radius:4px;font-weight:500;font-size:.75rem;text-transform:uppercase}
.btn-action-sleek{width:30px;height:30px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;transition:all .2s;border:none;cursor:pointer;text-decoration:none;font-size:.8125rem}
.btn-view-sleek{background:#3b82f6;color:#fff !important}
.btn-edit-sleek{background:var(--sunbeth-orange);color:#fff !important}
</style>

<div class="page-header-sleek">
  <div>
    <h1 class="page-title-sleek"><i class="fas fa-users"></i> User Management</h1>
    <p class="page-subtitle-sleek">Manage system users and role assignments</p>
  </div>
  <div>
    <?= $this->Html->link('<i class="fas fa-plus"></i> Add User', ['action' => 'add'], ['class' => 'btn-sleek btn-success-sleek', 'escape' => false]) ?>
  </div>
</div>

<?php if (isset($roleCounts) && !empty($roleCounts)): ?>
<div class="stats-row-sleek">
  <div class="stat-card-sleek">
    <div class="stat-icon-sleek" style="background: var(--primary); color: white;"><i class="fas fa-check-circle"></i></div>
    <div class="stat-label-sleek">Active Users</div>
    <div class="stat-value-sleek"><?= $activeCount ?? 0 ?></div>
  </div>
  <div class="stat-card-sleek" style="border-left-color:#ef4444;">
    <div class="stat-icon-sleek" style="background:#ef4444;color:#fff;"><i class="fas fa-times-circle"></i></div>
    <div class="stat-label-sleek">Inactive</div>
    <div class="stat-value-sleek"><?= $inactiveCount ?? 0 ?></div>
  </div>
  <?php if (isset($roleCounts['admin'])): ?>
  <div class="stat-card-sleek" style="border-left-color:#3b82f6;">
    <div class="stat-icon-sleek" style="background:#3b82f6;color:#fff;"><i class="fas fa-crown"></i></div>
    <div class="stat-label-sleek">Admins</div>
    <div class="stat-value-sleek"><?= $roleCounts['admin'] ?></div>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<div class="data-card-sleek">
  <div class="card-toolbar-sleek">
    <h3 class="toolbar-title-sleek"><i class="fas fa-list"></i> User List</h3>
  </div>
  <div class="card-body-sleek">
    <table id="admin-users-table" class="table table-hover" style="width:100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Email</th>
          <th>Name</th>
          <th>Department</th>
          <th>Role</th>
          <th>Status</th>
          <th>Last Login</th>
          <th>Registered</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(function(){
  $('#admin-users-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: { url: '<?= $this->Url->build(["action" => "index"]) ?>', type: 'GET', headers: {'X-Requested-With':'XMLHttpRequest'} },
    columns: [
      { data: 'id', render: function(d){ return '<strong>#'+d+'</strong>'; } },
      { data: 'email', render: function(d){ return '<i class="fas fa-envelope"></i> '+d; } },
      { data: 'name', render: function(d){ return d ? '<i class="fas fa-user"></i> '+d : '<span class="text-muted">—</span>'; } },
      { data: 'department', render: function(d){ return d ? '<i class="fas fa-building"></i> '+d : '<span class="text-muted">—</span>'; } },
      { data: 'role', render: function(d){
          if (!d) return '<span class="text-muted">—</span>';
          const icons={admin:'fa-crown',user:'fa-user',auditor:'fa-clipboard-check',risk_assessment:'fa-shield-alt',treasurer:'fa-dollar-sign',export:'fa-truck',sales:'fa-handshake'};
          const icon=icons[d]||'fa-user';
          return '<span class="role-badge role-'+d+'"><i class="fas '+icon+'"></i> '+d.replace('_',' ')+'</span>';
      }},
      { data: 'is_active', render: function(d){ return d ? '<span style="color:#10b981;font-weight:600;"><i class="fas fa-circle" style="font-size:.5rem;"></i> Active</span>' : '<span style="color:#ef4444;font-weight:600;"><i class="fas fa-circle" style="font-size:.5rem;"></i> Inactive</span>'; } },
      { data: 'last_login', render: function(d){ return d ? '<i class="fas fa-clock"></i> '+d : '<span class="text-muted">Never</span>'; } },
      { data: 'created', render: function(d){ return d ? '<i class="fas fa-calendar-alt"></i> '+d.substring(0,10) : ''; } },
      { data: null, orderable:false, searchable:false, render: function(row){
          const viewUrl='<?= $this->Url->build(["action"=>"view"]) ?>/'+row.id;
          const editUrl='<?= $this->Url->build(["action"=>"edit"]) ?>/'+row.id;
          return '<div style="display:flex;gap:.375rem;">'
            +'<a href="'+viewUrl+'" class="btn-action-sleek btn-view-sleek" title="View"><i class="fas fa-eye"></i></a>'
            +'<a href="'+editUrl+'" class="btn-action-sleek btn-edit-sleek" title="Edit"><i class="fas fa-edit"></i></a>'
            +'</div>';
      }}
    ],
    dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"bottom"ip><"clear">',
    pageLength: 25,
    lengthMenu: [[10,25,50,100],[10,25,50,100]],
    responsive: true,
    order: [[0,'desc']],
    columnDefs: [{ orderable:false, targets: -1 }],
    language: { search:"_INPUT_", searchPlaceholder:"🔍 Search users...", lengthMenu:"Display _MENU_ records", info:"Showing _START_ to _END_ of _TOTAL_ users", zeroRecords:"No matching users found", emptyTable:"No users found" }
  });
});
<\/script>


/** @var \App\View\AppView $this *//** @var \App\View\AppView $this */

$this->assign('title', 'User Management');$this->assign('title', 'User Management');

?>?>



<!-- Premium DataTables CSS --><!-- Premium DataTables CSS -->

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"><link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css"><link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css"><link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">



<style><style>

:root {:root {

    --primary: #0c5343;    --primary: #0c5343;

    --primary-dark: #083d2f;    --primary-dark: #083d2f;

    --sunbeth-orange: #ff5722;    --sunbeth-orange: #ff5722;

    --success: #10b981;    --success: #10b981;

}}



/* Page Header - Sleek Design *//* Page Header - Sleek Design */

.page-header-sleek {.page-header-sleek {

    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);

    padding: 1.5rem 1.75rem;    padding: 1.5rem 1.75rem;

    border-radius: 8px;    border-radius: 8px;

    box-shadow: 0 2px 8px rgba(12, 83, 67, 0.15);    box-shadow: 0 2px 8px rgba(12, 83, 67, 0.15);

    margin-bottom: 1.5rem;    margin-bottom: 1.5rem;

    display: flex;    display: flex;

    justify-content: space-between;    justify-content: space-between;

    align-items: center;    align-items: center;

    flex-wrap: wrap;    flex-wrap: wrap;

    gap: 1rem;    gap: 1rem;

}}



.page-title-sleek {.page-title-sleek {

    color: white;    color: white;

    font-size: 1.5rem;    font-size: 1.5rem;

    font-weight: 600;    font-weight: 600;

    margin: 0;    margin: 0;

    display: flex;    display: flex;

    align-items: center;    align-items: center;

    gap: 0.75rem;    gap: 0.75rem;

}}



.page-title-sleek i {.page-title-sleek i {

    font-size: 1.25rem;    font-size: 1.25rem;

    opacity: 0.9;    opacity: 0.9;

}}



.page-subtitle-sleek {.page-subtitle-sleek {

    color: rgba(255,255,255,0.85);    color: rgba(255,255,255,0.85);

    font-size: 0.875rem;    font-size: 0.875rem;

    margin: 0.25rem 0 0 0;    margin: 0.25rem 0 0 0;

    font-weight: 400;    font-weight: 400;

}}



/* Stats Row - Compact *//* Stats Row - Compact */

.stats-row-sleek {.stats-row-sleek {

    display: grid;    display: grid;

    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));

    gap: 1rem;    gap: 1rem;

    margin-bottom: 1.5rem;    margin-bottom: 1.5rem;

}}



.stat-card-sleek {.stat-card-sleek {

    background: white;    background: white;

    border-radius: 8px;    border-radius: 8px;

    padding: 1.25rem;    padding: 1.25rem;

    box-shadow: 0 1px 3px rgba(0,0,0,0.1);    box-shadow: 0 1px 3px rgba(0,0,0,0.1);

    transition: all 0.2s ease;    transition: all 0.2s ease;

    border-left: 3px solid var(--primary);    border-left: 3px solid var(--primary);

}}



.stat-card-sleek:hover {.stat-card-sleek:hover {

    transform: translateY(-2px);    transform: translateY(-2px);

    box-shadow: 0 4px 12px rgba(0,0,0,0.12);    box-shadow: 0 4px 12px rgba(0,0,0,0.12);

}}



.stat-icon-sleek {.stat-icon-sleek {

    width: 40px;    width: 40px;

    height: 40px;    height: 40px;

    border-radius: 8px;    border-radius: 8px;

    display: flex;    display: flex;

    align-items: center;    align-items: center;

    justify-content: center;    justify-content: center;

    font-size: 1.125rem;    font-size: 1.125rem;

    margin-bottom: 0.75rem;    margin-bottom: 0.75rem;

}}



.stat-label-sleek {.stat-label-sleek {

    color: #6b7280;    color: #6b7280;

    font-size: 0.75rem;    font-size: 0.75rem;

    font-weight: 600;    font-weight: 600;

    text-transform: uppercase;    text-transform: uppercase;

    letter-spacing: 0.5px;    letter-spacing: 0.5px;

    margin-bottom: 0.25rem;    margin-bottom: 0.25rem;

}}



.stat-value-sleek {.stat-value-sleek {

    font-size: 1.5rem;    font-size: 1.5rem;

    font-weight: 700;    font-weight: 700;

    color: #1f2937;    color: #1f2937;

}}



/* Data Card - Clean *//* Data Card - Clean */

.data-card-sleek {.data-card-sleek {

    background: white;    background: white;

    border-radius: 8px;    border-radius: 8px;

    box-shadow: 0 1px 3px rgba(0,0,0,0.1);    box-shadow: 0 1px 3px rgba(0,0,0,0.1);

    overflow: hidden;    overflow: hidden;

}}



.card-toolbar-sleek {.card-toolbar-sleek {

    background: #f9fafb;    background: #f9fafb;

    padding: 1rem 1.25rem;    padding: 1rem 1.25rem;

    border-bottom: 1px solid #e5e7eb;    border-bottom: 1px solid #e5e7eb;

    display: flex;    display: flex;

    justify-content: space-between;    justify-content: space-between;

    align-items: center;    align-items: center;

    flex-wrap: wrap;    flex-wrap: wrap;

    gap: 0.75rem;    gap: 0.75rem;

}}



.toolbar-title-sleek {.toolbar-title-sleek {

    font-size: 1rem;    font-size: 1rem;

    font-weight: 600;    font-weight: 600;

    color: #1f2937;    color: #1f2937;

    margin: 0;    margin: 0;

    display: flex;    display: flex;

    align-items: center;    align-items: center;

    gap: 0.5rem;    gap: 0.5rem;

}}



.toolbar-actions-sleek {.toolbar-actions-sleek {

    display: flex;    display: flex;

    gap: 0.5rem;    gap: 0.5rem;

    flex-wrap: wrap;    flex-wrap: wrap;

}}



.btn-sleek {.btn-sleek {

    padding: 0.5rem 1rem;    padding: 0.5rem 1rem;

    border-radius: 6px;    border-radius: 6px;

    font-weight: 500;    font-weight: 500;

    font-size: 0.875rem;    font-size: 0.875rem;

    display: inline-flex;    display: inline-flex;

    align-items: center;    align-items: center;

    gap: 0.5rem;    gap: 0.5rem;

    transition: all 0.2s ease;    transition: all 0.2s ease;

    border: none;    border: none;

    text-decoration: none;    text-decoration: none;

    cursor: pointer;    cursor: pointer;

}}



.btn-sleek:hover {.btn-sleek:hover {

    transform: translateY(-1px);    transform: translateY(-1px);

    box-shadow: 0 2px 8px rgba(0,0,0,0.15);    box-shadow: 0 2px 8px rgba(0,0,0,0.15);

}}



.btn-primary-sleek {.btn-primary-sleek {

    background: var(--primary);    background: var(--primary);

    color: white !important;    color: white !important;

}}



.btn-primary-sleek:hover {.btn-primary-sleek:hover {

    background: var(--primary-dark);    background: var(--primary-dark);

}}



.btn-success-sleek {.btn-success-sleek {

    background: var(--success);    background: var(--success);

    color: white !important;    color: white !important;

}}



.btn-success-sleek:hover {.btn-success-sleek:hover {

    background: #059669;    background: #059669;

}}



.btn-outline-sleek {.btn-outline-sleek {

    background: white;    background: white;

    color: var(--primary);    color: var(--primary);

    border: 1px solid #e5e7eb;    border: 1px solid #e5e7eb;

}}



.btn-outline-sleek:hover {.btn-outline-sleek:hover {

    background: #f9fafb;    background: #f9fafb;

    border-color: var(--primary);    border-color: var(--primary);

}}



.card-body-sleek {.card-body-sleek {

    padding: 1.25rem;    padding: 1.25rem;

}}



/* DataTables Styling *//* DataTables Styling */

table.dataTable {table.dataTable {

    font-size: 0.875rem;    font-size: 0.875rem;

}}



table.dataTable thead th {table.dataTable thead th {

    background: #f9fafb;    background: #f9fafb;

    font-weight: 600;    font-weight: 600;

    text-transform: uppercase;    text-transform: uppercase;

    font-size: 0.6875rem;    font-size: 0.6875rem;

    letter-spacing: 0.5px;    letter-spacing: 0.5px;

    color: #6b7280;    color: #6b7280;

    border-bottom: 1px solid #e5e7eb;    border-bottom: 1px solid #e5e7eb;

    padding: 0.875rem 0.75rem;    padding: 0.875rem 0.75rem;

}}



table.dataTable tbody tr:hover {table.dataTable tbody tr:hover {

    background: #f9fafb !important;    background: #f9fafb !important;

}}



table.dataTable tbody td {table.dataTable tbody td {

    padding: 0.875rem 0.75rem;    padding: 0.875rem 0.75rem;

    vertical-align: middle;    vertical-align: middle;

    border-bottom: 1px solid #f3f4f6;    border-bottom: 1px solid #f3f4f6;

}}



/* Role Badges *//* Role Badges */

.role-badge {.role-badge {

    display: inline-flex;    display: inline-flex;

    align-items: center;    align-items: center;

    gap: 0.3rem;    gap: 0.3rem;

    padding: 0.25rem 0.625rem;    padding: 0.25rem 0.625rem;

    border-radius: 4px;    border-radius: 4px;

    font-weight: 500;    font-weight: 500;

    font-size: 0.75rem;    font-size: 0.75rem;

    text-transform: uppercase;    text-transform: uppercase;

}}



.role-admin { background: #dbeafe; color: #1e40af; }.role-admin { background: #dbeafe; color: #1e40af; }

.role-user { background: #e0e7ff; color: #4338ca; }.role-user { background: #e0e7ff; color: #4338ca; }

.role-auditor { background: #fef3c7; color: #92400e; }.role-auditor { background: #fef3c7; color: #92400e; }

.role-risk_assessment { background: #fce7f3; color: #9f1239; }.role-risk_assessment { background: #fce7f3; color: #9f1239; }

.role-treasurer { background: #d1fae5; color: #065f46; }.role-treasurer { background: #d1fae5; color: #065f46; }

.role-export { background: #f3e8ff; color: #6b21a8; }.role-export { background: #f3e8ff; color: #6b21a8; }

.role-sales { background: #ccfbf1; color: #115e59; }.role-sales { background: #ccfbf1; color: #115e59; }



/* Action Buttons - Smaller *//* Action Buttons - Smaller */

.btn-action-sleek {.btn-action-sleek {

    width: 30px;    width: 30px;

    height: 30px;    height: 30px;

    border-radius: 6px;    border-radius: 6px;

    display: inline-flex;    display: inline-flex;

    align-items: center;    align-items: center;

    justify-content: center;    justify-content: center;

    transition: all 0.2s ease;    transition: all 0.2s ease;

    border: none;    border: none;

    cursor: pointer;    cursor: pointer;

    text-decoration: none;    text-decoration: none;

    font-size: 0.8125rem;    font-size: 0.8125rem;

}}



.btn-action-sleek:hover {.btn-action-sleek:hover {

    transform: scale(1.08);    transform: scale(1.08);

}}



.btn-view-sleek {.btn-view-sleek {

    background: #3b82f6;    background: #3b82f6;

    color: white !important;    color: white !important;

}}



.btn-edit-sleek {.btn-edit-sleek {

    background: var(--sunbeth-orange);    background: var(--sunbeth-orange);

    color: white !important;    color: white !important;

}}



.dataTables_wrapper .dataTables_length select,.btn-delete-sleek {

.dataTables_wrapper .dataTables_filter input {    background: #ef4444;

    font-size: 0.875rem;    color: white !important;

    padding: 0.375rem 0.625rem;}

    border: 1px solid #e5e7eb;

    border-radius: 6px;.dataTables_wrapper .dataTables_length select,

}.dataTables_wrapper .dataTables_filter input {

    font-size: 0.875rem;

.dataTables_wrapper .dataTables_filter input:focus {    padding: 0.375rem 0.625rem;

    border-color: var(--primary);    border: 1px solid #e5e7eb;

    outline: none;    border-radius: 6px;

}}

</style>

.dataTables_wrapper .dataTables_filter input:focus {

<div class="page-header-sleek">    border-color: var(--primary);

    <div>    outline: none;

        <h1 class="page-title-sleek">}

            <i class="fas fa-users"></i></style>

            <?php
            /** @var \App\View\AppView $this */
            $this->assign('title', 'User Management');
            ?>

            <!-- Premium DataTables CSS -->
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

            <style>
            :root{--primary:#0c5343;--primary-dark:#083d2f;--sunbeth-orange:#ff5722;--success:#10b981}
            .page-header-sleek{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);padding:1.5rem 1.75rem;border-radius:8px;box-shadow:0 2px 8px rgba(12,83,67,.15);margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
            .page-title-sleek{color:#fff;font-size:1.5rem;font-weight:600;margin:0;display:flex;align-items:center;gap:.75rem}
            .page-subtitle-sleek{color:rgba(255,255,255,.85);font-size:.875rem;margin:.25rem 0 0 0}
            .stats-row-sleek{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem}
            .stat-card-sleek{background:#fff;border-radius:8px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.1);transition:all .2s ease;border-left:3px solid var(--primary)}
            .stat-card-sleek:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.12)}
            .stat-icon-sleek{width:40px;height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.125rem;margin-bottom:.75rem}
            .stat-label-sleek{color:#6b7280;font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.25rem}
            .stat-value-sleek{font-size:1.5rem;font-weight:700;color:#1f2937}
            .data-card-sleek{background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1);overflow:hidden}
            .card-toolbar-sleek{background:#f9fafb;padding:1rem 1.25rem;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.75rem}
            .toolbar-title-sleek{font-size:1rem;font-weight:600;color:#1f2937;margin:0;display:flex;align-items:center;gap:.5rem}
            .card-body-sleek{padding:1.25rem}
            .btn-sleek{padding:.5rem 1rem;border-radius:6px;font-weight:500;font-size:.875rem;display:inline-flex;align-items:center;gap:.5rem;transition:all .2s ease;border:none;text-decoration:none;cursor:pointer}
            .btn-sleek:hover{transform:translateY(-1px);box-shadow:0 2px 8px rgba(0,0,0,.15)}
            .btn-success-sleek{background:var(--success);color:#fff !important}
            .btn-outline-sleek{background:#fff;color:var(--primary);border:1px solid #e5e7eb}
            .btn-outline-sleek:hover{background:#f9fafb;border-color:var(--primary)}
            .table thead th, table.dataTable thead th{background:#f9fafb;font-weight:600;text-transform:uppercase;font-size:.6875rem;letter-spacing:.5px;color:#6b7280;border-bottom:1px solid #e5e7eb;padding:.875rem .75rem}
            .role-badge{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .625rem;border-radius:4px;font-weight:500;font-size:.75rem;text-transform:uppercase}
            .btn-action-sleek{width:30px;height:30px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;transition:all .2s;border:none;cursor:pointer;text-decoration:none;font-size:.8125rem}
            .btn-view-sleek{background:#3b82f6;color:#fff !important}
            .btn-edit-sleek{background:var(--sunbeth-orange);color:#fff !important}
            </style>

            <div class="page-header-sleek">
                <div>
                    <h1 class="page-title-sleek"><i class="fas fa-users"></i> User Management</h1>
                    <p class="page-subtitle-sleek">Manage system users and role assignments</p>
                </div>
                <div>
                    <?= $this->Html->link('<i class="fas fa-plus"></i> Add User', ['action' => 'add'], ['class' => 'btn-sleek btn-success-sleek', 'escape' => false]) ?>
                </div>
            </div>
                <i class="fas fa-check-circle"></i>

    <div class="stat-card-sleek" style="border-left-color: #ef4444;">        </div>

        <div class="stat-icon-sleek" style="background: #ef4444; color: white;">        <div class="stat-label-sleek">Active Users</div>

            <i class="fas fa-times-circle"></i>        <div class="stat-value-sleek"><?= $activeCount ?? 0 ?></div>

        </div>    </div>

        <div class="stat-label-sleek">Inactive</div>    

        <div class="stat-value-sleek"><?= $inactiveCount ?? 0 ?></div>    <div class="stat-card-sleek" style="border-left-color: #ef4444;">

    </div>        <div class="stat-icon-sleek" style="background: #ef4444; color: white;">

                <i class="fas fa-times-circle"></i>

    <?php if (isset($roleCounts['admin'])): ?>        </div>

    <div class="stat-card-sleek" style="border-left-color: #3b82f6;">        <div class="stat-label-sleek">Inactive</div>

        <div class="stat-icon-sleek" style="background: #3b82f6; color: white;">        <div class="stat-value-sleek"><?= $inactiveCount ?? 0 ?></div>

            <i class="fas fa-crown"></i>    </div>

        </div>    

        <div class="stat-label-sleek">Admins</div>    <?php if (isset($roleCounts['admin'])): ?>

        <div class="stat-value-sleek"><?= $roleCounts['admin'] ?></div>    <div class="stat-card-sleek" style="border-left-color: #3b82f6;">

    </div>        <div class="stat-icon-sleek" style="background: #3b82f6; color: white;">

    <?php endif; ?>            <i class="fas fa-crown"></i>

            </div>

    <?php if (isset($roleCounts['auditor'])): ?>        <div class="stat-label-sleek">Admins</div>

    <div class="stat-card-sleek" style="border-left-color: #f59e0b;">        <div class="stat-value-sleek"><?= $roleCounts['admin'] ?></div>

        <div class="stat-icon-sleek" style="background: #f59e0b; color: white;">    </div>

            <i class="fas fa-clipboard-check"></i>    <?php endif; ?>

        </div>    

        <div class="stat-label-sleek">Auditors</div>    <?php if (isset($roleCounts['auditor'])): ?>

        <div class="stat-value-sleek"><?= $roleCounts['auditor'] ?></div>    <div class="stat-card-sleek" style="border-left-color: #f59e0b;">

    </div>        <div class="stat-icon-sleek" style="background: #f59e0b; color: white;">

    <?php endif; ?>            <i class="fas fa-clipboard-check"></i>

</div>        </div>

<?php endif; ?>        <div class="stat-label-sleek">Auditors</div>

        <div class="stat-value-sleek"><?= $roleCounts['auditor'] ?></div>

<div class="data-card-sleek">    </div>

    <div class="card-toolbar-sleek">    <?php endif; ?>

        <h3 class="toolbar-title-sleek"></div>

            <i class="fas fa-list"></i> User List<?php endif; ?>

        </h3>

    </div><div class="data-card-sleek">

        <div class="card-toolbar-sleek">

    <div class="card-body-sleek">        <h3 class="toolbar-title-sleek">

        <table id="admin-users-table" class="table table-hover" style="width:100%">            <i class="fas fa-list"></i> User List

            <thead>        </h3>

                <tr>        <div class="toolbar-actions-sleek">

                    <th>ID</th>            <button class="btn-sleek btn-outline-sleek" onclick="$('#admin-users-table').DataTable().button('.buttons-excel').trigger();">

                    <th>Email</th>                <i class="fas fa-file-excel"></i> Excel

                    <th>Name</th>            </button>

                    <th>Department</th>            <button class="btn-sleek btn-outline-sleek" onclick="$('#admin-users-table').DataTable().button('.buttons-pdf').trigger();">

                    <th>Role</th>                <i class="fas fa-file-pdf"></i> PDF

                    <th>Status</th>            </button>

                    <th>Last Login</th>        </div>

                    <th>Registered</th>    </div>

                    <th>Actions</th>    

                </tr>    <div class="card-body-sleek">

            </thead>        <table id="admin-users-table" class="table table-hover" style="width:100%">

            <tbody></tbody>        <thead>

        </table>            <tr>

    </div>                <th>ID</th>

</div>                <th>Email</th>

                <th>Name</th>

<!-- Premium DataTables JS -->                <th>Department</th>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>                <th>Role</th>

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>                <th>Status</th>

<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>                <th>Last Login</th>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>                <th>Registered</th>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>                <th>Actions</th>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>            </tr>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>        </thead>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>        <tbody></tbody>

    </table>

<script></div>

$(function(){

    $('#admin-users-table').DataTable({<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        processing: true,<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        serverSide: true,<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

        ajax: {<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

            url: '<?= $this->Url->build(["action" => "index"]) ?>',<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

            type: 'GET',<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

            headers: {'X-Requested-With':'XMLHttpRequest'}

        },<script>

        columns: [$(function(){

            {    $('#admin-users-table').DataTable({

                data: 'id',        processing: true,

                responsivePriority: 3,        serverSide: true,

                render: function(d) { return '<strong>#' + d + '</strong>'; }        ajax: {

            },            url: '<?= $this->Url->build(["action" => "index"]) ?>',

            {            type: 'GET',

                data: 'email',            headers: {'X-Requested-With':'XMLHttpRequest'}

                responsivePriority: 1,        },

                render: function(d) { return '<i class="fas fa-envelope"></i> ' + d; }        columns: [

            },            {

            {                data: 'id',

                data: 'name',                responsivePriority: 3,

                render: function(d) { return d ? '<i class="fas fa-user"></i> ' + d : '<span class="text-muted">—</span>'; }                render: function(d) { return '<strong>#' + d + '</strong>'; }

            },            },

            {            {

                data: 'department',                data: 'email',

                render: function(d) { return d ? '<i class="fas fa-building"></i> ' + d : '<span class="text-muted">—</span>'; }                responsivePriority: 1,

            },                render: function(d) { return '<i class="fas fa-envelope"></i> ' + d; }

            {            },

                data: 'role',            {

                responsivePriority: 2,                data: 'name',

                render: function(d) {                render: function(d) { return d ? '<i class="fas fa-user"></i> ' + d : '<span class="muted">—</span>'; }

                    if (!d) return '<span class="text-muted">—</span>';            },

                    const roleIcons = {            {

                        admin: 'fa-crown',                data: 'department',

                        user: 'fa-user',                render: function(d) { return d ? '<i class="fas fa-building"></i> ' + d : '<span class="muted">—</span>'; }

                        auditor: 'fa-clipboard-check',            },

                        risk_assessment: 'fa-shield-alt',            {

                        treasurer: 'fa-dollar-sign',                data: 'role',

                        export: 'fa-truck',                responsivePriority: 2,

                        sales: 'fa-handshake'                render: function(d) {

                    };                    if (!d) return '<span class="muted">—</span>';

                    const icon = roleIcons[d] || 'fa-user';                    const roleIcons = {

                    return '<span class="role-badge role-' + d + '"><i class="fas ' + icon + '"></i> ' + d.replace('_', ' ') + '</span>';                        admin: 'fa-crown',

                }                        user: 'fa-user',

            },                        auditor: 'fa-clipboard-check',

            {                        risk_assessment: 'fa-shield-alt',

                data: 'is_active',                        treasurer: 'fa-dollar-sign',

                render: function(d) {                        export: 'fa-truck',

                    return d                         sales: 'fa-handshake'

                        ? '<span style="color:#10b981;font-weight:600;"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Active</span>'                    };

                        : '<span style="color:#ef4444;font-weight:600;"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Inactive</span>';                    const icon = roleIcons[d] || 'fa-user';

                }                    return '<span class="role-badge role-' + d + '"><i class="fas ' + icon + '"></i> ' + d.replace('_', ' ') + '</span>';

            },                }

            {            },

                data: 'last_login',            {

                render: function(d) {                data: 'is_active',

                    return d ? '<i class="fas fa-clock"></i> ' + d : '<span class="text-muted">Never</span>';                render: function(d) {

                }                    return d 

            },                        ? '<span style="color:#2e7d32;font-weight:600;"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Active</span>'

            {                        : '<span style="color:#c62828;font-weight:600;"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Inactive</span>';

                data: 'created',                }

                render: function(d) {            },

                    return d ? '<i class="fas fa-calendar-alt"></i> ' + d.substring(0, 10) : '';            {

                }                data: 'last_login',

            },                render: function(d) {

            {                    return d ? '<i class="fas fa-clock"></i> ' + d : '<span class="muted">Never</span>';

                data: null,                }

                orderable: false,            },

                searchable: false,            {

                responsivePriority: 1,                data: 'created',

                render: function(row) {                render: function(d) {

                    const viewUrl = '<?= $this->Url->build(["action" => "view"]) ?>/' + row.id;                    return d ? '<i class="fas fa-calendar-alt"></i> ' + d.substring(0, 10) : '';

                    const editUrl = '<?= $this->Url->build(["action" => "edit"]) ?>/' + row.id;                }

                                },

                    let html = '<div style="display:flex;gap:0.375rem;">';            {

                    html += '<a href="' + viewUrl + '" class="btn-action-sleek btn-view-sleek" title="View"><i class="fas fa-eye"></i></a>';                data: null,

                    html += '<a href="' + editUrl + '" class="btn-action-sleek btn-edit-sleek" title="Edit"><i class="fas fa-edit"></i></a>';                orderable: false,

                    html += '</div>';                searchable: false,

                                    responsivePriority: 1,

                    return html;                render: function(row) {

                }                    const viewUrl = '<?= $this->Url->build(["action" => "view"]) ?>/' + row.id;

            }                    const editUrl = '<?= $this->Url->build(["action" => "edit"]) ?>/' + row.id;

        ],                    

        dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"bottom"ip><"clear">',                    let html = '<div style="display:flex;gap:0.3rem;">';

        pageLength: 25,                    html += '<a href="' + viewUrl + '" class="btn-icon-sm" title="View" style="background:#1976d2;color:#fff;"><i class="fas fa-eye"></i></a>';

        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],                    html += '<a href="' + editUrl + '" class="btn-icon-sm" title="Edit" style="background:#388e3c;color:#fff;"><i class="fas fa-edit"></i></a>';

        responsive: true,                    html += '</div>';

        columnDefs: [                    

            { orderable: false, targets: -1 }                    return html;

        ],                }

        language: {            }

            search: "_INPUT_",        ],

            searchPlaceholder: "🔍 Search users...",        dom: '<"top"Bfl>rt<"bottom"ip><"clear">',

            lengthMenu: "Display _MENU_ records",        buttons: [

            info: "Showing _START_ to _END_ of _TOTAL_ users",            {extend: 'copy', text: '<i class="fas fa-copy"></i> Copy'},

            zeroRecords: "No matching users found",            {extend: 'csv', text: '<i class="fas fa-file-csv"></i> CSV'},

            emptyTable: "No users found"            {extend: 'print', text: '<i class="fas fa-print"></i> Print'}

        },        ],

        order: [[0, 'desc']]        pageLength: 25,

    });        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],

});        language: {

</script>            search: '<i class="fas fa-search"></i> Search:',

            emptyTable: 'No users found',
            zeroRecords: 'No matching users found'
        },
        order: [[0, 'desc']]
    });
});
</script>
