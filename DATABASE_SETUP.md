# Database Configuration - Treasury Invoicing

## Quick Setup

### Step 1: Create Database

Open your MySQL client (phpMyAdmin, MySQL Workbench, or command line) and run:

```sql
CREATE DATABASE treasury_invoicing 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
```

### Step 2: Configure Connection

Create or update `config/app_local.php` with your database credentials:

```php
<?php
return [
    'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => 'localhost',
            'username' => 'root',
            'password' => '',  // Update with your MySQL password
            'database' => 'treasury_invoicing',
            'encoding' => 'utf8mb4',
            'timezone' => 'UTC',
            'flags' => [],
            'cacheMetadata' => true,
            'log' => false,
            'quoteIdentifiers' => false,
            'url' => env('DATABASE_URL', null),
        ],
    ],
];
```

### Step 3: Run Migrations

```bash
# Navigate to project directory
cd c:\xampp\htdocs\treasury_inv

# Run all migrations
bin\cake migrations migrate
```

Expected output:
```
using migration paths
 - C:\xampp\htdocs\treasury_inv\config\Migrations
using environment default
using adapter mysql
using database treasury_invoicing

== 20251112000001 CreateClients: migrating
== 20251112000001 CreateClients: migrated (0.0234s)

== 20251112000002 CreateProducts: migrating
== 20251112000002 CreateProducts: migrated (0.0198s)

== 20251112000003 CreateVessels: migrating
== 20251112000003 CreateVessels: migrated (0.0156s)

== 20251112000004 CreateSgcAccounts: migrating
== 20251112000004 CreateSgcAccounts: migrated (0.0289s)

== 20251112000005 CreateContracts: migrating
== 20251112000005 CreateContracts: migrated (0.0312s)

== 20251112000006 CreateFreshInvoices: migrating
== 20251112000006 CreateFreshInvoices: migrated (0.0456s)

== 20251112000007 CreateFinalInvoices: migrating
== 20251112000007 CreateFinalInvoices: migrated (0.0389s)

All Done. Took 0.1945s
```

### Step 4: Load Sample Data

```bash
bin\cake migrations seed --seed InitialDataSeed
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

== InitialDataSeed: seeding
== InitialDataSeed: seeded 0.0234s

All Done. Took 0.0256s
```

## Verify Installation

### Check Tables Created

```sql
USE treasury_invoicing;
SHOW TABLES;
```

You should see:
```
+----------------------------+
| Tables_in_treasury_invoicing|
+----------------------------+
| clients                    |
| contracts                  |
| final_invoices             |
| fresh_invoices             |
| phinxlog                   |
| products                   |
| sgc_accounts               |
| vessels                    |
+----------------------------+
```

### Check Sample Data

```sql
-- Check clients (should have 4 records)
SELECT * FROM clients;

-- Check products (should have 2 records)
SELECT * FROM products;

-- Check vessels (should have 3 records)
SELECT * FROM vessels;

-- Check SGC accounts (should have 4 records)
SELECT * FROM sgc_accounts;

-- Check contracts (should have 2 records)
SELECT * FROM contracts;
```

## Troubleshooting

### Error: "Access denied for user"

**Problem**: Wrong MySQL credentials

**Solution**: Update `config/app_local.php` with correct username/password

```php
'username' => 'your_mysql_username',
'password' => 'your_mysql_password',
```

### Error: "Unknown database 'treasury_invoicing'"

**Problem**: Database not created

**Solution**: 
```sql
CREATE DATABASE treasury_invoicing;
```

### Error: "SQLSTATE[HY000] [2002] No connection could be made"

**Problem**: MySQL server not running

**Solution**:
- Start MySQL via XAMPP Control Panel
- Or command line: `net start mysql`

### Error: "Migration failed"

**Problem**: Migrations already run or table conflicts

**Solution**: Check migration status
```bash
bin\cake migrations status
```

Reset if needed (WARNING: Deletes all data):
```bash
bin\cake migrations rollback -t 0
bin\cake migrations migrate
```

## Database Schema Overview

### Relationships

```
clients (1) ─┬─→ (n) contracts (n) ←─── (1) products
             │
             └─→ (n) fresh_invoices (n) ←─── (1) vessels
                      ↓                    ←─── (1) sgc_accounts
                      │                    ←─── (1) contracts
                     (1)
                      │
                     (n) final_invoices (n) ←─── (1) sgc_accounts
```

### Table Sizes (After Seeding)

| Table | Records | Description |
|-------|---------|-------------|
| clients | 4 | SFI, CARGILL clients |
| products | 2 | COCOA products |
| vessels | 3 | Shipping vessels |
| sgc_accounts | 4 | Bank accounts |
| contracts | 2 | Sample contracts |
| fresh_invoices | 0 | Will be created by users |
| final_invoices | 0 | Will be created by users |

## Connection String Format

For environment variables or external configs:

```bash
# .env file format
DATABASE_URL="mysql://username:password@localhost:3306/treasury_invoicing?encoding=utf8mb4&timezone=UTC"
```

## Production Considerations

### Security
1. Use strong MySQL passwords
2. Create dedicated database user (not root)
3. Grant only necessary privileges

```sql
-- Create dedicated user
CREATE USER 'treasury_user'@'localhost' IDENTIFIED BY 'strong_password_here';

-- Grant privileges
GRANT SELECT, INSERT, UPDATE, DELETE ON treasury_invoicing.* TO 'treasury_user'@'localhost';
FLUSH PRIVILEGES;
```

### Backup
```bash
# Backup database
mysqldump -u root -p treasury_invoicing > treasury_backup.sql

# Restore database
mysql -u root -p treasury_invoicing < treasury_backup.sql
```

### Performance
- Enable query caching in `app_local.php`:
```php
'cacheMetadata' => true,
'log' => false,
```

## Next Steps

After database setup:
1. ✅ Database created
2. ✅ Migrations run
3. ✅ Sample data loaded
4. → Start application: `bin\cake server`
5. → Access at: `http://localhost:8765`
6. → Create your first invoice!

---

**Need Help?**
- Check `SETUP_GUIDE.md` for complete installation
- Review `CONVERSION_SUMMARY.md` for system overview
- See CakePHP docs: https://book.cakephp.org/4/en/orm.html
