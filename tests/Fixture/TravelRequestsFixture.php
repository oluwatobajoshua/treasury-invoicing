<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TravelRequestsFixture
 */
class TravelRequestsFixture extends TestFixture
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
                'user_id' => 1,
                'request_number' => 'Lorem ipsum dolor sit amet',
                'purpose_of_travel' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'destination' => 'Lorem ipsum dolor sit amet',
                'travel_type' => 'Lorem ipsum dolor sit amet',
                'departure_date' => '2025-11-08',
                'return_date' => '2025-11-08',
                'duration_days' => 1,
                'accommodation_required' => 1,
                'status' => 'Lorem ipsum dolor sit amet',
                'current_step' => 1,
                'accommodation_allowance' => 1.5,
                'feeding_allowance' => 1.5,
                'transport_allowance' => 1.5,
                'incidental_allowance' => 1.5,
                'total_allowance' => 1.5,
                'line_manager_id' => 1,
                'line_manager_approved_at' => '2025-11-08 06:55:30',
                'line_manager_comments' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'admin_id' => 1,
                'requisition_prepared_at' => '2025-11-08 06:55:30',
                'requisition_number' => 'Lorem ipsum dolor sit amet',
                'power_automate_uploaded_at' => '2025-11-08 06:55:30',
                'power_automate_reference' => 'Lorem ipsum dolor sit amet',
                'completed_at' => '2025-11-08 06:55:30',
                'rejection_reason' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'rejected_at' => '2025-11-08 06:55:30',
                'rejected_by' => 1,
                'notes' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'attachments' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2025-11-08 06:55:30',
                'modified' => '2025-11-08 06:55:30',
            ],
        ];
        parent::init();
    }
}
