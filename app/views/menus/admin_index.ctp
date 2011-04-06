<div class="menus index">
	<h2><?php __('Menus');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('aco_id');?></th>
			<th><?php echo $this->Paginator->sort('public');?></th>
			<th><?php echo $this->Paginator->sort('customopt');?></th>
			<th><?php echo $this->Paginator->sort('url');?></th>
			<th><?php echo $this->Paginator->sort('label');?></th>
			<th><?php echo $this->Paginator->sort('skipasparent');?></th>
			<th><?php echo $this->Paginator->sort('topmenu');?></th>
			<th><?php echo $this->Paginator->sort('vertical');?></th>
			<th><?php echo $this->Paginator->sort('parent_id');?></th>
			<th><?php echo $this->Paginator->sort('lft');?></th>
			<th><?php echo $this->Paginator->sort('rght');?></th>
			<th><?php echo $this->Paginator->sort('creator_id');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modifier_id');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($menus as $menu):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $menu['Menu']['id']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['name']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($menu['Aco']['id'], array('controller' => 'acos', 'action' => 'view', $menu['Aco']['id'])); ?>
		</td>
		<td><?php echo $menu['Menu']['public']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['customopt']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['url']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['label']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['skipasparent']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['topmenu']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['vertical']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['parent_id']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['lft']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['rght']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['creator_id']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['created']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['modifier_id']; ?>&nbsp;</td>
		<td><?php echo $menu['Menu']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $menu['Menu']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $menu['Menu']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $menu['Menu']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $menu['Menu']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Menu', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Acos', true), array('controller' => 'acos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Aco', true), array('controller' => 'acos', 'action' => 'add')); ?> </li>
	</ul>
</div>