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
        return $this->render($menu);
    }
    function render($menu = null) {
		$out = '';
		//debugger::dump($here2);
                foreach($menu as $caption => $config) {  
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



        
	
}
?>