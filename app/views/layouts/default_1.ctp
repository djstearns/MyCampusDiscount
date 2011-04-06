<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $title_for_layout; ?></title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css(array('reset', 'text', 'grid', 'layout', 'nav','menu','/js/ext-2.0.1/resources/css/ext-custom.css' ));
		echo '<!--[if IE 6]>'.$this->Html->css('ie6').'<![endif]-->';
		echo '<!--[if IE 7]>'.$this->Html->css('ie').'<![endif]-->';
		echo $this->Html->script(array('jquery-1.3.2.min.js', 'jquery-ui.js','mootools.js', 'jquery-fluid16.js','ext-2.0.1/ext-custom.js'));
		echo $scripts_for_layout;
               
	?>

<!--[if IE]>
<style type="text/css" media="screen">
body {
behavior: url(http://192.168.112.52:8080/templatedev/app/webroot/css/csshover.htc);
font-size: 100%;
}

}
</style>
<![endif]-->
</head>
<body>
    
	<div class="container_16">			
		<div class="grid_16">
			<h1 id="branding">
				<a href="/">Tax Reconciliation Database</a>
			</h1>
                    
		</div>
                        
		<div class="clear"></div>
		<div class="grid_16">
			 <?php

                         //echo $menu->render($session->read('Menu.main'));

                     ?>
                    
		</div>
                <div class="grid_16">
			 <?php

                         //echo $menu->render($session->read('Menu.top'));

                     ?>

		</div>
             
                <?php echo $hgrid->RenderTopHorizontal($menu); ?>
		<div class="clear" style="height: 10px; width: 100%;"></div>
                <div id="content">

			

                           <?php echo $flash->show(); ?>
                    <?php echo $this->Session->flash(); ?>
			<?php echo $content_for_layout; ?>
                </div>
		<div class="clear"></div>
	</div>

	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
