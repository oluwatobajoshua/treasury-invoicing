<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * WorkflowHistory Entity
 *
 * @property int $id
 * @property int $travel_request_id
 * @property string|null $from_status
 * @property string $to_status
 * @property int|null $user_id
 * @property string|null $comments
 * @property string|null $metadata
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\TravelRequest $travel_request
 * @property \App\Model\Entity\User $user
 */
class WorkflowHistory extends Entity
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
        'travel_request_id' => true,
        'from_status' => true,
        'to_status' => true,
        'user_id' => true,
        'comments' => true,
        'metadata' => true,
        'created' => true,
        'travel_request' => true,
        'user' => true,
    ];
}
