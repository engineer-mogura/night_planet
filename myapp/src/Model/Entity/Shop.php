<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Shop Entity
 *
 * @property int $id
 * @property int $owner_id
 * @property string|null $area
 * @property string|null $genre
 * @property string|null $dir
 * @property string|null $name
 * @property string|null $top_image
 * @property string|null $image1
 * @property string|null $image2
 * @property string|null $image3
 * @property string|null $image4
 * @property string|null $image5
 * @property string|null $image6
 * @property string|null $image7
 * @property string|null $image8
 * @property string|null $catch
 * @property string|null $tel
 * @property string|null $staff
 * @property \Cake\I18n\FrozenTime|null $bus_from_time
 * @property \Cake\I18n\FrozenTime|null $bus_to_time
 * @property string|null $bus_hosoku
 * @property string|null $system
 * @property string|null $credit
 * @property string|null $cast
 * @property string|null $pref21
 * @property string|null $addr21
 * @property string|null $strt21
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Owner $owner
 * @property \App\Model\Entity\Cast[] $casts
 * @property \App\Model\Entity\Coupon[] $coupons
 * @property \App\Model\Entity\Job[] $jobs
 * @property \App\Model\Entity\ShopInfo[] $shop_infos
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
        'id' => true,
        'owner_id' => true,
        'area' => true,
        'genre' => true,
        'dir' => true,
        'name' => true,
        'top_image' => true,
        'image1' => true,
        'image2' => true,
        'image3' => true,
        'image4' => true,
        'image5' => true,
        'image6' => true,
        'image7' => true,
        'image8' => true,
        'catch' => true,
        'tel' => true,
        'staff' => true,
        'bus_from_time' => true,
        'bus_to_time' => true,
        'bus_hosoku' => true,
        'system' => true,
        'credit' => true,
        'cast' => true,
        'pref21' => true,
        'addr21' => true,
        'strt21' => true,
        'created' => true,
        'modified' => true,
        'owner' => true,
        'casts' => true,
        'coupons' => true,
        'jobs' => true,
        'shop_infos' => true
    ];

    /**
     * フルアドレスを返却する
     *
     *
     * @return void
     */
    protected function _getFullAddress()
    {
        return $this->_properties['pref21'] .
            $this->_properties['addr21'] . $this->_properties['strt21'];
    }

}
