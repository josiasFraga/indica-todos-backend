<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Security;

/**
 * User Entity
 *
 * @property int $id
 * @property int|null $service_provider_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string $photo
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\ServiceProvider $service_provider
 * @property \App\Model\Entity\Review[] $reviews
 */
class User extends Entity
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
        'service_provider_id' => true,
        'name' => true,
        'email' => true,
        'password' => true,
        'phone' => true,
        'photo' => true,
        'cpf' => true,
        'service_provider' => true,
        'reviews' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
          return Security::hash($password, 'sha256', true);
        }
    }
}
