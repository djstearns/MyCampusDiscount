<?php
class Coreappsetting extends AppModel {
	var $name = 'Coreappsetting';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Coreappsettingtype' => array(
			'className' => 'Coreappsettingtype',
			'foreignKey' => 'coreappsettingtype_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

        function checkSetting($slug){
            $itemid = $this->find('first', array('conditions'=> array('slug' => $slug)));
            return $this->checkSettingById($itemid['Coreappsetting']['id']);
        }

        function checkSettingById($id){
            $this->id = $id;
            $item = $this->read(null, $id);
            if($this->checkDefault($item)== true){
                //value set
                
                return $item['Coreappsetting']['defaultvalue'];
            }else{
                //use default
                return $item['Coreappsetting'][$item['Coreappsettingtype']['desc'].'value'];
            }
        }

        function checkDefault($item){

            if(empty($item['Coreappsetting']['longvalue']) && empty($item['Coreappsetting']['varvalue']) && empty($item['Coreappsetting']['intvalue']) && empty($item['Coreappsetting']['boolvalue'])){
                //use default

                return true;
            }else{
                return false;
            }
        }
}
?>