<?php
    echo($javascript->link("toggle.js"));
    echo $javascript->link('jquery-1.4.2.min');
    echo $javascript->link('list_flds');
?>

<div class="groups form">
<input type="hidden" value="0" id="theValue" />
<?php echo $this->Form->create('Strainer');?>
 		<legend><?php __('Add Group'); ?></legend>
                <div>
                    <table>
                    <tr>
                            <td>
                                <?php echo $this->Form->input('Strainerp.0.model', array('id' => 'model-name', 'type'=>'select', 'options'=>$modelName)); ?>
                            </td>
                            <td>
                                <?php echo $this->Form->input('Strainerp.0.name', array('label' => 'Query Name')); ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id = "etypearea">
                
                          <?php
                          //<div style="float:left; display:inline-block; width:80%;" id="ftypearea">
                          ?>
                            <div id="ftypearea">
                                <div style="float:left">
                                <table>
                                    <tr>
                                        <td>Field</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                        <td>Or</td>

                                    </tr><tr><td>
                                <?
		echo $this->Form->input('Strainer.0.fld', array('id'=> 'flds-name', 'type'=>'select', 'label' => False));
                echo '</td><td>';
		echo $this->Form->input('Strainer.0.parent_id', array('label' => '', 'type' => 'hidden', 'value' => ''));
                echo '</td><td>';
                echo $this->Form->input('Strainer.0.qualifier',  array('type'=>'select', 'options'=>$qualifierOptions, 'label' => false));
                echo '</td><td>';
                echo $this->Form->input('Strainer.0.fvalue', array('label' => false));

                echo $this->Form->input('Strainer.0.id', array('type' => 'hidden', 'label' => false));
                echo '</td><td>';
                echo $this->Form->input('Strainer.0.andor', array('label' => false, 'type' => 'select', 'options'=>array('and'=> 'and','or' => 'or')));
	?>                  </td>
                                    </tr>
                                </table>
                                </div>
                                <?php
                                //<div style="float:right;" id="newremove">
                                    //test
                                //</div>
                                ?>
                                
                                            </div>
                     
                           
                          <div id="clr" style="clear:both;"></div>
</div>
                     
         
               
                <div id="clr" style="clear:both;"></div>
                <div id="addfld" style="display: none">
                    <a  href="javascript:;" onclick="addElement('etypearea', 'ftypearea');">Add Another Field</a></p>
                </div>
   
<?php echo $this->Form->end(__('Submit', true));?>

     </div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Groups', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
<?php  echo $this->Js->writeBuffer(); ?>