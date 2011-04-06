<h1>Edit Your Article</h1>
<?php
echo $this->Form->create('edituserform', array('url' => '/users/manage'));
echo $this->Form->input('id');
		echo $this->Form->input('username');
		echo $this->Form->input('firstName');
		echo $this->Form->input('lastName');
		echo $this->Form->input('email');
		echo $this->Form->input('ext');
		echo $this->Form->input('password');
		echo $this->Form->input('group_id');
		echo $this->Form->input('creator_id');
		echo $this->Form->input('modifier_id');
		echo $this->Form->input('lastlogin');
echo $this->Form->end('Edit');
?>

<h1>Or, would you like to add another one?</h1>
<?php
echo $this->Form->create('adduserform', array('url' => '/users/manage'));
echo $this->Form->input('username');
		echo $this->Form->input('firstName');
		echo $this->Form->input('lastName');
		echo $this->Form->input('email');
		echo $this->Form->input('ext');
		echo $this->Form->input('password');
		echo $this->Form->input('group_id');
		echo $this->Form->input('creator_id');
		echo $this->Form->input('modifier_id');
		echo $this->Form->input('lastlogin');
echo $this->Form->end('Add');
?>