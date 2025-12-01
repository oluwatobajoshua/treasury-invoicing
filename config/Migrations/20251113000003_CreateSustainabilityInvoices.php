<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateSustainabilityInvoices extends AbstractMigration
{
    /**
     * Create sustainability_invoices table
     * 
     * Sustainability invoices track cocoa exports with sustainability investments
     * and differentials, including complex international banking details
     */
    public function change(): void
    {
        $table = $this->table('sustainability_invoices');
        
        // Core invoice fields
        $table->addColumn('invoice_number', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
            'comment' => 'Unique invoice number (e.g., 0169)'
        ]);
        
        $table->addColumn('invoice_date', 'date', [
            'default' => null,
            'null' => false,
            'comment' => 'Date of invoice issuance'
        ]);
        
        $table->addColumn('seller_id', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
            'comment' => 'Seller reference ID (e.g., RA_00051922110)'
        ]);
        
        $table->addColumn('client_id', 'integer', [
            'default' => null,
            'null' => false,
            'comment' => 'Foreign key to clients table (buyer company)'
        ]);
        
        // Line item fields
        $table->addColumn('buyer_id', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
            'comment' => 'Buyer reference ID (e.g., RA_0002855210B)'
        ]);
        
        $table->addColumn('quantity_mt', 'decimal', [
            'default' => null,
            'null' => false,
            'precision' => 18,
            'scale' => 3,
            'comment' => 'Quantity in Metric Tons (MT)'
        ]);
        
        $table->addColumn('description', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            'comment' => 'Product description (e.g., "Dried Cocoa Beans-Cocoa")'
        ]);
        
        // Sustainability-specific fields
        $table->addColumn('sustainability_investment', 'decimal', [
            'default' => null,
            'null' => false,
            'precision' => 18,
            'scale' => 2,
            'comment' => 'Sustainability investment amount in USD'
        ]);
        
        $table->addColumn('sustainability_differential', 'decimal', [
            'default' => null,
            'null' => false,
            'precision' => 18,
            'scale' => 2,
            'comment' => 'Sustainability differential amount in USD'
        ]);
        
        $table->addColumn('total_value', 'decimal', [
            'default' => null,
            'null' => false,
            'precision' => 18,
            'scale' => 2,
            'comment' => 'Total invoice value (investment + differential)'
        ]);
        
        $table->addColumn('net_receivable', 'decimal', [
            'default' => null,
            'null' => false,
            'precision' => 18,
            'scale' => 2,
            'comment' => 'Net receivable against value (100% of contract value)'
        ]);
        
        $table->addColumn('currency', 'string', [
            'default' => 'USD',
            'limit' => 3,
            'null' => false,
            'comment' => 'Currency code (typically USD for sustainability)'
        ]);
        
        // Complex banking details for international wire transfers
        $table->addColumn('correspondent_bank', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            'comment' => 'Correspondent bank name'
        ]);
        
        $table->addColumn('correspondent_address', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            'comment' => 'Correspondent bank address'
        ]);
        
        $table->addColumn('correspondent_swift', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true,
            'comment' => 'Correspondent bank SWIFT code'
        ]);
        
        $table->addColumn('aba_routing', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true,
            'comment' => 'ABA routing number'
        ]);
        
        $table->addColumn('beneficiary_bank', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            'comment' => 'Beneficiary bank name'
        ]);
        
        $table->addColumn('beneficiary_account_no', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
            'comment' => 'Beneficiary account number'
        ]);
        
        $table->addColumn('beneficiary_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            'comment' => 'Beneficiary account name'
        ]);
        
        $table->addColumn('beneficiary_acct_no', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
            'comment' => 'Beneficiary\'s account number (alternative field)'
        ]);
        
        $table->addColumn('beneficiary_swift', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true,
            'comment' => 'Beneficiary bank SWIFT code'
        ]);
        
        $table->addColumn('purpose', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            'comment' => 'Payment purpose (e.g., "COCOA EXPORT PROCEEDS")'
        ]);
        
        // Additional fields
        $table->addColumn('notes', 'text', [
            'default' => null,
            'null' => true,
            'comment' => 'Additional notes or comments'
        ]);
        
        $table->addColumn('status', 'string', [
            'default' => 'draft',
            'limit' => 20,
            'null' => false,
            'comment' => 'Invoice status: draft, sent, paid, cancelled'
        ]);
        
        // Timestamps
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        
        // Indexes
        $table->addIndex(['invoice_number'], ['unique' => true]);
        $table->addIndex(['client_id']);
        $table->addIndex(['invoice_date']);
        $table->addIndex(['seller_id']);
        $table->addIndex(['buyer_id']);
        $table->addIndex(['status']);
        
        // Foreign keys
        $table->addForeignKey('client_id', 'clients', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE'
        ]);
        
        $table->create();
    }
}
