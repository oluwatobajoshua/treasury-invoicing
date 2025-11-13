<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AllowanceRate Entity
 *
 * @property int $id
 * @property int $job_level_id
 * @property string $travel_type
 * @property string $accommodation_rate
 * @property string $feeding_rate
 * @property string $transport_rate
 * @property string $incidental_rate
 * @property string $currency
 * @property string|null $flight_class
 * @property string|null $hotel_standard
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\JobLevel $job_level
 */
class AllowanceRate extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'job_level_id' => true,
        'travel_type' => true,
        'accommodation_rate' => true,
        'feeding_rate' => true,
        'transport_rate' => true,
        'incidental_rate' => true,
        'currency' => true,
        'flight_class' => true,
        'hotel_standard' => true,
        'created' => true,
        'modified' => true,
        'job_level' => true,
    ];
}
