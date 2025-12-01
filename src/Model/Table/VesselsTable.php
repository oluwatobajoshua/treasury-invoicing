<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Vessels Model
 *
 * @property \App\Model\Table\FreshInvoicesTable&\Cake\ORM\Association\HasMany $FreshInvoices
 *
 * @method \App\Model\Entity\Vessel newEmptyEntity()
 * @method \App\Model\Entity\Vessel newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Vessel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Vessel get($primaryKey, $options = [])
 * @method \App\Model\Entity\Vessel findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Vessel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Vessel[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Vessel|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Vessel saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Vessel[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Vessel[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Vessel[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Vessel[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class VesselsTable extends Table
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

        $this->setTable('vessels');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditLog');

        $this->hasMany('FreshInvoices', [
            'foreignKey' => 'vessel_id',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('flag_country')
            ->maxLength('flag_country', 100)
            ->allowEmptyString('flag_country');

        $validator
            ->integer('dwt')
            ->allowEmptyString('dwt');

        return $validator;
    }
}
