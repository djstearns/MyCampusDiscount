<?php
class DeptsController extends AppController {

	var $name = 'Depts';

	function index() {
		$this->Dept->recursive = 0;
		$this->set('depts', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid dept', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('dept', $this->Dept->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Dept->create();
			if ($this->Dept->save($this->data)) {
				$this->Session->setFlash(__('The dept has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The dept could not be saved. Please, try again.', true));
			}
		}
		$companies = $this->Dept->Company->find('list');
		$this->set(compact('companies'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid dept', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Dept->save($this->data)) {
				$this->Session->setFlash(__('The dept has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The dept could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Dept->read(null, $id);
		}
		$companies = $this->Dept->Company->find('list');
		$this->set(compact('companies'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for dept', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Dept->delete($id)) {
			$this->Session->setFlash(__('Dept deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Dept was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	function admin_index() {
		$this->Dept->recursive = 0;
		$this->set('depts', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid dept', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('dept', $this->Dept->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Dept->create();
			if ($this->Dept->save($this->data)) {
				$this->Session->setFlash(__('The dept has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The dept could not be saved. Please, try again.', true));
			}
		}
		$companies = $this->Dept->Company->find('list');
		$this->set(compact('companies'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid dept', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Dept->save($this->data)) {
				$this->Session->setFlash(__('The dept has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The dept could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Dept->read(null, $id);
		}
		$companies = $this->Dept->Company->find('list');
		$this->set(compact('companies'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for dept', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Dept->delete($id)) {
			$this->Session->setFlash(__('Dept deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Dept was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>