# Admin System Documentation

## Overview
This document describes the complete Role-Based Access Control (RBAC) system implemented for the Treasury Invoice Management application.

## Architecture

### 1. RBAC Permission System (`src/Security/Rbac.php`)

The core permission system defines 4 roles with granular permissions:

#### Roles

**Admin (`admin`)**
- Full wildcard access to all resources and actions
- Can access Admin area (`/admin/*`)
- Permission: `['*' => ['*']]`

**User (`user`)**
- Full CRUD on Fresh Invoices and Final Invoices
- Includes bulk upload and template download
- Read-only access to master data (Clients, Products, Vessels, Contracts, SGC Accounts)
- Can view Reports
- Cannot access Admin area

**Auditor (`auditor`)**
- Read-only access to all entities (index, view, export)
- Full access to Reports and Audit Logs
- Can view Users list
- Cannot create, edit, or delete any records
- Cannot access Admin area

**Risk Assessment (`risk_assessment`)**
- Read-only access to all entities (index, view, export)
- Full access to Reports
- Limited Audit Logs access (index, view only - no delete)
- Cannot create, edit, or delete any records
- Cannot access Admin area

### 2. Authorization Flow

```
User Request → AppController.beforeFilter() 
  ↓
Check if public endpoint (Auth, Pages/home)
  ↓ (if protected)
Check if authenticated
  ↓ (if yes)
AppController.isAuthorized() → Rbac::can($user, $controller, $action)
  ↓
Authorization Component (for resource-level checks)
  ↓
Allow or Deny
```

### 3. Components

#### AppController (`src/Controller/AppController.php`)
- Implements `beforeFilter()` for authentication checks
- Implements `isAuthorized($user)` for permission checks using `Rbac::can()`
- Loads Authorization component for resource-level checks
- Exposes `$authUser` to all views

#### AppAdminController (`src/Controller/Admin/AppAdminController.php`)
- Base controller for all Admin area controllers
- Overrides `isAuthorized()` to require admin role only
- Uses 'admin' layout
- All Admin controllers extend this class

#### Authorization Component (`src/Controller/Component/AuthorizationComponent.php`)
- Integrates with RBAC for base permission checks
- Adds resource-level checks (e.g., can only edit draft invoices)
- Method: `can($action, $controller, $user, $resource = null)`
- Calls `Rbac::can()` first, then applies entity-specific rules

### 4. Database Schema

#### audit_logs table
Created by migration `20251113095129_CreateRolesAndPermissions.php`

```sql
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(50) NOT NULL,
    model VARCHAR(100) NOT NULL,
    record_id INT NULL,
    old_values TEXT NULL,
    new_values TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    created DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_model_record (model, record_id),
    INDEX idx_action (action),
    INDEX idx_created (created)
);
```

#### users table
- Existing table with `role` column (STRING field)
- Valid roles: 'admin', 'user', 'auditor', 'risk_assessment', 'treasurer', 'export', 'sales'

## Admin Dashboard

### Features
Located at `/admin/dashboard/index` (admin role only)

**Statistics Display:**
- 8 KPI cards: Users, Fresh Invoices, Final Invoices, Clients, Products, Vessels, Contracts, SGC Accounts
- Fresh Invoice status breakdown (Draft, Pending, Approved, Rejected, Sent to Export)
- Final Invoice status breakdown (Draft, Pending, Approved, Rejected, Sent to Sales)
- Recent Fresh Invoices table (last 10)
- Recent Users table (last 5)

**Management Menu:**
- Quick links to 8 admin modules:
  - Users Management → `/admin/users/index`
  - Clients Management → `/admin/clients/index`
  - Products Management → `/admin/products/index`
  - Vessels Management → `/admin/vessels/index`
  - Contracts Management → `/admin/contracts/index`
  - SGC Accounts Management → `/admin/sgc-accounts/index`
  - Audit Logs → `/admin/audit-logs/index`
  - Settings → `/admin/settings/index`

**Access:**
- Admin button appears on landing page only for users with `role='admin'`
- Button styled with orange gradient, links to `/admin/dashboard/index`

### Controller (`src/Controller/Admin/DashboardController.php`)
- Loads 8 models: Users, FreshInvoices, FinalInvoices, Clients, Products, Vessels, Contracts, SgcAccounts
- Generates counts for all entities
- Groups invoices by status using SQL GROUP BY
- Fetches recent activity

### View (`templates/Admin/Dashboard/index.php`)
- Modern responsive UI with CSS Grid
- Color-coded status badges
- Hover effects and animations
- Empty state handling

## Permission Matrix

| Resource | Action | Admin | User | Auditor | Risk Assessment |
|----------|--------|-------|------|---------|-----------------|
| FreshInvoices | index | ✅ | ✅ | ✅ | ✅ |
| FreshInvoices | view | ✅ | ✅ | ✅ | ✅ |
| FreshInvoices | add | ✅ | ✅ | ❌ | ❌ |
| FreshInvoices | edit | ✅ | ✅ | ❌ | ❌ |
| FreshInvoices | delete | ✅ | ✅ | ❌ | ❌ |
| FreshInvoices | bulkUpload | ✅ | ✅ | ❌ | ❌ |
| FreshInvoices | downloadTemplate | ✅ | ✅ | ❌ | ❌ |
| FinalInvoices | (all above) | ✅ | ✅ | ❌ | ❌ |
| Clients | index | ✅ | ✅ | ✅ | ✅ |
| Clients | view | ✅ | ✅ | ✅ | ✅ |
| Clients | add | ✅ | ❌ | ❌ | ❌ |
| Clients | edit | ✅ | ❌ | ❌ | ❌ |
| Clients | delete | ✅ | ❌ | ❌ | ❌ |
| Products | (same as Clients) | ✅ | ❌ | ❌ | ❌ |
| Vessels | (same as Clients) | ✅ | ❌ | ❌ | ❌ |
| Contracts | (same as Clients) | ✅ | ❌ | ❌ | ❌ |
| SgcAccounts | (same as Clients) | ✅ | ❌ | ❌ | ❌ |
| Reports | index | ✅ | ✅ | ✅ | ✅ |
| Reports | view | ✅ | ✅ | ✅ | ✅ |
| Reports | * (all actions) | ✅ | ❌ | ✅ | ✅ |
| AuditLogs | index | ✅ | ❌ | ✅ | ✅ |
| AuditLogs | view | ✅ | ❌ | ✅ | ✅ |
| AuditLogs | delete | ✅ | ❌ | ✅ | ❌ |
| AuditLogs | export | ✅ | ❌ | ✅ | ❌ |
| Admin/* | * (all) | ✅ | ❌ | ❌ | ❌ |

## Usage Examples

### Check Permission in Controller
```php
// In any controller
public function edit($id)
{
    $invoice = $this->FreshInvoices->get($id);
    
    // Check if user can edit (with resource-level check)
    if (!$this->Authorization->can('edit', 'FreshInvoices', $this->getUser(), $invoice)) {
        $this->Flash->error('You cannot edit this invoice.');
        return $this->redirect(['action' => 'index']);
    }
    
    // ... proceed with edit
}
```

### Check Permission in Template
```php
<!-- In any template -->
<?php if (\App\Security\Rbac::can($authUser, 'Clients', 'add')): ?>
    <?= $this->Html->link('Add Client', ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
<?php endif; ?>
```

### Get User Role
```php
// In controller
$user = $this->getUser();
$role = $user['role'] ?? 'user';

// In template
$role = $authUser['role'] ?? 'user';
$isAdmin = $role === 'admin';
```

## Testing

### Manual Testing
1. Create test users with different roles:
   ```sql
   UPDATE users SET role='auditor' WHERE email='auditor@example.com';
   UPDATE users SET role='risk_assessment' WHERE email='risk@example.com';
   UPDATE users SET role='admin' WHERE email='admin@example.com';
   ```

2. Login as each user and verify:
   - Admin sees "Admin Dashboard" button
   - Auditor can view all data but cannot edit/delete
   - Risk Assessment can view data and audit logs (limited)
   - User can CRUD invoices but not master data

### Automated Testing
Run the RBAC test script:
```bash
php test-rbac.php
```

All 17 tests should pass:
- 3 admin tests (full access)
- 5 user tests (invoice CRUD, read-only master data)
- 5 auditor tests (read-only + audit logs)
- 4 risk assessment tests (read-only + limited audit)

## Implementation Checklist

### Completed ✅
- [x] Created audit_logs table migration
- [x] Updated Rbac.php with 4 roles and permission mappings
- [x] Implemented isAuthorized() in AppController using Rbac::can()
- [x] Implemented isAuthorized() in AppAdminController (admin-only)
- [x] Updated Authorization Component to integrate with RBAC
- [x] Created Admin Dashboard controller with statistics
- [x] Built modern Admin Dashboard UI
- [x] Added conditional Admin button to landing page
- [x] Created RBAC test suite (17 tests, all passing)

### Pending 🔲
- [ ] Build User Management CRUD (assign roles, activate/deactivate)
- [ ] Build Audit Logs Viewer (search, filter, export)
- [ ] Implement Audit Logging Behavior (auto-track CUD operations)
- [ ] Build Clients Management CRUD
- [ ] Build Products Management CRUD
- [ ] Build Vessels Management CRUD
- [ ] Build Contracts Management CRUD
- [ ] Build SGC Accounts Management CRUD
- [ ] Add UI indicators for read-only fields (auditor/risk assessment views)
- [ ] Implement audit log export functionality
- [ ] Add role-based navigation menu filtering

## Security Considerations

1. **Session Management:** User data stored in `Auth.User` session key
2. **Password Authentication:** Microsoft Azure AD integration (existing)
3. **Authorization Logging:** Failed authorization attempts logged to debug log
4. **SQL Injection Prevention:** CakePHP ORM with parameterized queries
5. **CSRF Protection:** CakePHP built-in CSRF component (enabled by default)
6. **Audit Trail:** All CUD operations will be logged to audit_logs table (pending behavior implementation)

## Troubleshooting

### "Access Denied" errors
1. Check user role in database: `SELECT id, email, role FROM users WHERE email='user@example.com';`
2. Verify Rbac.php contains permission for that role/resource/action
3. Check debug log for RBAC warnings: `tail -f logs/debug.log | grep RBAC`

### Admin Dashboard not showing
1. Verify user has `role='admin'` (case-sensitive)
2. Check Admin button visibility on home page (only for admin role)
3. Verify AppAdminController.beforeFilter() not redirecting

### Authorization Component conflicts
1. The component integrates with RBAC - it doesn't replace it
2. RBAC checks happen first, then resource-level checks
3. If RBAC denies, resource check never runs

## Future Enhancements

1. **Dynamic Permissions:** Move permissions from Rbac.php to database table
2. **Permission Groups:** Create reusable permission sets (e.g., "Master Data Manager")
3. **Temporary Permissions:** Allow time-based permission grants
4. **2FA for Admin:** Require two-factor authentication for admin role
5. **Activity Dashboard:** Real-time view of user activity and permission usage
6. **Permission Audit:** Track permission changes over time
7. **Role Hierarchy:** Implement role inheritance (e.g., admin inherits user permissions)

## Maintenance

### Adding New Role
1. Add role to Rbac.php `$map` array with permissions
2. Update RBAC test suite with new role test cases
3. Update this documentation
4. Test all permission combinations

### Adding New Resource
1. Add resource to relevant role permission maps in Rbac.php
2. Create controller with `isAuthorized()` if needed
3. Update permission matrix in this doc
4. Add test cases

### Modifying Permissions
1. Update Rbac.php permission map
2. Clear cache: `bin/cake cache clear_all`
3. Run RBAC tests: `php test-rbac.php`
4. Test manually with affected roles
