<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Model\Entity\TokenTrait;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Owner Entity
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Owner extends Entity
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
        'email' => true,
        'password' => true,
        'status' => true,
        'created' => true,
        'modified' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

  protected function _setPassword($value){
      if (strlen($value)) {
          $hasher = new DefaultPasswordHasher();

          return $hasher->hash($value);
      }
  }

}
