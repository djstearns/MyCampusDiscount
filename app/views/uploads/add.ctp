<div class="uploads form">
<?php echo $this->Form->create('Upload');?>
	<fieldset>
 		<legend><?php __('Add Upload'); ?></legend>
	<?php
		echo $this->Form->input('fname');
		echo $this->Form->input('fdir');
		echo $this->Form->input('ftype');
		echo $this->Form->input('fsize');
		echo $this->Form->input('model');
		echo $this->Form->input('user_id',array('type'=>'hidden','value'=>$users));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Uploads', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>