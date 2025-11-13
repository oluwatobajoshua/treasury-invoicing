<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TravelRequest Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $request_number
 * @property string $purpose_of_travel
 * @property string $destination
 * @property string $travel_type
 * @property \Cake\I18n\FrozenDate $departure_date
 * @property \Cake\I18n\FrozenDate $return_date
 * @property int|null $duration_days
 * @property bool $accommodation_required
 * @property string $status
 * @property int $current_step
 * @property string $accommodation_allowance
 * @property string $feeding_allowance
 * @property string $transport_allowance
 * @property string $incidental_allowance
 * @property string $total_allowance
 * @property int|null $line_manager_id
 * @property \Cake\I18n\FrozenTime|null $line_manager_approved_at
 * @property string|null $line_manager_comments
 * @property int|null $admin_id
 * @property \Cake\I18n\FrozenTime|null $requisition_prepared_at
 * @property string|null $requisition_number
 * @property \Cake\I18n\FrozenTime|null $power_automate_uploaded_at
 * @property string|null $power_automate_reference
 * @property \Cake\I18n\FrozenTime|null $completed_at
 * @property string|null $rejection_reason
 * @property \Cake\I18n\FrozenTime|null $rejected_at
 * @property int|null $rejected_by
 * @property string|null $notes
 * @property string|null $attachments
 * @property string|null $email_conversation_file
 * @property \Cake\I18n\FrozenTime|null $email_conversation_uploaded_at
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\WorkflowHistory[] $workflow_history
 */
class TravelRequest extends Entity
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
        'user_id' => true,
        'request_number' => true,
        'purpose_of_travel' => true,
        'destination' => true,
        'travel_type' => true,
        'departure_date' => true,
        'return_date' => true,
        'duration_days' => true,
        'accommodation_required' => true,
        'status' => true,
        'current_step' => true,
        'accommodation_allowance' => true,
        'feeding_allowance' => true,
        'transport_allowance' => true,
        'incidental_allowance' => true,
        'total_allowance' => true,
        'line_manager_id' => true,
        'line_manager_approved_at' => true,
        'line_manager_comments' => true,
        'admin_id' => true,
        'requisition_prepared_at' => true,
        'requisition_number' => true,
        'power_automate_uploaded_at' => true,
        'power_automate_reference' => true,
        'completed_at' => true,
        'rejection_reason' => true,
        'rejected_at' => true,
        'rejected_by' => true,
        'notes' => true,
        'attachments' => true,
        'email_conversation_file' => true,
        'email_conversation_uploaded_at' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'workflow_history' => true,
    ];
}
