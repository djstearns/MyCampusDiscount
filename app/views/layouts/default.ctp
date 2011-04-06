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
                    <ul class="nav main">
                        <li>
                                            <a href="/">Home</a>
                        </li>
                        <li>
                            <a href="16/">Applications</a>
                                <ul>
                                    <li>
                                        <a href="/taxrecon/">Tax Reconciliation Home</a>
                                    </li>
                                </ul>

                        </li>
                        <li>
                                <a href="/support/">Support</a>
                        </li>

                        <li class="secondary">
                              <?php //echo $user_info;
                              if(!empty($user_info)){
                                  echo 	'<a href="/taxrecon/users/logout" title="Logout">Logout</a>';
                              }else{
                                 echo 	'<a href="/taxrecon/users/login" title="Logout">Login</a>';
                                 echo 	'<a href="/taxrecon/users/register" title="Logout">Register</a>';
                                 //echo $form->create('User', array('action' => 'login'));
                                 //    echo $form->input('username');
                                 //    echo $form->input('password');
                                     //echo $this->Form->input('remember_me', array('type' => 'checkbox', 'checked' => 'checked'));  

                                 //   echo $form->submit('Login');
                                 //echo $form->end();
                                  
                              }
                              ?>
                        </li>
                    </ul>

                </div>
                <div class="clear"></div>
                <div class="grid_16">
                            <h1 id="branding">
                                    <a href="/">Tax Reconciliation Database</a>
                            </h1>

               </div>
               <div class="clear"></div>
               <div  style="">
                    <?php echo $hgrid->RenderTopHorizontal($menu); ?>
               </div>
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
