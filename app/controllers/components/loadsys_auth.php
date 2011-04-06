<?php

uses('controller/components/auth');

class LoadsysAuthComponent extends AuthComponent {

        function initialize(&$controller) {
                ClassRegistry::addObject('LoadsysAuthComponent', $this);
                parent::initialize($controller);
        }

}

class LoadsysAuth {

        function &getInstance() {
                static $instance = array();
                if (!$instance) {
                        $instance[0] =& ClassRegistry::getObject('LoadsysAuthComponent');
                }
                return $instance[0];
        }

        static function getUser() {
                $_this =& LoadsysAuth::getInstance();
                return $_this->user();
        }

        static function getUserId() {
                $_this =& LoadsysAuth::getInstance();
                $user = $_this->user();
                return Set::extract($user, 'User.id');
        }

        static function getUserGroupId() {
                $_this =& LoadsysAuth::getInstance();
                $user = $_this->user();
                return Set::extract($user, 'User.user_group_id');
        }

        static function getUserNumPages() {
                $_this =& LoadsysAuth::getInstance();
                $user = $_this->user();
                return Set::extract($user, 'User.numreccs');
        }
}
?>
