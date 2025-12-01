# CakePHP Form & UI Development Standards

## Last Updated: November 13, 2025

This document contains critical settings and conventions for the Treasury Invoicing System to ensure consistency across all forms and UI components.

---

## 1. THEME COLORS (STRICT - NEVER DEVIATE)

### Primary Colors
```css
--primary: #0c5343;        /* Main green */
--primary-dark: #083d2f;   /* Dark green for gradients */
--secondary: #ff5722;      /* Sunbeth orange */
--success: #10b981;        /* Success green */
--danger: #ef4444;         /* Error/Delete red */
--warning: #ff5722;        /* Same as secondary */
--info: #3b82f6;           /* Info blue */
```

### Gray Scale
```css
--gray-50: #f9fafb;
--gray-100: #f3f4f6;
--gray-200: #e5e7eb;
--gray-300: #d1d5db;
--gray-400: #9ca3af;
--gray-500: #6b7280;
--gray-600: #4b5563;
--gray-700: #374151;
--gray-800: #1f2937;
--gray-900: #111827;
```

### Usage
- **Backgrounds**: Use gray-50 (#f9fafb) for light backgrounds
- **Borders**: Use gray-200 (#e5e7eb) for borders
- **Text**: Use gray-700 (#374151) for body text, gray-900 (#111827) for headings
- **Buttons**: Primary green (#0c5343), Orange (#ff5722), Red (#ef4444)

---

## 2. FONT AWESOME ICON STANDARDS

### ⚠️ CRITICAL: Icon-Action Mapping

```php
// Always use these exact icons for these actions:

// SAVE/CREATE/UPDATE Actions
'<i class="fas fa-check"></i> Save'
'<i class="fas fa-check"></i> Create'
'<i class="fas fa-check"></i> Update'

// CANCEL Action
'<i class="fas fa-times"></i> Cancel'

// DELETE Action
'<i class="fas fa-trash"></i> Delete'

// EDIT Action
'<i class="fas fa-edit"></i> Edit'

// VIEW Action
'<i class="fas fa-eye"></i> View'

// BACK/RETURN Action
'<i class="fas fa-arrow-left"></i> Back'

// ADD/NEW Action
'<i class="fas fa-plus"></i> Add New'
'<i class="fas fa-plus"></i> New'

// PRINT Action
'<i class="fas fa-print"></i> Print'

// DOWNLOAD/EXPORT Actions
'<i class="fas fa-download"></i> Download'
'<i class="fas fa-file-excel"></i> Excel'
'<i class="fas fa-file-pdf"></i> PDF'

// SEARCH Action
'<i class="fas fa-search"></i> Search'

// SUBMIT Action
'<i class="fas fa-paper-plane"></i> Submit'
```

---

## 3. CAKEPHP FORM HELPER CRITICAL SETTINGS

### ⚠️ ESCAPE HTML IN BUTTONS

```php
// ❌ WRONG - Will show HTML as text
<?= $this->Form->button('<i class="fas fa-check"></i> Save', [
    'escape' => false  // THIS IS WRONG!
]) ?>

// ✅ CORRECT - Will render icon properly
<?= $this->Form->button('<i class="fas fa-check"></i> Save', [
    'escapeTitle' => false  // Use escapeTitle for Form->button()
]) ?>

// For Html->link() and Form->postLink() use 'escape' => false
<?= $this->Html->link('<i class="fas fa-eye"></i> View', ['action' => 'view'], [
    'escape' => false  // This is correct for Html->link()
]) ?>

<?= $this->Form->postLink('<i class="fas fa-trash"></i> Delete', ['action' => 'delete'], [
    'escape' => false  // This is correct for Form->postLink()
]) ?>
```

### Summary Table

| Helper Method | HTML Escape Option |
|---------------|-------------------|
| `Form->button()` | `'escapeTitle' => false` |
| `Html->link()` | `'escape' => false` |
| `Form->postLink()` | `'escape' => false` |
| `Form->submit()` | `'escapeTitle' => false` |

---

## 4. BOOTSTRAP 5 FORM STRUCTURE

### Standard Form Layout

```php
<!-- Use Bootstrap's row/col grid system -->
<div class="row mb-3">
    <div class="col-md-6">
        <?= $this->Form->control('field_name', [
            'label' => ['text' => 'Field Label *', 'class' => 'form-label'],
            'class' => 'form-control',  // For text inputs
            'placeholder' => 'Enter value...',
            'required' => true
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $this->Form->control('select_field', [
            'label' => ['text' => 'Select Field *', 'class' => 'form-label'],
            'class' => 'form-select',  // Use form-select for dropdowns!
            'options' => $options,
            'empty' => '-- Select Option --',
            'required' => true
        ]) ?>
    </div>
</div>
```

### ⚠️ CRITICAL: Bootstrap Class Usage

```css
/* Text Inputs */
.form-control      /* For text, number, date, textarea inputs */

/* Select Dropdowns */
.form-select       /* For select/dropdown inputs (NOT form-control) */

/* Labels */
.form-label        /* For all labels */

/* Spacing */
.mb-3              /* Margin bottom between rows */
```

---

## 5. ADMIN CONTROLLER INHERITANCE

### ⚠️ CRITICAL: Admin Controllers Must Extend AppAdminController

```php
// ❌ WRONG - Will not use admin layout
namespace App\Controller\Admin;
use App\Controller\AppController;

class MyController extends AppController  // WRONG!
{
}

// ✅ CORRECT - Will use admin layout
namespace App\Controller\Admin;

class MyController extends AppAdminController  // CORRECT!
{
}
```

---

## 6. STANDARD PAGE HEADER STRUCTURE

```php
<!-- Page Header -->
<div class="page-header-sleek">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h1>
            <i class="fas fa-[icon-name]"></i>
            Page Title
        </h1>
        <div style="display: flex; gap: 0.75rem;">
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Back',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-plus"></i> Add New',
                ['action' => 'add'],
                ['class' => 'btn btn-primary', 'escape' => false]
            ) ?>
        </div>
    </div>
</div>
```

---

## 7. STANDARD CSS FOR FORMS

```css
/* Page Header */
.page-header-sleek {
    background: linear-gradient(135deg, #0c5343 0%, #083d2f 100%);
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    color: white;
    box-shadow: 0 2px 8px rgba(12,83,67,0.15);
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

/* Form Focus States */
.form-control:focus,
.form-select:focus {
    border-color: #0c5343;
    box-shadow: 0 0 0 0.25rem rgba(12,83,67,0.25);
}

/* Form Labels */
.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}
```

---

## 8. DATATABLES STANDARD CONFIGURATION

```javascript
$('#tableId').DataTable({
    responsive: true,
    pageLength: 25,
    order: [[0, 'desc']],
    dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rtip',
    buttons: [
        {
            extend: 'excel',
            text: '<i class="fas fa-file-excel"></i> Excel',
            className: 'dt-button',
            exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
        },
        {
            extend: 'pdf',
            text: '<i class="fas fa-file-pdf"></i> PDF',
            className: 'dt-button',
            exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
        },
        {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Print',
            className: 'dt-button',
            exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
        },
        {
            extend: 'colvis',
            text: '<i class="fas fa-columns"></i> Columns',
            className: 'dt-button'
        }
    ],
    language: {
        search: "_INPUT_",
        searchPlaceholder: "🔍 Search...",
        lengthMenu: "Show _MENU_ records",
        info: "Showing _START_ to _END_ of _TOTAL_ records"
    }
});
```

---

## 9. STATUS BADGE STANDARDS

```css
/* Status Badge Classes */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.active { background: #d1fae5; color: #065f46; }
.status-badge.inactive { background: #fee2e2; color: #991b1b; }
.status-badge.pending { background: #fef3c7; color: #92400e; }
.status-badge.completed { background: #dbeafe; color: #1e40af; }
.status-badge.approved { background: #d1fae5; color: #065f46; }
.status-badge.rejected { background: #fee2e2; color: #991b1b; }
.status-badge.draft { background: #f3f4f6; color: #374151; }
```

---

## 10. ACTION BUTTON STANDARDS

```css
/* Action Buttons - Use these exact sizes */
.btn-action-sleek {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.875rem;
    text-decoration: none;
    color: white;
}

.btn-view-sleek {
    background: #0c5343;
}

.btn-view-sleek:hover {
    background: #083d2f;
    transform: scale(1.05);
    color: white;
}

.btn-edit-sleek {
    background: #ff5722;
}

.btn-edit-sleek:hover {
    background: #e64a19;
    transform: scale(1.05);
    color: white;
}

.btn-delete-sleek {
    background: #ef4444;
}

.btn-delete-sleek:hover {
    background: #dc2626;
    transform: scale(1.05);
    color: white;
}
```

---

## 11. SWEETALERT CONFIRMATION PATTERN

```javascript
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Delete Record?',
        html: `Are you sure you want to delete <strong>${name}</strong>?<br><small class="text-muted">This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete',
        cancelButtonText: '<i class="fas fa-times"></i> Cancel',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form
        }
    });
}
```

---

## 12. BOOTSTRAP MODALS - CRITICAL REQUIREMENTS

### ⚠️ MUST Include Bootstrap CSS and JS

When using Bootstrap modals, you **MUST** include Bootstrap in your template:

```php
<!-- At the top of your template (in <style> section or before) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Add this CSS to ensure modal is hidden by default -->
<style>
.modal {
    display: none;
}

.modal.show {
    display: block;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: #000;
}

.modal-backdrop.show {
    opacity: 0.5;
}
</style>

<!-- At the end of your template (before closing tags) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

### Standard Modal Structure

```php
<!-- Modal -->
<div class="modal fade" id="yourModalId" tabindex="-1" aria-labelledby="yourModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg, #0c5343 0%, #083d2f 100%);color:#fff">
                <h5 class="modal-title" id="yourModalLabel">
                    <i class="fas fa-edit"></i> Modal Title
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= $this->Form->create($entity, ['url' => ['action' => 'yourAction'], 'id' => 'yourFormId']) ?>
            <div class="modal-body">
                <!-- Form fields here -->
            </div>
            <div class="modal-footer" style="background:#f9fafb">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <?= $this->Form->button('<i class="fas fa-check"></i> Save', [
                    'class' => 'btn',
                    'style' => 'background:#0c5343;color:#fff',
                    'escapeTitle' => false
                ]) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
```

### Trigger Button

```php
<button type="button" class="btn btn-sm" 
        style="background:#ff5722;color:#fff"
        data-bs-toggle="modal" data-bs-target="#yourModalId">
    <i class="fas fa-edit"></i> Edit
</button>
```

---

## 13. QUICK REFERENCE CHECKLIST

Before creating any new admin page, verify:

- [ ] Controller extends `AppAdminController` (not `AppController`)
- [ ] Using theme colors: #0c5343 (green), #ff5722 (orange), #ef4444 (red)
- [ ] Icons follow standard mapping (check → save, times → cancel, trash → delete)
- [ ] Form buttons use `escapeTitle => false`
- [ ] Html links use `escape => false`
- [ ] Using `.form-control` for inputs, `.form-select` for dropdowns
- [ ] Using Bootstrap's `.row` and `.col-md-*` for layout
- [ ] Labels have `.form-label` class
- [ ] Form focus uses primary green color
- [ ] Page header uses `.page-header-sleek` with gradient
- [ ] Status badges follow color conventions
- [ ] Action buttons are 30px × 30px
- [ ] DataTables configured with export buttons
- [ ] SweetAlert confirmations for delete actions
- [ ] **If using modals: Bootstrap CSS and JS are included**
- [ ] **Modal has proper `display: none` CSS rule**

---

## 13. DIRECTORY STRUCTURE FOR ADMIN PAGES

```
templates/Admin/
├── ControllerName/
│   ├── index.php      (List with DataTables)
│   ├── view.php       (Detail view)
│   ├── add.php        (Create form)
│   └── edit.php       (Update form)
```

---

## NOTES

- Always test form submissions after creating forms
- Check that icons render properly (not as HTML text)
- Verify color consistency across all pages
- Ensure responsive design works on mobile
- Test all CRUD operations before marking complete

---

**Generated by**: Treasury Invoicing System Development Team  
**Version**: 1.0  
**Last Review**: November 13, 2025
