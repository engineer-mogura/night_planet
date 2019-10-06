<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShopInfos Model
 *
 * @property \App\Model\Table\ShopsTable|\Cake\ORM\Association\BelongsTo $Shops
 *
 * @method \App\Model\Entity\ShopInfo get($primaryKey, $options = [])
 * @method \App\Model\Entity\ShopInfo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ShopInfo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ShopInfo|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ShopInfo|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ShopInfo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ShopInfo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ShopInfo findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ShopInfosTable extends Table
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

        $this->setTable('shop_infos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('shops', [
            'foreignKey' => 'shop_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('shop_info_likes', [
            'foreignKey' => 'shop_info_id',
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
            ->scalar('title')
            ->maxLength('title', 50,'タイトルが長すぎます。')
            ->notEmpty('title','タイトルを入力してください。')
            ->requirePresence('title', 'create')
            ->allowEmptyString('title', false);

        $validator
            ->scalar('content')
            ->maxLength('content', 600,'内容が長すぎます。')
            ->notEmpty('content','内容を入力してください。')
            ->requirePresence('content', 'create')
            ->allowEmptyString('content', false);

        $validator
            ->scalar('image1')
            ->maxLength('image1', 100)
            ->allowEmptyFile('image1');

        $validator
            ->scalar('image2')
            ->maxLength('image2', 100)
            ->allowEmptyFile('image2');

        $validator
            ->scalar('image3')
            ->maxLength('image3', 100)
            ->allowEmptyFile('image3');

        $validator
            ->scalar('image4')
            ->maxLength('image4', 100)
            ->allowEmptyFile('image4');

        $validator
            ->scalar('image5')
            ->maxLength('image5', 100)
            ->allowEmptyFile('image5');

        $validator
            ->scalar('image6')
            ->maxLength('image6', 100)
            ->allowEmptyFile('image6');

        $validator
            ->scalar('image7')
            ->maxLength('image7', 100)
            ->allowEmptyFile('image7');

        $validator
            ->scalar('image8')
            ->maxLength('image8', 100)
            ->allowEmptyFile('image8');

        $validator
            ->scalar('dir')
            ->maxLength('dir', 255)
            ->allowEmptyString('dir');

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
        $rules->add($rules->existsIn(['shop_id'], 'shops'));

        return $rules;
    }
}
