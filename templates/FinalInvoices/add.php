<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FinalInvoice $finalInvoice
 */
$this->assign('title', $finalInvoice->isNew() ? 'Add Final Invoice' : 'Edit Final Invoice #' . $finalInvoice->invoice_number);
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
.invoice-table .readonly-field{background:#f5f5f5;font-weight:700;color:#ff5722;font-size:1.1rem;text-align:right;padding:1rem}
.invoice-table input,.invoice-table select{width:100%;box-sizing:border-box;min-width:0}
.total-section{background:#fff;padding:1.5rem 2rem;border-top:3px solid #333}
.total-row{display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;font-size:.95rem}
.total-row.grand-total{font-size:1.3rem;font-weight:700;color:#333;border-top:2px solid #333;padding-top:1rem;margin-top:.5rem}
.total-label{font-weight:600;color:#555;text-transform:uppercase}
.total-value{font-weight:700;color:#333}
.grand-total .total-value{color:#ff5722;font-size:1.5rem}
.form-actions{padding:2rem;background:#f9f9f9;border-top:2px solid #e0e0e0;display:flex;gap:1rem;justify-content:center}
.date-display{font-size:.9rem;color:#666;text-align:right;margin-top:.5rem}
.variance-pill{font-weight:700;padding:.25rem .5rem;border-radius:4px}
.variance-pill.pos{background:#e8f5e9;color:#1b5e20}
.variance-pill.neg{background:#ffebee;color:#b71c1c}
.muted{color:#777}
.spinner{display:none;gap:.5rem;align-items:center;color:#555}
.spinner.show{display:inline-flex}
</style>

<div style="max-width:100%;padding:1rem">
    <div style="margin-bottom:1rem">
        <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Back to Final Invoices', ['action' => 'index'], ['class' => 'btn btn-outline', 'escape' => false]) ?>
    </div>

    <?= $this->Form->create($finalInvoice, ['id' => 'finalInvoiceForm']) ?>
    
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
            <h1 class="invoice-title">FINAL INVOICE</h1>
            <?php if (isset($hasEligibleFreshInvoices) && !$hasEligibleFreshInvoices): ?>
                <div style="margin-top:1rem;padding:1rem;border-left:4px solid #ff5722;background:#fff7f3;color:#6d2b1f;border-radius:4px">
                    <strong>No eligible Fresh Invoices found.</strong>
                    <div style="margin-top:.25rem">Create and approve a Fresh Invoice first before raising a Final Invoice.</div>
                    <div style="margin-top:.5rem">
                        <?= $this->Html->link('Go to Fresh Invoices', ['controller' => 'FreshInvoices', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="invoice-meta">
                <div class="meta-group">
                    <span class="meta-label">Reference Fresh Invoice</span>
                    <?= $this->Form->control('fresh_invoice_id', [
                        'label' => false,
                        'options' => $freshInvoices,
                        'empty' => '-- Select Fresh Invoice --',
                        'required' => true,
                        'id' => 'fresh-invoice-select',
                        'style' => 'padding:.5rem;border:2px solid #ddd;border-radius:4px;font-weight:600;font-size:.9rem'
                    ]) ?>
                    <div id="fetch-spinner" class="spinner" style="margin-top:.25rem">
                        <span class="loader" style="width:12px;height:12px;border:2px solid #ddd;border-top-color:#ff5722;border-radius:50%;display:inline-block;animation:spin 1s linear infinite"></span>
                        <span class="muted">Loading details…</span>
                    </div>
                </div>
                <div class="meta-group">
                    <span class="meta-label">Original Invoice No.</span>
                    <?= $this->Form->control('original_invoice_number', [
                        'label' => false,
                        'readonly' => true,
                        'id' => 'original-invoice-number',
                        'style' => 'padding:.5rem;border:2px solid #ddd;border-radius:4px;font-weight:600;font-size:.9rem;background:#f5f5f5'
                    ]) ?>
                </div>
                <?php if (!$finalInvoice->isNew()): ?>
                <div class="meta-group">
                    <span class="meta-label">FVP Invoice No.</span>
                    <div class="meta-value" style="padding:.5rem;background:#f5f5f5;border-radius:4px">
                        <?= h($finalInvoice->invoice_number) ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="meta-group">
                    <span class="meta-label">BL No.</span>
                    <?= $this->Form->control('bl_number', [
                        'label' => false,
                        'readonly' => true,
                        'id' => 'bl-number',
                        'style' => 'padding:.5rem;border:2px solid #ddd;border-radius:4px;font-weight:600;font-size:.9rem;background:#f5f5f5'
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Invoice Body -->
        <div class="invoice-body">
            <!-- Shipment & Amount Table -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width:18%">VESSEL</th>
                        <th style="width:11%">ORIGINAL QTY</th>
                        <th style="width:11%">LANDED QTY</th>
                        <th style="width:11%">VARIANCE</th>
                        <th style="width:11%">PRICE ($)</th>
                        <th style="width:11%">PAYMENT %</th>
                        <th style="width:14%">TOTAL VALUE ($)</th>
                    </tr>
                    <tr>
                        <td colspan="7" style="background:#f9f9f9;padding:.75rem">
                            <span class="meta-label">Packaging (from Fresh): </span>
                            <span id="packaging-display" style="font-weight:700;color:#333;margin-left:.5rem">—</span>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?= $this->Form->control('vessel_name', [
                                'label' => false,
                                'readonly' => true,
                                'id' => 'vessel-name',
                                'style' => 'padding:.6rem;border:1px solid #ddd;border-radius:4px;font-weight:600;background:#f5f5f5;font-size:.85rem'
                            ]) ?>
                        </td>
                        <td style="text-align:center">
                            <input type="text" id="original-quantity" readonly style="width:100%;padding:.6rem;border:1px solid #ddd;border-radius:4px;font-weight:600;text-align:center;background:#f5f5f5;font-size:.85rem;box-sizing:border-box" />
                        </td>
                        <td style="text-align:center">
                            <?= $this->Form->control('landed_quantity', [
                                'label' => false,
                                'type' => 'number',
                                'step' => '0.001',
                                'id' => 'landed-quantity',
                                'required' => true,
                                'style' => 'width:100%;padding:.6rem;text-align:center;font-weight:600;border:2px solid #ff5722;border-radius:4px;font-size:.85rem;box-sizing:border-box'
                            ]) ?>
                        </td>
                        <td style="text-align:center">
                            <div id="variance-pill" class="variance-pill" style="text-align:center;background:#f5f5f5;font-size:.85rem;padding:.5rem">—</div>
                        </td>
                        <td style="text-align:right">
                            <?= $this->Form->control('unit_price', [
                                'label' => false,
                                'type' => 'number',
                                'step' => '0.01',
                                'id' => 'unit-price',
                                'readonly' => true,
                                'style' => 'width:100%;padding:.6rem;text-align:right;font-weight:600;background:#f5f5f5;border:1px solid #ddd;border-radius:4px;font-size:.85rem;box-sizing:border-box'
                            ]) ?>
                        </td>
                        <td style="text-align:center">
                            <?= $this->Form->control('payment_percentage', [
                                'label' => false,
                                'type' => 'number',
                                'step' => '0.01',
                                'id' => 'payment-percentage',
                                'readonly' => true,
                                'style' => 'width:100%;padding:.6rem;text-align:center;font-weight:700;background:#f5f5f5;border:1px solid #ddd;border-radius:4px;font-size:.85rem;box-sizing:border-box'
                            ]) ?>
                        </td>
                        <td class="readonly-field" id="row-total" style="font-size:1rem">$0.00</td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Amount Payable -->
            <div style="background:#f9f9f9;border:2px solid #0c5343;border-radius:8px;padding:1.5rem;margin-bottom:2rem">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
                    <div style="font-size:1.2rem;font-weight:700;color:#333">
                        AMOUNT PAYABLE @ <span id="payable-percent" style="color:#ff5722">0</span>%
                    </div>
                    <div id="calc-total" style="font-size:1.8rem;font-weight:800;color:#ff5722">$0.00</div>
                </div>
            </div>

            <!-- Payment Details -->
            <div style="margin:2rem 0">
                <div class="section-title">Payment Details</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
                    <div>
                        <span class="meta-label">SGC Account</span>
                        <?= $this->Form->control('sgc_account_id', [
                            'label' => false,
                            'options' => $sgcAccounts,
                            'required' => true
                        ]) ?>
                    </div>
                    <div>
                        <span class="meta-label">Invoice Date</span>
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
                <div class="section-title">Notes (CWT Variance & Comments)</div>
                <?= $this->Form->control('notes', [
                    'label' => false,
                    'type' => 'textarea',
                    'rows' => 3,
                    'placeholder' => 'Enter variance notes and any comments...',
                    'style' => 'width:100%;padding:.75rem;border:2px solid #ddd;border-radius:4px;font-size:.9rem;resize:vertical'
                ]) ?>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-outline btn-lg', 'style' => 'min-width:150px']) ?>
            <?= $this->Form->button(
                $finalInvoice->isNew() ? 'Create Final Invoice' : 'Update Final Invoice',
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
// small spinner style
const style = document.createElement('style');
style.innerHTML = '@keyframes spin{to{transform:rotate(360deg)}}';
document.head.appendChild(style);

function calculateValues() {
    const landedQuantity = parseFloat(document.getElementById('landed-quantity').value) || 0;
    const originalQuantity = parseFloat(document.getElementById('original-quantity').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unit-price').value) || 0;
    const paymentPercentage = parseFloat(document.getElementById('payment-percentage').value) || 0;

    const subtotal = landedQuantity * unitPrice;
    const total = subtotal * (paymentPercentage / 100);
    // Only compute variance when landed quantity entered; otherwise show em dash
    let varianceText = '—';
    let varianceVal = null;
    if (document.getElementById('landed-quantity').value !== '') {
        varianceVal = landedQuantity - originalQuantity; // positive means landed > original
        varianceText = varianceVal.toLocaleString('en-US', {minimumFractionDigits: 3, maximumFractionDigits: 3});
    }

    document.getElementById('row-total').textContent = '$' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('calc-total').textContent = '$' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    const pill = document.getElementById('variance-pill');
    pill.textContent = varianceText;
    pill.classList.remove('pos','neg');
    if (varianceVal !== null) {
        if (varianceVal >= 0) pill.classList.add('pos'); else pill.classList.add('neg');
    }
    document.getElementById('payable-percent').textContent = (paymentPercentage || 0).toString();
}

const landedEl = document.getElementById('landed-quantity');
if (landedEl) landedEl.addEventListener('input', calculateValues);

// Load fresh invoice details when selected
const freshSelect = document.getElementById('fresh-invoice-select');
if (freshSelect) {
    freshSelect.addEventListener('change', function() {
        const freshInvoiceId = this.value;
        if (freshInvoiceId) {
            const spinner = document.getElementById('fetch-spinner');
            if (spinner) spinner.classList.add('show');
            fetch('<?= $this->Url->build(["controller" => "FinalInvoices", "action" => "getFreshInvoiceDetails"]) ?>?fresh_invoice_id=' + freshInvoiceId)
                .then(response => response.json())
                .then(data => {
                    if (!data.error) {
                        document.getElementById('original-invoice-number').value = data.invoice_number || '';
                        document.getElementById('vessel-name').value = data.vessel_name || '';
                        document.getElementById('bl-number').value = data.bl_number || '';
                        document.getElementById('unit-price').value = data.unit_price || 0;
                        document.getElementById('payment-percentage').value = data.payment_percentage || 0;
                        const sgc = document.getElementById('sgc-account-id');
                        const sgcAlt = document.getElementById('sgc-account');
                        const sgcTarget = sgc || sgcAlt;
                        if (sgcTarget) sgcTarget.value = data.sgc_account_id || '';
                        document.getElementById('original-quantity').value = (data.quantity ?? 0);
                        // Packaging display
                        const pack = document.getElementById('packaging-display');
                        if (pack) pack.textContent = (data.bulk_or_bag || '—');
                        calculateValues();
                        // focus landed qty for quick entry
                        const landed = document.getElementById('landed-quantity');
                        if (landed) landed.focus();
                    } else {
                        console.error('Error from server:', data.error);
                        alert('Error loading Fresh Invoice details: ' + data.error);
                    }
                })
                .catch(err => {
                    console.error('Error loading fresh invoice:', err);
                    alert('Failed to load Fresh Invoice details. Please try again.');
                })
                .finally(() => { 
                    const spinner = document.getElementById('fetch-spinner'); 
                    if (spinner) spinner.classList.remove('show'); 
                });
        }
    });
}

document.addEventListener('DOMContentLoaded', calculateValues);
</script>
