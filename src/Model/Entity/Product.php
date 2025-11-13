<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $category
 * @property float|null $unit_price
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Contract[] $contracts
 * @property \App\Model\Entity\FreshInvoice[] $fresh_invoices
 */
class Product extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'name' => true,
        'category' => true,
        'unit_price' => true,
        'created' => true,
        'modified' => true,
        'contracts' => true,
        'fresh_invoices' => true,
    ];
}
