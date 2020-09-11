<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CastLike Entity
 *
 * @property int $id
 * @property int $cast_id
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Cast $cast
 * @property \App\Model\Entity\User $user
 */
class CastLike extends Entity
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
        'cast_id' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'cast' => true,
        'user' => true
    ];

    /**
     * テーブル名を返却する
     *
     *
     * @return void
     */
    protected function _getRegistryAlias()
    {
        return $this->_registryAlias;
    }
}