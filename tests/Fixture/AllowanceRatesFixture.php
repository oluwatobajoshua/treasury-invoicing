<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AllowanceRatesFixture
 */
class AllowanceRatesFixture extends TestFixture
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
                'job_level_id' => 1,
                'travel_type' => 'Lorem ipsum dolor sit amet',
                'accommodation_rate' => 1.5,
                'feeding_rate' => 1.5,
                'transport_rate' => 1.5,
                'incidental_rate' => 1.5,
                'currency' => 'L',
                'flight_class' => 'Lorem ipsum dolor sit amet',
                'hotel_standard' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-11-08 06:55:23',
                'modified' => '2025-11-08 06:55:23',
            ],
        ];
        parent::init();
    }
}
