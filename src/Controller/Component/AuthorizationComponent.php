<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Authorization component for role-based access control
 * 
 * Roles:
 * - user: Can create and view own invoices
 * - treasurer: Can approve/reject all invoices
 * - export: Can view fresh invoices sent to Export
 * - sales: Can view final invoices sent to Sales
 * - admin: Full access to everything
 */
class AuthorizationComponent extends Component
{
    /**
     * Check if user can perform action
     *
     * @param string $action Action name
     * @param string $controller Controller name
     * @param array $user User data from session
     * @param mixed $resource Optional resource (e.g., invoice entity)
     * @return bool
     */
    public function can(string $action, string $controller, array $user, $resource = null): bool
    {
        $role = $user['role'] ?? 'user';
        
        // Admin has full access
        if ($role === 'admin') {
            return true;
        }
        
        // Controller-specific permissions
        if ($controller === 'FreshInvoices') {
            return $this->canAccessFreshInvoices($action, $role, $user, $resource);
        }
        
        if ($controller === 'FinalInvoices') {
            return $this->canAccessFinalInvoices($action, $role, $user, $resource);
        }
        
        // Default deny
        return false;
    }
    
    /**
     * Check Fresh Invoices permissions
     *
     * @param string $action Action name
     * @param string $role User role
     * @param array $user User data
     * @param mixed $resource Fresh invoice entity
     * @return bool
     */
    protected function canAccessFreshInvoices(string $action, string $role, array $user, $resource = null): bool
    {
        switch ($action) {
            case 'index':
            case 'view':
                // Everyone can view (with filtering applied in controller)
                return true;
                
            case 'add':
                // Only users and admins can create
                return in_array($role, ['user', 'admin']);
                
            case 'edit':
                // Can edit if status is draft and user created it
                if ($resource && isset($resource->status)) {
                    return $resource->status === 'draft' && in_array($role, ['user', 'admin']);
                }
                return in_array($role, ['user', 'admin']);
                
            case 'delete':
                // Can delete if status is draft
                if ($resource && isset($resource->status)) {
                    return $resource->status === 'draft' && in_array($role, ['user', 'admin']);
                }
                return in_array($role, ['user', 'admin']);
                
            case 'submitForApproval':
                // Users can submit their own drafts
                return in_array($role, ['user', 'admin']);
                
            case 'treasurerApprove':
            case 'treasurerReject':
                // Only treasurer can approve/reject
                return $role === 'treasurer';
                
            case 'sendToExport':
                // Only treasurer or admin can send to export
                return in_array($role, ['treasurer', 'admin']);
                
            default:
                return false;
        }
    }
    
    /**
     * Check Final Invoices permissions
     *
     * @param string $action Action name
     * @param string $role User role
     * @param array $user User data
     * @param mixed $resource Final invoice entity
     * @return bool
     */
    protected function canAccessFinalInvoices(string $action, string $role, array $user, $resource = null): bool
    {
        switch ($action) {
            case 'index':
            case 'view':
                // Everyone can view (with filtering applied in controller)
                return true;
                
            case 'add':
                // Only users and admins can create
                return in_array($role, ['user', 'admin']);
                
            case 'edit':
                // Can edit if status is draft
                if ($resource && isset($resource->status)) {
                    return $resource->status === 'draft' && in_array($role, ['user', 'admin']);
                }
                return in_array($role, ['user', 'admin']);
                
            case 'delete':
                // Can delete if status is draft
                if ($resource && isset($resource->status)) {
                    return $resource->status === 'draft' && in_array($role, ['user', 'admin']);
                }
                return in_array($role, ['user', 'admin']);
                
            case 'submitForApproval':
                // Users can submit their own drafts
                return in_array($role, ['user', 'admin']);
                
            case 'treasurerApprove':
            case 'treasurerReject':
                // Only treasurer can approve/reject
                return $role === 'treasurer';
                
            case 'sendToSales':
                // Only treasurer or admin can send to sales
                return in_array($role, ['treasurer', 'admin']);
                
            default:
                return false;
        }
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
        
        // Admin and treasurer see everything
        if (in_array($role, ['admin', 'treasurer'])) {
            return null; // null means all statuses
        }
        
        if ($controller === 'FreshInvoices') {
            if ($role === 'export') {
                // Export can only see invoices sent to them
                return ['sent_to_export'];
            }
            // Regular users see their own invoices (filtered by user_id in controller)
            return null;
        }
        
        if ($controller === 'FinalInvoices') {
            if ($role === 'sales') {
                // Sales can only see invoices sent to them
                return ['sent_to_sales'];
            }
            // Regular users see their own invoices (filtered by user_id in controller)
            return null;
        }
        
        return [];
    }
}
