<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FinalInvoice Entity
 *
 * @property int $id
 * @property string $invoice_number
 * @property int $fresh_invoice_id
 * @property string $original_invoice_number
 * @property float $landed_quantity
 * @property float|null $quantity_variance
 * @property string|null $vessel_name
 * @property string|null $bl_number
 * @property float $unit_price
 * @property float $payment_percentage
 * @property float|null $total_value
 * @property int $sgc_account_id
 * @property string|null $notes
 * @property string $status
 * @property string $treasurer_approval_status
 * @property \Cake\I18n\FrozenTime|null $treasurer_approval_date
 * @property string|null $treasurer_comments
 * @property \Cake\I18n\FrozenTime|null $sent_to_sales_date
 * @property \Cake\I18n\FrozenDate|null $invoice_date
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\FreshInvoice $fresh_invoice
 * @property \App\Model\Entity\SgcAccount $sgc_account
 */
class FinalInvoice extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'invoice_number' => true,
        'fresh_invoice_id' => true,
        'original_invoice_number' => true,
        'landed_quantity' => true,
        'quantity_variance' => true,
        'vessel_name' => true,
        'bl_number' => true,
        'unit_price' => true,
        'amount_paid' => true,
        'payment_percentage' => true,
        'total_value' => true,
        'sgc_account_id' => true,
        'notes' => true,
        'status' => true,
        'treasurer_approval_status' => true,
        'treasurer_approval_date' => true,
        'treasurer_comments' => true,
        'sent_to_sales_date' => true,
        'invoice_date' => true,
        'created' => true,
        'modified' => true,
        'fresh_invoice' => true,
        'sgc_account' => true,
    ];
}
