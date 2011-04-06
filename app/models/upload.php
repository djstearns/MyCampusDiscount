<?php
class Upload extends AppModel {
	var $name = 'Upload';
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
		),
		'Taxfile' => array(
			'className' => 'Taxfile',
			'foreignKey' => 'taxfile_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)

	);
       function clearN155($companies){
            //clears the imports table
            //debugger::dump($this->LoadsysAuth->user('id'));
           foreach($companies as $company){
            $this->query('DELETE FROM n155000imports WHERE co = "'.$company.'"');
            
           }
        }

        function transferToProd($from, $to, $concats=null){
            //create concats
            $fields = $thisd->$to->_schema;
            if(!null($concats)){
                //insert fields
                $concatfields = array_merge($fields, $concats);
                $insertFields = implode($concats);
                $i = 1;
                $fieldsToConcat = array();
                foreach($concats as $concatfeild => $concatdata){
                        $fieldsToConcat[] = 'concat('.implode($concatdata).')';
                }
                $fields = array_merge($fieldsToConcat, $fields);
                $fromFields = implode($fields);
            /**foreach($fields as $f => $fName){
                if($f < count($feilds)){
                     $fieldArray = $fieldArray.','.$fName;
                }else{
                    //check for empty contcat fields
                    if(empty($concats)){
                        $fieldArray = $fieldArray.$fName;
                    }else{
                        $fieldArray = $fieldArray.$concatfeilds;
                    }
                }

                *
             */
            }
             

            $qrystr = 'INSERT INTO '.$to.' ('.$insertFields.') SELECT '.$fromFields.' FROM '.$from;
        }

        function transferWHToProd(){

        }

        function transferN155ToProd(){
            //moves the imports to the production table
            $this->query('INSERT INTO n155000s (ACCOUNTNUMBER,DATEINPUT,ACCOUNTINGDATE,EFFECTIVEDATE,POLICYNUMBER,ITEMCODE,DEBITAMOUNT,CREDITAMOUNT,STATE,OPID,COMPANY,master_id) SELECT account,DATEINPUT,ACCOUNTINGDATE,EFFECTIVEDATE,POLICYNUMBER,ITEMCODE,DEBITAMOUNT,CREDITAMOUNT,STATE,OPID,co,Concat(co,POLICYNUMBER) FROM n155000imports');
        }

        function cleartable($tablename, $companies){
            foreach($companies as $company){
                $this->query('DELETE FROM '.$tablename.' WHERE co = "'.$company.'"');
           }
        }

        function deleteHeaders($tablename){
            
            $this->query('Delete FROM '.$tablename.'imports WHERE POLID = "POLID"');
        }
}
?>