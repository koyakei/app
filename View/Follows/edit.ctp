<div class="follows form">
<?php echo $this->Form->create('Follow'); ?>
	<fieldset>
		<legend><?php echo __('Edit Follow'); ?></legend>
	<?php
		echo $this->Form->input('ID');
		echo $this->Form->input('user_id');
		echo $this->Form->input('target');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Follow.ID')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Follow.ID'))); ?></li>
		<li><?php echo $this->Html->link(__('List Follows'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
