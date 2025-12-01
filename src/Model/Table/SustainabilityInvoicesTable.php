<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SustainabilityInvoices Model
 *
 * @property \App\Model\Table\ClientsTable&\Cake\ORM\Association\BelongsTo $Clients
 *
 * @method \App\Model\Entity\SustainabilityInvoice newEmptyEntity()
 * @method \App\Model\Entity\SustainabilityInvoice newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SustainabilityInvoice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SustainabilityInvoice get($primaryKey, $options = [])
 * @method \App\Model\Entity\SustainabilityInvoice findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SustainabilityInvoice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SustainabilityInvoice[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SustainabilityInvoice|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SustainabilityInvoice saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SustainabilityInvoice[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SustainabilityInvoice[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SustainabilityInvoice[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SustainabilityInvoice[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SustainabilityInvoicesTable extends Table
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

        $this->setTable('sustainability_invoices');
        $this->setDisplayField('invoice_number');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditLog');

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER',
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
            ->scalar('seller_id')
            ->maxLength('seller_id', 50)
            ->requirePresence('seller_id', 'create')
            ->notEmptyString('seller_id');

        $validator
            ->integer('client_id')
            ->requirePresence('client_id', 'create')
            ->notEmptyString('client_id');

        $validator
            ->scalar('buyer_id')
            ->maxLength('buyer_id', 50)
            ->requirePresence('buyer_id', 'create')
            ->notEmptyString('buyer_id');

        $validator
            ->decimal('quantity_mt')
            ->requirePresence('quantity_mt', 'create')
            ->notEmptyString('quantity_mt')
            ->greaterThan('quantity_mt', 0);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->decimal('sustainability_investment')
            ->requirePresence('sustainability_investment', 'create')
            ->notEmptyString('sustainability_investment')
            ->greaterThanOrEqual('sustainability_investment', 0);

        $validator
            ->decimal('sustainability_differential')
            ->requirePresence('sustainability_differential', 'create')
            ->notEmptyString('sustainability_differential')
            ->greaterThanOrEqual('sustainability_differential', 0);

        $validator
            ->decimal('total_value')
            ->requirePresence('total_value', 'create')
            ->notEmptyString('total_value')
            ->greaterThanOrEqual('total_value', 0);

        $validator
            ->decimal('net_receivable')
            ->requirePresence('net_receivable', 'create')
            ->notEmptyString('net_receivable')
            ->greaterThanOrEqual('net_receivable', 0);

        $validator
            ->scalar('currency')
            ->maxLength('currency', 3)
            ->notEmptyString('currency');

        $validator
            ->scalar('correspondent_bank')
            ->maxLength('correspondent_bank', 255)
            ->allowEmptyString('correspondent_bank');

        $validator
            ->scalar('correspondent_address')
            ->maxLength('correspondent_address', 255)
            ->allowEmptyString('correspondent_address');

        $validator
            ->scalar('correspondent_swift')
            ->maxLength('correspondent_swift', 20)
            ->allowEmptyString('correspondent_swift');

        $validator
            ->scalar('aba_routing')
            ->maxLength('aba_routing', 20)
            ->allowEmptyString('aba_routing');

        $validator
            ->scalar('beneficiary_bank')
            ->maxLength('beneficiary_bank', 255)
            ->allowEmptyString('beneficiary_bank');

        $validator
            ->scalar('beneficiary_account_no')
            ->maxLength('beneficiary_account_no', 50)
            ->allowEmptyString('beneficiary_account_no');

        $validator
            ->scalar('beneficiary_name')
            ->maxLength('beneficiary_name', 255)
            ->allowEmptyString('beneficiary_name');

        $validator
            ->scalar('beneficiary_acct_no')
            ->maxLength('beneficiary_acct_no', 50)
            ->allowEmptyString('beneficiary_acct_no');

        $validator
            ->scalar('beneficiary_swift')
            ->maxLength('beneficiary_swift', 20)
            ->allowEmptyString('beneficiary_swift');

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

        return $rules;
    }
}
