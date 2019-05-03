<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Diary Entity
 *
 * @property int $id
 * @property int $cast_id
 * @property string $title
 * @property string $content
 * @property string|null $image1
 * @property string|null $image2
 * @property string|null $image3
 * @property string|null $image4
 * @property string|null $image5
 * @property string|null $image6
 * @property string|null $image7
 * @property string|null $image8
 * @property string|null $dir
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Cast $cast
 * @property \App\Model\Entity\Like[] $likes
 */
class Diary extends Entity
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
        'title' => true,
        'content' => true,
        'image1' => true,
        'image2' => true,
        'image3' => true,
        'image4' => true,
        'image5' => true,
        'image6' => true,
        'image7' => true,
        'image8' => true,
        'dir' => true,
        'created' => true,
        'modified' => true,
        'cast' => true,
        'likes' => true
    ];
}
