<div class="coreappsettings index">
	<h2><?php __('Coreappsettings');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('coreappsettingtype_id');?></th>
			<th><?php echo $this->Paginator->sort('slug');?></th>
			<th><?php echo $this->Paginator->sort('desc');?></th>
			<th><?php echo $this->Paginator->sort('defaultvalue');?></th>
			<th><?php echo $this->Paginator->sort('boolvalue');?></th>
			<th><?php echo $this->Paginator->sort('intvalue');?></th>
			<th><?php echo $this->Paginator->sort('varvalue');?></th>
			<th><?php echo $this->Paginator->sort('longvalue');?></th>
			<th><?php echo $this->Paginator->sort('template');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($coreappsettings as $coreappsetting):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $coreappsetting['Coreappsetting']['id']; ?>&nbsp;</td>
		<td><?php echo $coreappsetting['Coreappsetting']['coreappsettingtype_id']; ?>&nbsp;</td>
		<td><?php echo $coreappsetting['Coreappsetting']['slug']; ?>&nbsp;</td>
		<td><?php echo $coreappsetting['Coreappsetting']['desc']; ?>&nbsp;</td>
		<td><?php echo $coreappsetting['Coreappsetting']['defaultvalue']; ?>&nbsp;</td>
		<td><?php echo $coreappsetting['Coreappsetting']['boolvalue']; ?>&nbsp;</td>
		<td><?php echo $coreappsetting['Coreappsetting']['intvalue']; ?>&nbsp;</td>
		<td><?php echo $coreappsetting['Coreappsetting']['varvalue']; ?>&nbsp;</td>
		<td><?php echo $coreappsetting['Coreappsetting']['longvalue']; ?>&nbsp;</td>
		<td><?php echo $coreappsetting['Coreappsetting']['template']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $coreappsetting['Coreappsetting']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $coreappsetting['Coreappsetting']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $coreappsetting['Coreappsetting']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $coreappsetting['Coreappsetting']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Coreappsetting', true), array('action' => 'add')); ?></li>
	</ul>
</div>