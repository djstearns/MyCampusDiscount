<?php
class MenusController extends AppController {
    
        
	function populate() {

		$this->Menu->populate();

		echo 'Population complete';
		exit;

	}

	function index() {
            
            $this->Menu->enabled = true;
             $this->set('menus', $this->paginate());
	}

        function add() {
            $this->Menu->enabled = true;
            if (!empty($this->data)) {
                //debugger::dump($this->data['Menu']['parent_id']);
                $parrec = $this->Menu->find('first', array('conditions'=> array('Menu.id' => $this->data['Menu']['parent_id'])));
                //debugger::dump($parrec['Aco']);

                $this->Acl->Aco->create(array('parent_id' => $parrec['Aco']['id'], 'alias' => $this->data['Menu']['name'], 'model' => 'menu'));
                $this->Acl->Aco->save();
                             
                $this->data['Menu']['aco_id'] = $this->Acl->Aco->getLastInsertId($this->Acl->Aco);

                //debugger::dump($this->Acl->Aco->getLastInsertId($this->Acl->Aco));
                $this->Menu->save($this->data);
                
                    $this->redirect(array('action'=>'index'));
                    
                
            } else {
                $parents[0] = "[ No Parent ]";
                $nodelist = $this->Menu->generatetreelist(null,null,null," - ");
                if($nodelist) {
                    foreach ($nodelist as $key=>$value)
                        $parents[$key] = $value;
                }
                $this->set(compact('parents'));
            }
        }

       


        function edit($id=null) {
            $this->Menu->enabled = null;
            if (!empty($this->data)) {
                
                if($this->Menu->save($this->data)==false)
                    $this->Session->setFlash('Error saving Node.');
                $this->redirect(array('action'=>'index'));
            } else {
                if($id==null) die("No ID received");
                
                $this->data = $this->Menu->read(null, $id);
                $parents[0] = "[ No Parent ]";
                $nodelist = $this->Menu->generatetreelist(null,null,null," - ");
                if($nodelist)
                    foreach ($nodelist as $key=>$value)
                        $parents[$key] = $value;
                $this->set(compact('parents'));
            }
        }

        function delete($id=null) {
            //debugger::dump($this->Menu);
            $this->Menu->enabled = null;
            if($id==null){
                die("No ID received");

            }
            $this->Menu->id=$id;
            $this->data = $this->Menu->read(null, $id);
            //debugger::dump($this->data['Aco']);
            $acoid = $this->data['Aco']['id'];
            if($this->Menu->delete($id)==false){
                $this->Session->setFlash('The Node could not be deleted.');
            }else{
                //delete aco

                $this->Acl->Aco->delete($acoid);
                //debugger::dump($acoid);
            }
            $this->redirect(array('action'=>'index'));
        }


	function beforeFilter() {
                //debugger::dump($this->Menu);

		parent::beforeFilter();

		// ensure our ajax methods are posted
		//$this->Security->requirePost('getnodes', 'reorder', 'reparent');

	}

        function beforeSave() {
		return parent::beforeSave();
	}
 

	function getnodes() {

		// retrieve the node id that Ext JS posts via ajax
		$parent = intval($this->params['form']['node']);

		// find all the nodes underneath the parent node defined above
		// the second parameter (true) means we only want direct children
		$nodes = $this->Menu->children($parent, true);

		// send the nodes to our view
		$this->set(compact('nodes'));

	}

	function reorder() {

		// retrieve the node instructions from javascript
		// delta is the difference in position (1 = next node, -1 = previous node)

		$node = intval($this->params['form']['node']);
		$delta = intval($this->params['form']['delta']);

		if ($delta > 0) {
			$this->Menu->movedown($node, abs($delta));
		} elseif ($delta < 0) {
			$this->Menu->moveup($node, abs($delta));
		}

		// send success response
		exit('1');

	}

	function reparent(){

		$node = intval($this->params['form']['node']);
		$parent = intval($this->params['form']['parent']);
		$position = intval($this->params['form']['position']);

		// save the employee node with the new parent id
		// this will move the employee node to the bottom of the parent list

		$this->Menu->id = $node;
		$this->Menu->saveField('parent_id', $parent);

		// If position == 0, then we move it straight to the top
		// otherwise we calculate the distance to move ($delta).
		// We have to check if $delta > 0 before moving due to a bug
		// in the tree behaviour (https://trac.cakephp.org/ticket/4037)

		if ($position == 0) {
			$this->Menu->moveup($node, true);
		} else {
			$count = $this->Menu->childcount($parent, true);
			$delta = $count-$position-1;
			if ($delta > 0) {
				$this->Menu->moveup($node, $delta);
			}
		}

		// send success response
		exit('1');

	}
    
}
?>