<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddAmountPaidToFinalInvoices extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Add amount_paid column to final_invoices table.
     * This stores the amount already paid via Fresh Invoice (user-editable).
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('final_invoices');
        
        $table->addColumn('amount_paid', 'decimal', [
            'precision' => 18,
            'scale' => 2,
            'null' => false,
            'default' => 0.00,
            'after' => 'unit_price',
            'comment' => 'Amount already paid via Fresh Invoice (editable)'
        ])
        ->update();
    }
}
