<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\SgcAccount> $sgcAccounts
 */
$this->assign('title', 'SGC Accounts Management');
?>

<!-- Premium DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
:root {
    --primary: #0c5343;
    --primary-dark: #083d2f;
    --sunbeth-orange: #ff5722;
    --success: #10b981;
}

/* Page Header - Sleek Design */
.page-header-sleek {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    padding: 1.5rem 1.75rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(12, 83, 67, 0.15);
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title-sleek {
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.page-title-sleek i {
    font-size: 1.25rem;
    opacity: 0.9;
}

.page-subtitle-sleek {
    color: rgba(255,255,255,0.85);
    font-size: 0.875rem;
    margin: 0.25rem 0 0 0;
    font-weight: 400;
}

/* Stats Row - Compact */
.stats-row-sleek {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card-sleek {
    background: white;
    border-radius: 8px;
    padding: 1.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
    border-left: 3px solid var(--primary);
}

.stat-card-sleek:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.stat-icon-sleek {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    margin-bottom: 0.75rem;
}

.stat-label-sleek {
    color: #6b7280;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.stat-value-sleek {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

/* Data Card - Clean */
.data-card-sleek {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-toolbar-sleek {
    background: #f9fafb;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.toolbar-title-sleek {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.toolbar-actions-sleek {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-sleek {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    border: none;
    text-decoration: none;
    cursor: pointer;
}

.btn-sleek:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.btn-primary-sleek {
    background: var(--primary);
    color: white !important;
}

.btn-primary-sleek:hover {
    background: var(--primary-dark);
}

.btn-success-sleek {
    background: var(--success);
    color: white !important;
}

.btn-success-sleek:hover {
    background: #059669;
}

.btn-outline-sleek {
    background: white;
    color: var(--primary);
    border: 1px solid #e5e7eb;
}

.btn-outline-sleek:hover {
    background: #f9fafb;
    border-color: var(--primary);
}

.card-body-sleek {
    padding: 1.25rem;
}

/* DataTables Styling */
table.dataTable {
    font-size: 0.875rem;
}

table.dataTable thead th {
    background: #f9fafb;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.6875rem;
    letter-spacing: 0.5px;
    color: #6b7280;
    border-bottom: 1px solid #e5e7eb;
    padding: 0.875rem 0.75rem;
}

table.dataTable tbody tr:hover {
    background: #f9fafb !important;
}

table.dataTable tbody td {
    padding: 0.875rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f3f4f6;
}

/* Badges - Compact */
.badge-sleek {
    padding: 0.25rem 0.625rem;
    border-radius: 4px;
    font-weight: 500;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
}

.badge-success-sleek {
    background: #d1fae5;
    color: #065f46;
}

.badge-inactive-sleek {
    background: #f3f4f6;
    color: #6b7280;
}

/* Action Buttons - Smaller */
.btn-action-sleek {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    text-decoration: none;
    font-size: 0.8125rem;
}

.btn-action-sleek:hover {
    transform: scale(1.08);
}

.btn-view-sleek {
    background: #3b82f6;
    color: white !important;
}

.btn-edit-sleek {
    background: var(--sunbeth-orange);
    color: white !important;
}

.btn-delete-sleek {
    background: #ef4444;
    color: white !important;
}

.product-name-sleek {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.product-code-sleek {
    background: #f3f4f6;
    color: #4b5563;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-weight: 600;
    font-size: 0.75rem;
}

.price-sleek {
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--primary);
}

.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    font-size: 0.875rem;
    padding: 0.375rem 0.625rem;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--primary);
    outline: none;
}
</style>

<div class="page-header-sleek">
    <div>
        <h1 class="page-title-sleek">
            <i class="fas fa-university"></i>
            SGC Accounts Management
        </h1>
        <p class="page-subtitle-sleek">Standard General Chart of Accounts</p>
    </div>
    <div>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> Add Account',
            ['action' => 'add'],
            ['class' => 'btn-sleek btn-success-sleek', 'escape' => false]
        ) ?>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-row-sleek">
    <div class="stat-card-sleek">
        <div class="stat-icon-sleek" style="background: var(--primary); color: white;">
            <i class="fas fa-university"></i>
        </div>
        <div class="stat-label-sleek">Total Accounts</div>
        <div class="stat-value-sleek"><?= count($sgcAccounts) ?></div>
    </div>
    
    <div class="stat-card-sleek" style="border-left-color: var(--success);">
        <div class="stat-icon-sleek" style="background: var(--success); color: white;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-label-sleek">Active</div>
        <div class="stat-value-sleek">
            <?php 
            $activeCount = 0;
            foreach ($sgcAccounts as $a) {
                if ($a->is_active) $activeCount++;
            }
            echo $activeCount;
            ?>
        </div>
    </div>
    
    <div class="stat-card-sleek" style="border-left-color: var(--sunbeth-orange);">
        <div class="stat-icon-sleek" style="background: var(--sunbeth-orange); color: white;">
            <i class="fas fa-chart-pie"></i>
        </div>
        <div class="stat-label-sleek">Asset Accounts</div>
        <div class="stat-value-sleek">
            <?php 
            $assetCount = 0;
            foreach ($sgcAccounts as $a) {
                if ($a->account_type === 'Asset') $assetCount++;
            }
            echo $assetCount;
            ?>
        </div>
    </div>
    
    <div class="stat-card-sleek" style="border-left-color: #3b82f6;">
        <div class="stat-icon-sleek" style="background: #3b82f6; color: white;">
            <i class="fas fa-calendar"></i>
        </div>
        <div class="stat-label-sleek">This Month</div>
        <div class="stat-value-sleek">
            <?php 
            $thisMonth = 0;
            $currentMonth = date('Y-m');
            foreach ($sgcAccounts as $a) {
                if ($a->created->format('Y-m') === $currentMonth) {
                    $thisMonth++;
                }
            }
            echo $thisMonth;
            ?>
        </div>
    </div>
</div>

<div class="data-card-sleek">
    <div class="card-toolbar-sleek">
        <h3 class="toolbar-title-sleek">
            <i class="fas fa-list"></i> Account List
        </h3>
        <div class="toolbar-actions-sleek">
            <button class="btn-sleek btn-outline-sleek" onclick="$('#sgcAccountsTable').DataTable().button('.buttons-excel').trigger();">
                <i class="fas fa-file-excel"></i> Excel
            </button>
            <button class="btn-sleek btn-outline-sleek" onclick="$('#sgcAccountsTable').DataTable().button('.buttons-pdf').trigger();">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
        </div>
    </div>
    
    <div class="card-body-sleek">
        <table id="sgcAccountsTable" class="table table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Account Name</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sgcAccounts as $account): ?>
                <tr>
                    <td><strong>#<?= $account->id ?></strong></td>
                    <td>
                        <span class="product-code-sleek"><?= h($account->account_code) ?></span>
                    </td>
                    <td>
                        <div class="product-name-sleek"><?= h($account->account_name) ?></div>
                    </td>
                    <td>
                        <?php
                        $typeColors = [
                            'Asset' => 'success',
                            'Liability' => 'danger',
                            'Equity' => 'primary',
                            'Revenue' => 'info',
                            'Expense' => 'warning'
                        ];
                        $type = $account->account_type;
                        $badgeClass = $typeColors[$type] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $badgeClass ?>" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                            <?= h($type) ?>
                        </span>
                    </td>
                    <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <?= h($account->description) ?>
                    </td>
                    <td>
                        <?php if ($account->is_active): ?>
                            <span class="badge-sleek badge-success-sleek">
                                <i class="fas fa-check"></i> Active
                            </span>
                        <?php else: ?>
                            <span class="badge-sleek badge-inactive-sleek">
                                <i class="fas fa-times"></i> Inactive
                            </span>
                        <?php endif; ?>
                    </td>
                    <td><?= h($account->created->format('M d, Y')) ?></td>
                    <td>
                        <div style="display: flex; gap: 0.375rem;">
                            <?= $this->Html->link(
                                '<i class="fas fa-eye"></i>',
                                ['action' => 'view', $account->id],
                                ['class' => 'btn-action-sleek btn-view-sleek', 'escape' => false, 'title' => 'View']
                            ) ?>
                            <?= $this->Html->link(
                                '<i class="fas fa-edit"></i>',
                                ['action' => 'edit', $account->id],
                                ['class' => 'btn-action-sleek btn-edit-sleek', 'escape' => false, 'title' => 'Edit']
                            ) ?>
                            <?= $this->Form->postLink(
                                '<i class="fas fa-trash"></i>',
                                ['action' => 'delete', $account->id],
                                [
                                    'confirm' => __('Are you sure you want to delete {0}?', $account->account_name),
                                    'class' => 'btn-action-sleek btn-delete-sleek',
                                    'escape' => false,
                                    'title' => 'Delete',
                                    'data-product-name' => $account->account_name,
                                    'onclick' => 'return confirmDelete(event, this);'
                                ]
                            ) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Premium DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    $('#sgcAccountsTable').DataTable({
        dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>Brtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn-sm',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn-sm',
                orientation: 'landscape',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn-sm',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
            }
        ],
        pageLength: 25,
        order: [[1, 'asc']],
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "🔍 Search accounts...",
            lengthMenu: "Display _MENU_ records",
            info: "Showing _START_ to _END_ of _TOTAL_ accounts",
            zeroRecords: "No matching accounts found"
        }
    });
});

function confirmDelete(event, element) {
    event.preventDefault();
    const productName = element.dataset.productName;
    const form = element.closest('form');
    
    Swal.fire({
        title: 'Delete Account?',
        html: `Are you sure you want to delete <strong>${productName}</strong>?<br><br>This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    
    return false;
}
</script>
