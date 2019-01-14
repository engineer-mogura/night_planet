<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
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
        <?php if(isset($this->request->query["targetTable"])):?>
            <?php if($itemName1 == "developer"): ?>
              <?= $this->Form->create($developer) ?>
              <fieldset>
                <legend><?= __('開発者を追加') ?></legend>
                <?php
                echo $this->Form->control('email');
                echo $this->Form->control('password');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
            <?php elseif($itemName1 == "user"): ?>
                <?= $this->Form->create($user) ?>
                <fieldset>
                  <legend><?= __('ユーザーを追加') ?></legend>
                  <?php
                  echo $this->Form->control('email');
                  echo $this->Form->control('password');
                  ?>
              </fieldset>
              <?= $this->Form->button(__('Submit')) ?>
              <?= $this->Form->end() ?>
              <?php elseif($itemName1 == "owner"): ?>
                <?= $this->Form->create($owner) ?>
                <fieldset>
                    <legend><?= __('オーナーを追加') ?></legend>
                  <?php
                  echo $this->Form->control('email');
                  echo $this->Form->control('password');
                  ?>
              </fieldset>
              <?= $this->Form->button(__('Submit')) ?>
              <?= $this->Form->end() ?>
          <?php endif;?>
      <?php endif;?>
  </div>
</div>
