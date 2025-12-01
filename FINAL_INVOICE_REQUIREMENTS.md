# Final Invoice Requirements

## Layout Structure (Based on User Requirements)

### Invoice Display Format

The Final Invoice follows the same layout as Fresh Invoice with these key differences:

1. **Title**: "INVOICE" (not "FINAL INVOICE")
2. **Table Columns**: Same as Fresh Invoice
   - CONTRACT NO
   - QTY (MT) - Shows the **landed/final quantity**
   - DESCRIPTION
   - BULK
   - PRICE ($)
   - TOTAL VALUE ($)

3. **Amount Calculation Section**:
   ```
   TOTAL VALUE:             $1,640,717.68
   LESS AMOUNT PAID :       $1,618,275.38  (from Fresh Invoice)
   ───────────────────────────────────────
   AMOUNT DUE :             $22,442.30
   ```

### Key Data Points for Add/Edit Forms

#### Required Fields:
1. **fresh_invoice_id** - Reference to the Fresh Invoice
2. **landed_quantity** - The final/actual quantity landed (MT)
3. **unit_price** - Price per MT (usually same as Fresh Invoice)
4. **amount_paid** - The amount already paid via Fresh Invoice (editable)
5. **bl_number** - Bill of Lading number
6. **vessel_name** - Vessel name
7. **invoice_number** - Auto-generated final invoice number
8. **invoice_date** - Date of final invoice
9. **sgc_account_id** - Bank account for payment
10. **notes** - Purpose (default: "Cocoa export proceeds")

#### Calculated Fields:
1. **Total Value** = `landed_quantity × unit_price`
2. **Amount Due** = `Total Value - amount_paid`

#### Important Notes:

1. **Fresh Invoice Link**:
   - When creating Final Invoice, must link to existing Fresh Invoice
   - Pull client, product, contract, vessel info from Fresh Invoice
   - **Amount Paid field is editable** - pre-fill with Fresh Invoice's total_value but allow user to adjust
   - This allows for partial payments or adjusted amounts

2. **Quantity Difference**:
   - Fresh Invoice: Original contracted quantity (e.g., 262.43 MT)
   - Final Invoice: Actual landed quantity (e.g., 260.748 MT)
   - The variance (positive or negative) determines if balance is due or refund

3. **Payment Calculation Logic**:
   ```php
   // Fresh Invoice (98% advance payment) - suggested default
   $freshTotal = $quantity × $unitPrice;
   $freshPayment = $freshTotal × 0.98; // Suggested amount (editable)
   
   // Final Invoice (balance calculation)
   $finalTotal = $landedQuantity × $unitPrice;
   $amountDue = $finalTotal - $amountPaid; // User enters amount_paid
   
   // If positive: Client owes additional payment
   // If negative: Client paid excess (rare, due to lower landed qty)
   ```

4. **Form Validation**:
   - `landed_quantity` must be > 0
   - `unit_price` must be > 0
   - `amount_paid` must be >= 0 (can be 0 if no advance payment)
   - `fresh_invoice_id` must exist and be valid
   - Fresh Invoice should be in 'approved' or 'sent_to_export' status

5. **Status Workflow**:
   - draft → pending_treasurer_approval → approved → sent_to_sales

### Database Schema Implications

```sql
-- Fresh Invoice stores:
- quantity (original contracted)
- total_value (98% payment)

-- Final Invoice stores:
- fresh_invoice_id (FK)
- landed_quantity (actual quantity)
- unit_price (same as fresh usually)
- amount_paid (editable - what was already paid)
-- Amount Due is calculated: (landed_quantity × unit_price) - amount_paid
```

**IMPORTANT**: Add `amount_paid` column to `final_invoices` table if not already present:
```sql
ALTER TABLE final_invoices ADD COLUMN amount_paid DECIMAL(12,2) DEFAULT 0.00 AFTER unit_price;
```

### Add/Edit Form Structure

```php
// Row 1: Fresh Invoice Selection and Invoice Date
<div class="row mb-3">
    <div class="col-md-6">
        <?= $this->Form->control('fresh_invoice_id', [
            'type' => 'select',
            'options' => $freshInvoices,
            'empty' => '-- Select Fresh Invoice --',
            'label' => ['text' => 'Fresh Invoice *', 'class' => 'form-label'],
            'class' => 'form-select',
            'required' => true
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $this->Form->control('invoice_date', [
            'type' => 'date',
            'label' => ['text' => 'Invoice Date *', 'class' => 'form-label'],
            'class' => 'form-control',
            'required' => true
        ]) ?>
    </div>
</div>

// Row 2: Landed Quantity and Unit Price
<div class="row mb-3">
    <div class="col-md-6">
        <?= $this->Form->control('landed_quantity', [
            'type' => 'number',
            'step' => '0.001',
            'label' => ['text' => 'Landed Quantity (MT) *', 'class' => 'form-label'],
            'class' => 'form-control',
            'required' => true,
            'id' => 'landed-quantity'
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $this->Form->control('unit_price', [
            'type' => 'number',
            'step' => '0.01',
            'label' => ['text' => 'Unit Price ($) *', 'class' => 'form-label'],
            'class' => 'form-control',
            'required' => true,
            'id' => 'unit-price'
        ]) ?>
    </div>
</div>

// Row 3: Amount Paid (KEY FIELD - Editable!)
<div class="row mb-3">
    <div class="col-md-6">
        <?= $this->Form->control('amount_paid', [
            'type' => 'number',
            'step' => '0.01',
            'label' => ['text' => 'Amount Paid (Fresh Invoice) *', 'class' => 'form-label'],
            'class' => 'form-control',
            'required' => true,
            'id' => 'amount-paid',
            'placeholder' => 'Enter amount already paid'
        ]) ?>
        <small class="text-muted">Amount paid via Fresh Invoice (editable)</small>
    </div>
    <div class="col-md-6">
        <?= $this->Form->control('sgc_account_id', [
            'type' => 'select',
            'options' => $sgcAccounts,
            'empty' => '-- Select Bank Account --',
            'label' => ['text' => 'Bank Account *', 'class' => 'form-label'],
            'class' => 'form-select',
            'required' => true
        ]) ?>
    </div>
</div>

// Row 4: Vessel and BL Number
<div class="row mb-3">
    <div class="col-md-6">
        <?= $this->Form->control('vessel_name', [
            'type' => 'text',
            'label' => ['text' => 'Vessel Name *', 'class' => 'form-label'],
            'class' => 'form-control',
            'required' => true
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $this->Form->control('bl_number', [
            'type' => 'text',
            'label' => ['text' => 'BL Number *', 'class' => 'form-label'],
            'class' => 'form-control',
            'required' => true
        ]) ?>
    </div>
</div>

// Row 5: Purpose/Notes
<div class="row mb-3">
    <div class="col-md-12">
        <?= $this->Form->control('notes', [
            'type' => 'textarea',
            'label' => ['text' => 'Purpose', 'class' => 'form-label'],
            'class' => 'form-control',
            'rows' => 3,
            'placeholder' => 'Cocoa export proceeds'
        ]) ?>
    </div>
</div>

// Calculation Display (Read-only info)
<div class="row mb-3">
    <div class="col-md-12">
        <div class="alert alert-info" style="background:#e3f2fd;border-left:4px solid #2196f3">
            <h6 style="margin-bottom:1rem;font-weight:700">
                <i class="fas fa-calculator"></i> Payment Summary
            </h6>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem">
                <div>
                    <strong>Total Value:</strong><br>
                    <span style="font-size:1.2rem;color:#0c5343">$<span id="totalValue">0.00</span></span>
                </div>
                <div>
                    <strong>Less Amount Paid:</strong><br>
                    <span style="font-size:1.2rem;color:#666">$<span id="displayAmountPaid">0.00</span></span>
                </div>
                <div>
                    <strong>Amount Due:</strong><br>
                    <span style="font-size:1.3rem;color:#ff5722;font-weight:700">$<span id="amountDue">0.00</span></span>
                </div>
            </div>
        </div>
    </div>
</div>
```

### JavaScript Calculation (Real-time Updates)

```javascript
<script>
$(document).ready(function() {
    // Auto-calculate on any input change
    function calculateAmounts() {
        const landedQty = parseFloat($('#landed-quantity').val()) || 0;
        const unitPrice = parseFloat($('#unit-price').val()) || 0;
        const amountPaid = parseFloat($('#amount-paid').val()) || 0;
        
        const totalValue = landedQty * unitPrice;
        const amountDue = totalValue - amountPaid;
        
        $('#totalValue').text(totalValue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#displayAmountPaid').text(amountPaid.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#amountDue').text(amountDue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        
        // Color code the amount due
        if (amountDue > 0) {
            $('#amountDue').parent().css('color', '#ff5722'); // Orange - payment due
        } else if (amountDue < 0) {
            $('#amountDue').parent().css('color', '#ef4444'); // Red - overpaid
        } else {
            $('#amountDue').parent().css('color', '#10b981'); // Green - balanced
        }
    }
    
    // Trigger calculation on input changes
    $('#landed-quantity, #unit-price, #amount-paid').on('input', calculateAmounts);
    
    // When Fresh Invoice is selected, auto-fill fields
    $('#fresh-invoice-id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        
        // Get data attributes from the option (set these in controller)
        const freshTotalValue = parseFloat(selectedOption.data('total-value')) || 0;
        const freshQuantity = parseFloat(selectedOption.data('quantity')) || 0;
        const freshUnitPrice = parseFloat(selectedOption.data('unit-price')) || 0;
        const vesselName = selectedOption.data('vessel-name') || '';
        const blNumber = selectedOption.data('bl-number') || '';
        
        // Pre-fill fields (user can edit)
        if (freshUnitPrice > 0) {
            $('#unit-price').val(freshUnitPrice.toFixed(2));
        }
        if (freshTotalValue > 0) {
            $('#amount-paid').val(freshTotalValue.toFixed(2));
        }
        if (!$('#landed-quantity').val() && freshQuantity > 0) {
            $('#landed-quantity').val(freshQuantity.toFixed(3));
        }
        if (vesselName) {
            $('#vessel-name').val(vesselName);
        }
        if (blNumber) {
            $('#bl-number').val(blNumber);
        }
        
        // Trigger calculation
        calculateAmounts();
    });
    
    // Initial calculation on page load (for edit forms)
    calculateAmounts();
});
</script>
```

### Controller - Preparing Fresh Invoice Options

```php
// In FinalInvoicesController::add() or edit()
$freshInvoices = $this->FinalInvoices->FreshInvoices->find('list', [
    'conditions' => ['status IN' => ['approved', 'sent_to_export']],
    'order' => ['invoice_number' => 'DESC']
])
->map(function($text, $key) use ($that) {
    // Get the full fresh invoice entity
    $fresh = $that->FinalInvoices->FreshInvoices->get($key, [
        'contain' => ['Clients', 'Vessels']
    ]);
    
    return [
        'value' => $key,
        'text' => $fresh->invoice_number . ' - ' . $fresh->client->name,
        'data-total-value' => $fresh->total_value,
        'data-quantity' => $fresh->quantity,
        'data-unit-price' => $fresh->unit_price,
        'data-vessel-name' => $fresh->vessel->name ?? $fresh->vessel_name,
        'data-bl-number' => $fresh->bl_number
    ];
})
->toArray();

$this->set(compact('freshInvoices'));
```

### Summary

**Fresh Invoice** = Advance payment (98%) based on contracted quantity
**Final Invoice** = Balance payment based on actual landed quantity

The difference in quantity determines if additional payment is due or if an overpayment occurred.

