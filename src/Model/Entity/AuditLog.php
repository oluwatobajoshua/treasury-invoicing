<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AuditLog Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string $model
 * @property int|null $record_id
 * @property string|null $old_values
 * @property string|null $new_values
 * @property string|null $description
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \App\Model\Entity\User $user
 */
class AuditLog extends Entity
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
        'user_id' => true,
        'action' => true,
        'model' => true,
        'record_id' => true,
        'old_values' => true,
        'new_values' => true,
        'description' => true,
        'ip_address' => true,
        'user_agent' => true,
        'created' => true,
        'user' => true,
    ];
}
