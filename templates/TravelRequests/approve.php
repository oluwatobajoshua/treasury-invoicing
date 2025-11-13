<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TravelRequest $travelRequest
 */
$this->assign('title', 'Approve Travel Request');
?>

<style>
    .approval-container {
        max-width: 800px;
        margin: 2rem auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .approval-header {
        background: linear-gradient(135deg, #28a745 0%, #20793a 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    
    .approval-header h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 700;
    }
    
    .approval-header p {
        margin: 0;
        opacity: 0.9;
    }
    
    .approval-body {
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
    
    .purpose-section {
        margin: 2rem 0;
    }
    
    .purpose-section h3 {
        color: var(--primary);
        margin-bottom: 1rem;
    }
    
    .purpose-text {
        background: var(--gray-50);
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid var(--primary);
        line-height: 1.6;
    }
</style>

<div class="approval-container">
    <div class="approval-header">
        <h1>✅ Approve Travel Request</h1>
        <p>Review and approve this travel request</p>
    </div>
    
    <div class="approval-body">
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
                <span class="detail-label">🛫 Departure Date:</span>
                <span class="detail-value"><?= $travelRequest->departure_date->format('F d, Y') ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">🛬 Return Date:</span>
                <span class="detail-value"><?= $travelRequest->return_date->format('F d, Y') ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">📅 Duration:</span>
                <span class="detail-value"><?= h($travelRequest->duration_days) ?> days</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">🏨 Accommodation:</span>
                <span class="detail-value"><?= $travelRequest->accommodation_required ? 'Yes' : 'No' ?></span>
            </div>
        </div>
        
        <div class="purpose-section">
            <h3>📝 Purpose of Travel</h3>
            <div class="purpose-text">
                <?= nl2br(h($travelRequest->purpose_of_travel)) ?>
            </div>
        </div>
        
        <?= $this->Form->create($travelRequest, ['url' => ['action' => 'approve', $travelRequest->id]]) ?>
        
        <div class="form-group">
            <label>💬 Approval Comments (Optional)</label>
            <?= $this->Form->textarea('comments', [
                'placeholder' => 'Add any comments or instructions for this approval...',
                'rows' => 4,
                'class' => 'form-control'
            ]) ?>
        </div>
        
        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; padding-top: 2rem; border-top: 2px solid var(--gray-200);">
            <?= $this->Html->link('← Back to Details', ['action' => 'view', $travelRequest->id], ['class' => 'btn btn-outline']) ?>
            <?= $this->Html->link('❌ Reject Instead', ['action' => 'reject', $travelRequest->id], ['class' => 'btn btn-danger']) ?>
            <?= $this->Form->button('✅ Approve Request', [
                'class' => 'btn btn-success',
                'style' => 'font-size: 1.1rem; padding: 0.75rem 2rem;',
                'onclick' => 'return confirm("Are you sure you want to approve this travel request?")'
            ]) ?>
        </div>
        
        <?= $this->Form->end() ?>
    </div>
</div>
