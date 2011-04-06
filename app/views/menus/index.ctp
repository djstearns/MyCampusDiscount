
<script type="text/javascript">

Ext.BLANK_IMAGE_URL = '<?php echo $html->url('/js/ext-2.0.1/resources/images/default/s.gif') ?>';

Ext.onReady(function(){

	var getnodesUrl = '<?php echo $html->url('/menus/getnodes/') ?>';
	var reorderUrl = '<?php echo $html->url('/menus/reorder/') ?>';
	var reparentUrl = '<?php echo $html->url('/menus/reparent/') ?>';

	var Tree = Ext.tree;

	var tree = new Tree.TreePanel({
		el:'tree-div',
		autoScroll:true,
		animate:true,
		enableDD:true,
		containerScroll: true,
		rootVisible: true,
		loader: new Ext.tree.TreeLoader({
			dataUrl:getnodesUrl
		})
	});

	var root = new Tree.AsyncTreeNode({
		text:'Menus',
		draggable:false,
		id:'root'
	});
	tree.setRootNode(root);


	// track what nodes are moved and send to server to save

	var oldPosition = null;
	var oldNextSibling = null;

	tree.on('startdrag', function(tree, node, event){
		oldPosition = node.parentNode.indexOf(node);
		oldNextSibling = node.nextSibling;
	});

	tree.on('movenode', function(tree, node, oldParent, newParent, position){

		if (oldParent == newParent){
			var url = reorderUrl;
			var params = {'node':node.id, 'delta':(position-oldPosition)};
		} else {
			var url = reparentUrl;
			var params = {'node':node.id, 'parent':newParent.id, 'position':position};
		}

		// we disable tree interaction until we've heard a response from the server
		// this prevents concurrent requests which could yield unusual results

		tree.disable();

		Ext.Ajax.request({
			url:url,
			params:params,
			success:function(response, request) {

				// if the first char of our response is not 1, then we fail the operation,
				// otherwise we re-enable the tree

				if (response.responseText.charAt(0) != 1){
					request.failure();
				} else {
					tree.enable();
				}
			},
			failure:function() {

				// we move the node back to where it was beforehand and
				// we suspendEvents() so that we don't get stuck in a possible infinite loop

				tree.suspendEvents();
				oldParent.appendChild(node);
				if (oldNextSibling){
					oldParent.insertBefore(node, oldNextSibling);
				}

				tree.resumeEvents();
				tree.enable();

				alert("Oh no! Your changes could not be saved!");
			}

		});

	});

	// render the tree
	tree.render();
	root.expand();

});

</script>

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
			<th><?php echo $this->Paginator->sort('order');?></th>
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
	foreach ($menus as $menu1):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $menu1['Menu']['id']; ?>&nbsp;</td>
		<td><?php echo $menu1['Menu']['name']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($menu1['Aco']['id'], array('controller' => 'acos', 'action' => 'view', $menu1['Aco']['id'])); ?>
		</td>
                <td><?php echo $menu1['Menu']['public']; ?>&nbsp;</td>
		<td><?php echo $menu1['Menu']['customopt']; ?>&nbsp;</td>
		<td><?php echo $menu1['Menu']['url']; ?>&nbsp;</td>
		<td><?php echo $menu1['Menu']['label']; ?>&nbsp;</td>
		<td><?php echo $menu1['Menu']['parent_id']; ?>&nbsp;</td>
		<td><?php echo $menu1['Menu']['lft']; ?>&nbsp;</td>
		<td><?php echo $menu1['Menu']['rght']; ?>&nbsp;</td>
		<td><?php echo $menu1['Menu']['creator_id']; ?>&nbsp;</td>
		<td><?php echo $menu1['Menu']['created']; ?>&nbsp;</td>
		<td><?php echo $menu1['Menu']['modifier_id']; ?>&nbsp;</td>
		<td><?php echo $menu1['Menu']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $menu1['Menu']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $menu1['Menu']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $menu1['Menu']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $menu1['Menu']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
        
        echo $this->Paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)));
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
      <?php echo $js->writeBuffer(); ?>
</div>


<div id="tree-div" style="height:400px;"></div>

<?php
$paginator->options(array(
    'update' => '#content',
    'evalScripts' => true,
    'before' => $js->get('#content')->effect('fadeOut', array('buffer' => false)),
    'success' => $js->get('#content')->effect('fadeIn', array('buffer' => false))
));
?>