<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TravelRequest $travelRequest
 * @var string[]|\Cake\Collection\CollectionInterface $users
 */
?>
<style>
    .edit-header {
        background: linear-gradient(135deg, var(--primary) 0%, #0a4636 100%);
        color: white;
        padding: 0.6rem 0.85rem;
        border-radius: 6px;
        margin-bottom: 0.6rem;
        box-shadow: 0 3px 12px rgba(12, 83, 67, 0.12);
    }
    .edit-header h1 {
        margin: 0 0 0.2rem 0;
        font-size: 1rem;
        font-weight: 700;
    }
    .edit-header-meta {
        opacity: 0.95;
        font-size: 0.7rem;
    }
    .tabs-container {
        background: white;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        border: 1px solid var(--gray-200);
        margin-bottom: 0.6rem;
    }
    .tabs {
        display: flex;
        border-bottom: 1px solid var(--gray-200);
        background: var(--gray-50);
        border-radius: 6px 6px 0 0;
        padding: 0.25rem 0.25rem 0;
        overflow-x: auto;
    }
    .tab {
        padding: 0.45rem 0.75rem;
        cursor: pointer;
        border: none;
        background: transparent;
        font-weight: 500;
        color: var(--muted);
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
        white-space: nowrap;
        font-size: 0.75rem;
    }
    .tab:hover {
        color: var(--primary);
        background: rgba(12, 83, 67, 0.04);
    }
    .tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        background: white;
        font-weight: 600;
    }
    .tab-content {
        display: none;
        padding: 0.7rem;
    }
    .tab-content.active {
        display: block;
        animation: fadeIn 0.2s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 0.6rem;
        margin-bottom: 0.6rem;
    }
    .form-group-full {
        grid-column: 1 / -1;
    }
    .form-section {
        margin-bottom: 0.8rem;
    }
    .form-section-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        padding-bottom: 0.25rem;
        border-bottom: 1px solid var(--gray-200);
    }
    .action-bar {
        display: flex;
        gap: 0.5rem;
        padding: 0.6rem;
        background: var(--gray-50);
        border-radius: 0 0 8px 8px;
        border-top: 1px solid var(--gray-200);
    }
    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 999px;
        font-size: 0.75rem;
        margin-right: 0.65rem;
        backdrop-filter: blur(10px);
    }
    .readonly-field {
        background: var(--gray-100);
        padding: 0.55rem;
        border-radius: 4px;
        font-weight: 500;
        font-size: 0.85rem;
    }
    .help-text {
        font-size: 0.75rem;
        color: var(--muted);
        margin-top: 0.2rem;
        font-style: italic;
    }
    .allowance-preview {
        background: linear-gradient(135deg, #e8f5f1 0%, #d4e9e2 100%);
        padding: 1rem;
        border-radius: 6px;
        margin-top: 0.75rem;
    }
    .allowance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 0.65rem;
        margin-top: 0.65rem;
    }
    .allowance-box {
        text-align: center;
        padding: 0.65rem;
        background: white;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .allowance-label {
        font-size: 0.7rem;
        color: var(--muted);
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.35rem;
    }
    .allowance-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
    }
    @media (max-width: 968px) {
        .edit-header {
            padding: 0.85rem 1rem;
        }
        .edit-header h1 {
            font-size: 1.2rem;
        }
        .edit-header-meta {
            flex-direction: column;
            gap: 0.4rem;
            align-items: flex-start;
        }
        .tabs {
            overflow-x: auto;
            justify-content: flex-start;
            gap: 0.4rem;
            padding-bottom: 0.5rem;
        }
        .tab {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
            white-space: nowrap;
        }
        .tab-content {
            padding: 0.85rem;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
        .action-bar {
            flex-direction: column-reverse;
            gap: 0.5rem;
        }
        .action-bar .btn {
            width: 100%;
            text-align: center;
        }
    }
    @media (max-width: 640px) {
        .edit-header {
            padding: 0.75rem;
        }
        .edit-header h1 {
            font-size: 1.1rem;
        }
        .tabs-container {
            margin: 0 -8px;
        }
        .tabs {
            padding: 0 8px 0.5rem;
        }
        .tab-content {
            padding: 0.75rem;
        }
        .info-badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.5rem;
        }
    }
</style>

<?= $this->element('progress_tracker', ['currentStep' => (int)$travelRequest->current_step]) ?>

<div class="edit-header">
    <h1>✏️ Edit Travel Request</h1>
    <div class="edit-header-meta">
        <span class="info-badge">📋 <?= h($travelRequest->request_number) ?></span>
        <span class="info-badge">🌍 <?= h($travelRequest->destination) ?></span>
        <span class="info-badge">📅 Created: <?= h($travelRequest->created->format('M d, Y')) ?></span>
    </div>
</div>

<?= $this->Form->create($travelRequest, ['id' => 'travelRequestForm']) ?>

<div class="tabs-container">
    <div class="tabs">
        <button type="button" class="tab active" data-tab="basic">
            📝 Basic Information
        </button>
        <button type="button" class="tab" data-tab="dates">
            📅 Travel Dates
        </button>
        <button type="button" class="tab" data-tab="allowances">
            💰 Allowances
        </button>
        <button type="button" class="tab" data-tab="workflow">
            🔄 Workflow & Status
        </button>
        <button type="button" class="tab" data-tab="admin">
            ⚙️ Admin Details
        </button>
    </div>

    <!-- Basic Information Tab -->
    <div class="tab-content active" data-tab="basic">
        <div class="form-section">
            <div class="form-section-title">📝 Request Details</div>
            <div class="form-grid">
                <div class="form-group-full">
                    <?= $this->Form->control('purpose_of_travel', [
                        'type' => 'textarea',
                        'rows' => 4,
                        'label' => '📝 Purpose of Travel',
                        'placeholder' => 'Describe the purpose and objectives of your travel...',
                        'required' => true
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('destination', [
                        'label' => '🌍 Destination',
                        'placeholder' => 'e.g., Lagos, Nigeria',
                        'required' => true
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('travel_type', [
                        'type' => 'select',
                        'options' => ['local' => '🇳🇬 Local Travel', 'international' => '🌍 International Travel'],
                        'label' => '✈️ Travel Type',
                        'required' => true
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('accommodation_required', [
                        'type' => 'checkbox',
                        'label' => '🛏️ Accommodation Required'
                    ]) ?>
                    <div class="help-text">Check if hotel accommodation is needed</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Travel Dates Tab -->
    <div class="tab-content" data-tab="dates">
        <div class="form-section">
            <div class="form-section-title">📅 Travel Schedule</div>
            <div class="form-grid">
                <div>
                    <?= $this->Form->control('departure_date', [
                        'type' => 'date',
                        'label' => '🛫 Departure Date',
                        'required' => true
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('return_date', [
                        'type' => 'date',
                        'label' => '🛬 Return Date',
                        'required' => true
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('duration_days', [
                        'type' => 'number',
                        'label' => '⏱️ Duration (Days)',
                        'min' => 1,
                        'readonly' => true
                    ]) ?>
                    <div class="help-text">Auto-calculated from dates</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Allowances Tab -->
    <div class="tab-content" data-tab="allowances">
        <div class="form-section">
            <div class="form-section-title">💰 Allowance Breakdown</div>
            <div class="form-grid">
                <div>
                    <?= $this->Form->control('accommodation_allowance', [
                        'type' => 'number',
                        'step' => '0.01',
                        'label' => '🏨 Accommodation Allowance',
                        'placeholder' => '0.00'
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('feeding_allowance', [
                        'type' => 'number',
                        'step' => '0.01',
                        'label' => '🍽️ Feeding Allowance',
                        'placeholder' => '0.00'
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('transport_allowance', [
                        'type' => 'number',
                        'step' => '0.01',
                        'label' => '🚗 Transport Allowance',
                        'placeholder' => '0.00'
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('incidental_allowance', [
                        'type' => 'number',
                        'step' => '0.01',
                        'label' => '💼 Incidental Allowance',
                        'placeholder' => '0.00'
                    ]) ?>
                </div>
            </div>
            
            <div class="allowance-preview">
                <div class="form-section-title" style="border: none; padding: 0;">💎 Total Allowance</div>
                <div class="allowance-grid">
                    <div class="allowance-box">
                        <div class="allowance-label">Total</div>
                        <div class="allowance-value" id="total-allowance-display">
                            <?= $travelRequest->travel_type === 'local' ? '₦' : '$' ?>
                            <?= number_format($travelRequest->total_allowance ?? 0, 2) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Workflow Tab -->
    <div class="tab-content" data-tab="workflow">
        <div class="form-section">
            <div class="form-section-title">🔄 Workflow Status</div>
            <div class="form-grid">
                <div>
                    <?= $this->Form->control('status', [
                        'type' => 'select',
                        'options' => [
                            'draft' => '📝 Draft',
                            'submitted' => '⏳ Submitted',
                            'lm_review' => '👀 LM Review',
                            'lm_approved' => '✅ LM Approved',
                            'lm_rejected' => '❌ LM Rejected',
                            'admin_processing' => '⚙️ Admin Processing',
                            'completed' => '✨ Completed',
                            'cancelled' => '🚫 Cancelled'
                        ],
                        'label' => '📊 Current Status'
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('current_step', [
                        'type' => 'number',
                        'label' => '🔢 Current Step',
                        'min' => 1,
                        'max' => 5
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">👔 Line Manager Review</div>
            <div class="form-grid">
                <div>
                    <?= $this->Form->control('line_manager_id', [
                        'type' => 'number',
                        'label' => '👤 Line Manager ID'
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('line_manager_approved_at', [
                        'type' => 'datetime-local',
                        'label' => '✅ Approved At',
                        'empty' => true
                    ]) ?>
                </div>
                <div class="form-group-full">
                    <?= $this->Form->control('line_manager_comments', [
                        'type' => 'textarea',
                        'rows' => 3,
                        'label' => '💭 Line Manager Comments',
                        'placeholder' => 'Comments from line manager...'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">⚠️ Rejection Details</div>
            <div class="form-grid">
                <div>
                    <?= $this->Form->control('rejected_at', [
                        'type' => 'datetime-local',
                        'label' => '📅 Rejected At',
                        'empty' => true
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('rejected_by', [
                        'type' => 'number',
                        'label' => '👤 Rejected By (User ID)'
                    ]) ?>
                </div>
                <div class="form-group-full">
                    <?= $this->Form->control('rejection_reason', [
                        'type' => 'textarea',
                        'rows' => 3,
                        'label' => '📝 Rejection Reason',
                        'placeholder' => 'Reason for rejection...'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Tab -->
    <div class="tab-content" data-tab="admin">
        <div class="form-section">
            <div class="form-section-title">⚙️ Administrative Details</div>
            <div class="form-grid">
                <div>
                    <?= $this->Form->control('admin_id', [
                        'options' => $users,
                        'empty' => '-- Select Admin --',
                        'label' => '👤 Assigned Admin'
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('requisition_number', [
                        'label' => '📋 Requisition Number',
                        'placeholder' => 'REQ-XXXX'
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('requisition_prepared_at', [
                        'type' => 'datetime-local',
                        'label' => '📅 Requisition Prepared',
                        'empty' => true
                    ]) ?>
                </div>
                <div>
                    <?= $this->Form->control('completed_at', [
                        'type' => 'datetime-local',
                        'label' => '✅ Completed At',
                        'empty' => true
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">📎 Additional Information</div>
            <div class="form-grid">
                <div class="form-group-full">
                    <?= $this->Form->control('notes', [
                        'type' => 'textarea',
                        'rows' => 4,
                        'label' => '📌 Internal Notes',
                        'placeholder' => 'Add any internal notes or additional information...'
                    ]) ?>
                </div>
                <div class="form-group-full">
                    <?= $this->Form->control('attachments', [
                        'type' => 'textarea',
                        'rows' => 3,
                        'label' => '📎 Attachments (URLs/Paths)',
                        'placeholder' => 'List of attachment URLs or file paths...'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">ℹ️ System Information</div>
            <div class="form-grid">
                <div>
                    <label>📋 Request Number</label>
                    <div class="readonly-field"><?= h($travelRequest->request_number) ?></div>
                </div>
                <div>
                    <label>👤 User ID</label>
                    <div class="readonly-field"><?= h($travelRequest->user_id) ?></div>
                </div>
                <div>
                    <label>📅 Created</label>
                    <div class="readonly-field"><?= h($travelRequest->created->format('M d, Y h:i A')) ?></div>
                </div>
                <div>
                    <label>📝 Last Modified</label>
                    <div class="readonly-field"><?= h($travelRequest->modified->format('M d, Y h:i A')) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="action-bar">
        <?= $this->Form->button('💾 Save Changes', ['class' => 'btn btn-primary']) ?>
        <?= $this->Html->link('👁️ View Request', ['action' => 'view', $travelRequest->id], ['class' => 'btn btn-outline']) ?>
        <?= $this->Html->link('📋 All Requests', ['action' => 'index'], ['class' => 'btn btn-outline']) ?>
        <?= $this->Form->postLink('🗑️ Delete', ['action' => 'delete', $travelRequest->id], [
            'confirm' => 'Are you sure you want to delete this travel request?',
            'class' => 'btn btn-danger',
            'style' => 'margin-left: auto;'
        ]) ?>
    </div>
</div>

<?= $this->Form->end() ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.querySelector(`.tab-content[data-tab="${targetTab}"]`).classList.add('active');
        });
    });
    
    // Auto-calculate duration
    const departureInput = document.querySelector('input[name="departure_date"]');
    const returnInput = document.querySelector('input[name="return_date"]');
    const durationInput = document.querySelector('input[name="duration_days"]');
    
    function calculateDuration() {
        if (departureInput.value && returnInput.value) {
            const departure = new Date(departureInput.value);
            const returnDate = new Date(returnInput.value);
            const diffTime = Math.abs(returnDate - departure);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            durationInput.value = diffDays + 1; // Include departure day
        }
    }
    
    if (departureInput && returnInput) {
        departureInput.addEventListener('change', calculateDuration);
        returnInput.addEventListener('change', calculateDuration);
    }
    
    // Auto-calculate total allowance
    const allowanceInputs = document.querySelectorAll('[name*="_allowance"]:not([name="total_allowance"])');
    const totalDisplay = document.getElementById('total-allowance-display');
    
    function calculateTotal() {
        let total = 0;
        allowanceInputs.forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        
        const travelType = document.querySelector('select[name="travel_type"]').value;
        const currency = travelType === 'local' ? '₦' : '$';
        totalDisplay.textContent = currency + total.toFixed(2);
        
        // Update hidden total field if exists
        const totalInput = document.querySelector('input[name="total_allowance"]');
        if (totalInput) {
            totalInput.value = total.toFixed(2);
        }
    }
    
    allowanceInputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
    });
    
    // Update currency symbol when travel type changes
    const travelTypeSelect = document.querySelector('select[name="travel_type"]');
    if (travelTypeSelect) {
        travelTypeSelect.addEventListener('change', calculateTotal);
    }
});
</script>
