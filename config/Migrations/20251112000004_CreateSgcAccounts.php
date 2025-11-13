<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateSgcAccounts extends AbstractMigration
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
        $table = $this->table('sgc_accounts');
        $table->addColumn('account_id', 'string', [
            'limit' => 50,
            'null' => false,
        ])
        ->addColumn('account_name', 'string', [
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('currency', 'string', [
            'limit' => 10,
            'null' => false,
            'default' => 'USD'
        ])
        ->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ])
        ->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ])
        ->addIndex(['account_id'], ['unique' => true])
        ->create();
    }
}
