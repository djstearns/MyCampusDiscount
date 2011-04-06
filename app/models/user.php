<?php
class User extends AppModel {
		var $name = 'User';

	var $displayField = 'username';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
        var $actsAs = array('Acl' => 'requester');

        

        var $validate = array(
                                'username' =>array(
                                                    'unique' => array(
                                                                            'rule' => 'isUnique',
                                                                            'message' => 'Please choose a different user name.',
                                                                            'required' => true
                                                                         ),

                                                   ),
                                  'email' => array(
                                                   'unique' => array(
                                                                            'rule' => 'isUnique',
                                                                            'message' => 'Email already exists!',
                                                                            'required' => true,
                                                                            'last' => true
                                                                         ),
                                                    'emailrule2' => array(
                                                                            'rule' => array('email', true),
                                                                            'message' => 'Please use a valid email address!'
                                                                        )
                                                      ),
                                  'password' => array(
                                                    'passrule1' => array(
                                                                            'rule' => 'notEmpty',
                                                                            'required' => true,
                                                                            'message' => 'Password cannot be blank!',
                                                                            'on' => 'create'
                                                                         )
                                                    ),
                                  'passwordverify' => array(
                                                    'compare' => array(
                                                                       
                                                                        'rule' => array('password_match', 'password', true),
                                                                        'message' => 'The password you entered does not match',

                                                                       )
                                                            ),

                                  'group_id' => array(
                                                    'grouprule1' => array(
                                                                            'rule' => 'notEmpty',
                                                                            'required' => true,
                                                                            'message' => 'User group cannot be blank!'

                                                                         )
                                                    ),
                                  'ext' => array(
                                                    'extrule1' => array(
                                                                            'rule' => 'notEmpty',
                                                                            'required' => true,
                                                                            'message' => 'Extension cannot be blank!'
                                                                         ),
                                                     'estrule2' => array(
                                                                            'rule' => 'numeric',
                                                                            'message' => 'Extension must be numeric'
                                                                        )
                                                    )
                                );






        function parentNode() {
            if (!$this->id && empty($this->data)) {
                return null;
            }
            $data = $this->data;
            if (empty($this->data)) {
                $data = $this->read();
            }
            if (empty($data['User']['group_id'])) {
                return null;
            } else {
                return array('Group' => array('id' => $data['User']['group_id']));
            }
        }

	var $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Dept' => array(
			'className' => 'Dept',
			'foreignKey' => 'dept_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'Upload' => array(
			'className' => 'Upload',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Userticket' => array(
			'className' => 'Userticket',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

         function password_match($data, $password_field, $hashed = true) {
            //debugger::dump('beforesaveP_M');
            if(isset($this->data[$this->alias][$password_field])){
                $password = $this->data[$this->alias][$password_field];
                $keys = array_keys($data);
                //debugger::dump($data[$keys[0]]);

                $password_confirm = $hashed ? Security::hash($data[$keys[0]], null, true) : $data[$keys[0]];

                return $password === $password_confirm;//$data[$keys[0]];//$password_confirm;
            }else{
                return true;
            }
        }

           // Validate the password & password_confirmation fields



           function hashPasswords($data, $do_it = false) {
            
       // Don't change the password if the form data password is blank
           if ( isset($data[$this->alias]['password']) &&
                empty($data[$this->alias]['password']) ) {
               unset($data[$this->alias]['password']);
           }

           // Only just before saving, hash the passwords
           if ($do_it) {
              
               if ( isset($data[$this->alias]['password']) &&
                    ! empty($data[$this->alias]['password']) ) {

                   // Watch this hashing operation.  If the password() function
                   // in Auth component changes for any reason, this line
                   // will need to change accordingly.
                   $data[$this->alias]['password'] =
                       Security::hash($data[$this->alias]['password'], null, true);
               }
           }

           return $data;
       }


        function beforeSave() {
        //debugger::dump('beforesavemod');
             //$this->data = $this->hashPasswords($this->data, true);

            return true;
        }

        function updateLastLogin($id){
            if($id){
                $this->query('UPDATE users SET lastlogin = Date WHERE users.id = '.$id);
            }
        }

}

class edituserform extends User {

}

class adduserform extends User {

}


?>