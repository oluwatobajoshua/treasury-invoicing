<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SgcAccount Entity
 *
 * @property int $id
 * @property string $account_id
 * @property string $account_name
 * @property string $currency
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\FreshInvoice[] $fresh_invoices
 * @property \App\Model\Entity\FinalInvoice[] $final_invoices
 */
class SgcAccount extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'account_id' => true,
        'account_name' => true,
        'currency' => true,
        'created' => true,
        'modified' => true,
        'fresh_invoices' => true,
        'final_invoices' => true,
    ];
}
