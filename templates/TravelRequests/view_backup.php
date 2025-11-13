<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TravelRequest $travelRequest
 */
?>
<style>
    .view-header {
        background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%);
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        margin-bottom: 0.5rem;
        box-shadow: 0 4px 16px rgba(12, 83, 67, 0.15);
    }
    .view-header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.35rem;
    }
    .request-number-badge {
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        backdrop-filter: blur(10px);
    }
    .status-badge-large {
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
    .view-header h1 {
        font-size: 0.95rem;
        margin: 0 0 0.2rem 0;
        font-weight: 700;
    }
    .view-header-meta {
        display: flex;
        gap: 0.75rem;
        font-size: 0.7rem;
        opacity: 0.95;
    }
    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .action-bar {
        display: flex;
        gap: 0.4rem;
        margin-bottom: 0.5rem;
        flex-wrap: wrap;
        padding: 0.5rem;
        background: var(--gray-50);
        border-radius: 6px;
        border: 1px solid var(--gray-200);
    }
    .action-bar .btn {
        font-size: 0.75rem;
        padding: 0.4rem 0.85rem;
        font-weight: 600;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .info-card {
        background: white;
        border-radius: 6px;
        padding: 0.6rem;
        border: 1px solid var(--gray-200);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }
    .info-card:hover {
        box-shadow: 0 6px 20px rgba(12, 83, 67, 0.12);
        transform: translateY(-1px);
        border-color: var(--primary);
    }
    .info-card-title {
        font-size: 0.65rem;
        text-transform: uppercase;
        color: var(--muted);
        font-weight: 600;
        letter-spacing: 0.3px;
        margin-bottom: 0.3rem;
    }
    .info-card-value {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text);
        line-height: 1.2;
    }
    .info-card-icon {
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
        display: block;
    }
    .timeline-card, .allowances-card, .section-card {
        background: white;
        border-radius: 6px;
        padding: 0.6rem;
        border: 1px solid var(--gray-200);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 0.5rem;
    }
    .section-card h3 {
        color: var(--primary);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        padding-bottom: 0.3rem;
        border-bottom: 2px solid var(--gray-200);
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 0.75rem;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.25rem;
        top: 0;
        width: 2px;
        height: 100%;
        background: var(--gray-200);
    }
    .timeline-item:last-child::before {
        display: none;
    }
    .timeline-dot {
        position: absolute;
        left: -1.5rem;
        top: 0.15rem;
        width: 0.6rem;
        height: 0.6rem;
        border-radius: 50%;
        background: white;
        border: 2px solid var(--primary);
        z-index: 1;
    }
    .timeline-dot.completed {
        background: #4CAF50;
        border-color: #4CAF50;
        box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
    }
    .timeline-dot.current {
        background: var(--accent);
        border-color: var(--accent);
        box-shadow: 0 0 0 2px rgba(246, 69, 0, 0.1);
    }
    .timeline-content {
        background: white;
        padding: 0.5rem;
        border-radius: 4px;
        border-left: 2px solid var(--primary);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .timeline-title {
        font-weight: 700;
        margin-bottom: 0.2rem;
        color: var(--primary);
        font-size: 0.75rem;
    }
    .timeline-date {
        font-size: 0.65rem;
        color: var(--muted);
        margin-bottom: 0.25rem;
    }
    .timeline-comment {
        font-size: 0.75rem;
        color: var(--text);
        line-height: 1.3;
    }
        margin-bottom: 0.5rem;
    }
    .timeline-comment {
        font-size: 0.9rem;
        color: var(--text);
        line-height: 1.6;
    }
    .allowances-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px solid var(--primary);
        border-left-width: 3px;
    }
    .allowance-breakdown {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    .allowance-item {
        text-align: center;
        padding: 0.5rem;
        background: white;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }
    .allowance-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(12, 83, 67, 0.15);
    }
    .allowance-label {
        font-size: 0.65rem;
        color: var(--muted);
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .allowance-amount {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--primary);
    }
    .total-allowance {
        grid-column: 1 / -1;
        background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%);
        color: white;
        padding: 0.65rem;
        margin-top: 0.4rem;
        border-radius: 6px;
    }
    .total-allowance .allowance-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.7rem;
    }
    .total-allowance .allowance-amount {
        color: white;
        font-size: 1.1rem;
        font-weight: 800;
    }
    
    /* Two-column layout for wider screens */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .content-grid.full-width {
        grid-template-columns: 1fr;
    }
    
    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.65rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding-bottom: 0.4rem;
        border-bottom: 2px solid var(--gray-200);
    }
    .document-preview {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 1px dashed var(--gray-300);
        border-radius: 4px;
        padding: 0.6rem;
        text-align: center;
        margin-top: 0.4rem;
        transition: all 0.3s ease;
    }
    .document-preview:hover {
        border-color: var(--primary);
        background: linear-gradient(135deg, #e8f5f1 0%, #ffffff 100%);
    }
    .document-icon {
        font-size: 1.5rem;
        margin-bottom: 0.3rem;
        color: var(--primary);
    }
    @media (max-width: 968px) {
        .content-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .view-header {
            padding: 0.85rem 1rem;
        }
        .view-header h1 {
            font-size: 1.2rem;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.75rem;
        }
        .info-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        .action-bar {
            flex-direction: column;
            gap: 0.5rem;
            padding: 0.75rem;
        }
        .action-bar .btn {
            width: 100%;
            text-align: center;
        }
        .allowance-breakdown-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 640px) {
        .view-header {
            padding: 0.75rem;
        }
        .view-header h1 {
            font-size: 1.1rem;
        }
        .view-header-meta {
            flex-direction: column;
            gap: 0.4rem;
            align-items: flex-start;
        }
        .timeline-item {
            padding: 0.75rem;
        }
        .section-card {
            padding: 0.85rem;
        }
        .request-number-badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
        }
    }
</style>

<div class="view-header">
    <div class="view-header-top">
        <div>
            <div class="request-number-badge"><?= h($travelRequest->request_number) ?></div>
        </div>
        <div>
            <?php
            $statusColors = [
                'draft' => 'background: #6c757d; color: white;',
                'submitted' => 'background: #ffc107; color: #000;',
                'lm_review' => 'background: #17a2b8; color: white;',
                'lm_approved' => 'background: #28a745; color: white;',
                'lm_rejected' => 'background: #dc3545; color: white;',
                'admin_processing' => 'background: #007bff; color: white;',
                'completed' => 'background: #28a745; color: white;',
                'cancelled' => 'background: #6c757d; color: white;',
            ];
            $statusIcons = [
                'draft' => '📝',
                'submitted' => '⏳',
                'lm_review' => '👀',
                'lm_approved' => '✅',
                'lm_rejected' => '❌',
                'admin_processing' => '⚙️',
                'completed' => '✨',
                'cancelled' => '🚫',
            ];
            $statusStyle = $statusColors[$travelRequest->status] ?? 'background: #6c757d; color: white;';
            $statusIcon = $statusIcons[$travelRequest->status] ?? '📋';
            ?>
            <span class="status-badge-large" style="<?= $statusStyle ?>">
                <?= $statusIcon ?> <?= ucwords(str_replace('_', ' ', h($travelRequest->status))) ?>
            </span>
        </div>
    </div>
    <h1>🌍 <?= h($travelRequest->destination) ?></h1>
    <div class="view-header-meta">
        <div class="meta-item">
            <span>📅</span>
            <span><?= h($travelRequest->departure_date->format('M d, Y')) ?> - <?= h($travelRequest->return_date->format('M d, Y')) ?></span>
        </div>
        <div class="meta-item">
            <span>⏱️</span>
            <span><?= h($travelRequest->duration_days) ?> Days</span>
        </div>
        <div class="meta-item">
            <span><?= $travelRequest->travel_type === 'local' ? '🇳🇬' : '🌍' ?></span>
            <span><?= ucfirst(h($travelRequest->travel_type)) ?> Travel</span>
        </div>
    </div>
</div>

<?= $this->element('progress_tracker', ['currentStep' => (int)$travelRequest->current_step]) ?>

<div class="action-bar">
    <?php if ($travelRequest->status === 'submitted'): ?>
        <!-- Line Manager Actions -->
        <button type="button" class="btn btn-success" onclick="approveRequest()" style="background: #28a745;">
            ✅ Approve Request
        </button>
        <button type="button" class="btn btn-danger" onclick="rejectRequest()">
            ❌ Reject Request
        </button>
        <!-- Resend Notification Button -->
        <?= $this->Form->postLink(
            '📧 Resend Approval Notification',
            ['action' => 'resendApprovalNotification', $travelRequest->id],
            [
                'class' => 'btn btn-outline',
                'style' => 'background: #2196F3; color: white;',
                'confirm' => 'Resend approval notification to line manager via Teams/Email?'
            ]
        ) ?>
    <?php elseif ($travelRequest->status === 'lm_approved' && (float)$travelRequest->total_allowance == 0): ?>
        <!-- Admin Action - Calculate Allowances -->
        <div style="background: #e7f3ff; border: 2px solid #2196F3; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; width: 100%;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                <span style="font-size: 2rem;">💰</span>
                <div>
                    <h4 style="margin: 0; color: #1976D2; font-size: 1rem;">Allowances Not Calculated</h4>
                    <p style="margin: 0; font-size: 0.85rem; color: #555;">Line Manager has approved. Click below to calculate allowances based on traveler's job level.</p>
                </div>
            </div>
            <?= $this->Form->postLink(
                '🧮 Calculate Allowances Now',
                ['action' => 'calculateAllowances', $travelRequest->id],
                [
                    'class' => 'btn btn-primary',
                    'style' => 'font-size: 1rem; padding: 0.75rem 1.5rem;',
                    'confirm' => 'Calculate allowances for this approved travel request?'
                ]
            ) ?>
        </div>
    <?php elseif ($travelRequest->status === 'admin_processing'): ?>
        <!-- Admin Action - Mark as Complete -->
        <div style="background: #e8f5e9; border: 2px solid #4CAF50; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; width: 100%;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                <span style="font-size: 2rem;">✅</span>
                <div>
                    <h4 style="margin: 0; color: #2E7D32; font-size: 1rem;">Ready to Complete</h4>
                    <p style="margin: 0; font-size: 0.85rem; color: #555;">Allowances have been calculated. Mark this request as complete.</p>
                </div>
            </div>
            <?= $this->Form->postLink(
                '✨ Mark as Complete',
                ['action' => 'complete', $travelRequest->id],
                [
                    'class' => 'btn btn-success',
                    'style' => 'font-size: 1rem; padding: 0.75rem 1.5rem; background: #4CAF50;',
                    'confirm' => 'Mark this travel request as complete?'
                ]
            ) ?>
        </div>
    <?php endif; ?>
    
    <?= $this->Html->link('✏️ Edit Request', ['action' => 'edit', $travelRequest->id], ['class' => 'btn btn-outline']) ?>
    <?= $this->Html->link('📋 All Requests', ['action' => 'index'], ['class' => 'btn btn-outline']) ?>
    <?= $this->Html->link('➕ New Request', ['action' => 'add'], ['class' => 'btn btn-outline']) ?>
    <?= $this->Form->postLink('🗑️ Delete', ['action' => 'delete', $travelRequest->id], ['confirm' => 'Are you sure you want to delete this travel request?', 'class' => 'btn btn-danger']) ?>
</div>

<!-- Line Manager Approval Modal -->
<div id="approvalModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 12px; max-width: 500px; width: 90%;">
        <h3 style="margin-top: 0; color: #28a745;">✅ Approve Travel Request</h3>
        <?= $this->Form->create(null, ['url' => ['action' => 'lineManagerApprove', $travelRequest->id]]) ?>
        <div class="form-group">
            <label>Comments (Optional)</label>
            <?= $this->Form->textarea('comments', [
                'rows' => 3,
                'placeholder' => 'Add any comments or conditions...',
                'style' => 'width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px;'
            ]) ?>
        </div>
        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <?= $this->Form->button('✅ Confirm Approval', ['class' => 'btn btn-success', 'style' => 'flex: 1;']) ?>
            <button type="button" class="btn btn-outline" onclick="closeApprovalModal()" style="flex: 1;">Cancel</button>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<!-- Line Manager Rejection Modal -->
<div id="rejectionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 12px; max-width: 500px; width: 90%;">
        <h3 style="margin-top: 0; color: #dc3545;">❌ Reject Travel Request</h3>
        <?= $this->Form->create(null, ['url' => ['action' => 'lineManagerReject', $travelRequest->id]]) ?>
        <div class="form-group">
            <label>Reason for Rejection <span style="color: red;">*</span></label>
            <?= $this->Form->textarea('reason', [
                'rows' => 4,
                'placeholder' => 'Please provide a reason for rejection...',
                'required' => true,
                'style' => 'width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px;'
            ]) ?>
        </div>
        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <?= $this->Form->button('❌ Confirm Rejection', ['class' => 'btn btn-danger', 'style' => 'flex: 1;']) ?>
            <button type="button" class="btn btn-outline" onclick="closeRejectionModal()" style="flex: 1;">Cancel</button>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<script>
function approveRequest() {
    document.getElementById('approvalModal').style.display = 'flex';
}

function closeApprovalModal() {
    document.getElementById('approvalModal').style.display = 'none';
}

function rejectRequest() {
    document.getElementById('rejectionModal').style.display = 'flex';
}

function closeRejectionModal() {
    document.getElementById('rejectionModal').style.display = 'none';
}
</script>

<div class="info-grid">
    <div class="info-card">
        <div class="info-card-icon">👤</div>
        <div class="info-card-title">Traveler</div>
        <div class="info-card-value">
            <?= $travelRequest->has('user') ? h($travelRequest->user->first_name . ' ' . $travelRequest->user->last_name) : 'N/A' ?>
        </div>
        <?php if ($travelRequest->has('user')): ?>
        <div style="font-size: 0.85rem; color: var(--muted); margin-top: 0.25rem;">
            <?= h($travelRequest->user->email) ?>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="info-card">
        <div class="info-card-icon">🏢</div>
        <div class="info-card-title">Department</div>
        <div class="info-card-value">
            <?= $travelRequest->has('user') ? h($travelRequest->user->department) : 'N/A' ?>
        </div>
    </div>
    
    <div class="info-card">
        <div class="info-card-icon">�</div>
        <div class="info-card-title">Job Level</div>
        <div class="info-card-value">
            <?php if ($travelRequest->has('user') && $travelRequest->user->has('job_level')): ?>
                <?= h($travelRequest->user->job_level->level_name) ?>
            <?php else: ?>
                N/A
            <?php endif; ?>
        </div>
        <?php if ($travelRequest->has('user') && $travelRequest->user->has('job_level')): ?>
        <div style="font-size: 0.85rem; color: var(--muted); margin-top: 0.25rem;">
            <?= h($travelRequest->user->job_level->level_code) ?>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="info-card">
        <div class="info-card-icon">�🛏️</div>
        <div class="info-card-title">Accommodation</div>
        <div class="info-card-value">
            <?= $travelRequest->accommodation_required ? '✅ Required' : '❌ Not Required' ?>
        </div>
    </div>
    
    <div class="info-card">
        <div class="info-card-icon">📅</div>
        <div class="info-card-title">Created On</div>
        <div class="info-card-value">
            <?= h($travelRequest->created->format('M d, Y')) ?>
        </div>
        <div style="font-size: 0.85rem; color: var(--muted); margin-top: 0.25rem;">
            <?= h($travelRequest->created->format('h:i A')) ?>
        </div>
    </div>
</div>

<!-- Two Column Layout for Purpose and Email -->
<div class="content-grid">
    <div class="section-card">
        <div class="section-title">📝 Purpose of Travel</div>
        <div style="line-height: 1.5; color: var(--text); font-size: 0.85rem;">
            <?= nl2br(h($travelRequest->purpose_of_travel)) ?>
        </div>
    </div>

    <?php if ($travelRequest->email_conversation_file): ?>
    <div class="section-card">
        <div class="section-title">📎 Email Conversation</div>
        <div class="document-preview">
            <div class="document-icon">📄</div>
            <div style="font-weight: 600; margin-bottom: 0.4rem; font-size: 0.85rem;">Email Conversation Document</div>
            <div style="color: var(--muted); margin-bottom: 0.75rem; font-size: 0.75rem;">
                Uploaded: <?= h($travelRequest->email_conversation_uploaded_at->format('M d, Y')) ?>
            </div>
            <?= $this->Html->link('📥 Download PDF', '/' . h($travelRequest->email_conversation_file), ['class' => 'btn btn-primary btn-sm', 'target' => '_blank']) ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Full Width for Allowances -->
<div class="allowances-card">
    <div class="section-title">💰 Allowances Breakdown</div>
    <div class="allowance-breakdown">
        <?php if ($travelRequest->travel_type === 'local'): ?>
            <!-- LOCAL TRAVEL -->
            <?php if ($travelRequest->accommodation_required): ?>
            <div class="allowance-item">
                <div class="allowance-label">🏨 Hotel Accommodation</div>
                <div class="allowance-amount">
                    ₦<?= number_format($travelRequest->accommodation_allowance, 2) ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="allowance-item">
                <div class="allowance-label">💵 Per Diem (Out-Station Allowance)</div>
                <div class="allowance-amount">
                    ₦<?= number_format($travelRequest->per_diem_allowance, 2) ?>
                </div>
            </div>
        <?php else: ?>
            <!-- INTERNATIONAL TRAVEL -->
            <div class="allowance-item">
                <div class="allowance-label">� Out of Station Allowance (Per Diem)</div>
                <div class="allowance-amount">
                    $<?= number_format($travelRequest->per_diem_allowance, 2) ?>
                </div>
            </div>
            <div class="allowance-item">
                <div class="allowance-label">� Transport Per Day</div>
                <div class="allowance-amount">
                    $<?= number_format($travelRequest->transport_allowance, 2) ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="allowance-item total-allowance">
            <div class="allowance-label" style="color: rgba(255,255,255,0.9);">💎 Total Allowance</div>
            <div class="allowance-amount">
                <?= $travelRequest->travel_type === 'local' ? '₦' : '$' ?>
                <?= number_format($travelRequest->total_allowance, 2) ?>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($travelRequest->workflow_history)): ?>
<div class="timeline-card">
    <div class="section-title">🕐 Workflow Timeline</div>
    <div class="timeline">
        <?php foreach ($travelRequest->workflow_history as $index => $history): ?>
        <div class="timeline-item">
            <div class="timeline-dot <?= $index < count($travelRequest->workflow_history) - 1 ? 'completed' : 'current' ?>"></div>
            <div class="timeline-content">
                <div class="timeline-title">
                    <?= ucwords(str_replace('_', ' ', h($history->to_status))) ?>
                </div>
                <div class="timeline-date">
                    <?= h($history->created->format('M d, Y h:i A')) ?>
                </div>
                <?php if ($history->comments): ?>
                <div class="timeline-comment">
                    💬 <?= h($history->comments) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php if ($travelRequest->line_manager_comments): ?>
<div class="section-card">
    <div class="section-title">💭 Line Manager Comments</div>
    <div style="background: var(--gray-50); padding: 0.75rem; border-radius: 6px; line-height: 1.6; font-size: 0.85rem;">
        <?= nl2br(h($travelRequest->line_manager_comments)) ?>
    </div>
    <?php if ($travelRequest->line_manager_approved_at): ?>
    <div style="margin-top: 0.5rem; color: var(--muted); font-size: 0.75rem;">
        Reviewed: <?= h($travelRequest->line_manager_approved_at->format('M d, Y')) ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($travelRequest->rejection_reason): ?>
<div class="section-card" style="border-left: 3px solid var(--danger);">
    <div class="section-title" style="color: var(--danger);">⚠️ Rejection Reason</div>
    <div style="background: #fff5f5; padding: 0.75rem; border-radius: 6px; line-height: 1.6; color: var(--danger); font-size: 0.85rem;">
        <?= nl2br(h($travelRequest->rejection_reason)) ?>
    </div>
    <?php if ($travelRequest->rejected_at): ?>
    <div style="margin-top: 0.5rem; color: var(--muted); font-size: 0.75rem;">
        Rejected: <?= h($travelRequest->rejected_at->format('M d, Y')) ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($travelRequest->notes): ?>
<div class="section-card">
    <div class="section-title">📌 Additional Notes</div>
    <div style="line-height: 1.6; color: var(--text); font-size: 0.85rem;">
        <?= nl2br(h($travelRequest->notes)) ?>
    </div>
</div>
<?php endif; ?>
