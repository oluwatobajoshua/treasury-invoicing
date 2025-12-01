<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use App\Security\Rbac;

/**
 * Authorization component for role-based access control
 * 
 * Integrates with RBAC system for permissions while maintaining
 * invoice workflow-specific logic.
 * 
 * Roles:
 * - admin: Full access to everything (from RBAC)
 * - user: Can create and manage invoices (from RBAC)
 * - auditor: Read-only access + audit logs (from RBAC)
 * - risk_assessment: Read-only access + limited audit access (from RBAC)
 * - treasurer: Can approve/reject all invoices (legacy role)
 * - export: Can view fresh invoices sent to Export (legacy role)
 * - sales: Can view final invoices sent to Sales (legacy role)
 */
class AuthorizationComponent extends Component
{
    /**
     * Check if user can perform action using RBAC system
     *
     * @param string $action Action name
     * @param string $controller Controller name
     * @param array $user User data from session
     * @param mixed $resource Optional resource (e.g., invoice entity)
     * @return bool
     */
    public function can(string $action, string $controller, array $user, $resource = null): bool
    {
        // First check RBAC permissions
        $rbacAllowed = Rbac::can($user, $controller, $action);
        
        // If RBAC denies, immediately deny
        if (!$rbacAllowed) {
            return false;
        }
        
        // If RBAC allows and no resource-specific check needed, allow
        if (!$resource) {
            return true;
        }
        
        // For invoice controllers, apply additional resource-level checks
        if ($controller === 'FreshInvoices') {
            return $this->canAccessFreshInvoiceResource($action, $user, $resource);
        }
        
        if ($controller === 'FinalInvoices') {
            return $this->canAccessFinalInvoiceResource($action, $user, $resource);
        }
        
        // Default: RBAC allowed and no additional restrictions
        return true;
    }
    
    /**
     * Resource-level permission check for Fresh Invoices
     * (e.g., can only edit draft status)
     *
     * @param string $action Action name
     * @param array $user User data
     * @param mixed $resource Fresh invoice entity
     * @return bool
     */
    protected function canAccessFreshInvoiceResource(string $action, array $user, $resource): bool
    {
        $role = $user['role'] ?? 'user';
        
        // Admin bypasses all resource-level checks
        if ($role === 'admin') {
            return true;
        }
        
        // Auditor and risk_assessment are read-only (RBAC already checked)
        if (in_array($role, ['auditor', 'risk_assessment'])) {
            return in_array($action, ['index', 'view']);
        }
        
        // For edit/delete actions, check if invoice is in draft status
        if (in_array($action, ['edit', 'delete'])) {
            if ($resource && isset($resource->status)) {
                return $resource->status === 'draft';
            }
        }
        
        return true;
    }
    
    /**
     * Resource-level permission check for Final Invoices
     *
     * @param string $action Action name
     * @param array $user User data
     * @param mixed $resource Final invoice entity
     * @return bool
     */
    protected function canAccessFinalInvoiceResource(string $action, array $user, $resource): bool
    {
        $role = $user['role'] ?? 'user';
        
        // Admin bypasses all resource-level checks
        if ($role === 'admin') {
            return true;
        }
        
        // Auditor and risk_assessment are read-only
        if (in_array($role, ['auditor', 'risk_assessment'])) {
            return in_array($action, ['index', 'view']);
        }
        
        // For edit/delete actions, check if invoice is in draft status
        if (in_array($action, ['edit', 'delete'])) {
            if ($resource && isset($resource->status)) {
                return $resource->status === 'draft';
            }
        }
        
        return true;
    }
    
    /**
     * Get user's accessible invoice statuses for filtering
     *
     * @param string $controller Controller name
     * @param array $user User data
     * @return array|null Array of statuses or null for all
     */
    public function getAccessibleStatuses(string $controller, array $user): ?array
    {
        $role = $user['role'] ?? 'user';
        
        // Admin, auditor, and risk_assessment see everything
        if (in_array($role, ['admin', 'auditor', 'risk_assessment', 'treasurer'])) {
            return null; // null means all statuses
        }
        
        if ($controller === 'FreshInvoices') {
            if ($role === 'export') {
                return ['sent_to_export'];
            }
            // Regular users see their own invoices (filtered by user_id in controller)
            return null;
        }
        
        if ($controller === 'FinalInvoices') {
            if ($role === 'sales') {
                return ['sent_to_sales'];
            }
            // Regular users see their own invoices (filtered by user_id in controller)
            return null;
        }
        
        return [];
    }
}
