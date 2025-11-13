<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TravelRequests Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\WorkflowHistoryTable&\Cake\ORM\Association\HasMany $WorkflowHistory
 *
 * @method \App\Model\Entity\TravelRequest newEmptyEntity()
 * @method \App\Model\Entity\TravelRequest newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\TravelRequest[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TravelRequest get($primaryKey, $options = [])
 * @method \App\Model\Entity\TravelRequest findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\TravelRequest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TravelRequest[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TravelRequest|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TravelRequest saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TravelRequest[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TravelRequest[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\TravelRequest[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TravelRequest[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TravelRequestsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('travel_requests');
        $this->setDisplayField('destination');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('LineManagers', [
            'className' => 'Users',
            'foreignKey' => 'line_manager_id',
        ]);
        $this->belongsTo('Admins', [
            'className' => 'Users',
            'foreignKey' => 'admin_id',
        ]);
        $this->hasMany('WorkflowHistory', [
            'foreignKey' => 'travel_request_id',
        ]);
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
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->scalar('request_number')
            ->maxLength('request_number', 50)
            ->allowEmptyString('request_number')
            ->add('request_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('purpose_of_travel')
            ->requirePresence('purpose_of_travel', 'create')
            ->notEmptyString('purpose_of_travel');

        $validator
            ->scalar('destination')
            ->maxLength('destination', 255)
            ->requirePresence('destination', 'create')
            ->notEmptyString('destination');

        $validator
            ->scalar('travel_type')
            ->requirePresence('travel_type', 'create')
            ->notEmptyString('travel_type');

        $validator
            ->date('departure_date')
            ->requirePresence('departure_date', 'create')
            ->notEmptyDate('departure_date');

        $validator
            ->date('return_date')
            ->requirePresence('return_date', 'create')
            ->notEmptyDate('return_date');

        $validator
            ->integer('duration_days')
            ->allowEmptyString('duration_days');

        $validator
            ->boolean('accommodation_required')
            ->notEmptyString('accommodation_required');

        $validator
            ->scalar('status')
            ->notEmptyString('status');

        $validator
            ->integer('current_step')
            ->notEmptyString('current_step');

        $validator
            ->decimal('accommodation_allowance')
            ->notEmptyString('accommodation_allowance');

        $validator
            ->decimal('feeding_allowance')
            ->notEmptyString('feeding_allowance');

        $validator
            ->decimal('transport_allowance')
            ->notEmptyString('transport_allowance');

        $validator
            ->decimal('incidental_allowance')
            ->notEmptyString('incidental_allowance');

        $validator
            ->decimal('total_allowance')
            ->notEmptyString('total_allowance');

        $validator
            ->integer('line_manager_id')
            ->allowEmptyString('line_manager_id');

        $validator
            ->dateTime('line_manager_approved_at')
            ->allowEmptyDateTime('line_manager_approved_at');

        $validator
            ->scalar('line_manager_comments')
            ->allowEmptyString('line_manager_comments');

        $validator
            ->integer('admin_id')
            ->allowEmptyString('admin_id');

        $validator
            ->dateTime('requisition_prepared_at')
            ->allowEmptyDateTime('requisition_prepared_at');

        $validator
            ->scalar('requisition_number')
            ->maxLength('requisition_number', 50)
            ->allowEmptyString('requisition_number');

        $validator
            ->dateTime('power_automate_uploaded_at')
            ->allowEmptyDateTime('power_automate_uploaded_at');

        $validator
            ->scalar('power_automate_reference')
            ->maxLength('power_automate_reference', 255)
            ->allowEmptyString('power_automate_reference');

        $validator
            ->dateTime('completed_at')
            ->allowEmptyDateTime('completed_at');

        $validator
            ->scalar('rejection_reason')
            ->allowEmptyString('rejection_reason');

        $validator
            ->dateTime('rejected_at')
            ->allowEmptyDateTime('rejected_at');

        $validator
            ->integer('rejected_by')
            ->allowEmptyString('rejected_by');

        $validator
            ->scalar('notes')
            ->allowEmptyString('notes');

        $validator
            ->scalar('attachments')
            ->maxLength('attachments', 4294967295)
            ->allowEmptyString('attachments');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['request_number'], ['allowMultipleNulls' => true]), ['errorField' => 'request_number']);
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->existsIn('line_manager_id', 'Users'), ['errorField' => 'line_manager_id']);
        $rules->add($rules->existsIn('admin_id', 'Users'), ['errorField' => 'admin_id']);

        return $rules;
    }
}
