<?php

namespace MyHomeCore\Panel;

use MyHomeCore\Estates\Estate;
use MyHomeCore\Users\User;


/**
 * Class Panel_Notifications
 * @package MyHomeCore\Panel
 */
class Panel_Notifications {

	/**
	 * Panel_Notifications constructor.
	 */
	public function __construct() {
		add_action( 'myhome_agent_created', array( $this, 'new_agent' ) );
		add_action( 'myhome_property_added', array( $this, 'new_property' ) );
		add_action( 'myhome_property_added_moderation', array( $this, 'new_property_moderation' ) );
		add_action( 'myhome_property_updated', array( $this, 'property_updated' ) );
		add_action( 'pending_to_publish', array( $this, 'property_approved' ), 10, 1 );
		add_action( 'pending_to_draft', array( $this, 'property_declined' ), 10, 1 );
	}

	public function new_agent( $user_id ) {
		if ( empty( \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_new-user'] ) ) {
			return;
		}

		$user    = User::get_instance( $user_id );
		$subject = sprintf( esc_html__( 'New user has been registered: %s', 'myhome-core' ), $user->get_name() );
		$message = wp_kses_post(
			sprintf( __( 'New user has been registered: %s', 'myhome-core' ), '<a href="' . $user->get_link() . '">' . $user->get_name() . '</a>' )
		);

		$this->send_notification( $subject, $message );
	}

	public function new_property( $property_id ) {
		if ( empty( \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_new-property'] ) ) {
			return;
		}

		$property = Estate::get_instance( $property_id );
		$subject  = sprintf( esc_html__( 'New property has been added: %s', 'myhome-core' ), $property->get_name() );
		$message  = wp_kses_post(
			sprintf( __( 'New property has been added: %s<br>', 'myhome-core' ), '<a href="' . $property->get_link() . '">' . $property->get_name() . '</a>' ) . '<br>'
			. sprintf( __( 'By: %s<br>', 'myhome-core' ), '<a href="' . $property->get_user()->get_link() . '">' . $property->get_user()->get_name() . '</a>' )
		);

		$this->send_notification( $subject, $message );
	}

	public function new_property_moderation( $property_id ) {
		if ( empty( \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_new-property-moderation'] ) ) {
			return;
		}

		$property = Estate::get_instance( $property_id );
		$subject  = sprintf( esc_html__( 'Moderation - new property has been added:  %s', 'myhome-core' ), $property->get_name() );
		$message  = wp_kses_post(
			sprintf( __( 'New property has been added and require moderation: %s<br>', 'myhome-core' ), '<a href="' . $property->get_link() . '">' . $property->get_name() . '</a>' ) . '<br>'
			. sprintf( __( 'By: %s<br>', 'myhome-core' ), '<a href="' . $property->get_user()->get_link() . '">' . $property->get_user()->get_name() . '</a>' )
		);

		$this->send_notification( $subject, $message );
	}

	public function property_updated( $property_id ) {
		if ( empty( \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_updated-property'] ) ) {
			return;
		}

		$property = Estate::get_instance( $property_id );
		$subject  = sprintf( esc_html__( 'Property has been updated: %s', 'myhome-core' ), $property->get_name() );
		$message  = wp_kses_post(
			sprintf( __( 'Property has been updated: %s<br>', 'myhome-core' ), '<a href="' . $property->get_link() . '">' . $property->get_name() . '</a>' ) . '<br>'
			. sprintf( __( 'By: %s<br>', 'myhome-core' ), '<a href="' . $property->get_user()->get_link() . '">' . $property->get_user()->get_name() . '</a>' )
		);

		$this->send_notification( $subject, $message );
	}

	/**
	 * @param \WP_Post $post
	 */
	public function property_approved( $post ) {
		if ( empty( \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_property-approved'] ) ) {
			return;
		}

		if ( $post->post_type != 'estate' ) {
			return;
		}

		$property = new Estate( $post );

		$subject = '';
		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_property-approved-subject'] ) ) {
			$subject = apply_filters(
				'wpml_translate_single_string',
				\MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_property-approved-subject'],
				'myhome-core',
				'Your Property has been approved'
			);
		}

		$subject = str_replace(
			array( '{{property_name}}', '{{property_ID}}' ),
			array( $property->get_name(), $property->get_ID() ),
			$subject
		);

		$message = '';
		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_property-approved-msg'] ) ) {
			$message = apply_filters(
				'wpml_translate_single_string',
				\MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_property-approved-msg'],
				'myhome-core',
				'Congratulation, your property has been approved.'
			);
		}

		$message = str_replace(
			array( '{{username}}', '{{property_name}}', '{{property_ID}}', '{{property_link}}' ),
			array(
				$property->get_user()->get_name(),
				$property->get_name(),
				$property->get_ID(),
				$property->get_link()
			),
			$message
		);

		$this->send_notification( $subject, $message, $property->get_user()->get_email() );
	}

	/**
	 * @param \WP_Post $post
	 */
	public function property_declined( $post ) {
		if ( empty( \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_property-declined'] ) ) {
			return;
		}

		if ( $post->post_type != 'estate' ) {
			return;
		}

		$property = new Estate( $post );

		$subject = '';
		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_property-declined-subject'] ) ) {
			$subject = apply_filters(
				'wpml_translate_single_string',
				\MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_property-declined-subject'],
				'myhome-core',
				'Your property has been declined'
			);
		}

		$subject = str_replace(
			array( '{{property_name}}', '{{property_ID}}' ),
			array( $property->get_name(), $property->get_ID() ),
			$subject
		);

		$message = '';
		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_property-declined-msg'] ) ) {
			$message = apply_filters(
				'wpml_translate_single_string',
				\MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_property-declined-msg'],
				'myhome-core',
				'We are sorry, your property has been declined.'
			);
		}

		$message = str_replace(
			array( '{{username}}', '{{property_name}}', '{{property_ID}}' ),
			array(
				$property->get_user()->get_name(),
				$property->get_name(),
				$property->get_ID(),
				$property->get_link()
			),
			$message
		);

		$this->send_notification( $subject, $message, $property->get_user()->get_email() );
	}

	public function send_notification( $subject, $message, $email = '' ) {
		if ( empty( $email ) ) {
			if (
				! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_email'] )
				&& \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_email'] == 'custom_email'
				&& ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_custom-email'] )
			) {
				$email = \MyHomeCore\My_Home_Core()->settings->props['mh-panel-notify_custom-email'];
			} else {
				$email = get_option( 'admin_email' );
			}
		}
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		$message = str_replace( PHP_EOL, '<br />', $message );

		wp_mail( $email, $subject, $message, $headers );
	}

}