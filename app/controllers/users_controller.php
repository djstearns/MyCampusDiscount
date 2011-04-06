<?php
class UsersController extends AppController {

	var $name = 'Users';
        

        function beforeFilter() {
            parent::beforeFilter();
            $actions = array('add', 'edit', 'admin_add', 'admin_edit');
            
            if(in_array($this->action, $actions)) {
             
                $this->LoadsysAuth->authenticate = $this->User;
                
            }
             
            //debugger::dump($this->User->id);
        }


        Public Function sendActivationEmails($to, $notice){
            //debugger::dump('sending emails');
            $this->Email->replyTo   = 'noreply@dell.com';
            $this->Email->from      = 'System <noreply@dell.com>';
            $this->Email->to        = $to;
            if($notice == true){

                $this->Email->subject   = 'New User Activiation on your System!';
                $temp = 'temp1';
                $layout = 'layone';
            }else{
                $this->Email->subject   = 'Your System Account Activation';
                $temp = 'activation';
                $layout = 'default';
            }
            //$this->Email->bcc       = array('djstearns402@gmail.com');
            //The email element to use for the message (located in app/views/elements/email/html/ and app/views/elements/email/text/)
            $this->Email->template  = $temp;
            //The layout used for the email (located in app/views/layouts/email/html/ and app/views/layouts/email/text/)
            $this->Email->layout    = $layout;
            $this->Email->sendas    = 'both';
             /* SMTP Options */
            $this->Email->smtpOptions = array(
                'host' => 'mail.tagtmi.com',
                'username' => 'dstearns',
                'password' => 'Qu@ker1',
                'port' => '25',
                'timeout' => '30'
            );
            /* Set delivery method */
            $this->Email->delivery = 'smtp';
            /* Do not pass any args to send() */
            //debugger::dump($this);
            $this->set('smtp-errors', $this->Email->smtpError);
            $this->set('user', $this->data['User']);
            $this->set('key', $this->data['Userticket']['0']);
            $this->Email->send();
            /* Check for SMTP errors. */


            //debugger::dump($this->Email->smtpError);
        }
        
        function register(){
            
            if (!empty($this->data)) {
                        $this->data['User']['group_id'] = "24";
			$this->User->create();
                        //debugger::dump('SAAVEALL');
                        $this->data = $this->User->hashPasswords($this->data, false);
			if ($this->User->saveAll($this->data)) {
                                //if($this->User->Usertickets->save($this->data)){
                                    //debugger::dump('saved the tickets!');
                                
                                //Send registration emails
                                
                                //$this->sendActivationEmails($this->data['User']['email'], false);
                                //$this->Email->reset();
                                //$this->sendActivationNotice();
                                //$this->build_acl();
                                $this->Redirect = array('controller'=>'users','action' => 'index');
				//$this->Session->setFlash(__('Please check your email to activate your account.', true));
                                $this->Session->setFlash(__('Your account has been added.', true));
                                unset($this->data['User']['password']);
                                //}
			} else {
                                unset($this->data['User']['password']);
                                $this->Redirect = array('controller'=>'users','action' => 'login');
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
                                
			}
		}

                $keystring = $this->User->genRandomString(25);
                $days = $this->User->adddays(14);
		$groups = $this->User->Group->find('list');
                
		$this->set(compact('groups', 'keystring','days', 'User'));
            



        }

        Function resendActivationEmails($to, $notice){
            //debugger::dump('sending emails');
            $this->Email->replyTo   = 'noreply@dell.com';
            $this->Email->from      = 'System <noreply@dell.com>';
            $this->Email->to        = $to;
            if($notice == true){

                $this->Email->subject   = 'New User Activiation on your System!';
                $temp = 'temp1';
                $layout = 'layone';
            }else{
                $this->Email->subject   = 'Your System Account Activation';
                $temp = 'activation';
                $layout = 'default';
            }
            //$this->Email->bcc       = array('djstearns402@gmail.com');
            //The email element to use for the message (located in app/views/elements/email/html/ and app/views/elements/email/text/)
            $this->Email->template  = $temp;
            //The layout used for the email (located in app/views/layouts/email/html/ and app/views/layouts/email/text/)
            $this->Email->layout    = $layout;
            $this->Email->sendas    = 'both';
             /* SMTP Options */
            $this->Email->smtpOptions = array(
                'host' => 'mail.tagtmi.com',
                'username' => 'dstearns',
                'password' => 'Qu@ker1',
                'port' => '25',
                'timeout' => '30'
            );
            /* Set delivery method */
            $this->Email->delivery = 'smtp';
            /* Do not pass any args to send() */
            //debugger::dump($this);
            $this->set('smtp-errors', $this->Email->smtpError);
            //debugger::dump($this->data['Userticket'][0]);
            $this->set('user', $this->data['User']);
            $this->set('key', $this->data['Userticket']['0']);
            $this->Email->send();
            /* Check for SMTP errors. */


            //debugger::dump($this->Email->smtpError);
        }

        function resend($id = null){
            if (empty($this->data)) {       
			$this->Session->setFlash(__('Please enter your email address.', true));		
		}
		if (!empty($this->data)) {
                    
                    $this->data = $this->User->find('first', array('conditions' => array('email' => $this->data['User']['email'])));
		    if($this->data){
                            if ($this->data['Userticket'][0]['id']) {
                                $this->resendActivationEmails($this->data['User']['email'], false);
				$this->Session->setFlash(__('Activation resent.', true));
                                $this->redirect($this->LoadsysAuth->logout());
                            }else{
				$this->Session->setFlash(__('Your activation could not be found. Please contact administrator.', true));
                            }
                    }else{
                        $this->Session->setFlash(__('Please enter your registered email address.', true));
                    }
		}
		
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
                //$helpme = "test";
                //$this->set(compact('helpme'));
	}

        function reset($id = null){
                if (empty($this->data)) {
			$this->Session->setFlash(__('Please enter your email address.', true));
		}
		if (!empty($this->data)) {
                    $this->data = $this->User->find('first', array('conditions' => array('email' => $this->data['User']['email'])));
		    if($this->data){
                            if ($this->data['Userticket'][0]['id']) {
                                $this->sendPasswordEmails($this->data['User']['email'], false);
				$this->Session->setFlash(__('Activation resent.', true));
                            }else{
				$this->Session->setFlash(__('Your activation could not be found. Please contact administrator.', true));
                            }
                    }else{
                        $this->Session->setFlash(__('Please enter your registered email address.', true));
                    }
		}

		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
        }

        function login() {
            
            if($this->data){

                if ($this->Session->read('LoadsysAuth.User')) {
                  
                        $this->User->updateLastLogin($this->User->id);
                        //debugger::dump($this->User->Userticket);
                  
                        //$this->redirect('/', null, false);
                }else{

                    $this->Session->setFlash('Incorrect username or password.');
                    $this->LoadsysAuth->Redirect = array('controller' => 'Users', 'action' => 'reset');
                }
            }else{
                $this->Session->setFlash('Please sign in.');
            }
        }
        function logout() {
            $this->Session->setFlash('Good-Bye');
            $this->redirect($this->LoadsysAuth->logout());
        }





	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
                //debugger::dump($this);
		$this->set('user', $this->User->read(null, $id));
	}

	function add() {
            //debugger::dump($this->data['User']);

		if (!empty($this->data)) {
                        $this->User->create();
                        $this->data = $this->User->hashPasswords($this->data, true);
                        //debugger::dump($this->data['User']);
                        if ($this->User->save($this->data)) {
                                $this->Session->setFlash(__('The user has been saved', true));
                                $this->redirect(array('action' => 'index'));
                        } else {

                                $this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
                        }
                }
           
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
             
	}
/** Crucuial
 *
 * @param <type> $created 
 */
        function afterSave($created) {
            if (!$created) {
                $parent = $this->parentNode();
                $parent = $this->node($parent);
                $node = $this->node();
                $aro = $node[0];
                $aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
                $this->Aro->save($aro);
            }
        }

        function index() {

            $this->paginate = $this->Filter->paginate;

            $users = $this->paginate('User', array('User.id' => $this->LoadsysAuth->user('id')));
            $this->set(compact('users'));

            //$this->User->recursive = 0;
            //$this->set('users', $this->paginate());
        }
       

	function edit($id = null) {

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
                    //debugger::dump($this->data['User']);
                        //$this->data['User']['password'] = $this->LoadsysAuth->password($this->data['User']['password']);
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
                        $this->data['User']['password'] = null;  
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
                //$helpme = "test";
                //$this->set(compact('helpme'));
	}

        function manage($id = null) {
            // Single Model - Single Page - Multiple Forms Hack
            $this->loadModel('edituserform');
            $this->loadModel('adduserform');

            if (!empty($this->data)) {
                if (isset($this->data['edituserform'])) { // Check if the Edit Form was submitted
                    if ($this->edituserform->save($this->data)) {
                        // Code
                    }
                } else if (isset($this->data['adduserform'])) { // Check if the Add Form was submitted
                    if ($this->adduserform->save($this->data)) {
                        // Code
                    }
                }
            }
            $groups = $this->User->Group->find('list');
            $this->set(compact('groups'));
        }


        function delete($id = null) {
           
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
                 
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
                
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
                    $this->data['User']['password'] = $this->LoadsysAuth->password($this->data['User']['password']);
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
		$groups = $this->User->Group->find('list');
                $depts = $this->User->Dept->find('list');
		$this->set(compact('groups','depts'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

        function user_actions(){
            
        }

        function build_acl() {
            if (!Configure::read('debug')) {
                return $this->_stop();
            }
            $log = array();
            $aco =& $this->Acl->Aco;
            $root = $aco->node('controllers');
            if (!$root) {
                $aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
                $root = $aco->save();
                $root['Aco']['id'] = $aco->id;
                $log[] = 'Created Aco node for controllers';
             } else {
                $root = $root[0];
             }
             App::import('Core', 'File');
             $Controllers = Configure::listObjects('controller');
             $appIndex = array_search('App', $Controllers);
             if ($appIndex !== false ) {
                 unset($Controllers[$appIndex]);
             }
             $baseMethods = get_class_methods('Controller');
             $baseMethods[] = 'buildAcl';
             $Plugins = $this->_getPluginControllerNames();
             $Controllers = array_merge($Controllers, $Plugins);
             // look at each controller in app/controllers
             foreach ($Controllers as $ctrlName) {
                 $methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));
             // Do all Plugins First
                 if ($this->_isPlugin($ctrlName)){
                    $pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
                    if (!$pluginNode) {
                        $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
                        $pluginNode = $aco->save();
                        $pluginNode['Aco']['id'] = $aco->id;
                        $log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
                    }
                 }
                 // find / make controller node
                 $controllerNode = $aco->node('controllers/'.$ctrlName);
                 if (!$controllerNode) {
                    if ($this->_isPlugin($ctrlName)){
                        $pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
                        $aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
                        $controllerNode = $aco->save();
                        $controllerNode['Aco']['id'] = $aco->id;
                        $log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
                    } else {
                        $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
                        $controllerNode = $aco->save();
                        $controllerNode['Aco']['id'] = $aco->id;
                        $log[] = 'Created Aco node for ' . $ctrlName;
                    }
                 } else {
                    $controllerNode = $controllerNode[0];
                 }
                 //clean the methods. to remove those in Controller and private actions.
                 foreach ($methods as $k => $method) {
                    if (strpos($method, '_', 0) === 0) {
                        unset($methods[$k]);
                        continue;
                    }
                    if (in_array($method, $baseMethods)) {
                        unset($methods[$k]);
                        continue;
                    }
                    $methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
                    if (!$methodNode) {
                        $aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
                        $methodNode = $aco->save();
                        $log[] = 'Created Aco node for '. $method;
                    }
                 }
             }
            if(count($log)>0) {
                //debug($log);
            }
        }

        function _getClassMethods($ctrlName = null) {
            App::import('Controller', $ctrlName);
            if (strlen(strstr($ctrlName, '.')) > 0) {
            // plugin's controller
                $num = strpos($ctrlName, '.');
                $ctrlName = substr($ctrlName, $num+1);
            }
            $ctrlclass = $ctrlName . 'Controller';
            $methods = get_class_methods($ctrlclass);
            // Add scaffold defaults if scaffolds are being used
            $properties = get_class_vars($ctrlclass);
            if (array_key_exists('scaffold',$properties)) {
                if($properties['scaffold'] == 'admin') {
                    $methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
                } else {
                    $methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
                }
            }
            return $methods;
        }

        function _isPlugin($ctrlName = null) {

            $arr = String::tokenize($ctrlName, '/');
            if (count($arr) > 1) {
                return true;
            } else {
                return false;
            }
        }

        function _getPluginControllerPath($ctrlName = null) {

            $arr = String::tokenize($ctrlName, '/');
           if (count($arr) == 2) {
                return $arr[0] . '.' . $arr[1];
            } else {
                return $arr[0];
            }
         }


         function _getPluginName($ctrlName = null) {

            $arr = String::tokenize($ctrlName, '/');
            if (count($arr) == 2) {
                return $arr[0];
            } else {
                return false;
            }
        }

        function _getPluginControllerName($ctrlName = null) {
            $arr = String::tokenize($ctrlName, '/');
            if (count($arr) == 2) {
                return $arr[1];
            } else {
                return false;
            }
        }

// * Get the names of the plugin controllers ...
//*
// This function will get an array of the plugin controller names, and
// * also makes sure the controllers are available for us to get the
// * method names by doing an App::import for each plugin controller.
// *
// * @return array of plugin names.
// *
//

        function _getPluginControllerNames() {
            App::import('Core', 'File', 'Folder');
            $paths = Configure::getInstance();
            $folder =& new Folder();
            $folder->cd(APP . 'plugins');
            // Get the list of plugins
            $Plugins = $folder->read();
            $Plugins = $Plugins[0];
            $arr = array();
            // Loop through the plugins
            foreach($Plugins as $pluginName) {
            // Change directory to the plugin
                $didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
                // Get a list of the files that have a file name that ends
                // with controller.php
                $files = $folder->findRecursive('.*_controller\.php');
                // Loop through the controllers we found in the plugins directory
                foreach($files as $fileName) {
                    // Get the base file name
                    $file = basename($fileName);
                    // Get the controller name
                    $file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
                    if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
                        if (!App::import('Controller', $pluginName.'.'.$file)) {
                            debug('Error importing '.$file.' for plugin '.$pluginName);
                        } else {
                            /// Now prepend the Plugin name ...
                            // This is required to allow us to fetch the method names.
                            $arr[] = Inflector::humanize($pluginName) . "/" . $file;
                        }
                    }
                }
            }
            return $arr;
        }

}
?>