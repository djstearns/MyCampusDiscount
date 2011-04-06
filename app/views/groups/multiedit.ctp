<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Transaction.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Transaction.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Transactions', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Categories', true), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category', true), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Institutions', true), array('controller' => 'institutions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Institution', true), array('controller' => 'institutions', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Accounts', true), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Uploads', true), array('controller' => 'uploads', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Upload', true), array('controller' => 'uploads', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div>
<?php echo $this->Form->create('User');?>
	<fieldset>
	<table>
         <?php
                
                echo '<table><tr>';
		//for($i=0;$i<count($detflds);$i++){
                $k = 0;
                foreach($detflds as $key3 => $value){
                    if($key3!='id'){
                        echo '<td>'.$this->Form->input($key3).'</td>';
                        $k = $k + 1;
                        if($k%4==0){
                            echo '</tr><tr>';
                        }
                    }
                    
                }
                 echo '</tr></table>';
           ?>
        </table>
	</fieldset>
    <center>
    <?php echo $form->submit('Update',array('name'=>'Task', 'value'=>'Update')); ?>
    </center>

<?php //echo $this->Form->end(__('Submit', true));?>
</div>
<div class="transactions index" id="transactionsindex">
	<h2><?php __('Transactions');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
            <?php
                    foreach($disflds as $key => $value){
                        
                            echo '<th>'.$this->Paginator->sort($key).'</th>';
                          
                    }
             ?>
             <th class="actions"><?php __('Actions');?></th>
	</tr>

<?php
        $i = 0;
	//foreach ($transactions as $transaction):
         $transaction = $this->data['data2'];
         if(isset($this->data['data2'])){
         //iterate rows
            foreach($this->data['data2'] as $key => $value){
                $class = null;
                if ($i % 2 == 0) {
                    $class = ' class="altrow"';
                 }
                 echo '<tr'.$class.'>';
                     //diplay the id and hidden id field
                    echo '<td>';
                        echo $this->data['data2'][$key]['id'];
                        echo $this->Form->input('data2.'.$key.'.id', array('type' => 'hidden'));
                    echo '</td>';

                    //start iteration through other fields
                 
                    foreach($disflds as $key2 => $value2){
                        if($key2!='id'){
                            echo '<td>'.$this->Form->input('data2.'.$key.'.'.$key2, array('label' => false)).'</td>';
                            
                        }
                    }
                     echo '<td>';
                   //debugger::dump($key);
                            //echo $this->Html->link(__('View', true), array('action' => 'view', $key));
                            echo $this->Html->link(__('Edit', true), array('action' => 'multiedit', $key));
                            echo $this->Html->link(__('Delete', true), array('action' => 'delete', $key), null, sprintf(__('Are you sure you want to delete # %s?', true), $key));
                    echo '</td></tr>';
                   
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