<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Develop[]|\Cake\Collection\CollectionInterface $develop
 */
?>
<div class="container">
  <nav>
    <div class="nav-wrapper">
      <ul>
        <li class="heading"><?= __('　開発者用') ?></li>
        <li><?= $this->Html->link(__('ユーザー管理'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('店舗ユーザー管理'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('開発ユーザー管理'), ['controller' => 'Users', 'action' => 'index']) ?></li>
      </ul>
    </div>
  </navigator>
</div>
