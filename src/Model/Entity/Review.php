<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Review Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $service_id
 * @property int $rating
 * @property string $comment
 * @property \Cake\I18n\FrozenTime $created_at
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Service $service
 */
class Review extends Entity
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
        'user_id' => true,
        'service_id' => true,
        'rating' => true,
        'comment' => true,
        'created_at' => true,
        'user' => true,
        'service' => true,
    ];
}
