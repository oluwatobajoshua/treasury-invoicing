# 🔧 Microsoft Graph Troubleshooting Guide

## Test Page
Navigate to: **http://localhost:8765/auth/test**

This page will help you diagnose connection issues with Microsoft Graph.

## Common Issues and Solutions

### 1. "Azure configuration not loaded"

**Problem:** The configuration file isn't being loaded.

**Solutions:**
- Verify `config/azure.php` exists
- Check if `config/bootstrap.php` has the configuration loader:
  ```php
  if (file_exists(CONFIG . 'azure.php')) {
      Configure::load('azure', 'default');
  }
  ```
- Restart the PHP server after changes

### 2. "Environment variables not loaded"

**Problem:** The `.env` file isn't being read.

**Solutions:**
- Verify `config/.env` exists (not `.env.example`)
- Check if the `.env` loader is uncommented in `config/bootstrap.php`:
  ```php
  if (!env('APP_NAME') && file_exists(CONFIG . '.env')) {
      $dotenv = new \josegonzalez\Dotenv\Loader([CONFIG . '.env']);
      $dotenv->parse()->putenv()->toEnv()->toServer();
  }
  ```
- Restart PHP server after creating/editing `.env`

### 3. Authentication Fails / Redirect Loop

**Problem:** Can't complete Microsoft login.

**Solutions:**
- Verify Azure App Registration redirect URI matches exactly:
  - Azure Portal: `http://localhost:8765/auth/callback`
  - .env file: `http://localhost:8765/auth/callback`
  - No trailing slashes, protocol must match
- Check if Client Secret hasn't expired in Azure Portal
- Clear browser cookies and session

### 4. "Failed to fetch users from Microsoft Graph"

**Problem:** Can't retrieve user list.

**Solutions:**
- Ensure you're logged in first (`/auth/login`)
- Verify API permissions in Azure:
  - Go to Azure Portal → App Registration → API Permissions
  - Should have: `User.Read`, `User.ReadBasic.All`
  - Must click "Grant admin consent"
- Check if access token is present in session
- Try logging out and back in to refresh token

### 5. "Invalid client" error from Microsoft

**Problem:** Azure rejects the authentication request.

**Solutions:**
- Verify Client ID in `.env` matches Azure App Registration
- Verify Tenant ID in `.env` matches your Azure AD tenant
- Ensure app is registered in the correct tenant
- Check Client Secret hasn't been revoked

### 6. Users dropdown shows "Error loading users"

**Problem:** Frontend can't fetch users.

**Solutions:**
- Check browser console for JavaScript errors
- Verify `/auth/getGraphUsers` endpoint returns valid JSON
- Ensure user is logged in (has valid session)
- Check network tab for failed requests

### 7. "Token expired" errors

**Problem:** Access token no longer valid.

**Solutions:**
- Tokens expire after ~1 hour
- Log out and log back in
- Implement refresh token logic (advanced)

## Testing Steps

### Step 1: Test Configuration
1. Go to `http://localhost:8765/auth/test`
2. Check "Configuration Check" section - should show ✅
3. Check "Environment Variables" section - should show ✅

### Step 2: Test Authentication
1. Click "Test Microsoft Login" button
2. Should redirect to Microsoft login page
3. Enter your Microsoft account credentials
4. Should redirect back to application
5. Check test page again - "Session Status" should show logged-in user

### Step 3: Test Graph API
1. After logging in, the test page will show "Test Microsoft Graph API"
2. Click "Fetch Users from Graph" button
3. Should display table of users from your organization

## Debug Mode

Enable detailed error logging in `config/app.php`:

```php
'debug' => true,
'Error' => [
    'errorLevel' => E_ALL,
],
'log' => true,
```

Check logs in `logs/error.log` and `logs/debug.log`

## Network Troubleshooting

### Test Azure Connection Manually

```bash
# Test if you can reach Microsoft auth endpoint
curl -I https://login.microsoftonline.com/YOUR_TENANT_ID/oauth2/v2.0/authorize

# Test if you can reach Graph API
curl -H "Authorization: Bearer YOUR_TOKEN" https://graph.microsoft.com/v1.0/me
```

### Verify Ports
- Ensure port 8765 is not blocked by firewall
- Check XAMPP Apache is running on port 8765

## Quick Fixes Checklist

- [ ] `.env` file exists in `config/` folder
- [ ] `.env` contains valid Azure credentials (Client ID, Secret, Tenant ID)
- [ ] `config/azure.php` exists
- [ ] `config/bootstrap.php` loads azure.php
- [ ] `config/bootstrap.php` loads .env file
- [ ] PHP server restarted after config changes
- [ ] Azure App Registration has correct redirect URI
- [ ] API permissions granted with admin consent
- [ ] Client Secret hasn't expired
- [ ] Browser cookies cleared if issues persist

## Get Help

If you're still stuck:

1. Check the test page results: `http://localhost:8765/auth/test`
2. Review browser console for JavaScript errors (F12)
3. Check CakePHP logs in `logs/` folder
4. Verify Azure Portal settings match your configuration
5. Test with a different browser to rule out cache issues

## Successful Setup Indicators

When everything is working correctly:

✅ Test page shows all green checkmarks
✅ Can log in with Microsoft account
✅ User info displays in header
✅ "Fetch Users" button returns organization users
✅ Traveler dropdown in Add Request page loads users
✅ No errors in browser console
✅ No errors in CakePHP logs

## Additional Resources

- Test Page: `http://localhost:8765/auth/test`
- Login Page: `http://localhost:8765/auth/login`
- Azure Portal: https://portal.azure.com
- Microsoft Graph Explorer: https://developer.microsoft.com/en-us/graph/graph-explorer
