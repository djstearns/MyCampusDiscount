<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// app/models/category.php
class Strainer extends AppModel {
	var $name = 'Strainer';
	var $actsAs = array('Tree');

    public function getModelName($id = Null){
        $modelName = $this->find('first', array('conditions' => array('id' => $id),
                                       'fields' => array('model')
                                   )
                   );
        return $modelName;
    }

    public function executeQuery($id = Null){
        if($id != Null){
            $modelToLoad = $this->find('first', array('conditions' => array('id' => $id),
                                                          'fields' => array('model','allflds')
                                       )
                       );

            App::import($modelToLoad['Strainer']['model']);
            $this->$modelToLoad['Strainer']['model'] = new $modelToLoad['Strainer']['model']();
            $numOfFlds = $modelToLoad['Strainer']['allflds'];
            $directChildren = $this->children($id, true);
            $numOfDirectChildren = $this->childCount($id);
            //debugger::dump($directChildren);
            //test for one fld
            $conditions = array();
            $flds = array();
            if($numOfDirectChildren == 1){
                //only one fld
                $this->$modelToLoad->find('list');
            }else{
                //test for all filds

                if ($numOfFlds == 1){
                    //show all flds
                    $this->$modelToLoad->find('all');
                }else{
                    //set flds and conditions
                    foreach($directChildren as $key => $value){
                        $conditions[] = $this->setConditions($directChildren[$key], $modelToLoad['Strainer']['model']);
                        $flds[] = $modelToLoad['Strainer']['model'].'.'.$directChildren[$key]['Strainer']['fld'];
                    }
                   //debugger::dump($this);
                   $this->$modelToLoad['Strainer']['model']->find('all', array('fields' => $flds,
                                                                               'conditions' => $conditions

                                            ));


                }

            }
        }

    }

    function setConditions($directChild, $modelToLoad){
        //debugger::dump($directChild['Strainer']);
        switch($directChild['Strainer']['qualifier']){
            case '=':
                $conditions[$modelToLoad.'.'.$directChild['Strainer']['fld']] = $directChild['Strainer']['fvalue'];
            break;
            case '<>':
                $conditions['NOT'] = array($modelToLoad.$directChild['Strainer']['fld'] => $directChild['Strainer']['fvalue']);
            break;
            case '<';
                $conditions[$modelToLoad.'.'.$directChild['Strainer']['fld'].' <'] = $directChild['Strainer']['fvalue'];
            break;
            case '>';
                $conditions[$modelToLoad.'.'.$directChild['Strainer']['fld'].' >'] = $directChild['Strainer']['fvalue'];
            break;
            case 'LIKE':
                $conditions[$modelToLoad.'.'.$directChild['Strainer']['fld'].' LIKE'] = $directChild['Strainer']['fvalue'];
            break;
            case 'NOT LIKE':
                $conditions[$modelToLoad.'.'.$directChild['Strainer']['fld']] = $directChild['Strainer']['fvalue'];
            break;
        }

            return $conditions;

    }
}
?>
