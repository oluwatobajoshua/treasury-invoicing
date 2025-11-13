<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class EnhanceContractsTable extends AbstractMigration
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
        $table = $this->table('contracts');
        
        $table->addColumn('contract_date', 'date', [
            'default' => null,
            'null' => true,
            'after' => 'contract_id',
        ]);
        
        $table->addColumn('start_date', 'date', [
            'default' => null,
            'null' => true,
            'after' => 'contract_date',
        ]);
        
        $table->addColumn('end_date', 'date', [
            'default' => null,
            'null' => true,
            'after' => 'start_date',
        ]);
        
        $table->addColumn('status', 'string', [
            'default' => 'active',
            'limit' => 50,
            'null' => false,
            'after' => 'end_date',
        ]);
        
        $table->addColumn('payment_terms', 'text', [
            'default' => null,
            'null' => true,
            'after' => 'status',
        ]);
        
        $table->addColumn('delivery_terms', 'text', [
            'default' => null,
            'null' => true,
            'after' => 'payment_terms',
        ]);
        
        $table->addColumn('notes', 'text', [
            'default' => null,
            'null' => true,
            'after' => 'delivery_terms',
        ]);
        
        $table->addColumn('total_value', 'decimal', [
            'default' => null,
            'null' => true,
            'precision' => 15,
            'scale' => 2,
            'after' => 'unit_price',
        ]);
        
        $table->addColumn('remaining_quantity', 'decimal', [
            'default' => null,
            'null' => true,
            'precision' => 15,
            'scale' => 3,
            'after' => 'quantity',
        ]);
        
        $table->update();
    }
}
