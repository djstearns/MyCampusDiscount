</div>
<div class="uploads form">
<?php echo $this->Form->create('Upload', array('type' => 'file'));?>
	
 		<legend><?php __('Add N155000 Upload'); ?></legend>



	<?php
		//echo $this->Form->input('fname');
		//echo $this->Form->input('fdir');
		//echo $this->Form->input('ftype');
		//echo $this->Form->input('fsize');
		echo $this->Form->input('model',array('value'=>$modelName, 'type'=>'hidden'));
		//echo $this->Form->input('user_id');
                echo $this->Form->input('fileName', array('type' => 'file'));
	?>
                <a href="http://192.168.112.52:8080/taxrecon/app/webroot/test.txt">right click and save</a>
	

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