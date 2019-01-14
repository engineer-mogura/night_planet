<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Developer $developer
 */
?>
<div class="container">
    <nav>
        <div class="nav-wrapper">
            <ul>
                <li><?= $this->Html->link(__('開発者リスト'), ['controller' => 'Developers', 'action' => 'index','?' => ['targetTable'=>'Developers']]) ?></li>
                <li><?= $this->Html->link(__('ユーザーリスト'), ['controller' => 'Developers', 'action' => 'index','?' => ['targetTable'=>'Users']]) ?></li>
                <li><?= $this->Html->link(__('オーナーリスト'), ['controller' => 'Developers', 'action' => 'index','?' => ['targetTable'=>'Owners']]) ?></li>
                <li><?= $this->Html->link(__('logout'), ['controller' => 'Developers', 'action' => 'logout']) ?></li>
                <li><?=$this->request->session()->read('Auth.Developer.email')?>でログイン中</li>
            </ul>
        </div>
    </nav>
    <div>
    <h3><?= h($itemName1); ?></h3>
    <?php if($itemName2 == "developer"): ?>
        <?= $this->Form->create($developer) ?>
        <fieldset>
            <legend><?= __('Edit Developer') ?></legend>
            <?php
            echo $this->Form->control('email');
            echo $this->Form->control('password');
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    <?php elseif($itemName2 == "user"): ?>
        <?= $this->Form->create($user) ?>
        <fieldset>
            <legend><?= __('Edit User') ?></legend>
            <?php
            echo $this->Form->control('email');
            echo $this->Form->control('password');
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    <?php elseif($itemName2 == "owner"): ?>
        <?= $this->Form->create($owner) ?>
        <fieldset>
            <legend><?= __('Edit Owner') ?></legend>
            <?php
            echo $this->Form->control('email');
            echo $this->Form->control('password');
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    <?php endif;?>
    </div>
</div>
