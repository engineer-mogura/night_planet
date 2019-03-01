<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
*/
?>

<div class="container">
    <?= $this->Flash->render() ?>
    <div class="card or-card">
        <div class="card-image waves-block waves-light">
            <div class="or-form-wrap">
                <h3><?= __('おきよるGo') ?></h3>
                <?= $this->Form->create() ?>
                <?= $this->Form->control('email', array('required' => false)) ?>
                <?= $this->Form->control('password', array('required' => false)) ?>
                <?= $this->Form->control('password_check', array('type'=>'password','label' => 'password check'
)) ?>
                <?= $this->Form->control('tel', array('required' => false)) ?>
                <?= $this->Form->input('area', array('type' => 'select',
                                                     'options' => $area,
                                                     'empty' => 'エリアを選択してください。')
                                      ); ?>
                <?= $this->Form->input('genre', array('type' => 'select',
                                                     'options' => $genre,
                                                     'empty' => 'ジャンルを選択してください。')
                                      ); ?>
                <div class="or-button">
                    <?= $this->Form->button('新規登録',array('class'=>'waves-effect waves-light btn-large'));?>
                </div>
                <?= $this->Form->end() ?>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("nav").hide();
        $('.page-footer').hide();
        $('select').material_select();

    });
</script>


