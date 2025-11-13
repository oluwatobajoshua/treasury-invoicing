<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Vessel Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $flag_country
 * @property int|null $dwt
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\FreshInvoice[] $fresh_invoices
 */
class Vessel extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'name' => true,
        'flag_country' => true,
        'dwt' => true,
        'created' => true,
        'modified' => true,
        'fresh_invoices' => true,
    ];
}
