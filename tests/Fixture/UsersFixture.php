<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'employee_id' => 'Lorem ipsum dolor sit amet',
                'job_level_id' => 1,
                'role' => 'Lorem ipsum dolor sit amet',
                'department' => 'Lorem ipsum dolor sit amet',
                'phone' => 'Lorem ipsum dolor ',
                'microsoft_id' => 'Lorem ipsum dolor sit amet',
                'line_manager_id' => 1,
                'last_login' => '2025-11-08 06:55:07',
                'is_active' => 1,
                'created' => '2025-11-08 06:55:07',
                'modified' => '2025-11-08 06:55:07',
            ],
        ];
        parent::init();
    }
}
