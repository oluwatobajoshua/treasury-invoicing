<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ApproverSettings Model
 * 
 * Manages notification settings for invoice approval workflows
 * 
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class ApproverSettingsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config Configuration
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('approver_settings');
        $this->setDisplayField('role');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('role')
            ->maxLength('role', 50)
            ->requirePresence('role', 'create')
            ->notEmptyString('role')
            // Extended roles to support straight notification flows for Sales & Sustainability invoices
            ->inList('role', ['treasurer', 'export', 'sales', 'sustainability'], 'Invalid role');

        $validator
            ->scalar('invoice_type')
            ->maxLength('invoice_type', 20)
            ->requirePresence('invoice_type', 'create')
            ->notEmptyString('invoice_type')
            // Add 'sales' and 'sustainability' simple notification workflows
            ->inList('invoice_type', ['fresh', 'final', 'sales', 'sustainability'], 'Invalid invoice type');

        $validator
            ->scalar('stage')
            ->maxLength('stage', 50)
            ->requirePresence('stage', 'create')
            ->notEmptyString('stage')
            // For sales & sustainability we introduce 'sent' (no approval cycle); legacy stages retained
            ->inList('stage', ['pending_approval', 'approved', 'rejected', 'sent'], 'Invalid stage');

        $validator
            ->scalar('to_emails')
            ->allowEmptyString('to_emails')
            ->add('to_emails', 'custom', [
                'rule' => function ($value) {
                    if (empty($value)) return true;
                    $emails = array_map('trim', explode(',', $value));
                    foreach ($emails as $email) {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            return false;
                        }
                    }
                    return true;
                },
                'message' => 'Please enter valid email addresses separated by commas'
            ]);

        $validator
            ->scalar('cc_emails')
            ->allowEmptyString('cc_emails')
            ->add('cc_emails', 'custom', [
                'rule' => function ($value) {
                    if (empty($value)) return true;
                    $emails = array_map('trim', explode(',', $value));
                    foreach ($emails as $email) {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            return false;
                        }
                    }
                    return true;
                },
                'message' => 'Please enter valid email addresses separated by commas'
            ]);

        $validator
            ->boolean('is_active')
            ->notEmptyString('is_active');

        return $validator;
    }

    /**
     * Get notification recipients for a specific workflow stage
     *
     * @param string $invoiceType 'fresh' or 'final'
     * @param string $stage 'pending_approval', 'approved', or 'rejected'
     * @return array Array of settings with to_emails and cc_emails
     */
    public function getRecipients(string $invoiceType, string $stage): array
    {
        $settings = $this->find()
            ->where([
                'invoice_type' => $invoiceType,
                'stage' => $stage,
                'is_active' => true
            ])
            ->all()
            ->toArray();

        $recipients = [];
        foreach ($settings as $setting) {
            $toEmails = !empty($setting->to_emails) 
                ? array_map('trim', explode(',', $setting->to_emails)) 
                : [];
            $ccEmails = !empty($setting->cc_emails) 
                ? array_map('trim', explode(',', $setting->cc_emails)) 
                : [];

            $recipients[] = [
                'role' => $setting->role,
                'to' => $toEmails,
                'cc' => $ccEmails
            ];
        }

        return $recipients;
    }
}
