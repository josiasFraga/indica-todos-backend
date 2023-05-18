<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ServiceProviderVisit Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $ip_address
 * @property string $phone_clicked
 * @property int|null $user_id
 * @property int $service_provider_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ServiceProvider $service_provider
 */
class ServiceProviderVisit extends Entity
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
        'created' => true,
        'modified' => true,
        'ip_address' => true,
        'phone_clicked' => true,
        'user_id' => true,
        'service_provider_id' => true,
        'user' => true,
        'service_provider' => true,
    ];
}
