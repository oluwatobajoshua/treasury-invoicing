<?php
/**
 * @var \App\View\AppView $this
 * @var array $settings
 * @var array $timezones
 */
$this->assign('title', 'Application Settings');
?>

<style>
:root { --primary:#0c5343; --primary-dark:#083d2f; }
.page-header-sleek{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);padding:1.5rem 1.75rem;border-radius:8px;box-shadow:0 2px 8px rgba(12,83,67,.15);margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}
.page-title-sleek{color:#fff;font-size:1.5rem;font-weight:600;margin:0;display:flex;align-items:center;gap:.75rem}
.page-subtitle-sleek{color:rgba(255,255,255,.85);font-size:.875rem;margin:.25rem 0 0}
</style>

<div class="page-header-sleek">
    <div>
        <h1 class="page-title-sleek"><i class="fas fa-cogs"></i> Application Settings</h1>
        <p class="page-subtitle-sleek">Configure system-wide settings and company information</p>
    </div>
</div>

<div class="container-fluid py-4">

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?= $this->Form->create(null, ['type' => 'post']) ?>
                    
                    <!-- Application Settings -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-cog text-primary me-2"></i>Application Settings
                        </h5>
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <?= $this->Form->control('app_name', [
                                    'label' => 'Application Name',
                                    'class' => 'form-control',
                                    'value' => $settings['app_name'],
                                    'required' => true
                                ]) ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?= $this->Form->control('currency', [
                                    'label' => 'Currency Code',
                                    'class' => 'form-control',
                                    'value' => $settings['currency'],
                                    'placeholder' => 'USD, EUR, GBP, etc.'
                                ]) ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?= $this->Form->control('currency_symbol', [
                                    'label' => 'Currency Symbol',
                                    'class' => 'form-control',
                                    'value' => $settings['currency_symbol'],
                                    'placeholder' => '$, €, £, etc.'
                                ]) ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?= $this->Form->control('date_format', [
                                    'label' => 'Date Format',
                                    'type' => 'select',
                                    'class' => 'form-control',
                                    'value' => $settings['date_format'],
                                    'options' => [
                                        'Y-m-d' => 'YYYY-MM-DD (2025-11-14)',
                                        'd/m/Y' => 'DD/MM/YYYY (14/11/2025)',
                                        'm/d/Y' => 'MM/DD/YYYY (11/14/2025)',
                                        'd-M-Y' => 'DD-MMM-YYYY (14-Nov-2025)',
                                    ]
                                ]) ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?= $this->Form->control('timezone', [
                                    'label' => 'Timezone',
                                    'type' => 'select',
                                    'class' => 'form-control',
                                    'value' => $settings['timezone'],
                                    'options' => array_combine($timezones, $timezones)
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Company Information -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-building text-success me-2"></i>Company Information
                        </h5>
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <?= $this->Form->control('company_name', [
                                    'label' => 'Company Name',
                                    'class' => 'form-control',
                                    'value' => $settings['company_name']
                                ]) ?>
                            </div>
                            
                            <div class="col-md-12">
                                <?= $this->Form->control('company_address', [
                                    'label' => 'Company Address',
                                    'type' => 'textarea',
                                    'class' => 'form-control',
                                    'value' => $settings['company_address'],
                                    'rows' => 2
                                ]) ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?= $this->Form->control('company_phone', [
                                    'label' => 'Phone Number',
                                    'class' => 'form-control',
                                    'value' => $settings['company_phone']
                                ]) ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?= $this->Form->control('company_email', [
                                    'label' => 'Email Address',
                                    'type' => 'email',
                                    'class' => 'form-control',
                                    'value' => $settings['company_email']
                                ]) ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?= $this->Form->control('company_website', [
                                    'label' => 'Website',
                                    'type' => 'url',
                                    'class' => 'form-control',
                                    'value' => $settings['company_website'],
                                    'placeholder' => 'https://www.example.com'
                                ]) ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?= $this->Form->control('tax_id', [
                                    'label' => 'Tax ID / Registration Number',
                                    'class' => 'form-control',
                                    'value' => $settings['tax_id']
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Settings -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-sliders-h text-info me-2"></i>System Settings
                        </h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <?= $this->Form->control('items_per_page', [
                                    'label' => 'Items Per Page (DataTables)',
                                    'type' => 'number',
                                    'class' => 'form-control',
                                    'value' => $settings['items_per_page'],
                                    'min' => 10,
                                    'max' => 100
                                ]) ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?= $this->Form->control('session_timeout', [
                                    'label' => 'Session Timeout (minutes)',
                                    'type' => 'number',
                                    'class' => 'form-control',
                                    'value' => $settings['session_timeout'],
                                    'min' => 5,
                                    'max' => 1440
                                ]) ?>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-check form-switch mb-2">
                                    <?= $this->Form->checkbox('enable_email_notifications', [
                                        'id' => 'enable_email_notifications',
                                        'class' => 'form-check-input',
                                        'checked' => $settings['enable_email_notifications']
                                    ]) ?>
                                    <label class="form-check-label" for="enable_email_notifications">
                                        Enable Email Notifications
                                    </label>
                                </div>
                                
                                <div class="form-check form-switch">
                                    <?= $this->Form->checkbox('enable_audit_log', [
                                        'id' => 'enable_audit_log',
                                        'class' => 'form-check-input',
                                        'checked' => $settings['enable_audit_log']
                                    ]) ?>
                                    <label class="form-check-label" for="enable_audit_log">
                                        Enable Audit Logging
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Integrations: Teams -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fab fa-microsoft text-primary me-2"></i>Microsoft Teams Notifications
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <?= $this->Form->checkbox('teams_enabled', [
                                        'id' => 'teams_enabled',
                                        'class' => 'form-check-input',
                                        'checked' => !empty($settings['teams_enabled'])
                                    ]) ?>
                                    <label class="form-check-label" for="teams_enabled">
                                        Enable Teams channel messages on submit/approve/reject
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('teams_team_id', [
                                    'label' => 'Team ID',
                                    'class' => 'form-control',
                                    'value' => $settings['teams_team_id'] ?? ''
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('teams_channel_id', [
                                    'label' => 'Channel ID',
                                    'class' => 'form-control',
                                    'value' => $settings['teams_channel_id'] ?? ''
                                ]) ?>
                            </div>
                            <div class="col-12 small text-muted">
                                Requires appropriate Graph permissions and valid IDs.
                            </div>
                        </div>
                    </div>

                    <!-- Integrations: Power Automate -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-diagram-project text-danger me-2"></i>Power Automate Approvals (with comments)
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <?= $this->Form->checkbox('power_automate_enabled', [
                                        'id' => 'power_automate_enabled',
                                        'class' => 'form-check-input',
                                        'checked' => !empty($settings['power_automate_enabled'])
                                    ]) ?>
                                    <label class="form-check-label" for="power_automate_enabled">
                                        Enable Flow trigger on submit and accept Flow callbacks for approve/reject
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?= $this->Form->control('power_automate_flow_url', [
                                    'label' => 'Flow Trigger URL (HTTP POST)',
                                    'type' => 'url',
                                    'class' => 'form-control',
                                    'value' => $settings['power_automate_flow_url'] ?? '',
                                    'placeholder' => 'https://prod-XX.westus.logic.azure.com:443/workflows/...' 
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('power_automate_secret', [
                                    'label' => 'Flow Shared Secret (for callbacks)',
                                    'class' => 'form-control',
                                    'value' => $settings['power_automate_secret'] ?? '',
                                    'type' => 'password'
                                ]) ?>
                            </div>
                            <div class="col-12 small text-muted">
                                The Flow should call back to <code>/approvals/flow-callback</code> with the shared secret.
                            </div>
                        </div>
                    </div>

                    <!-- PDF Settings -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-file-pdf text-danger me-2"></i>PDF Settings
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <?= $this->Form->control('pdf_logo', [
                                    'label' => 'Logo Filename (optional override)',
                                    'class' => 'form-control',
                                    'value' => $settings['pdf_logo'] ?? '',
                                    'placeholder' => 'e.g., sunbeth-logo.png (located in webroot/img)'
                                ]) ?>
                            </div>
                            <div class="col-12 small text-muted">
                                If empty, we will try to use the company logo from Settings; this overrides it for PDFs.
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Changes will take effect immediately
                        </small>
                    </div>
                    
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
        
        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>About Settings
                    </h6>
                    <p class="small text-muted mb-3">
                        These settings control system-wide behavior and appearance. Changes are stored in 
                        <code>config/app_settings.php</code> and persist across sessions.
                    </p>
                    
                    <h6 class="fw-bold mb-2 mt-3">
                        <i class="fas fa-shield-alt text-success me-2"></i>Security
                    </h6>
                    <p class="small text-muted mb-0">
                        All setting changes are logged in the audit trail for accountability.
                    </p>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-lightbulb text-warning me-2"></i>Tips
                    </h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li class="mb-2">The application name appears in the header and page titles</li>
                        <li class="mb-2">Company information is used in invoices and reports</li>
                        <li class="mb-2">Currency settings affect all financial displays</li>
                        <li class="mb-2">Timezone ensures correct timestamps in audit logs</li>
                        <li>Session timeout improves security for shared computers</li>
                        <li class="mb-2">Teams: enable and provide Team/Channel IDs to post stage updates</li>
                        <li>Power Automate: set a Flow URL to trigger approvals with comments, and set the shared secret for callbacks</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
.border-bottom {
    border-color: #dee2e6 !important;
}
.form-control:focus,
.form-select:focus {
    border-color: #0c5343;
    box-shadow: 0 0 0 0.2rem rgba(12, 83, 67, 0.25);
}
.form-check-input:checked {
    background-color: #0c5343;
    border-color: #0c5343;
}
</style>
