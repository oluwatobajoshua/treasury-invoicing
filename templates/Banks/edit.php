<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bank $bank
 */
$this->assign('title', 'Edit Bank Account');
?>

<style>
.page-header {
    background: linear-gradient(135deg, var(--primary) 0%, #094d3d 100%);
    padding: 1.5rem 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 12px rgba(12, 83, 67, 0.15);
}
.page-header h2 {
    margin: 0 0 .5rem;
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.page-header p {
    margin: 0;
    font-size: .875rem;
    color: rgba(255, 255, 255, 0.9);
}
.form-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
}
.form-section {
    margin-bottom: 2rem;
}
.form-section-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
    padding-bottom: .5rem;
    border-bottom: 2px solid #f3f4f6;
}
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}
.form-group {
    margin-bottom: 0;
}
.form-group label {
    display: block;
    font-size: .875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: .5rem;
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: .75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: .9375rem;
    transition: all 0.3s ease;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(12, 83, 67, 0.1);
}
.form-group textarea {
    resize: vertical;
    min-height: 80px;
}
.checkbox-group {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.checkbox-group input[type="checkbox"] {
    width: auto;
    cursor: pointer;
}
.info-box {
    background: #d1fae5;
    border-left: 4px solid var(--primary);
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1.5rem;
}
.info-box p {
    margin: 0;
    font-size: .875rem;
    color: #065f46;
}
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #f3f4f6;
}
.btn {
    padding: .875rem 2rem;
    border: none;
    border-radius: 8px;
    font-size: .9375rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    transition: all 0.3s ease;
    text-decoration: none;
}
.btn-primary {
    background: linear-gradient(135deg, var(--primary) 0%, #094d3d 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(12, 83, 67, 0.3);
}
.btn-primary:hover {
    box-shadow: 0 6px 16px rgba(12, 83, 67, 0.4);
    transform: translateY(-2px);
}
.btn-outline {
    background: white;
    color: #6b7280;
    border: 2px solid #e5e7eb;
}
.btn-outline:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}
.required {
    color: #ef4444;
}
.conditional-section {
    display: none;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 8px;
    margin-top: 1rem;
}
.conditional-section.active {
    display: block;
}
</style>

<div class="page-header">
    <h2>
        <i class="fas fa-edit"></i>
        Edit Bank Account
    </h2>
    <p>Update bank account: <?= h($bank->bank_name) ?></p>
</div>

<div class="form-container">
    <?= $this->Form->create($bank) ?>
    
    <!-- Basic Information -->
    <div class="form-section">
        <h3 class="form-section-title">Basic Information</h3>
        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Choose bank type carefully. "Sales" for simple banking, "Sustainability" for international wire transfers, or "Both" for versatile use.</p>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Bank Name <span class="required">*</span></label>
                <?= $this->Form->control('bank_name', [
                    'label' => false,
                    'placeholder' => 'e.g., Fidelity Bank PLC',
                    'required' => true
                ]) ?>
            </div>
            <div class="form-group">
                <label>Bank Type <span class="required">*</span></label>
                <?= $this->Form->control('bank_type', [
                    'label' => false,
                    'options' => [
                        'sales' => 'Sales Only',
                        'sustainability' => 'Sustainability Only',
                        'shipment' => 'Shipment Only (Fresh & Final)',
                        'both' => 'All Types'
                    ],
                    'default' => 'both',
                    'required' => true,
                    'id' => 'bank-type',
                    'onchange' => 'toggleSections()'
                ]) ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Account Number</label>
                <?= $this->Form->control('account_number', [
                    'label' => false,
                    'placeholder' => 'e.g., 5140005545'
                ]) ?>
            </div>
            <div class="form-group">
                <label>Currency <span class="required">*</span></label>
                <?= $this->Form->control('currency', [
                    'label' => false,
                    'options' => [
                        'NGN' => 'NGN - Nigerian Naira',
                        'USD' => 'USD - US Dollar',
                        'EUR' => 'EUR - Euro',
                        'GBP' => 'GBP - British Pound'
                    ],
                    'default' => 'USD',
                    'required' => true
                ]) ?>
            </div>
        </div>
        <div class="form-group checkbox-group">
            <?= $this->Form->control('is_active', [
                'type' => 'checkbox',
                'label' => 'Active',
                'checked' => true
            ]) ?>
            <label>Mark as active to use in invoices</label>
        </div>
    </div>

    <!-- Sales Bank Details (For Sales Invoices) -->
    <div id="sales-section" class="form-section conditional-section active">
        <h3 class="form-section-title">Simple Banking Details <span style="font-weight: 400; font-size: .875rem; color: #6b7280;">(For Sales Invoices)</span></h3>
        <div class="form-row">
            <div class="form-group">
                <label>Bank Address</label>
                <?= $this->Form->control('bank_address', [
                    'label' => false,
                    'placeholder' => 'e.g., 123 Banking Street, Lagos'
                ]) ?>
            </div>
            <div class="form-group">
                <label>SWIFT Code</label>
                <?= $this->Form->control('swift_code', [
                    'label' => false,
                    'placeholder' => 'e.g., FIDTNGLA'
                ]) ?>
            </div>
        </div>
        <div class="form-group">
            <label>Purpose</label>
            <?= $this->Form->control('purpose', [
                'label' => false,
                'placeholder' => 'e.g., Payment for goods and services'
            ]) ?>
        </div>
    </div>

    <!-- Sustainability Bank Details (For Sustainability Invoices) -->
    <div id="sustainability-section" class="form-section conditional-section active">
        <h3 class="form-section-title">International Banking Details <span style="font-weight: 400; font-size: .875rem; color: #6b7280;">(For Sustainability Invoices)</span></h3>
        
        <h4 style="font-size: .9375rem; font-weight: 600; color: #6b7280; margin-bottom: 1rem;">Correspondent Bank</h4>
        <div class="form-row">
            <div class="form-group">
                <label>Correspondent Bank Name</label>
                <?= $this->Form->control('correspondent_bank', [
                    'label' => false,
                    'placeholder' => 'e.g., CITI BANK N.A NEW YORK'
                ]) ?>
            </div>
            <div class="form-group">
                <label>Correspondent Address</label>
                <?= $this->Form->control('correspondent_address', [
                    'label' => false,
                    'placeholder' => 'e.g., 111 WALL STREET NEWYORK, NY1004'
                ]) ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Correspondent SWIFT Code</label>
                <?= $this->Form->control('correspondent_swift', [
                    'label' => false,
                    'placeholder' => 'e.g., CITIUS33'
                ]) ?>
            </div>
            <div class="form-group">
                <label>ABA Routing Number</label>
                <?= $this->Form->control('aba_routing', [
                    'label' => false,
                    'placeholder' => 'e.g., 021000089'
                ]) ?>
            </div>
        </div>

        <h4 style="font-size: .9375rem; font-weight: 600; color: #6b7280; margin: 1.5rem 0 1rem;">Beneficiary Bank</h4>
        <div class="form-row">
            <div class="form-group">
                <label>Beneficiary Bank</label>
                <?= $this->Form->control('beneficiary_bank', [
                    'label' => false,
                    'placeholder' => 'e.g., FIDELITY BANK PLC'
                ]) ?>
            </div>
            <div class="form-group">
                <label>Beneficiary Account Number</label>
                <?= $this->Form->control('beneficiary_account_no', [
                    'label' => false,
                    'placeholder' => 'e.g., 36115264'
                ]) ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Beneficiary Name</label>
                <?= $this->Form->control('beneficiary_name', [
                    'label' => false,
                    'placeholder' => 'e.g., SUNBETH GLOBAL CONCEPT LTD'
                ]) ?>
            </div>
            <div class="form-group">
                <label>Beneficiary's Alt Account No</label>
                <?= $this->Form->control('beneficiary_acct_no', [
                    'label' => false,
                    'placeholder' => 'e.g., 5140005545'
                ]) ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Beneficiary SWIFT Code</label>
                <?= $this->Form->control('beneficiary_swift', [
                    'label' => false,
                    'placeholder' => 'e.g., FIDTNGLA'
                ]) ?>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="form-section">
        <h3 class="form-section-title">Additional Information</h3>
        <div class="form-group">
            <label>Notes</label>
            <?= $this->Form->control('notes', [
                'type' => 'textarea',
                'label' => false,
                'placeholder' => 'Enter any additional notes or comments...'
            ]) ?>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="form-actions">
        <?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-outline']) ?>
        <?= $this->Form->button(
            '<i class="fas fa-save"></i> Update Bank',
            ['class' => 'btn btn-primary', 'escapeTitle' => false]
        ) ?>
    </div>
    
    <?= $this->Form->end() ?>
</div>

<script>
function toggleSections() {
    const bankType = document.getElementById('bank-type').value;
    const salesSection = document.getElementById('sales-section');
    const sustainabilitySection = document.getElementById('sustainability-section');
    
    if (bankType === 'sales') {
        salesSection.classList.add('active');
        sustainabilitySection.classList.remove('active');
    } else if (bankType === 'sustainability') {
        salesSection.classList.remove('active');
        sustainabilitySection.classList.add('active');
    } else { // both
        salesSection.classList.add('active');
        sustainabilitySection.classList.add('active');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleSections();
});
</script>
