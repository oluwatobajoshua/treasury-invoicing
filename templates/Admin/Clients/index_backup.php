<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 */
$this->assign('title', 'Products Management');
?>

<!-- Premium DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.bootstrap5.min.css">

<style>
:root {
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --shadow-lg: 0 10px 40px rgba(0,0,0,0.12);
    --shadow-xl: 0 20px 60px rgba(0,0,0,0.15);
}

.premium-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 3rem 2rem;
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    margin-bottom: 2.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.premium-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.premium-header-content {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.page-title-group h1 {
    margin: 0;
    font-size: 2.25rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-title-group h1 .icon-wrapper {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.page-subtitle {
    margin: 0.5rem 0 0 0;
    opacity: 0.95;
    font-size: 1.1rem;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.75rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left: 4px solid #667eea;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
    margin: 0.5rem 0;
}

.premium-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
}

.card-toolbar {
    background: linear-gradient(to right, #f8fafc, #ffffff);
    padding: 1.5rem;
    border-bottom: 2px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.toolbar-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-premium {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
}

.card-body-premium {
    padding: 2rem;
}

table.dataTable {
    border-collapse: separate !important;
    border-spacing: 0;
}

table.dataTable thead th {
    background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #374151;
    border-bottom: 2px solid #667eea;
    padding: 1.25rem 1rem;
}

table.dataTable tbody tr {
    transition: all 0.2s ease;
}

table.dataTable tbody tr:hover {
    background: linear-gradient(to right, #faf5ff, #ffffff) !important;
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
}

table.dataTable tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f3f4f6;
}

.badge-premium {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.badge-success-premium {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.badge-inactive-premium {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    color: #6b7280;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action-premium {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.btn-action-premium:hover {
    transform: scale(1.1);
}

.btn-view {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.btn-edit {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.btn-delete {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.dataTables_wrapper .dataTables_filter input {
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    padding: 0.75rem 1.25rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.dataTables_wrapper .dataTables_length select {
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    padding: 0.5rem 2rem 0.5rem 1rem;
}

.dt-buttons {
    display: flex;
    gap: 0.5rem;
}

.dt-button {
    background: white !important;
    border: 2px solid #e5e7eb !important;
    border-radius: 10px !important;
    padding: 0.5rem 1rem !important;
    font-weight: 600 !important;
    transition: all 0.2s ease !important;
}

.dt-button:hover {
    border-color: #667eea !important;
    background: #f8f9ff !important;
}

.product-name-cell {
    font-weight: 700;
    color: #1f2937;
    font-size: 1rem;
}

.product-code-badge {
    background: linear-gradient(135deg, #ede9fe, #ddd6fe);
    color: #5b21b6;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-weight: 700;
    font-size: 0.875rem;
}

.price-display {
    font-size: 1.125rem;
    font-weight: 800;
    color: #059669;
}
</style>

<div class="premium-header">
    <div class="premium-header-content">
        <div class="page-title-group">
            <h1>
                <span class="icon-wrapper">
                    <i class="fas fa-box"></i>
                </span>
                Products Management
            </h1>
            <p class="page-subtitle">Complete product catalog with real-time inventory tracking</p>
        </div>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-plus-circle"></i> Add New Product',
                ['action' => 'add'],
                ['class' => 'btn btn-premium btn-gradient-success', 'escape' => false]
            ) ?>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-label">Total Products</div>
        <div class="stat-value"><?= count($products) ?></div>
    </div>
    
    <div class="stat-card" style="border-left-color: #10b981;">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-label">Active Products</div>
        <div class="stat-value">
            <?php 
            $activeCount = 0;
            foreach ($products as $p) {
                if ($p->is_active) $activeCount++;
            }
            echo $activeCount;
            ?>
        </div>
    </div>
    
    <div class="stat-card" style="border-left-color: #f59e0b;">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-label">Avg. Unit Price</div>
        <div class="stat-value">
            <?php 
            $total = 0;
            $count = 0;
            foreach ($products as $p) {
                if ($p->unit_price) {
                    $total += $p->unit_price;
                    $count++;
                }
            }
            echo $count > 0 ? '$' . number_format($total / $count, 2) : '$0';
            ?>
        </div>
    </div>
    
    <div class="stat-card" style="border-left-color: #3b82f6;">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white;">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-label">Added This Month</div>
        <div class="stat-value">
            <?php 
            $thisMonth = 0;
            $currentMonth = date('Y-m');
            foreach ($products as $p) {
                if ($p->created->format('Y-m') === $currentMonth) {
                    $thisMonth++;
                }
            }
            echo $thisMonth;
            ?>
        </div>
    </div>
</div>

<div class="premium-card">
    <div class="card-toolbar">
        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #1f2937;">
<!-- Premium DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    const table = $('#productsTable').DataTable({
        dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>Brtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn-sm',
                orientation: 'landscape',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'colvis',
                text: '<i class="fas fa-columns"></i> Columns',
                className: 'btn-sm'
            }
        ],
        pageLength: 25,
        order: [[1, 'asc']],
        responsive: true,
        fixedHeader: true,
        columnDefs: [
            { orderable: false, targets: -1 },
            { responsivePriority: 1, targets: 1 },
            { responsivePriority: 2, targets: -1 }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "🔍 Search products...",
            lengthMenu: "Display _MENU_ records",
            info: "Showing _START_ to _END_ of _TOTAL_ products",
            infoEmpty: "No products available",
            infoFiltered: "(filtered from _MAX_ total records)",
            zeroRecords: "No matching products found",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next →",
                previous: "← Previous"
            }
        },
        drawCallback: function() {
            // Add animation to rows
            $('.dataTables_wrapper tbody tr').each(function(index) {
                $(this).css({
                    'animation': `fadeIn 0.5s ease ${index * 0.05}s both`
                });
            });
        }
    });
    
    // Custom search with debounce
    let searchTimer;
    $('.dataTables_filter input').on('keyup', function() {
        clearTimeout(searchTimer);
        const searchValue = this.value;
        searchTimer = setTimeout(function() {
            table.search(searchValue).draw();
        }, 300);
    });
});                     <td><strong>#<?= $this->Number->format($product->id) ?></strong></td>
                        <td>
                            <div class="product-name-cell"><?= h($product->name) ?></div>
                        </td>
                        <td>
                            <span class="product-code-badge"><?= h($product->product_code) ?></span>
                        </td>
                        <td style="max-width: 300px;">
                            <?= h($product->description) ?>
                        </td>
                        <td>
                            <?php if ($product->unit_price): ?>
                                <span class="price-display">$<?= $this->Number->format($product->unit_price, ['places' => 2]) ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($product->is_active): ?>
                                <span class="badge-premium badge-success-premium">
                                    <i class="fas fa-check-circle"></i> Active
                                </span>
                            <?php else: ?>
                                <span class="badge-premium badge-inactive-premium">
                                    <i class="fas fa-times-circle"></i> Inactive
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?= h($product->created->format('M d, Y')) ?></td>
                        <td>
                            <div class="action-buttons" style="justify-content: center;">
                                <?= $this->Html->link(
                                    '<i class="fas fa-eye"></i>',
                                    ['action' => 'view', $product->id],
                                    ['class' => 'btn-action-premium btn-view', 'escape' => false, 'title' => 'View Details']
                                ) ?>
                                <?= $this->Html->link(
                                    '<i class="fas fa-edit"></i>',
                                    ['action' => 'edit', $product->id],
                                    ['class' => 'btn-action-premium btn-edit', 'escape' => false, 'title' => 'Edit Product']
                                ) ?>
                                <?= $this->Form->postLink(
                                    '<i class="fas fa-trash"></i>',
                                    ['action' => 'delete', $product->id],
                                    [
                                        'confirm' => __('Are you sure you want to delete {0}?', $product->name),
                                        'class' => 'btn-action-premium btn-delete',
                                        'escape' => false,
                                        'title' => 'Delete Product',
                                        'data-product-name' => $product->name,
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
<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    const table = $('#productsTable').DataTable({
        dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>Brtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn-sm',
                orientation: 'landscape',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'colvis',
                text: '<i class="fas fa-columns"></i> Columns',
                className: 'btn-sm'
            }
        ],
        pageLength: 25,
        order: [[1, 'asc']],
        responsive: true,
        fixedHeader: true,
        columnDefs: [
            { orderable: false, targets: -1 },
            { responsivePriority: 1, targets: 1 },
            { responsivePriority: 2, targets: -1 }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "🔍 Search products...",
            lengthMenu: "Display _MENU_ records",
            info: "Showing _START_ to _END_ of _TOTAL_ products",
            infoEmpty: "No products available",
            infoFiltered: "(filtered from _MAX_ total records)",
            zeroRecords: "No matching products found",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next →",
                previous: "← Previous"
            }
        },
        drawCallback: function() {
            // Add animation to rows
            $('.dataTables_wrapper tbody tr').each(function(index) {
                $(this).css({
                    'animation': `fadeIn 0.5s ease ${index * 0.05}s both`
                });
            });
        }
    });
    
    // Custom search with debounce
    let searchTimer;
    $('.dataTables_filter input').on('keyup', function() {
        clearTimeout(searchTimer);
        const searchValue = this.value;
        searchTimer = setTimeout(function() {
            table.search(searchValue).draw();
        }, 300);
    });
});

// Professional SweetAlert delete confirmation
function confirmDelete(event, element) {
    event.preventDefault();
    
    const productName = element.dataset.productName;
    const form = element.closest('form');
    
    Swal.fire({
        title: 'Delete Product?',
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
