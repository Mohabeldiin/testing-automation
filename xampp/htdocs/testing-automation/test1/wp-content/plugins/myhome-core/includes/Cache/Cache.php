<?php

namespace MyHomeCore\Cache;


use MyHomeCore\Attributes\Attribute_Factory;

/**
 * Class Cache
 * @package MyHomeCore\Cache
 */
class Cache {

	/**
	 * Cache constructor.
	 */
	public function __construct() {
		add_action( 'myhome_reload_cache', array( $this, 'reload_cache' ) );
		add_action( 'save_post', array( $this, 'reload_cache' ) );
		add_action( 'edit_terms', array( $this, 'reload_cache' ) );
		add_action( 'redux/options/myhome_redux/saved', array( $this, 'reload_cache' ) );
		add_action( 'redux/options/myhome_redux/reset', array( $this, 'reload_cache' ) );
		add_action( 'redux/myhome_redux/panel/before', array( $this, 'clear_cache_button' ) );
		add_action( 'admin_post_clear_cache', array( $this, 'clear_cache_button_action' ) );
	}

	public function clear_cache_button_action() {
		$this->reload_cache();
		if ( wp_redirect( admin_url( 'admin.php?page=' . wp_get_theme()->get( 'Name' ) ) ) ) {
			exit;
		}
	}

	public function clear_cache_button() {
		ob_start();
		?>

		<?php
		echo ob_get_clean();
	}

	public function reload_cache() {
		global $wpdb;
		$query = "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%myhome_cache%'";
		$wpdb->query( $query );

		set_transient( 'myhome_flush_rewrite_rules', true, WEEK_IN_SECONDS );
		// load fresh cache
		Attribute_Factory::get();

		$is_expire = \MyHomeCore\My_Home_Core()->settings->get( 'frontend-properties_expire' );
		if ( empty( intval( $is_expire ) ) ) {
			global $wpdb;
			$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key = 'myhome_property_expire' OR meta_key = 'is_expired' " );
		}
	}

}