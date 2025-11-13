# 🚀 Quick Start: Deploy CakePHP Travel Request to Azure

## ⚡ Fastest Way to Deploy (5 Minutes)

### Step 1: Create Azure Resources

```bash
# Login to Azure
az login

# Set variables
RESOURCE_GROUP="travel-request-rg"
LOCATION="eastus"
APP_NAME="travel-request-$(openssl rand -hex 3)"  # Unique name
DB_NAME="travel-db-$(openssl rand -hex 3)"
DB_ADMIN="dbadmin"
DB_PASSWORD="TravelReq2024!@#"  # Change this!

# Create resource group
az group create --name $RESOURCE_GROUP --location $LOCATION

# Create App Service Plan (Basic)
az appservice plan create \
  --name "${APP_NAME}-plan" \
  --resource-group $RESOURCE_GROUP \
  --sku B1 \
  --is-linux

# Create Web App
az webapp create \
  --resource-group $RESOURCE_GROUP \
  --plan "${APP_NAME}-plan" \
  --name $APP_NAME \
  --runtime "PHP:8.1"

# Create MySQL Database
az mysql flexible-server create \
  --resource-group $RESOURCE_GROUP \
  --name $DB_NAME \
  --admin-user $DB_ADMIN \
  --admin-password $DB_PASSWORD \
  --sku-name Standard_B1ms \
  --tier Burstable \
  --version 8.0.21 \
  --storage-size 32 \
  --public-access 0.0.0.0

# Create database
az mysql flexible-server db create \
  --resource-group $RESOURCE_GROUP \
  --server-name $DB_NAME \
  --database-name travel_requests

# Get database host
DB_HOST=$(az mysql flexible-server show \
  --resource-group $RESOURCE_GROUP \
  --name $DB_NAME \
  --query "fullyQualifiedDomainName" -o tsv)

# Get app URL
APP_URL=$(az webapp show \
  --resource-group $RESOURCE_GROUP \
  --name $APP_NAME \
  --query "defaultHostName" -o tsv)

echo "✅ Resources created!"
echo "App URL: https://$APP_URL"
echo "Database Host: $DB_HOST"
```

### Step 2: Configure Environment Variables

```bash
# Generate security salt
SECURITY_SALT=$(openssl rand -base64 32)

# Set app settings
az webapp config appsettings set \
  --resource-group $RESOURCE_GROUP \
  --name $APP_NAME \
  --settings \
  DATABASE_URL="mysql://${DB_ADMIN}:${DB_PASSWORD}@${DB_HOST}:3306/travel_requests?ssl-mode=REQUIRED" \
  SECURITY_SALT="$SECURITY_SALT" \
  DEBUG="false" \
  APP_ENCODING="UTF-8" \
  AZURE_CLIENT_ID="your-azure-client-id" \
  AZURE_CLIENT_SECRET="your-azure-client-secret" \
  AZURE_TENANT_ID="your-azure-tenant-id" \
  AZURE_REDIRECT_URI="https://${APP_URL}/auth/callback"

echo "✅ Environment variables configured!"
```

### Step 3: Deploy Code

```bash
# In your project directory
cd c:\xampp\htdocs\travel_request

# Initialize git (if not done)
git init
git add .
git commit -m "Initial deployment to Azure"

# Configure deployment
az webapp deployment source config-local-git \
  --resource-group $RESOURCE_GROUP \
  --name $APP_NAME

# Get deployment URL
DEPLOY_URL=$(az webapp deployment list-publishing-credentials \
  --resource-group $RESOURCE_GROUP \
  --name $APP_NAME \
  --query "scmUri" -o tsv)

# Add Azure remote and deploy
git remote add azure $DEPLOY_URL
git push azure main:master

echo "✅ Code deployed!"
```

### Step 4: Initialize Database

```bash
# SSH into the app
az webapp ssh --resource-group $RESOURCE_GROUP --name $APP_NAME

# In SSH console, run:
cd /home/site/wwwroot
php bin/cake.php migrations migrate
exit

echo "✅ Database initialized!"
```

### Step 5: Test Your App

```bash
echo "🎉 Deployment complete!"
echo "Visit: https://$APP_URL"
```

---

## 📱 Alternative: Deploy via Azure Portal (No CLI)

### 1. Create Web App (2 minutes)

1. Go to [portal.azure.com](https://portal.azure.com)
2. Click **"Create a resource"** → **"Web App"**
3. Fill in:
   - Name: `travel-request-app` (must be unique)
   - Runtime: **PHP 8.1**
   - Region: **East US**
   - Plan: **Basic B1**
4. Click **"Review + create"** → **"Create"**

### 2. Create MySQL Database (2 minutes)

1. **"Create a resource"** → **"Azure Database for MySQL"**
2. Select **"Flexible Server"**
3. Fill in:
   - Server name: `travel-request-db`
   - Admin: `dbadmin`
   - Password: Create strong password
   - Version: **8.0**
   - Compute: **Burstable B1ms**
4. **Networking** → Check **"Allow public access"**
5. Click **"Review + create"** → **"Create"**

### 3. Configure App Settings (3 minutes)

In **Web App** → **Configuration** → **Application settings**, add:

```
DATABASE_URL = mysql://dbadmin:YOUR_PASSWORD@travel-request-db.mysql.database.azure.com:3306/travel_requests?ssl-mode=REQUIRED

SECURITY_SALT = [Generate with: openssl rand -base64 32]

AZURE_CLIENT_ID = your-azure-app-client-id
AZURE_CLIENT_SECRET = your-azure-app-client-secret
AZURE_TENANT_ID = your-azure-tenant-id
AZURE_REDIRECT_URI = https://travel-request-app.azurewebsites.net/auth/callback

DEBUG = false
```

Click **Save**

### 4. Deploy Code (2 minutes)

**Method A: Local Git**
1. **Web App** → **Deployment Center**
2. Source: **Local Git**
3. Copy the Git URL
4. In terminal:
   ```bash
   git remote add azure [COPIED-GIT-URL]
   git push azure main:master
   ```

**Method B: GitHub**
1. Push code to GitHub
2. **Web App** → **Deployment Center**
3. Source: **GitHub** → Authorize → Select repo
4. Azure deploys automatically

### 5. Initialize Database (1 minute)

1. **Web App** → **SSH**
2. Run:
   ```bash
   cd /home/site/wwwroot
   php bin/cake.php migrations migrate
   ```

---

## 🔧 Post-Deployment Checklist

- [ ] Test app URL: `https://your-app.azurewebsites.net`
- [ ] Verify login with Microsoft account works
- [ ] Check database connection in logs
- [ ] Enable HTTPS only (App Service → TLS/SSL settings)
- [ ] Add custom domain (optional)
- [ ] Set up Application Insights (monitoring)
- [ ] Configure automated backups
- [ ] Review security settings

---

## 📊 Cost Estimate

**Development/Testing:**
- App Service B1: $13/month
- MySQL B1ms: $15/month
- **Total: ~$28/month**

**Production:**
- App Service S1: $70/month
- MySQL D2s: $100/month
- **Total: ~$170/month**

**Free alternatives:**
- Use Azure Free Tier (first 12 months)
- MySQL In App (free, limited)
- Scale down when not in use

---

## 🆘 Quick Troubleshooting

### App won't start
```bash
# Check logs
az webapp log tail --resource-group $RESOURCE_GROUP --name $APP_NAME

# Restart app
az webapp restart --resource-group $RESOURCE_GROUP --name $APP_NAME
```

### Database connection failed
```bash
# Test connection
az mysql flexible-server connect \
  --name $DB_NAME \
  --admin-user $DB_ADMIN \
  --admin-password $DB_PASSWORD
```

### OAuth not working
1. Azure AD → App registrations → Your app → Authentication
2. Add redirect URI: `https://[YOUR-APP].azurewebsites.net/auth/callback`
3. Update `AZURE_REDIRECT_URI` in app settings

---

## 📚 Full Documentation

- **Complete Guide**: See `AZURE_DEPLOYMENT.md`
- **Environment Config**: See `AZURE_ENV_CONFIG.md`
- **CI/CD Setup**: See `.github/workflows/azure-deploy.yml`

---

## 🎯 Next Steps

1. **Custom Domain**: App Service → Custom domains
2. **SSL Certificate**: Free with App Service Managed Certificate
3. **Monitoring**: Enable Application Insights
4. **Auto-scaling**: Configure based on CPU/memory
5. **Backup**: Enable automated backups
6. **CDN**: Add Azure CDN for static assets

---

## 📞 Support

- **Azure Documentation**: https://docs.microsoft.com/azure
- **CakePHP on Azure**: https://github.com/Azure-Samples/php-docs-hello-world
- **Issues**: Create issue in your repository

---

## 🗑️ Clean Up Resources

**To delete everything and stop charges:**

```bash
az group delete --name $RESOURCE_GROUP --yes --no-wait
```

This removes:
- App Service
- App Service Plan
- MySQL Database
- All associated resources

---

**🎉 Congratulations! Your CakePHP app is now live on Azure!**

Visit: `https://your-app-name.azurewebsites.net`
