<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TravelRequest $travelRequest
 */
$this->assign('title', 'Reject Travel Request');
?>

<style>
    .rejection-container {
        max-width: 800px;
        margin: 2rem auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .rejection-header {
        background: linear-gradient(135deg, #dc3545 0%, #a02834 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    
    .rejection-header h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 700;
    }
    
    .rejection-header p {
        margin: 0;
        opacity: 0.9;
    }
    
    .rejection-body {
        padding: 2rem;
    }
    
    .request-details {
        background: var(--gray-50);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        font-weight: 600;
        color: var(--muted);
    }
    
    .detail-value {
        color: var(--text);
        text-align: right;
    }
    
    .warning-box {
        background: #fff3cd;
        border: 2px solid #ffc107;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .warning-box h3 {
        color: #856404;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .warning-box p {
        margin: 0;
        color: #856404;
    }
</style>

<div class="rejection-container">
    <div class="rejection-header">
        <h1>❌ Reject Travel Request</h1>
        <p>Provide a reason for rejecting this request</p>
    </div>
    
    <div class="rejection-body">
        <div class="warning-box">
            <h3>⚠️ Warning</h3>
            <p>
                You are about to reject this travel request. The requester will be notified of your decision.
                Please provide a clear reason for the rejection to help them understand your decision.
            </p>
        </div>
        
        <div class="request-details">
            <div class="detail-row">
                <span class="detail-label">📋 Request Number:</span>
                <span class="detail-value"><?= h($travelRequest->request_number) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">👤 Traveler:</span>
                <span class="detail-value">
                    <?= $travelRequest->has('user') ? h($travelRequest->user->first_name . ' ' . $travelRequest->user->last_name) : 'N/A' ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">📍 Destination:</span>
                <span class="detail-value"><?= h($travelRequest->destination) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">✈️ Travel Type:</span>
                <span class="detail-value"><?= h(ucfirst($travelRequest->travel_type)) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">📅 Travel Period:</span>
                <span class="detail-value">
                    <?= $travelRequest->departure_date->format('M d') ?> - <?= $travelRequest->return_date->format('M d, Y') ?>
                    (<?= h($travelRequest->duration_days) ?> days)
                </span>
            </div>
        </div>
        
        <?= $this->Form->create($travelRequest, ['url' => ['action' => 'reject', $travelRequest->id]]) ?>
        
        <div class="form-group">
            <label>📝 Reason for Rejection <span style="color: var(--danger);">*</span></label>
            <?= $this->Form->textarea('reason', [
                'placeholder' => 'Please provide a clear and detailed reason for rejecting this travel request...',
                'required' => true,
                'rows' => 5,
                'class' => 'form-control'
            ]) ?>
            <small style="display: block; margin-top: 0.5rem; color: var(--muted);">
                This reason will be sent to the requester. Please be professional and constructive.
            </small>
        </div>
        
        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; padding-top: 2rem; border-top: 2px solid var(--gray-200);">
            <?= $this->Html->link('← Back to Details', ['action' => 'view', $travelRequest->id], ['class' => 'btn btn-outline']) ?>
            <?= $this->Html->link('✅ Approve Instead', ['action' => 'approve', $travelRequest->id], ['class' => 'btn btn-success']) ?>
            <?= $this->Form->button('❌ Confirm Rejection', [
                'class' => 'btn btn-danger',
                'style' => 'font-size: 1.1rem; padding: 0.75rem 2rem;',
                'onclick' => 'return confirm("Are you sure you want to reject this travel request? This action cannot be undone.")'
            ]) ?>
        </div>
        
        <?= $this->Form->end() ?>
    </div>
</div>
