<div class="usertickets index">
	<h2><?php __('Usertickets');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('expiredate');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($usertickets as $userticket):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $userticket['Userticket']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($userticket['User']['username'], array('controller' => 'users', 'action' => 'view', $userticket['User']['id'])); ?>
		</td>
		<td><?php echo $userticket['Userticket']['expiredate']; ?>&nbsp;</td>
		<td><?php echo $userticket['Userticket']['created']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $userticket['Userticket']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $userticket['Userticket']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $userticket['Userticket']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $userticket['Userticket']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Userticket', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>