# Getting Started - Treasury Invoicing System

## 🚀 Quick Start (5 Minutes)

### Prerequisites Check
- [ ] PHP 8.1+ installed
- [ ] MySQL 5.7+ running
- [ ] Composer installed
- [ ] XAMPP (or similar) set up

### Installation Steps

#### 1. Create Database (1 minute)
```sql
CREATE DATABASE treasury_invoicing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 2. Configure Connection (1 minute)
Copy and edit `config/app_local.php`:
```php
'Datasources' => [
    'default' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',  // Your MySQL password
        'database' => 'treasury_invoicing',
    ],
],
```

#### 3. Install & Setup (3 minutes)
```bash
# Install dependencies
composer install

# Run migrations (creates all tables)
bin\cake migrations migrate

# Load sample data
bin\cake migrations seed --seed InitialDataSeed

# Start server
bin\cake server
```

#### 4. Access Application
Open browser: `http://localhost:8765`
Or XAMPP: `http://localhost/treasury_inv`

---

## 📊 What You'll See

### After Setup, You'll Have:

**Sample Clients (4):**
- SFI AGRI COMMODITIES LTD
- CARGILL COCOA & CHOCOLATE  
- SFI/CARGILL
- Cargill

**Sample Products (2):**
- COCOA @ $6,292.35
- Cocoa @ $5,717.50

**Sample Vessels (3):**
- GREAT TEMA - GTT0525
- GRANDE BENIN - GBN0625
- GREAT COTONOU - GTC0625

**Sample SGC Accounts (4):**
- 1100524 - ACCESS UK (GBP)
- 1100533 - FIDELITY NXP (USD)
- 1100534 - STERLING (USD)
- 1100523 - ACCESS UK (USD)

**Sample Contracts (2):**
- 2025-SI 1B/QP 136484 (SFI/CARGILL, Cocoa)
- QP-136790.01/6B (Cargill, Cocoa)

---

## 🎯 First Actions

### Try Creating Your First Invoice

#### 1. Create a Fresh Invoice
```
Dashboard → New Fresh Invoice

Fill in:
✓ Client: SFI/CARGILL
✓ Product: Cocoa
✓ Contract: 2025-SI 1B/QP 136484 (auto-fills price)
✓ Vessel: Vessel: GREAT TEMA - GTT0525
✓ BL Number: LOS37115
✓ Quantity: 262.430
✓ Payment %: 98
✓ SGC Account: 1100533 - FIDELITY NXP
✓ Bulk/Bag: 16 BULK

Result:
- Invoice #0001 created
- Total: $1,618,247.35
- Status: Draft
```

#### 2. Submit for Approval
```
View Invoice → Submit for Approval
Status: Draft → Pending Treasurer Approval
```

#### 3. Approve as Treasurer
```
View Invoice → Approve
Status: Pending → Approved
```

#### 4. Send to Export Team
```
View Invoice → Send to Export Team
Status: Approved → Sent to Export
✓ Complete!
```

#### 5. Create Final Invoice
```
Dashboard → Final Invoices → New Final Invoice

Fill in:
✓ Reference Invoice: 0001 (auto-fills all data)
✓ Landed Quantity: 260.748 (from CWT report)
✓ Notes: 1.682 MT variance

Result:
- Invoice #FVP0001 created
- Variance: 1.682 MT
- Total: $1,607,565.89
- Status: Draft
```

#### 6. Submit & Approve Final Invoice
```
Submit → Approve → Send to Sales
✓ Complete!
```

---

## 📁 Important Files & Folders

### Configuration
- `config/app_local.php` - Database settings
- `config/routes.php` - URL routing

### Database
- `config/Migrations/` - Database structure
- `config/Seeds/` - Sample data

### Business Logic
- `src/Controller/FreshInvoicesController.php`
- `src/Controller/FinalInvoicesController.php`
- `src/Model/Table/` - Database tables (ORM)
- `src/Model/Entity/` - Data entities

### User Interface
- `templates/FreshInvoices/` - Fresh invoice views
- `templates/FinalInvoices/` - Final invoice views

### Documentation
- `README.md` - Overview
- `SETUP_GUIDE.md` - Installation guide
- `DATABASE_SETUP.md` - Database configuration
- `WORKFLOW_GUIDE.md` - User workflows
- `CONVERSION_SUMMARY.md` - Complete conversion details

---

## 🔧 Common Commands

### Database
```bash
# Check migration status
bin\cake migrations status

# Run migrations
bin\cake migrations migrate

# Rollback last migration
bin\cake migrations rollback

# Seed data
bin\cake migrations seed --seed InitialDataSeed

# Reset database (WARNING: Deletes all data!)
bin\cake migrations rollback -t 0
bin\cake migrations migrate
bin\cake migrations seed --seed InitialDataSeed
```

### Application
```bash
# Start development server
bin\cake server

# Clear all caches
bin\cake cache clear_all

# Run on specific port
bin\cake server -p 8080
```

### Composer
```bash
# Install dependencies
composer install

# Update dependencies
composer update

# Optimize autoloader (production)
composer dump-autoload --optimize
```

---

## ❓ Troubleshooting

### Can't connect to database?
```bash
# Check MySQL is running
# XAMPP: Start MySQL in control panel
# Command line: net start mysql

# Verify credentials in config/app_local.php
```

### Migrations fail?
```bash
# Check database exists
# Check user has CREATE/ALTER privileges
# Check config/app_local.php has correct settings
```

### Page not found?
```bash
# Check URL routing in config/routes.php
# Clear cache: bin\cake cache clear_all
# Check .htaccess files exist (XAMPP)
```

### Can't write to tmp/ folder?
```bash
# Windows: Check folder permissions
# Linux/Mac: chmod -R 777 tmp logs
```

---

## 📚 Next Steps

### For Users
1. ✅ Create test invoices
2. ✅ Test approval workflow
3. ✅ Review generated data
4. → Train staff on system
5. → Import actual client/product data
6. → Start using for real invoices

### For Developers
1. ✅ System is ready
2. → Add PDF generation
3. → Add email notifications
4. → Add user authentication
5. → Add reporting features
6. → Deploy to production

---

## 📖 Documentation Index

| Document | Purpose | For Who |
|----------|---------|---------|
| **README.md** | System overview | Everyone |
| **SETUP_GUIDE.md** | Installation steps | IT/Developers |
| **DATABASE_SETUP.md** | Database config | IT/Developers |
| **WORKFLOW_GUIDE.md** | How to use system | End Users |
| **CONVERSION_SUMMARY.md** | Technical details | Developers |
| **This File** | Quick start | Everyone |

---

## ✅ Verification Checklist

After installation, verify:
- [ ] Database created successfully
- [ ] 7 migrations completed
- [ ] Sample data loaded (4 clients, 2 products, etc.)
- [ ] Application loads at http://localhost:8765
- [ ] Can navigate to Fresh Invoices page
- [ ] Can create new fresh invoice
- [ ] Invoice number auto-generates (0001)
- [ ] Total value auto-calculates
- [ ] Can submit for approval
- [ ] Status changes correctly
- [ ] Can approve invoice
- [ ] Can create final invoice
- [ ] FVP prefix added correctly
- [ ] Variance calculated correctly

---

## 🆘 Support

### If you need help:

1. **Check Documentation**
   - Start with relevant guide above
   - Search for error message

2. **Check Logs**
   - `logs/error.log` - Application errors
   - `logs/debug.log` - Debug info (if DEBUG=true)

3. **CakePHP Resources**
   - Docs: https://book.cakephp.org/4/en/
   - API: https://api.cakephp.org/4.5/
   - Forum: https://discourse.cakephp.org/

4. **Contact Development Team**
   - Include error messages
   - Describe steps to reproduce
   - Share relevant log entries

---

## 🎉 You're Ready!

The Treasury Invoicing System is now set up and ready to use.

**Start by:**
1. Creating a test fresh invoice
2. Approving it as treasurer
3. Creating a final invoice from it
4. Reviewing the workflow

**Happy Invoicing! 🚢📄💰**

---

**Last Updated**: November 12, 2025
**Version**: 1.0.0
**Framework**: CakePHP 4.5
