<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ServiceProvider Entity
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property int $address_number
 * @property string $address_complement
 */
class ServiceProvider extends Entity
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
        'name' => true,
        'email' => true,
        'phone' => true,
        'address' => true,
        'address_number' => true,
        'address_complement' => true,
    ];
}
