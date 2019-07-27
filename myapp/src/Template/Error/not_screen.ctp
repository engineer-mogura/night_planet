<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;
?>

<h2><?= __d('cake', 'An testtettesttestt Error Has Occurred') ?></h2>
<p class="error">
    <strong><?= __d('cake', 'Error') ?>: </strong>
    <?= h($message) ?>
</p>
