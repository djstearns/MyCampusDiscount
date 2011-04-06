<?php
class CoreappsettingsController extends AppController {

	var $name = 'Coreappsettings';

	function index() {
		$this->Coreappsetting->recursive = 0;
		$this->set('coreappsettings', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->flash(__('Invalid coreappsetting', true), array('action' => 'index'));
		}
		$this->set('coreappsetting', $this->Coreappsetting->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Coreappsetting->create();
			if ($this->Coreappsetting->save($this->data)) {
				$this->flash(__('Coreappsetting saved.', true), array('action' => 'index'));
			} else {
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->flash(sprintf(__('Invalid coreappsetting', true)), array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Coreappsetting->save($this->data)) {
				$this->flash(__('The coreappsetting has been saved.', true), array('action' => 'index'));
			} else {
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Coreappsetting->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->flash(sprintf(__('Invalid coreappsetting', true)), array('action' => 'index'));
		}
		if ($this->Coreappsetting->delete($id)) {
			$this->flash(__('Coreappsetting deleted', true), array('action' => 'index'));
		}
		$this->flash(__('Coreappsetting was not deleted', true), array('action' => 'index'));
		$this->redirect(array('action' => 'index'));
	}
}
?>