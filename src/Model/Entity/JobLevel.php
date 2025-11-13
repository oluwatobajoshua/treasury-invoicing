<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * JobLevel Entity
 *
 * @property int $id
 * @property string $level_name
 * @property string $level_code
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\AllowanceRate[] $allowance_rates
 * @property \App\Model\Entity\User[] $users
 */
class JobLevel extends Entity
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
        'level_name' => true,
        'level_code' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'allowance_rates' => true,
        'users' => true,
    ];
}
