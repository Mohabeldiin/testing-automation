<?php

namespace MyHomeCore\Frontend_Panel;


/**
 * Class Submit_Property_Settings
 * @package MyHomeCore\Panel
 */
class Submit_Property_Settings {

	const STEPS_OPTION = 'myhome_submit_property_steps';

	/**
	 * Submit_Property_Settings constructor.
	 */
	public function __construct() {
		if ( current_user_can( 'manage_options' ) ) {
			add_action( 'admin_post_myhome_submit_property_update_steps', array( $this, 'update_steps' ) );
		}
	}

	public function update_steps() {
		if ( isset( $_POST['steps'] ) && is_array( $_POST['steps'] ) ) {
			update_option( Submit_Property_Settings::STEPS_OPTION, $_POST['steps'] );
		}
	}

	/**
	 * @param bool $is_backend
	 *
	 * @return array
	 */
	public static function get_steps( $is_backend = false ) {
		$steps = get_option( Submit_Property_Settings::STEPS_OPTION );
		if ( empty( $steps ) || ! is_array( $steps ) ) {
			return array();
		}

		if ( $is_backend ) {
			foreach ( $steps as $key => $step ) {
				$steps[$key]['name'] = apply_filters( 'wpml_translate_single_string', $step['name'], 'myhome-core', 'Step name ' . $step['name'] );

				if ( ! empty( $step['fields'] ) ) {
					$steps[ $key ]['fields'] = Panel_Fields::get_selected_backend( $step['fields'] );
				}
			}
		} else {
			foreach ( $steps as $key => $step ) {
				$steps[$key]['name'] = apply_filters( 'wpml_translate_single_string', $step['name'], 'myhome-core', 'Step name ' . $step['name'] );

				if ( ! empty( $step['fields'] ) ) {
					$steps[ $key ]['fields'] = Panel_Fields::get_selected( $step['fields'] );
				}
			}
		}

		return $steps;
	}

	/**
	 * @return array
	 */
	public static function get_available_fields() {
		$selected_fields = array();

		foreach ( Submit_Property_Settings::get_steps() as $step ) {
			if ( isset( $step['fields'] ) && is_array( $step['fields'] ) ) {
				$selected_fields = array_merge( $selected_fields, $step['fields'] );
			}
		}

		return Panel_Fields::get_available( $selected_fields );
	}

	/**
	 * @return array
	 */
	public static function get_selected_fields() {
		$selected_fields = array();

		foreach ( Submit_Property_Settings::get_steps() as $step ) {
			if ( isset( $step['fields'] ) && is_array( $step['fields'] ) ) {
				$selected_fields = array_merge( $selected_fields, $step['fields'] );
			}
		}

		return $selected_fields;
	}

}