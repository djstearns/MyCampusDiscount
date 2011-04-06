<div class="users form">
<?php echo $this->Form->create('User');?>
	
 		<legend><?php __('Edit User'); ?></legend>
	<?php
        
		echo $this->Form->input('id');
		echo $this->Form->input('username');
		echo $this->Form->input('firstName');
		echo $this->Form->input('lastName');
		echo $this->Form->input('email');
		echo $this->Form->input('ext');
		echo $this->Form->input('password');
                echo $this->Form->input('passwordverify', array('type' => 'password'));
		echo $this->Form->input('group_id');
		echo $this->Form->input('creator_id');
		echo $this->Form->input('modifier_id');
		echo $this->Form->input('lastlogin');
	?>
	
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('User.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('User.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group', true), array('controller' => 'groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Uploads', true), array('controller' => 'uploads', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Upload', true), array('controller' => 'uploads', 'action' => 'add')); ?> </li>
	</ul>
</div>