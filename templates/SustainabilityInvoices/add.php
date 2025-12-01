<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SustainabilityInvoice $sustainabilityInvoice
 */
$this->assign('title', 'Create Sustainability Invoice');
?>

<style>
.page-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    padding: 1.5rem 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
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
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}
.form-group textarea {
    resize: vertical;
    min-height: 80px;
}
.calculation-display {
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1rem;
}
.calc-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .75rem 0;
}
.calc-row.total {
    border-top: 2px solid #10b981;
    margin-top: .5rem;
    padding-top: 1rem;
}
.calc-label {
    font-size: .875rem;
    font-weight: 600;
    color: #6b7280;
}
.calc-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #374151;
}
.calc-value.total {
    font-size: 1.5rem;
    color: #10b981;
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
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}
.btn-primary:hover {
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
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
</style>

<div class="page-header">
    <h2>
        <i class="fas fa-plus-circle"></i>
        Create Sustainability Invoice
    </h2>
    <p>Generate a new sustainability invoice for cocoa export proceeds</p>
</div>

<div class="form-container">
    <?= $this->Form->create($sustainabilityInvoice) ?>
    
    <!-- Basic Information -->
    <div class="form-section">
        <h3 class="form-section-title">Invoice Details</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Invoice Number <span class="required">*</span></label>
                <?= $this->Form->control('invoice_number', [
                    'label' => false,
                    'placeholder' => 'e.g., 0169',
                    'required' => true
                ]) ?>
            </div>
            <div class="form-group">
                <label>Invoice Date <span class="required">*</span></label>
                <?= $this->Form->control('invoice_date', [
                    'label' => false,
                    'type' => 'date',
                    'value' => date('Y-m-d'),
                    'required' => true
                ]) ?>
            </div>
            <div class="form-group">
                <label>Seller ID <span class="required">*</span></label>
                <?= $this->Form->control('seller_id', [
                    'label' => false,
                    'placeholder' => 'e.g., RA_00051922110',
                    'required' => true
                ]) ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Client <span class="required">*</span></label>
                <?= $this->Form->control('client_id', [
                    'label' => false,
                    'options' => $clients,
                    'empty' => '-- Select Client --',
                    'required' => true
                ]) ?>
            </div>
            <div class="form-group">
                <label>Buyer ID <span class="required">*</span></label>
                <?= $this->Form->control('buyer_id', [
                    'label' => false,
                    'placeholder' => 'e.g., RA_0002855210B',
                    'required' => true
                ]) ?>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="form-section">
        <h3 class="form-section-title">Product Information</h3>
        <div class="form-row">
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Description <span class="required">*</span></label>
                <?= $this->Form->control('description', [
                    'label' => false,
                    'placeholder' => 'e.g., Dried Cocoa Beans-Cocoa',
                    'required' => true
                ]) ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Quantity (MT) <span class="required">*</span></label>
                <?= $this->Form->control('quantity_mt', [
                    'label' => false,
                    'type' => 'number',
                    'step' => '0.001',
                    'placeholder' => 'e.g., 302.600',
                    'required' => true
                ]) ?>
            </div>
        </div>
    </div>

    <!-- Sustainability Amounts -->
    <div class="form-section">
        <h3 class="form-section-title">Sustainability Details</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Sustainability Investment ($) <span class="required">*</span></label>
                <?= $this->Form->control('sustainability_investment', [
                    'label' => false,
                    'type' => 'number',
                    'step' => '0.01',
                    'placeholder' => 'e.g., 31773.00',
                    'id' => 'investment',
                    'required' => true,
                    'onchange' => 'calculateTotal()'
                ]) ?>
            </div>
            <div class="form-group">
                <label>Sustainability Differential ($) <span class="required">*</span></label>
                <?= $this->Form->control('sustainability_differential', [
                    'label' => false,
                    'type' => 'number',
                    'step' => '0.01',
                    'placeholder' => 'e.g., 21182.00',
                    'id' => 'differential',
                    'required' => true,
                    'onchange' => 'calculateTotal()'
                ]) ?>
            </div>
        </div>

        <!-- Calculation Display -->
        <div class="calculation-display">
            <div class="calc-row">
                <span class="calc-label">Sustainability Investment</span>
                <span class="calc-value" id="investment-display">$0.00</span>
            </div>
            <div class="calc-row">
                <span class="calc-label">Sustainability Differential</span>
                <span class="calc-value" id="differential-display">$0.00</span>
            </div>
            <div class="calc-row total">
                <span class="calc-label">TOTAL VALUE</span>
                <span class="calc-value total" id="total-display">$0.00</span>
            </div>
            <div class="calc-row" style="border-top: 1px solid #e5e7eb; margin-top: .5rem; padding-top: .75rem;">
                <span class="calc-label">NET RECEIVABLE (100% of Contract Value)</span>
                <span class="calc-value total" id="receivable-display">$0.00</span>
            </div>
        </div>
        <?= $this->Form->hidden('total_value', ['id' => 'total_value']) ?>
        <?= $this->Form->hidden('net_receivable', ['id' => 'net_receivable']) ?>
        <?= $this->Form->hidden('currency', ['value' => 'USD']) ?>
    </div>

    <!-- Banking Details -->
    <div class="form-section">
        <h3 class="form-section-title">Banking Information</h3>
        
        <h4 style="font-size: .9375rem; font-weight: 600; color: #6b7280; margin-bottom: 1rem;">Correspondent Bank</h4>
        <div class="form-row">
            <div class="form-group">
                <label>Bank Name</label>
                <?= $this->Form->control('correspondent_bank', [
                    'label' => false,
                    'placeholder' => 'e.g., CITI BANK N.A NEW YORK'
                ]) ?>
            </div>
            <div class="form-group">
                <label>Address</label>
                <?= $this->Form->control('correspondent_address', [
                    'label' => false,
                    'placeholder' => 'e.g., 111 WALL STREET NEWYORK, NY1004'
                ]) ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>SWIFT Code</label>
                <?= $this->Form->control('correspondent_swift', [
                    'label' => false,
                    'placeholder' => 'e.g., CITIUS33'
                ]) ?>
            </div>
            <div class="form-group">
                <label>ABA Routing</label>
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
                <label>Account Number</label>
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
                <label>Beneficiary's Account No</label>
                <?= $this->Form->control('beneficiary_acct_no', [
                    'label' => false,
                    'placeholder' => 'e.g., 5140005545'
                ]) ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>SWIFT Code</label>
                <?= $this->Form->control('beneficiary_swift', [
                    'label' => false,
                    'placeholder' => 'e.g., FIDTNGLA'
                ]) ?>
            </div>
            <div class="form-group">
                <label>Purpose</label>
                <?= $this->Form->control('purpose', [
                    'label' => false,
                    'placeholder' => 'e.g., COCOA EXPORT PROCEEDS'
                ]) ?>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="form-section">
        <h3 class="form-section-title">Additional Details</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <?= $this->Form->control('status', [
                    'label' => false,
                    'options' => [
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled'
                    ],
                    'default' => 'draft',
                    'required' => true
                ]) ?>
            </div>
        </div>
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
            '<i class="fas fa-save"></i> Create Invoice',
            ['class' => 'btn btn-primary', 'escapeTitle' => false]
        ) ?>
    </div>
    
    <?= $this->Form->end() ?>
</div>

<script>
function calculateTotal() {
    const investment = parseFloat(document.getElementById('investment').value) || 0;
    const differential = parseFloat(document.getElementById('differential').value) || 0;
    
    const total = investment + differential;
    const receivable = total; // 100% of contract value
    
    // Update hidden fields
    document.getElementById('total_value').value = total.toFixed(2);
    document.getElementById('net_receivable').value = receivable.toFixed(2);
    
    // Update displays
    document.getElementById('investment-display').textContent = '$' + investment.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    document.getElementById('differential-display').textContent = '$' + differential.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    document.getElementById('total-display').textContent = '$' + total.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    document.getElementById('receivable-display').textContent = '$' + receivable.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Initialize calculation on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>
