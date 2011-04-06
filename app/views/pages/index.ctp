<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Fluid 960 Grid System | 16-column Grid</title>
		<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/text.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/grid.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />

		<link rel="stylesheet" type="text/css" href="css/nav.css" media="screen" />
		<!--[if IE 6]><link rel="stylesheet" type="text/css" href="css/ie6.css" media="screen" /><![endif]-->
		<!--[if IE 7]><link rel="stylesheet" type="text/css" href="css/ie.css" media="screen" /><![endif]-->
	</head>
	<body>
 <div class="grid_16">
                    <div class="box">
					<h2>
						<a href="#" id="toggle-login-forms">Login Forms</a>

					</h2>
					<div class="block" id="login-forms">
						    <?php echo $form->create('User', array('action' => 'login'));?>
                                                    <?php echo $form->input('username');?>
                                                    <?php echo $form->input('password');?>
                                                    

                                                    <?php echo $form->submit('Login');?>
                                                    <?php echo $form->end(); ?>
						<form action="">
							<fieldset>
								<legend>Register</legend>
								<p>If you do not already have an account, please create a new account to register.</p>
                                                                <a href="/taxrecon/users/register"><input type="submit" value="Create Account" /></a>
							</fieldset>
						</form>

					</div>
                   </div>
             </div>
			
			<div class="clear"></div>
			<div class="grid_16" id="site_info">
				<div class="box">

					<p>Fluid 960 Grid System, created by <a href="http://www.domain7.com/WhoWeAre/StephenBau.html">Stephen Bau</a>, based on the <a href="http://960.gs/">960 Grid System</a> by <a href="http://sonspring.com/journal/960-grid-system">Nathan Smith</a>. Released under the
		<a href="licenses/GPL_license.txt">GPL</a> / <a href="licenses/MIT_license.txt">MIT</a> <a href="README.txt">Licenses</a>.</p>

				</div>
			</div>
			<div class="clear"></div>
		</div>
		<script type="text/javascript" src="js/mootools-1.2.1-core.js"></script>
		<script type="text/javascript" src="js/mootools-1.2-more.js"></script>
		<script type="text/javascript" src="js/mootools-fluid16-autoselect.js"></script>

	</body>
</html>