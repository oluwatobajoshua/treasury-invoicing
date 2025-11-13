<?php
/**
 * End-to-End Test Script for Treasury Invoicing System
 * 
 * This script tests the complete workflow:
 * 1. Verify database setup and sample data
 * 2. Create a fresh invoice
 * 3. Test fresh invoice approval workflow
 * 4. Create a final invoice from fresh invoice
 * 5. Test final invoice approval workflow
 * 6. Verify complete audit trail
 */

require 'vendor/autoload.php';

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

// Bootstrap the application
require 'config/bootstrap.php';

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "  TREASURY INVOICING SYSTEM - END-TO-END TEST\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "\n";

$connection = ConnectionManager::get('default');
$allTestsPassed = true;

// Test 1: Verify Database Setup
echo "📋 TEST 1: Verify Database and Sample Data\n";
echo "───────────────────────────────────────────────────────────────────\n";

try {
    $tables = ['clients', 'products', 'vessels', 'sgc_accounts', 'contracts', 'fresh_invoices', 'final_invoices', 'phinxlog'];
    
    foreach ($tables as $table) {
        $result = $connection->execute("SHOW TABLES LIKE '$table'")->fetch();
        if ($result) {
            echo "  ✓ Table '$table' exists\n";
        } else {
            echo "  ✗ Table '$table' NOT FOUND\n";
            $allTestsPassed = false;
        }
    }
    
    // Check sample data
    $clientCount = $connection->execute("SELECT COUNT(*) as cnt FROM clients")->fetchAll('assoc')[0]['cnt'] ?? 0;
    $productCount = $connection->execute("SELECT COUNT(*) as cnt FROM products")->fetchAll('assoc')[0]['cnt'] ?? 0;
    $vesselCount = $connection->execute("SELECT COUNT(*) as cnt FROM vessels")->fetchAll('assoc')[0]['cnt'] ?? 0;
    $contractCount = $connection->execute("SELECT COUNT(*) as cnt FROM contracts")->fetchAll('assoc')[0]['cnt'] ?? 0;
    $sgcCount = $connection->execute("SELECT COUNT(*) as cnt FROM sgc_accounts")->fetchAll('assoc')[0]['cnt'] ?? 0;
    
    echo "\n  Sample Data Counts:\n";
    echo "    - Clients: $clientCount\n";
    echo "    - Products: $productCount\n";
    echo "    - Vessels: $vesselCount\n";
    echo "    - Contracts: $contractCount\n";
    echo "    - SGC Accounts: $sgcCount\n";
    
    if ($clientCount > 0 && $productCount > 0 && $vesselCount > 0 && $contractCount > 0 && $sgcCount > 0) {
        echo "\n  ✓ Sample data loaded successfully\n";
    } else {
        echo "\n  ✗ Sample data incomplete\n";
        $allTestsPassed = false;
    }
    
} catch (Exception $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
    $allTestsPassed = false;
}

echo "\n";

// Test 2: Create Fresh Invoice
echo "📝 TEST 2: Create Fresh Invoice\n";
echo "───────────────────────────────────────────────────────────────────\n";

try {
    $FreshInvoices = TableRegistry::getTableLocator()->get('FreshInvoices');
    $Contracts = TableRegistry::getTableLocator()->get('Contracts');
    $SgcAccounts = TableRegistry::getTableLocator()->get('SgcAccounts');
    $Vessels = TableRegistry::getTableLocator()->get('Vessels');
    
    // Get first contract and SGC account
    $contract = $Contracts->find()->contain(['Clients', 'Products'])->first();
    $sgcAccount = $SgcAccounts->find()->first();
    $vessel = $Vessels->find()->first();
    
    if (!$contract || !$sgcAccount || !$vessel) {
        echo "  ✗ Missing required data (contract, SGC account, or vessel)\n";
        $allTestsPassed = false;
    } else {
        echo "  Using Contract: " . $contract->contract_id . "\n";
        echo "  Using Vessel: " . $vessel->name . "\n";
        echo "  Using SGC Account: " . $sgcAccount->account_name . "\n";
        
        // Create fresh invoice
        $freshInvoice = $FreshInvoices->newEntity([
            'client_id' => $contract->client_id,
            'product_id' => $contract->product_id,
            'contract_id' => $contract->id,
            'vessel_id' => $vessel->id,
            'sgc_account_id' => $sgcAccount->id,
            'quantity' => 250.500,
            'unit_price' => $contract->unit_price,
            'payment_percentage' => 80.00,
            'status' => 'draft'
        ]);
        
        if ($FreshInvoices->save($freshInvoice)) {
            echo "\n  ✓ Fresh Invoice created successfully\n";
            echo "    - Invoice Number: " . $freshInvoice->invoice_number . "\n";
            echo "    - Quantity: " . $freshInvoice->quantity . "\n";
            echo "    - Unit Price: $" . number_format($freshInvoice->unit_price, 2) . "\n";
            echo "    - Payment %: " . $freshInvoice->payment_percentage . "%\n";
            echo "    - Total Value: $" . number_format($freshInvoice->total_value, 2) . "\n";
            echo "    - Status: " . $freshInvoice->status . "\n";
            
            // Verify auto-calculation
            $expectedTotal = $freshInvoice->quantity * $freshInvoice->unit_price * ($freshInvoice->payment_percentage / 100);
            if (abs($freshInvoice->total_value - $expectedTotal) < 0.01) {
                echo "\n  ✓ Total value auto-calculation correct\n";
            } else {
                echo "\n  ✗ Total value auto-calculation INCORRECT\n";
                echo "    Expected: $" . number_format($expectedTotal, 2) . "\n";
                echo "    Got: $" . number_format($freshInvoice->total_value, 2) . "\n";
                $allTestsPassed = false;
            }
            
            // Verify invoice number format (should be 0001, 0002, etc.)
            if (preg_match('/^\d{4}$/', $freshInvoice->invoice_number)) {
                echo "  ✓ Invoice number format correct (sequential)\n";
            } else {
                echo "  ✗ Invoice number format INCORRECT\n";
                $allTestsPassed = false;
            }
        } else {
            echo "\n  ✗ Failed to create fresh invoice\n";
            echo "  Errors: " . json_encode($freshInvoice->getErrors()) . "\n";
            $allTestsPassed = false;
            $freshInvoice = null;
        }
    }
} catch (Exception $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
    $allTestsPassed = false;
    $freshInvoice = null;
}

echo "\n";

// Test 3: Fresh Invoice Approval Workflow
if ($freshInvoice) {
    echo "✅ TEST 3: Fresh Invoice Approval Workflow\n";
    echo "───────────────────────────────────────────────────────────────────\n";
    
    try {
        // Submit for approval
        $freshInvoice->status = 'pending_approval';
        if ($FreshInvoices->save($freshInvoice)) {
            echo "  ✓ Submitted for approval (status: pending_approval)\n";
        } else {
            echo "  ✗ Failed to submit for approval\n";
            $allTestsPassed = false;
        }
        
        // Treasurer approve
        $freshInvoice->status = 'approved';
        $freshInvoice->treasurer_notes = 'Approved for processing - E2E Test';
        if ($FreshInvoices->save($freshInvoice)) {
            echo "  ✓ Treasurer approved (status: approved)\n";
            echo "    - Notes: " . $freshInvoice->treasurer_notes . "\n";
        } else {
            echo "  ✗ Failed treasurer approval\n";
            $allTestsPassed = false;
        }
        
        // Send to Export
        $freshInvoice->status = 'sent_to_export';
        if ($FreshInvoices->save($freshInvoice)) {
            echo "  ✓ Sent to Export department (status: sent_to_export)\n";
        } else {
            echo "  ✗ Failed to send to Export\n";
            $allTestsPassed = false;
        }
        
    } catch (Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }
    
    echo "\n";
}

// Test 4: Create Final Invoice
if ($freshInvoice) {
    echo "📄 TEST 4: Create Final Invoice from Fresh Invoice\n";
    echo "───────────────────────────────────────────────────────────────────\n";
    
    try {
        $FinalInvoices = TableRegistry::getTableLocator()->get('FinalInvoices');
        
        // Create final invoice with different quantity (simulate actual loaded quantity)
        $actualQuantity = 248.750; // Slightly less than fresh invoice quantity
        
        $finalInvoice = $FinalInvoices->newEntity([
            'fresh_invoice_id' => $freshInvoice->id,
            'sgc_account_id' => $freshInvoice->sgc_account_id,
            'landed_quantity' => $actualQuantity,
            'unit_price' => $freshInvoice->unit_price,
            'payment_percentage' => $freshInvoice->payment_percentage,
            'status' => 'draft'
        ]);
        
        if ($FinalInvoices->save($finalInvoice)) {
            echo "  ✓ Final Invoice created successfully\n";
            echo "    - Invoice Number: " . $finalInvoice->invoice_number . "\n";
            echo "    - Fresh Invoice Ref: " . $freshInvoice->invoice_number . "\n";
            echo "    - Fresh Quantity: " . $freshInvoice->quantity . "\n";
            echo "    - Final Quantity: " . $finalInvoice->landed_quantity . "\n";
            echo "    - Quantity Variance: " . $finalInvoice->quantity_variance . "\n";
            echo "    - Total Value: $" . number_format($finalInvoice->total_value, 2) . "\n";
            echo "    - Status: " . $finalInvoice->status . "\n";
            
            // Verify FVP prefix
            if (preg_match('/^FVP\d{4}$/', $finalInvoice->invoice_number)) {
                echo "\n  ✓ Invoice number format correct (FVP prefix)\n";
            } else {
                echo "\n  ✗ Invoice number format INCORRECT (should have FVP prefix)\n";
                $allTestsPassed = false;
            }
            
            // Verify variance calculation
            $expectedVariance = $finalInvoice->landed_quantity - $freshInvoice->quantity;
            if (abs($finalInvoice->quantity_variance - $expectedVariance) < 0.001) {
                echo "  ✓ Quantity variance calculation correct\n";
            } else {
                echo "  ✗ Quantity variance calculation INCORRECT\n";
                echo "    Expected: " . $expectedVariance . "\n";
                echo "    Got: " . $finalInvoice->quantity_variance . "\n";
                $allTestsPassed = false;
            }
            
            // Verify total value calculation
            $expectedTotal = $finalInvoice->landed_quantity * $finalInvoice->unit_price * ($finalInvoice->payment_percentage / 100);
            if (abs($finalInvoice->total_value - $expectedTotal) < 0.01) {
                echo "  ✓ Total value auto-calculation correct\n";
            } else {
                echo "  ✗ Total value auto-calculation INCORRECT\n";
                $allTestsPassed = false;
            }
            
        } else {
            echo "\n  ✗ Failed to create final invoice\n";
            echo "  Errors: " . json_encode($finalInvoice->getErrors()) . "\n";
            $allTestsPassed = false;
            $finalInvoice = null;
        }
        
    } catch (Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
        $finalInvoice = null;
    }
    
    echo "\n";
}

// Test 5: Final Invoice Approval Workflow
if (isset($finalInvoice) && $finalInvoice) {
    echo "✅ TEST 5: Final Invoice Approval Workflow\n";
    echo "───────────────────────────────────────────────────────────────────\n";
    
    try {
        // Submit for approval
        $finalInvoice->status = 'pending_approval';
        if ($FinalInvoices->save($finalInvoice)) {
            echo "  ✓ Submitted for approval (status: pending_approval)\n";
        } else {
            echo "  ✗ Failed to submit for approval\n";
            $allTestsPassed = false;
        }
        
        // Treasurer approve
        $finalInvoice->status = 'approved';
        $finalInvoice->treasurer_notes = 'Final invoice approved - E2E Test';
        if ($FinalInvoices->save($finalInvoice)) {
            echo "  ✓ Treasurer approved (status: approved)\n";
            echo "    - Notes: " . $finalInvoice->treasurer_notes . "\n";
        } else {
            echo "  ✗ Failed treasurer approval\n";
            $allTestsPassed = false;
        }
        
        // Send to Sales
        $finalInvoice->status = 'sent_to_sales';
        if ($FinalInvoices->save($finalInvoice)) {
            echo "  ✓ Sent to Sales department (status: sent_to_sales)\n";
        } else {
            echo "  ✗ Failed to send to Sales\n";
            $allTestsPassed = false;
        }
        
    } catch (Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }
    
    echo "\n";
}

// Test 6: Verify Complete Audit Trail
echo "🔍 TEST 6: Verify Complete Audit Trail\n";
echo "───────────────────────────────────────────────────────────────────\n";

try {
    if ($freshInvoice) {
        // Reload fresh invoice to get latest data
        $freshInvoice = $FreshInvoices->get($freshInvoice->id, [
            'contain' => ['Contracts', 'Vessels', 'SgcAccounts']
        ]);
        
        echo "  Fresh Invoice Audit Trail:\n";
        echo "    - ID: " . $freshInvoice->id . "\n";
        echo "    - Invoice Number: " . $freshInvoice->invoice_number . "\n";
        echo "    - Contract: " . $freshInvoice->contract->contract_id . "\n";
        echo "    - Vessel: " . $freshInvoice->vessel->name . "\n";
        echo "    - SGC Account: " . $freshInvoice->sgc_account->account_name . "\n";
        echo "    - Created: " . $freshInvoice->created . "\n";
        echo "    - Modified: " . $freshInvoice->modified . "\n";
        echo "    - Final Status: " . $freshInvoice->status . "\n";
        
        if ($freshInvoice->created && $freshInvoice->modified) {
            echo "\n  ✓ Fresh invoice timestamps recorded\n";
        } else {
            echo "\n  ✗ Fresh invoice timestamps missing\n";
            $allTestsPassed = false;
        }
    }
    
    if (isset($finalInvoice) && $finalInvoice) {
        // Reload final invoice
        $finalInvoice = $FinalInvoices->get($finalInvoice->id, [
            'contain' => ['FreshInvoices', 'SgcAccounts']
        ]);
        
        echo "\n  Final Invoice Audit Trail:\n";
        echo "    - ID: " . $finalInvoice->id . "\n";
        echo "    - Invoice Number: " . $finalInvoice->invoice_number . "\n";
        echo "    - Fresh Invoice Ref: " . $finalInvoice->fresh_invoice->invoice_number . "\n";
        echo "    - SGC Account: " . $finalInvoice->sgc_account->account_name . "\n";
        echo "    - Created: " . $finalInvoice->created . "\n";
        echo "    - Modified: " . $finalInvoice->modified . "\n";
        echo "    - Final Status: " . $finalInvoice->status . "\n";
        
        if ($finalInvoice->created && $finalInvoice->modified) {
            echo "\n  ✓ Final invoice timestamps recorded\n";
        } else {
            echo "\n  ✗ Final invoice timestamps missing\n";
            $allTestsPassed = false;
        }
        
        // Verify relationship
        if ($finalInvoice->fresh_invoice_id == $freshInvoice->id) {
            echo "  ✓ Fresh-to-Final invoice relationship verified\n";
        } else {
            echo "  ✗ Fresh-to-Final invoice relationship BROKEN\n";
            $allTestsPassed = false;
        }
    }
    
} catch (Exception $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
    $allTestsPassed = false;
}

echo "\n";

// Summary
echo "═══════════════════════════════════════════════════════════════════\n";
echo "  TEST SUMMARY\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "\n";

if ($allTestsPassed) {
    echo "  ✅ ALL TESTS PASSED! \n";
    echo "\n";
    echo "  The Treasury Invoicing System is working correctly:\n";
    echo "    ✓ Database setup verified\n";
    echo "    ✓ Fresh invoice creation with auto-calculations\n";
    echo "    ✓ Fresh invoice approval workflow\n";
    echo "    ✓ Final invoice creation with variance tracking\n";
    echo "    ✓ Final invoice approval workflow\n";
    echo "    ✓ Complete audit trail maintained\n";
    echo "\n";
    echo "  System Status: 🟢 READY FOR PRODUCTION\n";
} else {
    echo "  ❌ SOME TESTS FAILED\n";
    echo "\n";
    echo "  Please review the errors above and fix the issues.\n";
    echo "\n";
    echo "  System Status: 🔴 NEEDS ATTENTION\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "\n";

// Return exit code
exit($allTestsPassed ? 0 : 1);
