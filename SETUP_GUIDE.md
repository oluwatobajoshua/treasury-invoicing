# Treasury Invoicing System - Setup Guide

## Prerequisites

- PHP 8.1 or higher
- MySQL 5.7 or higher  
- Composer
- XAMPP (or similar PHP development environment)

## Installation Steps

### 1. Database Setup

Create a new MySQL database named `treasury_invoicing`:

```sql
CREATE DATABASE treasury_invoicing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Configure Database Connection

Copy `config/app_local.example.php` to `config/app_local.php` and update the database credentials:

```php
'Datasources' => [
    'default' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'treasury_invoicing',
        'encoding' => 'utf8mb4',
        'timezone' => 'UTC',
    ],
],
```

### 3. Install Dependencies

```bash
composer install
```

### 4. Run Migrations

This will create all the necessary database tables:

```bash
bin\cake migrations migrate
```

Expected output:
```
using migration paths
 - C:\xampp\htdocs\treasury_inv\config\Migrations
using seed paths
 - C:\xampp\htdocs\treasury_inv\config\Seeds
using environment default
using adapter mysql
using database treasury_invoicing
== 20251112000001 CreateClients: migrating
== 20251112000001 CreateClients: migrated (0.0123s)
== 20251112000002 CreateProducts: migrating
== 20251112000002 CreateProducts: migrated (0.0089s)
...
All Done!
```

### 5. Seed Initial Data

Load sample data for clients, products, vessels, SGC accounts, and contracts:

```bash
bin\cake migrations seed --seed InitialDataSeed
```

This will populate:
- 4 clients (SFI AGRI COMMODITIES LTD, CARGILL, etc.)
- 2 products (COCOA at different prices)
- 3 vessels (GREAT TEMA, GRANDE BENIN, GREAT COTONOU)
- 4 SGC accounts (ACCESS UK, FIDELITY NXP, STERLING)
- 2 sample contracts

### 6. Set File Permissions (Linux/Mac only)

```bash
chmod -R 777 tmp logs
```

For Windows, these directories should already have write permissions.

### 7. Start the Application

#### Option A: Using CakePHP Built-in Server
```bash
bin\cake server
```
Access at: `http://localhost:8765`

#### Option B: Using XAMPP
Place the project in `c:\xampp\htdocs\treasury_inv`
Access at: `http://localhost/treasury_inv`

## Database Tables Created

The migration will create the following tables:

1. **clients** - Client information
   - id, name, address, email, created, modified

2. **products** - Product catalog
   - id, name, category, unit_price, created, modified

3. **vessels** - Vessel registry
   - id, name, flag_country, dwt, created, modified

4. **sgc_accounts** - SGC FCY accounts
   - id, account_id, account_name, currency, created, modified

5. **contracts** - Contract details
   - id, contract_id, client_id, product_id, quantity, unit_price, created, modified

6. **fresh_invoices** - Fresh invoice records
   - id, invoice_number, client_id, product_id, contract_id, vessel_id, vessel_name
   - bl_number, quantity, unit_price, payment_percentage, total_value, sgc_account_id
   - bulk_or_bag, notes, status, treasurer_approval_status, treasurer_approval_date
   - treasurer_comments, sent_to_export_date, invoice_date, created, modified

7. **final_invoices** - Final invoice records (FVP prefix)
   - id, invoice_number, fresh_invoice_id, original_invoice_number, landed_quantity
   - quantity_variance, vessel_name, bl_number, unit_price, payment_percentage
   - total_value, sgc_account_id, notes, status, treasurer_approval_status
   - treasurer_approval_date, treasurer_comments, sent_to_sales_date, invoice_date
   - created, modified

## First Steps After Installation

### 1. Access the Application
Navigate to `http://localhost/treasury_inv` (or `http://localhost:8765`)

### 2. Create a Fresh Invoice
1. Go to **Fresh Invoices > New Fresh Invoice**
2. Select a client, product, and contract
3. Enter vessel details and BL number
4. Enter quantity and adjust payment percentage
5. Save the invoice

### 3. Submit for Approval
1. View the created invoice
2. Click **Submit for Approval**
3. As treasurer, approve or reject

### 4. Create a Final Invoice
1. Go to **Final Invoices > New Final Invoice**
2. Select the approved fresh invoice
3. Enter the landed quantity from CWT report
4. System auto-calculates variance
5. Save and submit for approval

## Troubleshooting

### Migration Errors

**Error**: "SQLSTATE[42000]: Syntax error or access violation"
**Solution**: Ensure your MySQL user has CREATE and ALTER privileges

```sql
GRANT ALL PRIVILEGES ON treasury_invoicing.* TO 'root'@'localhost';
FLUSH PRIVILEGES;
```

### Missing Composer Dependencies

**Error**: "Class not found" errors
**Solution**: Run composer install again

```bash
composer install --no-dev --optimize-autoloader
```

### File Permission Errors

**Error**: "Unable to write to tmp/cache directory"
**Solution**: 
- **Windows**: Check that IIS_IUSRS or IUSR has write permissions
- **Linux/Mac**: Run `chmod -R 777 tmp logs`

### Database Connection Errors

**Error**: "SQLSTATE[HY000] [1049] Unknown database"
**Solution**: 
1. Verify database exists: `SHOW DATABASES;`
2. Check database name in `config/app_local.php`
3. Ensure MySQL service is running

## Next Steps

- Review the main [README.md](README.md) for usage instructions
- Check out the workflow documentation
- Customize invoice templates
- Set up user authentication (if needed)
- Configure email notifications for approvals

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review CakePHP documentation: https://book.cakephp.org/4/en/
3. Contact the development team

## Maintenance Commands

### Clear Cache
```bash
bin\cake cache clear_all
```

### Check Database Status
```bash
bin\cake migrations status
```

### Rollback Last Migration
```bash
bin\cake migrations rollback
```

### Reset Database (Development Only!)
```bash
bin\cake migrations rollback -t 0
bin\cake migrations migrate
bin\cake migrations seed --seed InitialDataSeed
```

---

**Note**: This is a development guide. For production deployment, please consult your system administrator for proper security configuration, backup procedures, and monitoring setup.
