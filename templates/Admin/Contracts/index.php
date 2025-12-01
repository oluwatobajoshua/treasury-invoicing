<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Contract> $contracts
 */
$this->assign('title', 'Contracts Management');
?>

<style>
/* Sleek Page Header */
.page-header-sleek {
    background: linear-gradient(135deg, #0c5343 0%, #083d2f 100%);
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    color: white;
    box-shadow: 0 2px 8px rgba(12,83,67,0.15);
}

.page-header-sleek h1 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.page-header-sleek h1 i {
    font-size: 1.5rem;
}

/* Statistics Cards */
.stats-grid-sleek {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card-sleek {
    background: white;
    padding: 1.25rem;
    border-radius: 8px;
    border-left: 3px solid #0c5343;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card-sleek:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(12,83,67,0.15);
}

.stat-icon-sleek {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.stat-icon-sleek.primary { background: linear-gradient(135deg, #0c5343 0%, #083d2f 100%); color: white; }
.stat-icon-sleek.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
.stat-icon-sleek.orange { background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%); color: white; }
.stat-icon-sleek.info { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; }

.stat-content-sleek h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
}

.stat-content-sleek p {
    margin: 0;
    font-size: 0.875rem;
    color: #6b7280;
}

/* Table Card */
.table-card-sleek {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.table-card-header-sleek {
    padding: 1.25rem;
    border-bottom: 1px solid #e5e7eb;
}

.table-card-header-sleek h2 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
}

/* Professional Table Styling */
table.dataTable {
    border-collapse: separate;
    border-spacing: 0;
}

table.dataTable thead th {
    background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
    color: #374151;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.875rem;
    border-bottom: 2px solid #e5e7eb;
}

table.dataTable tbody td {
    padding: 0.875rem;
    color: #374151;
    font-size: 0.875rem;
    border-bottom: 1px solid #f3f4f6;
}

table.dataTable tbody tr {
    transition: background-color 0.15s;
}

table.dataTable tbody tr:hover {
    background-color: #f9fafb;
}

/* Status Badges */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.active { background: #d1fae5; color: #065f46; }
.status-badge.inactive { background: #fee2e2; color: #991b1b; }
.status-badge.pending { background: #fef3c7; color: #92400e; }
.status-badge.completed { background: #dbeafe; color: #1e40af; }

/* Action Buttons */
.action-buttons-sleek {
    display: flex;
    gap: 0.5rem;
}

.btn-action-sleek {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.875rem;
    text-decoration: none;
    color: white;
}

.btn-view-sleek {
    background: #0c5343;
}

.btn-view-sleek:hover {
    background: #083d2f;
    transform: scale(1.05);
    color: white;
}

.btn-edit-sleek {
    background: #ff5722;
}

.btn-edit-sleek:hover {
    background: #e64a19;
    transform: scale(1.05);
    color: white;
}

.btn-delete-sleek {
    background: #ef4444;
}

.btn-delete-sleek:hover {
    background: #dc2626;
    transform: scale(1.05);
    color: white;
}

/* DataTables Custom Styling */
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_length select {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 0.375rem 2rem 0.375rem 0.75rem;
    font-size: 0.875rem;
}

div.dt-buttons {
    margin-bottom: 1rem;
}

.dt-button {
    background: linear-gradient(135deg, #0c5343 0%, #083d2f 100%) !important;
    color: white !important;
    border: none !important;
    padding: 0.5rem 1rem !important;
    border-radius: 6px !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    transition: all 0.2s !important;
}

.dt-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(12,83,67,0.3) !important;
}
</style>

<!-- Page Header -->
<div class="page-header-sleek">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>
            <i class="fas fa-file-contract"></i>
            Contracts Management
        </h1>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> New Contract',
            ['action' => 'add'],
            ['class' => 'btn btn-light', 'escape' => false, 'style' => 'padding: 0.5rem 1rem; font-size: 0.875rem']
        ) ?>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid-sleek">
    <div class="stat-card-sleek">
        <div class="stat-icon-sleek primary">
            <i class="fas fa-file-contract"></i>
        </div>
        <div class="stat-content-sleek">
            <h3><?= count($contracts) ?></h3>
            <p>Total Contracts</p>
        </div>
    </div>
    
    <div class="stat-card-sleek">
        <div class="stat-icon-sleek success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content-sleek">
            <h3><?= collection($contracts)->filter(function($contract) { return $contract->status === 'active'; })->count() ?></h3>
            <p>Active Contracts</p>
        </div>
    </div>
    
    <div class="stat-card-sleek">
        <div class="stat-icon-sleek orange">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content-sleek">
            <h3><?= collection($contracts)->filter(function($contract) { return $contract->status === 'pending'; })->count() ?></h3>
            <p>Pending Contracts</p>
        </div>
    </div>
    
    <div class="stat-card-sleek">
        <div class="stat-icon-sleek info">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-content-sleek">
            <h3><?= collection($contracts)->filter(function($contract) { 
                return $contract->created && $contract->created->format('Y-m') === date('Y-m'); 
            })->count() ?></h3>
            <p>Added This Month</p>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="table-card-sleek">
    <div class="table-card-header-sleek">
        <h2><i class="fas fa-list"></i> All Contracts</h2>
    </div>
    <div style="padding: 1.25rem;">
        <table id="contractsTable" class="table table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Contract ID</th>
                    <th>Client</th>
                    <th>Product</th>
                    <th>Contract Date</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contracts as $contract): ?>
                <tr>
                    <td><?= $this->Number->format($contract->id) ?></td>
                    <td><strong><?= h($contract->contract_id) ?></strong></td>
                    <td><?= $contract->has('client') ? h($contract->client->name) : '' ?></td>
                    <td><?= $contract->has('product') ? h($contract->product->name) : '' ?></td>
                    <td><?= h($contract->contract_date ? $contract->contract_date->format('M d, Y') : '') ?></td>
                    <td><?= $this->Number->format($contract->quantity) ?> MT</td>
                    <td>$<?= $this->Number->format($contract->unit_price, ['places' => 2]) ?></td>
                    <td>
                        <span class="status-badge <?= h($contract->status) ?>">
                            <?= h(ucfirst($contract->status)) ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons-sleek">
                            <?= $this->Html->link(
                                '<i class="fas fa-eye"></i>',
                                ['action' => 'view', $contract->id],
                                ['class' => 'btn-action-sleek btn-view-sleek', 'escape' => false, 'title' => 'View']
                            ) ?>
                            <?= $this->Html->link(
                                '<i class="fas fa-edit"></i>',
                                ['action' => 'edit', $contract->id],
                                ['class' => 'btn-action-sleek btn-edit-sleek', 'escape' => false, 'title' => 'Edit']
                            ) ?>
                            <button type="button" 
                                    class="btn-action-sleek btn-delete-sleek" 
                                    onclick="confirmDeleteContract(<?= $contract->id ?>, '<?= h(addslashes($contract->contract_id)) ?>')"
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- DataTables and SweetAlert -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#contractsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'desc']],
        dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'dt-button',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'dt-button',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'dt-button',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'colvis',
                text: '<i class="fas fa-columns"></i> Columns',
                className: 'dt-button'
            }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "🔍 Search contracts...",
            lengthMenu: "Show _MENU_ contracts",
            info: "Showing _START_ to _END_ of _TOTAL_ contracts",
            infoEmpty: "No contracts available",
            infoFiltered: "(filtered from _MAX_ total contracts)",
            zeroRecords: "No matching contracts found"
        }
    });
});

function confirmDeleteContract(id, contractId) {
    Swal.fire({
        title: 'Delete Contract?',
        html: `Are you sure you want to delete contract <strong>${contractId}</strong>?<br><small class="text-muted">This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete',
        cancelButtonText: '<i class="fas fa-times"></i> Cancel',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit a form for DELETE request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= $this->Url->build(['action' => 'delete']) ?>/' + id;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_csrfToken';
            csrfToken.value = '<?= $this->request->getAttribute('csrfToken') ?>';
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
