<?php

namespace MyHomeCore\Users;


/**
 * Class User_Permissions
 * @package MyHomeCore\Users
 */
class User_Permissions {

	public function __construct() {
//		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
	}

	public function add_menu_page() {
		add_users_page( esc_html__( 'Roles', 'myhome-core' ), 'Roles', 'manage_options', 'myhome-roles', array(
			$this,
			'menu_page'
		) );
	}

	public function menu_page() {
		require MYHOME_CORE_VIEWS . 'user-roles-admin-page.php';
	}

}