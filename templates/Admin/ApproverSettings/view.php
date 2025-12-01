<?php
/** @var \App\View\AppView $this */
/** @var \App\Model\Entity\ApproverSetting $approverSetting */
$this->assign('title', 'Notification Rule Details');
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.view-header {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
}

.view-card {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
}

.context-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-right: 0.5rem;
}

.context-badge.fresh {
    background: rgba(102, 126, 234, 0.15);
    color: #5a67d8;
}

.context-badge.final {
    background: rgba(245, 87, 108, 0.15);
    color: #f5576c;
}

.context-badge.pending {
    background: rgba(245, 158, 11, 0.15);
    color: #d97706;
}

.context-badge.approved {
    background: rgba(16, 185, 129, 0.15);
    color: #059669;
}

.context-badge.rejected {
    background: rgba(239, 68, 68, 0.15);
    color: #dc2626;
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

.status-badge.active {
    background: rgba(16, 185, 129, 0.15);
    color: #059669;
}

.status-badge.inactive {
    background: rgba(239, 68, 68, 0.15);
    color: #dc2626;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
}

.role-treasurer {
    background: #e8f5e9;
    color: #388e3c;
}

.role-export {
    background: #e1f5fe;
    color: #0277bd;
}

.role-sales {
    background: #f3e5f5;
    color: #7b1fa2;
}

.info-grid {
    display: grid;
    grid-template-columns: 150px 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.info-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-value {
    font-size: 0.95rem;
    color: #111827;
}

.email-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.email {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #f3f4f6;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-family: monospace;
}

.workflow-preview {
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 2rem;
}

.workflow-preview h4 {
    margin: 0 0 1rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
}

.workflow-path {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.workflow-step {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: white;
    border: 2px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
}

.workflow-step.active {
    border-color: #3b82f6;
    background: #eff6ff;
    color: #1e40af;
}

.workflow-arrow {
    color: #9ca3af;
    font-size: 1.25rem;
}
</style>

<div class="view-header fade-in">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h2 class="title" style="margin:0;font-size:1.5rem;"><i class="fas fa-eye"></i> Notification Rule Details</h2>
            <p class="muted" style="margin:0.5rem 0 0 0;">
                <span class="context-badge <?= h($approverSetting->invoice_type) ?>">
                    <i class="fas fa-file-invoice"></i> <?= h(ucfirst($approverSetting->invoice_type)) ?> Invoice
                </span>
                <span class="context-badge <?= h($approverSetting->stage) ?>">
                    <i class="fas fa-<?= $approverSetting->stage === 'pending_approval' ? 'clock' : ($approverSetting->stage === 'approved' ? 'check-circle' : 'times-circle') ?>"></i> <?= h(ucwords(str_replace('_', ' ', $approverSetting->stage))) ?>
                </span>
                <span class="status-badge <?= $approverSetting->is_active ? 'active' : 'inactive' ?>">
                    <i class="fas fa-<?= $approverSetting->is_active ? 'check-circle' : 'times-circle' ?>"></i>
                    <?= $approverSetting->is_active ? 'Active' : 'Inactive' ?>
                </span>
            </p>
        </div>
        <div style="display:flex;gap:0.5rem;">
            <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back', ['action' => 'index'], [
                'class' => 'btn',
                'style' => 'background:#6b7280;color:white;',
                'escape' => false
            ]) ?>
            <?= $this->Html->link('<i class="fas fa-edit"></i> Edit', ['action' => 'edit', $approverSetting->id], [
                'class' => 'btn btn-primary',
                'escape' => false
            ]) ?>
        </div>
    </div>
</div>

<div class="view-card fade-in">
    <div class="info-grid">
        <div class="info-label">
            <i class="fas fa-user-tag"></i> Role / Team
        </div>
        <div class="info-value">
            <span class="role-badge role-<?= h($approverSetting->role) ?>">
                <i class="fas fa-<?= $approverSetting->role === 'treasurer' ? 'dollar-sign' : ($approverSetting->role === 'export' ? 'truck' : 'handshake') ?>"></i>
                <?= h(ucfirst($approverSetting->role)) ?>
            </span>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-label">
            <i class="fas fa-file-invoice"></i> Invoice Type
        </div>
        <div class="info-value">
            <span class="context-badge <?= h($approverSetting->invoice_type) ?>">
                <?= h(ucfirst($approverSetting->invoice_type)) ?> Invoice
            </span>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-label">
            <i class="fas fa-stream"></i> Workflow Stage
        </div>
        <div class="info-value">
            <span class="context-badge <?= h($approverSetting->stage) ?>">
                <?= h(ucwords(str_replace('_', ' ', $approverSetting->stage))) ?>
            </span>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-label">
            <i class="fas fa-envelope"></i> TO Recipients
        </div>
        <div class="info-value">
            <?php if ($approverSetting->to_emails): ?>
                <div class="email-list">
                    <?php foreach (explode(',', $approverSetting->to_emails) as $email): ?>
                        <span class="email">
                            <i class="fas fa-envelope"></i> <?= h(trim($email)) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <span style="color:#9ca3af;">No recipients configured</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-label">
            <i class="fas fa-copy"></i> CC Recipients
        </div>
        <div class="info-value">
            <?php if ($approverSetting->cc_emails): ?>
                <div class="email-list">
                    <?php foreach (explode(',', $approverSetting->cc_emails) as $email): ?>
                        <span class="email">
                            <i class="fas fa-copy"></i> <?= h(trim($email)) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <span style="color:#9ca3af;">No CC recipients</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-label">
            <i class="fas fa-toggle-on"></i> Status
        </div>
        <div class="info-value">
            <span class="status-badge <?= $approverSetting->is_active ? 'active' : 'inactive' ?>">
                <i class="fas fa-<?= $approverSetting->is_active ? 'check-circle' : 'times-circle' ?>"></i>
                <?= $approverSetting->is_active ? 'Active' : 'Inactive' ?>
            </span>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-label">
            <i class="fas fa-calendar-plus"></i> Created
        </div>
        <div class="info-value">
            <?= h($approverSetting->created->format('F j, Y \a\t g:i A')) ?>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-label">
            <i class="fas fa-calendar-edit"></i> Last Modified
        </div>
        <div class="info-value">
            <?= h($approverSetting->modified->format('F j, Y \a\t g:i A')) ?>
        </div>
    </div>

    <!-- Workflow Context -->
    <div class="workflow-preview">
        <h4><i class="fas fa-route"></i> Workflow Context</h4>
        <div class="workflow-path">
            <?php if ($approverSetting->invoice_type === 'fresh'): ?>
                <div class="workflow-step"><i class="fas fa-user"></i> User Creates Invoice</div>
                <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                
                <?php if ($approverSetting->stage === 'pending_approval'): ?>
                    <div class="workflow-step active"><i class="fas fa-paper-plane"></i> Treasurer (This Rule)</div>
                <?php else: ?>
                    <div class="workflow-step"><i class="fas fa-check"></i> Treasurer Approves</div>
                <?php endif; ?>
                
                <?php if ($approverSetting->stage === 'approved'): ?>
                    <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                    <div class="workflow-step active"><i class="fas fa-truck"></i> Export Team (This Rule)</div>
                <?php elseif ($approverSetting->stage === 'rejected'): ?>
                    <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                    <div class="workflow-step active"><i class="fas fa-times-circle"></i> Rejection Notice (This Rule)</div>
                <?php endif; ?>
            <?php else: ?>
                <div class="workflow-step"><i class="fas fa-user"></i> User Creates Final Invoice</div>
                <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                
                <?php if ($approverSetting->stage === 'pending_approval'): ?>
                    <div class="workflow-step active"><i class="fas fa-paper-plane"></i> Treasurer (This Rule)</div>
                <?php else: ?>
                    <div class="workflow-step"><i class="fas fa-check"></i> Treasurer Approves</div>
                <?php endif; ?>
                
                <?php if ($approverSetting->stage === 'approved'): ?>
                    <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                    <div class="workflow-step active"><i class="fas fa-handshake"></i> Sales Team (This Rule)</div>
                <?php elseif ($approverSetting->stage === 'rejected'): ?>
                    <div class="workflow-arrow"><i class="fas fa-arrow-right"></i></div>
                    <div class="workflow-step active"><i class="fas fa-times-circle"></i> Rejection Notice (This Rule)</div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div style="display:flex;gap:1rem;margin-top:2rem;padding-top:1.5rem;border-top:2px solid #f3f4f6;">
        <?= $this->Html->link('<i class="fas fa-edit"></i> Edit Rule', ['action' => 'edit', $approverSetting->id], [
            'class' => 'btn btn-primary',
            'escape' => false,
            'style' => 'padding:0.875rem 1.75rem;font-size:1rem;font-weight:600;'
        ]) ?>
        <?= $this->Form->postLink(
            '<i class="fas fa-toggle-on"></i> ' . ($approverSetting->is_active ? 'Deactivate' : 'Activate'),
            ['action' => 'toggle', $approverSetting->id],
            [
                'class' => 'btn',
                'style' => 'background:' . ($approverSetting->is_active ? '#ef4444' : '#10b981') . ';color:white;padding:0.875rem 1.75rem;',
                'escape' => false
            ]
        ) ?>
        <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to List', ['action' => 'index'], [
            'class' => 'btn',
            'style' => 'background:#6b7280;color:white;padding:0.875rem 1.75rem;',
            'escape' => false
        ]) ?>
    </div>
</div>
