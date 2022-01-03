<?php

namespace MyHomeCore\Admin;


use MyHomeCore\Integrations\Yoast\Yoast_Init;

/**
 * Class Init
 * @package MyHomeCore\Admin
 */
class Init {

	/**
	 * @var Yoast_Init
	 */
	public $yoast;

	/**
	 * Init constructor.
	 */
	public function __construct() {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			add_action( 'init', array( $this, 'register_custom_fields' ) );
		}

		add_action( 'init', array( $this, 'clear_cache' ) );
		//		add_action( 'init', array( $this, 'compatibility_check' ) );
	}

	public function clear_cache() {
		$check_cache = intval( get_option( 'myhome_clear_cache_check', 10 ) );
		if ( $check_cache ) {
			$check_cache = -- $check_cache;
			update_option( 'myhome_clear_cache_check', $check_cache );
		} else {
			\MyHomeCore\My_Home_Core()->cache->reload_cache();
			$check_cache = 10;
			update_option( 'myhome_clear_cache_check', $check_cache );
		}
	}

	//	public function compatibility_check() {
	//		$version = get_option( 'myhome_version' );
	//
	//		if ( $version == Core::VERSION ) {
	//			return;
	//		}
	//
	//		$autoload_fix = get_option( 'myhome_autoload_fields', false );
	//		if ( ! $autoload_fix ) {
	//			global $wpdb;
	//			$wpdb->query( "
	//				UPDATE $wpdb->options
	//				SET `autoload` = 'yes'
	//				WHERE option_name LIKE '%myhome_%' AND option_name NOT LIKE '%_transient%' "
	//			);
	//			$wpdb->query( "
	//				UPDATE $wpdb->options
	//				SET `autoload` = 'yes'
	//				WHERE option_name LIKE '%options_%'
	//			" );
	//
	//			update_option( 'myhome_autoload_fields', true );
	//			wp_cache_delete( 'alloptions', 'options' );
	//		}
	//
	//		foreach ( Panel_Fields::get_selected_backend() as $field ) {
	//			if ( $field['type'] == Panel_Field::TYPE_LOCATION ) {
	//				if ( isset( $field['position'] ) ) {
	//					global $myhome_redux;
	//					$myhome_redux['mh-map-center_lat'] = $field['position']['lat'];
	//					$myhome_redux['mh-map-center_lng'] = $field['position']['lng'];
	//					update_option( 'myhome_redux', $myhome_redux );
	//				}
	//				break;
	//			}
	//		}
	//
	//		\MyHomeCore\My_Home_Core()->cache->reload_cache();
	//		update_option( 'myhome_version', Core::VERSION );
	//	}

	public function init() {
		if ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
			$this->yoast = new Yoast_Init();
		}
	}

	public function register_custom_fields() {
		acf_add_local_field_group(
			array(
				'key'      => 'myhome_testimonial',
				'title'    => '<span class="dashicons dashicons-admin-home"></span> '
				              . esc_html__( 'Testimonial author', 'myhome-core' ),
				'fields'   => array(
					array(
						'key'           => 'myhome_testimonial_occupation',
						'label'         => esc_html__( 'Author occupation', 'myhome-core' ),
						'name'          => 'testimonial_occupation',
						'type'          => 'text',
						'default_value' => '',
					)
				),
				'location' => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'testimonial',
						),
					),
				),
			)
		);

		acf_add_local_field_group(
			array(
				'key'      => 'myhome_client',
				'title'    => esc_html__( 'Client', 'myhome-core' ),
				'fields'   => array(
					array(
						'key'           => 'myhome_client_link',
						'label'         => esc_html__( 'Client link', 'myhome-core' ),
						'name'          => 'client_link',
						'type'          => 'text',
						'default_value' => '',
					)
				),
				'location' => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'client',
						),
					),
				),
			)
		);
	}

}