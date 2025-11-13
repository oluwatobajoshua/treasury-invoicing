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
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 12px rgba(12, 83, 67, 0.15);
    }
    
    .view-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .header-left h1 {
        font-size: 1.4rem;
        margin: 0 0 0.25rem 0;
        font-weight: 700;
    }
    
    .request-number-badge {
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.25);
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        backdrop-filter: blur(10px);
        display: inline-block;
    }
    
    .status-badge-large {
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
    
    .status-badge-large.pending { color: #FF9800; }
    .status-badge-large.approved { color: #4CAF50; }
    .status-badge-large.rejected { color: #f44336; }
    .status-badge-large.processing { color: #2196F3; }
    
    .action-bar {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        padding: 0.75rem;
        background: var(--gray-50);
        border-radius: 6px;
        border: 1px solid var(--gray-200);
    }
    
    .section-divider {
        border: none;
        height: 1px;
        background: linear-gradient(90deg, var(--primary), transparent);
        margin: 1rem 0 0.75rem 0;
    }
    
    .section-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.4rem;
        border-bottom: 2px solid var(--gray-200);
    }
    
    .section-header h2 {
        font-size: 1rem;
        margin: 0;
        color: var(--primary);
        font-weight: 700;
    }
    
    .section-icon {
        font-size: 1.25rem;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .info-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .info-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .content-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 1200px) {
        .info-grid-4 {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 992px) {
        .info-grid-3, .info-grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .info-grid, .info-grid-3, .info-grid-4, .content-grid-2 {
            grid-template-columns: 1fr;
        }
    }
    
    .info-card {
        background: white;
        border-radius: 8px;
        padding: 0.85rem;
        border: 2px solid var(--gray-200);
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
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
        font-size: 1.75rem;
        margin-bottom: 0.4rem;
        display: block;
        filter: grayscale(0.2);
    }
    
    .info-card-title {
        font-size: 0.65rem;
        text-transform: uppercase;
        color: var(--muted);
        font-weight: 700;
        letter-spacing: 0.8px;
        margin-bottom: 0.3rem;
    }
    
    .info-card-value {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text);
        line-height: 1.3;
        margin-bottom: 0.2rem;
    }
    
    .info-card-meta {
        font-size: 0.75rem;
        color: var(--muted);
        margin-top: 0.3rem;
    }
    
    .content-box {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        border: 2px solid var(--gray-200);
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        margin-bottom: 1rem;
    }
    
    .content-box h3 {
        color: var(--primary);
        font-size: 0.95rem;
        margin: 0 0 0.75rem 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .allowance-grid {
        display: grid;
        gap: 0.75rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 1rem;
        border-radius: 8px;
        border: 2px dashed var(--gray-300);
    }
    
    .allowance-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: white;
        border-radius: 6px;
        border: 1px solid var(--gray-200);
        transition: all 0.2s ease;
    }
    
    .allowance-row:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .allowance-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .allowance-amount {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
        font-family: 'Courier New', monospace;
    }
    
    .allowance-total {
        background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%);
        border: none;
        padding: 1rem;
        margin-top: 0.75rem;
    }
    
    .allowance-total .allowance-label {
        color: white;
        font-size: 1rem;
    }
    
    .allowance-total .allowance-amount {
        color: white;
        font-size: 1.5rem;
    }
    
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1rem;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2rem;
        top: 0;
        width: 2px;
        height: 100%;
        background: var(--gray-300);
    }
    
    .timeline-item:last-child::before {
        display: none;
    }
    
    .timeline-dot {
        position: absolute;
        left: -2.35rem;
        top: 0.2rem;
        width: 0.7rem;
        height: 0.7rem;
        border-radius: 50%;
        background: white;
        border: 2px solid var(--primary);
        z-index: 1;
        box-shadow: 0 0 0 2px rgba(12, 83, 67, 0.1);
    }
    
    .timeline-dot.completed {
        background: #4CAF50;
        border-color: #4CAF50;
        box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
    }
    
    .timeline-dot.current {
        background: #F64500;
        border-color: #F64500;
        box-shadow: 0 0 0 2px rgba(246, 69, 0, 0.1);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .timeline-content {
        background: white;
        padding: 0.75rem;
        border-radius: 6px;
        border: 2px solid var(--gray-200);
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    
    .timeline-title {
        font-weight: 700;
        margin-bottom: 0.3rem;
        color: var(--primary);
        font-size: 0.85rem;
    }
    
    .timeline-date {
        font-size: 0.75rem;
        color: var(--muted);
        margin-bottom: 0.4rem;
    }
    
    .timeline-comment {
        font-size: 0.85rem;
        color: var(--text);
        line-height: 1.4;
        padding: 0.6rem;
        background: var(--gray-50);
        border-radius: 4px;
        border-left: 2px solid var(--primary);
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
    <div class="action-bar">
        <!-- Back to List - Always available -->
        <?= $this->Html->link('← Back to List', ['action' => 'index'], ['class' => 'btn btn-outline']) ?>
        
        <!-- Draft/Submitted Status Actions -->
        <?php if (in_array($travelRequest->status, ['draft', 'submitted'])): ?>
            <?= $this->Html->link('✏️ Edit Request', ['action' => 'edit', $travelRequest->id], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link('🗑️ Delete', ['action' => 'delete', $travelRequest->id], ['class' => 'btn btn-danger', 'confirm' => 'Are you sure?']) ?>
        <?php endif; ?>
        
        <!-- Submitted Status - Line Manager Actions -->
        <?php if ($travelRequest->status === 'submitted'): ?>
            <?= $this->Html->link('✅ Approve Request', ['action' => 'approve', $travelRequest->id], ['class' => 'btn btn-success']) ?>
            <?= $this->Html->link('❌ Reject Request', ['action' => 'reject', $travelRequest->id], ['class' => 'btn btn-danger']) ?>
            <?= $this->Form->postLink('📧 Send Email Notification', ['action' => 'sendEmailApprovalNotification', $travelRequest->id], ['class' => 'btn btn-info', 'confirm' => 'Send approval notification email?']) ?>
            <?= $this->Form->postLink('💬 Send Teams Notification', ['action' => 'sendTeamsApprovalCard', $travelRequest->id], ['class' => 'btn btn-info', 'confirm' => 'Send Teams approval card?']) ?>
        <?php endif; ?>
        
        <!-- Approved Status - Calculate Allowances -->
        <?php if ($travelRequest->status === 'lm_approved'): ?>
            <form method="post" action="<?= $this->Url->build(['action' => 'calculateAllowances', $travelRequest->id]) ?>" style="display: inline;">
                <?= $this->Form->hidden('_csrfToken', ['value' => $this->request->getAttribute('csrfToken')]) ?>
                <button type="submit" class="btn btn-success">💰 Calculate Allowances</button>
            </form>
        <?php endif; ?>
        
        <!-- Admin Processing - Mark as Completed -->
        <?php if ($travelRequest->status === 'admin_processing'): ?>
            <form method="post" action="<?= $this->Url->build(['action' => 'complete', $travelRequest->id]) ?>" style="display: inline;">
                <?= $this->Form->hidden('_csrfToken', ['value' => $this->request->getAttribute('csrfToken')]) ?>
                <button type="submit" class="btn btn-success" onclick="return confirm('Mark this travel request as completed?')">✅ Mark as Completed</button>
            </form>
        <?php endif; ?>
        
        <!-- Print/Export Options -->
        <button onclick="window.print()" class="btn btn-outline">🖨️ Print</button>
        
        <!-- Admin Delete - Available for admins on any status -->
        <?php if ($this->request->getSession()->read('Auth.role') === 'admin'): ?>
            <?= $this->Html->link('🗑️ Admin Delete', ['action' => 'delete', $travelRequest->id], ['class' => 'btn btn-danger', 'confirm' => 'Are you sure you want to permanently delete this travel request? This action cannot be undone.']) ?>
        <?php endif; ?>
    </div>

    <!-- Traveler Information Section -->
    <div class="section-header">
        <span class="section-icon">👤</span>
        <h2>Traveler Information</h2>
    </div>
    
    <div class="info-grid-3">
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
    
    <div class="info-grid-4">
        <div class="info-card">
            <div class="info-card-icon"><?= $travelRequest->travel_type === 'local' ? '🇳🇬' : '🌍' ?></div>
            <div class="info-card-title">Travel Type</div>
            <div class="info-card-value">
                <?= $travelRequest->travel_type === 'local' ? 'Local Travel' : 'International' ?>
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
                <?= h($travelRequest->departure_date->format('M d, Y')) ?>
            </div>
            <div class="info-card-meta">
                <?= h($travelRequest->departure_date->format('l')) ?>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-card-icon">🛬</div>
            <div class="info-card-title">Return Date</div>
            <div class="info-card-value">
                <?= h($travelRequest->return_date->format('M d, Y')) ?>
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
        
        <div class="info-card">
            <div class="info-card-icon">📅</div>
            <div class="info-card-title">Created On</div>
            <div class="info-card-value">
                <?= h($travelRequest->created->format('M d, Y')) ?>
            </div>
            <div class="info-card-meta">
                <?= h($travelRequest->created->format('h:i A')) ?>
            </div>
        </div>
        
        <?php if ($travelRequest->line_manager_approved_at): ?>
        <div class="info-card">
            <div class="info-card-icon">✅</div>
            <div class="info-card-title">LM Approved</div>
            <div class="info-card-value">
                <?= h($travelRequest->line_manager_approved_at->format('M d, Y')) ?>
            </div>
            <div class="info-card-meta">
                <?= h($travelRequest->line_manager_approved_at->format('h:i A')) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Purpose and Documents in 2 columns -->
    <div class="content-grid-2">
        <div class="content-box">
            <h3>� Purpose of Travel</h3>
            <div style="line-height: 1.6; font-size: 0.95rem; color: var(--text);">
                <?= nl2br(h($travelRequest->purpose_of_travel)) ?>
            </div>
        </div>

        <?php if ($travelRequest->email_conversation_file): ?>
        <div class="content-box">
            <h3>📎 Supporting Documents</h3>
            <div style="padding: 1rem; background: var(--gray-50); border-radius: 8px; text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">📄</div>
                <div style="font-weight: 600; margin-bottom: 0.5rem;">Email Conversation</div>
                <div style="color: var(--muted); margin-bottom: 1rem; font-size: 0.85rem;">
                    Uploaded: <?= h($travelRequest->email_conversation_uploaded_at->format('M d, Y')) ?>
                </div>
                <?= $this->Html->link('📥 Download', '/' . h($travelRequest->email_conversation_file), [
                    'class' => 'btn btn-primary btn-sm',
                    'target' => '_blank'
                ]) ?>
            </div>
        </div>
        <?php elseif ($travelRequest->notes): ?>
        <div class="content-box">
            <h3>📌 Additional Notes</h3>
            <div style="line-height: 1.6; font-size: 0.95rem; color: var(--text);">
                <?= nl2br(h($travelRequest->notes)) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Allowances Section -->
    <?php if ($travelRequest->total_allowance > 0): ?>
    <hr class="section-divider">
    
    <div class="section-header">
        <span class="section-icon">💰</span>
        <h2>Allowances Breakdown</h2>
    </div>
    
    <style>
        .allowance-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .allowance-table thead {
            background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%);
            color: white;
        }
        .allowance-table th {
            padding: 0.75rem;
            text-align: left;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .allowance-table th:last-child,
        .allowance-table td:last-child {
            text-align: right;
        }
        .allowance-table tbody tr {
            border-bottom: 1px solid var(--gray-200);
        }
        .allowance-table tbody tr:last-child {
            border-bottom: none;
        }
        .allowance-table tbody tr:hover {
            background: var(--gray-50);
        }
        .allowance-table td {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        .allowance-table tfoot {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-weight: 700;
        }
        .allowance-table tfoot td {
            padding: 1rem 0.75rem;
            font-size: 1rem;
            border-top: 3px solid var(--primary);
        }
        .allowance-amount {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: var(--primary);
        }
        .grand-total {
            font-size: 1.25rem !important;
            color: var(--primary);
        }
    </style>
    
    <div class="content-box">
        <?php if ($travelRequest->travel_type === 'international'): ?>
            <!-- INTERNATIONAL TRAVEL TABLE -->
            <table class="allowance-table">
                <thead>
                    <tr>
                        <th>Day</th>
                        <?php if (isset($allowanceRate) && $allowanceRate): ?>
                            <th>Hotel Category</th>
                            <th>Air Ticket Class</th>
                        <?php endif; ?>
                        <th>Out-of-Station Allowance (Per Diem)</th>
                        <th>Transport Per Day</th>
                        <th>Daily Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $currency = '$';
                        $perDiemDaily = $travelRequest->per_diem_allowance / $travelRequest->duration_days;
                        $transportDaily = $travelRequest->transport_allowance / $travelRequest->duration_days;
                        $dailyTotal = $perDiemDaily + $transportDaily;
                        
                        for ($day = 1; $day <= $travelRequest->duration_days; $day++): 
                    ?>
                    <tr>
                        <td><strong>Day <?= $day ?></strong></td>
                        <?php if (isset($allowanceRate) && $allowanceRate): ?>
                            <?php if ($day === 1): ?>
                                <td rowspan="<?= $travelRequest->duration_days ?>">
                                    🏨 <?= h($allowanceRate->hotel_standard ?? 'Standard') ?>
                                </td>
                                <td rowspan="<?= $travelRequest->duration_days ?>">
                                    ✈️ <?= h($allowanceRate->flight_class ?? 'Economy') ?>
                                </td>
                            <?php endif; ?>
                        <?php endif; ?>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($perDiemDaily, 2) ?></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($transportDaily, 2) ?></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($dailyTotal, 2) ?></td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="<?= isset($allowanceRate) && $allowanceRate ? '3' : '1' ?>"><strong>GRAND TOTAL (<?= $travelRequest->duration_days ?> Days)</strong></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($travelRequest->per_diem_allowance, 2) ?></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($travelRequest->transport_allowance, 2) ?></td>
                        <td class="allowance-amount grand-total"><?= $currency ?><?= number_format($travelRequest->total_allowance, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
            
        <?php else: ?>
            <!-- LOCAL TRAVEL TABLE -->
            <table class="allowance-table">
                <thead>
                    <tr>
                        <th>Day/Night</th>
                        <?php if (isset($allowanceRate) && $allowanceRate): ?>
                            <th>Room Type</th>
                        <?php endif; ?>
                        <?php if ($travelRequest->accommodation_required && $travelRequest->accommodation_allowance > 0): ?>
                            <th>Hotel Accommodation</th>
                        <?php endif; ?>
                        <?php if (isset($allowanceRate) && $allowanceRate && $allowanceRate->alternate_accommodation): ?>
                            <th>Alternate Accommodation</th>
                        <?php endif; ?>
                        <th>Per Diem (Out-station Allowance)</th>
                        <th>Daily Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $currency = '₦';
                        $perDiemDaily = $travelRequest->per_diem_allowance / $travelRequest->duration_days;
                        $accommodationDaily = $travelRequest->accommodation_required && $travelRequest->accommodation_allowance > 0
                            ? $travelRequest->accommodation_allowance / $travelRequest->duration_days
                            : 0;
                        $dailyTotal = $perDiemDaily + $accommodationDaily;
                        
                        for ($day = 1; $day <= $travelRequest->duration_days; $day++): 
                    ?>
                    <tr>
                        <td><strong>Day <?= $day ?><?= $travelRequest->accommodation_required && $day < $travelRequest->duration_days ? ' / Night ' . $day : '' ?></strong></td>
                        <?php if (isset($allowanceRate) && $allowanceRate): ?>
                            <?php if ($day === 1): ?>
                                <td rowspan="<?= $travelRequest->duration_days ?>">
                                    🛏️ <?= h($allowanceRate->room_type ?? 'Standard Room') ?>
                                </td>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($travelRequest->accommodation_required && $travelRequest->accommodation_allowance > 0): ?>
                            <td class="allowance-amount"><?= $currency ?><?= number_format($accommodationDaily, 2) ?></td>
                        <?php endif; ?>
                        <?php if (isset($allowanceRate) && $allowanceRate && $allowanceRate->alternate_accommodation): ?>
                            <?php if ($day === 1): ?>
                                <td rowspan="<?= $travelRequest->duration_days ?>">
                                    🏠 <?= h($allowanceRate->alternate_accommodation) ?>
                                </td>
                            <?php endif; ?>
                        <?php endif; ?>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($perDiemDaily, 2) ?></td>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($dailyTotal, 2) ?></td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="<?= 
                            (isset($allowanceRate) && $allowanceRate ? 1 : 0) +
                            (isset($allowanceRate) && $allowanceRate && $allowanceRate->alternate_accommodation ? 1 : 0) +
                            1
                        ?>"><strong>GRAND TOTAL (<?= $travelRequest->duration_days ?> Days)</strong></td>
                        <?php if ($travelRequest->accommodation_required && $travelRequest->accommodation_allowance > 0): ?>
                            <td class="allowance-amount"><?= $currency ?><?= number_format($travelRequest->accommodation_allowance, 2) ?></td>
                        <?php endif; ?>
                        <td class="allowance-amount"><?= $currency ?><?= number_format($travelRequest->per_diem_allowance, 2) ?></td>
                        <td class="allowance-amount grand-total"><?= $currency ?><?= number_format($travelRequest->total_allowance, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>
        
        <?php if (isset($allowanceRate) && $allowanceRate): ?>
        <div style="margin-top: 1.5rem; padding: 1rem; background: var(--gray-50); border-radius: 6px; border-left: 4px solid var(--primary);">
            <div style="font-size: 0.85rem; color: var(--muted); margin-bottom: 0.5rem;">
                <strong>💡 Allowance Rate Information:</strong>
            </div>
            <div style="font-size: 0.85rem; line-height: 1.8;">
                <?php if ($travelRequest->travel_type === 'international'): ?>
                    • <strong>Hotel Category:</strong> <?= h($allowanceRate->hotel_standard ?? 'Standard') ?><br>
                    • <strong>Flight Class:</strong> <?= h($allowanceRate->flight_class ?? 'Economy') ?><br>
                    • <strong>Per Diem Rate:</strong> $<?= number_format($allowanceRate->per_diem_rate, 2) ?> per day<br>
                    • <strong>Transport Rate:</strong> $<?= number_format($allowanceRate->transport_rate, 2) ?> per day
                <?php else: ?>
                    • <strong>Room Type:</strong> <?= h($allowanceRate->room_type ?? 'Standard Room') ?><br>
                    <?php if ($travelRequest->accommodation_required): ?>
                        • <strong>Hotel Rate:</strong> ₦<?= number_format($allowanceRate->accommodation_rate, 2) ?> per night<br>
                    <?php endif; ?>
                    <?php if ($allowanceRate->alternate_accommodation): ?>
                        • <strong>Alternate Option:</strong> <?= h($allowanceRate->alternate_accommodation) ?><br>
                    <?php endif; ?>
                    • <strong>Per Diem Rate:</strong> ₦<?= number_format($allowanceRate->per_diem_rate, 2) ?> per day
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if ($travelRequest->requisition_number): ?>
    <div class="content-box" style="background: #e8f5e9; border-color: #4CAF50; margin-top: 1rem;">
        <h3 style="color: #2e7d32;">📋 Requisition Info</h3>
        <div style="font-size: 1rem; font-weight: 600;">
            Number: <span style="font-family: 'Courier New', monospace; color: #2e7d32;"><?= h($travelRequest->requisition_number) ?></span>
        </div>
        <?php if ($travelRequest->requisition_prepared_at): ?>
        <div style="color: #558b2f; margin-top: 0.5rem;">
            Prepared: <?= h($travelRequest->requisition_prepared_at->format('M d, Y')) ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    <!-- Request Timeline & History -->
    <hr class="section-divider">
    
    <div class="section-header">
        <span class="section-icon">🕐</span>
        <h2>Request Timeline & History</h2>
    </div>
    
    <div class="content-box">
        <?php if (!empty($travelRequest->workflow_history)): ?>
            <div class="timeline">
                <?php foreach ($travelRequest->workflow_history as $index => $history): ?>
                <div class="timeline-item">
                    <div class="timeline-dot <?= $index < count($travelRequest->workflow_history) - 1 ? 'completed' : 'current' ?>"></div>
                    <div class="timeline-content">
                        <div class="timeline-title">
                            <?php
                                // Add appropriate emoji based on status
                                $emoji = '🔄';
                                $statusLower = strtolower($history->to_status);
                                if (strpos($statusLower, 'submitted') !== false) $emoji = '📧';
                                elseif (strpos($statusLower, 'approved') !== false) $emoji = '✅';
                                elseif (strpos($statusLower, 'rejected') !== false) $emoji = '❌';
                                elseif (strpos($statusLower, 'processing') !== false) $emoji = '⚙️';
                                elseif (strpos($statusLower, 'completed') !== false) $emoji = '✨';
                                elseif (strpos($statusLower, 'draft') !== false) $emoji = '�';
                            ?>
                            <?= $emoji ?> <?= ucwords(str_replace('_', ' ', h($history->to_status))) ?>
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
        <?php else: ?>
            <!-- Fallback to basic timeline if no workflow history -->
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
        <?php endif; ?>
    </div>

</div>
