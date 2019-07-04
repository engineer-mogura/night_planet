<?php
namespace App\Model\Table;

use ArrayObject;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\I18n\Time;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Events Model
 *
 * @property \App\Model\Table\EventTypesTable|\Cake\ORM\Association\BelongsTo $EventTypes
 * @property \App\Model\Table\CastsTable|\Cake\ORM\Association\BelongsTo $Casts
 *
 * @method \App\Model\Entity\Event get($primaryKey, $options = [])
 * @method \App\Model\Entity\Event newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Event[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Event|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Event|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Event patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Event[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Event findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EventsTable extends Table
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

        $this->setTable('events');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('EventTypes', [
            'foreignKey' => 'event_type_id'
        ]);
        $this->belongsTo('Casts', [
            'foreignKey' => 'cast_id',
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
            ->maxLength('title', 255)
            ->allowEmptyString('title');

        $validator
            ->scalar('details')
            ->maxLength('details', 255)
            ->allowEmptyString('details');

        $validator
            ->dateTime('start')
            ->allowEmptyDateTime('start');

        $validator
            ->dateTime('end')
            ->allowEmptyDateTime('end');

        $validator
            ->scalar('time_start')
            ->maxLength('time_start', 20)
            ->allowEmptyString('time_start');

        $validator
            ->scalar('time_end')
            ->maxLength('time_end', 20)
            ->allowEmptyString('time_end');

        $validator
            ->scalar('all_day')
            ->maxLength('all_day', 1)
            ->allowEmptyString('all_day');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->allowEmptyString('status');

        $validator
            ->integer('active')
            ->allowEmptyString('active');

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
        $rules->add($rules->existsIn(['event_type_id'], 'EventTypes'));
        $rules->add($rules->existsIn(['cast_id'], 'Casts'));

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
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        // startは、DateTime型に変換
        if (isset($data['start'])) {
            $data['start'] = new Time($data['start']);
            $data['start'] = $data['start']->i18nFormat('YYYY-MM-dd HH:mm:ss');
        }
        // endは、DateTime型に変換
        if (isset($data['end'])) {
            $data['end'] = new Time($data['end']);
            $data['end'] = $data['end']->i18nFormat('YYYY-MM-dd HH:mm:ss');
        }

    }
}
