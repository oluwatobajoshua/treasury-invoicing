<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateFinalInvoices extends AbstractMigration
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
        $table = $this->table('final_invoices');
        $table->addColumn('invoice_number', 'string', [
            'limit' => 50,
            'null' => false,
            'comment' => 'Will be prefixed with FVP, e.g., FVP0155'
        ])
        ->addColumn('fresh_invoice_id', 'integer', [
            'null' => false,
            'comment' => 'Reference to the original fresh invoice'
        ])
        ->addColumn('original_invoice_number', 'string', [
            'limit' => 50,
            'null' => false,
        ])
        ->addColumn('landed_quantity', 'decimal', [
            'precision' => 15,
            'scale' => 3,
            'null' => false,
            'comment' => 'Actual landed weight from CWT report'
        ])
        ->addColumn('quantity_variance', 'decimal', [
            'precision' => 15,
            'scale' => 3,
            'null' => true,
            'comment' => 'Difference between original and landed quantity'
        ])
        ->addColumn('vessel_name', 'string', [
            'limit' => 255,
            'null' => true,
        ])
        ->addColumn('bl_number', 'string', [
            'limit' => 100,
            'null' => true,
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
        ])
        ->addColumn('total_value', 'decimal', [
            'precision' => 18,
            'scale' => 2,
            'null' => true,
            'comment' => 'Calculated: landed_quantity * unit_price * payment_percentage'
        ])
        ->addColumn('sgc_account_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('notes', 'text', [
            'null' => true,
            'comment' => 'CWT variance notes and other comments'
        ])
        ->addColumn('status', 'string', [
            'limit' => 50,
            'default' => 'draft',
            'null' => false,
            'comment' => 'draft, pending_treasurer_approval, approved, rejected, sent_to_sales'
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
        ->addColumn('sent_to_sales_date', 'datetime', [
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
        ->addForeignKey('fresh_invoice_id', 'fresh_invoices', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE'
        ])
        ->addForeignKey('sgc_account_id', 'sgc_accounts', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE'
        ])
        ->addIndex(['invoice_number'], ['unique' => true])
        ->addIndex(['original_invoice_number'])
        ->addIndex(['status'])
        ->addIndex(['treasurer_approval_status'])
        ->addIndex(['invoice_date'])
        ->create();
    }
}
