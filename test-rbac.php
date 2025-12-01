<?php
/**
 * Simple RBAC test script
 * Run from command line: php test-rbac.php
 */

require 'vendor/autoload.php';

use App\Security\Rbac;

// Test scenarios
$testCases = [
    // Admin tests
    ['role' => 'admin', 'resource' => 'FreshInvoices', 'action' => 'add', 'expected' => true],
    ['role' => 'admin', 'resource' => 'Clients', 'action' => 'delete', 'expected' => true],
    ['role' => 'admin', 'resource' => 'Admin/Dashboard', 'action' => 'index', 'expected' => true],
    
    // User tests
    ['role' => 'user', 'resource' => 'FreshInvoices', 'action' => 'add', 'expected' => true],
    ['role' => 'user', 'resource' => 'FinalInvoices', 'action' => 'edit', 'expected' => true],
    ['role' => 'user', 'resource' => 'Clients', 'action' => 'index', 'expected' => true],
    ['role' => 'user', 'resource' => 'Clients', 'action' => 'add', 'expected' => false],
    ['role' => 'user', 'resource' => 'Reports', 'action' => 'index', 'expected' => true],
    
    // Auditor tests
    ['role' => 'auditor', 'resource' => 'FreshInvoices', 'action' => 'index', 'expected' => true],
    ['role' => 'auditor', 'resource' => 'FreshInvoices', 'action' => 'add', 'expected' => false],
    ['role' => 'auditor', 'resource' => 'FinalInvoices', 'action' => 'edit', 'expected' => false],
    ['role' => 'auditor', 'resource' => 'Reports', 'action' => 'index', 'expected' => true],
    ['role' => 'auditor', 'resource' => 'AuditLogs', 'action' => 'index', 'expected' => true],
    
    // Risk Assessment tests
    ['role' => 'risk_assessment', 'resource' => 'Clients', 'action' => 'view', 'expected' => true],
    ['role' => 'risk_assessment', 'resource' => 'Clients', 'action' => 'delete', 'expected' => false],
    ['role' => 'risk_assessment', 'resource' => 'AuditLogs', 'action' => 'index', 'expected' => true],
    ['role' => 'risk_assessment', 'resource' => 'AuditLogs', 'action' => 'delete', 'expected' => false],
];

echo "====================================\n";
echo "RBAC Authorization Tests\n";
echo "====================================\n\n";

$passed = 0;
$failed = 0;

foreach ($testCases as $test) {
    $user = ['role' => $test['role']];
    $result = Rbac::can($user, $test['resource'], $test['action']);
    $status = $result === $test['expected'] ? '✓ PASS' : '✗ FAIL';
    
    if ($result === $test['expected']) {
        $passed++;
    } else {
        $failed++;
    }
    
    printf(
        "%s | %s %-20s %-30s %-15s (expected: %s, got: %s)\n",
        $status,
        $test['role'],
        $test['resource'],
        $test['action'],
        $result ? 'ALLOWED' : 'DENIED',
        $test['expected'] ? 'ALLOWED' : 'DENIED',
        $result ? 'ALLOWED' : 'DENIED'
    );
}

echo "\n====================================\n";
echo "Results: {$passed} passed, {$failed} failed\n";
echo "====================================\n";

exit($failed > 0 ? 1 : 0);
