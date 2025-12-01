<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Contract $contract
 * @var \Cake\Collection\CollectionInterface|string[] $clients
 * @var \Cake\Collection\CollectionInterface|string[] $products
 */
$this->assign('title', 'Add New Contract');
?>

<style>
/* Page Header */
.page-header-sleek {
    background: linear-gradient(135deg, #0c5343 0%, #083d2f 100%);
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    color: white;
    box-shadow: 0 2px 8px rgba(12,83,67,0.15);
}

.page-header-sleek h1 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Form Card */
.form-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.form-card-header {
    background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
    padding: 1.25rem;
    border-bottom: 2px solid #e5e7eb;
}

.form-card-header h2 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
}

.form-card-body {
    padding: 1.5rem;
}

/* Button Group */
.btn-group-sleek {
    display: flex;
    gap: 0.75rem;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
    margin-top: 1.5rem;
}

/* Form Label Styling */
.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

/* Form Control Focus */
.form-control:focus,
.form-select:focus {
    border-color: #0c5343;
    box-shadow: 0 0 0 0.25rem rgba(12,83,67,0.25);
}
</style>

<!-- Page Header -->
<div class="page-header-sleek">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>
            <i class="fas fa-plus"></i>
            Add New Contract
        </h1>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Back to List',
            ['action' => 'index'],
            ['class' => 'btn btn-light', 'escape' => false]
        ) ?>
    </div>
</div>

<!-- Form Card -->
<div class="form-card">
    <div class="form-card-header">
        <h2><i class="fas fa-edit"></i> Contract Information</h2>
    </div>
    <div class="form-card-body">
        <?= $this->Form->create($contract, [
            'class' => 'needs-validation',
            'novalidate' => true
        ]) ?>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <?= $this->Form->control('contract_id', [
                    'label' => ['text' => 'Contract ID *', 'class' => 'form-label'],
                    'class' => 'form-control',
                    'placeholder' => 'e.g., CTR-2025-001',
                    'required' => true,
                    'templates' => [
                        'inputContainer' => '<div class="{{required}}">{{content}}</div>',
                        'input' => '<input type="{{type}}" name="{{name}}" {{attrs}}/>'
                    ]
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('status', [
                    'label' => ['text' => 'Status *', 'class' => 'form-label'],
                    'class' => 'form-select',
                    'options' => [
                        'active' => 'Active',
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'inactive' => 'Inactive'
                    ],
                    'empty' => '-- Select Status --',
                    'required' => true
                ]) ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <?= $this->Form->control('client_id', [
                    'label' => ['text' => 'Client *', 'class' => 'form-label'],
                    'class' => 'form-select',
                    'options' => $clients,
                    'empty' => '-- Select Client --',
                    'required' => true
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('product_id', [
                    'label' => ['text' => 'Product *', 'class' => 'form-label'],
                    'class' => 'form-select',
                    'options' => $products,
                    'empty' => '-- Select Product --',
                    'required' => true
                ]) ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <?= $this->Form->control('contract_date', [
                    'label' => ['text' => 'Contract Date', 'class' => 'form-label'],
                    'class' => 'form-control',
                    'type' => 'date',
                    'empty' => true
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('expiry_date', [
                    'label' => ['text' => 'Expiry Date', 'class' => 'form-label'],
                    'class' => 'form-control',
                    'type' => 'date',
                    'empty' => true
                ]) ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <?= $this->Form->control('quantity', [
                    'label' => ['text' => 'Quantity (MT) *', 'class' => 'form-label'],
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '0.001',
                    'placeholder' => 'e.g., 1000.000',
                    'required' => true
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('unit_price', [
                    'label' => ['text' => 'Unit Price (USD) *', 'class' => 'form-label'],
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '0.01',
                    'placeholder' => 'e.g., 2500.00',
                    'required' => true
                ]) ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <?= $this->Form->control('payment_terms', [
                    'label' => ['text' => 'Payment Terms', 'class' => 'form-label'],
                    'class' => 'form-control',
                    'placeholder' => 'e.g., 30 days net, L/C at sight'
                ]) ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <?= $this->Form->control('notes', [
                    'label' => ['text' => 'Notes', 'class' => 'form-label'],
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'rows' => 4,
                    'placeholder' => 'Additional notes or comments about this contract...'
                ]) ?>
            </div>
        </div>

        <div class="btn-group-sleek">
            <?= $this->Form->button('<i class="fas fa-check"></i> Save Contract', [
                'class' => 'btn btn-primary',
                'escapeTitle' => false
            ]) ?>
            <?= $this->Html->link(
                '<i class="fas fa-times"></i> Cancel',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
        
        <?= $this->Form->end() ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Show validation errors with SweetAlert if any
<?php if ($this->Form->isFieldError('contract_id') || $this->Form->isFieldError('client_id') || $this->Form->isFieldError('product_id')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        text: 'Please check the form for errors and try again.',
        confirmButtonColor: '#0c5343'
    });
<?php endif; ?>
</script>
