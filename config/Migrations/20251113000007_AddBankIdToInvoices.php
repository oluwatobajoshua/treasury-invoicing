<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddBankIdToInvoices extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change(): void
    {
        // Add bank_id to sales_invoices
        $salesTable = $this->table('sales_invoices');
        if (!$salesTable->hasColumn('bank_id')) {
            $salesTable->addColumn('bank_id', 'integer', [
                'default' => null,
                'null' => true,
                'after' => 'currency'
            ]);
            $salesTable->addIndex(['bank_id']);
            $salesTable->addForeignKey('bank_id', 'banks', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ]);
            $salesTable->update();
        }
        
        // Add bank_id to sustainability_invoices
        $sustainabilityTable = $this->table('sustainability_invoices');
        if (!$sustainabilityTable->hasColumn('bank_id')) {
            $sustainabilityTable->addColumn('bank_id', 'integer', [
                'default' => null,
                'null' => true,
                'after' => 'currency'
            ]);
            $sustainabilityTable->addIndex(['bank_id']);
            $sustainabilityTable->addForeignKey('bank_id', 'banks', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ]);
            $sustainabilityTable->update();
        }
    }
}
