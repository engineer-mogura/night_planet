<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NewPhotosRank Model
 *
 * @method \App\Model\Entity\NewPhotosRank get($primaryKey, $options = [])
 * @method \App\Model\Entity\NewPhotosRank newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\NewPhotosRank[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NewPhotosRank|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NewPhotosRank saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NewPhotosRank patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NewPhotosRank[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\NewPhotosRank findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NewPhotosRankTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('new_photos_rank');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('area')
            ->maxLength('area', 255)
            ->requirePresence('area', 'create')
            ->allowEmptyString('area', false);

        $validator
            ->scalar('genre')
            ->maxLength('genre', 255)
            ->requirePresence('genre', 'create')
            ->allowEmptyString('genre', false);

        $validator
            ->scalar('like_count')
            ->maxLength('like_count', 255)
            ->requirePresence('like_count', 'create')
            ->allowEmptyString('like_count', false);

        $validator
            ->integer('is_insta')
            ->requirePresence('is_insta', 'create')
            ->allowEmptyString('is_insta', false);

        $validator
            ->scalar('media_type')
            ->maxLength('media_type', 50)
            ->requirePresence('media_type', 'create')
            ->allowEmptyString('media_type', false);

        $validator
            ->scalar('comments_count')
            ->maxLength('comments_count', 255)
            ->requirePresence('comments_count', 'create')
            ->allowEmptyString('comments_count', false);

        $validator
            ->scalar('photo_path')
            ->maxLength('photo_path', 255)
            ->requirePresence('photo_path', 'create')
            ->allowEmptyString('photo_path', false);

        $validator
            ->scalar('details')
            ->maxLength('details', 255)
            ->requirePresence('details', 'create')
            ->allowEmptyString('details', false);

        $validator
            ->scalar('content')
            ->maxLength('content', 355)
            ->requirePresence('content', 'create')
            ->allowEmptyString('content', false);

        $validator
            ->dateTime('post_date')
            ->allowEmptyDateTime('post_date');

        return $validator;
    }
}
