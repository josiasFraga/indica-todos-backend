<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ServiceProviders Model
 *
 * @property \App\Model\Table\LocationsTable&\Cake\ORM\Association\HasMany $Locations
 * @property \App\Model\Table\PaymentsTable&\Cake\ORM\Association\HasMany $Payments
 * @property \App\Model\Table\ServicesTable&\Cake\ORM\Association\HasMany $Services
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\ServiceProvider newEmptyEntity()
 * @method \App\Model\Entity\ServiceProvider newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceProvider[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceProvider get($primaryKey, $options = [])
 * @method \App\Model\Entity\ServiceProvider findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ServiceProvider patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceProvider[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceProvider|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceProvider saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceProvider[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceProvider[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceProvider[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceProvider[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServiceProvidersTable extends Table
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

        $this->setTable('service_providers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Locations', [
            'foreignKey' => 'service_provider_id',
        ]);
        $this->hasMany('Payments', [
            'foreignKey' => 'service_provider_id',
        ]);
        $this->hasMany('Services', [
            'foreignKey' => 'service_provider_id',
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'service_provider_id',
        ]);
        $this->hasMany('ServiceProviderPhotos', [
            'foreignKey' => 'service_provider_id',
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
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 255)
            ->requirePresence('phone', 'create')
            ->notEmptyString('phone');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->requirePresence('address', 'create')
            ->notEmptyString('address');

        $validator
            ->integer('address_number')
            ->requirePresence('address_number', 'create')
            ->notEmptyString('address_number');

        $validator
            ->scalar('address_complement')
            ->maxLength('address_complement', 50);

        $validator
            ->scalar('city')
            ->maxLength('city', 50)
            ->requirePresence('city', 'create')
            ->notEmptyString('city');

        $validator
            ->scalar('state')
            ->maxLength('state', 2)
            ->requirePresence('state', 'create')
            ->notEmptyString('state');

        $validator
            ->scalar('postal_code')
            ->maxLength('postal_code', 9)
            ->requirePresence('postal_code', 'create')
            ->notEmptyString('postal_code');

        $validator
            ->scalar('neighborhood')
            ->maxLength('neighborhood', 255)
            ->requirePresence('neighborhood', 'create')
            ->notEmptyString('neighborhood');

        return $validator;
    }
}
