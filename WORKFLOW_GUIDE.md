# Invoice Workflow Guide - Quick Reference

## Fresh Invoice Workflow

### 1. Create Fresh Invoice

**User: Treasury Staff**

**Steps:**
1. Navigate to **Fresh Invoices > New Fresh Invoice**
2. Fill in form:
   - **Client Name** ← Dropdown (SFI/CARGILL, Cargill, etc.)
   - **Product Name** ← Dropdown (COCOA, etc.)
   - **Contract ID** ← Dropdown (auto-fills price & quantity)
   - **Vessel Name** ← Text input (e.g., "Vessel: GREAT TEMA - GTT0525")
   - **BL Number** ← Text input (e.g., "LOS37115")
   - **Quantity (MT)** ← Number (e.g., 262.430)
   - **Unit Price** ← Auto-filled from contract
   - **Payment %** ← Number (98 or 99) - Default: 98%
   - **SGC Account** ← Dropdown (1100533 - FIDELITY NXP USD)
   - **Bulk/Bag** ← Text (e.g., "16 BULK")
   - **Notes** ← Optional text

3. **Auto-Generated:**
   - Invoice Number: 0001, 0002, 0003...
   - Invoice Date: Today's date
   - Total Value: Qty × Price × (Payment% ÷ 100)

4. Click **Save Invoice**
5. Status: **Draft**

### 2. Submit for Approval

**User: Treasury Staff**

**Steps:**
1. View invoice details
2. Click **Submit for Approval**
3. Status changes: **Draft** → **Pending Treasurer Approval**
4. Invoice locked (no editing)

### 3. Treasurer Review

**User: Treasurer**

**Option A: Approve**
1. View invoice
2. Add optional comments
3. Click **Approve**
4. Status: **Approved**
5. Timestamp recorded

**Option B: Reject**
1. View invoice
2. Add rejection comments (required)
3. Click **Reject**
4. Status: **Rejected**
5. Invoice locked

### 4. Send to Export Team

**User: Treasury Staff** (only if approved)

**Steps:**
1. View approved invoice
2. Click **Send to Export Team**
3. Status: **Sent to Export**
4. Timestamp recorded
5. PDF generated (future feature)

---

## Final Invoice Workflow

### 1. Create Final Invoice

**User: Treasury Staff**

**Pre-requisite:** Fresh invoice must be **Approved**

**Steps:**
1. Navigate to **Final Invoices > New Final Invoice**
2. Fill in form:
   - **Reference Fresh Invoice** ← Dropdown (approved invoices only)
   - System auto-populates:
     - Original Invoice Number (e.g., "0155")
     - Vessel Name
     - BL Number
     - Unit Price
     - Payment %
     - SGC Account

3. **Manual Input:**
   - **Landed Quantity (MT)** ← From CWT Report (e.g., 260.748)
   - **Notes** ← CWT variance notes

4. **Auto-Generated:**
   - Invoice Number: **FVP** + original number (e.g., "FVP0155")
   - Invoice Date: Today's date
   - Quantity Variance: Original Qty - Landed Qty
   - Total Value: Landed Qty × Price × (Payment% ÷ 100)

5. Click **Save Final Invoice**
6. Status: **Draft**

### 2. Submit for Approval

**User: Treasury Staff**

**Steps:**
1. View final invoice details
2. Click **Submit for Approval**
3. Status changes: **Draft** → **Pending Treasurer Approval**
4. Invoice locked (no editing)

### 3. Treasurer Review

**User: Treasurer**

**Option A: Approve**
1. View final invoice
2. Review quantity variance
3. Add optional comments
4. Click **Approve**
5. Status: **Approved**
6. Timestamp recorded

**Option B: Reject**
1. View final invoice
2. Add rejection comments (required)
3. Click **Reject**
4. Status: **Rejected**
5. Invoice locked

### 4. Send to Sales Team

**User: Treasury Staff** (only if approved)

**Steps:**
1. View approved final invoice
2. Click **Send to Sales Team**
3. Status: **Sent to Sales**
4. Timestamp recorded
5. PDF generated (future feature)

---

## Status Reference

### Fresh Invoice Statuses

| Status | Editable | Deletable | Next Action |
|--------|----------|-----------|-------------|
| **Draft** | ✅ Yes | ✅ Yes | Submit for Approval |
| **Pending Treasurer Approval** | ❌ No | ❌ No | Approve/Reject |
| **Approved** | ❌ No | ❌ No | Send to Export |
| **Rejected** | ❌ No | ❌ No | Create new invoice |
| **Sent to Export** | ❌ No | ❌ No | Complete |

### Final Invoice Statuses

| Status | Editable | Deletable | Next Action |
|--------|----------|-----------|-------------|
| **Draft** | ✅ Yes | ✅ Yes | Submit for Approval |
| **Pending Treasurer Approval** | ❌ No | ❌ No | Approve/Reject |
| **Approved** | ❌ No | ❌ No | Send to Sales |
| **Rejected** | ❌ No | ❌ No | Create new invoice |
| **Sent to Sales** | ❌ No | ❌ No | Complete |

---

## Sample Data Flow

### Example: Fresh Invoice Creation

```
Input:
- Client: SFI/CARGILL
- Product: Cocoa
- Contract: 2025-SI 1B/QP 136484
- Vessel: Vessel: GREAT TEMA - GTT0525
- BL: LOS37115
- Quantity: 262.430 MT
- Unit Price: $6,292.35 (from contract)
- Payment %: 98%
- SGC Account: 1100533 - FIDELITY NXP (USD)
- Bulk/Bag: 16 BULK

Auto-Generated:
- Invoice #: 0155
- Invoice Date: 2025-11-12
- Total Value: 262.430 × 6,292.35 × 0.98 = $1,618,247.35

Status Flow:
Draft → Submit → Pending → Approve → Send to Export ✓
```

### Example: Final Invoice Creation

```
Input:
- Reference Invoice: 0155 (Fresh Invoice above)
- Landed Quantity: 260.748 MT (from CWT report)
- Notes: "1.682 MT variance - CWT report attached"

Auto-Generated:
- Invoice #: FVP0155
- Invoice Date: 2025-11-12
- Original Qty: 262.430 MT
- Landed Qty: 260.748 MT
- Variance: 1.682 MT (less than expected)
- Total Value: 260.748 × 6,292.35 × 0.98 = $1,607,565.89

Status Flow:
Draft → Submit → Pending → Approve → Send to Sales ✓
```

---

## User Roles & Permissions

### Treasury Staff
- ✅ Create invoices
- ✅ Edit draft invoices
- ✅ Delete draft invoices
- ✅ Submit for approval
- ✅ Send to Export/Sales (after approval)
- ❌ Approve/Reject invoices

### Treasurer
- ✅ View all invoices
- ✅ Approve invoices
- ✅ Reject invoices with comments
- ❌ Create invoices
- ❌ Edit invoices
- ❌ Send to Export/Sales

---

## Calculation Formulas

### Fresh Invoice Total
```
Total Value = Quantity × Unit Price × (Payment Percentage ÷ 100)

Example:
262.430 × $6,292.35 × (98 ÷ 100) = $1,618,247.35
```

### Final Invoice Calculations

**Total Value:**
```
Total Value = Landed Quantity × Unit Price × (Payment Percentage ÷ 100)

Example:
260.748 × $6,292.35 × (98 ÷ 100) = $1,607,565.89
```

**Quantity Variance:**
```
Variance = Original Quantity - Landed Quantity

Example:
262.430 - 260.748 = 1.682 MT

Positive variance = Less cargo than expected (loss)
Negative variance = More cargo than expected (gain)
```

---

## Common Scenarios

### Scenario 1: Reject and Recreate

**Problem:** Invoice contains errors

**Solution:**
1. Treasurer rejects with comments
2. Treasury staff creates new invoice with corrections
3. New invoice number assigned
4. Submit new invoice for approval

### Scenario 2: CWT Shows Large Variance

**Problem:** Landed quantity differs significantly from BL

**Solution:**
1. Document variance in Final Invoice notes
2. Attach CWT report reference
3. Treasurer reviews variance explanation
4. If acceptable, approve final invoice

### Scenario 3: Multiple BLs per Contract

**Problem:** One contract, multiple shipments

**Solution:**
1. Create separate fresh invoice for each BL
2. Each gets unique invoice number (0155, 0156, etc.)
3. All reference same contract ID
4. Create final invoices individually as cargo lands

---

## Quick Access URLs

After installation at `http://localhost/treasury_inv`:

- **Fresh Invoices List**: `/fresh-invoices`
- **New Fresh Invoice**: `/fresh-invoices/add`
- **Final Invoices List**: `/final-invoices`
- **New Final Invoice**: `/final-invoices/add`
- **Dashboard**: `/dashboard` (redirects to fresh invoices)

---

## Tips & Best Practices

1. **Always check contract details** before creating invoice
2. **Use consistent vessel names** as they appear on BL
3. **Document CWT variances** clearly in final invoice notes
4. **Double-check quantities** - they cannot be edited after submission
5. **Review SGC account currency** matches contract terms
6. **Keep BL numbers accurate** for tracking and audit
7. **Submit for approval promptly** to avoid backlog

---

**Need Help?**
- Full documentation: `SETUP_GUIDE.md`
- System overview: `CONVERSION_SUMMARY.md`
- Database setup: `DATABASE_SETUP.md`
