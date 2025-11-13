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
 * FinalInvoices Model
 *
 * @property \App\Model\Table\FreshInvoicesTable&\Cake\ORM\Association\BelongsTo $FreshInvoices
 * @property \App\Model\Table\SgcAccountsTable&\Cake\ORM\Association\BelongsTo $SgcAccounts
 *
 * @method \App\Model\Entity\FinalInvoice newEmptyEntity()
 * @method \App\Model\Entity\FinalInvoice newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FinalInvoice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FinalInvoice get($primaryKey, $options = [])
 * @method \App\Model\Entity\FinalInvoice findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FinalInvoice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FinalInvoice[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FinalInvoice|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FinalInvoice saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FinalInvoice[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FinalInvoice[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FinalInvoice[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FinalInvoice[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FinalInvoicesTable extends Table
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

        $this->setTable('final_invoices');
        $this->setDisplayField('invoice_number');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('FreshInvoices', [
            'foreignKey' => 'fresh_invoice_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('SgcAccounts', [
            'foreignKey' => 'sgc_account_id',
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
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('invoice_number')
            ->maxLength('invoice_number', 50)
            ->allowEmptyString('invoice_number', null, 'create')
            ->add('invoice_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('original_invoice_number')
            ->maxLength('original_invoice_number', 50)
            ->allowEmptyString('original_invoice_number');

        $validator
            ->decimal('landed_quantity')
            ->requirePresence('landed_quantity', 'create')
            ->notEmptyString('landed_quantity');

        $validator
            ->decimal('quantity_variance')
            ->allowEmptyString('quantity_variance');

        $validator
            ->scalar('vessel_name')
            ->maxLength('vessel_name', 255)
            ->allowEmptyString('vessel_name');

        $validator
            ->scalar('bl_number')
            ->maxLength('bl_number', 100)
            ->allowEmptyString('bl_number');

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
            ->dateTime('sent_to_sales_date')
            ->allowEmptyDateTime('sent_to_sales_date');

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
        $rules->add($rules->existsIn('fresh_invoice_id', 'FreshInvoices'), ['errorField' => 'fresh_invoice_id']);
        $rules->add($rules->existsIn('sgc_account_id', 'SgcAccounts'), ['errorField' => 'sgc_account_id']);

        return $rules;
    }

    /**
     * Before save callback to calculate total value and variance
     *
     * @param \Cake\Event\EventInterface $event The event
     * @param \Cake\Datasource\EntityInterface $entity The entity
     * @param \ArrayObject $options The options
     * @return void
     */
    public function beforeSave(EventInterface $event, $entity, ArrayObject $options)
    {
        // Calculate total value if landed_quantity, unit_price, and payment_percentage are set
        if ($entity->has('landed_quantity') && $entity->has('unit_price') && $entity->has('payment_percentage')) {
            $entity->total_value = $entity->landed_quantity * $entity->unit_price * ($entity->payment_percentage / 100);
        }

        // Calculate quantity variance if fresh invoice is available
        if ($entity->has('fresh_invoice_id') && $entity->has('landed_quantity')) {
            $freshInvoice = $this->FreshInvoices->get($entity->fresh_invoice_id);
            $entity->quantity_variance = $entity->landed_quantity - $freshInvoice->quantity;
        }

        // Auto-generate invoice number with FVP prefix ensuring uniqueness
        if ($entity->isNew() && empty($entity->invoice_number)) {
            $originalNumber = null;
            if ($entity->has('fresh_invoice_id')) {
                $freshInvoice = $this->FreshInvoices->get($entity->fresh_invoice_id);
                $originalNumber = $freshInvoice->invoice_number;
            } elseif (!empty($entity->original_invoice_number)) {
                $originalNumber = $entity->original_invoice_number;
            }
            $entity->invoice_number = $this->generateInvoiceNumber($originalNumber);
        }

        // Set invoice_date to today if not set
        if ($entity->isNew() && empty($entity->invoice_date)) {
            $entity->invoice_date = date('Y-m-d');
        }
    }

    /**
     * Generate the next invoice number with FVP prefix
     *
     * @param string|null $originalInvoiceNumber The original invoice number
     * @return string
     */
    public function generateInvoiceNumber(?string $originalInvoiceNumber = null): string
    {
        // Try direct mapping first if original provided
        if ($originalInvoiceNumber) {
            $candidate = 'FVP' . $originalInvoiceNumber;
            if (!$this->exists(['invoice_number' => $candidate])) {
                return $candidate;
            }
        }
        // Fallback: sequential FVP number independent of original
        $lastInvoice = $this->find()
            ->select(['invoice_number'])
            ->where(['invoice_number LIKE' => 'FVP%'])
            ->orderDesc('id')
            ->first();

        if ($lastInvoice && preg_match('/FVP(\d+)/', $lastInvoice->invoice_number, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        } else {
            $nextNumber = 1;
        }
        return 'FVP' . str_pad((string)$nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
