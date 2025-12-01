# IMPLEMENTATION CHECKLIST - Final Invoice Amount Paid Feature

## ✅ Completed

1. **Documentation Updated** (`FINAL_INVOICE_REQUIREMENTS.md`):
   - Added `amount_paid` as a required editable field
   - Updated form structure with proper Bootstrap layout
   - Added JavaScript for real-time calculation
   - Provided controller example for pre-filling data

2. **Migration Created** (`20251113000001_AddAmountPaidToFinalInvoices.php`):
   - Adds `amount_paid` column (DECIMAL 18,2) to `final_invoices` table
   - Default value: 0.00
   - Positioned after `unit_price` column

3. **View Template Updated** (`templates/FinalInvoices/view.php`):
   - Now reads `amount_paid` from database instead of calculating from Fresh Invoice
   - Color-codes Amount Due (orange for positive, red for negative)

## 🔲 TODO - Next Steps

### 1. Run Migration
```bash
php bin/cake.php migrations migrate
```

### 2. Update FinalInvoicesTable.php
Add validation for `amount_paid`:
```php
$validator
    ->decimal('amount_paid')
    ->requirePresence('amount_paid', 'create')
    ->notEmptyString('amount_paid')
    ->greaterThanOrEqual('amount_paid', 0, 'Amount paid must be 0 or greater');
```

### 3. Update FinalInvoice Entity
Add `amount_paid` to accessible fields in `src/Model/Entity/FinalInvoice.php`:
```php
protected array $_accessible = [
    'invoice_number' => true,
    'fresh_invoice_id' => true,
    'landed_quantity' => true,
    'unit_price' => true,
    'amount_paid' => true,  // ADD THIS
    // ... rest of fields
];
```

### 4. Create/Update Add Form (`templates/FinalInvoices/add.php`)
Use the structure from `FINAL_INVOICE_REQUIREMENTS.md`:
- Fresh Invoice dropdown with data attributes
- Landed Quantity field
- Unit Price field
- **Amount Paid field** (KEY - editable!)
- Bank Account dropdown
- Vessel Name and BL Number
- Payment Summary display box
- JavaScript for auto-calculation

### 5. Create/Update Edit Form (`templates/FinalInvoices/edit.php`)
Same structure as add.php but with pre-filled values

### 6. Update Controller (`src/Controller/FinalInvoicesController.php`)

#### In `add()` method:
```php
// Prepare Fresh Invoices with data attributes
$freshInvoices = $this->FinalInvoices->FreshInvoices
    ->find('list')
    ->where(['status IN' => ['approved', 'sent_to_export']])
    ->contain(['Clients', 'Vessels'])
    ->toArray();

// Transform for template with data attributes
$freshInvoicesOptions = [];
foreach ($freshInvoices as $id => $text) {
    $fresh = $this->FinalInvoices->FreshInvoices->get($id, [
        'contain' => ['Clients', 'Vessels']
    ]);
    $freshInvoicesOptions[$id] = [
        'text' => $fresh->invoice_number . ' - ' . $fresh->client->name,
        'data-total-value' => $fresh->total_value,
        'data-quantity' => $fresh->quantity,
        'data-unit-price' => $fresh->unit_price,
        'data-vessel-name' => $fresh->vessel->name ?? $fresh->vessel_name,
        'data-bl-number' => $fresh->bl_number
    ];
}
```

#### On save:
```php
if ($this->request->is('post')) {
    $finalInvoice = $this->FinalInvoices->patchEntity($finalInvoice, $this->request->getData());
    
    // Calculate amount due (for reference/validation)
    $totalValue = $finalInvoice->landed_quantity * $finalInvoice->unit_price;
    $amountDue = $totalValue - $finalInvoice->amount_paid;
    
    // Optional: Warn if amount due is negative (overpayment)
    if ($amountDue < 0) {
        $this->Flash->warning('Amount Paid exceeds Total Value. This indicates an overpayment.');
    }
    
    if ($this->FinalInvoices->save($finalInvoice)) {
        $this->Flash->success('Final invoice created successfully.');
        return $this->redirect(['action' => 'view', $finalInvoice->id]);
    }
}
```

### 7. Test Workflow

1. Create Fresh Invoice (98% payment)
2. Create Final Invoice:
   - Select Fresh Invoice
   - Enter Landed Quantity (may differ from contracted)
   - Verify Unit Price auto-fills
   - Verify Amount Paid auto-fills with Fresh total_value
   - **User can edit Amount Paid if needed**
   - See real-time calculation of Amount Due
3. Submit and approve
4. View final invoice - verify Amount Due calculation

## Key Benefits

- **Flexibility**: User can adjust amount paid for partial payments or corrections
- **Transparency**: Clear display of Total Value, Less Amount Paid, Amount Due
- **Automation**: Auto-fills suggested values but allows manual override
- **Real-time**: JavaScript calculates and displays amounts instantly
- **Color-coded**: Visual indicators for positive/negative/zero balance

## Field Placement - Best Practice

The `amount_paid` field is placed in **Row 3** of the form, right after Landed Quantity and Unit Price. This logical flow:

1. Select source (Fresh Invoice) → auto-fills defaults
2. Enter quantities and pricing → calculate total
3. **Enter amount already paid** → calculate balance due
4. Additional details (bank, vessel, BL)
5. See summary with Amount Due

This placement ensures users see the calculation flow naturally.
