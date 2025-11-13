# Authentication & Authorization Setup

## Overview

The Treasury Invoicing System now has a complete authentication and authorization system with role-based access control (RBAC). Users authenticate via Microsoft Azure AD, and their roles determine what actions they can perform.

---

## User Roles

The system supports 5 distinct roles:

### 1. **User** (Default)
- Can create fresh and final invoices
- Can edit/delete their own invoices (when status = draft)
- Can submit invoices for treasurer approval
- Can view their own invoices

### 2. **Treasurer**
- Can approve or reject all invoices
- Can send approved fresh invoices to Export department
- Can send approved final invoices to Sales department
- Has full visibility of all invoices
- **Key Actions**: `treasurerApprove`, `treasurerReject`, `sendToExport`, `sendToSales`

### 3. **Export**
- Can view fresh invoices that have been sent to Export department
- Read-only access to invoices with status = `sent_to_export`
- Cannot create, edit, or approve invoices

### 4. **Sales**
- Can view final invoices that have been sent to Sales department
- Read-only access to invoices with status = `sent_to_sales`
- Cannot create, edit, or approve invoices

### 5. **Admin**
- Full access to all features and all invoices
- Can perform any action regardless of invoice status
- Superuser with no restrictions

---

## Database Schema

### Users Table

```sql
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    microsoft_id VARCHAR(255),
    role VARCHAR(50) NOT NULL DEFAULT 'user',
    is_active BOOLEAN NOT NULL DEFAULT 1,
    last_login DATETIME,
    created DATETIME,
    modified DATETIME,
    INDEX idx_email (email),
    INDEX idx_microsoft_id (microsoft_id),
    INDEX idx_role (role)
);
```

**Role Values**: `user`, `treasurer`, `export`, `sales`, `admin`

---

## Authentication Flow

### 1. Login Process

```
User clicks "Sign in with Microsoft"
    ↓
Redirected to Microsoft Azure AD OAuth2 endpoint
    ↓
User authenticates with Microsoft credentials
    ↓
Microsoft redirects back to /auth/callback with authorization code
    ↓
System exchanges code for access token
    ↓
System fetches user profile from Microsoft Graph API
    ↓
System finds or creates user in database
    ↓
User session established with user data
    ↓
Redirect to Fresh Invoices dashboard
```

### 2. Auto-Role Assignment

- First-time users are assigned the **user** role by default
- Users listed in `config/.env` `ADMIN_EMAILS` are automatically assigned **admin** role
- Roles can be manually updated in the database:

```sql
UPDATE users SET role = 'treasurer' WHERE email = 'treasurer@company.com';
UPDATE users SET role = 'export' WHERE email = 'export@company.com';
UPDATE users SET role = 'sales' WHERE email = 'sales@company.com';
```

### 3. Session Management

User data stored in session:
```php
$_SESSION['Auth']['User'] = [
    'id' => 1,
    'email' => 'user@company.com',
    'name' => 'John Doe',
    'role' => 'treasurer',
    'microsoft_id' => 'abc123...'
];
```

---

## Authorization System

### Authorization Component

Location: `src/Controller/Component/AuthorizationComponent.php`

The Authorization component provides centralized permission checking:

```php
// Check if user can perform action
$canApprove = $this->Authorization->can(
    'treasurerApprove',    // action
    'FreshInvoices',       // controller
    $authUser,             // user data from session
    $invoice               // resource (optional)
);
```

### Permission Matrix

#### Fresh Invoices

| Action | User | Treasurer | Export | Sales | Admin |
|--------|------|-----------|--------|-------|-------|
| index | ✓ (own) | ✓ (all) | ✓ (sent) | ✗ | ✓ |
| view | ✓ (own) | ✓ (all) | ✓ (sent) | ✗ | ✓ |
| add | ✓ | ✗ | ✗ | ✗ | ✓ |
| edit | ✓ (draft) | ✗ | ✗ | ✗ | ✓ |
| delete | ✓ (draft) | ✗ | ✗ | ✗ | ✓ |
| submitForApproval | ✓ | ✗ | ✗ | ✗ | ✓ |
| treasurerApprove | ✗ | ✓ | ✗ | ✗ | ✓ |
| treasurerReject | ✗ | ✓ | ✗ | ✗ | ✓ |
| sendToExport | ✗ | ✓ | ✗ | ✗ | ✓ |

#### Final Invoices

| Action | User | Treasurer | Export | Sales | Admin |
|--------|------|-----------|--------|-------|-------|
| index | ✓ (own) | ✓ (all) | ✗ | ✓ (sent) | ✓ |
| view | ✓ (own) | ✓ (all) | ✗ | ✓ (sent) | ✓ |
| add | ✓ | ✗ | ✗ | ✗ | ✓ |
| edit | ✓ (draft) | ✗ | ✗ | ✗ | ✓ |
| delete | ✓ (draft) | ✗ | ✗ | ✗ | ✓ |
| submitForApproval | ✓ | ✗ | ✗ | ✗ | ✓ |
| treasurerApprove | ✗ | ✓ | ✗ | ✗ | ✓ |
| treasurerReject | ✗ | ✓ | ✗ | ✗ | ✓ |
| sendToSales | ✗ | ✓ | ✗ | ✗ | ✓ |

**Legend**:
- ✓ = Allowed
- ✗ = Denied
- (own) = Only own invoices
- (all) = All invoices
- (draft) = Only when status is draft
- (sent) = Only when sent to their department

---

## Status-Based Permissions

Invoice actions are also restricted by invoice status:

### Fresh Invoice Workflow

```
draft → pending_approval → approved → sent_to_export
```

- **draft**: Can be edited/deleted by creator
- **pending_approval**: Can be approved/rejected by treasurer
- **approved**: Can be sent to Export by treasurer
- **sent_to_export**: Read-only, visible to Export role

### Final Invoice Workflow

```
draft → pending_approval → approved → sent_to_sales
```

- **draft**: Can be edited/deleted by creator
- **pending_approval**: Can be approved/rejected by treasurer
- **approved**: Can be sent to Sales by treasurer
- **sent_to_sales**: Read-only, visible to Sales role

---

## Implementation in Controllers

### Example: FreshInvoicesController

```php
public function treasurerApprove($id = null)
{
    // Check authorization
    if (!$this->Authorization->can('treasurerApprove', 'FreshInvoices', $this->authUser)) {
        $this->Flash->error('You do not have permission to approve invoices.');
        return $this->redirect(['action' => 'index']);
    }
    
    // Proceed with approval logic
    $invoice = $this->FreshInvoices->get($id);
    
    // Only approve if status is pending_approval
    if ($invoice->status !== 'pending_approval') {
        $this->Flash->error('This invoice cannot be approved at this time.');
        return $this->redirect(['action' => 'view', $id]);
    }
    
    // Update status
    $invoice->status = 'approved';
    $invoice->treasurer_notes = $this->request->getData('treasurer_notes');
    
    if ($this->FreshInvoices->save($invoice)) {
        $this->Flash->success('Invoice approved successfully.');
    }
    
    return $this->redirect(['action' => 'view', $id]);
}
```

### Filtering by Role

```php
public function index()
{
    $query = $this->FreshInvoices->find();
    
    // Apply role-based filtering
    $accessibleStatuses = $this->Authorization->getAccessibleStatuses(
        'FreshInvoices', 
        $this->authUser
    );
    
    if ($accessibleStatuses !== null) {
        // Limit to specific statuses
        $query->where(['status IN' => $accessibleStatuses]);
    }
    
    // For regular users, show only own invoices
    if ($this->authUser['role'] === 'user') {
        $query->where(['created_by' => $this->authUser['id']]);
    }
    
    $invoices = $this->paginate($query);
    $this->set(compact('invoices'));
}
```

---

## Configuration

### Microsoft Azure AD Setup

1. **Environment Variables** (`.env` file):

```bash
# Microsoft Azure AD Configuration
export AZURE_CLIENT_ID="your-client-id"
export AZURE_CLIENT_SECRET="your-client-secret"
export AZURE_TENANT_ID="your-tenant-id"
export AZURE_REDIRECT_URI="http://localhost:8765/auth/callback"
export AZURE_SCOPES="openid profile email offline_access User.Read"

# Admin Emails (comma-separated)
export ADMIN_EMAILS="admin@company.com,manager@company.com"
```

2. **Azure Portal Configuration**:
   - Register application in Azure AD
   - Add redirect URI: `http://localhost:8765/auth/callback`
   - Grant API permissions: `User.Read`
   - Create client secret

---

## Security Features

### 1. CSRF Protection
- State parameter validation during OAuth flow
- Session-based state verification

### 2. Session Security
- User data stored in encrypted session
- Access token stored securely in session
- Automatic session expiration

### 3. Protected Routes
- All routes except `/`, `/auth/*`, and `/pages/display/home` require authentication
- Unauthorized access redirects to login with saved redirect URL
- Login loops prevented with smart redirect handling

### 4. SQL Injection Prevention
- CakePHP ORM with parameterized queries
- Input validation and sanitization

### 5. XSS Protection
- Output escaping in templates
- Content Security Policy headers (recommended)

---

## Testing Authentication & Authorization

### 1. Test User Creation

When you first login with Microsoft, a user is created automatically. To assign roles:

```sql
-- View all users
SELECT id, email, role, is_active FROM users;

-- Assign treasurer role
UPDATE users SET role = 'treasurer' WHERE email = 'treasurer@company.com';

-- Assign export role
UPDATE users SET role = 'export' WHERE email = 'export@company.com';

-- Assign sales role
UPDATE users SET role = 'sales' WHERE email = 'sales@company.com';

-- Assign admin role
UPDATE users SET role = 'admin' WHERE email = 'admin@company.com';
```

### 2. Test Scenarios

#### Scenario 1: User Creates Invoice
1. Login as user
2. Navigate to Fresh Invoices → Add
3. Create a draft invoice
4. Submit for approval
5. Verify user cannot approve their own invoice

#### Scenario 2: Treasurer Approves Invoice
1. Login as treasurer
2. View pending invoice
3. Approve invoice
4. Send to Export department
5. Verify status updated to `sent_to_export`

#### Scenario 3: Export Views Invoice
1. Login as export user
2. View Fresh Invoices list
3. Verify only `sent_to_export` invoices visible
4. Verify cannot edit or approve

#### Scenario 4: Admin Full Access
1. Login as admin
2. Verify can perform all actions
3. Can edit any invoice regardless of status
4. Can approve and send invoices

---

## Troubleshooting

### Issue: User Not Assigned Role

**Problem**: New user gets default "user" role instead of intended role

**Solution**:
```sql
UPDATE users SET role = 'treasurer' WHERE email = 'user@company.com';
```

### Issue: Permission Denied

**Problem**: User sees "You do not have permission" error

**Check**:
1. Verify user's role: `SELECT email, role FROM users WHERE email = 'user@company.com';`
2. Check invoice status matches allowed actions
3. Verify authorization logic in `AuthorizationComponent`

### Issue: Login Redirects to Wrong Page

**Problem**: After login, redirected to unexpected page

**Solution**: Clear saved redirect in session or update AuthController default redirect:
```php
// In AuthController::callback()
return $this->redirect(['controller' => 'FreshInvoices', 'action' => 'index']);
```

---

## Next Steps

### Recommended Enhancements

1. **User Management UI**
   - Admin interface to manage users
   - Ability to change user roles
   - Deactivate/activate users

2. **Audit Trail**
   - Log who approved/rejected invoices
   - Track status changes with timestamps
   - Record user actions for compliance

3. **Email Notifications**
   - Notify treasurer when invoice submitted
   - Notify creator when invoice approved/rejected
   - Notify departments when invoice sent to them

4. **Advanced Permissions**
   - Department-based filtering
   - Client-based access control
   - Delegate approval permissions

5. **Multi-Factor Authentication**
   - Require MFA for treasurer and admin roles
   - Azure AD Conditional Access policies

---

## API Reference

### Authorization Component Methods

#### `can(string $action, string $controller, array $user, $resource = null): bool`

Check if user can perform action.

**Parameters**:
- `$action`: Action name (e.g., 'treasurerApprove')
- `$controller`: Controller name (e.g., 'FreshInvoices')
- `$user`: User data from session
- `$resource`: Optional invoice entity for context

**Returns**: `true` if authorized, `false` otherwise

**Example**:
```php
if ($this->Authorization->can('edit', 'FreshInvoices', $authUser, $invoice)) {
    // Allow edit
}
```

#### `getAccessibleStatuses(string $controller, array $user): ?array`

Get invoice statuses accessible to user based on role.

**Parameters**:
- `$controller`: Controller name
- `$user`: User data from session

**Returns**: Array of statuses or `null` for all statuses

**Example**:
```php
$statuses = $this->Authorization->getAccessibleStatuses('FreshInvoices', $authUser);
// For export role: ['sent_to_export']
// For admin/treasurer: null (all)
```

---

## Files Created/Modified

### Created:
1. `config/Migrations/20251112000008_CreateUsers.php` - Users table migration
2. `src/Controller/Component/AuthorizationComponent.php` - RBAC component

### Modified:
1. `src/Model/Entity/User.php` - Updated for new schema
2. `src/Model/Table/UsersTable.php` - Generated via bake
3. `src/Controller/AuthController.php` - Updated redirect to FreshInvoices
4. `src/Controller/AppController.php` - Added Authorization component

### Database:
- `users` table created with role-based access fields

---

## Summary

✅ **Authentication**: Microsoft Azure AD OAuth2 integration
✅ **Authorization**: Role-based access control (5 roles)
✅ **Permissions**: Action and status-based restrictions
✅ **Security**: CSRF protection, session security, input validation
✅ **Flexibility**: Easy to extend and customize

The system is now ready for production use with comprehensive authentication and authorization!
