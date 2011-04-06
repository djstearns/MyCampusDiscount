</div>
<div class="uploads form">
<?php echo $this->Form->create('Upload', array('type' => 'file'));?>
	
 		<legend><?php __('Add T055041 Upload'); ?></legend>



	<?php
		//echo $this->Form->input('fname');
		//echo $this->Form->input('fdir');
		//echo $this->Form->input('ftype');
		//echo $this->Form->input('fsize');
		echo $this->Form->input('model',array('value'=>$modelName, 'type'=>'hidden'));
		//echo $this->Form->input('user_id');
                echo $this->Form->input('fileName', array('type' => 'file'));
	?>
             
	

<?php echo $this->Form->end(__('Submit', true));?>
</div>
