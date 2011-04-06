<?php
/**
 *
 * @author   Nicolas Rod <nico@alaxos.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.alaxos.ch
 */
class ArosController extends AclAppController {

	var $name       = 'Aros';
	var $uses       = array('Aro');
	var $components = array('RequestHandler');
	var $helpers    = array('Js' => array('Jquery'));
	
	var $paginate = array(
        'limit' => 25,
        'order' => array(
            'display_name' => 'asc'
        ));
	
	function beforeFilter()
	{
	    $this->loadModel(Configure :: read('acl.aro.role.model'));
	    $this->loadModel(Configure :: read('acl.aro.user.model'));
	    
	    parent :: beforeFilter();
	}
        
	
	function admin_index()
	{
	    
	}
	
	function admin_check($run = null)
	{
		$user_model_name = Configure :: read('acl.aro.user.model');
	    $role_model_name = Configure :: read('acl.aro.role.model');
	    
	    $missing_aros = array('roles' => array(), 'users' => array());
	    
		$roles = $this->{$role_model_name}->find('all');
		foreach($roles as $role)
		{
			/*
			 * Check if ARO for role exist
			 */
			$aro = $this->Aro->find('first', array('conditions' => array('model' => $role_model_name, 'foreign_key' => $role[$role_model_name]['id'])));
			
			if($aro === false)
			{
				$missing_aros['roles'][] = $role;
			}
		}
		
		$this->{$user_model_name}->virtualFields = array('display_name' => Configure :: read('acl.user.display_name'));
		$users = $this->{$user_model_name}->find('all');
		foreach($users as $user)
		{
			/*
			 * Check if ARO for user exist
			 */
			$aro = $this->Aro->find('first', array('conditions' => array('model' => $user_model_name, 'foreign_key' => $user[$user_model_name]['id'])));
			
			if($aro === false)
			{
				$missing_aros['users'][] = $user;
			}
		}
		
		
		if(isset($run))
		{
			$this->set('run', true);
			
			/*
			 * Complete roles AROs
			 */
			if(count($missing_aros['roles']) > 0)
			{
				foreach($missing_aros['roles'] as $k => $role)
				{
					$this->Aro->create(array('parent_id' 		=> null,
												'model' 		=> $role_model_name,
												'foreign_key' 	=> $role[$role_model_name]['id'],
												'alias'			=> $role[$role_model_name][Configure :: read('acl.aro.role.display_field')]));
					
					if($this->Aro->save())
					{
						unset($missing_aros['roles'][$k]);
					}
				}
			}
			
			/*
			 * Complete users AROs
			 */
			if(count($missing_aros['users']) > 0)
			{
				foreach($missing_aros['users'] as $k => $user)
				{
					/*
					 * Find ARO parent for user ARO
					 */
					$parent_id = $this->Aro->field('id', array('model' => $role_model_name, 'foreign_key' => $user[$user_model_name][strtolower(Configure :: read('acl.aro.role.model')) . '_id']));
					
					if($parent_id !== false)
					{
						$this->Aro->create(array('parent_id' 		=> $parent_id,
													'model' 		=> $user_model_name,
													'foreign_key' 	=> $user[$user_model_name]['id'],
													'alias'			=> $user[$user_model_name]['display_name']));
						
						if($this->Aro->save())
						{
							unset($missing_aros['users'][$k]);
						}
					}
				}
			}
		}
		else
		{
			$this->set('run', false);
		}
		
		$this->set('missing_aros', $missing_aros);
		
	}
	
	function admin_users()
	{
	    $user_model_name = Configure :: read('acl.aro.user.model');
	    $role_model_name = Configure :: read('acl.aro.role.model');
	    
	    $this->{$role_model_name}->recursive = -1;
	    $roles = $this->{$role_model_name}->find('all');
	    
	    $this->{$user_model_name}->recursive = -1;
	    $this->{$user_model_name}->virtualFields = array('display_name' => Configure :: read('acl.user.display_name'));
	    $users = $this->paginate($user_model_name);
	    
	    $missing_aro = false;
	    
	    foreach($users as &$user)
	    {
	    	$aro = $this->Acl->Aro->find('first', array('conditions' => array('model' => $user_model_name, 'foreign_key' => $user[$user_model_name]['id'])));
	    	
	        if($aro !== false)
	        {
	            $user['Aro'] = $aro['Aro'];
	        }
	        else
	        {
	            $missing_aro = true;
	        }
	    }
	    
	    $this->set('roles', $roles);
	    $this->set('users', $users);
	    $this->set('missing_aro', $missing_aro);
	}
	
	function admin_update_user_role()
	{
	    $user_model_name = Configure :: read('acl.aro.user.model');
	    
        $data = array($user_model_name => array('id' => $this->params['named']['user'], strtolower(Configure :: read('acl.aro.role.model')) . '_id' => $this->params['named']['role']));
	    
	    if($this->{$user_model_name}->save($data))
	    {
	        $this->Session->setFlash(__d('acl', 'The user role has been updated', true), 'flash_message', null, 'plugin_acl');
	    }
	    else
	    {
	        $this->Session->setFlash(__d('acl', 'The user role could not be updated', true), 'flash_error', null, 'plugin_acl');
	    }

	    $this->_return_to_referer();
	}
	
	function admin_role_permissions()
	{
	    $role_model_name = Configure :: read('acl.aro.role.model');
	    
	    $this->{$role_model_name}->recursive = -1;
	    $roles = $this->{$role_model_name}->find('all');

	    $actions = $this->AclReflector->get_all_actions();
	    //$actions = array_merge($actions, $this->get_additional_controllers());
            //$this->get_additional_controllers();
            debugger::dump($actions);

            $permissions = array();
	    $methods     = array();
	    
	    foreach($actions as $full_action)
    	{
	    	$arr = String::tokenize($full_action, '/');
	    	
			if (count($arr) == 2)
			{
				$plugin_name     = null;
				$controller_name = $arr[0];
				$action          = $arr[1];
			}
			elseif(count($arr) == 3)
			{
				$plugin_name     = $arr[0];
				$controller_name = $arr[1];
				$action          = $arr[2];
			}
    		
		    foreach($roles as $role)
	    	{
	    	    $aro_node = $this->Acl->Aro->node($role);
	            if(!empty($aro_node))
	            {
	            	$aco_node = $this->Acl->Aco->node($full_action);
	        	    if(!empty($aco_node))
	        	    {
	        	    	$authorized = $this->Acl->check($role, $full_action);
	        	    	
	        	    	$permissions[$role[Configure :: read('acl.aro.role.model')]['id']] = $authorized ? 1 : 0 ;
					}
	            }
	    		else
        	    {
        	        /*
        	         * No check could be done as the ARO is missing
        	         */
        	        $permissions[$role[Configure :: read('acl.aro.role.model')]['id']] = -1;
        	    }
    		}
    		
    		if(isset($plugin_name))
            {
            	$methods['plugin'][$plugin_name][$controller_name][] = array('name' => $action, 'permissions' => $permissions);
            }
            else
            {
        	    $methods['app'][$controller_name][] = array('name' => $action, 'permissions' => $permissions);
            }
    	}
    	
    	//debug($methods);
    	
	    $this->set('roles', $roles);
	    $this->set('actions', $methods);
	}
        //added to get additional controllers by DJS

	
	function admin_user_permissions($user_id = null)
	{
	    $user_model_name = Configure :: read('acl.aro.user.model');
	    $role_model_name = Configure :: read('acl.aro.role.model');
	    
		$this->{$user_model_name}->virtualFields = array('display_name' => Configure :: read('acl.user.display_name'));
		
	    if(empty($user_id))
	    {
	        $users = $this->paginate($user_model_name);
	        
	        $this->set('users', $users);
	    }
	    else
	    {
	        $this->{$role_model_name}->recursive = -1;
	        $roles = $this->{$role_model_name}->find('all');
	        
	        $this->{$user_model_name}->recursive = -1;
	        $user = $this->{$user_model_name}->read(null, $user_id);
	        
	        //debug($user);
	        
	        $permissions = array();
	    	$methods     = array();
	    		
	        /*
             * Check if the user exists in the ARO table
             */
            $user_aro = $this->Acl->Aro->node($user);
            if(empty($user_aro))
            {
                if(empty($this->{$user_model_name}->virtualFields['display_name']))
                {
                    $this->{$user_model_name}->virtualFields['display_name'] = Configure :: read('acl.user.display_name');
                }
                
                $display_user = $this->{$user_model_name}->find('first', array('conditions' => array($user_model_name . '.id' => $user_id)));
                $this->Session->setFlash(sprintf(__d('acl', "The user '%s' does not exist in the ARO table", true), $display_user[$user_model_name]['display_name']), 'flash_error');
            }
            else
            {
            	$actions = $this->AclReflector->get_all_actions();
        		
	            foreach($actions as $full_action)
		    	{
			    	$arr = String::tokenize($full_action, '/');
			    	
					if (count($arr) == 2)
					{
						$plugin_name     = null;
						$controller_name = $arr[0];
						$action          = $arr[1];
					}
					elseif(count($arr) == 3)
					{
						$plugin_name     = $arr[0];
						$controller_name = $arr[1];
						$action          = $arr[2];
					}
					
		    		$aco_node = $this->Acl->Aco->node($full_action);
	        	    if(!empty($aco_node))
	        	    {
	        	    	$authorized = $this->Acl->check($user, $full_action);
	        	    	
	        	    	//debug($full_action . ' => ' . $authorized);
	        	    	
	        	    	$permissions[$user[$user_model_name]['id']] = $authorized ? 1 : 0 ;
					}
					
			    	if(isset($plugin_name))
		            {
		            	$methods['plugin'][$plugin_name][$controller_name][] = array('name' => $action, 'permissions' => $permissions);
		            }
		            else
		            {
		        	    $methods['app'][$controller_name][] = array('name' => $action, 'permissions' => $permissions);
		            }
		    	}
            }
            
            //debug($methods);
            
            $this->set('user', $user);
            $this->set('roles', $roles);
            $this->set('actions', $methods);
	    }
	}
	
	function admin_empty_permissions()
	{
	    if($this->Aro->Permission->deleteAll(array('Permission.id > ' => 0)))
	    {
	        $this->Session->setFlash(__d('acl', 'The permissions have been cleared', true), 'flash_message', null, 'plugin_acl');
	    }
	    else
	    {
	        $this->Session->setFlash(__d('acl', 'The permissions could not be cleared', true), 'flash_error', null, 'plugin_acl');
	    }
	    
	    $this->_return_to_referer();
	}
	
	function admin_grant_all_controllers($role_id)
	{
	    $role =& $this->{Configure :: read('acl.aro.role.model')};
        $role->id = $role_id;
        
		/*
         * Check if the Role exists in the ARO table
         */
        $node = $this->Acl->Aro->node($role);
        if(empty($node))
        {
            $asked_role = $role->read(null, $role_id);
            $this->Session->setFlash(sprintf(__d('acl', "The role '%s' does not exist in the ARO table", true), $asked_role['Role'][Configure :: read('acl.aro.role.display_field')]), 'flash_error');
        }
        else
        {
            //Allow to everything
            $this->Acl->allow($role, 'controllers');
        }
        
	    $this->_return_to_referer();
	}
	function admin_deny_all_controllers($role_id)
	{
	    $role =& $this->{Configure :: read('acl.aro.role.model')};
        $role->id = $role_id;
        
        /*
         * Check if the Role exists in the ARO table
         */
        $node = $this->Acl->Aro->node($role);
        if(empty($node))
        {
            $asked_role = $role->read(null, $role_id);
            $this->Session->setFlash(sprintf(__d('acl', "The role '%s' does not exist in the ARO table", true), $asked_role['Role'][Configure :: read('acl.aro.role.display_field')]), 'flash_error');
        }
        else
        {
            //Deny everything
            $this->Acl->deny($role, 'controllers');
        }
        
	    $this->_return_to_referer();
	}
	
	function admin_grant_role_permission($role_id)
	{
	    $role =& $this->{Configure :: read('acl.aro.role.model')};
        
        $role->id = $role_id;
        
        $aco_path = $this->_get_passed_aco_path();
        
        /*
         * Check if the role exists in the ARO table
         */
        $aro_node = $this->Acl->Aro->node($role);
        if(!empty($aro_node))
        {
            if(!$this->AclManager->save_permission($aro_node, $aco_path, 'grant'))
            {
                $this->set('acl_error', true);
            }
        }
        else
        {
            $this->set('acl_error', true);
            $this->set('acl_error_aro', true);
        }
        
        $this->set('role_id', $role_id);
        $this->_set_aco_variables();
        
        if($this->RequestHandler->isAjax())
        {
            $this->render('ajax_role_granted');
        }
        else
        {
            $this->_return_to_referer();
        }
	}
	function admin_deny_role_permission($role_id)
	{
	    $role =& $this->{Configure :: read('acl.aro.role.model')};
        
        $role->id = $role_id;
        
        $aco_path = $this->_get_passed_aco_path();
        
        $aro_node = $this->Acl->Aro->node($role);
        if(!empty($aro_node))
        {
            if(!$this->AclManager->save_permission($aro_node, $aco_path, 'deny'))
            {
                $this->set('acl_error', true);
            }
        }
        else
        {
        	$this->set('acl_error', true);
        }
        
        $this->set('role_id', $role_id);
        $this->_set_aco_variables();
        
        if($this->RequestHandler->isAjax())
        {
            $this->render('ajax_role_denied');
        }
        else
        {
            $this->_return_to_referer();
        }
	}
	
	function admin_grant_user_permission($user_id)
	{
	    $user =& $this->{Configure :: read('acl.aro.user.model')};
        
        $user->id = $user_id;

        $aco_path = $this->_get_passed_aco_path();
        
        /*
         * Check if the user exists in the ARO table
         */
        $aro_node = $this->Acl->Aro->node($user);
        if(!empty($aro_node))
        {
        	$aco_node = $this->Acl->Aco->node($aco_path);
        	if(!empty($aco_node))
        	{
	            if(!$this->AclManager->save_permission($aro_node, $aco_path, 'grant'))
	            {
	                $this->set('acl_error', true);
	            }
        	}
        	else
        	{
        		$this->set('acl_error', true);
            	$this->set('acl_error_aco', true);
        	}
        }
        else
        {
            $this->set('acl_error', true);
            $this->set('acl_error_aro', true);
        }
        
        $this->set('user_id', $user_id);
        $this->_set_aco_variables();
        
        if($this->RequestHandler->isAjax())
        {
            $this->render('ajax_user_granted');
        }
        else
        {
            $this->_return_to_referer();
        }
	}
	function admin_deny_user_permission($user_id)
	{
	    $user =& $this->{Configure :: read('acl.aro.user.model')};
        
        $user->id = $user_id;

        $aco_path = $this->_get_passed_aco_path();
        
        /*
         * Check if the user exists in the ARO table
         */
        $aro_node = $this->Acl->Aro->node($user);
        if(!empty($aro_node))
        {
        	$aco_node = $this->Acl->Aco->node($aco_path);
        	
        	if(!empty($aco_node))
        	{
        	    if(!$this->AclManager->save_permission($aro_node, $aco_path, 'deny'))
	            {
	                $this->set('acl_error', true);
	            }
        	}
        	else
        	{
        		$this->set('acl_error', true);
            	$this->set('acl_error_aco', true);
        	}
        }
        else
        {
            $this->set('acl_error', true);
            $this->set('acl_error_aro', true);
        }
        
        $this->set('user_id', $user_id);
        $this->_set_aco_variables();
        
        if($this->RequestHandler->isAjax())
        {
            $this->render('ajax_user_denied');
        }
        else
        {
            $this->_return_to_referer();
        }
	}
}
?>