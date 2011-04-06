<?php
    class AppController extends Controller {
        //Don't use security, it will bomb out the filter!!
        public $components = array('RequestHandler', 'Acl', 'Filter.Filter', 'Multiedit', 'Permissionable.Permissionable', 'Session',  'LoadsysAuth', 'Email', 'aclmenu.menu');//, 'flash');// 'Menu');
         
        
        //public $components = array('Acl','Permissionable.Permissionable', 'Session', 'LoadsysAuth', 'Email');
        var $helpers = array( 'Form', 'Html', 'Js' => array('Mootools'), 'Javascript','Time', 'Session', 'aclmenu.hgrid', 'flash');//'Menu');
        function beforeFilter() {

            $this->LoadsysAuth->allowedActions = array('login', 'logout', 'register','build_acl', 'resend', 'display');

            $this->loadModel('Coreappsetting');
            
            $this->LoadsysAuth->authorize = 'actions';
            $this->LoadsysAuth->actionPath = 'controllers/';
            $this->LoadsysAuth->loginAction = array('controller' => 'users', 'action' => 'login');
            $this->LoadsysAuth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
            $this->LoadsysAuth->loginRedirect = array('controller' => 'pages', 'action' => 'home');
            
            $user_info = $this->LoadsysAuth->User();
            $this->paginate = array('limit' => $user_info['User']['numreccs']);

            $this->set('user_info', $user_info);

            
        }



         Public function multiedit($id=null){
            if (empty($this->data)) {
			$this->data = $this->{$this->modelClass}->read(null, $id);
            }
            $this->Multiedit->showmulti($id);
        }

        function flash( $message, $class = 'status' )
        {
            $old = $this->Session->read('messages');
            $old[$class][] = $message;
            $this->Session->write( 'messages', $old );
        }
     

        


     }

?>