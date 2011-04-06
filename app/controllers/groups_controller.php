<?php
class GroupsController extends AppController {

	var $name = 'Groups';
        function beforeSave() {
		return parent::beforeSave();
	}
        function beforeFilter() {
            parent::beforeFilter();
            //$this->Auth->allowedActions = array('*');
        }
	function index() {
		$this->Group->recursive = 0;
		$this->set('groups', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('group', $this->Group->read(null, $id));
	}

	function add() {

		if (!empty($this->data)) {
			$this->Group->create();

			if ($this->Group->save($this->data)) {
                            //debugger::dump($this->data);
                            if($this->data['Group']['parentGroups'] != 'None'){
                                $aro =& $this->Acl->Aro;
                                $aroid = $this->Acl->Aro->find('first',array('conditions' => array('alias' => $this->data['Group']['parentGroups'])));
                             
                                $aro->save(array('parent_id' => $this->data['Group']['parentGroups'], 'alias' => $this->data['Group']['name']));
                            }

				$this->Session->setFlash(__('The group has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}
                $this->Group->recursive = 0;
                $grouplist = $this->Acl->Aro->find('list',array('fields' => array('alias'),'conditions' => array('model' => 'Group')),null,null,'_');
                $grouplist[] = 'None';
		$this->set('parentGroups', $grouplist);

	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Group->save($this->data)) {
                                if($this->data['Group']['parentGroups'] != 'None'){
                                    $aro =& $this->Acl->Aro;
                                    $aroid = $this->Acl->Aro->find('first',array('conditions' => array('alias' => $this->data['Group']['parentGroups'])));

                                    $aro->save(array('parent_id' => $this->data['Group']['parentGroups'], 'alias' => $this->data['Group']['name']));
                                }
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Group->read(null, $id);
                        $this->Group->recursive = 0;
                        $grouplist = $this->Acl->Aro->find('list',array('fields' => array('alias'),'conditions' => array('model' => 'Group')),null,null,'_');
                        $grouplist[] = 'None';
                        $this->set('parentGroups', $grouplist);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for group', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Group->delete($id)) {
			$this->Session->setFlash(__('Group deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Group was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}


}
?>