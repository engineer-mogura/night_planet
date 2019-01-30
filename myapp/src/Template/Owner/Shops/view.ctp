<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Shop $shop
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Shop'), ['action' => 'edit', $shop->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Shop'), ['action' => 'delete', $shop->id], ['confirm' => __('Are you sure you want to delete # {0}?', $shop->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Shops'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Shop'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Owners'), ['controller' => 'Owners', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Owner'), ['controller' => 'Owners', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="shops view large-9 medium-8 columns content">
    <h3><?= h($shop->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Owner') ?></th>
            <td><?= $shop->has('owner') ? $this->Html->link($shop->owner->id, ['controller' => 'Owners', 'action' => 'view', $shop->owner->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Top Image') ?></th>
            <td><?= h($shop->top_image) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($shop->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Coupon') ?></th>
            <td><?= $this->Number->format($shop->coupon) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tennai') ?></th>
            <td><?= $this->Number->format($shop->tennai) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($shop->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($shop->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Catch') ?></h4>
        <?= $this->Text->autoParagraph(h($shop->catch)); ?>
    </div>
    <div class="row">
        <h4><?= __('Cast') ?></h4>
        <?= $this->Text->autoParagraph(h($shop->cast)); ?>
    </div>
    <div class="row">
        <h4><?= __('Tenpo') ?></h4>
        <?= $this->Text->autoParagraph(h($shop->tenpo)); ?>
    </div>
    <div class="row">
        <h4><?= __('Map') ?></h4>
        <?= $this->Text->autoParagraph(h($shop->map)); ?>
    </div>
    <div class="row">
        <h4><?= __('Job') ?></h4>
        <?= $this->Text->autoParagraph(h($shop->job)); ?>
    </div>
</div>
