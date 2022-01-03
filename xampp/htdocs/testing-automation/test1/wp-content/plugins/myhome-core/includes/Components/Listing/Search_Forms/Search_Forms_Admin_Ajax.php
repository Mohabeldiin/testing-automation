<?php

namespace MyHomeCore\Components\Listing\Search_Forms;

use MyHomeCore\Attributes\Attribute;


/**
 * Class Search_Forms_Admin_Ajax
 * @package MyHomeCore\Components\Listing\Search_Forms
 */
class Search_Forms_Admin_Ajax {

	/**
	 * Search_Forms_Admin_Ajax constructor.
	 */
	public function __construct() {
		add_action( 'admin_post_search_form_create', array( $this, 'create' ) );
		add_action( 'admin_post_search_form_update', array( $this, 'update' ) );
		add_action( 'admin_post_search_form_delete', array( $this, 'delete' ) );
		add_action( 'myhome_attribute_deleted', array( $this, 'check' ) );
	}

	/**
	 * @param Attribute $attribute
	 */
	public static function check( $attribute ) {
		if ( ! $attribute instanceof Attribute ) {
			return;
		}

		$search_forms = Search_Form::get_all_search_forms();
		foreach ( $search_forms as $search_form ) {
			$elements = array();

			foreach ( $search_form->get_selected_elements() as $key => $element ) {
				if ( $attribute->get_ID() != intval( $element['id'] ) ) {
					$elements[] = $element['id'];
				}
			}

			$search_form->update_elements( $elements );
		}
	}

	public function create() {
		$this->check_caps();

		if ( empty( $_POST['label'] ) ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$label       = sanitize_text_field( $_POST['label'] );
		$search_form = Search_Form::create( $label );

		echo json_encode(
			array(
				'success'     => true,
				'search_form' => $search_form->get_data()
			)
		);
	}

	public function update() {
		$this->check_caps();

		if ( empty( $_POST['search_form'] ) ) {
			echo json_encode( array( 'success' => false ) );
		}

		$search_form_data = $_POST['search_form'];
		Search_Form::update( $search_form_data );

		echo json_encode( array( 'success' => true ) );
	}

	public function delete() {
		$this->check_caps();

		if ( empty( $_POST['key'] ) ) {
			echo json_encode( array( 'success' => false ) );

			return;
		}

		$key = sanitize_text_field( $_POST['key'] );
		Search_Form::delete( $key );

		echo json_encode( array( 'success' => true ) );
	}

	public function check_caps() {
		check_ajax_referer( 'myhome_backend_panel_' . get_current_user_id() );

		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error( '401' );
		}
	}

}