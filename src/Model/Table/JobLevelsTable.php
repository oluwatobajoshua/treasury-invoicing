<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JobLevels Model
 *
 * @property \App\Model\Table\AllowanceRatesTable&\Cake\ORM\Association\HasMany $AllowanceRates
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\JobLevel newEmptyEntity()
 * @method \App\Model\Entity\JobLevel newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\JobLevel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JobLevel get($primaryKey, $options = [])
 * @method \App\Model\Entity\JobLevel findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\JobLevel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JobLevel[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\JobLevel|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JobLevel saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JobLevel[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JobLevel[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\JobLevel[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JobLevel[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class JobLevelsTable extends Table
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

        $this->setTable('job_levels');
        $this->setDisplayField('level_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('AllowanceRates', [
            'foreignKey' => 'job_level_id',
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'job_level_id',
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
            ->scalar('level_name')
            ->maxLength('level_name', 100)
            ->requirePresence('level_name', 'create')
            ->notEmptyString('level_name')
            ->add('level_name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('level_code')
            ->maxLength('level_code', 20)
            ->requirePresence('level_code', 'create')
            ->notEmptyString('level_code')
            ->add('level_code', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

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
        $rules->add($rules->isUnique(['level_name']), ['errorField' => 'level_name']);
        $rules->add($rules->isUnique(['level_code']), ['errorField' => 'level_code']);

        return $rules;
    }
}
