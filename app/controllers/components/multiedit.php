<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class MultieditComponent extends Object {
    
var $components = array('RequestHandler', 'Filter.Filter', 'Multiedit', 'Acl','Permissionable.Permissionable', 'Session', 'LoadsysAuth', 'Security');


function initialize(&$controller, $settings = array()) {
		// saving the controller reference for later use
            
            $this->controller =& $controller;
	}

    Public function showmulti($id=null, $detflds=null, $disflds=null, $actns=null, $relatns=null){
            //debugger::dump($this->modelClass);
            
            $controller = $this->controller;
            $modl = $this->controller->modelClass;
            
            //$this->controller->Menu->enabled = null;
            if (!empty($this->controller->data)) {
                        //test which submit pressed
                if(array_key_exists('Task', $this->controller->params['form'])){
                   
                    switch($this->controller->params['form']['Task']){
                    //if($this->params['form']['Task']=='Update'){
                        case "Update":
                        //pressed single submit
                        
                            if ($this->controller->{$modl}->save($this->data)) {
                                    $this->controller->redirect(array('action' => 'index'));

                                    $this->controller->Session->setFlash(__('The transaction has been saved', true));
                                    $this->controller->redirect(array('action' => 'index'));
                            } else {
                                    $this->controller->Session->setFlash(__('The transaction could not be saved. Please, try again.', true));
                            }
                            break;
                        case "Update All":
                            //do multi delete

                            $ids = array();
                            //debugger::dump($this->controller->data['data2'][2]);
                            foreach ($this->controller->data['data2'] as $id=>$v) {
                                    if ($v['del'] == 1) {
                                        //debugger::dump($v);
                                        //debugger::dump($id);
                                            if ($this->controller->delete($id)) {
                                                //debugger::dump('et');
                                                    $this->controller->Session->setFlash(__(sprintf('Deleted record %d.',$id),true));
                                            }else {
                                                    $this->controller->Session->setFlash(__(sprintf('Could not delete record %d.',$id),true));
                                            }
                                    }
                            }
                            //do multi save

                            if ($this->controller->{$modl}->saveAll($this->controller->data['data2'])) {
                                $this->controller->redirect(array('action' => 'index'));
                                    $this->controller->Session->setFlash(__('The transaction has been saved', true));
                                    $this->controller->redirect(array('action' => 'index'));
                            } else {
                                    $this->controller->Session->setFlash(__('The transaction could not be saved. Please, try again.', true));
                            }
                        break;

                    }
                }
           }

           if (empty($this->data)) {
                //debugger::dump($this);
                $this->data = $this->controller->{$modl}->read(null, $id);
                //sort exists
                $url = $this->controller->here;
                $pager = $this->controller->paginate();
                for($i=0;$i<count($pager);$i++){
                    $pageids[$i] = $pager[$i][$modl]['id'];
                }
                if (isset($pageids)){
                    if(strstr($url,'sort:')){
                        //sort exists
                        $sort = substr($url,strpos($url,'sort:')+5);
                        $sort = substr($sort, 0, (strpos($sort,"/")-strlen($sort)));
                          //debugger::dump($sort);
                        if(strstr($url,'direction:')){
                            //sort exists
                            $ord = substr($url,strpos($url,'direction:')+10);
                            //debugger::dump($ord);
                         }
                        $params = array('conditions' => array($modl.'.id' => $pageids),
                                    'order' => array($modl.'.'.$sort.' '.$ord.''),
                                    'recursive' >= 2
                                   );
                        //$this->data = $this->Transaction->find('all', $params );
                        $this->controller->data['data2'] = Set::combine($this->controller->{$modl}->find('all', $params ), '{n}.'.$modl.'.id', '{n}.'.$modl);

                    }else{
                        $params = array('conditions' => array($modl.'.id' => $pageids),
                                        'recursive' >= 2
                                        );
                        $this->controller->data['data2'] = Set::combine($this->controller->{$modl}->find('all', $params ), '{n}.'.$modl.'.id', '{n}.'.$modl);
                    }
                }
            }
                  //set modl
          $this->controller->set(compact('modl'));
            //set up $detflds,

          $detflds = $this->controller->{$modl}->_schema;
          $this->controller->set(compact('detflds'));

          $disflds = $this->controller->{$modl}->_schema;
          $this->controller->set(compact('disflds'));
            //setup actions,
            //

            //

            //setup $relatns
          if($modl == 'User' || $modl == 'Group'){
                $groups = $this->controller->{$modl}->Group->find('list');
                $this->controller->set(compact('groups'));
          }
          if($relatns!=null){
            $list = '';
            for($i=0;$i<count($relatns);$i++){
                if($i < count($relatns)-1){
                    //set var name
                    $varname = $relatns[$i].'s';
                    $$varname = $this->controller->{$relatns[$i]}->find('list');
                    $list = $list.$relatns[$i].',';
                }else{
                    $list = $list.$relatns[$i];
                }
            }
            $this->controller->set(compact($list));
          }

        }


}
?>
