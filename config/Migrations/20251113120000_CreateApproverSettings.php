<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

/**
 * CreateApproverSettings migration
 * 
 * Manages approval workflow settings and notification recipients
 * for Fresh and Final invoice approval flows
 */
class CreateApproverSettings extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change(): void
    {
        // Approver settings table for managing notification recipients
        $table = $this->table('approver_settings');
        $table->addColumn('role', 'string', [
            'limit' => 50,
            'null' => false,
            'comment' => 'Role type: treasurer, export, sales'
        ])
        ->addColumn('invoice_type', 'string', [
            'limit' => 20,
            'null' => false,
            'comment' => 'Invoice type: fresh, final'
        ])
        ->addColumn('stage', 'string', [
            'limit' => 50,
            'null' => false,
            'comment' => 'Workflow stage: pending_approval, approved, rejected'
        ])
        ->addColumn('to_emails', 'text', [
            'null' => true,
            'comment' => 'Comma-separated list of primary recipient emails'
        ])
        ->addColumn('cc_emails', 'text', [
            'null' => true,
            'comment' => 'Comma-separated list of CC recipient emails'
        ])
        ->addColumn('is_active', 'boolean', [
            'default' => true,
            'null' => false,
            'comment' => 'Whether this notification rule is active'
        ])
        ->addColumn('created', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false
        ])
        ->addColumn('modified', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'update' => 'CURRENT_TIMESTAMP',
            'null' => false
        ])
        ->addIndex(['role', 'invoice_type', 'stage'], ['unique' => true])
        ->addIndex(['is_active'])
        ->create();

        // Insert default settings
        $this->table('approver_settings')->insert([
            // Fresh Invoice - Treasurer Approval
            [
                'role' => 'treasurer',
                'invoice_type' => 'fresh',
                'stage' => 'pending_approval',
                'to_emails' => 'treasurer@example.com',
                'cc_emails' => 'finance@example.com',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            // Fresh Invoice - Export Team (after approval)
            [
                'role' => 'export',
                'invoice_type' => 'fresh',
                'stage' => 'approved',
                'to_emails' => 'export@example.com',
                'cc_emails' => 'logistics@example.com',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            // Final Invoice - Treasurer Approval
            [
                'role' => 'treasurer',
                'invoice_type' => 'final',
                'stage' => 'pending_approval',
                'to_emails' => 'treasurer@example.com',
                'cc_emails' => 'finance@example.com',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            // Final Invoice - Sales Team (after approval)
            [
                'role' => 'sales',
                'invoice_type' => 'final',
                'stage' => 'approved',
                'to_emails' => 'sales@example.com',
                'cc_emails' => 'accounts@example.com',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ])->save();
    }
}
