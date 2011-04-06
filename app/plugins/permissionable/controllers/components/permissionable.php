<?php

/**
 * PermissionableComponent
 *
 * Sets user info for PermissionableBehavior
 *
 * @package     permissionable
 * @subpackage  permissionable.controllers.components
 * @see         PermissionableBehavior
 * @uses		Component
 * @author      Joshua McNeese <jmcneese@gmail.com>
 * @license		Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 * @copyright	Copyright (c) 2009,2010 Joshua M. McNeese, Curtis J. Beeson
 */
final class PermissionableComponent extends Object {

	/**
	 * @author  Joshua McNeese <jmcneese@gmail.com>
	 * @param   object	$controller
	 * @return	void
	 */
    public function initialize(&$controller) {

		App::import('Lib', 'Permissionable.Permissionable');

		/**
		 * if the root user or root group are other than '1',
		 * set them here here, with:
		 *
		 * Permissionable::setRootUserId(2);
		 * Permissionable::setRootGroupId(2);
		 */
                $user_id = $controller->LoadsysAuth->user('id');
                Permissionable::setUserId($user_id);
                $group_id = $controller->LoadsysAuth->user('group_id');
                Permissionable::setGroupId($group_id);
                Permissionable::setGroupIds(array($group_id));
               
		/**
		 * set user info here, with:
		 *
		 * Permissionable::setUserId(2);
		 * Permissionable::setGroupId(2);
		 * Permissionable::setGroupIds(array(3,4));
		 */

    }

}

?>