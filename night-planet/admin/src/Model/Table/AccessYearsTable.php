<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AccessYears Model
 *
 * @property \App\Model\Table\ShopsTable|\Cake\ORM\Association\BelongsTo $Shops
 * @property \App\Model\Table\OwnersTable|\Cake\ORM\Association\BelongsTo $Owners
 *
 * @method \App\Model\Entity\AccessYear get($primaryKey, $options = [])
 * @method \App\Model\Entity\AccessYear newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AccessYear[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AccessYear|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AccessYear saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AccessYear patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AccessYear[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AccessYear findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccessYearsTable extends Table
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

        $this->setTable('access_years');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Shops', [
            'foreignKey' => 'shop_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Owners', [
            'foreignKey' => 'owner_id',
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
            ->scalar('pagePath')
            ->maxLength('pagePath', 255)
            ->requirePresence('pagePath', 'create')
            ->allowEmptyString('pagePath', false);

        $validator
            ->integer('sessions')
            ->requirePresence('sessions', 'create')
            ->allowEmptyString('sessions', false);

        $validator
            ->integer('pageviews')
            ->requirePresence('pageviews', 'create')
            ->allowEmptyString('pageviews', false);

        $validator
            ->integer('users')
            ->requirePresence('users', 'create')
            ->allowEmptyString('users', false);

        $validator
            ->dateTime('date')
            ->allowEmptyDateTime('date');

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
        $rules->add($rules->existsIn(['shop_id'], 'Shops'));
        $rules->add($rules->existsIn(['owner_id'], 'Owners'));

        return $rules;
    }
}
