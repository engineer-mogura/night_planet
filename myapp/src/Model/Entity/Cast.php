<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Token\Model\Entity\TokenTrait;
use Cake\Auth\DefaultPasswordHasher;
/**
 * Cast Entity
 *
 * @property int $id
 * @property int $shop_id
 * @property string $name
 * @property string $nickname
 * @property string $email
 * @property string|null $password
 * @property \Cake\I18n\FrozenTime|null $birthday
 * @property string|null $three_size
 * @property string|null $blood_type
 * @property string|null $constellation
 * @property string|null $age
 * @property string|null $message
 * @property string|null $holiday
 * @property string|null $image1
 * @property string|null $image2
 * @property string|null $image3
 * @property string|null $image4
 * @property string|null $image5
 * @property string|null $image6
 * @property string|null $image7
 * @property string|null $image8
 * @property string|null $remember_token
 * @property int $status
 * @property int|null $delete_flag
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Shop $shop
 */
class Cast extends Entity
{
    use TokenTrait;
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
        'role' => true,
        'name' => true,
        'nickname' => true,
        'email' => true,
        'password' => true,
        'birthday' => true,
        'three_size' => true,
        'blood_type' => true,
        'constellation' => true,
        'age' => true,
        'message' => true,
        'holiday' => true,
        'image1' => true,
        'image2' => true,
        'image3' => true,
        'image4' => true,
        'image5' => true,
        'image6' => true,
        'image7' => true,
        'image8' => true,
        'dir' => true,
        'remember_token' => true,
        'status' => true,
        'delete_flag' => true,
        'created' => true,
        'modified' => true,
        'shop' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        //'remember_token',  // 自動ログイン用トークン TODO: リリース前にコメントインする
        ];

    protected function _setPassword($value){
        if (strlen($value)) {
            $hasher = new DefaultPasswordHasher();
    
            return $hasher->hash($value);
        }
    }
}
