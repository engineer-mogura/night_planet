<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Casts Model
 *
 * @property \App\Model\Table\ShopsTable|\Cake\ORM\Association\BelongsTo $Shops
 *
 * @method \App\Model\Entity\Cast get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cast newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Cast[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cast|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cast|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cast patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cast[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cast findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CastsTable extends Table
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

        $this->setTable('casts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Shops', [
            'foreignKey' => 'shop_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Diarys', [
            'foreignKey' => 'cast_id'
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
            ->notEmpty('name','名前を入力してください。')
            ->maxLength('name', 30, '名前が長すぎます。')
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('nickname')
            ->notEmpty('nickname','ニックネームを入力してください。')
            ->maxLength('nickname', 30, 'ニックネームが長すぎます。')
            ->requirePresence('nickname', 'create')
            ->allowEmptyString('nickname', false);

        $validator
            ->email('email',false, "メールアドレスの形式が不正です。")
            ->notEmpty('email','メールアドレスを入力してください。')
            ->requirePresence('email', 'create')
            ->allowEmptyString('email', false)
            ->add('email', [
                'exists' => [
                    'rule' => function($value, $context) {
                        return !TableRegistry::get('Casts')->exists(['email' => $value]);
                    },
                    'message' => 'そのメールアドレスは既に登録されています。'
                ],
            ]);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->allowEmptyString('password');

        $validator
            ->date('birthday')
            ->allowEmptyTime('birthday');

        $validator
            ->scalar('three_size')
            ->maxLength('three_size', 10)
            ->allowEmptyString('three_size');

        $validator
            ->scalar('blood_type')
            ->maxLength('blood_type', 20)
            ->allowEmptyString('blood_type');

        $validator
            ->scalar('constellation')
            ->maxLength('constellation', 20)
            ->allowEmptyString('constellation');

        $validator
            ->scalar('age')
            ->maxLength('age', 5)
            ->allowEmptyString('age');

        $validator
            ->scalar('message')
            ->maxLength('message', 50,'メッセージが長すぎます。')
            ->allowEmptyString('message', false);

        $validator
            ->scalar('holiday')
            ->maxLength('holiday', 50)
            ->allowEmptyString('holiday');

        $validator
            ->scalar('image1')
            ->maxLength('image1', 255)
            ->allowEmptyFile('image1');

        $validator
            ->scalar('image2')
            ->maxLength('image2', 255)
            ->allowEmptyFile('image2');

        $validator
            ->scalar('image3')
            ->maxLength('image3', 255)
            ->allowEmptyFile('image3');

        $validator
            ->scalar('image4')
            ->maxLength('image4', 255)
            ->allowEmptyFile('image4');

        $validator
            ->scalar('image5')
            ->maxLength('image5', 255)
            ->allowEmptyFile('image5');

        $validator
            ->scalar('image6')
            ->maxLength('image6', 255)
            ->allowEmptyFile('image6');

        $validator
            ->scalar('image7')
            ->maxLength('image7', 255)
            ->allowEmptyFile('image7');

        $validator
            ->scalar('image8')
            ->maxLength('image8', 255)
            ->allowEmptyFile('image8');

        $validator
            ->scalar('remember_token')
            ->maxLength('remember_token', 64)
            ->allowEmptyString('remember_token');

        $validator
            ->integer('status')
            ->allowEmptyString('status', false);

        $validator
            ->integer('delete_flag')
            ->allowEmptyString('delete_flag');

        return $validator;
    }

        /**
     * バリデーション ログイン.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationCastLogin(Validator $validator)
{
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email','メールアドレスを入力してください。')
            ->allowEmptyString('email', false);

        $validator
            ->scalar('password')
            ->maxLength('password', 255,'パスワードが長すぎます。')
            ->notEmpty('password','パスワードを入力してください。')
            ->requirePresence('password', 'create')
            ->allowEmptyString('password', false);

        $validator
            ->integer('status')
            ->allowEmptyString('status');

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['shop_id'], 'Shops'));

        return $rules;
    }
}
