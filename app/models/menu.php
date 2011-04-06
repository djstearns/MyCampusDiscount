<?php
class Menu extends AppModel {
	var $name = 'Menu';
	var $displayField = 'name';
        var $actsAs = array('tree');
	//The Associations below have been created with all possible keys, those that are not needed can be removed
        
	var $belongsTo = array(
		'Aco' => array(
			'className' => 'Aco',
			'foreignKey' => 'aco_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

        function populate(){
            $this->query('INSERT INTO menus (name, aco_id, parent_id, lft, rght) SELECT alias, id, parent_id, lft, rght FROM acos');
        }
}
?>