# Logo Upload Instructions for Treasury Invoicing System

## Where to Upload the Sunbeth Logo

### Step 1: Upload Logo File
Upload your Sunbeth Global Concepts logo to:
```
c:\xampp\htdocs\treasury_inv\webroot\img\sunbeth-logo.png
```

**Recommended specifications:**
- File name: `sunbeth-logo.png` (exactly as shown)
- Format: PNG with transparent background (preferred) or JPG
- Dimensions: 200px width × 60-80px height
- File size: Under 100KB for fast loading

### Step 2: Update Company Settings in Database

The system will automatically create default settings, but you can update them by running this SQL:

```sql
-- Check if settings exist
SELECT * FROM settings;

-- If no settings exist, insert default
INSERT INTO settings (company_name, company_logo, email, telephone, corporate_address) 
VALUES (
    'Sunbeth Global Concepts',
    'sunbeth-logo.png',
    'info@sunbeth.net',
    '+234(0)805 6666 266',
    'First Floor, Churchgate Towers 2, Victoria Island, Lagos State, Nigeria.'
);

-- If settings exist, update them
UPDATE settings SET
    company_name = 'Sunbeth Global Concepts',
    company_logo = 'sunbeth-logo.png',
    email = 'info@sunbeth.net',
    telephone = '+234(0)805 6666 266',
    corporate_address = 'First Floor, Churchgate Towers 2, Victoria Island, Lagos State, Nigeria.'
WHERE id = 1;
```

### Step 3: Alternative - Use Different Logo Name
If you want to use a different filename, update the database:
```sql
UPDATE settings SET company_logo = 'your-logo-filename.png' WHERE id = 1;
```

### Step 4: Test the Invoice
1. Navigate to Fresh Invoices → Add New Invoice
2. The logo should appear in the top-left corner
3. Company details should show in the top-right corner

## Company Settings Table Structure

The `settings` table contains:
- `company_name` - Your company name
- `company_logo` - Logo filename (stored in webroot/img/)
- `email` - Company email address
- `telephone` - Company phone number
- `corporate_address` - Full corporate office address

## Troubleshooting

### Logo not showing?
1. Verify file is in: `webroot/img/sunbeth-logo.png`
2. Check filename matches exactly (case-sensitive)
3. Clear browser cache (Ctrl+F5)
4. Check file permissions

### Wrong company info?
1. Run: `SELECT * FROM settings;` to see current data
2. Update using SQL above
3. Refresh the invoice page

### No settings table?
Run migrations:
```bash
bin\cake migrations migrate
```

## Quick Upload Steps

1. **Copy your logo** → `c:\xampp\htdocs\treasury_inv\webroot\img\sunbeth-logo.png`
2. **Open phpMyAdmin** → Select `treasury_invoicing` database
3. **Run SQL** → Insert or Update settings (see SQL above)
4. **Test** → Create a new fresh invoice and verify logo appears

---

The invoice will now display:
- ✅ Sunbeth logo (top-left)
- ✅ Email, telephone, address (top-right)
- ✅ Current date
- ✅ Professional table layout matching the PDF format
