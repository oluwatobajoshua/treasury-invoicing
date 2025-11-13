<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FreshInvoice Entity
 *
 * @property int $id
 * @property string $invoice_number
 * @property int $client_id
 * @property int $product_id
 * @property int $contract_id
 * @property int|null $vessel_id
 * @property string|null $vessel_name
 * @property string|null $bl_number
 * @property float $quantity
 * @property float $unit_price
 * @property float $payment_percentage
 * @property float|null $total_value
 * @property int $sgc_account_id
 * @property string|null $bulk_or_bag
 * @property string|null $notes
 * @property string $status
 * @property string $treasurer_approval_status
 * @property \Cake\I18n\FrozenTime|null $treasurer_approval_date
 * @property string|null $treasurer_comments
 * @property \Cake\I18n\FrozenTime|null $sent_to_export_date
 * @property \Cake\I18n\FrozenDate|null $invoice_date
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Contract $contract
 * @property \App\Model\Entity\Vessel $vessel
 * @property \App\Model\Entity\SgcAccount $sgc_account
 * @property \App\Model\Entity\FinalInvoice[] $final_invoices
 */
class FreshInvoice extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'invoice_number' => true,
        'client_id' => true,
        'product_id' => true,
        'contract_id' => true,
        'vessel_id' => true,
        'vessel_name' => true,
        'bl_number' => true,
        'quantity' => true,
        'unit_price' => true,
        'payment_percentage' => true,
        'total_value' => true,
        'sgc_account_id' => true,
        'bulk_or_bag' => true,
        'notes' => true,
        'status' => true,
        'treasurer_approval_status' => true,
        'treasurer_approval_date' => true,
        'treasurer_comments' => true,
        'sent_to_export_date' => true,
        'invoice_date' => true,
        'created' => true,
        'modified' => true,
        'client' => true,
        'product' => true,
        'contract' => true,
        'vessel' => true,
        'sgc_account' => true,
        'final_invoices' => true,
    ];
}
