<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\FreshInvoice> $freshInvoices
 */
$this->assign('title', 'Fresh Invoices Dashboard');
$authUser = $this->request->getAttribute('authUser') ?? $this->request->getSession()->read('Auth.User');

// Calculate statistics
$totalInvoices = 0;
$totalValue = 0;
$pendingCount = 0;
$approvedCount = 0;
$rejectedCount = 0;
$draftCount = 0;

foreach ($freshInvoices as $invoice) {
    $totalInvoices++;
    $totalValue += $invoice->total_value;
    
    switch ($invoice->status) {
        case 'pending':
            $pendingCount++;
            break;
        case 'approved':
        case 'sent_to_export':
            $approvedCount++;
            break;
        case 'rejected':
            $rejectedCount++;
            break;
        case 'draft':
            $draftCount++;
            break;
    }
}
?>

<style>
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        animation: fadeInDown 0.5s ease;
    }
    
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        animation: fadeIn 0.6s ease;
    }
    
    .kpi-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
    }
    
    .kpi-card.warning::before {
        background: linear-gradient(90deg, var(--warning), #fbbf24);
    }
    
    .kpi-card.success::before {
        background: linear-gradient(90deg, var(--success), #34d399);
    }
    
    .kpi-card.danger::before {
        background: linear-gradient(90deg, var(--danger), #f87171);
    }
    
    .kpi-card.info::before {
        background: linear-gradient(90deg, var(--info), #60a5fa);
    }
    
    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .kpi-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .kpi-info h4 {
        font-size: 0.875rem;
        color: var(--gray-600);
        font-weight: 500;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .kpi-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1;
    }
    
    .kpi-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: var(--gray-100);
        color: var(--primary);
    }
    
    .kpi-card.warning .kpi-icon {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }
    
    .kpi-card.success .kpi-icon {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }
    
    .kpi-card.danger .kpi-icon {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }
    
    .kpi-card.info .kpi-icon {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
    }
    
    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        animation: fadeIn 0.7s ease;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    
    .filter-group label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
    }
    
    .filter-input {
        padding: 0.625rem 1rem;
        border: 2px solid var(--gray-200);
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    
    .filter-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(12, 83, 67, 0.1);
    }
    
    .table-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        animation: fadeIn 0.8s ease;
    }
    
    .table-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(to right, var(--gray-50), white);
    }
    
    .table-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-900);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .table-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .modern-table thead th {
        background: var(--gray-50);
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-700);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--gray-200);
    }
    
    .modern-table tbody tr {
        transition: all 0.2s;
        border-bottom: 1px solid var(--gray-100);
    }
    
    .modern-table tbody tr:hover {
        background: var(--gray-50);
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .modern-table tbody td {
        padding: 1rem 1.5rem;
        font-size: 0.9rem;
        color: var(--gray-800);
    }
    
    .invoice-number {
        font-weight: 600;
        color: var(--primary);
        font-family: 'Courier New', monospace;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-badge.draft {
        background: rgba(156, 163, 175, 0.15);
        color: var(--gray-700);
    }
    
    .status-badge.pending {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
    }
    
    .status-badge.approved, .status-badge.sent_to_export {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
    }
    
    .status-badge.rejected {
        background: rgba(239, 68, 68, 0.15);
        color: #dc2626;
    }
    
    .status-badge i {
        font-size: 0.7rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
    
    .pagination-wrapper {
        padding: 1.5rem;
        border-top: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--gray-50);
    }
    
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .pagination li a,
    .pagination li span {
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        background: white;
        color: var(--gray-700);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s;
        border: 1px solid var(--gray-200);
    }
    
    .pagination li a:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .pagination li.active span {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-icon {
        font-size: 4rem;
        color: var(--gray-300);
        margin-bottom: 1rem;
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
    }
    
    .empty-text {
        color: var(--gray-600);
        margin-bottom: 2rem;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @media (max-width: 768px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .modern-table {
            font-size: 0.8rem;
        }
        
        .modern-table thead {
            display: none;
        }
        
        .modern-table tbody tr {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 1rem;
        }
        
        .modern-table tbody td {
            padding: 0.5rem 0;
            border: none;
        }
        
        .modern-table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            display: block;
            margin-bottom: 0.25rem;
            color: var(--gray-600);
            font-size: 0.75rem;
            text-transform: uppercase;
        }
        
        .action-buttons {
            flex-wrap: wrap;
        }
        
        .pagination-wrapper {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

<!-- Dashboard Header -->
<div class="dashboard-header">
    <div>
        <h1 class="page-title">Fresh Invoices</h1>
        <p class="page-subtitle">Manage and track all fresh invoices</p>
    </div>
    <div style="display:flex;gap:1rem;align-items:center;">
        <?php
        $isAdmin = $authUser && isset($authUser['role']) && strtolower($authUser['role']) === 'admin';
        if ($isAdmin):
        ?>
            <?= $this->Html->link(
                '<i class="fas fa-cog"></i> Admin Dashboard',
                ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
                [
                    'class' => 'btn btn-lg',
                    'escape' => false,
                    'style' => 'background:linear-gradient(135deg,#ff5722 0%,#f4511e 100%);color:white;border:none;'
                ]
            ) ?>
        <?php endif; ?>
        <?= $this->Html->link(
            '<i class="fas fa-plus-circle"></i> Create Invoice',
            ['action' => 'add'],
            ['class' => 'btn btn-primary btn-lg', 'escape' => false]
        ) ?>
    </div>
</div>

<!-- KPI Cards -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-content">
            <div class="kpi-info">
                <h4>Total Invoices</h4>
                <div class="kpi-value"><?= number_format($totalInvoices) ?></div>
            </div>
            <div class="kpi-icon">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
    </div>
    
    <div class="kpi-card warning">
        <div class="kpi-content">
            <div class="kpi-info">
                <h4>Pending Approval</h4>
                <div class="kpi-value"><?= number_format($pendingCount) ?></div>
            </div>
            <div class="kpi-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="kpi-card success">
        <div class="kpi-content">
            <div class="kpi-info">
                <h4>Approved</h4>
                <div class="kpi-value"><?= number_format($approvedCount) ?></div>
            </div>
            <div class="kpi-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="kpi-card">
        <div class="kpi-content">
            <div class="kpi-info">
                <h4>Total Value</h4>
                <div class="kpi-value" style="font-size: 1.5rem;"><?= $this->Number->currency($totalValue, 'USD') ?></div>
            </div>
            <div class="kpi-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="filter-section">
    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">
        <i class="fas fa-filter"></i> Filters
    </h3>
    <div class="filter-grid">
        <div class="filter-group">
            <label>Search Invoice</label>
            <input type="text" id="searchInput" class="filter-input" placeholder="Invoice number, BL...">
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select id="statusFilter" class="filter-input">
                <option value="">All Statuses</option>
                <option value="draft">Draft</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="sent_to_export">Sent to Export</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Client</label>
            <select id="clientFilter" class="filter-input">
                <option value="">All Clients</option>
                <?php if (!empty($clients)) : ?>
                    <?php foreach ($clients as $clientName): ?>
                        <option value="<?= h($clientName) ?>"><?= h($clientName) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="filter-group">
            <label>Date Range</label>
            <input type="date" id="dateFilter" class="filter-input">
        </div>
    </div>
</div>

<!-- Invoices Table -->
<div class="table-card">
    <div class="table-header">
        <h3 class="table-title">
            <i class="fas fa-list"></i>
            Invoice List
        </h3>
        <div class="table-actions">
            <?= $this->Html->link('<i class="fas fa-upload"></i> Bulk Upload', ['action' => 'bulkUpload'], ['class' => 'btn btn-sm btn-primary', 'escape' => false, 'style' => 'margin-right:.5rem']) ?>
            <button class="btn btn-sm btn-outline" onclick="window.print()">
                <i class="fas fa-print"></i> Export
            </button>
        </div>
    </div>
    
    <?php if ($totalInvoices > 0): ?>
    <div style="overflow-x: auto;">
        <table class="modern-table" id="invoicesTable">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Client</th>
                    <th>Product</th>
                    <th>BL Number</th>
                    <th>Quantity</th>
                    <th>Total Value</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($freshInvoices as $freshInvoice): ?>
                <tr>
                    <td data-label="Invoice #">
                        <span class="invoice-number"><?= h($freshInvoice->invoice_number) ?></span>
                    </td>
                    <td data-label="Client">
                        <?= $freshInvoice->has('client') ? h($freshInvoice->client->name) : '' ?>
                    </td>
                    <td data-label="Product">
                        <?= $freshInvoice->has('product') ? h($freshInvoice->product->name) : '' ?>
                    </td>
                    <td data-label="BL Number">
                        <?= h($freshInvoice->bl_number) ?>
                    </td>
                    <td data-label="Quantity">
                        <strong><?= $this->Number->format($freshInvoice->quantity, ['places' => 3]) ?></strong> MT
                    </td>
                    <td data-label="Total Value">
                        <strong><?= $this->Number->currency($freshInvoice->total_value, 'USD') ?></strong>
                    </td>
                    <td data-label="Status">
                        <?php
                        $statusIcons = [
                            'draft' => 'fa-edit',
                            'pending' => 'fa-clock',
                            'approved' => 'fa-check-circle',
                            'sent_to_export' => 'fa-paper-plane',
                            'rejected' => 'fa-times-circle'
                        ];
                        $statusClass = str_replace('_', '-', $freshInvoice->status);
                        ?>
                        <span class="status-badge <?= h($statusClass) ?>">
                            <i class="fas <?= $statusIcons[$freshInvoice->status] ?? 'fa-circle' ?>"></i>
                            <?= h(ucfirst(str_replace('_', ' ', $freshInvoice->status))) ?>
                        </span>
                    </td>
                    <td data-label="Date">
                        <?= h($freshInvoice->invoice_date->format('M d, Y')) ?>
                    </td>
                    <td data-label="Actions">
                        <div class="action-buttons">
                            <?= $this->Html->link(
                                '<i class="fas fa-eye"></i>',
                                ['action' => 'view', $freshInvoice->id],
                                ['class' => 'btn btn-sm btn-info btn-icon', 'escape' => false, 'title' => 'View']
                            ) ?>
                            
                            <?php if ($freshInvoice->status === 'draft'): ?>
                                <?= $this->Html->link(
                                    '<i class="fas fa-edit"></i>',
                                    ['action' => 'edit', $freshInvoice->id],
                                    ['class' => 'btn btn-sm btn-warning btn-icon', 'escape' => false, 'title' => 'Edit']
                                ) ?>
                                
                                <?= $this->Form->postLink(
                                    '<i class="fas fa-trash"></i>',
                                    ['action' => 'delete', $freshInvoice->id],
                                    [
                                        'class' => 'btn btn-sm btn-danger btn-icon js-swal-post',
                                        'data-swal-title' => 'Delete Invoice?',
                                        'data-swal-text' => 'Are you sure you want to delete invoice ' . h($freshInvoice->invoice_number) . '? This action cannot be undone.',
                                        'escape' => false,
                                        'title' => 'Delete'
                                    ]
                                ) ?>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <h3 class="empty-title">No Invoices Yet</h3>
        <p class="empty-text">Get started by creating your first fresh invoice</p>
        <?= $this->Html->link(
            '<i class="fas fa-plus-circle"></i> Create First Invoice',
            ['action' => 'add'],
            ['class' => 'btn btn-primary btn-lg', 'escape' => false]
        ) ?>
    </div>
    <?php endif; ?>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
/* DataTables Custom Styling */
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.dataTables_wrapper .dataTables_filter input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(12, 83, 67, 0.1);
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, var(--primary) 0%, #094d3d 100%) !important;
    border-color: var(--primary) !important;
    color: white !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f3f4f6 !important;
    border-color: #d1d5db !important;
    color: #111827 !important;
}

.dt-buttons {
    margin-bottom: 1rem;
}

.dt-button {
    background: white !important;
    border: 2px solid #e5e7eb !important;
    border-radius: 8px !important;
    padding: 0.5rem 1rem !important;
    font-size: 0.875rem !important;
    font-weight: 600 !important;
    color: #374151 !important;
    transition: all 0.3s ease !important;
    margin-right: 0.5rem !important;
}

.dt-button:hover {
    background: var(--primary) !important;
    border-color: var(--primary) !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(12, 83, 67, 0.3) !important;
}

div.dataTables_wrapper div.dataTables_info {
    color: #6b7280;
    font-size: 0.875rem;
    padding-top: 1rem;
}
</style>

<!-- DataTables JS Libraries -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#invoicesTable').DataTable({
        pageLength: 25,
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn-success',
                exportOptions: {
                    columns: ':visible:not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn-danger',
                exportOptions: {
                    columns: ':visible:not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                exportOptions: {
                    columns: ':visible:not(:last-child)'
                }
            },
            {
                extend: 'colvis',
                text: '<i class="fas fa-columns"></i> Columns'
            }
        ],
        order: [[7, 'desc']], // Sort by Date column (index 7) descending
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search invoices...",
            lengthMenu: "Show _MENU_ invoices",
            info: "Showing _START_ to _END_ of _TOTAL_ invoices",
            infoEmpty: "No invoices found",
            infoFiltered: "(filtered from _MAX_ total invoices)",
            zeroRecords: "No matching invoices found"
        }
    });
});
</script>
