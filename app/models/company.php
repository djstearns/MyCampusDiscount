<?php
class Company extends AppModel {
	var $name = 'Company';
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasAndBelongsToMany = array(
		'Dept' => array(
			'className' => 'Dept',
			'joinTable' => 'companies_depts',
			'foreignKey' => 'company_id',
			'associationForeignKey' => 'dept_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

}
?>