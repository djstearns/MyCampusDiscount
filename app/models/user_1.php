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
                                                                            'message' => 'Password cannot be blank!'
                                                                         )
                                                    ),
                                  'passwordverify' => array(
                                                    'passrule1' => array(
                                                                            'rule' => 'notEmpty',
                                                                            'required' => true,
                                                                            'message' => 'Password cannot be blank!'
                                                                         ),
                                                    'passrule2' => array(
                                                                            'rule' => array('checkpasswords'),
                                                                            'message' => 'Passwords do not match!'
                                                                         ),
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
		)
	);


        function checkpasswords(){
            
            if ($this->data['User']['password'] == $this->data['User']['passwordverify']){
                return true;
            }else{
                return false;
            }
        }

        function hashPasswords($data, $enforce=false) {
            //debugger::dump($this->data);
            if($enforce && isset($this->data[$this->alias]['password'])) {
                if(!empty($this->data[$this->alias]['password'])) {
                    $this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password'], null, true);
                }
            }
            return $data;
        }

        function beforeSave() {
            //debugger::dump($this->data);
            $this->hashPasswords(null, true);
            
            return true;
        }

}

class edituserform extends User {

}

class adduserform extends User {

}
?>