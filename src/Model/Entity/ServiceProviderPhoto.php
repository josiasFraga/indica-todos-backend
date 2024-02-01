<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ServiceProviderPhoto Entity
 *
 * @property int $id
 * @property int $service_provider_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string $photo
 *
 * @property \App\Model\Entity\ServiceProvider $service_provider
 */
class ServiceProviderPhoto extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'service_provider_id' => true,
        'created' => true,
        'modified' => true,
        'photo' => true,
        'service_provider' => true,
    ];
}
