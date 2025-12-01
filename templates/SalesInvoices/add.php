<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SalesInvoice $salesInvoice
 */
$this->assign('title', 'Create Sales Invoice');
?>

<style>
.page-header {
    background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%);
    padding: 1.5rem 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.15);
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
    border-color: #ff5722;
    box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
}
.form-group textarea {
    resize: vertical;
    min-height: 100px;
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
    border-top: 2px solid #ff5722;
    margin-top: .5rem;
    padding-top: 1rem;
}
.calc-label {
    font-size: .875rem;
    font-weight: 600;
    color: #6b7280;
}
.calc-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #ff5722;
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
    background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(255, 87, 34, 0.3);
}
.btn-primary:hover {
    box-shadow: 0 6px 16px rgba(255, 87, 34, 0.4);
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
        Create Sales Invoice
    </h2>
    <p>Generate a new sales invoice for product transactions</p>
</div>

<div class="form-container">
    <?= $this->Form->create($salesInvoice) ?>
    
    <!-- Basic Information -->
    <div class="form-section">
        <h3 class="form-section-title">Invoice Details</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Invoice Number <span class="required">*</span></label>
                <?= $this->Form->control('invoice_number', [
                    'label' => false,
                    'placeholder' => 'e.g., 0168',
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
                <label>Client <span class="required">*</span></label>
                <?= $this->Form->control('client_id', [
                    'label' => false,
                    'options' => $clients,
                    'empty' => '-- Select Client --',
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
                    'placeholder' => 'e.g., SALE OF JUTE BAGS',
                    'required' => true
                ]) ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Quantity <span class="required">*</span></label>
                <?= $this->Form->control('quantity', [
                    'label' => false,
                    'type' => 'number',
                    'step' => '0.001',
                    'placeholder' => 'e.g., 22778.00',
                    'id' => 'quantity',
                    'required' => true,
                    'onchange' => 'calculateTotal()'
                ]) ?>
            </div>
            <div class="form-group">
                <label>Unit Price <span class="required">*</span></label>
                <?= $this->Form->control('unit_price', [
                    'label' => false,
                    'type' => 'number',
                    'step' => '0.01',
                    'placeholder' => 'e.g., 300.00',
                    'id' => 'unit_price',
                    'required' => true,
                    'onchange' => 'calculateTotal()'
                ]) ?>
            </div>
            <div class="form-group">
                <label>Currency <span class="required">*</span></label>
                <?= $this->Form->control('currency', [
                    'label' => false,
                    'options' => ['NGN' => 'NGN - Nigerian Naira', 'USD' => 'USD - US Dollar', 'EUR' => 'EUR - Euro', 'GBP' => 'GBP - British Pound'],
                    'default' => 'NGN',
                    'required' => true,
                    'id' => 'currency',
                    'onchange' => 'calculateTotal()'
                ]) ?>
            </div>
        </div>

        <!-- Calculation Display -->
        <div class="calculation-display">
            <div class="calc-row total">
                <span class="calc-label">TOTAL VALUE</span>
                <span class="calc-value" id="total-display">₦0.00</span>
            </div>
        </div>
        <?= $this->Form->hidden('total_value', ['id' => 'total_value']) ?>
    </div>

    <!-- Payment Details -->
    <div class="form-section">
        <h3 class="form-section-title">Payment Information</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Bank Account</label>
                <?= $this->Form->control('bank_account_id', [
                    'label' => false,
                    'options' => $bankAccounts,
                    'empty' => '-- Select Bank Account --'
                ]) ?>
            </div>
            <div class="form-group">
                <label>Purpose</label>
                <?= $this->Form->control('purpose', [
                    'label' => false,
                    'placeholder' => 'e.g., Sale of Jute Bags'
                ]) ?>
            </div>
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
    </div>

    <!-- Additional Notes -->
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
            '<i class="fas fa-save"></i> Create Invoice',
            ['class' => 'btn btn-primary', 'escapeTitle' => false]
        ) ?>
    </div>
    
    <?= $this->Form->end() ?>
</div>

<script>
function calculateTotal() {
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
    const currency = document.getElementById('currency').value;
    
    const total = quantity * unitPrice;
    
    // Update hidden field
    document.getElementById('total_value').value = total.toFixed(2);
    
    // Update display with currency symbol
    let symbol = '₦';
    if (currency === 'USD') symbol = '$';
    else if (currency === 'EUR') symbol = '€';
    else if (currency === 'GBP') symbol = '£';
    
    document.getElementById('total-display').textContent = symbol + total.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Initialize calculation on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>
