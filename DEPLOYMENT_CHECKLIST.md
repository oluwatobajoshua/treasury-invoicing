# ✅ Azure Deployment Checklist

Use this checklist to ensure a successful deployment to Azure.

## 📋 Pre-Deployment

### Azure Setup
- [ ] Azure account created (free trial available)
- [ ] Azure CLI installed (if using CLI method)
- [ ] Resource group name decided: `__________________`
- [ ] App name decided (globally unique): `__________________`
- [ ] Region selected: `__________________`

### Azure AD Configuration
- [ ] Azure AD app registration created
- [ ] Client ID obtained: `__________________`
- [ ] Client Secret created and saved securely
- [ ] Tenant ID obtained: `__________________`
- [ ] Redirect URI configured: `https://[app-name].azurewebsites.net/auth/callback`
- [ ] API permissions granted:
  - [ ] User.Read
  - [ ] Mail.Send
  - [ ] Chat.Create
  - [ ] ChatMessage.Send

### Database Planning
- [ ] Database name decided: `__________________`
- [ ] Admin username decided: `__________________`
- [ ] Strong admin password created (16+ chars, mixed case, numbers, symbols)
- [ ] Database tier selected:
  - [ ] Burstable B1ms ($15/month) - Development
  - [ ] General Purpose D2s ($100/month) - Production

### Security
- [ ] Security salt generated: `openssl rand -base64 32`
- [ ] All passwords stored securely (password manager)
- [ ] Secrets NOT committed to Git

---

## 🚀 Deployment Steps

### Step 1: Create Azure Resources
- [ ] Resource group created
- [ ] App Service Plan created (B1 Basic or higher)
- [ ] Web App created (PHP 8.1)
- [ ] MySQL Flexible Server created
- [ ] Database created (`treasury_invoicing`)
- [ ] Firewall rule added (Allow Azure services)

### Step 2: Configure Environment Variables
- [ ] `DATABASE_URL` configured with proper format
- [ ] `SECURITY_SALT` set (random 32+ characters)
- [ ] `AZURE_CLIENT_ID` set
- [ ] `AZURE_CLIENT_SECRET` set
- [ ] `AZURE_TENANT_ID` set
- [ ] `AZURE_REDIRECT_URI` set with correct app URL
- [ ] `DEBUG` set to `false`
- [ ] All settings saved in Azure Portal

### Step 3: Deploy Code
- [ ] Git repository initialized locally
- [ ] All code committed
- [ ] Azure remote added OR GitHub connected
- [ ] Code pushed successfully
- [ ] Deployment logs checked (no errors)

### Step 4: Initialize Database
- [ ] SSH into App Service
- [ ] Navigated to `/home/site/wwwroot`
- [ ] Migrations run: `php bin/cake.php migrations migrate`
- [ ] Tables created successfully
- [ ] Sample data added (if needed)

### Step 5: Verify Deployment
- [ ] App URL accessible: `https://[app-name].azurewebsites.net`
- [ ] No 500 errors on homepage
- [ ] Microsoft login button visible
- [ ] Database connection working (check logs)

---

## 🔧 Post-Deployment Configuration

### Security Settings
- [ ] HTTPS Only enabled
- [ ] TLS 1.2 minimum enforced
- [ ] IP restrictions configured (if needed)
- [ ] CORS settings configured (if needed)
- [ ] Custom error pages enabled

### SSL Certificate
- [ ] Free Managed Certificate added
- [ ] Custom domain added (if applicable)
- [ ] SSL binding configured
- [ ] Certificate auto-renewal enabled

### Monitoring & Logging
- [ ] Application Insights enabled
- [ ] Log retention period set
- [ ] Diagnostic logging enabled:
  - [ ] Application logging
  - [ ] Web server logging
  - [ ] Detailed error messages
  - [ ] Failed request tracing
- [ ] Alerts configured:
  - [ ] High CPU usage
  - [ ] High memory usage
  - [ ] Application errors
  - [ ] Database connection failures

### Performance
- [ ] Always On enabled (prevents cold starts)
- [ ] ARR Affinity disabled (for stateless apps)
- [ ] Compression enabled
- [ ] Cache headers configured

### Backup & Disaster Recovery
- [ ] Automated backups enabled
- [ ] Backup schedule configured (daily recommended)
- [ ] Backup retention period set (30 days minimum)
- [ ] Database backup configured
- [ ] Recovery plan documented

---

## 🧪 Testing

### Functional Testing
- [ ] Homepage loads correctly
- [ ] Microsoft OAuth login works
- [ ] User can create fresh invoice
- [ ] User can create final invoice
- [ ] Admin can manage approver settings
- [ ] Email notifications sent successfully
- [ ] Microsoft Graph user picker works
- [ ] All CRUD operations work
- [ ] File uploads work (invoices, attachments)

### Security Testing
- [ ] HTTPS enforced (HTTP redirects to HTTPS)
- [ ] OAuth flow secure (no token leakage)
- [ ] Session management secure
- [ ] SQL injection protection verified
- [ ] XSS protection verified
- [ ] CSRF protection enabled
- [ ] Sensitive data not in URLs

### Performance Testing
- [ ] Page load time < 3 seconds
- [ ] Database queries optimized
- [ ] No N+1 query issues
- [ ] Images optimized
- [ ] CDN configured (if needed)

### Compatibility Testing
- [ ] Works on Chrome
- [ ] Works on Firefox
- [ ] Works on Safari
- [ ] Works on Edge
- [ ] Mobile responsive (iOS)
- [ ] Mobile responsive (Android)

---

## 📊 Monitoring Setup

### Application Insights
- [ ] Instrumentation key added
- [ ] Custom events tracked
- [ ] Dependencies monitored
- [ ] Performance counters configured
- [ ] User behavior analytics enabled

### Alerts Configuration
- [ ] Email alerts configured
- [ ] SMS alerts configured (critical only)
- [ ] Teams/Slack integration (optional)
- [ ] Alert thresholds set appropriately

### Dashboard Setup
- [ ] Azure dashboard created
- [ ] Key metrics pinned:
  - [ ] Response time
  - [ ] Request rate
  - [ ] Error rate
  - [ ] CPU usage
  - [ ] Memory usage
  - [ ] Database connections

---

## 📝 Documentation

### Internal Documentation
- [ ] Deployment process documented
- [ ] Environment variables documented
- [ ] Troubleshooting guide created
- [ ] Rollback procedure documented
- [ ] Admin access procedures documented

### User Documentation
- [ ] User guide created
- [ ] FAQ documented
- [ ] Support contacts listed
- [ ] Known issues documented

---

## 👥 Team Handoff

### Access & Permissions
- [ ] Azure portal access granted to team
- [ ] Role-based access configured
- [ ] SSH keys shared (if applicable)
- [ ] Database access granted to DBAs
- [ ] Secrets shared securely (Azure Key Vault)

### Training
- [ ] Team trained on deployment process
- [ ] Team trained on monitoring tools
- [ ] Team trained on rollback procedure
- [ ] Support escalation path defined

---

## 🔄 Maintenance Plan

### Regular Tasks
- [ ] Weekly security updates
- [ ] Monthly dependency updates
- [ ] Quarterly performance reviews
- [ ] Annual disaster recovery drill

### Update Schedule
- [ ] CakePHP framework updates: Monthly
- [ ] PHP version updates: Quarterly
- [ ] Database version updates: As needed
- [ ] SSL certificate renewal: Automatic

---

## 🆘 Troubleshooting Quick Reference

### Issue: App not loading
1. Check Azure Portal → App Service → Overview (status)
2. Check logs: `az webapp log tail`
3. Verify environment variables
4. Restart app

### Issue: Database connection failed
1. Check firewall rules
2. Verify connection string
3. Test connection in SSH console
4. Check MySQL service status

### Issue: OAuth not working
1. Verify redirect URI in Azure AD
2. Check client ID and secret
3. Verify tenant ID
4. Check API permissions granted

### Issue: 500 Internal Server Error
1. Enable debug mode temporarily
2. Check application logs
3. Check file permissions
4. Verify all dependencies installed

---

## ✅ Final Verification

Before marking deployment complete:

- [ ] All tests passing
- [ ] All stakeholders notified
- [ ] Documentation updated
- [ ] Monitoring confirmed working
- [ ] Backup tested
- [ ] Support team briefed
- [ ] Go-live communication sent

---

## 🎉 Post-Go-Live

### Day 1
- [ ] Monitor error rates closely
- [ ] Check performance metrics
- [ ] Verify backups running
- [ ] Address any user-reported issues

### Week 1
- [ ] Review all metrics
- [ ] Optimize based on usage patterns
- [ ] Address performance issues
- [ ] Update documentation based on learnings

### Month 1
- [ ] Cost analysis
- [ ] Performance optimization
- [ ] Scale as needed
- [ ] Plan for future enhancements

---

## 📞 Support Contacts

| Role | Name | Contact |
|------|------|---------|
| Azure Admin | ____________ | ____________ |
| Database Admin | ____________ | ____________ |
| App Developer | ____________ | ____________ |
| Support Lead | ____________ | ____________ |

---

## 📅 Maintenance Windows

| Type | Frequency | Day/Time | Duration |
|------|-----------|----------|----------|
| Updates | Weekly | Sunday 2 AM | 1 hour |
| Backups | Daily | 3 AM | 30 min |
| Security Scan | Monthly | 1st Sunday | 2 hours |

---

**Deployment Date:** __________________
**Completed By:** __________________
**Sign-off:** __________________

---

Save this checklist and check items off as you complete them!
