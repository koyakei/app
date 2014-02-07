<div class="socials form">
<?php echo $this->Form->create('Social'); ?>
	<fieldset>
		<legend><?php echo __('Add Social'); ?></legend>
	<?php
		echo $this->Form->input('user_id');
		echo $this->Form->input('ctrl');
		echo $this->Form->input('view');
		echo $this->Form->input('page_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Socials'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
