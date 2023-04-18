<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Service Entity
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $category_id
 * @property int $subcategory_id
 * @property int $provider_id
 * @property string $price
 *
 * @property \App\Model\Entity\ServiceCategory $service_category
 * @property \App\Model\Entity\ServiceSubcategory $service_subcategory
 * @property \App\Model\Entity\ServiceProvider $service_provider
 * @property \App\Model\Entity\Review[] $reviews
 */
class Service extends Entity
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
        'title' => true,
        'description' => true,
        'category_id' => true,
        'subcategory_id' => true,
        'provider_id' => true,
        'price' => true,
        'service_category' => true,
        'service_subcategory' => true,
        'service_provider' => true,
        'reviews' => true,
    ];
}
