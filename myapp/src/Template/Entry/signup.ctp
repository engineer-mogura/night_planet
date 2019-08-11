<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
*/
?>

    <?= $this->Flash->render() ?>
    <div class="card or-card">
        <div class="card-image waves-block waves-light">
            <div class="or-form-wrap">
                <h3><?= __(LT['001']) ?></h3>
                <?= $this->Form->create($owner) ?>
                <?= $this->Form->control('email', array('label'=>'メールアドレス')) ?>
                <?= $this->Form->control('password', array('label'=>'パスワード')) ?>
                <?= $this->Form->control('password_check', array('type'=>'password','label' => 'パスワード再入力'
)) ?>
                <?= $this->Form->control('tel', array('label'=>'電話番号')) ?>
                <?= $this->Form->input('area', array('type' => 'select',
                                                     'options' => $selectList['area'],
                                                     'empty' => 'エリアを選択してください。',
                                                     'value' => 'エリアを選択してください。',
                                                     'label'=>'エリア')
                                      ); ?>
                <?= $this->Form->input('genre', array('type' => 'select',
                                                     'options' => $selectList['genre'],
                                                     'empty' => 'ジャンルを選択してください。',
                                                     'value' => 'ジャンルを選択してください。',
                                                     'label'=>'ジャンル')
                                      ); ?>
                <?= $this->Form->input('role', array('type' => 'hidden',
                                                     'value' => 'owner')
                                      ); ?>
                <div class="or-button">
                    <?= $this->Form->button('リセット',array('type' =>'reset', 'class'=>'waves-effect waves-light btn-large'));?>
                    <?= $this->Form->button('登録する',array('type' =>'submit','class'=>'waves-effect waves-light btn-large'));?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>



