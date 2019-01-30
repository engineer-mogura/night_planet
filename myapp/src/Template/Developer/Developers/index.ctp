<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Developer[]|\Cake\Collection\CollectionInterface $developers
*/
use Cake\Core\Configure;
?>
<div class="container">
    <nav>
        <div class="nav-wrapper">
            <ul>
                <li><?= $this->Html->link(__(Configure::read('devlop.developerList')), ['controller' => 'Developers', 'action' => 'index','?' => ['targetTable'=>'Developers']]) ?></li>
                <li><?= $this->Html->link(__(Configure::read('devlop.userList')), ['controller' => 'Developers', 'action' => 'index','?' => ['targetTable'=>'Users']]) ?></li>
                <li><?= $this->Html->link(__(Configure::read('devlop.ownerList')), ['controller' => 'Developers', 'action' => 'index','?' => ['targetTable'=>'Owners']]) ?></li>
                <li><?= $this->Html->link(__('logout'), ['controller' => 'Developers', 'action' => 'logout']) ?></li>
                <li><?=$this->request->session()->read('Auth.Developer.email')?>でログイン中</li>
            </ul>
        </div>
    </nav>

    <?= $this->Flash->render() ?>
    <?php if(isset($this->request->query["targetTable"])):?>
    <h3><?= h($itemName1); ?></h3>
    <?php if($itemName2 == "Developers"): ?>
    <span><?= $this->Html->link(__('追加'), ['action' => 'add','?' => ['targetTable'=>'Developers']]) ?></span>
    <table class="bordered highlight">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('password') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($developers as $developer): ?>
                <tr>
                    <td><?= $this->Number->format($developer->id) ?></td>
                    <td><?= h($developer->email) ?></td>
                    <td><?= h($developer->password) ?></td>
                    <td><?= h($developer->created) ?></td>
                    <td><?= h($developer->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('表示'), ['action' => 'view', $developer->id,'?' => ['targetTable'=>'Developers']]) ?>
                        <?= $this->Html->link(__('編集'), ['action' => 'edit', $developer->id,'?' => ['targetTable'=>'Developers']]) ?>
                        <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $developer->id,'?' => ['targetTable'=>'Developers']],['confirm' => __(' #{0}を消去してもよろしいですか？', $developer->id)]) ?>
                   </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php elseif($itemName2 == "Users"): ?>
    <span><?= $this->Html->link(__('追加'), ['action' => 'add','?' => ['targetTable'=>'Users']]) ?></span>
    <table class="bordered highlight">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('password') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $this->Number->format($user->id) ?></td>
                    <td><?= h($user->email) ?></td>
                    <td><?= h($user->password) ?></td>
                    <td><?= h($user->created) ?></td>
                    <td><?= h($user->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('表示'), ['action' => 'view', $user->id,'?' => ['targetTable'=>'Users']]) ?>
                        <?= $this->Html->link(__('編集'), ['action' => 'edit', $user->id,'?' => ['targetTable'=>'Users']]) ?>
                        <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $user->id,'?' => ['targetTable'=>'Users']],['confirm' => __(' #{0}を消去してもよろしいですか？', $user->id)]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php elseif($itemName2 == "Owners"): ?>
    <span><?= $this->Html->link(__('追加'), ['action' => 'add','?' => ['targetTable'=>'Owners']]) ?></span>
    <table class="bordered highlight">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('password') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($owners as $owner): ?>
                <tr>
                    <td><?= $this->Number->format($owner->id) ?></td>
                    <td><?= h($owner->email) ?></td>
                    <td><?= h($owner->password) ?></td>
                    <td><?= h($owner->created) ?></td>
                    <td><?= h($owner->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('表示'), ['action' => 'view', $owner->id,'?' => ['targetTable'=>'Owners']]) ?>
                        <?= $this->Html->link(__('編集'), ['action' => 'edit', $owner->id,'?' => ['targetTable'=>'Owners']]) ?>
                        <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $owner->id,'?' => ['targetTable'=>'Owners']],['confirm' => __(' #{0}を消去してもよろしいですか？', $owner->id)]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif;?>
     <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('先頭')) ?>
            <?= $this->Paginator->prev('< ' . __('前')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('次') . ' >') ?>
            <?= $this->Paginator->last(__('最後') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('{{page}} / {{pages}}のうち,{{current}}件表示 record(s) 合計:{{count}} 件')]) ?></p>
    </div>
    <?php endif;?>
</div>