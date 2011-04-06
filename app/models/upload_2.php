<?php
class Upload extends AppModel {
	var $name = 'Upload';
	var $displayField = 'id';
        public $actsAs = array('Permissionable.Permissionable');
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        function clearN155(){
            //clears the imports table
            $this->query('truncate n155000imports');
        }
        function transferN155ToProd(){
            //moves the imports to the production table
            $this->query('INSERT INTO n155000s (DATEINPUT,ACCOUNTINGDATE,EFFECTIVEDATE,POLICYNUMBER,ITEMCODE,DEBITAMOUNT,CREDITAMOUNT,STATE,OPID,COMPANY) SELECT DATEINPUT,ACCOUNTINGDATE,EFFECTIVEDATE,POLICYNUMBER,ITEMCODE,DEBITAMOUNT,CREDITAMOUNT,STATE,OPID,co FROM n155000imports');
        }
        function cleartable($tablename){
            $this->query('truncate '.$tablename.'imports');
        }
}
?>