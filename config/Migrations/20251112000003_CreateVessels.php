<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateVessels extends AbstractMigration
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
        $table = $this->table('vessels');
        $table->addColumn('name', 'string', [
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('flag_country', 'string', [
            'limit' => 100,
            'null' => true,
        ])
        ->addColumn('dwt', 'integer', [
            'null' => true,
            'comment' => 'Deadweight tonnage'
        ])
        ->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ])
        ->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ])
        ->addIndex(['name'])
        ->create();
    }
}
