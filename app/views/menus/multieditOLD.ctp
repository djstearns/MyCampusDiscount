<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Transaction.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Transaction.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Uploads', true), array('controller' => 'uploads', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Upload', true), array('controller' => 'uploads', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div>
    <?php echo $this->Form->create($modl);?>
	<fieldset>
	<table>
         <?php
            echo '<tr>';
                $k = 0;
                foreach($detflds as $key => $value){
                    if($key!='id'){
                        echo '<td>'.$this->Form->input($key).'</td>';
                        $k = $k + 1;
                        if($k%4==0){
                            echo '</tr><tr>';
                        }
                    }
                }
                echo '</tr>';
           ?>
        </table>
	</fieldset>
    <center>
    <?php echo $form->submit('Update',array('name'=>'Task', 'value'=>'Update')); ?>
    </center>
</div>
<div class="transactions index" id="transactionsindex">
	<h2><?php __($modl);?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
            <?php
                foreach($disflds as $key2 => $value2){
                    echo '<th>'.$this->Paginator->sort($key2).'</th>';
                }
             ?>
             <th class="actions"><?php __('Actions');?></th>
	</tr>

<?php //END HEADER FILEDS
        $i = 0;
     if(isset($this->data['data2'])){
     //iterate rows
         
        foreach($this->data['data2'] as $key3 => $value3){
            $class = null;
            if ($i % 2 == 0) {
                $class = ' class="altrow"';
             }
             echo '<tr'.$class.'>';
                 //diplay the id and hidden id field
                echo '<td>';
                    echo $this->data['data2'][$key3]['id'].$i;
                    echo $this->Form->input('data2.'.$key3.'.id', array('type' => 'hidden'));
                echo '</td>';
             foreach($disflds as $key4 => $value4){
                if($key4!='id'){
                    echo '<td>'.$this->Form->input('data2.'.$key3.'.'.$key4, array('label' => false)).'</td>';
                }
             }
             echo '<td>';
                echo $this->Html->link(__('Edit', true), array('action' => 'multiedit', $key3));
                echo $this->Html->link(__('Delete', true), array('action' => 'delete', $key3), null, sprintf(__('Are you sure you want to delete # %s?', true), $key3));
             echo '</td></tr>';
             $i = $i + 1;
            }
         }      
?>
        </table>
</div>
<center>
<?php echo $form->submit('Update All',array('name'=>'Task', 'value'=>'Update All')); ?>
<?php echo $form->submit('Delete Duplicates',array('name'=>'Task', 'value'=>'Delete Duplicates')); ?>
</center>
<?php echo $form->end(); ?>
<?php echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
?>
<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
</div>