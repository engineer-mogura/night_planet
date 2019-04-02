<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Shop Entity
 *
 * @property int $id
 * @property int $owner_id
 * @property string|null $name
 * @property string|null $top_image
 * @property string|null $catch
 * @property string|null $tel
 * @property string|null $staff
 * @property string|null $bus_hours
 * @property string|null $system
 * @property string|null $credit
 * @property string|null $cast
 * @property string|null $address
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Owner $owner
 * @property \App\Model\Entity\Coupon[] $coupons
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
        'area' => true,
        'genre' => true,
        'dir' => true,
        'name' => true,
        'top_image' => true,
        'catch' => true,
        'tel' => true,
        'staff' => true,
        'bus_hours' => true,
        'system' => true,
        'credit' => true,
        'cast' => true,
        'pref21' => true,
        'addr21' => true,
        'strt21' => true,
        'created' => true,
        'modified' => true,
        'owner' => true,
        'coupons' => true
    ];
}
