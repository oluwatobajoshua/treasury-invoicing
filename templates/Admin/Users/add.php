<?php
/** @var \App\View\AppView $this */
/** @var \App\Model\Entity\User $user */
$this->assign('title', 'Add New User');
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.form-card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    max-width: 800px;
    margin: 0 auto;
}
.form-group {
    margin-bottom: 1.5rem;
}
.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}
.form-group label .required {
    color: #c62828;
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #1976d2;
    box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
}
.form-group .help-text {
    font-size: 0.875rem;
    color: #666;
    margin-top: 0.25rem;
}
.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
}
.error-message {
    color: #c62828;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
.role-description {
    background: #f5f5f5;
    padding: 1rem;
    border-radius: 4px;
    margin-top: 0.5rem;
    font-size: 0.875rem;
}
</style>

<div class="card fade-in" style="margin-bottom: 1.5rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h2 class="title" style="margin:0;font-size:1.5rem;"><i class="fas fa-user-plus"></i> Add New User</h2>
            <p class="muted" style="margin:0.25rem 0 0 0;">Create a new user account and assign role</p>
        </div>
        <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to Users', ['action' => 'index'], [
            'class' => 'btn btn-outline',
            'escape' => false
        ]) ?>
    </div>
</div>

<div class="form-card">
    <?= $this->Form->create($user, ['type' => 'post']) ?>
    
    <div class="form-group">
        <label for="email">
            <i class="fas fa-envelope"></i> Email Address <span class="required">*</span>
        </label>
        <div style="display:flex;gap:.5rem;align-items:center;">
            <?= $this->Form->control('email', [
                'type' => 'email',
                'label' => false,
                'required' => true,
                'placeholder' => 'user@example.com',
                'id' => 'email-input',
                'style' => 'flex:1'
            ]) ?>
            <button type="button" id="open-ms-picker" class="btn btn-outline" style="white-space:nowrap;">
                <i class="fas fa-magnifying-glass"></i> Pick from Microsoft 365
            </button>
        </div>
        <div class="help-text">Search your organization directory via Microsoft Graph and autofill fields.</div>
        <?php if ($this->Form->isFieldError('email')): ?>
            <div class="error-message"><?= $this->Form->error('email') ?></div>
        <?php endif; ?>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <div class="form-group">
            <label for="first-name">
                <i class="fas fa-user"></i> First Name <span class="required">*</span>
            </label>
            <?= $this->Form->control('first_name', [
                'label' => false,
                'required' => true,
                'placeholder' => 'John'
            ]) ?>
            <?php if ($this->Form->isFieldError('first_name')): ?>
                <div class="error-message"><?= $this->Form->error('first_name') ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="last-name">
                <i class="fas fa-user"></i> Last Name <span class="required">*</span>
            </label>
            <?= $this->Form->control('last_name', [
                'label' => false,
                'required' => true,
                'placeholder' => 'Doe'
            ]) ?>
            <?php if ($this->Form->isFieldError('last_name')): ?>
                <div class="error-message"><?= $this->Form->error('last_name') ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <div class="form-group">
            <label for="department">
                <i class="fas fa-building"></i> Department
            </label>
            <?= $this->Form->control('department', [
                'label' => false,
                'placeholder' => 'e.g., Finance, Operations'
            ]) ?>
            <?php if ($this->Form->isFieldError('department')): ?>
                <div class="error-message"><?= $this->Form->error('department') ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="phone">
                <i class="fas fa-phone"></i> Phone Number
            </label>
            <?= $this->Form->control('phone', [
                'label' => false,
                'placeholder' => '+1 (555) 123-4567'
            ]) ?>
            <?php if ($this->Form->isFieldError('phone')): ?>
                <div class="error-message"><?= $this->Form->error('phone') ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group">
        <label for="role">
            <i class="fas fa-user-tag"></i> Role <span class="required">*</span>
        </label>
        <?= $this->Form->control('role', [
            'type' => 'select',
            'options' => $roles,
            'label' => false,
            'required' => true,
            'empty' => '-- Select Role --',
            'id' => 'role-select'
        ]) ?>
        <?php if ($this->Form->isFieldError('role')): ?>
            <div class="error-message"><?= $this->Form->error('role') ?></div>
        <?php endif; ?>
        
        <div class="role-description" id="role-description" style="display:none;">
            <!-- Role description will be injected here via JavaScript -->
        </div>
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="is_active" value="1" checked>
            <i class="fas fa-check-circle"></i> Active
        </label>
        <div class="help-text">User can login and access the system</div>
    </div>

    <div class="form-actions">
        <?= $this->Form->button('<i class="fas fa-save"></i> Create User', [
            'class' => 'btn btn-primary',
            'type' => 'submit',
            'escape' => false,
            'style' => 'padding:0.75rem 1.5rem;'
        ]) ?>
        <?= $this->Html->link('<i class="fas fa-times"></i> Cancel', ['action' => 'index'], [
            'class' => 'btn btn-outline',
            'escape' => false,
            'style' => 'padding:0.75rem 1.5rem;'
        ]) ?>
    </div>

    <?= $this->Form->end() ?>
</div>

<?= $this->element('graph_user_picker') ?>
<script>
// Microsoft Graph picker (lazy-load on open)
(function(){
    const btn = document.getElementById('open-ms-picker');
    if (!btn) return;
    btn.addEventListener('click', function(){
      openGraphUserPicker({
        multiple: false,
        title: 'Find user in Microsoft 365',
        onConfirm: (emails, users)=>{
          const u = users && users[0] ? users[0] : { email: emails[0]||'', firstName:'', lastName:'', department:'' };
          const emailInput = document.getElementById('email-input');
          if (emailInput) emailInput.value = u.email || '';
          const fn = document.querySelector('[name="first_name"]');
          const ln = document.querySelector('[name="last_name"]');
          const dp = document.querySelector('[name="department"]');
          if (fn && !fn.value) fn.value = u.firstName || '';
          if (ln && !ln.value) ln.value = u.lastName || '';
          if (dp && !dp.value) dp.value = u.department || '';
        }
      });
    });
})();

document.getElementById('role-select').addEventListener('change', function() {
    const roleDescriptions = {
        'admin': '<strong><i class="fas fa-crown"></i> Administrator</strong><br>Full system access including user management, configuration, and all data operations.',
        'user': '<strong><i class="fas fa-user"></i> User</strong><br>Can create and manage invoices (Fresh & Final). Read-only access to master data (Clients, Products, Vessels, etc.).',
        'auditor': '<strong><i class="fas fa-clipboard-check"></i> Auditor</strong><br>Read-only access to all data. Full access to audit logs for compliance review.',
        'risk_assessment': '<strong><i class="fas fa-shield-alt"></i> Risk Assessment</strong><br>Read-only access to all data. Limited audit log access for risk analysis.',
        'treasurer': '<strong><i class="fas fa-dollar-sign"></i> Treasurer</strong><br>Can approve/reject invoices. View all invoice data.',
        'export': '<strong><i class="fas fa-truck"></i> Export</strong><br>Can view Fresh Invoices sent to Export department.',
        'sales': '<strong><i class="fas fa-handshake"></i> Sales</strong><br>Can view Final Invoices sent to Sales department.'
    };
    
    const descDiv = document.getElementById('role-description');
    const selectedRole = this.value;
    
    if (selectedRole && roleDescriptions[selectedRole]) {
        descDiv.innerHTML = roleDescriptions[selectedRole];
        descDiv.style.display = 'block';
    } else {
        descDiv.style.display = 'none';
    }
});
</script>
