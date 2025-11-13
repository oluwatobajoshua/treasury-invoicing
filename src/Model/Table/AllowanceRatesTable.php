<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AllowanceRates Model
 *
 * @property \App\Model\Table\JobLevelsTable&\Cake\ORM\Association\BelongsTo $JobLevels
 *
 * @method \App\Model\Entity\AllowanceRate newEmptyEntity()
 * @method \App\Model\Entity\AllowanceRate newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AllowanceRate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AllowanceRate get($primaryKey, $options = [])
 * @method \App\Model\Entity\AllowanceRate findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AllowanceRate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AllowanceRate[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AllowanceRate|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AllowanceRate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AllowanceRate[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AllowanceRate[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AllowanceRate[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AllowanceRate[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AllowanceRatesTable extends Table
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

        $this->setTable('allowance_rates');
        $this->setDisplayField('travel_type');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('JobLevels', [
            'foreignKey' => 'job_level_id',
            'joinType' => 'INNER',
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
            ->integer('job_level_id')
            ->notEmptyString('job_level_id');

        $validator
            ->scalar('travel_type')
            ->requirePresence('travel_type', 'create')
            ->notEmptyString('travel_type');

        $validator
            ->decimal('accommodation_rate')
            ->notEmptyString('accommodation_rate');

        $validator
            ->decimal('feeding_rate')
            ->notEmptyString('feeding_rate');

        $validator
            ->decimal('transport_rate')
            ->notEmptyString('transport_rate');

        $validator
            ->decimal('incidental_rate')
            ->notEmptyString('incidental_rate');

        $validator
            ->scalar('currency')
            ->maxLength('currency', 3)
            ->notEmptyString('currency');

        $validator
            ->scalar('flight_class')
            ->maxLength('flight_class', 50)
            ->allowEmptyString('flight_class');

        $validator
            ->scalar('hotel_standard')
            ->maxLength('hotel_standard', 50)
            ->allowEmptyString('hotel_standard');

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
        $rules->add($rules->existsIn('job_level_id', 'JobLevels'), ['errorField' => 'job_level_id']);

        return $rules;
    }
}
