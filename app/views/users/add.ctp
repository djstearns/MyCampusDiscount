<div class="users form">
<?php echo $this->Form->create('User');?>
	
 		<legend><?php __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('first name');
		echo $this->Form->input('last name');
		echo $this->Form->input('email');
		echo $this->Form->input('ext');
		echo $this->Form->input('password');
                echo $this->Form->input('passwordverify', array('type' => 'password'));
                echo $this->Form->input('active');
		echo $this->Form->input('group_id');
		echo $this->Form->input('lastlogin');
	?>
	
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Users', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group', true), array('controller' => 'groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Uploads', true), array('controller' => 'uploads', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Upload', true), array('controller' => 'uploads', 'action' => 'add')); ?> </li>
	</ul>
</div>