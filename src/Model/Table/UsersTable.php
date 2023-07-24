<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Josegonzalez\Upload\File\Path\Processor\DefaultProcessor;
use Josegonzalez\Upload\File\Path\Sanitizer\DefaultSanitizer;
use Josegonzalez\Upload\File\Path\ProcessorInterface;
use Josegonzalez\Upload\File\Path\SanitizerInterface;
use Josegonzalez\Upload\File\Path\SimpleSlugSanitizer;
use Josegonzalez\Upload\File\Path\DefaultProcessor as BaseDefaultProcessor;
use Cake\Utility\Text;

/**
 * Users Model
 *
 * @property \App\Model\Table\ServiceProvidersTable&\Cake\ORM\Association\BelongsTo $ServiceProviders
 * @property \App\Model\Table\ReviewsTable&\Cake\ORM\Association\HasMany $Reviews
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('ServiceProviders', [
            'foreignKey' => 'service_provider_id',
        ]);
        $this->hasMany('Reviews', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('ChangePasswordTokens', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('ServiceProviderVisits', [
            'foreignKey' => 'user_id',
        ]);
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'photo' => [
                'path' => 'webroot{DS}img{DS}users{DS}',
                'nameCallback' => function ($table, $entity, $data, $field, $settings) {
     
                    $extension = pathinfo($data->getClientFilename(), PATHINFO_EXTENSION);
                    return 'photo-' . uniqid() . '.' . $extension;
                },
                'validate' => [
                    'rule' => ['mimeType', ['image/jpeg', 'image/png']],
                    'message' => 'Somente arquivos JPG ou PNG são permitidos.'
                ]
            ]
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
            ->integer('service_provider_id')
            ->allowEmptyString('service_provider_id');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'O email informado, já está sendo suado por outro usuário.']);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 255)
            ->requirePresence('phone', 'create')
            ->notEmptyString('phone');

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
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);
        $rules->add($rules->existsIn('service_provider_id', 'ServiceProviders'), ['errorField' => 'service_provider_id']);

        return $rules;
    }
    
    /*public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        debug("teste"); die();
        // Verifica se o campo 'photo' foi alterado
        if ($entity->isDirty('photo')) {
            $file = $entity->get('photo');
            $filename = time() . '_' . $file->getClientFilename();
            $path = WWW_ROOT . 'img' . DS . 'users' . DS . $filename;
            $image = Image::make($file->getStream())->save($path);
            $entity->set('photo', $filename); // ou $entity->set('photo', $path) se quiser salvar o caminho completo
        }

    }*/
}
