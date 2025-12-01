<?php
/** @var \App\View\AppView $this */
/** @var \App\Model\Entity\User $user */
$this->assign('title', 'User Details');
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.detail-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    max-width: 900px;
    margin: 0 auto;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.user-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f3f4f6;
    margin-bottom: 2rem;
}

.user-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: white;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.user-info-header {
    flex: 1;
}

.user-info-header h2 {
    margin: 0 0 0.5rem 0;
    font-size: 1.75rem;
    font-weight: 700;
    color: #111827;
}

.user-email {
    color: #6b7280;
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-active {
    background: rgba(16, 185, 129, 0.15);
    color: #059669;
}

.status-inactive {
    background: rgba(239, 68, 68, 0.15);
    color: #dc2626;
}

.detail-section {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.detail-item {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
}

.detail-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6b7280;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.detail-value {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
}

.role-admin { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.role-user { background: #e3f2fd; color: #1976d2; }
.role-auditor { background: #fff3e0; color: #f57c00; }
.role-risk_assessment { background: #fce4ec; color: #c2185b; }
.role-treasurer { background: #e8f5e9; color: #388e3c; }

.actions-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.btn-group {
    display: flex;
    gap: 0.75rem;
}
</style>

<div class="card fade-in" style="margin-bottom: 1.5rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h2 class="title" style="margin:0;font-size:1.5rem;"><i class="fas fa-user"></i> User Profile</h2>
            <p class="muted" style="margin:0.25rem 0 0 0;">View user details and account information</p>
        </div>
        <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to Users', ['action' => 'index'], [
            'class' => 'btn btn-outline',
            'escape' => false
        ]) ?>
    </div>
</div>

<div class="detail-card fade-in">
    <div class="user-header">
        <div class="user-avatar-large">
            <?= strtoupper(substr($user->first_name ?? $user->email ?? 'U', 0, 1)) ?>
        </div>
        <div class="user-info-header">
            <h2><?= h(trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'User') ?></h2>
            <div class="user-email"><i class="fas fa-envelope"></i> <?= h($user->email) ?></div>
            <div style="margin-top: 0.5rem;">
                <?php if ($user->is_active): ?>
                    <span class="status-badge status-active">
                        <i class="fas fa-check-circle"></i> Active
                    </span>
                <?php else: ?>
                    <span class="status-badge status-inactive">
                        <i class="fas fa-times-circle"></i> Inactive
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <h3 class="section-title"><i class="fas fa-info-circle"></i> Account Information</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-user-tag"></i> Role</div>
                <div class="detail-value">
                    <span class="role-badge role-<?= h($user->role ?? 'user') ?>">
                        <i class="fas <?= $user->role === 'admin' ? 'fa-crown' : ($user->role === 'auditor' ? 'fa-clipboard-check' : 'fa-user') ?>"></i>
                        <?= h(ucfirst(str_replace('_', ' ', $user->role ?? 'user'))) ?>
                    </span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-building"></i> Department</div>
                <div class="detail-value"><?= h($user->department ?: '—') ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-phone"></i> Phone</div>
                <div class="detail-value"><?= h($user->phone ?: '—') ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-clock"></i> Last Login</div>
                <div class="detail-value">
                    <?= $user->last_login ? h($user->last_login->format('Y-m-d H:i:s')) : '<span style="color:#9ca3af;">Never</span>' ?>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-calendar-plus"></i> Registered</div>
                <div class="detail-value">
                    <?= $user->created ? h($user->created->format('Y-m-d H:i:s')) : '—' ?>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label"><i class="fab fa-microsoft"></i> Microsoft ID</div>
                <div class="detail-value" style="font-family:monospace;font-size:0.875rem;">
                    <?= h($user->microsoft_id ? substr($user->microsoft_id, 0, 20) . '...' : '—') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="actions-bar">
        <div class="btn-group">
            <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to List', ['action' => 'index'], [
                'class' => 'btn btn-outline',
                'escape' => false
            ]) ?>
        </div>
        <div class="btn-group">
            <?= $this->Html->link('<i class="fas fa-edit"></i> Edit User', ['action' => 'edit', $user->id], [
                'class' => 'btn btn-primary',
                'escape' => false
            ]) ?>
            <?php if ($user->is_active): ?>
                <?= $this->Form->postLink(
                    '<i class="fas fa-ban"></i> Deactivate',
                    ['action' => 'deactivate', $user->id],
                    [
                        'class' => 'btn',
                        'style' => 'background:#ef4444;color:white;',
                        'confirm' => 'Are you sure you want to deactivate this user?',
                        'escape' => false
                    ]
                ) ?>
            <?php else: ?>
                <?= $this->Form->postLink(
                    '<i class="fas fa-check-circle"></i> Activate',
                    ['action' => 'activate', $user->id],
                    [
                        'class' => 'btn',
                        'style' => 'background:#10b981;color:white;',
                        'escape' => false
                    ]
                ) ?>
            <?php endif; ?>
        </div>
    </div>
</div>
