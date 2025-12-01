<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SalesInvoice Entity
 *
 * @property int $id
 * @property string $invoice_number
 * @property \Cake\I18n\FrozenDate $invoice_date
 * @property int $client_id
 * @property string $description
 * @property string $quantity
 * @property string $unit_price
 * @property string $total_value
 * @property string $currency
 * @property int|null $bank_account_id
 * @property string|null $purpose
 * @property string|null $notes
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\SgcAccount $bank_account
 */
class SalesInvoice extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'invoice_number' => true,
        'invoice_date' => true,
        'client_id' => true,
        'description' => true,
        'quantity' => true,
        'unit_price' => true,
        'total_value' => true,
        'currency' => true,
        'bank_account_id' => true,
        'purpose' => true,
        'notes' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'client' => true,
        'bank_account' => true,
    ];
}
