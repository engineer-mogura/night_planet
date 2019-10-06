<?php
namespace App\Model\Table;

use ArrayObject;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Coupons Model
 *
 * @property \App\Model\Table\ShopsTable|\Cake\ORM\Association\BelongsTo $Shops
 *
 * @method \App\Model\Entity\Coupon get($primaryKey, $options = [])
 * @method \App\Model\Entity\Coupon newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Coupon[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Coupon|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Coupon|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Coupon patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Coupon[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Coupon findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CouponsTable extends Table
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

        $this->setTable('coupons');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('shops', [
            'foreignKey' => 'shop_id',
            'joinType' => 'OUTER'
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
            ->integer('status')
            ->requirePresence('status', 'create')
            ->allowEmptyString('status', false);

        $validator
            ->date('from_day')
            ->notEmpty('from_day','有効開始日を入力してください。')
            ->requirePresence('from_day', 'create')
            ->allowEmptyDateTime('from_day', false);
            //開始日、終了日が片方入力のチェック ////
            // ->add('from_day', 'from_to_day_check',[
            //     'rule' =>'from_to_day_check',
            //     'provider' => 'custom',
            //     'message' => '終了日も入力してください。'
            // ]);

        $validator
            ->date('to_day')
            ->notEmpty('to_day','有効終了日を入力してください。')
            ->requirePresence('to_day', 'create')
            ->allowEmptyDateTime('to_day', false)
            ->greaterThanField('to_day', 'from_day','対象期間の終了日は開始日より後にしてください。');
            ////開始日、終了日が片方入力のチェック ////
            // ->add('to_day', 'fromToDayCheck',[
            //     'rule' =>'fromToDayCheck',
            //     'provider' => 'custom',
            //     'message' => '開始日も入力してください。'
            // ]);


        $validator
            ->scalar('title')
            ->notEmpty('title','タイトルを入力してください。')
            ->maxLength('title', 255,'タイトルが長すぎます。')
            ->minLength('title', 5,'タイトルが短すぎます。')
            ->requirePresence('title', 'create')
            ->allowEmptyString('title', false);

        $validator
            ->scalar('content')
            ->notEmpty('content','内容を入力してください。')
            ->maxLength('content', 255,'内容が長すぎます。')
            ->minLength('content', 5,'内容が短すぎます。')
            ->requirePresence('content', 'create')
            ->allowEmptyString('content', false);

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
        $rules->add($rules->existsIn(['owner_id'], 'shops'));

        return $rules;
    }

        /**
     * リクエストデータがエンティティーに変換される前に呼ばれる処理。 
     * 主にリクエストデータに変換を掛けたり、バリデーションを条件次第で事前に解除したりできる。
     * @param Event $event
     * @param ArrayObject $data
     * @param ArrayObject $options
     * @return void
     */
    // public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    // {
    //     // startは、DateTime型に変換
    //     if (isset($data['status'])) {
    //         $data['status'] = 0;
    //     }

    // }
}
