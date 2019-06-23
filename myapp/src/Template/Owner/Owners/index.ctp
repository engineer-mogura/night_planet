<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
*/
?>
<div class="container">
  <?= $this->Flash->render() ?>
  <h5><?= __('オーナー画面ｎ') ?></h5>
  <?php foreach ($owner as $ownerRow): ?>
  <div class="row">
    <div class="col s12 m12 l8">
      
    </div>
  </div>
</div>
<?php endforeach; ?>
</div>
