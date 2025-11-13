<?php
/** @var \App\View\AppView $this */
$this->assign('title', 'Users');
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<div class="card fade-in" style="margin-bottom: .6rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem;">
        <div>
            <h2 class="title" style="margin:0;font-size:1.05rem;">👥 Users</h2>
            <p class="muted" style="margin:0;font-size:.7rem;">Manage system users & roles</p>
        </div>
        <div style="display:flex;gap:.4rem;">
            <?= $this->Html->link('🔄 Refresh', ['action' => 'index'], ['class' => 'btn btn-outline btn-sm', 'onclick' => 'window.location.reload();return false;']) ?>
        </div>
    </div>
</div>

<table id="admin-users-table" class="display responsive nowrap" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Name</th>
            <th>Role</th>
            <th>Active</th>
            <th>Last Login</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(function(){
    $('#admin-users-table').DataTable({
        processing:true,
        serverSide:true,
        ajax:{
            url:'<?= $this->Url->build(["action" => "index"]) ?>',
            type:'GET',
            headers:{'X-Requested-With':'XMLHttpRequest'}
        },
        columns:[
            {data:'id', responsivePriority:2},
            {data:'email', responsivePriority:1, render:function(d){ return '<span style="font-weight:600;">📧 '+d+'</span>'; }},
            {data:'name', render:function(d){ return d ? '👤 '+d : '—'; }},
            {data:'role', render:function(d){
                const colors={admin:'var(--primary)', manager:'#F64500', user:'#555'};
                return '<span style="font-weight:600;color:'+ (colors[d]||'var(--text)') +'">'+d+'</span>';
            }},
            {data:'is_active', render:function(d){ return d ? '<span class="badge approved" style="padding:4px 10px;border-radius:12px;font-size:.65rem;">Active</span>' : '<span class="badge rejected" style="padding:4px 10px;border-radius:12px;font-size:.65rem;">Inactive</span>'; }},
            {data:'last_login', render:function(d){ return d ? '🕒 '+d : '<span class="muted">—</span>'; }},
            {data:'created', render:function(d){ return d ? '<span style="white-space:nowrap;">📅 '+d.substring(0,10)+'</span>' : ''; }},
            {data:null, orderable:false, searchable:false, render:function(row){
                const viewUrl = '<?= $this->Url->build(["action" => "view"]) ?>/'+row.id;
                let html = '<div style="display:flex;gap:6px;">'
                    + '<a title="View" href="'+viewUrl+'" class="btn-icon-sm" style="background:var(--primary);color:#fff;width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;border-radius:4px;">👁️</a>';
                return html + '</div>';
            }}
        ],
        dom:'<"top"Bfl>rt<"bottom"ip><"clear">',
        buttons:[{extend:'copy',text:'📋 Copy'},{extend:'csv',text:'📊 CSV'},{extend:'print',text:'🖨️ Print'}],
        pageLength:10,
        lengthMenu:[[10,25,50,100],[10,25,50,100]],
        language:{ search:'🔍 Search:', emptyTable:'No users found' }
    });
});
</script>
