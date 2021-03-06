<?php
/* -------------------------------------------------------------------
 * The settings below have to be loaded to make the acl plugin work.
 * -------------------------------------------------------------------
 *
 * See how to include these settings in the README file
 */

/*
 * The model name used for the user role (typically 'Role' or 'Group')
 */
Configure :: write('acl.aro.role.model', 'Group');

/*
 * The model name used for the user (typically 'User')
 */
Configure :: write('acl.aro.user.model', 'User');

/*
 * The name of the database field that can be used to display the role name
 */
Configure :: write('acl.aro.role.display_field', 'name');

/*
 * You can add here role id(s) that are always allowed to access the ACL plugin (by bypassing the ACL check)
 * (This may prevent a user from being rejected from the ACL plugin after a ACL permission update)
 */
Configure :: write('acl.role.access_plugin_role_ids', array());

/*
 * You can add here users id(s) that are always allowed to access the ACL plugin (by bypassing the ACL check)
 * (This may prevent a user from being rejected from the ACL plugin after a ACL permission update)
 */
Configure :: write('acl.role.access_plugin_user_ids', array(1));

/*
 * The users table field used as username in the views
 * It may be a table field or a SQL expression such as "CONCAT(User.lastname, ' ', User.firstname)" for MySQL or "User.lastname||' '||User.firstname" for PostgreSQL
 */
Configure :: write('acl.user.display_name', "username");

/*
 * Indicates wether the presence of the Acl behavior in the user and role models must be verified when the ACL plugin is accessed
 */
Configure :: write('acl.check_act_as_requester', false);

/*
 * Add the ACL plugin 'locale' folder to your application locales' folders
 */
App :: build(array('locales' => APP . 'plugins' . DS . 'acl' . DS . 'locale'));
?>