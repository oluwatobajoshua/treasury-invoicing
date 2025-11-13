<?php
$this->assign('title', 'Allowance Rates');
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<div class="card fade-in" style="margin-bottom:.6rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem;">
        <div>
            <h2 class="title" style="margin:0;font-size:1.05rem;">💸 Allowance Rates</h2>
            <p class="muted" style="margin:0;font-size:.7rem;">Configure per diem, transport, and more</p>
        </div>
        <div style="display:flex;gap:.4rem;">
            <?= $this->Html->link('➕ Add', ['action' => 'add'], ['class' => 'btn btn-primary btn-sm']) ?>
        </div>
    </div>
</div>

<table id="admin-allowances-table" class="display responsive nowrap" style="width:100%">
    <thead>
        <tr>
            <th>Level Code</th>
            <th>Level Name</th>
            <th>Type</th>
            <th>Accommodation</th>
            <th>Per Diem</th>
            <th>Transport</th>
            <th>Currency</th>
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
    $('#admin-allowances-table').DataTable({
        processing:true,
        serverSide:true,
        ajax:{ url:'<?= $this->Url->build(["action"=>"index"]) ?>', type:'GET', headers:{'X-Requested-With':'XMLHttpRequest'} },
        columns:[
            {data:'job_level_code', render:d=>'<code>'+d+'</code>', responsivePriority:1},
            {data:'job_level'},
            {data:'travel_type', render:d=> d==='local' ? '🇳🇬 Local' : '🌍 International'},
            {data:'accommodation_rate', render:(d,_,row)=> (row.currency||'')+' '+Number(d||0).toLocaleString()},
            {data:'per_diem_rate', render:(d,_,row)=> (row.currency||'')+' '+Number(d||0).toLocaleString()},
            {data:'transport_rate', render:(d,_,row)=> (row.currency||'')+' '+Number(d||0).toLocaleString()},
            {data:'currency'},
            {data:'created', render:d=> d ? '📅 '+d.substring(0,10) : ''},
            {data:null, orderable:false, searchable:false, render:function(row){
                const editUrl='<?= $this->Url->build(["action"=>"edit"]) ?>/'+row.id;
                return '<div style="display:flex;gap:6px;"><a class="btn-icon-sm" title="Edit" href="'+editUrl+'" style="background:#F64500;color:#fff;width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;border-radius:4px;">✏️</a></div>';
            }}
        ],
        dom:'<"top"Bfl>rt<"bottom"ip><"clear">',
        buttons:[{extend:'copy',text:'📋 Copy'},{extend:'csv',text:'📊 CSV'},{extend:'print',text:'🖨️ Print'}],
        pageLength:10,
        lengthMenu:[[10,25,50,100],[10,25,50,100]],
        language:{ search:'🔍 Search:', emptyTable:'No allowance rates found' }
    });
});
</script>
