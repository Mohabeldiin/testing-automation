<?php
/*
Plugin Name: MyHome IDX Broker
Description: IDX Broker integration. This plugin is currently available for United States, Canada, Bahamas, Mexico and Jamaica only.
Version: 2.1.36
Plugin URI: https://myhometheme.net
*/

namespace MyHomeIDXBroker {

	define( 'MY_HOME_IDX_PATH', basename( dirname( __FILE__ ) ) );
	define( 'MY_HOME_IDX_VIEWS', __DIR__ . '/pages/' );

	spl_autoload_register(
		function ( $class_name ) {
			$path       = plugin_dir_path( __FILE__ ) . 'MyHomeIDXBroker/';
			$class_name = str_replace( 'MyHomeIDXBroker\\', '', $class_name );
			$class_name = str_replace( '\\', '/', $class_name );
			$class      = $path . $class_name . '.php';

			if ( file_exists( $class ) ) {
				return require_once( $class );
			}
		}
	);

	function My_Home_IDX_Broker() {
		return IDX::get_instance();
	}

	$my_home_idx = My_Home_IDX_Broker();
	add_action( 'plugins_loaded', array( $my_home_idx, 'init' ) );
}