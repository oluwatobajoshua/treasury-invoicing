<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SgcAccounts Model
 *
 * @property \App\Model\Table\FreshInvoicesTable&\Cake\ORM\Association\HasMany $FreshInvoices
 * @property \App\Model\Table\FinalInvoicesTable&\Cake\ORM\Association\HasMany $FinalInvoices
 *
 * @method \App\Model\Entity\SgcAccount newEmptyEntity()
 * @method \App\Model\Entity\SgcAccount newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SgcAccount[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SgcAccount get($primaryKey, $options = [])
 * @method \App\Model\Entity\SgcAccount findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SgcAccount patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SgcAccount[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SgcAccount|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SgcAccount saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SgcAccount[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SgcAccount[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SgcAccount[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SgcAccount[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SgcAccountsTable extends Table
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

        $this->setTable('sgc_accounts');
        $this->setDisplayField('account_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditLog');

        $this->hasMany('FreshInvoices', [
            'foreignKey' => 'sgc_account_id',
        ]);
        $this->hasMany('FinalInvoices', [
            'foreignKey' => 'sgc_account_id',
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
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('account_id')
            ->maxLength('account_id', 50)
            ->requirePresence('account_id', 'create')
            ->notEmptyString('account_id')
            ->add('account_id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('account_name')
            ->maxLength('account_name', 255)
            ->requirePresence('account_name', 'create')
            ->notEmptyString('account_name');

        $validator
            ->scalar('currency')
            ->maxLength('currency', 10)
            ->requirePresence('currency', 'create')
            ->notEmptyString('currency');

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
        $rules->add($rules->isUnique(['account_id']), ['errorField' => 'account_id']);

        return $rules;
    }
}
