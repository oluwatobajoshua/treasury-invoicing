<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * InitialData seed.
 */
class InitialDataSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        // Insert Clients
        $clients = [
            [
                'name' => 'SFI AGRI COMMODITIES LTD',
                'address' => 'UK',
                'email' => 'N/A',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'CARGILL COCOA & CHOCOLATE',
                'address' => 'NETHERLANDS',
                'email' => 'N/A',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'SFI/CARGILL',
                'address' => 'NETHERLANDS',
                'email' => 'N/A',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cargill',
                'address' => 'NETHERLANDS',
                'email' => 'N/A',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->table('clients')->insert($clients)->save();

        // Insert Products
        $products = [
            [
                'name' => 'COCOA',
                'category' => 'COCOA BEAN',
                'unit_price' => 6292.35,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cocoa',
                'category' => 'COCOA BEAN',
                'unit_price' => 5717.50,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->table('products')->insert($products)->save();

        // Insert Vessels
        $vessels = [
            [
                'name' => 'Vessel: GREAT TEMA -  GTT0525',
                'flag_country' => 'NETHERLANDS',
                'dwt' => null,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Vessel: GRANDE BENIN -  GBN0625',
                'flag_country' => 'NETHERLANDS',
                'dwt' => null,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Vessel: GREAT COTONOU -GTC0625',
                'flag_country' => 'NETHERLANDS',
                'dwt' => null,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->table('vessels')->insert($vessels)->save();

        // Insert SGC Accounts
        $sgcAccounts = [
            [
                'account_id' => '1100524',
                'account_name' => 'ACCESS UK',
                'currency' => 'GBP',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'account_id' => '1100533',
                'account_name' => 'FIDELITY NXP',
                'currency' => 'USD',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'account_id' => '1100534',
                'account_name' => 'STERLING',
                'currency' => 'USD',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'account_id' => '1100523',
                'account_name' => 'ACCESS UK',
                'currency' => 'USD',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->table('sgc_accounts')->insert($sgcAccounts)->save();

        // Insert Contracts
        // Note: client_id and product_id will need to be matched with the actual IDs from the database
        // For this seed, we're using assumed IDs (1, 2, 3, 4 for clients and 1, 2 for products)
        $contracts = [
            [
                'contract_id' => '2025-SI 1B/QP 136484',
                'client_id' => 3, // SFI/CARGILL
                'product_id' => 1, // COCOA at 6292.35
                'quantity' => 262.430,
                'unit_price' => 6292.35,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'contract_id' => 'QP-136790.01/6B',
                'client_id' => 4, // Cargill
                'product_id' => 2, // Cocoa at 5717.50
                'quantity' => 260.900,
                'unit_price' => 5717.50,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->table('contracts')->insert($contracts)->save();
    }
}
