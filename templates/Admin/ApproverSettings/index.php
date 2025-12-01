<?php
/** @var \App\View\AppView $this */
$this->assign('title', 'Approval Workflow Settings');
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root { --primary:#0c5343; --primary-dark:#083d2f; }
.page-header-sleek{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);padding:1.5rem 1.75rem;border-radius:8px;box-shadow:0 2px 8px rgba(12,83,67,.15);margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}
.page-title-sleek{color:#fff;font-size:1.5rem;font-weight:600;margin:0;display:flex;align-items:center;gap:.75rem}
.page-subtitle-sleek{color:rgba(255,255,255,.85);font-size:.875rem;margin:.25rem 0 0}
</style>

<div class="page-header-sleek">
    <div>
        <h1 class="page-title-sleek"><i class="fas fa-user-check"></i> Approval Workflow Settings</h1>
        <p class="page-subtitle-sleek">Configure notification recipients for invoice approval workflows</p>
    </div>
    <div>
        <?= $this->Html->link('<i class="fas fa-plus-circle"></i> Add New Rule', ['action' => 'add'], [ 'class' => 'btn btn-primary', 'escape' => false ]) ?>
    </div>
</div>

<style>
.workflow-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
}

.workflow-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.workflow-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.workflow-icon.fresh {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.workflow-icon.final {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.workflow-title {
    flex: 1;
}

.workflow-title h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
}

.workflow-title p {
    margin: 0.25rem 0 0 0;
    font-size: 0.875rem;
    color: #6b7280;
}

.stage-card {
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.25rem;
    margin-bottom: 1rem;
}

.stage-card:last-child {
    margin-bottom: 0;
}

.stage-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.stage-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
}

.stage-badge.pending {
    background: rgba(245, 158, 11, 0.15);
    color: #d97706;
}

.stage-badge.approved {
    background: rgba(16, 185, 129, 0.15);
    color: #059669;
}

.stage-badge.rejected {
    background: rgba(239, 68, 68, 0.15);
    color: #dc2626;
}

.recipient-row {
    display: grid;
    grid-template-columns: 120px 1fr 1fr auto auto;
    gap: 1rem;
    align-items: center;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    margin-bottom: 0.75rem;
    border: 1px solid #e5e7eb;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
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

.role-sustainability {
    background: #e6f4ea;
    color: #0c6b46;
}

.email-list {
    font-size: 0.875rem;
    color: #374151;
}

.email-list .email {
    display: inline-block;
    background: #e5e7eb;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    margin: 0.125rem;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #9ca3af;
}
</style>

<div class="card fade-in" style="margin-bottom: 1.5rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h2 class="title" style="margin:0;font-size:1.5rem;"><i class="fas fa-paper-plane"></i> Approval Workflow Settings</h2>
            <p class="muted" style="margin:0.25rem 0 0 0;">Configure notification recipients for invoice approval workflows</p>
        </div>
        <?= $this->Html->link('<i class="fas fa-plus-circle"></i> Add New Rule', ['action' => 'add'], [
            'class' => 'btn btn-primary',
            'escape' => false
        ]) ?>
    </div>
</div>

<!-- Fresh Invoice Workflow -->
<div class="workflow-section fade-in">
    <div class="workflow-header">
        <div class="workflow-icon fresh">
            <i class="fas fa-file-invoice"></i>
        </div>
        <div class="workflow-title">
            <h3>Fresh Invoice Workflow</h3>
            <p>Notification flow for initial invoices</p>
        </div>
    </div>

    <?php
    $freshPending = $groupedSettings['fresh_pending_approval'] ?? [];
    $freshApproved = $groupedSettings['fresh_approved'] ?? [];
    $freshRejected = $groupedSettings['fresh_rejected'] ?? [];
    ?>

    <!-- Stage 1: Pending Approval -->
    <div class="stage-card">
        <div class="stage-header">
            <span class="stage-badge pending">
                <i class="fas fa-clock"></i> Stage 1: Pending Approval
            </span>
            <small style="color:#6b7280;"><i class="fas fa-info-circle"></i> Sent when invoice is submitted for approval</small>
        </div>
        <?php if (!empty($freshPending)): ?>
            <?php foreach ($freshPending as $setting): ?>
                <div class="recipient-row">
                    <span class="role-badge role-<?= h($setting->role) ?>">
                        <i class="fas fa-<?= $setting->role === 'treasurer' ? 'dollar-sign' : ($setting->role === 'export' ? 'truck' : 'handshake') ?>"></i>
                        <?= h(ucfirst($setting->role)) ?>
                    </span>
                    <div>
                        <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">TO:</div>
                        <div class="email-list">
                            <?php if ($setting->to_emails): ?>
                                <?php foreach (explode(',', $setting->to_emails) as $email): ?>
                                    <span class="email"><i class="fas fa-envelope"></i> <?= h(trim($email)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">CC:</div>
                        <div class="email-list">
                            <?php if ($setting->cc_emails): ?>
                                <?php foreach (explode(',', $setting->cc_emails) as $email): ?>
                                    <span class="email"><i class="fas fa-copy"></i> <?= h(trim($email)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <?= $this->Form->postLink(
                            $setting->is_active ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>',
                            ['action' => 'toggle', $setting->id],
                            [
                                'escape' => false,
                                'title' => $setting->is_active ? 'Active - Click to deactivate' : 'Inactive - Click to activate',
                                'style' => 'font-size:1.5rem;color:' . ($setting->is_active ? '#10b981' : '#ef4444')
                            ]
                        ) ?>
                    </div>
                    <div style="display:flex;gap:0.5rem;">
                        <?= $this->Html->link('<i class="fas fa-edit"></i>', ['action' => 'edit', $setting->id], [
                            'class' => 'btn btn-sm',
                            'style' => 'background:#3b82f6;color:white;',
                            'escape' => false,
                            'title' => 'Edit'
                        ]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:0.5rem;"></i>
                <p>No recipients configured</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Stage 2: Approved -->
    <div class="stage-card">
        <div class="stage-header">
            <span class="stage-badge approved">
                <i class="fas fa-check-circle"></i> Stage 2: Approved
            </span>
            <small style="color:#6b7280;"><i class="fas fa-info-circle"></i> Sent to Export team after treasurer approval</small>
        </div>
        <?php if (!empty($freshApproved)): ?>
            <?php foreach ($freshApproved as $setting): ?>
                <div class="recipient-row">
                    <span class="role-badge role-<?= h($setting->role) ?>">
                        <i class="fas fa-<?= $setting->role === 'treasurer' ? 'dollar-sign' : ($setting->role === 'export' ? 'truck' : 'handshake') ?>"></i>
                        <?= h(ucfirst($setting->role)) ?>
                    </span>
                    <div>
                        <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">TO:</div>
                        <div class="email-list">
                            <?php if ($setting->to_emails): ?>
                                <?php foreach (explode(',', $setting->to_emails) as $email): ?>
                                    <span class="email"><i class="fas fa-envelope"></i> <?= h(trim($email)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">CC:</div>
                        <div class="email-list">
                            <?php if ($setting->cc_emails): ?>
                                <?php foreach (explode(',', $setting->cc_emails) as $email): ?>
                                    <span class="email"><i class="fas fa-copy"></i> <?= h(trim($email)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <?= $this->Form->postLink(
                            $setting->is_active ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>',
                            ['action' => 'toggle', $setting->id],
                            [
                                'escape' => false,
                                'title' => $setting->is_active ? 'Active - Click to deactivate' : 'Inactive - Click to activate',
                                'style' => 'font-size:1.5rem;color:' . ($setting->is_active ? '#10b981' : '#ef4444')
                            ]
                        ) ?>
                    </div>
                    <div style="display:flex;gap:0.5rem;">
                        <?= $this->Html->link('<i class="fas fa-edit"></i>', ['action' => 'edit', $setting->id], [
                            'class' => 'btn btn-sm',
                            'style' => 'background:#3b82f6;color:white;',
                            'escape' => false,
                            'title' => 'Edit'
                        ]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:0.5rem;"></i>
                <p>No recipients configured</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Final Invoice Workflow -->
<div class="workflow-section fade-in">
    <div class="workflow-header">
        <div class="workflow-icon final">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <div class="workflow-title">
            <h3>Final Invoice Workflow</h3>
            <p>Notification flow for final invoices (FVP prefix)</p>
        </div>
    </div>

    <?php
    $finalPending = $groupedSettings['final_pending_approval'] ?? [];
    $finalApproved = $groupedSettings['final_approved'] ?? [];
    ?>

    <!-- Stage 1: Pending Approval -->
    <div class="stage-card">
        <div class="stage-header">
            <span class="stage-badge pending">
                <i class="fas fa-clock"></i> Stage 1: Pending Approval
            </span>
            <small style="color:#6b7280;"><i class="fas fa-info-circle"></i> Sent when final invoice is submitted for approval</small>
        </div>
        <?php if (!empty($finalPending)): ?>
            <?php foreach ($finalPending as $setting): ?>
                <div class="recipient-row">
                    <span class="role-badge role-<?= h($setting->role) ?>">
                        <i class="fas fa-<?= $setting->role === 'treasurer' ? 'dollar-sign' : ($setting->role === 'export' ? 'truck' : 'handshake') ?>"></i>
                        <?= h(ucfirst($setting->role)) ?>
                    </span>
                    <div>
                        <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">TO:</div>
                        <div class="email-list">
                            <?php if ($setting->to_emails): ?>
                                <?php foreach (explode(',', $setting->to_emails) as $email): ?>
                                    <span class="email"><i class="fas fa-envelope"></i> <?= h(trim($email)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">CC:</div>
                        <div class="email-list">
                            <?php if ($setting->cc_emails): ?>
                                <?php foreach (explode(',', $setting->cc_emails) as $email): ?>
                                    <span class="email"><i class="fas fa-copy"></i> <?= h(trim($email)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <?= $this->Form->postLink(
                            $setting->is_active ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>',
                            ['action' => 'toggle', $setting->id],
                            [
                                'escape' => false,
                                'title' => $setting->is_active ? 'Active - Click to deactivate' : 'Inactive - Click to activate',
                                'style' => 'font-size:1.5rem;color:' . ($setting->is_active ? '#10b981' : '#ef4444')
                            ]
                        ) ?>
                    </div>
                    <div style="display:flex;gap:0.5rem;">
                        <?= $this->Html->link('<i class="fas fa-edit"></i>', ['action' => 'edit', $setting->id], [
                            'class' => 'btn btn-sm',
                            'style' => 'background:#3b82f6;color:white;',
                            'escape' => false,
                            'title' => 'Edit'
                        ]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:0.5rem;"></i>
                <p>No recipients configured</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Stage 2: Approved -->
    <div class="stage-card">
        <div class="stage-header">
            <span class="stage-badge approved">
                <i class="fas fa-check-circle"></i> Stage 2: Approved
            </span>
            <small style="color:#6b7280;"><i class="fas fa-info-circle"></i> Sent to Sales team after treasurer approval</small>
        </div>
        <?php if (!empty($finalApproved)): ?>
            <?php foreach ($finalApproved as $setting): ?>
                <div class="recipient-row">
                    <span class="role-badge role-<?= h($setting->role) ?>">
                        <i class="fas fa-<?= $setting->role === 'treasurer' ? 'dollar-sign' : ($setting->role === 'export' ? 'truck' : 'handshake') ?>"></i>
                        <?= h(ucfirst($setting->role)) ?>
                    </span>
                    <div>
                        <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">TO:</div>
                        <div class="email-list">
                            <?php if ($setting->to_emails): ?>
                                <?php foreach (explode(',', $setting->to_emails) as $email): ?>
                                    <span class="email"><i class="fas fa-envelope"></i> <?= h(trim($email)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">CC:</div>
                        <div class="email-list">
                            <?php if ($setting->cc_emails): ?>
                                <?php foreach (explode(',', $setting->cc_emails) as $email): ?>
                                    <span class="email"><i class="fas fa-copy"></i> <?= h(trim($email)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <?= $this->Form->postLink(
                            $setting->is_active ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>',
                            ['action' => 'toggle', $setting->id],
                            [
                                'escape' => false,
                                'title' => $setting->is_active ? 'Active - Click to deactivate' : 'Inactive - Click to activate',
                                'style' => 'font-size:1.5rem;color:' . ($setting->is_active ? '#10b981' : '#ef4444')
                            ]
                        ) ?>
                    </div>
                    <div style="display:flex;gap:0.5rem;">
                        <?= $this->Html->link('<i class="fas fa-edit"></i>', ['action' => 'edit', $setting->id], [
                            'class' => 'btn btn-sm',
                            'style' => 'background:#3b82f6;color:white;',
                            'escape' => false,
                            'title' => 'Edit'
                        ]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:0.5rem;"></i>
                <p>No recipients configured</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Sales Invoice Workflow -->
<div class="workflow-section fade-in">
    <div class="workflow-header">
        <div class="workflow-icon final" style="background:linear-gradient(135deg,#4ade80 0%, #16a34a 100%);">
            <i class="fas fa-handshake"></i>
        </div>
        <div class="workflow-title">
            <h3>Sales Invoice Workflow</h3>
            <p>Simple notification when a Sales Invoice is sent</p>
        </div>
    </div>
    <?php $salesSent = $groupedSettings['sales_sent'] ?? []; ?>
    <div class="stage-card">
        <div class="stage-header">
            <span class="stage-badge approved" style="background:rgba(56,161,105,.15);color:#047857;">
                <i class="fas fa-paper-plane"></i> Sent
            </span>
            <small style="color:#6b7280;"><i class="fas fa-info-circle"></i> Triggered when a Sales Invoice email is dispatched</small>
        </div>
        <?php if (!empty($salesSent)): ?>
            <?php foreach ($salesSent as $setting): ?>
                <div class="recipient-row">
                    <span class="role-badge role-<?= h($setting->role) ?>">
                        <i class="fas fa-<?= $setting->role === 'sales' ? 'handshake' : ($setting->role === 'sustainability' ? 'leaf' : 'envelope') ?>"></i>
                        <?= h(ucfirst($setting->role)) ?>
                    </span>
                    <div>
                        <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">TO:</div>
                        <div class="email-list">
                            <?php if ($setting->to_emails): ?>
                                <?php foreach (explode(',', $setting->to_emails) as $email): ?>
                                    <span class="email"><i class="fas fa-envelope"></i> <?= h(trim($email)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">CC:</div>
                        <div class="email-list">
                            <?php if ($setting->cc_emails): ?>
                                <?php foreach (explode(',', $setting->cc_emails) as $email): ?>
                                    <span class="email"><i class="fas fa-copy"></i> <?= h(trim($email)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <?= $this->Form->postLink(
                            $setting->is_active ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>',
                            ['action' => 'toggle', $setting->id],
                            [
                                'escape' => false,
                                'title' => $setting->is_active ? 'Active - Click to deactivate' : 'Inactive - Click to activate',
                                'style' => 'font-size:1.5rem;color:' . ($setting->is_active ? '#10b981' : '#ef4444')
                            ]
                        ) ?>
                    </div>
                    <div style="display:flex;gap:0.5rem;">
                        <?= $this->Html->link('<i class="fas fa-edit"></i>', ['action' => 'edit', $setting->id], [
                            'class' => 'btn btn-sm',
                            'style' => 'background:#3b82f6;color:white;',
                            'escape' => false,
                            'title' => 'Edit'
                        ]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:0.5rem;"></i>
                <p>No recipients configured</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Sustainability Invoice Workflow -->
<div class="workflow-section fade-in">
    <div class="workflow-header">
        <div class="workflow-icon final" style="background:linear-gradient(135deg,#22d3ee 0%, #0ea5e9 100%);">
            <i class="fas fa-leaf"></i>
        </div>
        <div class="workflow-title">
            <h3>Sustainability Invoice Workflow</h3>
            <p>Simple notification when a Sustainability Invoice is sent</p>
        </div>
    </div>
    <?php $sustSent = $groupedSettings['sustainability_sent'] ?? []; ?>
    <div class="stage-card">
        <div class="stage-header">
            <span class="stage-badge approved" style="background:rgba(34,197,94,.15);color:#047857;">
                <i class="fas fa-paper-plane"></i> Sent
            </span>
            <small style="color:#6b7280;"><i class="fas fa-info-circle"></i> Triggered when a Sustainability Invoice email is dispatched</small>
        </div>
        <?php if (!empty($sustSent)): ?>
            <?php foreach ($sustSent as $setting): ?>
                <div class="recipient-row">
                    <span class="role-badge role-<?= h($setting->role) ?>">
                        <i class="fas fa-<?= $setting->role === 'sustainability' ? 'leaf' : 'handshake' ?>"></i>
                        <?= h(ucfirst($setting->role)) ?>
                    </span>
                    <div>
                        <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">TO:</div>
                        <div class="email-list">
                            <?php if ($setting->to_emails): ?>
                                <?php foreach (explode(',', $setting->to_emails) as $email): ?>
                                    <span class="email"><i class="fas fa-envelope"></i> <?= h(trim($email)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">CC:</div>
                        <div class="email-list">
                            <?php if ($setting->cc_emails): ?>
                                <?php foreach (explode(',', $setting->cc_emails) as $email): ?>
                                    <span class="email"><i class="fas fa-copy"></i> <?= h(trim($email)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <?= $this->Form->postLink(
                            $setting->is_active ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>',
                            ['action' => 'toggle', $setting->id],
                            [
                                'escape' => false,
                                'title' => $setting->is_active ? 'Active - Click to deactivate' : 'Inactive - Click to activate',
                                'style' => 'font-size:1.5rem;color:' . ($setting->is_active ? '#10b981' : '#ef4444')
                            ]
                        ) ?>
                    </div>
                    <div style="display:flex;gap:0.5rem;">
                        <?= $this->Html->link('<i class="fas fa-edit"></i>', ['action' => 'edit', $setting->id], [
                            'class' => 'btn btn-sm',
                            'style' => 'background:#3b82f6;color:white;',
                            'escape' => false,
                            'title' => 'Edit'
                        ]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:0.5rem;"></i>
                <p>No recipients configured</p>
            </div>
        <?php endif; ?>
    </div>
</div>
