<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Review Entity
 *
 * @property int $id
 * @property int $shop_id
 * @property int $user_id
 * @property int $cost
 * @property int $atmosphere
 * @property int $customer
 * @property int $staff
 * @property int $cleanliness
 * @property string|null $comment
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Shop $shop
 * @property \App\Model\Entity\User $user
 */
class Review extends Entity
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
        'user_id' => true,
        'cost' => true,
        'atmosphere' => true,
        'customer' => true,
        'staff' => true,
        'cleanliness' => true,
        'comment' => true,
        'created' => true,
        'modified' => true,
        'shop' => true,
        'user' => true
    ];

    /**
     * 店舗レビューを返却する
     *
     *
     * @return void
     */
    protected function _getReview($id)
    {
        $sql = 'SELECT
            (r.cost_1 * 1 + r.cost_2 * 2 + r.cost_3 * 3
            + r.cost_4 * 4 + r.cost_5 * 5)
            / (r.cost_1 + r.cost_2 + r.cost_3 + r.cost_4 + r.cost_5) as cost_star
            , (r.atmosphere_1 * 1 + r.atmosphere_2 * 2 + r.atmosphere_3 * 3
            + r.atmosphere_4 * 4 + r.atmosphere_5 * 5)
            / (r.atmosphere_1 + r.atmosphere_2 + r.atmosphere_3
            + r.atmosphere_4 + r.atmosphere_5) as atmosphere_star
            , (r.customer_1 * 1 + r.customer_2 * 2 + r.customer_3 * 3
            + r.customer_4 * 4 + r.customer_5 * 5)
            / (r.customer_1 + r.customer_2 + r.customer_3
            + r.customer_4 + r.customer_5) as customer_star
            , (r.staff_1 * 1 + r.staff_2 * 2 + r.staff_3 * 3
            + r.staff_4 * 4 + r.staff_5 * 5)
            / (r.staff_1 + r.staff_2 + r.staff_3
            + r.staff_4 + r.staff_5) as staff_star
            , (r.cleanliness_1 * 1 + r.cleanliness_2 * 2 + r.cleanliness_3 * 3
            + r.cleanliness_4 * 4 + r.cleanliness_5 * 5)
            / (r.cleanliness_1 + r.cleanliness_2 + r.cleanliness_3
            + r.cleanliness_4 + r.cleanliness_5) as cleanliness_star

        FROM(
            SELECT
            COUNT(cost = 1 OR NULL) AS cost_1,
            COUNT(cost = 2 OR NULL) AS cost_2,
            COUNT(cost = 3 OR NULL) AS cost_3,
            COUNT(cost = 4 OR NULL) AS cost_4,
            COUNT(cost = 5 OR NULL) AS cost_5,
            COUNT(atmosphere = 1 OR NULL) AS atmosphere_1,
            COUNT(atmosphere = 2 OR NULL) AS atmosphere_2,
            COUNT(atmosphere = 3 OR NULL) AS atmosphere_3,
            COUNT(atmosphere = 4 OR NULL) AS atmosphere_4,
            COUNT(atmosphere = 5 OR NULL) AS atmosphere_5,
            COUNT(customer = 1 OR NULL) AS customer_1,
            COUNT(customer = 2 OR NULL) AS customer_2,
            COUNT(customer = 3 OR NULL) AS customer_3,
            COUNT(customer = 4 OR NULL) AS customer_4,
            COUNT(customer = 5 OR NULL) AS customer_5,
            COUNT(staff = 1 OR NULL) AS staff_1,
            COUNT(staff = 2 OR NULL) AS staff_2,
            COUNT(staff = 3 OR NULL) AS staff_3,
            COUNT(staff = 4 OR NULL) AS staff_4,
            COUNT(staff = 5 OR NULL) AS staff_5,
            COUNT(cleanliness = 1 OR NULL) AS cleanliness_1,
            COUNT(cleanliness = 2 OR NULL) AS cleanliness_2,
            COUNT(cleanliness = 3 OR NULL) AS cleanliness_3,
            COUNT(cleanliness = 4 OR NULL) AS cleanliness_4,
            COUNT(cleanliness = 5 OR NULL) AS cleanliness_5
        FROM
            reviews reviews
        WHERE
            shop_id = :id
        ) r
        ';
        $params = array(
            'id'=> $id
        );
        $data = $this->query($sql,$params);
        return $sql;
    }
    /**
     * フルアドレスを返却する
     *
     *
     * @return void
     */
    protected function _getReviewAverage()
    {
        $average = ($this->_properties['cost'] + $this->_properties['atmosphere']
            + $this->_properties['customer'] + $this->_properties['staff']
            + $this->_properties['cleanliness']) / 5;

        return $average;
    }

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

