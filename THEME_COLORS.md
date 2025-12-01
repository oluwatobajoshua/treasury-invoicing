# Treasury Invoice System - Theme Colors

This document defines the standard color palette for the application. All new pages and components should use these colors to maintain consistency.

## Color Variables

The application uses CSS custom properties (variables) defined in `templates/layout/default.php`:

```css
:root {
    --primary: #0c5343;      /* Dark green - Main brand color */
    --primary-light: #10b981; /* Light green */
    --success: #10b981;       /* Green - Success states */
    --warning: #ff5722;       /* Orange - Warning states */
    --danger: #ef4444;        /* Red - Error/Delete states */
    --info: #3b82f6;          /* Blue - Info states */
}
```

## Usage Guidelines

### Primary Actions
- Use `var(--primary)` for main buttons, headers, and primary UI elements
- Gradient: `linear-gradient(135deg, var(--primary) 0%, #094d3d 100%)`
- Example: Add/Save buttons, page headers

### Status Badges
- **Success/Active**: Green (`var(--success)`)
- **Warning/Sales**: Orange (`var(--warning)`)
- **Danger/Inactive**: Red (`var(--danger)`)
- **Info/Shipment**: Blue (`var(--info)`)

### Focus States
- Border color: `var(--primary)`
- Shadow: `0 0 0 3px rgba(12, 83, 67, 0.1)`

### KPI Cards
Use light backgrounds with darker text:
- Green cards: `background: #d1fae5`, `color: #059669`
- Blue cards: `background: #dbeafe`, `color: #2563eb`
- Red cards: `background: #ffe4e1`, `color: #991b1b`

## Invoice Type Colors

Each invoice type can have its own accent color:
- **Fresh Invoice**: Green (`#0c5343`)
- **Sales Invoice**: Orange (`#ff5722`)
- **Sustainability Invoice**: Green (`#10b981`)
- **Shipment/Final Invoice**: Blue (`#3b82f6`)

## Updated Pages

The following pages have been standardized to use these theme colors:
- ✅ Banks/index.php
- ✅ Banks/add.php
- ✅ Banks/edit.php
- ✅ FreshInvoices/index.php
- ✅ SalesInvoices/index.php
- ✅ SustainabilityInvoices/index.php

## Implementation Notes

1. **Always use CSS variables** where possible: `var(--primary)` instead of hardcoded hex values
2. **For gradients**: Use primary color with darker shade for depth
3. **Hover states**: Increase shadow and translate vertically for lift effect
4. **Consistency**: Review this document before styling new pages
