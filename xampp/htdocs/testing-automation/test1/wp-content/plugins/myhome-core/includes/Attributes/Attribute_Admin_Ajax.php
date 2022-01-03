<?php

namespace MyHomeCore\Attributes;


use MyHomeCore\Components\Listing\Search_Forms\Search_Forms_Admin_Ajax;

/**
 * Class Attribute_Admin_Ajax
 * @package MyHomeCore\Attributes
 */
class Attribute_Admin_Ajax {

	/**
	 * Attribute_Admin_Ajax constructor.
	 */
	public function __construct() {
		add_action( 'admin_post_attribute_update', array( $this, 'update' ) );
		add_action( 'admin_post_attribute_move', array( $this, 'move' ) );
		add_action( 'admin_post_attribute_delete', array( $this, 'delete' ) );
		add_action( 'admin_post_attribute_create', array( $this, 'create' ) );
	}

	/**
	 * @return bool|\WP_Error
	 */
	public function move() {
		$this->check_caps();

		if ( empty( $_POST['attributes'] ) || ! is_array( $_POST['attributes'] ) ) {
			return new \WP_Error( '400' );
		}

		$attributes = $_POST['attributes'];
		foreach ( $attributes as $form_order => $attr ) {
			$attribute = Attribute::get_by_id( $attr['id'] );
			if ($attribute instanceof Attribute) {
				$attribute->update( $attr );
			}
		}

		do_action( 'myhome_reload_cache' );

		return true;
	}

	public function delete() {
		$this->check_caps();
		$attribute_id = intval( $_POST['attribute_id'] );
		$attribute    = Attribute::get_by_id( $attribute_id );
		Search_Forms_Admin_Ajax::check( $attribute );
		Attribute::delete( $attribute_id );
		do_action( 'myhome_attribute_deleted_' . $attribute->get_type(), $attribute );
		do_action( 'myhome_attribute_deleted', $attribute );
		do_action( 'myhome_reload_cache' );
	}

	public function create() {
		$this->check_caps();

		global $wpdb;
		$table_name                   = $wpdb->prefix . 'myhome_attributes';
		$attribute_data               = $_POST['attribute'];
		$attribute_data['slug']       = sanitize_file_name( mb_strtolower( $attribute_data['name'], 'UTF-8' ) );
		$form_order                   = $wpdb->get_var( "SELECT form_order FROM $table_name ORDER BY form_order DESC LIMIT 1" );
		$attribute_data['form_order'] = intval( $form_order ) + 1;
		$attribute                    = Attribute::create( $attribute_data );
		do_action( 'myhome_attribute_created_' . $attribute->get_type(), $attribute );

		flush_rewrite_rules();

		do_action( 'myhome_reload_cache' );

		echo json_encode( array( 'attribute' => $attribute->get_data() ) );
	}

	/**
	 * @return \WP_Error
	 */
	private function check_caps() {
		check_ajax_referer( 'myhome_backend_panel_' . get_current_user_id() );

		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error( '401' );
		}
	}

	public function update() {
		$this->check_caps();

		if ( empty( $_POST['attribute'] ) || ! is_array( $_POST['attribute'] ) ) {
			throw new \ErrorException( 'Bad request', 400 );
		}

		$attribute_data = $_POST['attribute'];

		foreach ( Attribute_Factory::get_unfiltered() as $attribute ) {
			if ( $attribute->get_ID() == $attribute_data['id'] ) {
				$attribute->update( $attribute_data );
				do_action( 'myhome_reload_cache' );
				break;
			}
		}
	}

}