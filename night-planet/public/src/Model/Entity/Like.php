<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Like Entity
 *
 * @property int $id
 * @property int|null $shop_id
 * @property int|null $shop_info_id
 * @property int|null $cast_id
 * @property int|null $diary_id
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Diary $diary
 * @property \App\Model\Entity\Cast $cast
 * @property \App\Model\Entity\User $user
 */
class Like extends Entity
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
        'shop_info_id' => true,
        'cast_id' => true,
        'diary_id' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'diary' => true,
        'cast' => true,
        'user' => true
    ];
}
