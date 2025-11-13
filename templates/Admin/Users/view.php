<?php
/** @var \App\View\AppView $this */
$this->assign('title', 'User Details');
?>
<style>
.detail-card {background:var(--card);border:var(--border);border-radius:8px;padding:1rem;max-width:780px;margin:0 auto;box-shadow:0 1px 3px rgba(0,0,0,.06);} 
.detail-grid {display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:.6rem;margin-top:.6rem;} 
.detail-item {background:var(--gray-50);border:1px solid var(--gray-200);border-radius:6px;padding:.55rem .65rem;display:flex;flex-direction:column;gap:.25rem;} 
.detail-item span.label {font-size:.55rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);font-weight:600;} 
.detail-item span.value {font-size:.75rem;font-weight:600;color:var(--text);} 
.actions-bar {display:flex;justify-content:flex-end;gap:.5rem;margin-top:.8rem;} 
.badge-role {padding:.25rem .5rem;border-radius:12px;font-size:.6rem;font-weight:600;display:inline-block;} 
.badge-role.admin {background:#0c5343;color:#fff;} 
.badge-role.manager {background:#F64500;color:#fff;} 
.badge-role.user {background:var(--gray-300);color:#222;} 
</style>
<div class="detail-card fade-in">
    <h2 style="margin:0;font-size:1rem;display:flex;align-items:center;gap:.4rem;">👤 User Profile <small class="muted" style="font-size:.65rem;font-weight:500;">ID #<?= (int)$user->id ?></small></h2>
    <div class="detail-grid">
        <div class="detail-item"><span class="label">Email</span><span class="value"><?= h($user->email) ?></span></div>
        <div class="detail-item"><span class="label">Name</span><span class="value"><?= h(trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))) ?></span></div>
        <div class="detail-item"><span class="label">Role</span><span class="value"><span class="badge-role <?= h($user->role) ?>"><?= h(ucfirst($user->role)) ?></span></span></div>
        <div class="detail-item"><span class="label">Active</span><span class="value"><?= $user->is_active ? '✅ Yes' : '❌ No' ?></span></div>
        <div class="detail-item"><span class="label">Last Login</span><span class="value"><?= $user->last_login ? h($user->last_login->format('Y-m-d H:i')) : '—' ?></span></div>
        <div class="detail-item"><span class="label">Microsoft ID</span><span class="value" style="font-family:monospace;"><?= h($user->microsoft_id ?: '—') ?></span></div>
        <div class="detail-item"><span class="label">Job Level</span><span class="value"><?= $user->job_level_id ? h($user->job_level->level_name ?? $user->job_level_id) : '—' ?></span></div>
    </div>
    <div class="actions-bar">
        <?= $this->Html->link('← Back', ['action' => 'index'], ['class' => 'btn btn-outline btn-sm']) ?>
        <?= $this->Html->link('✏️ Edit', ['action' => 'edit', $user->id], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php if ($user->is_active): ?>
            <?= $this->Form->postLink('Deactivate', ['action' => 'deactivate', $user->id], ['class' => 'btn btn-outline btn-sm', 'confirm' => 'Deactivate this user?']) ?>
        <?php else: ?>
            <?= $this->Form->postLink('Activate', ['action' => 'activate', $user->id], ['class' => 'btn btn-outline btn-sm']) ?>
        <?php endif; ?>
    </div>
</div>
