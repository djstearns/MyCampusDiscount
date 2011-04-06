<div class="usertickets view">
<h2><?php  __('Userticket');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $userticket['Userticket']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($userticket['User']['username'], array('controller' => 'users', 'action' => 'view', $userticket['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Expiredate'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $userticket['Userticket']['expiredate']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $userticket['Userticket']['created']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Userticket', true), array('action' => 'edit', $userticket['Userticket']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Userticket', true), array('action' => 'delete', $userticket['Userticket']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $userticket['Userticket']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Usertickets', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Userticket', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
