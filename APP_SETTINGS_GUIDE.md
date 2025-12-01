# Application Settings Guide

## Overview
The Treasury Invoicing System uses a centralized settings system to manage application-wide configurations. These settings are stored in `config/app_settings.php` and can be modified through the Admin interface.

## Managing Settings

### Via Admin Interface
Navigate to **Admin → App Settings** to modify settings through a user-friendly interface:
- All changes are saved to `config/app_settings.php`
- Changes take effect immediately
- All modifications are logged in the audit trail

### Via Code
Access settings anywhere in your application:

```php
use Cake\Core\Configure;

// Get a specific setting
$appName = Configure::read('AppSettings.app_name');
$currency = Configure::read('AppSettings.currency');

// With default value
$timeout = Configure::read('AppSettings.session_timeout', 30);
```

## Using the AppSettings Helper

The `AppSettingsHelper` is automatically loaded in all views and provides convenient methods:

### In Templates
```php
// Display app name
<?= $this->AppSettings->appName() ?>

// Display company information
<?= $this->AppSettings->companyName() ?>
<?= $this->AppSettings->companyAddress() ?>
<?= $this->AppSettings->companyPhone() ?>
<?= $this->AppSettings->companyEmail() ?>

// Format money with currency symbol
<?= $this->AppSettings->formatMoney(1500.50) ?> // Output: $1,500.50

// Format dates according to settings
<?= $this->AppSettings->formatDate($invoice->created) ?>

// Get currency information
<?= $this->AppSettings->currency() ?> // USD
<?= $this->AppSettings->currencySymbol() ?> // $

// Check feature flags
<?php if ($this->AppSettings->emailNotificationsEnabled()): ?>
    <!-- Show email notification options -->
<?php endif; ?>

<?php if ($this->AppSettings->auditLogEnabled()): ?>
    <!-- Show audit trail -->
<?php endif; ?>
```

## Available Settings

### Application Settings
| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| `app_name` | string | "Treasury Invoicing System" | Application name shown in header and page titles |
| `currency` | string | "USD" | ISO currency code |
| `currency_symbol` | string | "$" | Symbol used for currency display |
| `date_format` | string | "Y-m-d" | PHP date format string |
| `timezone` | string | "UTC" | Application timezone |
| `items_per_page` | int | 25 | Default pagination size for DataTables |
| `session_timeout` | int | 30 | Session timeout in minutes |

### Company Information
| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| `company_name` | string | "Your Company Name" | Legal company name |
| `company_address` | string | "" | Physical address |
| `company_phone` | string | "" | Contact phone number |
| `company_email` | string | "" | Contact email address |
| `company_website` | string | "" | Company website URL |
| `tax_id` | string | "" | Tax ID / Registration number |

### Feature Flags
| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| `enable_email_notifications` | bool | true | Enable/disable email notifications |
| `enable_audit_log` | bool | true | Enable/disable audit logging |

## Helper Methods

### AppSettingsHelper Methods

```php
// Basic getters
appName(): string
companyName(): string
companyAddress(): string
companyPhone(): string
companyEmail(): string
companyWebsite(): string
taxId(): string
currency(): string
currencySymbol(): string
dateFormat(): string
timezone(): string
itemsPerPage(): int
sessionTimeout(): int

// Feature flags
emailNotificationsEnabled(): bool
auditLogEnabled(): bool

// Formatting helpers
formatMoney(float $amount, int $decimals = 2): string
formatDate($date): string

// Generic getter
get(string $key, $default = null): mixed
```

## Examples

### Display Company Header on Invoice
```php
<div class="invoice-header">
    <h1><?= h($this->AppSettings->companyName()) ?></h1>
    <address>
        <?= nl2br(h($this->AppSettings->companyAddress())) ?><br>
        Phone: <?= h($this->AppSettings->companyPhone()) ?><br>
        Email: <?= h($this->AppSettings->companyEmail()) ?><br>
        Tax ID: <?= h($this->AppSettings->taxId()) ?>
    </address>
</div>
```

### Format Invoice Totals
```php
<tr>
    <th>Total Amount:</th>
    <td><?= $this->AppSettings->formatMoney($invoice->total_value) ?></td>
</tr>
```

### Display Date in User's Format
```php
<p>Invoice Date: <?= $this->AppSettings->formatDate($invoice->invoice_date) ?></p>
```

### Conditional Email Features
```php
<?php if ($this->AppSettings->emailNotificationsEnabled()): ?>
    <button type="button" class="btn btn-primary" onclick="sendEmail()">
        <i class="fas fa-envelope"></i> Send Email
    </button>
<?php endif; ?>
```

## Integration Points

### Where Settings Are Used

1. **Page Titles** - `templates/layout/default.php`
   - App name appears in browser title bar
   - Company name shown in footer

2. **Currency Display** - All invoice views
   - Use `formatMoney()` helper method
   - Consistent currency symbol across app

3. **Date Formatting** - All date displays
   - Use `formatDate()` helper method
   - Respects user's preferred format

4. **DataTables** - Index pages
   - Page length from `items_per_page` setting

5. **Session Management** - Authentication
   - Timeout from `session_timeout` setting

6. **Audit Logging** - All CUD operations
   - Controlled by `enable_audit_log` flag

7. **Email Notifications** - Future feature
   - Controlled by `enable_email_notifications` flag

## Best Practices

1. **Always use helpers in views**
   ```php
   // Good
   <?= $this->AppSettings->formatMoney($amount) ?>
   
   // Bad
   <?= '$' . number_format($amount, 2) ?>
   ```

2. **Use Configure in controllers/models**
   ```php
   // In controller
   $currency = Configure::read('AppSettings.currency', 'USD');
   ```

3. **Provide default values**
   ```php
   // Always provide sensible defaults
   $timeout = Configure::read('AppSettings.session_timeout', 30);
   ```

4. **Escape output**
   ```php
   // Always escape user-configurable content
   <?= h($this->AppSettings->companyName()) ?>
   ```

5. **Check feature flags before using features**
   ```php
   if (Configure::read('AppSettings.enable_audit_log', true)) {
       // Log the action
   }
   ```

## Troubleshooting

### Settings Not Appearing
- Check if `config/app_settings.php` exists
- Verify file permissions (must be writable)
- Check bootstrap.php loads the settings file

### Changes Not Taking Effect
- Clear cache: `bin/cake cache clear_all`
- Check browser cache (hard refresh)
- Verify settings saved to file

### Helper Not Available
- Ensure `AppSettingsHelper` is loaded in `AppView::initialize()`
- Check helper file exists at `src/View/Helper/AppSettingsHelper.php`

## File Locations

```
config/
  └── app_settings.php          # Settings storage (auto-generated)
  
src/
  ├── Controller/
  │   └── Admin/
  │       └── AppSettingsController.php   # Settings management
  └── View/
      └── Helper/
          └── AppSettingsHelper.php        # Helper methods

templates/
  └── Admin/
      └── AppSettings/
          └── index.php                    # Settings UI
```

## Security Notes

- Settings file is writable by web server
- All changes logged in audit trail
- Input sanitized before saving
- Output escaped when displayed
- Only admin users can modify settings
