<?php
declare(strict_types=1);

namespace App\Security;

class Rbac
{
    /**
     * Simple permission map per role.
     * Roles: admin, user, auditor, risk_assessment, treasurer, export, sales
     * Resource is a string key like 'Users', 'FreshInvoices', 'FinalInvoices', 'Reports', etc.
     * Action is a controller action like 'index','view','add','edit','delete', 'approve', etc.
     */
    protected static array $map = [
        'admin' => ['*' => ['*']], // Full access to everything
        
        'user' => [
            'FreshInvoices' => ['index', 'view', 'add', 'edit', 'delete', 'bulkUpload', 'downloadTemplate'],
            'FinalInvoices' => ['index', 'view', 'add', 'edit', 'delete', 'bulkUpload', 'downloadTemplate'],
            'Reports' => ['index', 'view'],
            'Clients' => ['index', 'view'],
            'Products' => ['index', 'view'],
            'Vessels' => ['index', 'view'],
            'Contracts' => ['index', 'view'],
            'SgcAccounts' => ['index', 'view'],
        ],
        
        'treasurer' => [
            // Can approve/reject invoices, view all invoices
            'FreshInvoices' => ['index', 'view', 'approve', 'reject', 'export'],
            'FinalInvoices' => ['index', 'view', 'approve', 'reject', 'export'],
            'Reports' => ['index', 'view'],
            'Clients' => ['index', 'view'],
            'Products' => ['index', 'view'],
            'Vessels' => ['index', 'view'],
            'Contracts' => ['index', 'view'],
            'SgcAccounts' => ['index', 'view'],
        ],
        
        'export' => [
            // Can view approved fresh invoices sent to export
            'FreshInvoices' => ['index', 'view', 'export'],
            'Reports' => ['index', 'view'],
            'Clients' => ['index', 'view'],
            'Products' => ['index', 'view'],
            'Vessels' => ['index', 'view'],
        ],
        
        'sales' => [
            // Can view approved final invoices sent to sales
            'FinalInvoices' => ['index', 'view', 'export'],
            'Reports' => ['index', 'view'],
            'Clients' => ['index', 'view'],
            'Products' => ['index', 'view'],
        ],
        
        'auditor' => [
            // View-only access to all data
            'FreshInvoices' => ['index', 'view', 'export'],
            'FinalInvoices' => ['index', 'view', 'export'],
            'Reports' => ['*'], // Full access to reports
            'AuditLogs' => ['*'], // Full access to audit logs
            'Clients' => ['index', 'view'],
            'Products' => ['index', 'view'],
            'Vessels' => ['index', 'view'],
            'Contracts' => ['index', 'view'],
            'SgcAccounts' => ['index', 'view'],
            'Users' => ['index', 'view'],
        ],
        
        'risk_assessment' => [
            // View-only access similar to auditor
            'FreshInvoices' => ['index', 'view', 'export'],
            'FinalInvoices' => ['index', 'view', 'export'],
            'Reports' => ['*'],
            'AuditLogs' => ['index', 'view'],
            'Clients' => ['index', 'view'],
            'Products' => ['index', 'view'],
            'Vessels' => ['index', 'view'],
            'Contracts' => ['index', 'view'],
            'SgcAccounts' => ['index', 'view'],
        ],
    ];

    public static function can(array $user, string $resource, string $action): bool
    {
        $role = $user['role'] ?? 'user';
        // Admin wildcard
        if (isset(self::$map[$role]['*']) && (in_array('*', self::$map[$role]['*'], true) || in_array($action, self::$map[$role]['*'], true))) {
            return true;
        }
        $allowed = self::$map[$role][$resource] ?? [];
        return in_array('*', $allowed, true) || in_array($action, $allowed, true);
    }
}
