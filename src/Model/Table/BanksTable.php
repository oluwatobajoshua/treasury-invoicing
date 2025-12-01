<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Banks Model
 *
 * @method \App\Model\Entity\Bank newEmptyEntity()
 * @method \App\Model\Entity\Bank newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Bank[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Bank get($primaryKey, $options = [])
 * @method \App\Model\Entity\Bank findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Bank patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Bank[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Bank|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bank saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bank[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Bank[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Bank[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Bank[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BanksTable extends Table
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

        $this->setTable('banks');
        $this->setDisplayField('bank_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditLog');
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
            ->scalar('bank_name')
            ->maxLength('bank_name', 255)
            ->requirePresence('bank_name', 'create')
            ->notEmptyString('bank_name');

        $validator
            ->scalar('bank_type')
            ->maxLength('bank_type', 20)
            ->requirePresence('bank_type', 'create')
            ->notEmptyString('bank_type')
            ->inList('bank_type', ['sales', 'sustainability', 'shipment', 'both'], 'Please select a valid bank type');

        $validator
            ->scalar('account_number')
            ->maxLength('account_number', 100)
            ->allowEmptyString('account_number');

        $validator
            ->scalar('currency')
            ->maxLength('currency', 3)
            ->requirePresence('currency', 'create')
            ->notEmptyString('currency');

        $validator
            ->scalar('bank_address')
            ->maxLength('bank_address', 500)
            ->allowEmptyString('bank_address');

        $validator
            ->scalar('swift_code')
            ->maxLength('swift_code', 50)
            ->allowEmptyString('swift_code');

        $validator
            ->scalar('correspondent_bank')
            ->maxLength('correspondent_bank', 255)
            ->allowEmptyString('correspondent_bank');

        $validator
            ->scalar('correspondent_address')
            ->maxLength('correspondent_address', 500)
            ->allowEmptyString('correspondent_address');

        $validator
            ->scalar('correspondent_swift')
            ->maxLength('correspondent_swift', 50)
            ->allowEmptyString('correspondent_swift');

        $validator
            ->scalar('aba_routing')
            ->maxLength('aba_routing', 50)
            ->allowEmptyString('aba_routing');

        $validator
            ->scalar('beneficiary_bank')
            ->maxLength('beneficiary_bank', 255)
            ->allowEmptyString('beneficiary_bank');

        $validator
            ->scalar('beneficiary_account_no')
            ->maxLength('beneficiary_account_no', 100)
            ->allowEmptyString('beneficiary_account_no');

        $validator
            ->scalar('beneficiary_name')
            ->maxLength('beneficiary_name', 255)
            ->allowEmptyString('beneficiary_name');

        $validator
            ->scalar('beneficiary_acct_no')
            ->maxLength('beneficiary_acct_no', 100)
            ->allowEmptyString('beneficiary_acct_no');

        $validator
            ->scalar('beneficiary_swift')
            ->maxLength('beneficiary_swift', 50)
            ->allowEmptyString('beneficiary_swift');

        $validator
            ->scalar('purpose')
            ->maxLength('purpose', 255)
            ->allowEmptyString('purpose');

        $validator
            ->scalar('notes')
            ->allowEmptyString('notes');

        $validator
            ->boolean('is_active')
            ->notEmptyString('is_active');

        return $validator;
    }
}
