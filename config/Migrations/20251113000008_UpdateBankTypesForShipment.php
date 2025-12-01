<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class UpdateBankTypesForShipment extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change(): void
    {
        $table = $this->table('banks');
        
        // Update bank_type column to allow 'shipment' type
        $table->changeColumn('bank_type', 'string', [
            'default' => 'both',
            'limit' => 20,
            'null' => false,
            'comment' => 'sales, sustainability, shipment, or both'
        ]);
        
        $table->update();
    }
}
