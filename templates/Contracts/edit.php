<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Contract $contract
 * @var string[]|\Cake\Collection\CollectionInterface $clients
 * @var string[]|\Cake\Collection\CollectionInterface $products
 */
$this->assign('title', 'Edit Contract');
?>

<style>
.form-card{background:#fff;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,.08);overflow:hidden;animation:fadeIn .5s}
.form-header{padding:2rem;background:linear-gradient(135deg,var(--warning),#d97706);color:#fff}
.form-header h1{margin:0;font-size:1.75rem;font-weight:700}
.form-header p{margin:.5rem 0 0;opacity:.9;font-size:.95rem}
.form-body{padding:2rem}
.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.5rem}
.form-group{display:flex;flex-direction:column;margin-bottom:1.5rem}
.form-group label{font-size:.875rem;font-weight:600;color:var(--gray-700);margin-bottom:.5rem}
.form-control{padding:.75rem 1rem;border:2px solid var(--gray-200);border-radius:8px;font-size:.9rem;transition:all .2s}
.form-control:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(12,83,67,.1)}
.form-text{font-size:.85rem;color:var(--gray-600);margin-top:.25rem}
.form-footer{padding:2rem;background:var(--gray-50);border-top:1px solid var(--gray-200);display:flex;gap:1rem;justify-content:space-between}
.calc-box{background:var(--gray-50);border:2px solid var(--primary);border-radius:12px;padding:1.5rem;margin-top:1rem}
.calc-row{display:flex;justify-content:space-between;align-items:center;padding:.75rem 0;border-bottom:1px solid var(--gray-300)}
.calc-row:last-child{border-bottom:none;font-size:1.25rem;font-weight:700;color:var(--primary)}
@keyframes fadeIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
</style>

<div style="max-width:1200px;margin:0 auto">
    <div style="margin-bottom:2rem">
        <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to Contracts', ['action' => 'index'], ['class' => 'btn btn-outline', 'escape' => false]) ?>
    </div>

    <div class="form-card">
        <div class="form-header">
            <h1><i class="fas fa-edit"></i> Edit Contract</h1>
            <p>Update contract details: <?= h($contract->contract_id) ?></p>
        </div>

        <?= $this->Form->create($contract, ['id' => 'contractForm']) ?>
        
        <div class="form-body">
            <!-- Contract Identification -->
            <h3 style="font-size:1.125rem;font-weight:600;margin-bottom:1.5rem;color:var(--gray-900)">
                <i class="fas fa-info-circle"></i> Contract Information
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Contract ID *</label>
                    <?= $this->Form->control('contract_id', [
                        'label' => false,
                        'class' => 'form-control',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <label>Contract Date</label>
                    <?= $this->Form->control('contract_date', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'date',
                        'empty' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <label>Start Date</label>
                    <?= $this->Form->control('start_date', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'date',
                        'empty' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <label>End Date</label>
                    <?= $this->Form->control('end_date', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'date',
                        'empty' => true
                    ]) ?>
                </div>
            </div>

            <!-- Client & Product -->
            <h3 style="font-size:1.125rem;font-weight:600;margin:2rem 0 1.5rem;color:var(--gray-900)">
                <i class="fas fa-handshake"></i> Client & Product Details
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Client *</label>
                    <?= $this->Form->control('client_id', [
                        'label' => false,
                        'options' => $clients,
                        'class' => 'form-control',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <label>Product *</label>
                    <?= $this->Form->control('product_id', [
                        'label' => false,
                        'options' => $products,
                        'class' => 'form-control',
                        'required' => true
                    ]) ?>
                </div>

                <div class="form-group">
                    <label>Status *</label>
                    <?= $this->Form->control('status', [
                        'label' => false,
                        'options' => ['active' => 'Active', 'expired' => 'Expired', 'cancelled' => 'Cancelled'],
                        'class' => 'form-control'
                    ]) ?>
                </div>
            </div>

            <!-- Quantities & Pricing -->
            <h3 style="font-size:1.125rem;font-weight:600;margin:2rem 0 1.5rem;color:var(--gray-900)">
                <i class="fas fa-calculator"></i> Quantities & Pricing
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Quantity (MT) *</label>
                    <?= $this->Form->control('quantity', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'step' => '0.001',
                        'id' => 'quantity',
                        'required' => true
                    ]) ?>
                    <span class="form-text">Total contract quantity</span>
                </div>

                <div class="form-group">
                    <label>Remaining Quantity (MT)</label>
                    <?= $this->Form->control('remaining_quantity', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'step' => '0.001',
                        'id' => 'remaining_quantity'
                    ]) ?>
                    <span class="form-text">Quantity not yet invoiced</span>
                </div>

                <div class="form-group">
                    <label>Unit Price (USD/MT) *</label>
                    <?= $this->Form->control('unit_price', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'step' => '0.01',
                        'id' => 'unit_price',
                        'required' => true
                    ]) ?>
                    <span class="form-text">Price per metric ton</span>
                </div>

                <div class="form-group">
                    <label>Total Value (USD)</label>
                    <?= $this->Form->control('total_value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'step' => '0.01',
                        'id' => 'total_value',
                        'readonly' => true
                    ]) ?>
                    <span class="form-text">Auto-calculated</span>
                </div>
            </div>

            <!-- Calculation Preview -->
            <div class="calc-box">
                <h4 style="margin:0 0 1rem;font-size:1rem;font-weight:600;color:var(--gray-800)">
                    <i class="fas fa-chart-line"></i> Contract Value Calculation
                </h4>
                <div class="calc-row">
                    <span>Quantity:</span>
                    <strong id="calc-quantity">0.000 MT</strong>
                </div>
                <div class="calc-row">
                    <span>Unit Price:</span>
                    <strong id="calc-price">$0.00/MT</strong>
                </div>
                <div class="calc-row">
                    <span>Total Contract Value:</span>
                    <strong id="calc-total">$0.00</strong>
                </div>
            </div>

            <!-- Terms & Notes -->
            <h3 style="font-size:1.125rem;font-weight:600;margin:2rem 0 1.5rem;color:var(--gray-900)">
                <i class="fas fa-file-alt"></i> Terms & Additional Information
            </h3>
            
            <div class="form-group">
                <label>Payment Terms</label>
                <?= $this->Form->control('payment_terms', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'rows' => 3
                ]) ?>
            </div>

            <div class="form-group">
                <label>Delivery Terms</label>
                <?= $this->Form->control('delivery_terms', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'rows' => 3
                ]) ?>
            </div>

            <div class="form-group">
                <label>Additional Notes</label>
                <?= $this->Form->control('notes', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'rows' => 3
                ]) ?>
            </div>
        </div>

        <div class="form-footer">
            <div>
                <?= $this->Form->postLink(
                    '<i class="fas fa-trash"></i> Delete Contract',
                    ['action' => 'delete', $contract->id],
                    [
                        'confirm' => 'Are you sure you want to delete contract ' . $contract->contract_id . '?',
                        'class' => 'btn btn-danger',
                        'escape' => false
                    ]
                ) ?>
            </div>
            <div style="display:flex;gap:1rem">
                <?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-outline']) ?>
                <?= $this->Form->button('<i class="fas fa-save"></i> Update Contract', [
                    'class' => 'btn btn-warning btn-lg',
                    'escape' => false
                ]) ?>
            </div>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<script>
// Auto-calculate total value
function calculateTotal() {
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
    const totalValue = quantity * unitPrice;
    
    document.getElementById('total_value').value = totalValue.toFixed(2);
    
    document.getElementById('calc-quantity').textContent = quantity.toFixed(3) + ' MT';
    document.getElementById('calc-price').textContent = '$' + unitPrice.toFixed(2) + '/MT';
    document.getElementById('calc-total').textContent = '$' + totalValue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

document.getElementById('quantity').addEventListener('input', calculateTotal);
document.getElementById('unit_price').addEventListener('input', calculateTotal);
document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
