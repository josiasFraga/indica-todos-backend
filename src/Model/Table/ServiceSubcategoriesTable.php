<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ServiceSubcategories Model
 *
 * @method \App\Model\Entity\ServiceSubcategory newEmptyEntity()
 * @method \App\Model\Entity\ServiceSubcategory newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceSubcategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceSubcategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\ServiceSubcategory findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ServiceSubcategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceSubcategory[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceSubcategory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceSubcategory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceSubcategory[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceSubcategory[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceSubcategory[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceSubcategory[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ServiceSubcategoriesTable extends Table
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

        $this->setTable('service_subcategories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('ServiceCategories', [
            'foreignKey' => 'category_id',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('category_id')
            ->notEmptyString('category_id');

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
        $rules->add($rules->existsIn('category_id', 'ServiceCategories'), ['errorField' => 'category_id']);

        return $rules;
    }
}
