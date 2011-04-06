<?php
class MenuComponent extends Object {
	var $components = array('RequestHandler', 'Filter.Filter', 'Multiedit', 'Acl','Permissionable.Permissionable', 'Session', 'LoadsysAuth', 'Security');

	function startup() {
            /* this function starts menu iteration

             */
            
            $topmenu = array();
            $menu = array();
            //reset menu
            $MenuIns = ClassRegistry::init('Menu');
            

            //add the menu class
            $Menumod = $MenuIns->find('threaded', array('order'=> array('Menu.lft')));
            //sort and thread menu tree to variable
            debugger::dump($Menumod[0]['children']);
            debugger::dump($Menumod[0]['children'][6]);
            debugger::dump($Menumod[0]['children'][9]['Menu']);

            foreach($Menumod[0]['children'] as $menuitem){
                //debugger::dump('test');
                //debugger::dump($menuitem['Menu']['topmenu']);
                if($menuitem['Menu']['topmenu']==1){
                    
                    $topmenu = $menuitem['children'];
                }
            }
            $menus = $this->parse($Menumod[0]['children'] ,false);
            
            $topmenu = $this->parse($topmenu ,true);
            //being parse iteration
            
                //$testusergrp = $this->LoadsysAuth->Group();
                //debugger::dump($testusergrp['User']);
                $this->Session->write('Menu.main', $menus);
        
            
            $this->Session->write('Menu.top', $topmenu);
            //write the menu to view variable.
        }

  

        function parse($array,  $root, $parentstoskip = 0, $out = Null) {
        /* This function parses the menu tree and returns the better sorted array with appropriate menu titles and
            links for each parent and child.
         */
            $out = array();
            //iterate through menu array.
            foreach($array as $itemname => $arritem){

                //get custom label
                //debugger::dump($arritem);
                if(is_null($arritem['Menu']['label']) || trim($arritem['Menu']['label']) == ''){
                    $lnk = $arritem['Menu']['name'];
                }else{
                    $lnk = $arritem['Menu']['label'];
                }

                //get custom URL
                if($arritem['Menu']['customopt']  == True){
                    $urllnk = $arritem['Menu']['url'];
                    //debugger::dump($urllnk);
                }else{
                    if($root == true){
                        
                        $urllnk = $this->getTheRootPath($arritem['Menu']['id'], 1, $root);
                    }else{
                        $urllnk = $this->getThePath($arritem['Menu']['id'], $parentstoskip, $root);
                    }
                    
                }

                //check if parent menu item
                if(array_key_exists('children',$arritem) &&  !empty($arritem['children'])){
                    //check if public
                    if($arritem['Menu']['public']==true){
                        //display item
                        $out[strtolower($lnk)] = $this->parse($arritem['children'], $root, $parentstoskip + 1, $out);
                        $out['Paths'][strtolower($lnk)] = strtolower($urllnk);
                        //debugger::dump($urllnk);
                    }else{
                        //not public, check acl
                        
                        if($this->Acl->check($this->LoadsysAuth->user(),$arritem['Menu']['name']) == true){
                            $out[strtolower($lnk)] = $this->parse($arritem['children'], $root, $parentstoskip + 1, $out);
                            //$out[strtolower($arritem['Menu']['name'])] = $this->parse($arritem['children'],$out);
                            $out['Paths'][strtolower($lnk)] = strtolower($urllnk);
                            
                        }
                    }
                }else{
               //This is a child menu item.
                    //check if public first
                    if($arritem['Menu']['public']==true){
                        //show item: public
                        $out[$arritem['Menu']['name']] = $urllnk.'/';
                        
                    }else{
                    //not public
                        //check acl
                        if($this->Acl->check($this->LoadsysAuth->user(),$arritem['Menu']['name']) == true){
                            //debugger::dump($arritem['Menu']['name']);
                                //add menu item
                                $out[$arritem['Menu']['name']] = $urllnk.'/';
                            }
                    }
                //no children
                }
            }
            return $out;
	}

        function getTheRootPath($id, $skipchildnum = 0, $root){

            /*
             This function is able to parse the menu tree and return the proper path with "/"
             */
            //instantiate the menu model
            $MenuIns = ClassRegistry::init('Menu');
            //check if manual url is 
            $patharray = $MenuIns->getpath($id);
            $finalpath = '';

            //check for manual URL
           
            $i = 0;
            
            //debugger::dump(count($patharray));
            foreach($patharray as $fork => $forkarray){
                //debugger::dump($forkarray['Menu']['name']);
                
                if($forkarray['Menu']['name'] != 'controllers' && $forkarray['Menu']['skipasparent'] != 1 ){
                    
                    if($i >= count($patharray)-3){

                        if($forkarray['Menu']['skipmyparent'] == 1){
                            $pos = strrchr(substr($finalpath,0,-1),"/");
                            
                            $finalpath = substr($finalpath,0,$pos).strtolower($forkarray['Menu']['name']).'/';
                        }else{
                             $finalpath = $finalpath.strtolower($forkarray['Menu']['name']).'/';
                        }
                        //debugger::dump($finalpath);
                    }
                }
               $i = $i + 1;
            }
            return $finalpath;
        }

        function getThePath($id, $skipchildnum = 0, $root){

            /*
             This function is able to parse the menu tree and return the proper path with "/"
             */
            //instantiate the menu model
            $MenuIns = ClassRegistry::init('Menu');
            //check if manual url is
            $patharray = $MenuIns->getpath($id);
            $finalpath = '';

            //check for manual URL
        
                $i = 0;
            
            foreach($patharray as $fork => $forkarray){
                //debugger::dump($forkarray['Menu']['name']);

                if($forkarray['Menu']['name'] != 'controllers' && $forkarray['Menu']['skipasparent'] != 1 ){
                    if($i >= $skipchildnum){
                        //debugger::dump($skipchildnum);

                        $finalpath = $finalpath.strtolower($forkarray['Menu']['name']).'/';

                    }
                }
               $i = $i + 1;
            }
            return $finalpath;
        }


        function array_sort($array, $on, $order=SORT_ASC)
        {
            $new_array = array();
            $sortable_array = array();

            if (count($array) > 0) {
                foreach ($array as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $k2 => $v2) {
                            if ($k2 == $on) {
                                $sortable_array[$k] = $v2;
                            }
                        }
                    } else {
                        $sortable_array[$k] = $v;
                    }
                }

                switch ($order) {
                    case SORT_ASC:
                        asort($sortable_array);
                    break;
                    case SORT_DESC:
                        arsort($sortable_array);
                    break;
                }

                foreach ($sortable_array as $k => $v) {
                    $new_array[$k] = $array[$k];
                }
            }

            return $new_array;
        }

                /*

		$userMenu = array();
		$generalMenu = array();

		$generalMenu[__('Home', true)] = '/';

		if(!$this->Session->check('Auth.User')) {
			$userMenu[__('Register', true)] = '/users/register';
			$userMenu[__('Login', true)] = '/users/login';
		}
		else {
			$userMenu[__('Logout', true)] = '/users/logout';
			$userMenu[__('Change', true)] = '/users/change';
		}

		//sample child item
		$parent = array();
		$parent[__('Child', true)] = '/';
		$generalMenu[__('Parent', true)] = $parent;

		$user = $this->Session->read('Auth.User');


		//menus arra
		$menus = array();
		$menus[__('General', true)] = $generalMenu;
		$menus[__('User ', true)] = $userMenu;

		$this->Session->write('Menu.main', $menus);

	}
                 *
                 */
}
?>