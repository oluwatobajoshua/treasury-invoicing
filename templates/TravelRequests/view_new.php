<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TravelRequest $travelRequest
 */
?>
<style>
    /* Professional View Styles */
    .view-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .view-header {
        background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(12, 83, 67, 0.2);
    }
    
    .view-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .header-left h1 {
        font-size: 1.8rem;
        margin: 0 0 0.5rem 0;
        font-weight: 700;
    }
    
    .request-number-badge {
        font-family: 'Courier New', monospace;
        font-size: 1rem;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.25);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        backdrop-filter: blur(10px);
        display: inline-block;
    }
    
    .status-badge-large {
        padding: 0.75rem 1.5rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .status-badge-large.pending { color: #FF9800; }
    .status-badge-large.approved { color: #4CAF50; }
    .status-badge-large.rejected { color: #f44336; }
    .status-badge-large.processing { color: #2196F3; }
    
    .action-bar {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: 8px;
        border: 1px solid var(--gray-200);
    }
    
    .section-divider {
        border: none;
        height: 2px;
        background: linear-gradient(90deg, var(--primary), transparent);
        margin: 2rem 0 1.5rem 0;
    }
    
    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid var(--gray-200);
    }
    
    .section-header h2 {
        font-size: 1.4rem;
        margin: 0;
        color: var(--primary);
        font-weight: 700;
    }
    
    .section-icon {
        font-size: 2rem;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 2px solid var(--gray-200);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .info-card:hover {
        box-shadow: 0 8px 24px rgba(12, 83, 67, 0.15);
        transform: translateY(-4px);
        border-color: var(--primary);
    }
    
    .info-card:hover::before {
        transform: scaleY(1);
    }
    
    .info-card-icon {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        display: block;
        filter: grayscale(0.2);
    }
    
    .info-card-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: var(--muted);
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }
    
    .info-card-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text);
        line-height: 1.4;
        margin-bottom: 0.25rem;
    }
    
    .info-card-meta {
        font-size: 0.85rem;
        color: var(--muted);
        margin-top: 0.5rem;
    }
    
    .content-box {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        border: 2px solid var(--gray-200);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        margin-bottom: 2rem;
    }
    
    .content-box h3 {
        color: var(--primary);
        font-size: 1.2rem;
        margin: 0 0 1rem 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .allowance-grid {
        display: grid;
        gap: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 1.5rem;
        border-radius: 8px;
        border: 2px dashed var(--gray-300);
    }
    
    .allowance-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        border: 1px solid var(--gray-200);
        transition: all 0.2s ease;
    }
    
    .allowance-row:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .allowance-label {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .allowance-amount {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
        font-family: 'Courier New', monospace;
    }
    
    .allowance-total {
        background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%);
        border: none;
        padding: 1.5rem;
        margin-top: 1rem;
    }
    
    .allowance-total .allowance-label {
        color: white;
        font-size: 1.2rem;
    }
    
    .allowance-total .allowance-amount {
        color: white;
        font-size: 2rem;
    }
    
    .timeline {
        position: relative;
        padding-left: 3rem;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2.5rem;
        top: 0;
        width: 3px;
        height: 100%;
        background: var(--gray-300);
    }
    
    .timeline-item:last-child::before {
        display: none;
    }
    
    .timeline-dot {
        position: absolute;
        left: -3rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--primary);
        z-index: 1;
        box-shadow: 0 0 0 4px rgba(12, 83, 67, 0.1);
    }
    
    .timeline-dot.completed {
        background: #4CAF50;
        border-color: #4CAF50;
        box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.1);
    }
    
    .timeline-dot.current {
        background: #F64500;
        border-color: #F64500;
        box-shadow: 0 0 0 4px rgba(246, 69, 0, 0.1);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .timeline-content {
        background: white;
        padding: 1.25rem;
        border-radius: 8px;
        border: 2px solid var(--gray-200);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .timeline-title {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--primary);
        font-size: 1rem;
    }
    
    .timeline-date {
        font-size: 0.85rem;
        color: var(--muted);
        margin-bottom: 0.75rem;
    }
    
    .timeline-comment {
        font-size: 0.95rem;
        color: var(--text);
        line-height: 1.6;
        padding: 0.75rem;
        background: var(--gray-50);
        border-radius: 6px;
        border-left: 3px solid var(--primary);
    }
    
    .rejection-box {
        background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
        border: 2px solid #ef5350;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    
    .rejection-box h3 {
        color: #c62828;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .view-header-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .timeline {
            padding-left: 2rem;
        }
        
        .timeline-dot {
            left: -2.5rem;
        }
        
        .timeline-item::before {
            left: -2rem;
        }
    }
</style>

<div class="view-container">
    <!-- Header -->
    <div class="view-header">
        <div class="view-header-content">
            <div class="header-left">
                <div class="request-number-badge">
                    <?= h($travelRequest->request_number) ?>
                </div>
                <h1><?= h($travelRequest->destination) ?> Travel Request</h1>
            </div>
            <div class="header-right">
                <?php
                    $statusClass = 'pending';
                    $statusIcon = '⏳';
                    if (in_array($travelRequest->status, ['lm_approved', 'completed'])) {
                        $statusClass = 'approved';
                        $statusIcon = '✅';
                    } elseif ($travelRequest->status === 'lm_rejected') {
                        $statusClass = 'rejected';
                        $statusIcon = '❌';
                    } elseif (in_array($travelRequest->status, ['admin_processing', 'power_automate'])) {
                        $statusClass = 'processing';
                        $statusIcon = '⚙️';
                    }
                ?>
                <div class="status-badge-large <?= $statusClass ?>">
                    <?= $statusIcon ?> <?= ucwords(str_replace('_', ' ', h($travelRequest->status))) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <?php if (in_array($travelRequest->status, ['draft', 'submitted'])): ?>
    <div class="action-bar">
        <?= $this->Html->link('✏️ Edit Request', ['action' => 'edit', $travelRequest->id], ['class' => 'btn btn-primary']) ?>
        <?= $this->Html->link('🗑️ Delete', ['action' => 'delete', $travelRequest->id], ['class' => 'btn btn-danger', 'confirm' => 'Are you sure?']) ?>
        <?= $this->Html->link('← Back to List', ['action' => 'index'], ['class' => 'btn btn-outline']) ?>
    </div>
    <?php else: ?>
    <div class="action-bar">
        <?= $this->Html->link('← Back to List', ['action' => 'index'], ['class' => 'btn btn-outline']) ?>
        <?php if ($travelRequest->status === 'lm_approved'): ?>
            <form method="post" action="<?= $this->Url->build(['action' => 'calculateAllowances', $travelRequest->id]) ?>" style="display: inline;">
                <?= $this->Form->hidden('_csrfToken', ['value' => $this->request->getAttribute('csrfToken')]) ?>
                <button type="submit" class="btn btn-success">💰 Calculate Allowances</button>
            </form>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Traveler Information Section -->
    <div class="section-header">
        <span class="section-icon">👤</span>
        <h2>Traveler Information</h2>
    </div>
    
    <div class="info-grid">
        <div class="info-card">
            <div class="info-card-icon">👤</div>
            <div class="info-card-title">Full Name</div>
            <div class="info-card-value">
                <?= $travelRequest->has('user') ? h($travelRequest->user->first_name . ' ' . $travelRequest->user->last_name) : 'N/A' ?>
            </div>
            <?php if ($travelRequest->has('user')): ?>
            <div class="info-card-meta">
                📧 <?= h($travelRequest->user->email) ?>
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
            <div class="info-card-icon">👔</div>
            <div class="info-card-title">Job Level</div>
            <div class="info-card-value">
                <?php if ($travelRequest->has('user') && $travelRequest->user->has('job_level')): ?>
                    <?= h($travelRequest->user->job_level->level_name) ?>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </div>
            <?php if ($travelRequest->has('user') && $travelRequest->user->has('job_level')): ?>
            <div class="info-card-meta">
                Level Code: <?= h($travelRequest->user->job_level->level_code) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <hr class="section-divider">

    <!-- Travel Details Section -->
    <div class="section-header">
        <span class="section-icon">✈️</span>
        <h2>Travel Details</h2>
    </div>
    
    <div class="info-grid">
        <div class="info-card">
            <div class="info-card-icon"><?= $travelRequest->travel_type === 'local' ? '🇳🇬' : '🌍' ?></div>
            <div class="info-card-title">Travel Type</div>
            <div class="info-card-value">
                <?= $travelRequest->travel_type === 'local' ? 'Local Travel' : 'International Travel' ?>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-card-icon">📍</div>
            <div class="info-card-title">Destination</div>
            <div class="info-card-value">
                <?= h($travelRequest->destination) ?>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-card-icon">🛫</div>
            <div class="info-card-title">Departure Date</div>
            <div class="info-card-value">
                <?= h($travelRequest->departure_date->format('F d, Y')) ?>
            </div>
            <div class="info-card-meta">
                <?= h($travelRequest->departure_date->format('l')) ?>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-card-icon">🛬</div>
            <div class="info-card-title">Return Date</div>
            <div class="info-card-value">
                <?= h($travelRequest->return_date->format('F d, Y')) ?>
            </div>
            <div class="info-card-meta">
                <?= h($travelRequest->return_date->format('l')) ?>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-card-icon">⏱️</div>
            <div class="info-card-title">Duration</div>
            <div class="info-card-value">
                <?= h($travelRequest->duration_days) ?> Days
            </div>
            <div class="info-card-meta">
                <?= h($travelRequest->duration_days - 1) ?> Night<?= $travelRequest->duration_days > 2 ? 's' : '' ?>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-card-icon">🛏️</div>
            <div class="info-card-title">Accommodation</div>
            <div class="info-card-value">
                <?= $travelRequest->accommodation_required ? '✅ Required' : '❌ Not Required' ?>
            </div>
        </div>
    </div>

    <!-- Purpose of Travel -->
    <div class="content-box">
        <h3>📝 Purpose of Travel</h3>
        <div style="line-height: 1.8; font-size: 1rem; color: var(--text);">
            <?= nl2br(h($travelRequest->purpose_of_travel)) ?>
        </div>
    </div>

    <?php if ($travelRequest->notes): ?>
    <!-- Additional Notes -->
    <div class="content-box">
        <h3>📌 Additional Notes</h3>
        <div style="line-height: 1.8; font-size: 1rem; color: var(--text);">
            <?= nl2br(h($travelRequest->notes)) ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($travelRequest->email_conversation_file): ?>
    <!-- Email Conversation Document -->
    <div class="content-box">
        <h3>📎 Supporting Documents</h3>
        <div style="padding: 1.5rem; background: var(--gray-50); border-radius: 8px; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📄</div>
            <div style="font-weight: 600; margin-bottom: 0.5rem; font-size: 1.1rem;">Email Conversation Document</div>
            <div style="color: var(--muted); margin-bottom: 1.5rem;">
                Uploaded: <?= h($travelRequest->email_conversation_uploaded_at->format('F d, Y \a\t h:i A')) ?>
            </div>
            <?= $this->Html->link('📥 Download Document', '/' . h($travelRequest->email_conversation_file), [
                'class' => 'btn btn-primary',
                'target' => '_blank'
            ]) ?>
        </div>
    </div>
    <?php endif; ?>

    <hr class="section-divider">

    <!-- Allowances Section -->
    <?php if ($travelRequest->total_allowance > 0): ?>
    <div class="section-header">
        <span class="section-icon">💰</span>
        <h2>Allowances Breakdown</h2>
    </div>
    
    <div class="content-box">
        <div class="allowance-grid">
            <?php if ($travelRequest->travel_type === 'local'): ?>
                <!-- LOCAL TRAVEL -->
                <?php if ($travelRequest->accommodation_required && $travelRequest->accommodation_allowance > 0): ?>
                <div class="allowance-row">
                    <div class="allowance-label">
                        <span>🏨</span> Hotel Accommodation (per night)
                    </div>
                    <div class="allowance-amount">
                        ₦<?= number_format($travelRequest->accommodation_allowance, 2) ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($travelRequest->per_diem_allowance > 0): ?>
                <div class="allowance-row">
                    <div class="allowance-label">
                        <span>💵</span> Per Diem (Out-Station Allowance)
                    </div>
                    <div class="allowance-amount">
                        ₦<?= number_format($travelRequest->per_diem_allowance, 2) ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- INTERNATIONAL TRAVEL -->
                <?php if ($travelRequest->per_diem_allowance > 0): ?>
                <div class="allowance-row">
                    <div class="allowance-label">
                        <span>💵</span> Out of Station Allowance (Per Diem)
                    </div>
                    <div class="allowance-amount">
                        $<?= number_format($travelRequest->per_diem_allowance, 2) ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($travelRequest->transport_allowance > 0): ?>
                <div class="allowance-row">
                    <div class="allowance-label">
                        <span>🚗</span> Transport (per day)
                    </div>
                    <div class="allowance-amount">
                        $<?= number_format($travelRequest->transport_allowance, 2) ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <!-- Total -->
            <div class="allowance-row allowance-total">
                <div class="allowance-label">
                    <span>💎</span> Total Allowance
                </div>
                <div class="allowance-amount">
                    <?= $travelRequest->travel_type === 'local' ? '₦' : '$' ?>
                    <?= number_format($travelRequest->total_allowance, 2) ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($travelRequest->requisition_number): ?>
    <div class="content-box" style="background: #e8f5e9; border-color: #4CAF50;">
        <h3 style="color: #2e7d32;">📋 Requisition Information</h3>
        <div style="font-size: 1.1rem; font-weight: 600;">
            Requisition Number: <span style="font-family: 'Courier New', monospace; color: #2e7d32;"><?= h($travelRequest->requisition_number) ?></span>
        </div>
        <?php if ($travelRequest->requisition_prepared_at): ?>
        <div style="color: #558b2f; margin-top: 0.5rem;">
            Prepared on: <?= h($travelRequest->requisition_prepared_at->format('F d, Y \a\t h:i A')) ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    <hr class="section-divider">

    <!-- Timeline Section -->
    <div class="section-header">
        <span class="section-icon">🕐</span>
        <h2>Request Timeline</h2>
    </div>
    
    <div class="content-box">
        <div class="timeline">
            <!-- Created -->
            <div class="timeline-item">
                <div class="timeline-dot completed"></div>
                <div class="timeline-content">
                    <div class="timeline-title">📧 Request Created</div>
                    <div class="timeline-date">
                        <?= h($travelRequest->created->format('F d, Y \a\t h:i A')) ?>
                    </div>
                    <div class="timeline-comment">
                        Travel request submitted for <?= h($travelRequest->destination) ?>
                    </div>
                </div>
            </div>
            
            <!-- Line Manager Approval -->
            <?php if ($travelRequest->line_manager_approved_at): ?>
            <div class="timeline-item">
                <div class="timeline-dot completed"></div>
                <div class="timeline-content">
                    <div class="timeline-title">✅ Line Manager Approved</div>
                    <div class="timeline-date">
                        <?= h($travelRequest->line_manager_approved_at->format('F d, Y \a\t h:i A')) ?>
                    </div>
                    <?php if ($travelRequest->line_manager_comments): ?>
                    <div class="timeline-comment">
                        💬 <?= h($travelRequest->line_manager_comments) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Rejection -->
            <?php if ($travelRequest->rejected_at): ?>
            <div class="timeline-item">
                <div class="timeline-dot" style="background: #f44336; border-color: #f44336;"></div>
                <div class="timeline-content" style="border-color: #f44336;">
                    <div class="timeline-title" style="color: #c62828;">❌ Request Rejected</div>
                    <div class="timeline-date">
                        <?= h($travelRequest->rejected_at->format('F d, Y \a\t h:i A')) ?>
                    </div>
                    <?php if ($travelRequest->rejection_reason): ?>
                    <div class="timeline-comment" style="background: #ffebee; border-color: #f44336;">
                        💬 <?= h($travelRequest->rejection_reason) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Completion -->
            <?php if ($travelRequest->completed_at): ?>
            <div class="timeline-item">
                <div class="timeline-dot completed"></div>
                <div class="timeline-content">
                    <div class="timeline-title">✨ Request Completed</div>
                    <div class="timeline-date">
                        <?= h($travelRequest->completed_at->format('F d, Y \a\t h:i A')) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Current Status -->
            <?php if (!$travelRequest->completed_at && !$travelRequest->rejected_at): ?>
            <div class="timeline-item">
                <div class="timeline-dot current"></div>
                <div class="timeline-content">
                    <div class="timeline-title">🔄 Current Status</div>
                    <div class="timeline-date">
                        <?= h($travelRequest->modified->format('F d, Y \a\t h:i A')) ?>
                    </div>
                    <div class="timeline-comment">
                        <?= ucwords(str_replace('_', ' ', h($travelRequest->status))) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Workflow History -->
    <?php if (!empty($travelRequest->workflow_history)): ?>
    <div class="content-box">
        <h3>📜 Detailed Workflow History</h3>
        <div class="timeline">
            <?php foreach ($travelRequest->workflow_history as $index => $history): ?>
            <div class="timeline-item">
                <div class="timeline-dot <?= $index < count($travelRequest->workflow_history) - 1 ? 'completed' : 'current' ?>"></div>
                <div class="timeline-content">
                    <div class="timeline-title">
                        <?= ucwords(str_replace('_', ' ', h($history->to_status))) ?>
                    </div>
                    <div class="timeline-date">
                        <?= h($history->created->format('F d, Y \a\t h:i A')) ?>
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

</div>
