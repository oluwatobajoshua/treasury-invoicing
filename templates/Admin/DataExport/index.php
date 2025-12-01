<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Data Export & Compliance');
?>

<style>
:root{--primary:#0c5343;--primary-dark:#083d2f}
.page-header-sleek{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);padding:1.5rem 1.75rem;border-radius:8px;box-shadow:0 2px 8px rgba(12,83,67,.15);margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.page-title-sleek{color:#fff;font-size:1.5rem;font-weight:600;margin:0;display:flex;align-items:center;gap:.75rem}
.page-subtitle-sleek{color:rgba(255,255,255,.85);font-size:.875rem;margin:.25rem 0 0 0}
</style>

<div class="container-fluid py-4">
    <div class="page-header-sleek">
        <div>
            <h1 class="page-title-sleek"><i class="fas fa-file-export"></i> <?= __('Data Export & Compliance') ?></h1>
            <p class="page-subtitle-sleek">Export data for compliance, auditing, and reporting purposes</p>
        </div>
    </div>

    <!-- Export Options -->
    <div class="row g-4">
        <!-- Audit Logs Export -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clipboard-list fa-3x text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">Audit Logs Export</h5>
                            <p class="text-muted mb-0">Export complete audit trail with filters</p>
                        </div>
                    </div>
                    
                    <form id="auditLogsExportForm" class="mt-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="start_date">
                            </div>
                            <div class="col-md-6">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="end_date">
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-download me-2"></i>Export Audit Logs
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6 class="fw-bold mb-2"><i class="fas fa-info-circle me-2"></i>Export Includes:</h6>
                        <ul class="mb-0 small">
                            <li>All user actions (Create, Update, Delete)</li>
                            <li>Timestamps and IP addresses</li>
                            <li>Old and new values for changes</li>
                            <li>User information</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Invoices Export -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-invoice fa-3x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">All Invoices Export</h5>
                            <p class="text-muted mb-0">Export all invoice types in one file</p>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <?= $this->Html->link(
                            '<i class="fas fa-download me-2"></i>Export All Invoices',
                            ['action' => 'allInvoices'],
                            ['class' => 'btn btn-success', 'escape' => false]
                        ) ?>
                    </div>
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6 class="fw-bold mb-2"><i class="fas fa-info-circle me-2"></i>Export Includes:</h6>
                        <ul class="mb-0 small">
                            <li>Fresh Invoices (with client, product details)</li>
                            <li>Final Invoices (with landed quantities)</li>
                            <li>Sales Invoices (with pricing details)</li>
                            <li>Sustainability Invoices (with investments)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compliance Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Compliance & Data Protection</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="fw-bold text-primary">
                                <i class="fas fa-check-circle me-2"></i>Audit Trail
                            </h6>
                            <p class="small text-muted">
                                Complete logging of all data changes with user tracking, 
                                timestamps, and IP addresses for full accountability.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold text-success">
                                <i class="fas fa-check-circle me-2"></i>Data Backup
                            </h6>
                            <p class="small text-muted">
                                Regular database backups with restore capability ensure 
                                data protection and disaster recovery.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold text-info">
                                <i class="fas fa-check-circle me-2"></i>Export Compliance
                            </h6>
                            <p class="small text-muted">
                                Data export functionality for regulatory compliance, 
                                auditing, and reporting requirements.
                            </p>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="fw-bold text-warning">
                                <i class="fas fa-user-shield me-2"></i>User Accountability
                            </h6>
                            <p class="small text-muted">
                                Every action is tracked with user ID, ensuring full 
                                accountability for all system operations.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold text-danger">
                                <i class="fas fa-lock me-2"></i>Sensitive Data Protection
                            </h6>
                            <p class="small text-muted">
                                Automatic redaction of passwords, tokens, and API keys 
                                in audit logs for security.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold text-secondary">
                                <i class="fas fa-history me-2"></i>Data Versioning
                            </h6>
                            <p class="small text-muted">
                                Track changes over time with old and new values stored 
                                for every modification.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Best Practices -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-lightbulb me-2"></i>Best Practices for Data Export
                </h6>
                <ul class="mb-0">
                    <li>Export audit logs regularly for long-term archival and compliance</li>
                    <li>Use date filters to export specific time periods</li>
                    <li>Store exported files in secure, backed-up locations</li>
                    <li>Review exported data before sharing for sensitive information</li>
                    <li>Export data before major system changes or migrations</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle audit logs export form
    $('#auditLogsExportForm').on('submit', function(e) {
        e.preventDefault();
        
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        
        var url = '<?= $this->Url->build(['action' => 'auditLogs']) ?>';
        if (startDate || endDate) {
            url += '?';
            if (startDate) url += 'start_date=' + startDate;
            if (startDate && endDate) url += '&';
            if (endDate) url += 'end_date=' + endDate;
        }
        
        window.location.href = url;
    });
    
    // Set default dates (last 30 days)
    var endDate = new Date();
    var startDate = new Date();
    startDate.setDate(startDate.getDate() - 30);
    
    $('#startDate').val(startDate.toISOString().split('T')[0]);
    $('#endDate').val(endDate.toISOString().split('T')[0]);
});
</script>

<style>
.card {
    border: none;
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}
.btn-primary {
    background-color: #0c5343;
    border-color: #0c5343;
}
.btn-primary:hover {
    background-color: #094132;
    border-color: #094132;
}
.text-primary {
    color: #0c5343 !important;
}
.bg-primary {
    background-color: #0c5343 !important;
}
</style>
