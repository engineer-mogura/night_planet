<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Shop $shop
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $shop->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $shop->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Shops'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Owners'), ['controller' => 'Owners', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Owner'), ['controller' => 'Owners', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="shops form large-9 medium-8 columns content">
    <?= $this->Form->create($shop) ?>
    <fieldset>
        <legend><?= __('Edit Shop') ?></legend>
        <?php
            echo $this->Form->control('owner_id', ['options' => $owners]);
            echo $this->Form->control('top_image');
            echo $this->Form->control('catch');
            echo $this->Form->control('coupon');
            echo $this->Form->control('cast');
            echo $this->Form->control('tenpo');
            echo $this->Form->control('tennai');
            echo $this->Form->control('map');
            echo $this->Form->control('job');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
