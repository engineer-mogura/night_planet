<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Owners Model
 *
 * @property \App\Model\Table\ShopsTable|\Cake\ORM\Association\HasMany $Shops
 *
 * @method \App\Model\Entity\Owner get($primaryKey, $options = [])
 * @method \App\Model\Entity\Owner newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Owner[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Owner|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Owner|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Owner patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Owner[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Owner findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OwnersTable extends Table
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

        $this->setTable('owners');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Shops', [
            'foreignKey' => 'owner_id',
        ]);

    }

    /**
     * バリデーション 新規登録.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationOwnerRegistration(Validator $validator)
    {

        $validator->setProvider('custom', 'App\Model\Validation\CustomValidation');

        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->notEmpty('name','名前を入力してください。')
            ->maxLength('name', 45, '名前が長すぎます。')
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('image')
            ->maxLength('image', 100)
            ->allowEmptyFile('image');

        $validator
            ->scalar('role')
            ->maxLength('role', 10)
            ->requirePresence('role', 'create')
            ->allowEmptyString('role', false);

        $validator
            ->email('email',false, "メールアドレスの形式が不正です。")
            ->requirePresence('email', 'create')
            ->notEmpty('email','メールアドレスを入力してください。')
            ->allowEmptyString('email', false)
            ->add('email', [
                'exists' => [
                    'rule' => function($value, $context) {
                        return !TableRegistry::get('Owners')->exists(['email' => $value]);
                    },
                    'message' => 'そのメールアドレスは既に登録されています。'
                ],
            ]);

        $validator
            ->scalar('password')
            ->maxLength('password', 255,'パスワードが長すぎます。')
            ->notEmpty('password','パスワードを入力してください。')
            ->requirePresence('password', 'create')
            ->allowEmptyString('password', false)
            ->add('password',[  //←バリデーション対象カラム
                    'comWith' => [  //←任意のバリデーション名
                        'rule' => ['compareWith','password_check'],  //←バリデーションのルール
                        'message' => '確認用のパスワードと一致しません。'  //←エラー時のメッセージ
            ]]);

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        $validator
            ->integer('gender')
            ->requirePresence('gender', 'create')
            ->notEmpty('gender','性別を選択してください。')
            ->allowEmptyString('gender', false);

        $validator
            ->scalar('age')
            ->maxLength('age', 5)
            ->requirePresence('age', 'create')
            ->notEmpty('age','年齢を選択してください。')
            ->allowEmptyString('age', false);

        $validator
            ->integer('dir');

        $validator
            ->scalar('tel')
            ->requirePresence('tel', 'create')
            ->notEmpty('tel','電話番号を入力してください。')
            ////電話番号形式のチェック ////
            ->add('tel', 'tel_check',[
                'rule' =>'tel_check',
                'provider' => 'custom',
                'message' => '無効な電話番号です。'
            ]);

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        return $validator;
    }

    /**
     * バリデーション ログイン.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationOwnerLogin(Validator $validator)
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
        //$rules->add($rules->isUnique(['email']));

        return $rules;
    }

}
