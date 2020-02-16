<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AccessYear Entity
 *
 * @property int $id
 * @property int $shop_id
 * @property int $owner_id
 * @property string $name
 * @property string $area
 * @property string $genre
 * @property string $pagePath
 * @property int $sessions
 * @property int $pageviews
 * @property int $users
 * @property \Cake\I18n\FrozenTime|null $date
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Shop $shop
 * @property \App\Model\Entity\Owner $owner
 */
class AccessYear extends Entity
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
        'shop_id' => true,
        'owner_id' => true,
        'name' => true,
        'area' => true,
        'genre' => true,
        'pagePath' => true,
        'sessions' => true,
        'pageviews' => true,
        'users' => true,
        'date' => true,
        'created' => true,
        'modified' => true,
        'shop' => true,
        'owner' => true
    ];
}
