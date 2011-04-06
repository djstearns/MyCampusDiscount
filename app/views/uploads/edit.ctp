<div class="uploads form">
<?php echo $this->Form->create('Upload');?>
	
 		<legend><?php __('Edit Upload'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('fname');
		echo $this->Form->input('fdir');
		echo $this->Form->input('ftype');
		echo $this->Form->input('fsize');
		echo $this->Form->input('model');
		echo $this->Form->input('user_id');
	?>
	
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Upload.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Upload.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Uploads', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>