<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Payment Entity
 *
 * @property int $id
 * @property int $provider_id
 * @property string $amount
 * @property string $status
 * @property string $payment_type
 * @property \Cake\I18n\FrozenTime $created_at
 *
 * @property \App\Model\Entity\ServiceProvider $service_provider
 */
class Payment extends Entity
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
        'provider_id' => true,
        'amount' => true,
        'status' => true,
        'payment_type' => true,
        'created_at' => true,
        'service_provider' => true,
    ];
}
