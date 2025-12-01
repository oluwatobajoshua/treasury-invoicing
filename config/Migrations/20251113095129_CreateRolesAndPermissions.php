<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateRolesAndPermissions extends AbstractMigration
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
        // Audit logs table for tracking all actions
        $auditLogs = $this->table('audit_logs');
        $auditLogs->addColumn('user_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('action', 'string', [
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('model', 'string', [
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('record_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('old_values', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('new_values', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('ip_address', 'string', [
                'limit' => 45,
                'default' => null,
                'null' => true,
            ])
            ->addColumn('user_agent', 'string', [
                'limit' => 255,
                'default' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => false,
            ])
            ->addIndex(['user_id'])
            ->addIndex(['model', 'record_id'])
            ->addIndex(['action'])
            ->addIndex(['created'])
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'CASCADE'
            ])
            ->create();
    }
}
