<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MeasurementUnits Model
 *
 * @method \App\Model\Entity\MeasurementUnit newEmptyEntity()
 * @method \App\Model\Entity\MeasurementUnit newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementUnit[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementUnit get($primaryKey, $options = [])
 * @method \App\Model\Entity\MeasurementUnit findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MeasurementUnit patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementUnit[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementUnit|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MeasurementUnit saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MeasurementUnit[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MeasurementUnit[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MeasurementUnit[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MeasurementUnit[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class MeasurementUnitsTable extends Table
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

        $this->setTable('measurement_units');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->scalar('titulo')
            ->maxLength('titulo', 50)
            ->requirePresence('titulo', 'create')
            ->notEmptyString('titulo');

        return $validator;
    }
}
