# Azure App Service Environment Variables Configuration

## Required Environment Variables

Copy these to Azure Portal → App Service → Configuration → Application settings:

### Database Configuration

```
DATABASE_URL=mysql://USERNAME:PASSWORD@HOSTNAME:3306/DATABASE_NAME?ssl-mode=REQUIRED
```

**Example:**
```
DATABASE_URL=mysql://dbadmin:MyStr0ngP@ss@travel-request-db.mysql.database.azure.com:3306/travel_requests?ssl-mode=REQUIRED
```

### Security Configuration

```
SECURITY_SALT=
```

**Generate a secure random string (32+ characters):**

PowerShell:
```powershell
-join ((48..57) + (65..90) + (97..122) | Get-Random -Count 32 | ForEach-Object {[char]$_})
```

Bash:
```bash
openssl rand -base64 32
```

### Azure AD OAuth Configuration

```
AZURE_CLIENT_ID=your-azure-app-client-id
AZURE_CLIENT_SECRET=your-azure-app-client-secret
AZURE_TENANT_ID=your-azure-tenant-id
AZURE_REDIRECT_URI=https://your-app-name.azurewebsites.net/auth/callback
```

**Get these from:**
1. Azure Portal → Azure Active Directory → App registrations
2. Select your app
3. Copy:
   - Application (client) ID → `AZURE_CLIENT_ID`
   - Directory (tenant) ID → `AZURE_TENANT_ID`
4. Certificates & secrets → New client secret → Copy value → `AZURE_CLIENT_SECRET`
5. Authentication → Add platform → Web → Redirect URI → `AZURE_REDIRECT_URI`

### Application Configuration

```
DEBUG=false
APP_NAME=Travel Request System
APP_ENCODING=UTF-8
APP_DEFAULT_LOCALE=en_US
APP_DEFAULT_TIMEZONE=UTC
```

### Cache Configuration

```
CACHE_DURATION=+2 minutes
CACHE_DEFAULT_URL=file://tmp/cache/?prefix=travel_default&duration=+2 minutes
CACHE_CAKECORE_URL=file://tmp/cache/persistent?prefix=travel_cake_core&serialize=true&duration=+2 minutes
CACHE_CAKEMODEL_URL=file://tmp/cache/models?prefix=travel_cake_model&serialize=true&duration=+2 minutes
```

### Session Configuration

```
SESSION_DEFAULTS=php
SESSION_TIMEOUT=240
SESSION_COOKIE_PATH=/
```

### Email Configuration (if using)

```
EMAIL_TRANSPORT_DEFAULT_URL=smtp://user:password@smtp.example.com:587?tls=true
```

---

## Azure-Specific Settings

### PHP Settings (Configuration → General settings → Stack settings)

```
PHP Version: 8.1 or 8.2
```

### Required PHP Extensions

Enable these extensions (most are enabled by default):
- mbstring
- intl
- pdo_mysql
- openssl
- xml
- fileinfo
- curl
- json

---

## Complete Configuration Example

**Azure Portal → App Service → Configuration → Application settings:**

| Name | Value |
|------|-------|
| `DATABASE_URL` | `mysql://dbadmin:Pass123!@travel-db.mysql.database.azure.com:3306/travel_requests?ssl-mode=REQUIRED` |
| `SECURITY_SALT` | `a8f7d9e2b4c6h1j3k5m7n9p1q3r5s7t9` |
| `AZURE_CLIENT_ID` | `12345678-1234-1234-1234-123456789012` |
| `AZURE_CLIENT_SECRET` | `abc123~DEF456.GHI789-JKL012` |
| `AZURE_TENANT_ID` | `87654321-4321-4321-4321-210987654321` |
| `AZURE_REDIRECT_URI` | `https://travel-request-app.azurewebsites.net/auth/callback` |
| `DEBUG` | `false` |
| `APP_ENCODING` | `UTF-8` |
| `APP_DEFAULT_LOCALE` | `en_US` |
| `APP_DEFAULT_TIMEZONE` | `UTC` |

---

## Azure Database Connection Strings

### MySQL Flexible Server Format

```
mysql://USERNAME:PASSWORD@HOSTNAME:3306/DATABASE_NAME?ssl-mode=REQUIRED
```

### Connection String Components

- **USERNAME**: Database admin username (e.g., `dbadmin`)
- **PASSWORD**: Database password (URL encode special characters)
- **HOSTNAME**: Server name (e.g., `travel-request-db.mysql.database.azure.com`)
- **DATABASE_NAME**: Database name (e.g., `travel_requests`)
- **SSL**: Required for Azure MySQL

### Special Character Encoding in Passwords

If your password contains special characters, URL encode them:
- `@` → `%40`
- `#` → `%23`
- `$` → `%24`
- `%` → `%25`
- `^` → `%5E`
- `&` → `%26`

**Example with special characters:**
```
Password: MyP@ss#123
Encoded: MyP%40ss%23123
```

---

## Testing Environment Variables

### In Azure SSH Console

```bash
# Access the app
cd /home/site/wwwroot

# Test database connection
php -r "echo getenv('DATABASE_URL');"

# Test CakePHP configuration
php bin/cake.php --help
```

### Test from PHP

Create a temporary test file `test-config.php`:

```php
<?php
require 'vendor/autoload.php';

use Cake\Core\Configure;
use Cake\Database\Connection;

// Load configuration
Configure::load('app', 'default');

// Test database connection
try {
    $config = Configure::read('Datasources.default');
    echo "Database Config: " . print_r($config, true) . "\n";
    
    $connection = new Connection($config);
    $connection->connect();
    echo "✅ Database connection successful!\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}

// Test Azure AD config
$azure = Configure::read('Azure');
echo "\nAzure AD Config:\n";
echo "- Client ID: " . (isset($azure['clientId']) ? 'SET' : 'NOT SET') . "\n";
echo "- Tenant ID: " . (isset($azure['tenantId']) ? 'SET' : 'NOT SET') . "\n";
echo "- Redirect URI: " . ($azure['redirectUri'] ?? 'NOT SET') . "\n";
```

Run:
```bash
php test-config.php
```

---

## Security Best Practices

1. **Never commit secrets to Git**
   - Add `.env` to `.gitignore`
   - Use Azure App Settings for production

2. **Rotate secrets regularly**
   - Change `SECURITY_SALT` periodically
   - Rotate Azure AD client secrets

3. **Use strong passwords**
   - Database: 16+ characters, mixed case, numbers, symbols
   - Minimum: `MyStr0ng!P@ssw0rd#2024`

4. **Enable SSL/TLS**
   - Force HTTPS in Azure
   - Use `ssl-mode=REQUIRED` for database

5. **Limit database access**
   - Use Azure firewall rules
   - Only allow Azure services + your IP

---

## Troubleshooting

### Issue: "Could not connect to database"

**Check:**
1. DATABASE_URL format is correct
2. Password is URL-encoded
3. MySQL firewall allows Azure services
4. Database exists

**Test connection:**
```bash
php -r "new PDO('mysql:host=HOST;dbname=DB', 'USER', 'PASS');"
```

### Issue: "Invalid redirect URI"

**Fix:**
1. Azure AD → App registrations → Your app → Authentication
2. Add redirect URI: `https://your-app.azurewebsites.net/auth/callback`
3. Update `AZURE_REDIRECT_URI` environment variable

### Issue: "Security salt not configured"

**Fix:**
```bash
# Generate new salt
openssl rand -base64 32

# Add to Azure App Settings
SECURITY_SALT=<generated-value>
```

### Issue: Environment variables not loaded

**Check:**
1. App Service → Configuration → Application settings
2. Click "Save" after adding settings
3. Restart app: `az webapp restart --name APP_NAME --resource-group RG_NAME`

---

## Updating Configuration

### Via Azure Portal

1. App Service → Configuration → Application settings
2. Click "New application setting"
3. Add Name and Value
4. Click "OK" → "Save"
5. App will restart automatically

### Via Azure CLI

```bash
az webapp config appsettings set \
  --resource-group travel-request-rg \
  --name travel-request-app \
  --settings \
  DEBUG="false" \
  NEW_SETTING="value"
```

### Via GitHub Actions

Add secrets in GitHub:
1. Repository → Settings → Secrets → Actions
2. Add repository secrets
3. Reference in workflow: `${{ secrets.SECRET_NAME }}`

---

## Environment-Specific Configuration

### Development (Local)

Use `.env` file:
```env
export DEBUG=true
export DATABASE_URL="mysql://root@localhost/travel_dev"
```

### Staging

Use separate Azure App Service with `_staging` suffix:
```
AZURE_REDIRECT_URI=https://travel-request-app-staging.azurewebsites.net/auth/callback
```

### Production

Use production settings with `DEBUG=false`:
```
DEBUG=false
AZURE_REDIRECT_URI=https://travel-request-app.azurewebsites.net/auth/callback
```
