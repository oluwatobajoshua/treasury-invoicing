<?php
$this->assign('title', 'Edit User');
?>
<style>
.form-card {background:var(--card);border:var(--border);border-radius:8px;padding:1rem;max-width:760px;margin:0 auto;box-shadow:0 1px 3px rgba(0,0,0,.06);} 
.form-row {display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:.75rem;margin-bottom:.75rem;} 
fieldset {border:1px solid var(--gray-200);padding:.75rem 1rem;border-radius:6px;margin-bottom:.9rem;} 
fieldset legend {padding:0 .4rem;font-size:.65rem;text-transform:uppercase;font-weight:600;color:var(--muted);} 
.actions-bar {display:flex;justify-content:flex-end;gap:.6rem;margin-top:.5rem;} 
.back-link {font-size:.65rem;color:var(--muted);text-decoration:none;} 
</style>
<div class="form-card fade-in">
    <h2 style="margin:0 0 .9rem;font-size:1rem;display:flex;align-items:center;gap:.4rem;">👤 Edit User <small class="muted" style="font-size:.7rem;font-weight:500;">ID #<?= (int)$user->id ?></small></h2>
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend>Profile</legend>
        <div class="form-row">
            <?= $this->Form->control('first_name', ['label' => 'First Name']) ?>
            <?= $this->Form->control('last_name', ['label' => 'Last Name']) ?>
            <?= $this->Form->control('department', ['label' => 'Department']) ?>
            <?= $this->Form->control('phone', ['label' => 'Phone']) ?>
        </div>
    </fieldset>
    <fieldset>
        <legend>Organization</legend>
        <div class="form-row">
            <?= $this->Form->control('job_level_id', ['type' => 'select', 'options' => $jobLevels, 'empty' => 'Select job level', 'label' => 'Job Level']) ?>
        </div>
    </fieldset>
    <fieldset>
        <legend>Access</legend>
        <div class="form-row">
            <?= $this->Form->control('role', ['type' => 'select', 'options' => $roles, 'label' => 'Role']) ?>
            <div style="display:flex;align-items:center;gap:.5rem;padding-top:.4rem;">
                <?= $this->Form->control('is_active', ['type' => 'checkbox', 'label' => 'Active?']) ?>
            </div>
        </div>
    </fieldset>
    <div class="actions-bar">
        <?= $this->Html->link('Cancel', ['action' => 'view', $user->id], ['class' => 'btn btn-outline btn-sm']) ?>
        <?= $this->Form->button('💾 Save Changes', ['class' => 'btn btn-primary']) ?>
    </div>
    <?= $this->Form->end() ?>
    <div style="margin-top:.75rem;text-align:right;">
        <?= $this->Html->link('← Back to user', ['action' => 'view', $user->id], ['class' => 'back-link']) ?>
    </div>
</div>
