<script type="text/javascript">
function deselectAll(){
        var xCheckboxs=document.getElementsByTagName("input");
        for (var xSel = 0;xSel < xCheckboxs.length;xSel++)
                {
                        if (xCheckboxs[xSel].type == "checkbox") {
                                xCheckboxs[xSel].checked = false;
                        }
                }
}
function selectAll(){
        var xCheckboxs=document.getElementsByTagName("input");
        for (var xSel = 0;xSel < xCheckboxs.length;xSel++)
                {
                        if (xCheckboxs[xSel].type == "checkbox") {
                                xCheckboxs[xSel].checked = true;
                        }
                }
}

</script>
<div class="uploads form">
<?php echo $this->Form->create('Upload', array('type' => 'file'));?>
	
 		<legend><?php __('Add Upload'); ?></legend>
	<?php
		echo $form->input('startdate', array('type' => 'date'));
                echo $form->input('enddate', array('type' => 'date'));
                echo $this->Form->input('Company',array('type'=>'select','multiple'=>'checkbox'));
                echo "<input type='button' onclick='selectAll()' value='Select All'>";
                echo "<input type='button' onclick='deselectAll()' value='Unselect All'";

	?>
	
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