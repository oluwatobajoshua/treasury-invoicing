<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FreshInvoice $freshInvoice
 */
$this->assign('title', $freshInvoice->isNew() ? 'Add Fresh Invoice' : 'Edit Fresh Invoice #' . $freshInvoice->invoice_number);
?>

<style>
.invoice-form-wrapper{background:#fff;max-width:1200px;margin:2rem auto;box-shadow:0 0 20px rgba(0,0,0,.1);overflow:hidden}
.company-header{display:flex;justify-content:space-between;align-items:flex-start;padding:2rem 2rem 1rem;border-bottom:4px solid #ff5722}
.company-logo img{max-height:80px;width:auto}
.company-info{text-align:right;font-size:.85rem;line-height:1.8;color:#333}
.company-info strong{font-weight:700}
.invoice-header{background:#fff;padding:1.5rem 2rem;border-bottom:2px solid #e0e0e0}
.invoice-title{text-align:center;font-size:2rem;font-weight:700;color:#333;margin:0 0 .5rem;text-transform:uppercase;letter-spacing:2px}
.invoice-meta{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem;margin-top:1.5rem}
.meta-group{display:flex;flex-direction:column;gap:.5rem}
.meta-label{font-size:.8rem;font-weight:600;color:#666;text-transform:uppercase;letter-spacing:.5px}
.meta-value{font-size:.95rem;font-weight:600;color:#333}
.invoice-body{padding:2rem}
.section-title{font-size:1rem;font-weight:700;color:#333;margin:0 0 1rem;padding-bottom:.5rem;border-bottom:2px solid #e0e0e0;text-transform:uppercase;letter-spacing:.5px}
.invoice-table{width:100%;border-collapse:collapse;margin-bottom:2rem;border:2px solid #0c5343;table-layout:fixed}
.invoice-table th{background:linear-gradient(135deg,#0c5343 0%,#0a4636 100%);color:#fff;padding:1rem;text-align:center;font-size:.85rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;border:1px solid #0c5343}
.invoice-table td{padding:.75rem;border:1px solid #0c5343;vertical-align:middle;word-wrap:break-word}
.invoice-table .field-label{font-size:.8rem;font-weight:600;color:#555;margin-bottom:.5rem;display:block;text-transform:uppercase}
.invoice-table input,.invoice-table select,.invoice-table textarea{width:100%;padding:.6rem;border:1px solid #ddd;border-radius:4px;font-size:.9rem;box-sizing:border-box}
.invoice-table input:focus,.invoice-table select:focus,.invoice-table textarea:focus{outline:none;border-color:#ff5722;box-shadow:0 0 0 2px rgba(255,87,34,.1)}
.invoice-table .readonly-field{background:#f5f5f5;font-weight:700;color:#ff5722;font-size:1.1rem;text-align:right;padding:1rem}
.total-section{background:#fff;padding:1.5rem 2rem;border-top:3px solid #0c5343}
.total-row{display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;font-size:.95rem}
.total-row.grand-total{font-size:1.3rem;font-weight:700;color:#333;border-top:2px solid #333;padding-top:1rem;margin-top:.5rem}
.total-label{font-weight:600;color:#555;text-transform:uppercase}
.total-value{font-weight:700;color:#333}
.grand-total .total-value{color:#ff5722;font-size:1.5rem}
.form-actions{padding:2rem;background:#f9f9f9;border-top:2px solid #e0e0e0;display:flex;gap:1rem;justify-content:center}
.hint-text{font-size:.75rem;color:#888;margin-top:.25rem;font-style:italic}
.date-display{font-size:.9rem;color:#666;text-align:right;margin-top:.5rem}
@keyframes fadeIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
</style>

<div style="max-width:100%;padding:1rem">
    <div style="margin-bottom:1rem">
        <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to Fresh Invoices', ['action' => 'index'], ['class' => 'btn btn-outline', 'escape' => false]) ?>
    </div>

    <?= $this->Form->create($freshInvoice, ['id' => 'invoiceForm']) ?>
    
    <div class="invoice-form-wrapper">
        <!-- Company Header with Logo -->
        <div class="company-header">
            <div class="company-logo">
                <?php if (isset($settings) && $settings->company_logo): ?>
                    <?= $this->Html->image($settings->company_logo, ['alt' => 'Company Logo']) ?>
                <?php else: ?>
                    <div style="width:200px;height:60px;background:#ff5722;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.2rem">
                        LOGO
                    </div>
                <?php endif; ?>
            </div>
            <div class="company-info">
                <strong>Email:</strong> <?= h($settings->email ?? 'info@sunbeth.net') ?><br>
                <strong>Telephone:</strong> <?= h($settings->telephone ?? '+234(0)805 6666 266') ?><br>
                <strong>Corporate Office:</strong> First Floor, Churchgate Towers 2,<br>
                Victoria Island, Lagos State, Nigeria.
            </div>
        </div>
        
        <div class="date-display" style="padding:0 2rem">
            <strong><?= date('jS F, Y') ?></strong>
        </div>

        <!-- Invoice Header -->
        <div class="invoice-header">
            <h1 class="invoice-title">INVOICE</h1>
            
            <div class="invoice-meta">
                <div class="meta-group">
                    <span class="meta-label">Vessel</span>
                    <div>
                        <?= $this->Form->control('vessel_id', [
                            'label' => false,
                            'options' => $vessels,
                            'empty' => '-- Select Vessel --',
                            'required' => true,
                            'style' => 'padding:.5rem;border:2px solid #ddd;border-radius:4px;font-weight:600;font-size:.9rem'
                        ]) ?>
                    </div>
                </div>
                
                <div class="meta-group">
                    <span class="meta-label">BL No.</span>
                    <div>
                        <?= $this->Form->control('bl_number', [
                            'label' => false,
                            'required' => true,
                            'placeholder' => 'e.g., LOS37115',
                            'style' => 'padding:.5rem;border:2px solid #ddd;border-radius:4px;font-weight:600;font-size:.9rem'
                        ]) ?>
                    </div>
                </div>
                
                <?php if (!$freshInvoice->isNew()): ?>
                <div class="meta-group">
                    <span class="meta-label">Invoice No.</span>
                    <div class="meta-value" style="padding:.5rem;background:#f5f5f5;border-radius:4px">
                        <?= h($freshInvoice->invoice_number) ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="meta-group">
                    <span class="meta-label">Contract</span>
                    <div>
                        <?= $this->Form->control('contract_id', [
                            'label' => false,
                            'options' => $contracts,
                            'id' => 'contract-select',
                            'required' => true,
                            'empty' => '-- Select --',
                            'style' => 'padding:.5rem;border:2px solid #ddd;border-radius:4px;font-weight:600;font-size:.9rem'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Body -->
        <div class="invoice-body">
            <!-- Invoice Details Table -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width:18%">CONTRACT NO</th>
                        <th style="width:12%">QTY (MT)</th>
                        <th style="width:30%">DESCRIPTION</th>
                        <th style="width:15%">BULK/BAG</th>
                        <th style="width:12%">PRICE ($)</th>
                        <th style="width:13%">TOTAL VALUE ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div id="display-contract-id" style="font-weight:700;color:#ff5722;font-size:.95rem">—</div>
                        </td>
                        <td>
                            <?= $this->Form->control('quantity', [
                                'label' => false,
                                'type' => 'number',
                                'step' => '0.001',
                                'id' => 'quantity',
                                'required' => true,
                                'placeholder' => '0.000',
                                'style' => 'text-align:center;font-weight:600'
                            ]) ?>
                        </td>
                        <td>
                            <span class="field-label">Product</span>
                            <?= $this->Form->control('product_id', [
                                'label' => false,
                                'options' => $products,
                                'id' => 'product-id',
                                'required' => true
                            ]) ?>
                            <span class="field-label" style="margin-top:.75rem">Client</span>
                            <?= $this->Form->control('client_id', [
                                'label' => false,
                                'options' => $clients,
                                'id' => 'client-id',
                                'required' => true
                            ]) ?>
                        </td>
                        <td>
                            <?= $this->Form->control('bulk_or_bag', [
                                'label' => false,
                                'type' => 'select',
                                'options' => [
                                    '16 BULK' => '16 BULK',
                                    '15 BULK' => '15 BULK',
                                    '14 BULK' => '14 BULK',
                                    '16 BAGS' => '16 BAGS',
                                    '15 BAGS' => '15 BAGS',
                                    '14 BAGS' => '14 BAGS',
                                    'BULK' => 'BULK',
                                    'BAGS' => 'BAGS',
                                    'CONTAINERS' => 'CONTAINERS',
                                    'PALLETS' => 'PALLETS'
                                ],
                                'empty' => '-- Select --',
                                'required' => true
                            ]) ?>
                        </td>
                        <td>
                            <?= $this->Form->control('unit_price', [
                                'label' => false,
                                'type' => 'number',
                                'step' => '0.01',
                                'id' => 'unit-price',
                                'required' => true,
                                'placeholder' => '0.00',
                                'style' => 'text-align:right;font-weight:600'
                            ]) ?>
                        </td>
                        <td class="readonly-field" id="row-total">$0.00</td>
                    </tr>
                    <tr>
                        <td colspan="6" style="padding:0;border:none">
                            <table style="width:100%;border-collapse:collapse;margin:0">
                                <tr>
                                    <td colspan="5" style="border:1px solid #333;padding:1rem;text-align:right;font-weight:700;font-size:1.1rem">
                                        TOTAL VALUE:
                                    </td>
                                    <td class="readonly-field" id="subtotal-display" style="border:1px solid #333;font-size:1.2rem">$0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="border:1px solid #333;padding:1rem;text-align:right;font-weight:700;font-size:1.1rem">
                                        AMOUNT PAYABLE @
                                        <?= $this->Form->control('payment_percentage', [
                                            'label' => false,
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'id' => 'payment-percentage',
                                            'value' => 98,
                                            'required' => true,
                                            'style' => 'width:80px;display:inline;padding:.3rem;border:2px solid #ddd;border-radius:4px;text-align:center;font-weight:700'
                                        ]) ?>%
                                    </td>
                                    <td class="readonly-field" id="calc-total" style="border:1px solid #333;font-size:1.3rem;color:#ff5722">$0.00</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Payment Details -->
            <div style="margin:2rem 0">
                <div class="section-title">Payment Details</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
                    <div>
                        <span class="field-label">SGC Account</span>
                        <?= $this->Form->control('sgc_account_id', [
                            'label' => false,
                            'options' => $sgcAccounts,
                            'required' => true
                        ]) ?>
                    </div>
                    <div>
                        <span class="field-label">Invoice Date</span>
                        <?= $this->Form->control('invoice_date', [
                            'label' => false,
                            'type' => 'date',
                            'value' => date('Y-m-d')
                        ]) ?>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div>
                <div class="section-title">Additional Notes</div>
                <?= $this->Form->control('notes', [
                    'label' => false,
                    'type' => 'textarea',
                    'rows' => 3,
                    'placeholder' => 'Enter any additional notes or special instructions...',
                    'style' => 'width:100%;padding:.75rem;border:2px solid #ddd;border-radius:4px;font-size:.9rem;resize:vertical'
                ]) ?>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-outline btn-lg', 'style' => 'min-width:150px']) ?>
            <?= $this->Form->button(
                $freshInvoice->isNew() ? 'Create Invoice' : 'Update Invoice',
                [
                    'class' => 'btn btn-primary btn-lg',
                    'style' => 'min-width:200px'
                ]
            ) ?>
        </div>
    </div>
    
    <?= $this->Form->end() ?>
</div>

<script>
// Auto-calculate total value
function calculateTotal() {
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unit-price').value) || 0;
    const paymentPercentage = parseFloat(document.getElementById('payment-percentage').value) || 0;
    
    const subtotal = quantity * unitPrice;
    const totalValue = subtotal * (paymentPercentage / 100);
    
    // Update all display fields
    document.getElementById('row-total').textContent = '$' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('subtotal-display').textContent = '$' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('calc-total').textContent = '$' + totalValue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

document.getElementById('quantity').addEventListener('input', calculateTotal);
document.getElementById('unit-price').addEventListener('input', calculateTotal);
document.getElementById('payment-percentage').addEventListener('input', calculateTotal);

// Load contract details when selected
document.getElementById('contract-select').addEventListener('change', function() {
    const contractId = this.value;
    const selectedOption = this.options[this.selectedIndex];
    const contractText = selectedOption ? selectedOption.text : '—';
    
    document.getElementById('display-contract-id').textContent = contractText;
    
    if (contractId) {
        fetch('/treasury-inv/fresh-invoices/get-contract-details?contract_id=' + contractId)
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    document.getElementById('client-id').value = data.client_id;
                    document.getElementById('product-id').value = data.product_id;
                    document.getElementById('unit-price').value = data.unit_price;
                    
                    calculateTotal();
                }
            })
            .catch(error => {
                console.error('Error loading contract:', error);
            });
    }
});

document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
