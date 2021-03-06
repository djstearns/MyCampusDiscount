<?php
/**
 * Menu Component
 *
 * Uses ACL to generate Menus.
 *
 * Copyright 2008, Mark Story.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2008, Mark Story.
 * @link http://mark-story.com
 * @version 1.1
 * @author Mark Story <mark@mark-story.com>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class MenuComponent extends Object {
/**
 * The Default Menu Parent for things that have no parent element defined
 * used a lot by menu items generated by controller folder scrapings
 *
 * @var string
 */
	public $defaultMenuParent = null;
/**
 * Set to false to disable the auto menu generation in startup()
 * Useful if you want your menus generated off of Aro's other than the user in the current session.
 *
 * @var boolean
 */
	public $autoLoad = true;
/**
 * Controller reference
 *
 * @var object
 */
	public $Controller = null;
/**
 * Components used by Menu
 *
 * @var array
 */
	public $components = array('SuperAuth.Acl', 'SuperAuth.Auth');

/**
 * Key for the caching
 *
 * @var string
 */
	public $cacheKey = 'menu_storage';

/**
 * Time to cache menus for.
 *
 * @var string  String compatible with strtotime.
 */
	public $cacheTime = '+1 day';
/**
 * cache config key
 *
 * @var string
 */
	public $cacheConfig = 'menu_component';
/**
 * Separator between controller and action name.
 *
 * @var string
 */
	public $aclSeparator = '/';

/**
 * The Node path to get to the controller listing
 *
 * @var string
 **/
	public $aclPath = 'controllers/';

/**
 * Array of Actions to exclude when making menus.
 * Per controller exclusions can be set with Controller::menuOptions
 *
 * @var array
 **/
	public $excludeActions = array('view', 'edit', 'delete', 'admin_edit', 'admin_delete', 'admin_edit', 'admin_view');

/**
 * Completed list of methods to not include in menus. Includes all of Controller's methods.
 *
 * @var array
 **/
	public $excludedMethods = array();
/**
 * The Completed menu for the current user.
 *
 * @var array
 */
	public $menu = array();

/**
 * Raw menus before formatting, either loaded from parsing controllers directory or loading Cache
 *
 * @var array
 */
	public $rawMenus = array();
/**
 *
 * 
 * 
 *
 */
	public $acoControllers = array();

/**
 *
 * Internal Flag to check if new menus have been added to a cached menu set.  Indicates that new menu items
 * have been added and that menus need to be rebuilt.
 *
 */
	protected $_rebuildMenus = false;

/**
 * initialize function
 *
 * Takes Settings declared in Controller and assigns them.
 *
 * @return bool
 **/
	public function initialize(&$Controller, $settings) {
		if (!empty($settings)) {
			$this->_set($settings);
		}
		return true;
	}

/**
 * Startup Method
 *
 * Automatically makes menus for all a the controllers based on the current user.
 * If $this->autoLoad = false then you must manually loadCache(),
 * contstructMenu() and writeCache().
 *
 * @param Object $Controller
 */
	public function startup(&$Controller) {
		$this->Controller =& $Controller;

		Cache::config($this->cacheConfig, array('engine' => 'File', 'duration' => $this->cacheTime, 'prefix' => $this->cacheKey));

		//no active session, no menu can be generated
		if (!$this->Auth->user()) {
			return;
		}
		if ($this->autoLoad) {
                    //$this->clearCache();
                    $this->loadCache();
                    $this->constructMenu($this->Auth->user());
                    $this->writeCache();
		}
               
	}

/**
 * Write the current Block Access data to a file.
 *
 * @return boolean on success of writing a file.
 */
	public function writeCache() {
		$data = array(
			'menus' => $this->rawMenus
		);
		if (Cache::write($this->cacheKey, $data, $this->cacheConfig)) {
			return true;
		}
		$this->log('Menu Component - Could not write Menu cache.');
		return false;
	}

/**
 * Load the Cached Permissions and restore them
 *
 * @return boolean true if cache was loaded.
 */
	public function loadCache() {
		if ($data = Cache::read($this->cacheKey, $this->cacheConfig)) {
			$this->rawMenus = $this->_mergeMenuCache($data['menus']);
			return true;
		}
		$this->_rebuildMenus = true;
		return false;
	}

/**
 * Clears the raw Menu Cache, this will in turn force
 * a menu rebuild for each ARO that needs a menu.
 *
 * @return boolean
 **/
	public function clearCache() {
		return Cache::delete($this->cacheKey, $this->cacheConfig);
	}

/**
 * Construct the menus From the Controllers in the Application.  This is an expensive
 * Process Timewise and is cached.
 *
 * @param string $aro  Aro Alias / identification array that a menu is needed for.
 */
	public function constructMenu($aro) {
                
                //$this->_rebuildMenus = true;
		$simple = true;
               
                $aroKey = $aro;
		if (is_array($aro)) {
			$aroKey = key($aro) . $aro[key($aro)]['id'];
		}
		$cacheKey = $aroKey . '_' . $this->cacheKey;
		$completeMenu = Cache::read($cacheKey, $this->cacheConfig);
                
		if (!$completeMenu || $this->_rebuildMenus == true) {
                //add if statement for type of menu bulding
                     //debugger::dump($completeMenu);
                    if($simple == true){
                       $complex = true;
                    //this is simple menu building
			$this->generateRawMenus($complex);
                        
			$menu = array();
			$size = count($this->rawMenus);
			for ($i = 0; $i < $size; $i++) {

				$item = $this->rawMenus[$i];
				//debugger::dump($item['url']);
                                $aco = Inflector::underscore($item['url']['controller']);
                                
				if(strpos($aco,'.')){
                                    $aco = str_replace('.','/',$aco);
                                }
                                //debugger::dump($item['url']['controller'].'*'.$item['url']['action']);
                                //debugger::dump($item['url']);
                                if (isset($item['url']['action'])) {
                                        //debugger::dump($item['url']);
                                        //if statement added 1/27/2010 because acl check fails on amdin/ routes
                                        
                                            $aco = $this->aclPath . $aco . $this->aclSeparator . $item['url']['action'];
                                        
				}

                                //DELETE THIS SETTING WHEN POSTING TO GIT HUB!!
                                //debugger::dump($aro['User']['id']);
                                //if ($aro['User']['id'] != 60){
                                    //debugger::dump($aco);
                                    if ($this->Acl->check($aro, $aco)) {
                                        //debugger::dump($aco);

                                            if (!isset($menu[$item['id']])) {
                                                
                                                //debugger::dump($aco);
                                                    if(strpos($this->rawMenus[$i]['url']['controller'],'.')){
                                                        $this->rawMenus[$i]['url']['controller'] = str_replace('.','/',$this->rawMenus[$i]['url']['controller']);
                                                    }
                                                    
                                                    if($this->rawMenus[$i]['url']['admin']==true){
                                                        $this->rawMenus[$i]['url']['controller'] = 'admin/'.$this->rawMenus[$i]['url']['controller'];
                                                        $this->rawMenus[$i]['url']['action'] = str_replace('admin_','',$this->rawMenus[$i]['url']['action']);
                                                    }
                                                    $menu[$item['id']] = $this->rawMenus[$i];

                                            }
                                    }
                               // }
			}
                        //debugger::dump($menu);
			$completeMenu = $this->_formatMenu($menu);
                        //debugger::dump($completeMenu);
			Cache::write($cacheKey, $completeMenu, $this->cacheConfig);
                }else{
                    //complex Menu building
                    
                    $MenuIns = ClassRegistry::init('Menu');
                    
                    //add the menu class
                    $Menumod = $MenuIns->find('threaded', array('order'=> array('Menu.lft')));
                    //sort and thread menu tree to variable

                    foreach($Menumod[0]['children'] as $menuitem){
                        //debugger::dump('test');
                        //debugger::dump($menuitem['Menu']['topmenu']);
                        if($menuitem['Menu']['topmenu']==1){

                            $topmenu = $menuitem['children'];
                        }
                    }
                    $completeMenu = $this->parse($Menumod[0]['children'] ,false);

                    $topMenu = $this->parse($topmenu ,true);
                    Cache::write($cacheKey, $completeMenu, $this->cacheConfig);
                    
                }
                }
		$this->menu = $completeMenu;
                
	}

/**
 * Generate Raw Menus from Controller in the Application
 * Loads a list of All controllers in the app/controllers, imports the class and gets a method
 * list.  Uses a common exclusion list to remove unwanted methods.  Each Controller can specify a
 * menuOptions var which allows additional menu configuration.
 *
 * Menu Options for Controllers:
 * 		exclude => actions to exclude from the menu list
 * 		parent => Parent link to add a controller / actions underneath
 * 		alias => array of action => aliases Allows you to set friendly link names for actions
 *
 * @return void sets $this->rawMenus
 */
	public function generateRawMenus($complex = false) {
		$Controllers = $this->getControllers($complex);
                //debugger::dump($Controllers);
		$cakeAdmin = Configure::read('Routing.prefixes');
                //debugger::dump(Configure::read('Routing.prefixes'));
		$this->createExclusions();

		//go through the controllers folder and make an array of every menu that could be used.
		foreach($Controllers as $ControllerName => $Controller) {
                    //debugger::dump($Controller);
			if ($Controller == 'App') {
				continue;
			}
			$ctrlName = $ControllerName;
                        
                        if(!is_array($Controller)){
                            App::import('Controller', $ctrlName);
                            
                            //debugger::dump($Controller);
                            if($pos = strpos($ctrlName,'.')){
                                $ctrlclass = substr($ctrlName,$pos-strlen($ctrlName)+1) . 'Controller';
                            }else{
                                $ctrlclass = $ctrlName . 'Controller';
                            }
                        
                            $methods = get_class_methods($ctrlclass);

                            $classVars = get_class_vars($ctrlclass);
                            $menuOptions = $this->setOptions($classVars);
                            if ($menuOptions === false) {

                                    continue;
                            }
                            $methods = $this->filterMethods($methods, $menuOptions['exclude']);
                        }else{
                                    //debugger::dump($ControllerName);
                            //debugger::dump($this->acoControllers[$ControllerName]);

                                        $methods = $this->acoControllers[$ControllerName];
                                        $menuOptions = $this->setOptions(array());

                            //debugger::dump($methods);

                        }
			
                        $ctrlCamel = Inflector::variable($ctrlName);
			//debugger::dump($ctrlCamel);
                        $ctrlHuman = Inflector::humanize(Inflector::underscore($ctrlCamel));
			$methodList = array();
			$adminController = false;
			if($methods){
                            foreach ($methods as $action) {
                                    $camelAction = Inflector::variable($action);
                                        if (empty($menuOptions['alias']) || !isset($menuOptions['alias'][$action])) {
                                                $human = Inflector::humanize(Inflector::underscore($action));
                                        } else {
                                                $human = $menuOptions['alias'][$action];
                                        }
                                    if(strpos($ctrlCamel,'.')){
                                        $newCont = str_replace('.','/',$ctrlCamel);
                                       
                                    }else{
                                        $newCont = $ctrlCamel;
                                        
                                        
                                    }
                                    
                                    $url = array(
                                            'controller' => $newCont,
                                            'action' => $action,
                                            
                                    );

                                    if($cakeAdmin){
                                        
                                        foreach($cakeAdmin as $route){
                                            	$url[$route] = false;
                                            
                                            if (strpos($action, $route . '_') !== false) {
                                                
                                                $url[$route] = true;
                                                $adminController = true;
                                            }
                                        }
                                    }
                                    //debugger::dump($url);
                                    $parent = $menuOptions['controllerButton'] ? $ctrlCamel : $menuOptions['parent'];
                                   //debugger::dump($parent);

                                    $this->rawMenus[] = array(
                                            'parent' => $parent,
                                            'id' => $this->_createId($ctrlCamel, $action),
                                            'title' => $human,
                                            'url' => $url,
                                            'weight' => 0,
                                    );
                                     
                                    
                            }
                        }
			if ($menuOptions['controllerButton']) {
                                
				//$action = $adminController ? $route . '_index' : 'index';
                                $action = 'index';
                                //debugger::dump($menuOptions['parent']);
                                //debugger::dump($Controller);
                                $url = array(
					'controller' => $ctrlCamel,
					'action' => $action,
					'admin' => $adminController,
				);
				$menuItem = array(
					'parent' => $menuOptions['parent'],
					'id' => $ctrlCamel,
					'title' => $ctrlHuman,
					'url' => $url,
					'weight' => 0
				);
                                //debugger::dump($menuItem);
				$this->rawMenus[] = $menuItem;
			}
                        
		}
	}

/**
 * Get the Controllers in the Application
 *
 * @access public
 * @return void
 */
	public function getControllers($complex) {
                
                //add on if wanting to add menu controller items from ACOs table
                if($complex == true){
                    $rootAcoItems = $this->Acl->Aco->find('threaded');
                    foreach($rootAcoItems[0]['children'] as $rootitem){
                        $this->parseitem($rootitem);
                    }
                    $AControllers = $this->acoControllers;
                    //debugger::dump($AControllers);

                }else{

                    $AControllers = Configure::listObjects('controller');
                    $plugins = Configure::listObjects('plugin');
                    if (!empty($plugins)) {

                            foreach ($plugins as $plugin) {

                                    $pPath = APP . 'plugins' . DS . Inflector::underscore($plugin) . DS . 'controllers' . DS;
                                    $pluginControllers = Configure::listObjects('controller', $pPath, false);
                                    if (!empty($pluginControllers)) {

                                            foreach ($pluginControllers as $PController) {
                                                    $AControllers[] = "$plugin.$PController";
                                            }
                                    }
                            }
                    }
                }

                return $AControllers;
                
	}

        public function parseitem($item, $nodePath=null, $arra=array(),$init = true){
            if(empty($item['children'])){
                //not a controller, this is an action; do nothing
             
                $this->acoControllers[$nodePath][] = $item['Aco']['alias'];
            }else{
                //controller
                if($init ==true){
                    $nodePath = $item['Aco']['alias'];
                }else{
                    $nodePath = $nodePath.'.'.$item['Aco']['alias'];
                }
                    //$this->acoControllers[$nodePath] = array('type' => 'aco');
                //set item node name
                foreach($item['children'] as $childitem){
                    $this->parseitem($childitem, $nodePath, $arra, false);
                }

            }
            
        }



/**
 * filter out methods based on $menuOptions.
 * Removes private actions as well.
 *
 * @param array $methods  Array of methods to prepare
 * @param array $remove Array of additional Methods to remove, normally options on the controller.
 * @return array
 **/
	public function filterMethods($methods, $remove = array()) {
            $methods2 = array();
            if($methods){

                if (!empty($remove)) {
			$remove = array_map('strtolower', $remove);
		}
		$exclusions = array_merge($this->excludedMethods, $remove);

		foreach ($methods as $k => $method) {
                       //check for aco methods
                       if(array_key_exists('type',$methods)){
                            $method = strtolower($method['Aco']['alias']);
                            if (strpos($method, '_', 0) === 0) {
                                    unset($methods[$k]);
                            }
                            if (in_array($method, $exclusions)) {
                                    unset($methods[$k]);
                            }
                            $methods2[] = $method;
                       }else{
                        //check for regular methods
                            $method = strtolower($method);
                            if (strpos($method, '_', 0) === 0) {
                                    unset($methods[$k]);
                            }
                            if (in_array($method, $exclusions)) {
                                    unset($methods[$k]);
                            }
                       }
		}
                if($methods2){
                    $methods = $methods2;
                }
		return array_values($methods);
            }
	}

/**
 * Set the Options for the current Controller.
 *
 * @return mixed.  Array of options or false on total exclusion
 **/

	public function setOptions($controllerVars) {
		//$cakeAdmin = Configure::read('Routing.admin');
                $cakeAdmin = Configure::read('Routing.prefixes');
		$menuOptions = isset($controllerVars['menuOptions']) ? $controllerVars['menuOptions'] : array();

                /*
                    $exclude = array('view', 'edit', 'delete', $cakeAdmin . '_edit',
			$cakeAdmin . '_delete', $cakeAdmin . '_edit', $cakeAdmin . '_view');
                 *
                 */
                foreach($cakeAdmin as $route){
                    $exclude = array('view', 'edit', 'delete', $route . '_edit',
			$route . '_delete', $route . '_edit', $route . '_view');
                }
                //debugger::dump($exclude);
		$defaults = array(
			'exclude' => $exclude,
			'alias' => array(),
			'parent' => $this->defaultMenuParent,
			'controllerButton' => true
		);
		$menuOptions = Set::merge($defaults, $menuOptions);
		if (in_array('*', (array)$menuOptions['exclude'])) {
			return false;
		}
		return $menuOptions;
	}

/**
 * Creates the Exclusions for generating menus.
 *
 * @return void
 **/
	public function createExclusions() {
		$methods = array_merge(get_class_methods('Controller'), $this->excludeActions);
		$this->excludedMethods = array_map('strtolower', $methods);
	}
/**
 * Add a Menu Item.
 * Allows manual Insertion into the menu system.
 * If Added after constructMenu()  It will not be shown
 *
 * @param string $parent
 * @param array $menu
 * 		'Menu' Array
 * 			'title' => name
 * 			'url' => url array of menu, url strings are lame and won't work
 * 			'key' => unique name of this menu for parenting purposes.
 * 			'controller' => controller Name this action is from
 */
	public function addMenu($menu) {
		$defaults = array(
			'title' => null,
			'url' => null,
			'parent' => null,
			'id' => null,
			'weight' => 0,
		);
		$menu = array_merge($defaults, $menu);
		if (!$menu['id'] && isset($menu['url'])) {
			$menu['id'] = $this->_createId($menu['url']);
		}
		if (!$menu['title'] && isset($menu['url']['action'])) {
			$menu['title'] = Inflector::humanize($menu['url']['action']);
		}
		$this->rawMenus[] = $menu;
	}

/**
 * BeforeRender Callback.
 *
 */
	public function beforeRender() {
            if($this->Controller){
		$this->Controller->set('menu', $this->menu);
            }
	}

/**
 * Make a Unique Menu item key
 *
 * @param array $parts
 * @return string Unique key name
 */
	protected function _createId() {
		$parts = func_get_args();
		if (is_array($parts[0])) {
			$parts = $parts[0];
		}
		$key = Inflector::variable(implode('-', $parts));
		return $key;
	}

/**
 * Recursive function to construct Menu
 *
 * @param unknown_type $menu
 * @param unknown_type $parentId
 */
	protected function _formatMenu($menu) {
		$out = $idMap = array();
		foreach ($menu as $item) {
                    
			$item['children'] = array();
			$id = $item['id'];
			$parentId = $item['parent'];
                        
			if (isset($idMap[$id]['children'])) {
				$idMap[$id] = am($item, $idMap[$id]);
			} else {
				$idMap[$id] = am($item, array('children' => array()));
                                
			}
			if ($parentId) {
				$idMap[$parentId]['children'][] =& $idMap[$id];
			} else {
                                
				$out[] =& $idMap[$id];
                                
			}
		}
		usort($out, array(&$this, '_sortMenu'));
		return $out;
	}

/**
 * Sort the menu before returning it. Used with usort()
 *
 * @return int
 **/
	protected function _sortMenu($one, $two) {
		if ($one['weight'] == $two['weight']) {
			return 1;
		}
		return ($one['weight'] < $two['weight']) ? -1 : 1;
	}
/**
 * Merge the Cached menus with the Menus added in Controller::beforeFilter to ensure they are unique.
 *
 * @param array $cachedMenus
 * @return array Merged Menus
 */
	protected function _mergeMenuCache($cachedMenus) {
		$cacheCount = sizeOf($cachedMenus);
		$currentCount = sizeOf($this->rawMenus);
		$tmp = array();
		for ($i = 0; $i < $currentCount; $i++) {
			$exist = false;
			$addedMenu = $this->rawMenus[$i];
			for ($j =0; $j < $cacheCount; $j++) {
				$cachedItem = $cachedMenus[$j];
				if ($addedMenu['id'] == $cachedItem['id']) {
					$exist = true;
					break;
				}
			}
			if ($exist) {
				continue;
			}
			$tmp[] = $addedMenu;
		}
		if (!empty($tmp)) {
			$this->_rebuildMenus = true;
		}
		return array_merge($cachedMenus, $tmp);
	}




        //COOMPLEX MENU 
        //
        //
        //
        //
        //
        //completx menu buiding
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

                        if($this->Acl->check($this->Auth->user(),$arritem['Menu']['name']) == true){
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
                        if($this->Acl->check($this->Auth->user(),$arritem['Menu']['name']) == true){
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
}
?>