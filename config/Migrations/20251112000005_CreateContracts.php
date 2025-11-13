<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateContracts extends AbstractMigration
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
        $table->addColumn('contract_id', 'string', [
            'limit' => 100,
            'null' => false,
        ])
        ->addColumn('client_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('product_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('quantity', 'decimal', [
            'precision' => 15,
            'scale' => 3,
            'null' => true,
        ])
        ->addColumn('unit_price', 'decimal', [
            'precision' => 15,
            'scale' => 2,
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
        ->addIndex(['contract_id'])
        ->create();
    }
}
