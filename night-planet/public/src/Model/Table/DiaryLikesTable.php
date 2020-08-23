<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DiaryLikes Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Diaries
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\DiaryLike get($primaryKey, $options = [])
 * @method \App\Model\Entity\DiaryLike newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DiaryLike[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DiaryLike|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DiaryLike saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DiaryLike patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DiaryLike[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DiaryLike findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DiaryLikesTable extends Table
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

        $this->setTable('diary_likes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('diarys', [
            'foreignKey' => 'diary_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('users', [
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT'
        ]);
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

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['diary_id'], 'diarys'));
        $rules->add($rules->existsIn(['user_id'], 'users'));

        return $rules;
    }
}
