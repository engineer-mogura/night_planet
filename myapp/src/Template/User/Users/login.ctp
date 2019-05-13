<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
*/
?>

<div>
  <?= $this->Flash->render() ?>
  <div class="card or-card">
    <div class="card-image waves-block">
        <div class="or-form-wrap">
            <h3><?='おきよるGo'?></h3>
            <div class="left">
                <span>キャストログイン</span>
                <span class="right"><?= $this->Html->link(('オーナーはこちらからログイン'), ['controller' => 'Owners', 'action' => 'login']) ?></span>
            </div>
            <?= $this->Form->create(null, array('class' => 'login')) ?>
            <?= $this->Form->control('email', array('required' => false)) ?>
            <?= $this->Form->control('password', array('required' => false)) ?>
            <?= $this->Form->control('remember_me',['type'=>'checkbox','label'=>['text'=>'ログイン状態を保存する']]) ?>
            <div class="or-button">
                <?= $this->Form->button('ログイン',array('class'=>'waves-effect waves-light btn-large'));?>
            </div>
            <?= $this->Form->end() ?>
        </div>
        <div class="card-content"style="text-align:center">
            <p><class="">SNSからでもログインできます。</p>
            <p><a href="#" class="waves-effect waves-light btn-large">facebook</a>　<a href="#" class="waves-effect waves-light btn-large">twitter</a></p>
            <br />
            <p><a href="#">パスワードをお忘れですか？</a></p>
        </div>
    </div>
</div>

<script>
  $(document).ready(function(){
    $("nav, .side-nav.fixed").hide();
    $('.page-footer').hide();
});
</script>

