<div class="login">
<h2>Login</h2>
    <?php echo $form->create('User', array('action' => 'login'));?>
        <?php echo $form->input('username');?>
        <?php echo $form->input('password');?>
        <?php //echo $this->Form->input('remember_me', array('type' => 'checkbox', 'checked' => 'checked'));  ?>

        <?php echo $form->submit('Login');?>
    <?php echo $form->end(); ?>
</div>