# 🌐 Azure Deployment Files

This directory contains all files needed to deploy the CakePHP Travel Request System to Microsoft Azure.

## 📁 Files Overview

### Configuration Files

1. **`.deployment`**
   - Azure deployment configuration
   - Tells Azure to use `deploy.sh` for deployment

2. **`deploy.sh`**
   - Bash script for Azure deployment
   - Installs Composer dependencies
   - Sets file permissions
   - Runs database migrations
   - Clears cache

3. **`web.config`**
   - IIS configuration for Windows-based Azure App Service
   - URL rewriting rules for CakePHP
   - PHP handler configuration
   - Security headers

### Documentation Files

4. **`AZURE_DEPLOYMENT.md`** ⭐ Main Guide
   - Complete deployment instructions
   - Three deployment options:
     - Azure Portal (GUI)
     - Azure CLI (Command line)
     - GitHub Actions (CI/CD)
   - Troubleshooting section
   - Cost optimization tips
   - Scaling and monitoring

5. **`AZURE_ENV_CONFIG.md`** 🔧 Configuration
   - Environment variables reference
   - Database connection strings
   - Security configuration
   - Testing guide
   - Troubleshooting

6. **`AZURE_QUICKSTART.md`** 🚀 Quick Start
   - 5-minute deployment guide
   - Copy-paste commands
   - Portal-based alternative
   - Post-deployment checklist

### CI/CD

7. **`.github/workflows/azure-deploy.yml`**
   - GitHub Actions workflow
   - Automated deployment on push
   - Builds and deploys to Azure
   - Requires Azure publish profile secret

## 🎯 Quick Start

### Option 1: Azure CLI (Recommended)

```bash
# Follow AZURE_QUICKSTART.md
# Takes ~5 minutes

az login
# ... follow the guide
```

### Option 2: Azure Portal

1. Open [Azure Portal](https://portal.azure.com)
2. Follow `AZURE_DEPLOYMENT.md` → "Option 1: Deploy via Azure Portal"
3. Takes ~10 minutes, all GUI-based

### Option 3: GitHub Actions

1. Push code to GitHub
2. Set up Azure publish profile as secret
3. Follow `AZURE_DEPLOYMENT.md` → "Option 3: Deploy via GitHub Actions"
4. Automatic deployment on every push

## 📋 Prerequisites

- **Azure Account**: [Free trial available](https://azure.microsoft.com/free/)
- **Azure CLI** (for CLI deployment): [Install here](https://docs.microsoft.com/cli/azure/install-azure-cli)
- **Git**: For code deployment
- **Composer**: Already included in deployment script

## 🔐 Required Secrets

Before deployment, you need:

1. **Azure AD Application** (for OAuth login):
   - Client ID
   - Client Secret
   - Tenant ID

2. **Strong Passwords**:
   - Database admin password
   - Security salt (generate with `openssl rand -base64 32`)

3. **Connection Strings**:
   - Database URL (automatically configured)

See `AZURE_ENV_CONFIG.md` for details.

## 💰 Cost Estimate

### Development/Testing
- **App Service B1**: ~$13/month
- **MySQL Flexible Server B1ms**: ~$15/month
- **Total**: ~$28/month

### Production
- **App Service S1**: ~$70/month
- **MySQL Flexible Server D2s**: ~$100/month
- **Application Insights**: Free tier (1GB)
- **Total**: ~$170/month

### Free Options
- Azure Free Tier (first 12 months)
- MySQL In App (free, limited to 1GB)
- Stop resources when not in use

## 🚀 Deployment Steps Summary

1. **Create Azure Resources** (5 min)
   - App Service
   - MySQL Database
   - Resource Group

2. **Configure Environment** (2 min)
   - Database connection
   - Azure AD OAuth
   - Security settings

3. **Deploy Code** (3 min)
   - Git push to Azure
   - Or GitHub Actions

4. **Initialize Database** (1 min)
   - Run migrations via SSH

5. **Test & Verify** (2 min)
   - Test login
   - Check functionality

**Total Time: ~15 minutes**

## 📊 Deployment Options Comparison

| Method | Difficulty | Time | Best For |
|--------|-----------|------|----------|
| **Azure Portal** | Easy | 10 min | Beginners, GUI preference |
| **Azure CLI** | Medium | 5 min | Developers, automation |
| **GitHub Actions** | Medium | 15 min setup | CI/CD, teams |

## 🛠️ Post-Deployment Tasks

- [ ] Enable HTTPS only
- [ ] Configure custom domain (optional)
- [ ] Set up Application Insights
- [ ] Configure automated backups
- [ ] Set up auto-scaling rules
- [ ] Review security settings
- [ ] Test all functionality
- [ ] Set up monitoring alerts

## 🐛 Common Issues

### "Database connection failed"
➡️ Check `AZURE_ENV_CONFIG.md` → Troubleshooting → Database Connection

### "OAuth redirect error"
➡️ Update redirect URI in Azure AD app registration

### "500 Internal Server Error"
➡️ Enable debug mode temporarily: `DEBUG=true`
➡️ Check logs: `az webapp log tail --name APP_NAME --resource-group RG_NAME`

### "File permissions error"
➡️ SSH into app and run: `chmod -R 777 tmp logs`

## 📚 Documentation Structure

```
AZURE_DEPLOYMENT.md (Main Guide)
├── Option 1: Azure Portal
│   ├── Step-by-step with screenshots
│   ├── Create resources
│   ├── Configure settings
│   └── Deploy code
├── Option 2: Azure CLI
│   ├── Command reference
│   ├── Scripted deployment
│   └── Advanced options
└── Option 3: GitHub Actions
    ├── Workflow setup
    ├── Secrets configuration
    └── Automated deployment

AZURE_ENV_CONFIG.md (Configuration)
├── Environment Variables
├── Database Connection Strings
├── Security Configuration
├── Testing Guide
└── Troubleshooting

AZURE_QUICKSTART.md (Quick Start)
├── 5-Minute CLI Deployment
├── Portal Alternative
├── Post-Deployment Checklist
└── Cost Estimates
```

## 🔄 Update Deployment

### Update Code

```bash
# Make changes locally
git add .
git commit -m "Update feature"

# Deploy to Azure
git push azure main:master

# Or via GitHub (if using Actions)
git push origin main
```

### Update Environment Variables

```bash
az webapp config appsettings set \
  --resource-group RG_NAME \
  --name APP_NAME \
  --settings NEW_VAR="value"
```

### Update Database Schema

```bash
# SSH into app
az webapp ssh --resource-group RG_NAME --name APP_NAME

# Run migrations
cd /home/site/wwwroot
php bin/cake.php migrations migrate
```

## 🗑️ Clean Up / Delete Resources

**To stop all charges:**

```bash
az group delete --name RESOURCE_GROUP_NAME --yes --no-wait
```

This deletes:
- App Service
- Database
- All configurations
- All backups

## 📞 Support & Resources

- **Azure Documentation**: https://docs.microsoft.com/azure/app-service/
- **CakePHP on Azure**: https://github.com/Azure-Samples/php-docs-hello-world
- **Azure CLI Reference**: https://docs.microsoft.com/cli/azure/
- **GitHub Actions for Azure**: https://github.com/Azure/actions

## 🎓 Learning Resources

- [Azure App Service Tutorial](https://docs.microsoft.com/learn/modules/host-a-web-app-with-azure-app-service/)
- [PHP on Azure Best Practices](https://docs.microsoft.com/azure/app-service/quickstart-php)
- [Azure MySQL Documentation](https://docs.microsoft.com/azure/mysql/)

## ✅ Deployment Verification

After deployment, verify:

1. **App is running**: Visit `https://your-app.azurewebsites.net`
2. **OAuth works**: Test Microsoft login
3. **Database connected**: Check logs for connection success
4. **HTTPS enabled**: Verify SSL certificate
5. **Environment variables loaded**: Test config in SSH

## 🎉 Success!

Once deployed, your CakePHP Travel Request System will be:
- ✅ Live on Azure
- ✅ Using MySQL database
- ✅ Secured with HTTPS
- ✅ Integrated with Azure AD
- ✅ Scalable and monitored

**Need help?** Check the troubleshooting sections in the documentation files!
