<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $owners
 */
?>

<div class="container">
	<h3><?= __('Users') ?></h3>
	<?php
	echo $this->Form->create($user);
	echo $this->Form->control('email');
	echo $this->Form->control('password');
	echo $this->Form->button('Submit');
	echo $this->Form->end();
	?>
</div>
