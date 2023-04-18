<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Services Model
 *
 * @property \App\Model\Table\ServiceCategoriesTable&\Cake\ORM\Association\BelongsTo $ServiceCategories
 * @property \App\Model\Table\ServiceSubcategoriesTable&\Cake\ORM\Association\BelongsTo $ServiceSubcategories
 * @property \App\Model\Table\ServiceProvidersTable&\Cake\ORM\Association\BelongsTo $ServiceProviders
 * @property \App\Model\Table\ReviewsTable&\Cake\ORM\Association\HasMany $Reviews
 *
 * @method \App\Model\Entity\Service newEmptyEntity()
 * @method \App\Model\Entity\Service newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Service[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Service get($primaryKey, $options = [])
 * @method \App\Model\Entity\Service findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Service patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Service[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Service|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Service saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Service[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Service[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Service[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Service[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ServicesTable extends Table
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

        $this->setTable('services');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('ServiceCategories', [
            'foreignKey' => 'category_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('ServiceSubcategories', [
            'foreignKey' => 'subcategory_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('ServiceProviders', [
            'foreignKey' => 'provider_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Reviews', [
            'foreignKey' => 'service_id',
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->integer('category_id')
            ->notEmptyString('category_id');

        $validator
            ->integer('subcategory_id')
            ->notEmptyString('subcategory_id');

        $validator
            ->integer('provider_id')
            ->notEmptyString('provider_id');

        $validator
            ->decimal('price')
            ->requirePresence('price', 'create')
            ->notEmptyString('price');

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
        $rules->add($rules->existsIn('subcategory_id', 'ServiceSubcategories'), ['errorField' => 'subcategory_id']);
        $rules->add($rules->existsIn('provider_id', 'ServiceProviders'), ['errorField' => 'provider_id']);

        return $rules;
    }
}
