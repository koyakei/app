<?php echo $this->Html->script('users');?>
<div class="actions">
	<ul><li><?php echo $this->Html->link(__('Top'), array('plugin'=>false,'controller' => 'tags' ,'action' => 'search')); ?></li>
		<li><?php echo $this->Html->link(__d('users', 'Change password'), array('action' => 'change_password')); ?></li>
		<?php if (!$this->Session->read('Auth.User.id')) : ?>
			<li><?php echo $this->Html->link(__d('users', 'Login'), array('action' => 'login')); ?></li>
            <?php if (!empty($allowRegistration) && $allowRegistration)  : ?>
			<li><?php echo $this->Html->link(__d('users', 'Register an account'), array('action' => 'add')); ?></li>
            <?php endif; ?>
		<?php else : ?>
			<li><?php echo $this->Html->link(__('My Follow'), array('plugin' => null,'controller' => 'follows','action' => 'myfollow')); ?></li>
			<li><?php echo $this->Html->link(__d('users', 'Logout'), array('action' => 'logout')); ?></li>
			<li><?php echo $this->Html->link(__d('users', 'My Account'), array('action' => 'edit')); ?></li>

		<?php endif ?>
		<?php if($this->Session->read('Auth.User.is_admin')) : ?>
            <li>&nbsp;</li>
            <li><?php echo $this->Html->link(__d('users', 'List Users'), array('action'=>'index'));?></li>

        <?php endif; ?>
	</ul>
</div>
