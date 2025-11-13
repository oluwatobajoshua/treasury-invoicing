<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Settings seed.
 */
class SettingsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'company_name' => 'Sunbeth Global Concepts',
                'company_logo' => 'sunbeth-logo.png',
                'email' => 'info@sunbeth.net',
                'telephone' => '+234(0)805 6666 266',
                'corporate_address' => 'First Floor, Churchgate Towers 2, Victoria Island, Lagos State, Nigeria.',
            ],
        ];

        $table = $this->table('settings');
        $table->insert($data)->saveData();
    }
}
