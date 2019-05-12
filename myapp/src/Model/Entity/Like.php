<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Like Entity
 *
 * @property int $id
 * @property int $diary_id
 * @property int $cast_id
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Diary $diary
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
        'diary_id' => true,
        'cast_id' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'diary' => true
    ];
}