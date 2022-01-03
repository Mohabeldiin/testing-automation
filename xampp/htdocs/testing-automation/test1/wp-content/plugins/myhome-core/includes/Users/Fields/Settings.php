<?php

namespace MyHomeCore\Users\Fields;


/**
 * Class Settings
 * @package MyHomeCore\Users\Fields
 */
class Settings {

	const USER_FIELDS_OPTION = 'myhome_user_fields';

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		add_action( 'admin_post_myhome_agent_fields_save', array( $this, 'save' ) );
		add_action( 'admin_post_myhome_agent_fields_create', array( $this, 'create' ) );
	}

	public function create() {
		if ( ! $this->check_caps() ) {
			return;
		}

		if ( ! isset( $_POST['field'] ) || ! is_array( $_POST['field'] ) ) {
			return;
		}

		$field         = $_POST['field'];
		$field['slug'] = str_replace( '.', '', sanitize_file_name( mb_strtolower( $_POST['field']['name'], 'UTF-8' ) ) );
		$fields        = self::get_fields();
		$fields[]      = $field;

		update_option( self::USER_FIELDS_OPTION, $fields );
		echo json_encode( array( 'field' => $field ) );
	}

	public function save() {
		if ( ! $this->check_caps() ) {
			return;
		}

		if ( ! isset( $_POST['fields'] ) && ! is_array( $_POST['fields'] ) ) {
			update_option( self::USER_FIELDS_OPTION, array() );

			return;
		}

		update_option( self::USER_FIELDS_OPTION, $_POST['fields'] );
	}

	private function check_caps() {
		if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @return array
	 */
	public static function get_fields() {
		$fields = get_option( self::USER_FIELDS_OPTION );

		if ( empty( $fields ) || ! is_array( $fields ) ) {
			return array();
		}

		foreach ( $fields as $field ) {
			$field['is_required'] = filter_var( $field['is_required'], FILTER_VALIDATE_BOOLEAN );
			$field['is_link']     = filter_var( $field['is_link'], FILTER_VALIDATE_BOOLEAN );
			if ( isset( $fields['use_for_registration'] ) ) {
				$field['use_for_registration'] = filter_var( $field['use_for_registration'], FILTER_VALIDATE_BOOLEAN );
			} else {
				$field['use_for_registration'] = false;
			}
		}

		return $fields;
	}

	/**
	 * @return array
	 */
	public static function get_fields_for_registration() {
		$fields = [];
		foreach ( Settings::get_fields() as $field ) {
			if ( isset( $field['use_for_registration'] ) && $field['use_for_registration'] ) {
				$fields[] = $field;
			}
		}

		return $fields;
	}

}