<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Bank Entity
 *
 * @property int $id
 * @property string $bank_name
 * @property string $bank_type
 * @property string|null $account_number
 * @property string $currency
 * @property string|null $bank_address
 * @property string|null $swift_code
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
 * @property bool $is_active
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Bank extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'bank_name' => true,
        'bank_type' => true,
        'account_number' => true,
        'currency' => true,
        'bank_address' => true,
        'swift_code' => true,
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
        'is_active' => true,
        'created' => true,
        'modified' => true,
    ];
}
