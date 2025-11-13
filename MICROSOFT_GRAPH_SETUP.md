# Microsoft Graph Integration Setup

## Overview
This application uses Microsoft Azure AD (Entra ID) for authentication and to fetch the list of employees/travelers from your organization's directory.

## Prerequisites
- An Azure AD (Microsoft Entra ID) tenant
- Admin access to register applications in Azure portal
- Organization Microsoft 365 account

## Setup Steps

### 1. Register Application in Azure Portal

1. Go to [Azure Portal](https://portal.azure.com)
2. Navigate to **Azure Active Directory** (or Microsoft Entra ID)
3. Click on **App registrations** → **New registration**
4. Fill in the details:
   - **Name**: Travel Request System
   - **Supported account types**: Accounts in this organizational directory only (Single tenant)
   - **Redirect URI**: Web → `http://localhost:8765/auth/callback`
5. Click **Register**

### 2. Configure API Permissions

1. In your app registration, go to **API permissions**
2. Click **Add a permission** → **Microsoft Graph** → **Delegated permissions**
3. Add the following permissions:
   - `User.Read` - Sign in and read user profile
   - `User.ReadBasic.All` - Read all users' basic profiles
4. Click **Add permissions**
5. Click **Grant admin consent** (requires admin privileges)

### 3. Create Client Secret

1. Go to **Certificates & secrets**
2. Click **New client secret**
3. Add a description (e.g., "Travel Request App")
4. Set expiration (recommended: 24 months)
5. Click **Add**
6. **IMPORTANT**: Copy the secret **Value** immediately (you won't be able to see it again!)

### 4. Get Application IDs

From your app registration **Overview** page, copy:
- **Application (client) ID**
- **Directory (tenant) ID**

### 5. Configure Environment Variables

Edit `config/.env` file and add your Azure credentials:

```bash
export AZURE_CLIENT_ID="your-application-client-id-here"
export AZURE_CLIENT_SECRET="your-client-secret-value-here"
export AZURE_TENANT_ID="your-directory-tenant-id-here"
export AZURE_REDIRECT_URI="http://localhost:8765/auth/callback"
```

Common local dev URLs you can use as Redirect URIs (pick the one you actually browse with and configure it in BOTH places: Azure and `config/.env`):

- `http://localhost:8765/auth/callback`  ← CakePHP dev server (bin/cake server -p 8765)
- `http://localhost/travel_request/auth/callback`  ← XAMPP Apache under htdocs

**For production**, update the redirect URI to your production domain:
```bash
export AZURE_REDIRECT_URI="https://yourdomain.com/auth/callback"
```

And add that production URL to Azure App Registration → Authentication → Redirect URIs.

### 6. Test the Integration

1. Restart your CakePHP application
2. Navigate to `http://localhost:8765/auth/login` (or the base that matches your Redirect URI)
3. Click "Sign in with Microsoft"
4. You'll be redirected to Microsoft login
5. After successful login, you'll be redirected back to the application

## Features

### Authentication
- **Single Sign-On (SSO)** with Microsoft accounts
- No need for separate passwords
- Automatic user creation on first login
- Session management

### User Directory
- **Dynamic traveler list** fetched from Microsoft Graph
- Shows employee names and job titles
- Real-time data from your organization's directory
- No need to manually maintain user lists

## Troubleshooting

### Microsoft page says "We couldn't sign you in. Please try again." or keeps selecting a Windows account
- Make sure the Redirect URI in Azure exactly matches `AZURE_REDIRECT_URI` in `config/.env` (including port and path)
- Try a private/incognito window
- Click "Use another account" on the Microsoft picker and enter your work account for the configured tenant
- If the same Windows-connected account keeps getting auto-picked, remove stale accounts from Windows:
   - Windows Settings → Accounts → Email & accounts → Accounts used by other apps → remove any old work/school entries
- Sign out of Microsoft globally, then retry:
   - https://login.microsoftonline.com/common/oauth2/v2.0/logout
   - https://portal.office.com/SignOut.aspx

Note: The app now forces the account picker during login to avoid stuck Windows sessions.

### Loop returns to Microsoft without logging you in
- Check `logs/error.log` for lines starting with `[Auth] OAuth state mismatch`. If present, the session cookie was lost.
- Fixes:
   1. Make sure you always use the same base URL as in `AZURE_REDIRECT_URI`.
   2. Avoid switching between `localhost` and `127.0.0.1` mid-flow.
   3. Disable aggressive privacy/cookie extensions for the test.
   4. If needed add in `config/app_local.php` under `'Session'`: `['cookie' => 'travel_session', 'timeout' => 0]`.
   5. For Chrome/Edge, if running non-HTTPS, ensure no setting forces `SameSite=None` on insecure contexts.
- After a successful callback, the log should not show state mismatch.

### Database errors during first login
If you see `Connection to Mysql could not be established` the user creation fails and you will loop back to login. Ensure MySQL is running and that `DATABASE_URL` points to `127.0.0.1:3306` (updated in `.env`). Restart the app after changes.

#### Temporary SQLite Fallback
If local MySQL/MariaDB will not start (e.g. Aria recovery failures in XAMPP), the app can run against a local SQLite file so you can proceed with Microsoft login testing.

Steps:
1. Remove or comment out `DATABASE_URL` in `config/.env` (or set it blank).
2. Confirm `config/app_local.php` has the `Sqlite` driver pointing to `tmp/app.sqlite`.
3. Clear old cache: delete `tmp/cache/models/*` if schema mismatches occur.
4. Run migrations:
   ```powershell
   php bin/cake.php migrations migrate
   php bin/cake.php migrations seed
   ```
5. Login via `/auth/login` – user creation will now persist in the SQLite file.

Later, when MySQL is repaired, restore `DATABASE_URL`, drop the SQLite file, and re-run migrations on MySQL.

#### Repairing XAMPP MariaDB (Aria / mysql.plugin errors)
If `mysql_error.log` shows `Aria recovery failed` or `Could not open mysql.plugin table`:

1. Stop MySQL from XAMPP Control Panel.
2. Backup data directory: copy `C:\xampp\mysql\data` to `data_backup_YYYYMMDD`.
3. Delete all `aria_log.*` files in `data`.
4. Run (from `C:\xampp\mysql\bin`):
   ```powershell
   .\aria_chk.exe -r ..\data\aria_log_control
   ```
   (Ignore if file missing.)
5. If system tables still fail, move `data\mysql` out of the way and copy a pristine one from a fresh XAMPP download.
6. Start MySQL, recreate database:
   ```powershell
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS travel_requests CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```
7. Re-run migrations/seeds.
8. Test app login.


### "Not authenticated" error when loading travelers
- Make sure you're logged in first (`/auth/login`)
- Check if access token is valid in session
- Verify API permissions are granted in Azure

### "Failed to fetch users" error
- Check API permissions (User.ReadBasic.All)
- Ensure admin consent has been granted
- Verify client secret hasn't expired

### Redirect URI mismatch
- Ensure the redirect URI in Azure matches exactly with your `.env` configuration
- Include protocol (http/https) and port if applicable
- No trailing slashes

### Token expiration
- Access tokens expire after 1 hour
- Refresh tokens can be implemented for longer sessions
- For now, users will need to re-login after token expires

## Security Notes

1. **Never commit** `config/.env` to version control
2. **Rotate secrets** regularly (every 6-12 months)
3. **Use HTTPS** in production
4. **Limit API permissions** to only what's needed
5. **Monitor** app usage in Azure portal

## Production Deployment

1. Update redirect URI in both Azure and `.env`
2. Use HTTPS for all communications
3. Set appropriate token expiration policies
4. Enable audit logging in Azure AD
5. Implement proper error handling and logging

## Additional Resources

- [Microsoft Graph API Documentation](https://docs.microsoft.com/en-us/graph/)
- [Azure AD App Registration Guide](https://docs.microsoft.com/en-us/azure/active-directory/develop/quickstart-register-app)
- [Microsoft Graph Permissions Reference](https://docs.microsoft.com/en-us/graph/permissions-reference)
