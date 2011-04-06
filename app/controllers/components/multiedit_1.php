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
            if($modl == 'User' || $modl == 'Group'){
                $groups = $this->controller->{$modl}->Group->find('list');
                $this->controller->set(compact('groups'));
            }
            //$this->controller->Menu->enabled = null;
            if (!empty($this->controller->data)) {
                        //test which submit pressed

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
                        //}else{
                            //pressed multiple
                                /*
                                foreach($this->controller->data['data2'] as $test){

                                    debugger::dump($test);

                                }
                                 *
                                 */

                                if ($this->controller->{$modl}->saveAll($this->controller->data['data2'])) {
                                    $this->controller->redirect(array('action' => 'index'));
                                        $this->controller->Session->setFlash(__('The transaction has been saved', true));
                                        $this->controller->redirect(array('action' => 'index'));
                                } else {
                                        $this->controller->Session->setFlash(__('The transaction could not be saved. Please, try again.', true));
                                }
                            break;
                            case "Delete Duplicates":
                                if ($this->{$modl}->saveAll($this->data['data2'])) {
                                    if($this->{$modl}->deletedups()){
                                        $this->Session->setFlash(__('The duplicates were deleted!', true));
                                        $this->redirect(array('action' => 'index'));
                                    }

                                } else {
                                        $this->Session->setFlash(__('The transaction could not be saved. Please, try again.', true));
                                }

                            break;

                            case "Delete Selected":
                            break;
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
