<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateFreshInvoices extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('fresh_invoices');
        $table->addColumn('invoice_number', 'string', [
            'limit' => 50,
            'null' => false,
        ])
        ->addColumn('client_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('product_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('contract_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('vessel_id', 'integer', [
            'null' => true,
        ])
        ->addColumn('vessel_name', 'string', [
            'limit' => 255,
            'null' => true,
        ])
        ->addColumn('bl_number', 'string', [
            'limit' => 100,
            'null' => true,
        ])
        ->addColumn('quantity', 'decimal', [
            'precision' => 15,
            'scale' => 3,
            'null' => false,
        ])
        ->addColumn('unit_price', 'decimal', [
            'precision' => 15,
            'scale' => 2,
            'null' => false,
        ])
        ->addColumn('payment_percentage', 'decimal', [
            'precision' => 5,
            'scale' => 2,
            'null' => false,
            'default' => 98.00,
            'comment' => 'Payment percentage (98%, 99%, etc.)'
        ])
        ->addColumn('total_value', 'decimal', [
            'precision' => 18,
            'scale' => 2,
            'null' => true,
            'comment' => 'Calculated: quantity * unit_price * payment_percentage'
        ])
        ->addColumn('sgc_account_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('bulk_or_bag', 'string', [
            'limit' => 20,
            'null' => true,
            'comment' => 'e.g., "16 BULK", "15 BULK"'
        ])
        ->addColumn('notes', 'text', [
            'null' => true,
        ])
        ->addColumn('status', 'string', [
            'limit' => 50,
            'default' => 'draft',
            'null' => false,
            'comment' => 'draft, pending_treasurer_approval, approved, rejected, sent_to_export'
        ])
        ->addColumn('treasurer_approval_status', 'string', [
            'limit' => 50,
            'default' => 'pending',
            'null' => false,
            'comment' => 'pending, approved, rejected'
        ])
        ->addColumn('treasurer_approval_date', 'datetime', [
            'default' => null,
            'null' => true,
        ])
        ->addColumn('treasurer_comments', 'text', [
            'null' => true,
        ])
        ->addColumn('sent_to_export_date', 'datetime', [
            'default' => null,
            'null' => true,
        ])
        ->addColumn('invoice_date', 'date', [
            'default' => null,
            'null' => true,
        ])
        ->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ])
        ->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ])
        ->addForeignKey('client_id', 'clients', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE'
        ])
        ->addForeignKey('product_id', 'products', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE'
        ])
        ->addForeignKey('contract_id', 'contracts', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE'
        ])
        ->addForeignKey('vessel_id', 'vessels', 'id', [
            'delete' => 'SET_NULL',
            'update' => 'CASCADE'
        ])
        ->addForeignKey('sgc_account_id', 'sgc_accounts', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE'
        ])
        ->addIndex(['invoice_number'], ['unique' => true])
        ->addIndex(['status'])
        ->addIndex(['treasurer_approval_status'])
        ->addIndex(['invoice_date'])
        ->create();
    }
}
