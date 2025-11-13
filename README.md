# Treasury Invoicing System

![Build Status](https://github.com/cakephp/app/actions/workflows/ci.yml/badge.svg?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/cakephp/app.svg?style=flat-square)](https://packagist.org/packages/cakephp/app)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%207-brightgreen.svg?style=flat-square)](https://github.com/phpstan/phpstan)

A professional treasury invoicing system built with [CakePHP](https://cakephp.org) 4.x for managing fresh and final invoices for cocoa export transactions, featuring automated invoice numbering, approval workflows, and SGC account integration.

## ✨ Features

- � **Fresh Invoice Management** - Create and manage initial invoices based on BL data
- 📋 **Final Invoice Management** - Generate FVP-prefixed invoices with landed quantities
- 🔢 **Auto-Generated Invoice Numbers** - Sequential numbering (0001, 0002) and FVP prefix
- 💰 **Automated Calculations** - Total value, payment percentages, quantity variance
- � **Approval Workflow** - Treasurer approval for both invoice types
- � **Contract Integration** - Auto-populate pricing from contracts
- � **Vessel & BL Tracking** - Complete shipment documentation
- � **SGC Account Management** - Multi-currency FCY account support
- 📱 **Responsive Design** - Works on desktop, tablet, and mobile
- 🛡️ **Error Handling** - Graceful error handling with user-friendly messages

## 🚀 Quick Deploy to Azure

Deploy this application to Microsoft Azure in ~5 minutes:

```bash
# See AZURE_QUICKSTART.md for complete guide
az login
# ... follow the quick start guide
```

**Deployment Options:**
- **Azure Portal** (GUI-based, beginner-friendly) → See `AZURE_DEPLOYMENT.md`
- **Azure CLI** (Fast, scriptable) → See `AZURE_QUICKSTART.md`
- **GitHub Actions** (CI/CD automation) → See `.github/workflows/azure-deploy.yml`

**📚 Azure Documentation:**
- [Quick Start Guide](AZURE_QUICKSTART.md) - 5-minute deployment
- [Complete Deployment Guide](AZURE_DEPLOYMENT.md) - Detailed instructions
- [Environment Configuration](AZURE_ENV_CONFIG.md) - Settings reference
- [Azure README](AZURE_README.md) - Files overview

**💰 Cost:** Starting at ~$28/month for dev/test environment

---

## 📦 Installation

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist cakephp/app [app_name]`.

If Composer is installed globally, run

```bash
composer create-project --prefer-dist cakephp/app
```

In case you want to use a custom app dir name (e.g. `/myapp/`):

```bash
composer create-project --prefer-dist cakephp/app myapp
```

You can now either use your machine's webserver to view the default home page, or start
up the built-in webserver with:

```bash
bin/cake server -p 8765
```

Then visit `http://localhost:8765` to see the welcome page.

## Update

Since this skeleton is a starting point for your application and various files
would have been modified as per your needs, there isn't a way to provide
automated upgrades, so you have to do any updates manually.

## Configuration

Read and edit the environment specific `config/app_local.php` and set up the
`'Datasources'` and any other configuration relevant for your application.
Other environment agnostic settings can be changed in `config/app.php`.

## Layout

The app skeleton uses [Milligram](https://milligram.io/) (v1.3) minimalist CSS
framework by default. You can, however, replace it with any other library or
custom styles.
