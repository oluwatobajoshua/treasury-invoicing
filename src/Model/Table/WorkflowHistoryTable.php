<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * WorkflowHistory Model
 *
 * @property \App\Model\Table\TravelRequestsTable&\Cake\ORM\Association\BelongsTo $TravelRequests
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\WorkflowHistory newEmptyEntity()
 * @method \App\Model\Entity\WorkflowHistory newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowHistory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowHistory get($primaryKey, $options = [])
 * @method \App\Model\Entity\WorkflowHistory findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\WorkflowHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowHistory[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowHistory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\WorkflowHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\WorkflowHistory[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\WorkflowHistory[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\WorkflowHistory[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\WorkflowHistory[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WorkflowHistoryTable extends Table
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

        $this->setTable('workflow_history');
        $this->setDisplayField('to_status');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('TravelRequests', [
            'foreignKey' => 'travel_request_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->integer('travel_request_id')
            ->notEmptyString('travel_request_id');

        $validator
            ->scalar('from_status')
            ->maxLength('from_status', 50)
            ->allowEmptyString('from_status');

        $validator
            ->scalar('to_status')
            ->maxLength('to_status', 50)
            ->requirePresence('to_status', 'create')
            ->notEmptyString('to_status');

        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id');

        $validator
            ->scalar('comments')
            ->allowEmptyString('comments');

        $validator
            ->scalar('metadata')
            ->maxLength('metadata', 4294967295)
            ->allowEmptyString('metadata');

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
        $rules->add($rules->existsIn('travel_request_id', 'TravelRequests'), ['errorField' => 'travel_request_id']);
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
