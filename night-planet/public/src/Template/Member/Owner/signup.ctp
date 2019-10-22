<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
 */
?>

<div class="container">
    <h3><?= __('Owners') ?></h3>
<?php
echo $this->Form->create($contact);
echo $this->Form->input('name');
echo $this->Form->input('email');
echo $this->Form->input('body');
echo $this->Form->button('Submit');
echo $this->Form->end();
?>
</div>
