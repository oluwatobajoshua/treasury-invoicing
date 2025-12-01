<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AuditLog $auditLog
 */
$this->assign('title', 'Audit Log Details');
?>

<style>
/* Page Header */
.page-header-sleek {
    background: linear-gradient(135deg, #0c5343 0%, #083d2f 100%);
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    color: white;
    box-shadow: 0 2px 8px rgba(12,83,67,0.15);
}

.page-header-sleek h1 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Detail Card */
.detail-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.detail-card-header {
    background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
    padding: 1.25rem;
    border-bottom: 2px solid #e5e7eb;
}

.detail-card-header h2 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-card-body {
    padding: 1.5rem;
}

.detail-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.detail-field {
    padding: 0;
}

.detail-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1rem;
    font-weight: 500;
    color: #111827;
}

.detail-value.large {
    font-size: 1.5rem;
    font-weight: 700;
}

/* Action Badge */
.action-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.action-badge.create { background: #d1fae5; color: #065f46; }
.action-badge.update { background: #dbeafe; color: #1e40af; }
.action-badge.delete { background: #fee2e2; color: #991b1b; }
.action-badge.login { background: #fef3c7; color: #92400e; }
.action-badge.logout { background: #e5e7eb; color: #374151; }

/* Code Block */
.code-block {
    background: #1e293b;
    color: #e2e8f0;
    padding: 1rem;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    overflow-x: auto;
}

/* Button Group */
.btn-group-sleek {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}
</style>

<!-- Page Header -->
<div class="page-header-sleek">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h1>
            <i class="fas fa-history"></i>
            Audit Log Details
        </h1>
        <div class="btn-group-sleek">
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Back to List',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-trash"></i> Delete',
                ['action' => 'delete', $auditLog->id],
                [
                    'confirm' => __('Are you sure you want to delete this audit log?'),
                    'class' => 'btn btn-danger',
                    'escape' => false
                ]
            ) ?>
        </div>
    </div>
</div>

<!-- Log Information -->
<div class="detail-card">
    <div class="detail-card-header">
        <h2><i class="fas fa-info-circle"></i> Log Information</h2>
    </div>
    <div class="detail-card-body">
        <div class="detail-row">
            <div class="detail-field">
                <div class="detail-label">Log ID</div>
                <div class="detail-value large">#<?= h($auditLog->id) ?></div>
            </div>
            <div class="detail-field">
                <div class="detail-label">Action Type</div>
                <div class="detail-value">
                    <span class="action-badge <?= h(strtolower($auditLog->action)) ?>">
                        <?= h(ucfirst($auditLog->action)) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-field">
                <div class="detail-label">Model</div>
                <div class="detail-value"><?= h($auditLog->model) ?></div>
            </div>
            <div class="detail-field">
                <div class="detail-label">Record ID</div>
                <div class="detail-value"><?= h($auditLog->record_id ?: 'N/A') ?></div>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-field">
                <div class="detail-label">User</div>
                <div class="detail-value">
                    <?php if ($auditLog->has('user')): ?>
                        <strong><?= h($auditLog->user->name) ?></strong><br>
                        <small style="color: #6b7280;"><?= h($auditLog->user->email) ?></small>
                    <?php else: ?>
                        <span style="color: #6b7280;">System / Anonymous</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="detail-field">
                <div class="detail-label">Date & Time</div>
                <div class="detail-value"><?= h($auditLog->created->format('F d, Y H:i:s')) ?></div>
            </div>
        </div>

        <?php if ($auditLog->description): ?>
        <div class="detail-row">
            <div class="detail-field" style="grid-column: 1 / -1;">
                <div class="detail-label">Description</div>
                <div class="detail-value"><?= h($auditLog->description) ?></div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Request Information -->
<div class="detail-card">
    <div class="detail-card-header">
        <h2><i class="fas fa-network-wired"></i> Request Information</h2>
    </div>
    <div class="detail-card-body">
        <div class="detail-row">
            <div class="detail-field">
                <div class="detail-label">IP Address</div>
                <div class="detail-value">
                    <code style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 4px;">
                        <?= h($auditLog->ip_address ?: 'Not recorded') ?>
                    </code>
                </div>
            </div>
        </div>

        <?php if ($auditLog->user_agent): ?>
        <div class="detail-row">
            <div class="detail-field" style="grid-column: 1 / -1;">
                <div class="detail-label">User Agent</div>
                <div class="code-block">
                    <?= h($auditLog->user_agent) ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Timeline -->
<div class="detail-card">
    <div class="detail-card-header">
        <h2><i class="fas fa-clock"></i> Timeline</h2>
    </div>
    <div class="detail-card-body">
        <div style="padding: 1rem; background: #f9fafb; border-left: 4px solid #0c5343; border-radius: 4px;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                <i class="fas fa-calendar-check" style="color: #0c5343; font-size: 1.25rem;"></i>
                <div>
                    <div style="font-weight: 600; color: #111827;">Log Created</div>
                    <div style="font-size: 0.875rem; color: #6b7280;">
                        <?= h($auditLog->created->format('l, F d, Y \a\t g:i:s A')) ?>
                    </div>
                </div>
            </div>
            <?php if ($auditLog->modified && $auditLog->modified != $auditLog->created): ?>
            <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1rem;">
                <i class="fas fa-edit" style="color: #ff5722; font-size: 1.25rem;"></i>
                <div>
                    <div style="font-weight: 600; color: #111827;">Last Modified</div>
                    <div style="font-size: 0.875rem; color: #6b7280;">
                        <?= h($auditLog->modified->format('l, F d, Y \a\t g:i:s A')) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
