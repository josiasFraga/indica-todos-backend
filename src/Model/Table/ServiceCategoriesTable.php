<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ServiceCategories Model
 *
 * @method \App\Model\Entity\ServiceCategory newEmptyEntity()
 * @method \App\Model\Entity\ServiceCategory newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\ServiceCategory findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ServiceCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceCategory[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceCategory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceCategory[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceCategory[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceCategory[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceCategory[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ServiceCategoriesTable extends Table
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

        $this->setTable('service_categories');
        $this->setDisplayField('name');
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }
}
