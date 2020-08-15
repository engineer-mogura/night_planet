<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CastLikes Model
 *
 * @property \App\Model\Table\CastsTable|\Cake\ORM\Association\BelongsTo $Casts
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\CastLike get($primaryKey, $options = [])
 * @method \App\Model\Entity\CastLike newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CastLike[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CastLike|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CastLike saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CastLike patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CastLike[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CastLike findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CastLikesTable extends Table
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

        $this->setTable('cast_likes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('casts', [
            'foreignKey' => 'cast_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('users', [
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT'
        ]);
        $this->hasOne('is_like', [
            'className' => 'cast_likes',
            'foreignKey' => 'cast_id',
            'bindingKey' => 'user_id',
            'joinType' => 'INNER'
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
        $rules->add($rules->existsIn(['cast_id'], 'Casts'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
