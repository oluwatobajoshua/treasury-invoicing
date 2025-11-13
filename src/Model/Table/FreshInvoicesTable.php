<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use ArrayObject;

/**
 * FreshInvoices Model
 *
 * @property \App\Model\Table\ClientsTable&\Cake\ORM\Association\BelongsTo $Clients
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\ContractsTable&\Cake\ORM\Association\BelongsTo $Contracts
 * @property \App\Model\Table\VesselsTable&\Cake\ORM\Association\BelongsTo $Vessels
 * @property \App\Model\Table\SgcAccountsTable&\Cake\ORM\Association\BelongsTo $SgcAccounts
 * @property \App\Model\Table\FinalInvoicesTable&\Cake\ORM\Association\HasMany $FinalInvoices
 *
 * @method \App\Model\Entity\FreshInvoice newEmptyEntity()
 * @method \App\Model\Entity\FreshInvoice newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FreshInvoice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FreshInvoice get($primaryKey, $options = [])
 * @method \App\Model\Entity\FreshInvoice findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FreshInvoice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FreshInvoice[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FreshInvoice|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FreshInvoice saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FreshInvoice[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FreshInvoice[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FreshInvoice[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FreshInvoice[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FreshInvoicesTable extends Table
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

        $this->setTable('fresh_invoices');
        $this->setDisplayField('invoice_number');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Contracts', [
            'foreignKey' => 'contract_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Vessels', [
            'foreignKey' => 'vessel_id',
            'joinType' => 'LEFT',
        ]);
        $this->belongsTo('SgcAccounts', [
            'foreignKey' => 'sgc_account_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('FinalInvoices', [
            'foreignKey' => 'fresh_invoice_id',
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
            ->scalar('invoice_number')
            ->maxLength('invoice_number', 50)
            ->allowEmptyString('invoice_number', null, 'create')
            ->add('invoice_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('vessel_name')
            ->maxLength('vessel_name', 255)
            ->allowEmptyString('vessel_name');

        $validator
            ->scalar('bl_number')
            ->maxLength('bl_number', 100)
            ->allowEmptyString('bl_number');

        $validator
            ->decimal('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmptyString('quantity');

        $validator
            ->decimal('unit_price')
            ->requirePresence('unit_price', 'create')
            ->notEmptyString('unit_price');

        $validator
            ->decimal('payment_percentage')
            ->requirePresence('payment_percentage', 'create')
            ->notEmptyString('payment_percentage');

        $validator
            ->decimal('total_value')
            ->allowEmptyString('total_value');

        $validator
            ->scalar('bulk_or_bag')
            ->maxLength('bulk_or_bag', 20)
            ->allowEmptyString('bulk_or_bag');

        $validator
            ->scalar('notes')
            ->allowEmptyString('notes');

        $validator
            ->scalar('status')
            ->maxLength('status', 50)
            ->notEmptyString('status');

        $validator
            ->scalar('treasurer_approval_status')
            ->maxLength('treasurer_approval_status', 50)
            ->notEmptyString('treasurer_approval_status');

        $validator
            ->dateTime('treasurer_approval_date')
            ->allowEmptyDateTime('treasurer_approval_date');

        $validator
            ->scalar('treasurer_comments')
            ->allowEmptyString('treasurer_comments');

        $validator
            ->dateTime('sent_to_export_date')
            ->allowEmptyDateTime('sent_to_export_date');

        $validator
            ->date('invoice_date')
            ->allowEmptyDate('invoice_date');

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
        $rules->add($rules->existsIn('product_id', 'Products'), ['errorField' => 'product_id']);
        $rules->add($rules->existsIn('contract_id', 'Contracts'), ['errorField' => 'contract_id']);
        $rules->add($rules->existsIn('vessel_id', 'Vessels'), ['errorField' => 'vessel_id']);
        $rules->add($rules->existsIn('sgc_account_id', 'SgcAccounts'), ['errorField' => 'sgc_account_id']);

        return $rules;
    }

    /**
     * Before save callback to calculate total value
     *
     * @param \Cake\Event\EventInterface $event The event
     * @param \Cake\Datasource\EntityInterface $entity The entity
     * @param \ArrayObject $options The options
     * @return void
     */
    public function beforeSave(EventInterface $event, $entity, ArrayObject $options)
    {
        // Calculate total value if quantity, unit_price, and payment_percentage are set
        if ($entity->has('quantity') && $entity->has('unit_price') && $entity->has('payment_percentage')) {
            $entity->total_value = $entity->quantity * $entity->unit_price * ($entity->payment_percentage / 100);
        }

        // Auto-generate invoice number if not set
        if ($entity->isNew() && empty($entity->invoice_number)) {
            $entity->invoice_number = $this->generateInvoiceNumber();
        }

        // Set invoice_date to today if not set
        if ($entity->isNew() && empty($entity->invoice_date)) {
            $entity->invoice_date = date('Y-m-d');
        }
    }

    /**
     * Generate the next invoice number
     *
     * @return string
     */
    public function generateInvoiceNumber(): string
    {
        $lastInvoice = $this->find()
            ->select(['invoice_number'])
            ->order(['invoice_number' => 'DESC'])
            ->first();

        if ($lastInvoice) {
            // Extract the numeric part and increment
            $lastNumber = (int)preg_replace('/[^0-9]/', '', $lastInvoice->invoice_number);
            $nextNumber = str_pad((string)($lastNumber + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return $nextNumber;
    }
}
