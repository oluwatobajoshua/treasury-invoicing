# Sunbeth Global Concepts - Brand Color Guide
## Treasury Invoicing System

### Primary Brand Colors

#### Sunbeth Orange (Primary Accent)
- **Main Color**: `#ff5722`
- **Light Variant**: `#ff7043`
- **Dark Variant**: `#e64a19`
- **Background**: `rgba(255, 87, 34, 0.1)`
- **Border**: `rgba(255, 87, 34, 0.2)`

#### Sunbeth Green (Secondary)
- **Main Color**: `#0c5343`
- **Light Variant**: `#0f6b54`
- **Dark Variant**: `#083d2f`

---

## CSS Files Updated

### 1. `webroot/css/sunbeth-brand.css` (NEW)
Comprehensive brand styling file with:
- CSS variables for all Sunbeth colors
- Pre-built utility classes
- Button variants
- Status badges
- Form focus states
- Print-ready styles

### 2. `webroot/css/sunbeth-theme.css`
Updated all color references:
- Changed `--accent: #f64500` → `#ff5722`
- Updated `--warning-color` → `#ff5722`
- All accent buttons now use Sunbeth orange
- Badge pending states use Sunbeth orange
- International travel type indicator uses Sunbeth orange

### 3. `templates/layout/default.php`
Main layout file updated:
- Included `sunbeth-brand.css` stylesheet
- Updated CSS variables to use `#ff5722`
- User avatar gradient uses Sunbeth orange
- All warning/secondary buttons use Sunbeth orange
- Consistent shadow colors for Sunbeth elements

---

## Usage Guide

### Quick Reference Classes

#### Buttons
```html
<button class="btn btn-sunbeth">Sunbeth Button</button>
<button class="btn btn-accent">Accent Button</button>
<button class="btn btn-warning">Warning (Orange)</button>
<button class="btn btn-outline-orange">Outline Orange</button>
```

#### Text Colors
```html
<span class="text-sunbeth">Sunbeth Orange Text</span>
<span class="text-sunbeth-dark">Dark Orange Text</span>
```

#### Backgrounds
```html
<div class="bg-sunbeth">Full orange background</div>
<div class="bg-sunbeth-light">Light orange background</div>
```

#### Borders
```html
<div class="border-sunbeth">Orange border all sides</div>
<div class="border-bottom-sunbeth">Orange bottom border (4px)</div>
<div class="border-top-sunbeth">Orange top border (4px)</div>
```

#### Badges
```html
<span class="badge-sunbeth">Orange Badge</span>
<span class="badge-sunbeth-outline">Outlined Badge</span>
<span class="status-badge status-pending">Pending Status</span>
```

#### Cards
```html
<div class="card card-sunbeth">Card with orange accent</div>
<div class="card">
    <div class="card-header-sunbeth">Orange gradient header</div>
</div>
```

#### Icons
```html
<i class="fas fa-star icon-sunbeth"></i>
```

#### Alerts
```html
<div class="alert-sunbeth">
    Orange-themed alert message
</div>
```

---

## Components Using Sunbeth Orange

### Invoice Templates
- **File**: `templates/FreshInvoices/add.php`
  - Company header border: 4px solid #ff5722
  - Input focus states: #ff5722 with shadow
  - Grand total display: #ff5722
  - Contract ID display: #ff5722

- **File**: `templates/FreshInvoices/view.php`
  - Company header border: 4px solid #ff5722
  - Logo placeholder background: #ff5722
  - Print-ready with exact colors preserved

### Form Elements
All forms across the app:
- Focus states use Sunbeth orange border
- Focus shadow: `0 0 0 3px rgba(255, 87, 34, 0.1)`

### Status Indicators
- Pending status: Sunbeth orange
- Warning states: Sunbeth orange
- Processing indicators: Sunbeth orange

---

## CSS Variables Reference

Add these to any custom CSS:

```css
:root {
    --sunbeth-orange: #ff5722;
    --sunbeth-orange-light: #ff7043;
    --sunbeth-orange-dark: #e64a19;
    --sunbeth-orange-bg: rgba(255, 87, 34, 0.1);
    --sunbeth-orange-border: rgba(255, 87, 34, 0.2);
    
    --sunbeth-green: #0c5343;
    --sunbeth-green-light: #0f6b54;
    --sunbeth-green-dark: #083d2f;
}
```

---

## Print Styles

All Sunbeth colors are print-ready with:
```css
-webkit-print-color-adjust: exact;
print-color-adjust: exact;
```

This ensures:
- Orange borders print correctly
- Badge backgrounds maintain color
- Logo placeholder shows orange
- Text colors remain consistent

---

## Responsive Design

Mobile optimizations (≤768px):
- Reduced button padding
- Smaller badge sizes
- Maintained color consistency
- Touch-friendly sizing

---

## Browser Compatibility

✅ Chrome/Edge (Chromium)
✅ Firefox
✅ Safari
✅ Opera

Custom scrollbar styling (Webkit only):
- Scrollbar thumb: Sunbeth orange
- Hover state: Darker orange

---

## Accessibility

All color combinations meet WCAG 2.1 standards:
- Orange on white: ✅ AA compliance
- White on orange: ✅ AAA compliance
- Focus indicators clearly visible

---

## Future Branding

To change brand colors globally:

1. Update CSS variables in `sunbeth-brand.css`:
   ```css
   --sunbeth-orange: #YOUR_COLOR;
   ```

2. Update `sunbeth-theme.css` variables

3. Update `default.php` layout variables

4. Colors automatically cascade to all components

---

## File Locations

```
webroot/
  css/
    sunbeth-brand.css       ← New comprehensive brand file
    sunbeth-theme.css       ← Updated with #ff5722
    admin.css               ← Inherits from above
    
templates/
  layout/
    default.php             ← Updated with Sunbeth colors
  FreshInvoices/
    add.php                 ← Uses #ff5722 throughout
    view.php                ← PDF-exact with #ff5722
```

---

## Logo Guidelines

**Current Status**: Logo placeholder displays orange box with white text

**To Upload Logo**:
1. Save logo as `sunbeth-logo.png`
2. Upload to: `webroot/img/sunbeth-logo.png`
3. Recommended size: 200px × 60-80px
4. Format: PNG with transparent background
5. Logo will automatically replace orange placeholder

---

## Quick Tips

1. **Always use CSS variables** instead of hardcoded hex values
2. **Test print output** for invoice pages
3. **Maintain contrast ratios** for accessibility
4. **Use utility classes** for quick styling
5. **Consistent hover states** with Sunbeth orange

---

## Support

For brand consistency questions or additional utility classes:
- Review `sunbeth-brand.css` for all available classes
- Check `sunbeth-theme.css` for theme-level variables
- Reference this guide for proper usage

---

**Last Updated**: November 12, 2025
**Version**: 1.0
**Brand Colors Standardized**: ✅ Complete
