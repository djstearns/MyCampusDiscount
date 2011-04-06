<div class="coreappsettings form">
<?php echo $this->Form->create('Coreappsetting');?>
	<fieldset>
 		<legend><?php __('Edit Coreappsetting'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('coreappsettingtype_id');
		echo $this->Form->input('slug');
		echo $this->Form->input('desc');
		echo $this->Form->input('defaultvalue');
		echo $this->Form->input('boolvalue');
		echo $this->Form->input('intvalue');
		echo $this->Form->input('varvalue');
		echo $this->Form->input('longvalue');
		echo $this->Form->input('template');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Coreappsetting.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Coreappsetting.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Coreappsettings', true), array('action' => 'index'));?></li>
	</ul>
</div>