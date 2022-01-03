<?php

namespace MyHomeCore\Frontend_Panel;

use MyHomeCore\Attributes\Attribute;
use MyHomeCore\Estates\Elements\Estate_Element;


/**
 * Class Panel_Settings
 * @package MyHomeCore\Panel
 */
class Panel_Settings {

	/**
	 * Panel_Settings constructor.
	 */
	public function __construct() {
		add_action( 'admin_post_myhome_panel_settings_save', array( $this, 'save' ) );
		add_action( 'myhome_attribute_deleted', array( $this, 'remove_attribute' ) );
		add_action( 'redux/options/myhome_redux/reset', array( $this, 'check_fields' ) );
		add_action( 'redux/options/myhome_redux/saved', array( $this, 'check_fields' ) );
	}

	public function check_fields() {
		$remove_types   = array();
		$check_elements = array(
			Estate_Element::ATTACHMENTS        => 'mh-estate_attachments',
			Estate_Element::RELATED_PROPERTIES => 'mh-estate_related-properties',
			Estate_Element::PLANS              => 'mh-estate_plans',
			Estate_Element::VIRTUAL_TOUR       => 'mh-estate_virtual_tour',
			Estate_Element::VIDEO              => 'mh-estate_video'
		);

		foreach ( $check_elements as $type => $element ) {
			if ( empty( \MyHomeCore\My_Home_Core()->settings->props[ $element ] ) ) {
				$remove_types[] = $type;
			}
		}

		$fields = Panel_Fields::get_selected();
		$fields = array_filter( $fields, function ( $field ) use ( $remove_types ) {
			return ! in_array( $field['type'], $remove_types );
		} );

		if ( ! is_array( $fields ) ) {
			$fields = array();
		}
		update_option( Panel_Fields::OPTIONS_KEY, $fields );
	}

	public function save() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}
		check_ajax_referer( 'myhome_backend_panel_' . get_current_user_id() );

		if ( isset( $_POST['fields'] ) && is_array( $_POST['fields'] ) ) {
			update_option( 'myhome_panel_fields', $_POST['fields'] );
		}
	}

	public function remove_attribute( Attribute $attribute ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		$fields = get_option( Panel_Fields::OPTIONS_KEY, Panel_Fields::get() );
		foreach ( $fields as $field_key => $field ) {
			if ( intval( $field['id'] ) == $attribute->get_ID() ) {
				unset( $fields[ $field_key ] );
				break;
			}
		}
		update_option( 'myhome_panel_fields', $fields );
	}

	/**
	 * @return bool
	 */
	public static function is_moderation_enabled() {
		$enabled = \MyHomeCore\My_Home_Core()->settings->get( 'agent-moderation' );

		return ! empty( $enabled );
	}

	/**
	 * @return bool
	 */
	public static function is_payment_enabled() {
		$enabled = \MyHomeCore\My_Home_Core()->settings->get( 'payment' );

		return ! empty( $enabled );
	}

	/**
	 * @return bool
	 */
	public static function is_expire_enabled() {
		$enabled = \MyHomeCore\My_Home_Core()->settings->get( 'frontend-properties_expire' );

		return ! empty( $enabled );
	}

}