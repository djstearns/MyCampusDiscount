<?php
/**
 * Dynamic menu  helper library.
 * Can be used with CSS from http://www.tanfa.co.uk/css/examples/menu/
 *
 * Methods to render dynamic menu .
 */
class HgridHelper extends AppHelper {
    var $helpers = array('Html');
    function renderTopHorizontal($menu){
        //debugger::dump($menu[0]);
        return $this->render($menu);
    }
    function render($menu = null) {
		$out = '';
		//debugger::dump($here2);
                foreach($menu as $caption => $config) {
                    //debugger::dump($caption);
                        if(is_array($config)){
                            //check if admin
                            if($config['url']['admin']==false){
                                $out = $out.'<li><a href="'.$this->webroot.$config['url']['controller'].'/'.$config['url']['action'].'"><h2>'.$config['title'].'</h2></a>'.$this->Html->nestedList($this->parse($config, $menu)).'</li>';
                            }else{
								/*
								foreach($config['url'] as $admindesc => $urlitem){
									if($admindesc != 'action' && $admindesc != 'controller'){
										//debugger::dump($urlitem.'test');
                                		$out = $out.'<li><a href="'.$this->webroot.$admindesc.'/'.$config['url']['controller'].'/'.$config['url']['action'].'"><h2>'.$config['title'].'</h2></a>'.$this->Html->nestedList($this->parse($config, $menu)).'</li>';
										
										
									
									}
								}
								*/
								 $out = $out.'<li><a href="'.$this->webroot.$config['url']['controller'].'/'.$config['url']['action'].'"><h2>'.$config['title'].'</h2></a>'.$this->Html->nestedList($this->parse($config, $menu)).'</li>';
                            }
                            

                            //$out = $out.'<li><a href="'.$this->webroot.$caption.'"><h2>'.$caption.'</h2></a>'.
                            //$this->Html->nestedList($this->parse($config)).'</li>';
                        }
                    
                }
		$out = '<ul>'.$out.'</ul>';
		$out = $this->Html->div('menu1', $out);
                return $this->Html->div('grid_16',$out);
                
              
	}

	/**
	 * Transforms configuration array in to array of hyperlinks recursively.
	  * Returns arraly of list items.
	 */
	function parse($config, $menu) {
            $here = Router::url(substr($this->here, strlen($this->webroot)-1));
		
		foreach($config['children'] as $caption => $link) {
			if (!empty($link['children'])) {
                            //check for Path array
                            $out = $this->parse($link['children'], $menu);
                                
                            
			}else{
				if (Router::url($link) != $here) {
     
                                        $out[$caption] = $this->Html->link($link['title'], '../'.$link['url']['controller'].'/'.$link['url']['action']);
				}
				else {
					$out[$caption] = $this->Html->div('current', $caption);
				}
			}

		}
		return $out;
	}



        //render complex
        	function renderC($menu = null) {
            //debugger::dump($menu);
		$out = '';
                $here2 = Router::url(substr($this->here, strlen($this->webroot)-1));
		//debugger::dump($here2);
                foreach($menu as $caption => $config) {
                    if($caption != 'Paths'){
                        if(is_array($config)){
                             //test for http://
                            $tUrl = $this->evalLink($menu['Paths'][$caption]);
                            $out = $out.'<li><a href="'.$this->webroot.$tUrl.'"><h2>'.$caption.'</h2></a>'.
                            $this->Html->nestedList($this->parseC($config, $menu)).'</li>';

                            //$out = $out.'<li><a href="'.$this->webroot.$caption.'"><h2>'.$caption.'</h2></a>'.
                            //$this->Html->nestedList($this->parse($config)).'</li>';
                        }
                    }
                }
		$out = '<ul>'.$out.'</ul>';
		$out = $this->Html->div('menu1', $out);
		//debugger::dump($out);
                return $out;

	}

	/**
	 * Transforms configuration array in to array of hyperlinks recursively.
	  * Returns arraly of list items.
	 */
	function parseC($config, $menu) {
		$out = array();
		$here = Router::url(substr($this->here, strlen($this->webroot)-1));

		foreach($config as $caption => $link) {
			if (is_array($link)) {
                            //check for Path array
                            if($caption != 'Paths'){
                                //$htmllnk = $this->Html->link($caption, $link);
				$out[$this->Html->div('parent1', $caption )] = $this->parseC($link, $menu);
                            }
			}else{
				if (Router::url($link) != $here) {
                                    $link = $this->evalLink($link);

                                    if(strpos($link, 'http') === false ){
                                        $out[$caption] = $this->Html->link($caption, '../'.$link);
                                    }else{
                                        $out[$caption] = $this->Html->link($caption, $link);
                                        //debugger::dump($this->evalLink($link));
                                    }
				}
				else {
					$out[$caption] = $this->Html->div('current', $caption);
				}
			}

		}
		return $out;
	}

        function evalLink($elnk){
            if(strpos($elnk, 'http') === false ){
                //no http
                if(strpos($elnk, 'www') === false){
                    $tUrl = $elnk;
                }else{
                    $tUrl = 'http://'.$elnk;
                }
            }else{
                //http included.
                $tUrl = $elnk;
            }
            return $tUrl;
        }

        function getThePath($id){
            /*
             This function is able to parse the menu tree and return the proper path with "/"
             */
            //instantiate the menu model
            $MenuIns = ClassRegistry::init('Menu');
            //check if manual url is
            $patharray = $MenuIns->getpath($id);
            $finalpath = '';

            //check for manual URL

            foreach($patharray as $fork => $forkarray){
                if($forkarray['Menu']['name'] != 'controllers'){
                    $finalpath = $finalpath.strtolower($forkarray['Menu']['name']).'/';
                }
            }
            return $finalpath;
        }

	
}
?>