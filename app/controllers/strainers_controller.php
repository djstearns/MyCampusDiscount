<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class StrainersController extends AppController {

	var $name = 'Strainers';
        function beforeFilter() {
            parent::beforeFilter();
            //$this->Auth->allowedActions = array('*');
        }
	function index($id = null) {
		$this->data = $this->Strainer->generatetreelist(null, null, null, '&nbsp;&nbsp;&nbsp;');
		debug ($this->data);
                $this->Strainer->executeQuery($id);
                //set up for view
                $modelName = $this->Strainer->getModelName($id);
                $this->set('strainers', $this->paginate());
	}

        function add() {
            debugger::dump('test');
                
		if (!empty($this->data)) {
			if ($this->Strainer->save($this->data['Strainerp'][0])) {
                              //debugger::dump($this->Strainer->getLastInsertId());
                               
                               if($this->addChildren($this->Strainer->getLastInsertId())){
                                    
                                    $this->Session->setFlash(__('The upload has been saved', true));
                                    $this->redirect(array('action' => 'index'));
                                }else{
                                   $this->Session->setFlash(__('There were issues with your criteria.', true));
                                }
			} else {
				$this->Session->setFlash(__('The upload could not be saved. Please, try again.', true));
			}

                }
                $qualifierOptions = array('LIKE' =>'LIKE','=' =>'=', '<>'=>'<>', '<'=>'<', '>'=>'>', 'NOT LIKE'=>'NOT LIKE');
                $user = $this->LoadsysAuth->user('id');
                $modelNames = App::objects('model');
                foreach($modelNames as $key => $value){
                    $modelName[$value] = $value;
                }

                //$this->loadModel($modelName[$ids]);
                //debugger::dump($modelName[$ids]);
                $this->set(compact('modelName', 'user', 'qualifierOptions'));
		//debugger::dump($this->LoadsysAuth->user('id'));
                
	}

        function addChildren($id){
          for($i = 0;$i<(count($this->data['Strainer']));$i++){
              $this->data['Strainer'][$i]['parent_id'] = $id;
          }
		if (!empty($this->data)) {
			if ($this->Strainer->saveAll($this->data['Strainer'])) {
				return true;
			} else {
				return false;
			}
		}
        }

         public function get_flds_ajax() {
          Configure::write('debug', 0);
          $modelNames = App::objects('model');
          $i = 0;
          foreach($modelNames as $key => $value){
                    $newModelArr[$value] = $value;
                }

          $ids = $this->params['url']['mids'];
          $this->loadModel($newModelArr[$ids]);
          $testme = $this->$newModelArr[$ids]->_schema;
          $this->set(compact('testme'));
          
        }


}

?>
