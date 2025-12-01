<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateBanks extends AbstractMigration
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
        $table = $this->table('banks');
        
        $table->addColumn('bank_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        
        $table->addColumn('bank_type', 'string', [
            'default' => 'both',
            'limit' => 20,
            'null' => false,
            'comment' => 'sales, sustainability, or both'
        ]);
        
        $table->addColumn('account_number', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
        ]);
        
        $table->addColumn('currency', 'string', [
            'default' => 'USD',
            'limit' => 3,
            'null' => false,
        ]);
        
        // For Sales Invoices (Simple Banking)
        $table->addColumn('bank_address', 'string', [
            'default' => null,
            'limit' => 500,
            'null' => true,
        ]);
        
        $table->addColumn('swift_code', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
        ]);
        
        // For Sustainability Invoices (Correspondent Bank)
        $table->addColumn('correspondent_bank', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        
        $table->addColumn('correspondent_address', 'string', [
            'default' => null,
            'limit' => 500,
            'null' => true,
        ]);
        
        $table->addColumn('correspondent_swift', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
        ]);
        
        $table->addColumn('aba_routing', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
        ]);
        
        // Beneficiary Details
        $table->addColumn('beneficiary_bank', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        
        $table->addColumn('beneficiary_account_no', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
        ]);
        
        $table->addColumn('beneficiary_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        
        $table->addColumn('beneficiary_acct_no', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
            'comment' => 'Alternative account number field'
        ]);
        
        $table->addColumn('beneficiary_swift', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
        ]);
        
        $table->addColumn('purpose', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        
        $table->addColumn('notes', 'text', [
            'default' => null,
            'null' => true,
        ]);
        
        $table->addColumn('is_active', 'boolean', [
            'default' => true,
            'null' => false,
        ]);
        
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        
        $table->addIndex(['bank_name']);
        $table->addIndex(['bank_type']);
        $table->addIndex(['currency']);
        $table->addIndex(['is_active']);
        
        $table->create();
    }
}
