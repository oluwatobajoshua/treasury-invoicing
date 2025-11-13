<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Contract Entity
 *
 * @property int $id
 * @property string $contract_id
 * @property int $client_id
 * @property int $product_id
 * @property float|null $quantity
 * @property float|null $unit_price
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\FreshInvoice[] $fresh_invoices
 */
class Contract extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'contract_id' => true,
        'client_id' => true,
        'product_id' => true,
        'quantity' => true,
        'unit_price' => true,
        'created' => true,
        'modified' => true,
        'client' => true,
        'product' => true,
        'fresh_invoices' => true,
    ];
}
