<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SustainabilityInvoice Entity
 *
 * @property int $id
 * @property string $invoice_number
 * @property \Cake\I18n\FrozenDate $invoice_date
 * @property string $seller_id
 * @property int $client_id
 * @property string $buyer_id
 * @property string $quantity_mt
 * @property string $description
 * @property string $sustainability_investment
 * @property string $sustainability_differential
 * @property string $total_value
 * @property string $net_receivable
 * @property string $currency
 * @property string|null $correspondent_bank
 * @property string|null $correspondent_address
 * @property string|null $correspondent_swift
 * @property string|null $aba_routing
 * @property string|null $beneficiary_bank
 * @property string|null $beneficiary_account_no
 * @property string|null $beneficiary_name
 * @property string|null $beneficiary_acct_no
 * @property string|null $beneficiary_swift
 * @property string|null $purpose
 * @property string|null $notes
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Client $client
 */
class SustainabilityInvoice extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'invoice_number' => true,
        'invoice_date' => true,
        'seller_id' => true,
        'client_id' => true,
        'buyer_id' => true,
        'quantity_mt' => true,
        'description' => true,
        'sustainability_investment' => true,
        'sustainability_differential' => true,
        'total_value' => true,
        'net_receivable' => true,
        'currency' => true,
        'correspondent_bank' => true,
        'correspondent_address' => true,
        'correspondent_swift' => true,
        'aba_routing' => true,
        'beneficiary_bank' => true,
        'beneficiary_account_no' => true,
        'beneficiary_name' => true,
        'beneficiary_acct_no' => true,
        'beneficiary_swift' => true,
        'purpose' => true,
        'notes' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'client' => true,
    ];
}
