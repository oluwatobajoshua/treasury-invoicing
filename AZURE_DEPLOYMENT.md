# Azure Deployment Guide for CakePHP Travel Request System

## Prerequisites

1. **Azure Account**: Active Azure subscription
2. **Azure CLI**: Install from https://docs.microsoft.com/en-us/cli/azure/install-azure-cli
3. **Git**: For deployment via Git
4. **Composer**: Installed locally for testing

## Option 1: Deploy via Azure Portal (Recommended for Beginners)

### Step 1: Create Azure App Service

1. Go to [Azure Portal](https://portal.azure.com)
2. Click **"Create a resource"** → **"Web App"**
3. Configure:
   - **Subscription**: Select your subscription
   - **Resource Group**: Create new or use existing
   - **Name**: `travel-request-app` (must be globally unique)
   - **Publish**: Code
   - **Runtime stack**: PHP 8.1 or 8.2
   - **Operating System**: Linux
   - **Region**: Choose closest to you
   - **Pricing Plan**: B1 (Basic) or higher

### Step 2: Create MySQL Database

**Option A: Azure Database for MySQL (Recommended)**

1. Go to Azure Portal → **"Create a resource"** → **"Azure Database for MySQL"**
2. Choose **"Flexible Server"**
3. Configure:
   - **Server name**: `travel-request-db`
   - **MySQL version**: 8.0
   - **Compute + storage**: Burstable, B1ms (1-2 vCores)
   - **Admin username**: `dbadmin`
   - **Password**: Create strong password
   - **Networking**: Allow access from Azure services

**Option B: MySQL In App (Free but limited)**

1. In App Service → **Configuration** → **General settings**
2. Enable **"MySQL In App"** (Free, good for testing)

### Step 3: Configure Environment Variables

In Azure App Service → **Configuration** → **Application settings**, add:

```
DATABASE_URL=mysql://dbadmin:YOUR_PASSWORD@travel-request-db.mysql.database.azure.com:3306/travel_requests?ssl-mode=REQUIRED

SECURITY_SALT=YOUR_RANDOM_32_CHAR_STRING

AZURE_CLIENT_ID=your-azure-app-client-id
AZURE_CLIENT_SECRET=your-azure-app-client-secret
AZURE_TENANT_ID=your-azure-tenant-id
AZURE_REDIRECT_URI=https://travel-request-app.azurewebsites.net/auth/callback

DEBUG=false
APP_ENCODING=UTF-8
APP_DEFAULT_LOCALE=en_US
APP_DEFAULT_TIMEZONE=UTC

CACHE_DURATION=+2 minutes
CACHE_DEFAULT_URL=file://tmp/cache/?prefix=myapp_default&duration=+2 minutes
CACHE_CAKECORE_URL=file://tmp/cache/persistent?prefix=myapp_cake_core&serialize=true&duration=+2 minutes
CACHE_CAKEMODEL_URL=file://tmp/cache/models?prefix=myapp_cake_model&serialize=true&duration=+2 minutes
```

### Step 4: Enable PHP Extensions

In App Service → **Configuration** → **General settings**:
- Enable: `mbstring`, `intl`, `pdo_mysql`, `openssl`, `fileinfo`

### Step 5: Deploy Code

**Method A: Local Git Deployment**

1. In App Service → **Deployment Center**
2. Choose **"Local Git"**
3. Get the Git URL (e.g., `https://travel-request-app.scm.azurewebsites.net/travel-request-app.git`)
4. In your local project:

```bash
# Initialize git (if not done)
git init
git add .
git commit -m "Initial commit"

# Add Azure remote
git remote add azure https://travel-request-app.scm.azurewebsites.net/travel-request-app.git

# Deploy
git push azure main:master
```

**Method B: GitHub Deployment**

1. Push code to GitHub repository
2. In App Service → **Deployment Center**
3. Choose **"GitHub"** → Authorize → Select repository
4. Azure will automatically deploy on push

### Step 6: Initialize Database

**SSH into the App Service:**

1. In Azure Portal → App Service → **SSH** (or **Advanced Tools** → **Go** → **SSH**)
2. Run migrations:

```bash
cd /home/site/wwwroot
php bin/cake.php migrations migrate
```

**Or create tables manually** via Azure Database for MySQL → Query editor

### Step 7: Configure Custom Domain (Optional)

1. Purchase domain or use existing
2. In App Service → **Custom domains**
3. Add your domain and configure DNS records

### Step 8: Enable HTTPS

1. In App Service → **TLS/SSL settings**
2. **HTTPS Only**: ON
3. Add SSL certificate (Free with Azure Managed Certificate)

---

## Option 2: Deploy via Azure CLI (Advanced)

### Step 1: Login to Azure

```bash
az login
```

### Step 2: Create Resource Group

```bash
az group create --name travel-request-rg --location eastus
```

### Step 3: Create App Service Plan

```bash
az appservice plan create \
  --name travel-request-plan \
  --resource-group travel-request-rg \
  --sku B1 \
  --is-linux
```

### Step 4: Create Web App

```bash
az webapp create \
  --resource-group travel-request-rg \
  --plan travel-request-plan \
  --name travel-request-app \
  --runtime "PHP:8.1"
```

### Step 5: Create MySQL Database

```bash
az mysql flexible-server create \
  --resource-group travel-request-rg \
  --name travel-request-db \
  --admin-user dbadmin \
  --admin-password YOUR_STRONG_PASSWORD \
  --sku-name Standard_B1ms \
  --tier Burstable \
  --version 8.0.21 \
  --storage-size 32
```

### Step 6: Configure Firewall

```bash
# Allow Azure services
az mysql flexible-server firewall-rule create \
  --resource-group travel-request-rg \
  --name travel-request-db \
  --rule-name AllowAzure \
  --start-ip-address 0.0.0.0 \
  --end-ip-address 0.0.0.0
```

### Step 7: Create Database

```bash
az mysql flexible-server db create \
  --resource-group travel-request-rg \
  --server-name travel-request-db \
  --database-name travel_requests
```

### Step 8: Configure App Settings

```bash
az webapp config appsettings set \
  --resource-group travel-request-rg \
  --name travel-request-app \
  --settings \
  DATABASE_URL="mysql://dbadmin:YOUR_PASSWORD@travel-request-db.mysql.database.azure.com:3306/travel_requests?ssl-mode=REQUIRED" \
  SECURITY_SALT="YOUR_32_CHAR_RANDOM_STRING" \
  DEBUG="false" \
  AZURE_CLIENT_ID="your-client-id" \
  AZURE_CLIENT_SECRET="your-client-secret" \
  AZURE_TENANT_ID="your-tenant-id" \
  AZURE_REDIRECT_URI="https://travel-request-app.azurewebsites.net/auth/callback"
```

### Step 9: Deploy Code

```bash
# Configure local Git deployment
az webapp deployment source config-local-git \
  --resource-group travel-request-rg \
  --name travel-request-app

# Get deployment URL (output from above command)
# Add remote and push
git remote add azure <deployment-url>
git push azure main:master
```

---

## Option 3: Deploy via GitHub Actions (CI/CD)

See `.github/workflows/azure-deploy.yml` for automated deployment

---

## Post-Deployment Configuration

### 1. Verify PHP Version

```bash
az webapp config show \
  --resource-group travel-request-rg \
  --name travel-request-app \
  --query linuxFxVersion
```

### 2. Enable Logging

```bash
az webapp log config \
  --resource-group travel-request-rg \
  --name travel-request-app \
  --application-logging filesystem \
  --detailed-error-messages true \
  --failed-request-tracing true \
  --web-server-logging filesystem
```

### 3. Stream Logs

```bash
az webapp log tail \
  --resource-group travel-request-rg \
  --name travel-request-app
```

### 4. Test the Application

Visit: `https://travel-request-app.azurewebsites.net`

---

## Troubleshooting

### Issue: 500 Internal Server Error

1. Check logs: Azure Portal → App Service → **Log stream**
2. Enable debug mode temporarily: Set `DEBUG=true`
3. Check file permissions in SSH console

### Issue: Database Connection Failed

1. Verify firewall rules allow Azure services
2. Check connection string format
3. Test connection via SSH:

```bash
php -r "new PDO('mysql:host=travel-request-db.mysql.database.azure.com;dbname=travel_requests', 'dbadmin', 'password');"
```

### Issue: OAuth Redirect Not Working

1. Update redirect URI in Azure AD App Registration
2. Add both:
   - `https://travel-request-app.azurewebsites.net/auth/callback`
   - `http://localhost:8765/auth/callback` (for local testing)

### Issue: Slow Performance

1. Upgrade App Service Plan to S1 or P1v2
2. Enable Application Insights for monitoring
3. Enable caching (Redis Cache)

---

## Cost Optimization

**Free/Low-Cost Setup:**
- App Service: B1 Basic (~$13/month)
- MySQL Flexible Server: Burstable B1ms (~$15/month)
- **Total: ~$28/month**

**Production Setup:**
- App Service: S1 Standard (~$70/month)
- MySQL Flexible Server: General Purpose D2s (~$100/month)
- Application Insights: Free tier (1GB/month)
- **Total: ~$170/month**

**Stop dev resources when not in use:**

```bash
az appservice plan update \
  --resource-group travel-request-rg \
  --name travel-request-plan \
  --sku FREE
```

---

## Security Best Practices

1. **Enable HTTPS Only**: Force SSL/TLS
2. **Use Managed Identity**: For accessing Azure resources
3. **Implement IP Restrictions**: Limit admin access
4. **Enable DDoS Protection**: For production
5. **Regular Backups**: Enable automated backups
6. **Update Dependencies**: Keep PHP and packages updated
7. **Monitor with Application Insights**: Track errors and performance

---

## Backup and Restore

### Create Backup

```bash
az webapp config backup create \
  --resource-group travel-request-rg \
  --webapp-name travel-request-app \
  --backup-name backup-$(date +%Y%m%d) \
  --container-url "https://yourstorageaccount.blob.core.windows.net/backups?SAS_TOKEN"
```

### Restore Backup

```bash
az webapp config backup restore \
  --resource-group travel-request-rg \
  --webapp-name travel-request-app \
  --backup-name backup-20250108
```

---

## Scaling

### Manual Scaling

```bash
# Scale up (vertical)
az appservice plan update \
  --resource-group travel-request-rg \
  --name travel-request-plan \
  --sku S1

# Scale out (horizontal)
az appservice plan update \
  --resource-group travel-request-rg \
  --name travel-request-plan \
  --number-of-workers 3
```

### Auto-scaling

Configure in Azure Portal → App Service → **Scale out (App Service Plan)**

---

## Monitoring

### Set up Application Insights

```bash
az monitor app-insights component create \
  --app travel-request-insights \
  --location eastus \
  --resource-group travel-request-rg

# Link to Web App
az webapp config appsettings set \
  --resource-group travel-request-rg \
  --name travel-request-app \
  --settings APPLICATIONINSIGHTS_CONNECTION_STRING="<connection-string>"
```

---

## Support

- Azure Support: https://azure.microsoft.com/support/
- CakePHP Documentation: https://book.cakephp.org/
- Project Issues: GitHub Issues

---

## Quick Commands Reference

```bash
# View app URL
az webapp show --resource-group travel-request-rg --name travel-request-app --query defaultHostName -o tsv

# Restart app
az webapp restart --resource-group travel-request-rg --name travel-request-app

# SSH into app
az webapp ssh --resource-group travel-request-rg --name travel-request-app

# View configuration
az webapp config show --resource-group travel-request-rg --name travel-request-app

# Delete all resources
az group delete --name travel-request-rg --yes --no-wait
```
