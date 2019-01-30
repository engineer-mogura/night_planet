<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Shop Entity
 *
 * @property int $id
 * @property int $owner_id
 * @property string|null $top_image
 * @property string|null $catch
 * @property int|null $coupon
 * @property string|null $cast
 * @property string|null $tenpo
 * @property int|null $tennai
 * @property string|null $map
 * @property string|null $job
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Shop $shop
 */
class Shop extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'owner_id' => true,
        'top_image' => true,
        'catch' => true,
        'coupon' => true,
        'cast' => true,
        'tenpo' => true,
        'tennai' => true,
        'map' => true,
        'job' => true,
        'created' => true,
        'modified' => true,
        'shop' => true
    ];
}
