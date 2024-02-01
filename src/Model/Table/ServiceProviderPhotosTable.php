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
 * ServiceProviderPhotos Model
 *
 * @property \App\Model\Table\ServiceProvidersTable&\Cake\ORM\Association\BelongsTo $ServiceProviders
 *
 * @method \App\Model\Entity\ServiceProviderPhoto newEmptyEntity()
 * @method \App\Model\Entity\ServiceProviderPhoto newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceProviderPhoto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceProviderPhoto get($primaryKey, $options = [])
 * @method \App\Model\Entity\ServiceProviderPhoto findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ServiceProviderPhoto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceProviderPhoto[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceProviderPhoto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceProviderPhoto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceProviderPhoto[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceProviderPhoto[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceProviderPhoto[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ServiceProviderPhoto[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServiceProviderPhotosTable extends Table
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

        $this->setTable('service_provider_photos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ServiceProviders', [
            'foreignKey' => 'service_provider_id',
            'joinType' => 'INNER',
        ]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'photo' => [
                'path' => 'webroot{DS}img{DS}gallery{DS}',
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
        $rules->add($rules->existsIn('service_provider_id', 'ServiceProviders'), ['errorField' => 'service_provider_id']);

        return $rules;
    }
}
