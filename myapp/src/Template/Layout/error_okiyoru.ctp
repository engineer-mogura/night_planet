<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('materialize.css') ?>
    <?= $this->Html->css('okiyoru.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <div class="container" style="padding:initial">
        <?= $this->Flash->render() ?>
        <div id="header">
            <?php if($code == 500) :?>
                <h3><?= __('アクセスしようとしたページは表示できませんでした。') ?></h3>
            <?php else : ?>
                <h3><?= __('お探しのページは見つかりませんでした。') ?></h3>
            <?php endif; ?>
        </div>
        <div id="content">
            <?= $this->fetch('content') ?>
        </div>
        <div id="footer">
            <div class="or-button">
                <?=$this->Html->link(__('Back'),'javascript:history.back()'
                    ,[ 'class'=>'waves-effect waves-light btn-large']);?>
            </div>
        </div>
    </div>
</body>
</html>
