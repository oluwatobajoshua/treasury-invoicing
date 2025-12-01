<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SalesInvoices Model
 *
 * @property \App\Model\Table\ClientsTable&\Cake\ORM\Association\BelongsTo $Clients
 * @property \App\Model\Table\SgcAccountsTable&\Cake\ORM\Association\BelongsTo $BankAccounts
 *
 * @method \App\Model\Entity\SalesInvoice newEmptyEntity()
 * @method \App\Model\Entity\SalesInvoice newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SalesInvoice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SalesInvoice get($primaryKey, $options = [])
 * @method \App\Model\Entity\SalesInvoice findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SalesInvoice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SalesInvoice[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SalesInvoice|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SalesInvoice saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SalesInvoice[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SalesInvoice[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SalesInvoice[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SalesInvoice[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SalesInvoicesTable extends Table
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

        $this->setTable('sales_invoices');
        $this->setDisplayField('invoice_number');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditLog');

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER',
        ]);
        
        $this->belongsTo('BankAccounts', [
            'className' => 'SgcAccounts',
            'foreignKey' => 'bank_account_id',
            'joinType' => 'LEFT',
        ]);
        
        $this->belongsTo('Banks', [
            'foreignKey' => 'bank_id',
            'joinType' => 'LEFT',
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
            ->scalar('invoice_number')
            ->maxLength('invoice_number', 50)
            ->requirePresence('invoice_number', 'create')
            ->notEmptyString('invoice_number')
            ->add('invoice_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->date('invoice_date')
            ->requirePresence('invoice_date', 'create')
            ->notEmptyDate('invoice_date');

        $validator
            ->integer('client_id')
            ->requirePresence('client_id', 'create')
            ->notEmptyString('client_id');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->decimal('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmptyString('quantity')
            ->greaterThan('quantity', 0);

        $validator
            ->decimal('unit_price')
            ->requirePresence('unit_price', 'create')
            ->notEmptyString('unit_price')
            ->greaterThan('unit_price', 0);

        $validator
            ->decimal('total_value')
            ->requirePresence('total_value', 'create')
            ->notEmptyString('total_value')
            ->greaterThanOrEqual('total_value', 0);

        $validator
            ->scalar('currency')
            ->maxLength('currency', 3)
            ->notEmptyString('currency');

        $validator
            ->integer('bank_account_id')
            ->allowEmptyString('bank_account_id');

        $validator
            ->scalar('purpose')
            ->maxLength('purpose', 255)
            ->allowEmptyString('purpose');

        $validator
            ->scalar('notes')
            ->allowEmptyString('notes');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->notEmptyString('status')
            ->inList('status', ['draft', 'sent', 'paid', 'cancelled']);

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
        $rules->add($rules->isUnique(['invoice_number']), ['errorField' => 'invoice_number']);
        $rules->add($rules->existsIn('client_id', 'Clients'), ['errorField' => 'client_id']);
        $rules->add($rules->existsIn('bank_account_id', 'BankAccounts'), ['errorField' => 'bank_account_id']);

        return $rules;
    }
}
