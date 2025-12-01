# Professional View Layout Guide

## 🎨 Layout Recommendations by Page Type

### 1. Record Detail Pages (view.php)
**✅ Use: Compact Two-Column Layout**

#### Structure
```
┌─────────────────────────────────────────────────────────────┐
│ 🔷 HEADER (Compact)                              [Actions]  │
│ Contract ID #12345 [Active]                    [Edit] [Del] │
└─────────────────────────────────────────────────────────────┘
┌───────────────────────────────────┬─────────────────────────┐
│ 📊 MAIN CONTENT (2fr - 66%)       │ 📌 SIDEBAR (1fr - 33%)  │
├───────────────────────────────────┼─────────────────────────┤
│ ┌───────────────────────────────┐ │ ┌─────────────────────┐ │
│ │ 📈 Utilization Chart          │ │ │ ✓ Status Card       │ │
│ │ [████████░░] 80%              │ │ │   [Active]          │ │
│ └───────────────────────────────┘ │ └─────────────────────┘ │
│                                   │                         │
│ ┌───────────────────────────────┐ │ ┌─────────────────────┐ │
│ │ 📋 Contract Details           │ │ │ 🕒 Record Info      │ │
│ │ ID      | #12345              │ │ │ Created: Nov 1      │ │
│ │ Date    | Nov 1, 2025         │ │ │ Modified: Nov 13    │ │
│ └───────────────────────────────┘ │ └─────────────────────┘ │
│                                   │                         │
│ ┌───────────────────────────────┐ │ ┌─────────────────────┐ │
│ │ 💰 Financial Details          │ │ │ 📝 Notes            │ │
│ │ Qty     | 1000 MT             │ │ │ Important info...   │ │
│ │ Price   | $500/MT             │ │ │                     │ │
│ └───────────────────────────────┘ │ └─────────────────────┘ │
└───────────────────────────────────┴─────────────────────────┘
┌─────────────────────────────────────────────────────────────┐
│ 📑 Related Records Table (Full Width)                       │
│ [Invoice List Table]                                        │
└─────────────────────────────────────────────────────────────┘
```

**Benefits**:
- ✅ 40% less scrolling
- ✅ Status always visible
- ✅ Better information hierarchy
- ✅ Quick access to metadata

**Applied To**:
- Banks/view.php ✅
- Contracts/view.php ✅

---

### 2. Invoice Print Pages (view.php)
**✅ Use: Full-Width Print Layout**

#### Structure
```
┌─────────────────────────────────────────────────────────────┐
│ [Logo]                                    Company Info       │
│                                          info@company.com    │
├─────────────────────────────────────────────────────────────┤
│ Date: November 13, 2025                                     │
├─────────────────────────────────────────────────────────────┤
│ TO:                                                          │
│ Client Name                                                  │
│ Client Address                                               │
├─────────────────────────────────────────────────────────────┤
│                        INVOICE                               │
├─────────────────────────────────────────────────────────────┤
│ Description          │ Qty   │ Price  │ Total               │
│ Product Name         │ 100   │ $50    │ $5,000              │
├─────────────────────────────────────────────────────────────┤
│                                    TOTAL: $5,000             │
├─────────────────────────────────────────────────────────────┤
│ Payment Details:                                             │
│ Bank: XYZ Bank                                               │
│ Account: 1234567890                                          │
└─────────────────────────────────────────────────────────────┘
```

**Benefits**:
- ✅ Print-ready format
- ✅ Professional invoice layout
- ✅ Company branding
- ✅ Clear payment instructions

**Applied To**:
- FreshInvoices/view.php (unchanged) ✅
- FinalInvoices/view.php (unchanged) ✅
- SalesInvoices/view.php (unchanged) ✅
- SustainabilityInvoices/view.php (unchanged) ✅

---

### 3. Form Pages (add.php, edit.php)
**✅ Use: Single-Column Form Layout**

#### Structure
```
┌─────────────────────────────────────────────────────────────┐
│ 📝 Add New Contract                              [Back]     │
└─────────────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────────────┐
│ ℹ️ Info Box: Fill in all required fields                    │
└─────────────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────────────┐
│ 📋 Basic Information                                         │
├─────────────────────────────────────────────────────────────┤
│ Contract ID: [________________]  Date: [__________]         │
│ Client:      [________________]  Product: [________]        │
├─────────────────────────────────────────────────────────────┤
│ 💰 Financial Details                                         │
├─────────────────────────────────────────────────────────────┤
│ Quantity:    [________________]  Unit Price: [_____]        │
├─────────────────────────────────────────────────────────────┤
│                                           [Cancel] [Save]    │
└─────────────────────────────────────────────────────────────┘
```

**Benefits**:
- ✅ Clear form flow
- ✅ Logical field grouping
- ✅ Good for data entry
- ✅ Validation-friendly

**Note**: Already well-designed, no changes needed

---

### 4. List/Index Pages (index.php)
**✅ Use: DataTables with KPI Cards**

#### Structure
```
┌─────────────────────────────────────────────────────────────┐
│ 📊 Contracts                                     [+ New]     │
└─────────────────────────────────────────────────────────────┘
┌─────────┬─────────┬─────────┬─────────┐
│ 📈 Total│ ✓ Active│ ✅ Done │ ❌ Cancel│
│   125   │   85    │   30    │   10     │
└─────────┴─────────┴─────────┴─────────┘
┌─────────────────────────────────────────────────────────────┐
│ 🔍 Search: [________]  [Export ▼] [Columns ▼]              │
├─────────────────────────────────────────────────────────────┤
│ ID          │ Client      │ Date       │ Status  │ Actions  │
│ #12345      │ ABC Corp    │ Nov 1      │ Active  │ [V][E]  │
│ #12346      │ XYZ Ltd     │ Nov 2      │ Done    │ [V][E]  │
├─────────────────────────────────────────────────────────────┤
│ Showing 1-25 of 125    [← 1 2 3 4 5 →]                     │
└─────────────────────────────────────────────────────────────┘
```

**Benefits**:
- ✅ Advanced search/filter
- ✅ Export to Excel/PDF
- ✅ Column visibility toggle
- ✅ Responsive design

**Status**: Banks/index.php complete, 5 others pending

---

## 📐 Spacing Standards

### Before Optimization (Old Layout)
```css
padding: 2rem;           /* Too much space */
margin-bottom: 2rem;     /* Excessive gaps */
font-size: 1rem;         /* Standard size */
section-title: 1.125rem; /* Too large */
gap: 1.5rem;            /* Wide gaps */
```

**Result**: ~2000px page height ❌

### After Optimization (New Layout)
```css
padding: 1.5rem;         /* Compact but readable */
margin-bottom: 1rem;     /* Tighter spacing */
font-size: .875rem;      /* Efficient size */
section-title: .875rem;  /* Compact headers */
gap: 1rem;              /* Closer spacing */
```

**Result**: ~1200px page height ✅ (40% reduction)

---

## 🎨 Professional Color Scheme

### Status Badges
```css
✅ Success (Active/Approved)
   Background: #d1fae5 (light green)
   Text: #065f46 (dark green)

⚠️ Warning (Pending/Expiring)
   Background: #fef3c7 (light yellow)
   Text: #92400e (dark brown)

❌ Danger (Inactive/Rejected)
   Background: #fee2e2 (light red)
   Text: #991b1b (dark red)

ℹ️ Info (Draft/Processing)
   Background: #dbeafe (light blue)
   Text: #1e40af (dark blue)
```

### Action Buttons
```css
🟢 Primary (Save/Submit)
   Background: linear-gradient(135deg, #0c5343, #094d3d)
   Color: white

🟡 Warning (Edit)
   Background: #ff5722 (orange)
   Color: white

🔴 Danger (Delete)
   Background: #ef4444 (red)
   Color: white

⚪ Outline (Back/Cancel)
   Background: white
   Border: #e5e7eb
   Color: #6b7280
```

---

## 📱 Responsive Breakpoints

### Desktop (> 1024px)
- Two-column layout (2fr + 1fr)
- All features visible
- Detail grids: 2 columns

### Tablet (768px - 1024px)
- Single column layout
- Sidebar below main content
- Detail grids: 2 columns

### Mobile (< 768px)
- Single column layout
- Detail grids: 1 column
- Stacked action buttons
- Simplified tables

---

## 🔧 Implementation Checklist

### For Record View Pages

- [ ] Replace header with compact version (1rem padding)
- [ ] Add content-wrapper div (2fr 1fr grid)
- [ ] Move status to sidebar card
- [ ] Move metadata to sidebar card
- [ ] Reduce all font sizes (.875rem values, .7rem labels)
- [ ] Update section titles (.875rem, bold)
- [ ] Change detail grids to repeat(2, 1fr)
- [ ] Reduce all spacing (1rem gaps, 1.5rem padding)
- [ ] Add responsive breakpoint (@media max-width: 1024px)
- [ ] Test on mobile, tablet, desktop
- [ ] Verify all conditional sections display correctly
- [ ] Check badge colors match theme
- [ ] Test action buttons functionality

### CSS Classes to Include
```css
.page-header
.content-wrapper
.content-card
.section-title
.detail-grid
.detail-grid.full
.detail-item
.detail-label
.detail-value
.sidebar-card
.badge (success, warning, danger, primary)
.btn (primary, outline, warning, danger)
```

---

## 💡 Best Practices

### DO ✅
- Use two-column layout for record details
- Keep status/metadata in sidebar
- Use small, efficient fonts (.875rem or smaller)
- Group related fields in sections
- Show conditional sections only if data exists
- Make tables full-width below columns
- Use responsive design for mobile

### DON'T ❌
- Apply to invoice print templates
- Use excessive spacing (> 1.5rem padding)
- Use large fonts on view pages (> 1rem)
- Stack everything vertically
- Hide important information
- Forget mobile breakpoints
- Remove print styling from invoices

---

## 📚 References

**Completed Implementations**:
- `templates/Banks/view.php` - Complete example
- `templates/Contracts/view.php` - Complete example

**Documentation**:
- `COMPACT_VIEW_LAYOUT.md` - Detailed specs
- `THEME_COLORS.md` - Color palette
- `CRUD_DATATABLES_COMPLETE.md` - DataTables guide

**CSS Variables** (from layout/default.php):
```css
--primary: #0c5343;    /* Dark green */
--success: #10b981;    /* Green */
--warning: #ff5722;    /* Orange */
--danger: #ef4444;     /* Red */
--info: #3b82f6;       /* Blue */
```

---

**Last Updated**: November 13, 2025  
**Designer**: Professional UX/UI Guidelines  
**Status**: Production Ready ✅
