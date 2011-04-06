<?php
class Upload extends AppModel {
	var $name = 'Upload';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
        public $actsAs = array('Permissionable.Permissionable');
        var $paginate;
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>