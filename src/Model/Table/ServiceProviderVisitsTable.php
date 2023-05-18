<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ServiceProviderVisits Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ServiceProvidersTable&\Cake\ORM\Association\BelongsTo $ServiceProviders
 *
 * @method \App\Model\Entity\ServiceProviderVisit newEmptyEntity()
 * @method \App\Model\Entity\ServiceProviderVisit newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceProviderVisit[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceProviderVisit get($primaryKey, $options = [])
 * @method \App\Model\Entity\ServiceProviderVisit findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ServiceProviderVisit patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceProviderVisit[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceProviderVisit|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceProviderVisit saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceProviderVisit[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceProviderVisit[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceProviderVisit[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceProviderVisit[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServiceProviderVisitsTable extends Table
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

        $this->setTable('service_provider_visits');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        $this->belongsTo('ServiceProviders', [
            'foreignKey' => 'service_provider_id',
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
            ->scalar('ip_address')
            ->maxLength('ip_address', 80)
            ->requirePresence('ip_address', 'create')
            ->notEmptyString('ip_address');

        $validator
            ->scalar('phone_clicked')
            ->notEmptyString('phone_clicked');

        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id');

        $validator
            ->integer('service_provider_id')
            ->notEmptyString('service_provider_id');

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
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->existsIn('service_provider_id', 'ServiceProviders'), ['errorField' => 'service_provider_id']);

        return $rules;
    }
}
