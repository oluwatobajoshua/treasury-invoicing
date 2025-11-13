<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('first_name', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
        ])
        ->addColumn('last_name', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
        ])
        ->addColumn('microsoft_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ])
        ->addColumn('role', 'string', [
            'default' => 'user',
            'limit' => 50,
            'null' => false,
            'comment' => 'user, treasurer, export, sales, admin',
        ])
        ->addColumn('is_active', 'boolean', [
            'default' => true,
            'null' => false,
        ])
        ->addColumn('last_login', 'datetime', [
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
        ->addIndex(['email'], [
            'unique' => true,
        ])
        ->addIndex(['microsoft_id'])
        ->addIndex(['role'])
        ->create();
    }
}
