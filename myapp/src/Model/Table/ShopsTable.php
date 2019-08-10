<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Shops Model
 *
 * @property \App\Model\Table\OwnersTable|\Cake\ORM\Association\BelongsTo $Owners
 * @property \App\Model\Table\CastsTable|\Cake\ORM\Association\HasMany $Casts
 * @property \App\Model\Table\CouponsTable|\Cake\ORM\Association\HasMany $Coupons
 * @property \App\Model\Table\JobsTable|\Cake\ORM\Association\HasMany $Jobs
 * @property \App\Model\Table\ShopInfosTable|\Cake\ORM\Association\HasMany $ShopInfos
 *
 * @method \App\Model\Entity\Shop get($primaryKey, $options = [])
 * @method \App\Model\Entity\Shop newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Shop[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Shop|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Shop|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Shop patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Shop[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Shop findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ShopsTable extends Table
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

        $this->setTable('shops');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Owners', [
            'foreignKey' => 'owner_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Casts', [
            'foreignKey' => 'shop_id'
        ]);
        $this->hasMany('Coupons', [
            'foreignKey' => 'shop_id'
        ]);
        $this->hasMany('Jobs', [
            'foreignKey' => 'shop_id'
        ]);
        $this->hasMany('ShopInfos', [
            'foreignKey' => 'shop_id'
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

        $validator->setProvider('custom', 'App\Model\Validation\CustomValidation');

        $validator
            ->integer('id')
            ->requirePresence('id', 'create')
            ->allowEmptyString('id', false);

        $validator
            ->scalar('area')
            ->maxLength('area', 255)
            ->allowEmptyString('area');

        $validator
            ->scalar('genre')
            ->maxLength('genre', 255)
            ->allowEmptyString('genre');

        $validator
            ->scalar('dir')
            ->maxLength('dir', 255)
            ->allowEmptyString('dir');

        $validator
            ->scalar('name')
            ->maxLength('name', 40,"店舗名は40文字以内にしてください。")
            ->allowEmptyString('name');

        $validator
            ->scalar('top_image')
            ->maxLength('top_image', 100)
            ->allowEmptyFile('top_image');

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
            ->scalar('catch')
            ->minLength('catch', 5,"キャッチコピーが短すぎます。")
            ->maxLength('catch', 100,"キャッチコピーは120文字以内にしてください。")
            ->allowEmptyString('catch');

        $validator
            ->scalar('tel')
            ->maxLength('tel', 15,"電話番号が長いです。")
            ->allowEmptyString('tel')
            ////電話番号形式のチェック ////
            ->add('tel', 'tel_check',[
                'rule' =>'tel_check',
                'provider' => 'custom',
                'message' => '無効な電話番号です。'
            ]);

        $validator
            ->scalar('staff')
            ->maxLength('staff', 255,"スタッフは120文字以内にしてください。")
            ->allowEmptyString('staff');

        $validator
            ->time('bus_from_time')
            ->allowEmptyTime('bus_from_time');
        // TODO: bus_to_time => 営業時間の終了は「LAST」も含めた方がよいのでは？
        $validator
            ->time('bus_to_time')
            ->allowEmptyTime('bus_to_time');

        $validator
            ->scalar('bus_hosoku')
            ->maxLength('bus_hosoku', 255,"補足は120文字以内にしてください。")
            ->allowEmptyString('bus_hosoku');

        $validator
            ->scalar('system')
            ->maxLength('system', 600,"システムは600文字以内にしてください。")
            ->allowEmptyString('system');

        $validator
            ->scalar('credit')
            ->maxLength('credit', 255)
            ->allowEmptyString('credit');

        $validator
            ->scalar('cast')
            ->maxLength('cast', 255,"キャストは255文字以内にしてください。")
            ->allowEmptyString('cast');

        $validator
            ->scalar('pref21')
            ->maxLength('pref21', 3,"都道府県が不正です。")
            ->allowEmptyString('pref21');

        $validator
            ->scalar('addr21')
            ->maxLength('addr21', 10,"市町村が不正です。")
            ->allowEmptyString('addr21');

        $validator
            ->scalar('strt21')
            ->maxLength('strt21', 30,"以降の住所が不正です。")
            ->allowEmptyString('strt21');

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
        $rules->add($rules->existsIn(['owner_id'], 'Owners'));

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
    //     // telは、ハイフン削除
    //     if (isset($data['tel'])) {
    //         $data['tel'] = str_replace(array('-', 'ー', '"', '―', '‐'), '', $data['tel']);
    //     }

    // }
}
