<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Banks seed.
 */
class BanksSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        // Delete all existing banks first to avoid duplicates
        $this->execute('DELETE FROM banks');
        
        $data = [
            // Bank for Shipment Invoices (Fresh & Final) - NGN
            [
                'bank_name' => 'First Bank of Nigeria',
                'bank_type' => 'shipment',
                'account_number' => '2011234567',
                'currency' => 'NGN',
                'bank_address' => '35 Marina, Lagos Island, Lagos',
                'swift_code' => 'FBNINGLA',
                'correspondent_bank' => null,
                'correspondent_address' => null,
                'correspondent_swift' => null,
                'aba_routing' => null,
                'beneficiary_bank' => null,
                'beneficiary_account_no' => null,
                'beneficiary_name' => 'SUNBETH GLOBAL CONCEPT LTD',
                'beneficiary_acct_no' => null,
                'beneficiary_swift' => null,
                'purpose' => 'Shipment invoicing - cocoa export payments',
                'notes' => 'Primary bank for Fresh and Final invoices (Shipment type)',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            
            // Bank for Sales Invoices - NGN
            [
                'bank_name' => 'Zenith Bank PLC',
                'bank_type' => 'sales',
                'account_number' => '1234567890',
                'currency' => 'NGN',
                'bank_address' => 'Plot 84, Ajose Adeogun Street, Victoria Island, Lagos',
                'swift_code' => 'ZEIBNGLA',
                'correspondent_bank' => null,
                'correspondent_address' => null,
                'correspondent_swift' => null,
                'aba_routing' => null,
                'beneficiary_bank' => null,
                'beneficiary_account_no' => null,
                'beneficiary_name' => 'SUNBETH GLOBAL CONCEPT LTD',
                'beneficiary_acct_no' => null,
                'beneficiary_swift' => null,
                'purpose' => 'Payment for goods and services',
                'notes' => 'Primary bank for local NGN sales transactions',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            
            // Bank for Sustainability Invoices - USD (International)
            [
                'bank_name' => 'Fidelity Bank PLC',
                'bank_type' => 'sustainability',
                'account_number' => '5140005545',
                'currency' => 'USD',
                'bank_address' => null,
                'swift_code' => 'FIDTNGLA',
                'correspondent_bank' => 'CITI BANK N.A NEW YORK',
                'correspondent_address' => '111 WALL STREET NEWYORK, NY1004',
                'correspondent_swift' => 'CITIUS33',
                'aba_routing' => '021000089',
                'beneficiary_bank' => 'FIDELITY BANK PLC',
                'beneficiary_account_no' => '36115264',
                'beneficiary_name' => 'SUNBETH GLOBAL CONCEPT LTD',
                'beneficiary_acct_no' => '5140005545',
                'beneficiary_swift' => 'FIDTNGLA',
                'purpose' => 'COCOA EXPORT PROCEEDS',
                'notes' => 'International wire transfer for sustainability/cocoa export invoices',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            
            // Versatile bank for all invoice types - USD
            [
                'bank_name' => 'Access Bank PLC',
                'bank_type' => 'both',
                'account_number' => '0987654321',
                'currency' => 'USD',
                'bank_address' => 'Plot 999C Danmole Street, Victoria Island, Lagos',
                'swift_code' => 'ABNGNGLA',
                'correspondent_bank' => 'JPMORGAN CHASE BANK, N.A.',
                'correspondent_address' => '383 MADISON AVENUE, NEW YORK, NY 10179',
                'correspondent_swift' => 'CHASUS33',
                'aba_routing' => '021000021',
                'beneficiary_bank' => 'ACCESS BANK PLC',
                'beneficiary_account_no' => '0987654321',
                'beneficiary_name' => 'SUNBETH GLOBAL CONCEPT LTD',
                'beneficiary_acct_no' => '0987654321',
                'beneficiary_swift' => 'ABNGNGLA',
                'purpose' => 'International trade and export proceeds',
                'notes' => 'Versatile bank account for all invoice types (Shipment, Sales, Sustainability)',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            
            // Additional Shipment bank - USD for international shipments
            [
                'bank_name' => 'GTBank PLC',
                'bank_type' => 'shipment',
                'account_number' => '0123456789',
                'currency' => 'USD',
                'bank_address' => '635 Akin Adesola Street, Victoria Island, Lagos',
                'swift_code' => 'GTBINGLA',
                'correspondent_bank' => 'STANDARD CHARTERED BANK NEW YORK',
                'correspondent_address' => '1 MADISON AVENUE, NEW YORK, NY 10010',
                'correspondent_swift' => 'SCBLUS33',
                'aba_routing' => '026002561',
                'beneficiary_bank' => 'GUARANTY TRUST BANK PLC',
                'beneficiary_account_no' => '0123456789',
                'beneficiary_name' => 'SUNBETH GLOBAL CONCEPT LTD',
                'beneficiary_acct_no' => '0123456789',
                'beneficiary_swift' => 'GTBINGLA',
                'purpose' => 'International cocoa shipment payments',
                'notes' => 'USD account for Fresh and Final invoices with international wire transfer',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('banks');
        $table->insert($data)->save();
    }
}
