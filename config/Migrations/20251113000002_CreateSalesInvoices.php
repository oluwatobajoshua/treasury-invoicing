<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateSalesInvoices extends AbstractMigration
{
    /**
     * Create sales_invoices table
     * 
     * Sales invoices are simple, single-stage invoices for product sales
     * without shipping logistics (no vessels, BL numbers, etc.)
     */
    public function change(): void
    {
        $table = $this->table('sales_invoices');
        
        // Core invoice fields
        $table->addColumn('invoice_number', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
            'comment' => 'Unique invoice number (e.g., 0168)'
        ]);
        
        $table->addColumn('invoice_date', 'date', [
            'default' => null,
            'null' => false,
            'comment' => 'Date of invoice issuance'
        ]);
        
        $table->addColumn('client_id', 'integer', [
            'default' => null,
            'null' => false,
            'comment' => 'Foreign key to clients table'
        ]);
        
        // Line item fields (simplified - one line per invoice for now)
        $table->addColumn('description', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            'comment' => 'Product/service description (e.g., "SALE OF JUTE BAGS")'
        ]);
        
        $table->addColumn('quantity', 'decimal', [
            'default' => null,
            'null' => false,
            'precision' => 18,
            'scale' => 3,
            'comment' => 'Quantity sold'
        ]);
        
        $table->addColumn('unit_price', 'decimal', [
            'default' => null,
            'null' => false,
            'precision' => 18,
            'scale' => 2,
            'comment' => 'Price per unit'
        ]);
        
        $table->addColumn('total_value', 'decimal', [
            'default' => null,
            'null' => false,
            'precision' => 18,
            'scale' => 2,
            'comment' => 'Total invoice value (quantity × unit_price)'
        ]);
        
        $table->addColumn('currency', 'string', [
            'default' => 'NGN',
            'limit' => 3,
            'null' => false,
            'comment' => 'Currency code (NGN, USD, etc.)'
        ]);
        
        // Bank details
        $table->addColumn('bank_account_id', 'integer', [
            'default' => null,
            'null' => true,
            'comment' => 'Foreign key to sgc_accounts (bank accounts) table'
        ]);
        
        $table->addColumn('purpose', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            'comment' => 'Payment purpose/reference'
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
        $table->addIndex(['status']);
        
        // Foreign keys
        $table->addForeignKey('client_id', 'clients', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE'
        ]);
        
        $table->addForeignKey('bank_account_id', 'sgc_accounts', 'id', [
            'delete' => 'SET_NULL',
            'update' => 'CASCADE'
        ]);
        
        $table->create();
    }
}
