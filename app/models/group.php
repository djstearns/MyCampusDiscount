<?php
class Group extends AppModel {
	var $name = 'Group';
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
        var $actsAs = array('Acl' => array('requester'));
        //public $actsAs = array('Permissionable.Permissionable');
        var $paginate;
        function parentNode() {
            return null;
        }
        
	var $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'group_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
?>