<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Bank> $banks
 */
$this->assign('title', 'Bank Accounts');
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
.page-header {
    background: linear-gradient(135deg, var(--primary) 0%, #094d3d 100%);
    padding: 1.5rem 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 12px rgba(12, 83, 67, 0.15);
}
.page-header h2 {
    margin: 0 0 .5rem;
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.page-header p {
    margin: 0;
    font-size: .875rem;
    color: rgba(255, 255, 255, 0.9);
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}
.stat-card .icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}
.stat-card.blue .icon {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #059669;
}
.stat-card.green .icon {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #059669;
}
.stat-card.orange .icon {
    background: linear-gradient(135deg, #ffe4e1 0%, #ffcccb 100%);
    color: #dc2626;
}
.stat-card.purple .icon {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #2563eb;
}
.stat-card .value {
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: .25rem;
}
.stat-card .label {
    font-size: .875rem;
    color: #6b7280;
    font-weight: 600;
}
.content-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
}
.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}
.table-header h3 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
}
.btn {
    padding: .625rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    transition: all 0.3s ease;
    text-decoration: none;
}
.btn-primary {
    background: linear-gradient(135deg, var(--primary) 0%, #094d3d 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(12, 83, 67, 0.3);
}
.btn-primary:hover {
    box-shadow: 0 6px 16px rgba(12, 83, 67, 0.4);
    transform: translateY(-2px);
}
.table-responsive {
    overflow-x: auto;
}
table.dataTable {
    width: 100%;
    border-collapse: collapse;
}
table.dataTable thead th {
    padding: 1rem;
    text-align: left;
    font-size: .75rem;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: .05em;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-bottom: 2px solid #e5e7eb;
}
table.dataTable tbody td {
    padding: 1rem;
    font-size: .875rem;
    color: #374151;
    border-bottom: 1px solid #f3f4f6;
}
table.dataTable tbody tr {
    transition: background 0.2s ease;
}
table.dataTable tbody tr:hover {
    background: #f9fafb;
}
.badge {
    display: inline-flex;
    align-items: center;
    gap: .375rem;
    padding: .375rem .75rem;
    border-radius: 6px;
    font-size: .75rem;
    font-weight: 600;
}
.badge.success {
    background: #d1fae5;
    color: #065f46;
}
.badge.danger {
    background: #fee2e2;
    color: #991b1b;
}
.badge.primary {
    background: #dbeafe;
    color: #1e40af;
}
.badge.warning {
    background: #ffe4e1;
    color: #991b1b;
}
.badge.purple {
    background: #dbeafe;
    color: #1e40af;
}
.action-buttons {
    display: flex;
    gap: .5rem;
}
.btn-sm {
    padding: .375rem .75rem;
    font-size: .75rem;
}
.btn-outline {
    background: white;
    color: #6b7280;
    border: 2px solid #e5e7eb;
}
.btn-outline:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}
.btn-danger {
    background: #ef4444;
    color: white;
}
.btn-danger:hover {
    background: #dc2626;
}
.no-data {
    text-align: center;
    padding: 4rem 2rem;
    color: #9ca3af;
}
.no-data i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}
.no-data h3 {
    font-size: 1.25rem;
    color: #6b7280;
    margin-bottom: .5rem;
}
.no-data p {
    margin: .5rem 0 1.5rem;
    font-size: .9375rem;
}

/* DataTables Custom Styling */
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    padding: .5rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: .875rem;
    transition: all 0.3s ease;
}
.dataTables_wrapper .dataTables_filter input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(12, 83, 67, 0.1);
}
.dataTables_wrapper .dataTables_info {
    padding-top: 1rem;
    color: #6b7280;
    font-size: .875rem;
}
.dataTables_wrapper .dataTables_paginate {
    padding-top: 1rem;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: .5rem .75rem;
    margin: 0 .25rem;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    background: white;
    color: #374151;
    font-size: .875rem;
    cursor: pointer;
    transition: all 0.2s ease;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f9fafb;
    border-color: var(--primary);
    color: var(--primary);
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.dt-buttons {
    margin-bottom: 1rem;
    display: flex;
    gap: .5rem;
}
.dt-button {
    padding: .625rem 1.25rem !important;
    border: 2px solid #e5e7eb !important;
    border-radius: 8px !important;
    background: white !important;
    color: #374151 !important;
    font-size: .875rem !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
}
.dt-button:hover {
    background: var(--primary) !important;
    border-color: var(--primary) !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(12, 83, 67, 0.2) !important;
}
</style>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>
                <i class="fas fa-university"></i>
                Bank Accounts
            </h2>
            <p>Manage bank accounts for Sales, Sustainability, and Shipment invoices</p>
        </div>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> Add Bank',
            ['action' => 'add'],
            ['class' => 'btn btn-primary', 'escapeTitle' => false]
        ) ?>
    </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="icon"><i class="fas fa-university"></i></div>
        <div class="value"><?= $total ?></div>
        <div class="label">Total Banks</div>
    </div>
    <div class="stat-card green">
        <div class="icon"><i class="fas fa-check-circle"></i></div>
        <div class="value"><?= $active ?></div>
        <div class="label">Active</div>
    </div>
    <div class="stat-card orange">
        <div class="icon"><i class="fas fa-cash-register"></i></div>
        <div class="value"><?= $salesBanks ?></div>
        <div class="label">Sales Banks</div>
    </div>
    <div class="stat-card purple">
        <div class="icon"><i class="fas fa-ship"></i></div>
        <div class="value"><?= $shipmentBanks ?></div>
        <div class="label">Shipment Banks</div>
    </div>
    <div class="stat-card green">
        <div class="icon"><i class="fas fa-leaf"></i></div>
        <div class="value"><?= $sustainabilityBanks ?></div>
        <div class="label">Sustainability Banks</div>
    </div>
</div>

<div class="content-card">
    <div class="table-header">
        <h3><i class="fas fa-list"></i> All Banks</h3>
    </div>
    
    <?php if (!empty($banks)): ?>
    <div class="table-responsive">
        <table id="banksTable" class="table dataTable">
            <thead>
                <tr>
                    <th>Bank Name</th>
                    <th>Type</th>
                    <th>Account Number</th>
                    <th>Currency</th>
                    <th>SWIFT Code</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($banks as $bank): ?>
                <tr>
                    <td><strong><?= h($bank->bank_name) ?></strong></td>
                    <td>
                        <?php if ($bank->bank_type === 'sales'): ?>
                            <span class="badge warning"><i class="fas fa-cash-register"></i> Sales</span>
                        <?php elseif ($bank->bank_type === 'sustainability'): ?>
                            <span class="badge success"><i class="fas fa-leaf"></i> Sustainability</span>
                        <?php elseif ($bank->bank_type === 'shipment'): ?>
                            <span class="badge primary"><i class="fas fa-ship"></i> Shipment</span>
                        <?php else: ?>
                            <span class="badge success"><i class="fas fa-check-double"></i> All Types</span>
                        <?php endif; ?>
                    </td>
                    <td><?= h($bank->account_number) ?></td>
                    <td><strong><?= h($bank->currency) ?></strong></td>
                    <td><?= h($bank->swift_code) ?></td>
                    <td>
                        <?php if ($bank->is_active): ?>
                            <span class="badge success"><i class="fas fa-check-circle"></i> Active</span>
                        <?php else: ?>
                            <span class="badge danger"><i class="fas fa-times-circle"></i> Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td><?= h($bank->created->format('M d, Y')) ?></td>
                    <td>
                        <div class="action-buttons">
                            <?= $this->Html->link(
                                '<i class="fas fa-eye"></i>',
                                ['action' => 'view', $bank->id],
                                ['class' => 'btn btn-sm btn-outline', 'escapeTitle' => false, 'title' => 'View']
                            ) ?>
                            <?= $this->Html->link(
                                '<i class="fas fa-edit"></i>',
                                ['action' => 'edit', $bank->id],
                                ['class' => 'btn btn-sm btn-outline', 'escapeTitle' => false, 'title' => 'Edit']
                            ) ?>
                            <?= $this->Form->postLink(
                                '<i class="fas fa-trash"></i>',
                                ['action' => 'delete', $bank->id],
                                ['confirm' => __('Are you sure you want to delete {0}?', $bank->bank_name), 'class' => 'btn btn-sm btn-danger', 'escapeTitle' => false, 'title' => 'Delete']
                            ) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="no-data">
        <i class="fas fa-university"></i>
        <h3>No banks found</h3>
        <p>Get started by adding your first bank account</p>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> Add First Bank',
            ['action' => 'add'],
            ['class' => 'btn btn-primary', 'escapeTitle' => false]
        ) ?>
    </div>
    <?php endif; ?>
</div>

<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    $('#banksTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[6, 'desc']], // Order by created date
        dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'dt-button',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'dt-button',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'dt-button',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
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
            searchPlaceholder: "Search banks...",
            lengthMenu: "Show _MENU_ banks",
            info: "Showing _START_ to _END_ of _TOTAL_ banks",
            infoEmpty: "No banks available",
            infoFiltered: "(filtered from _MAX_ total banks)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});
</script>
