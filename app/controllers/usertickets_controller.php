<?php
class UserticketsController extends AppController {

	var $name = 'Usertickets';
        function beforeFilter() {
            parent::beforeFilter();
            $this->LoadsysAuth->allowedActions = array('activate');
            

        }
	function index() {
		$this->Userticket->recursive = 0;
		$this->set('usertickets', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid userticket', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('userticket', $this->Userticket->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Userticket->create();
			if ($this->Userticket->save($this->data)) {
				$this->Session->setFlash(__('The userticket has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The userticket could not be saved. Please, try again.', true));
			}
		}
		$users = $this->Userticket->User->find('list');
		$this->set(compact('users'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid userticket', true));
			$this->redirect(array('action' => 'index'));
		}
                
		if (!empty($this->data)) {
                    
			if ($this->Userticket->save($this->data)) {
                                $this->Session->setFlash(__('The userticket has been saved', true));
				$this->redirect(array('action' => 'index'));
                               
			} else {
				$this->Session->setFlash(__('The userticket could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Userticket->read(null, $id);
		}
		$users = $this->Userticket->User->find('list');
		$this->set(compact('users'));
	}
        function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for userticket', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Userticket->delete($id)) {
			$this->Session->setFlash(__('Userticket deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Userticket was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
        
        function activate($id = null){
            
                if (!$id){
                       
			$this->Session->setFlash(__('Invalid activation key', true));
			
		}else{
                        if (empty($this->data)) {
                            $this->data = $this->Userticket->read(null, $id);
                            
                        }
                    if (!empty($this->data)) {
                            //test for expiration
                            $exp_date = $this->data['Userticket']['expiredate'];
                            $todays_date = date("Y-m-d"); 
                            $today = strtotime($todays_date); 
                            $expiration_date = strtotime($exp_date);
                            if ($expiration_date >= $today){
                                
                                if ($this->Userticket->save($this->data)) {

                                        //if($this->Userticket->User->updateactive($this->data['User'])==true){
                                        $this->Userticket->User->id = $this->data['Userticket']['user_id'];
                                        
                                        if($this->Userticket->User->saveField('active', '1')){
                                            $this->Session->setFlash(__('Your account is active.  Please login.', true));
                                            $this->Userticket->delete($id);
                                            $this->redirect(array('controller' => 'users', 'action' => 'login'));
                                        }else{
                                            $this->Session->setFlash(__('Could not activate your account.  Please contact an adminstrator', true));
                                            $this->redirect(array('controller' => 'users', 'action' => 'login'));
                                        }
                                                                               
                                        
                                }else{
                                        $this->Session->setFlash(__('Invalid activation key.  Please notify your system administrator or try resending your activation code.', true));
                                        $this->redirect(array('controller' => 'users', 'action' => 'resend'));
                                }
                            }else{
                                //ticket expired
                                $this->Session->setFlash(__('Your activation has expired.  Please fill out a new application', true));
                                //$this->redirect(array('controller' => 'users', 'action' => 'login'));

                            }
                    }else{
                        $this->Session->setFlash(__('Invalid activation key', true));
			
                    }
                }
		$users = $this->Userticket->User->find('list');
		$this->set(compact('users'));
        }
        function reset($id = null){
                if (!$id){
			$this->Session->setFlash(__('Invalid activation key', true));
			$this->redirect(array('action' => 'index'));
		}else{
                    if (empty($this->data)) {
			$this->data = $this->Userticket->read(null, $id);
                    }
                    if (!empty($this->data)) {
                            if ($this->Userticket->save($this->data)) {
                                    $this->Session->setFlash(__('The userticket has been saved', true));
                                    $this->redirect(array('action' => 'index'));
                            } else {
                                    $this->Session->setFlash(__('The userticket could not be saved. Please, try again.', true));
                            }
                    }
                }
		$users = $this->Userticket->User->find('list');
		$this->set(compact('users'));
        }
}
?>