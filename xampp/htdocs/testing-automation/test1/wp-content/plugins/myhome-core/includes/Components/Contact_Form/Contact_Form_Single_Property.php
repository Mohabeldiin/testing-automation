<?php

namespace MyHomeCore\Components\Contact_Form;


use MyHomeCore\Common\Translations;
use MyHomeCore\Estates\Estate;

/**
 * Class Contact_Form_Single_Property
 * @package MyHomeCore\Components\Contact_Form
 */
class Contact_Form_Single_Property extends Contact_Form {

	/**
	 * @var Estate
	 */
	private $estate;

	/**
	 * Contact_Form_Single_Property constructor.
	 *
	 * @param Estate $estate
	 */
	public function __construct( Estate $estate ) {
		$this->estate = $estate;
	}

	public function display() {
		ob_start();
		?>
		<contact-form
			:translations='<?php echo esc_attr( json_encode( Translations::get_contact_form() ) ); ?>'
			:estate-id='<?php echo esc_attr( $this->estate->get_ID() ); ?>'
			id="myhome-contact-form"
		></contact-form>
		<?php
		echo ob_get_clean();
	}

	public static function mail() {
		$params = $_POST;
		if ( ! isset( $params['estate_id'] )
			&& ! isset( $params['email'] )
			&& ! isset( $params['phone'] )
			&& ! isset( $params['message'] )
		) {
			die( '404' );
		}

		$msg_email   = sanitize_text_field( $params['email'] );
		$msg_phone   = sanitize_text_field( $params['phone'] );
		$msg_message = sanitize_text_field( $params['message'] );

		if ( mb_strlen( $msg_message, 'UTF-8' ) < 5 || ! is_email( $msg_email ) ) {
			return array( 'result' => false );
		}

		if ( array_key_exists( 'mh-contact_form-send_to', \MyHomeCore\My_Home_Core()->settings->props ) ) {
			$send_to = \MyHomeCore\My_Home_Core()->settings->props['mh-contact_form-send_to'];
		} else {
			$send_to = 'agents';
		}

		$estate_id = intval( $params['estate_id'] );
		$estate    = Estate::get_instance( $estate_id );
		if ( $send_to == 'agents' ) {
			$user  = $estate->get_user();
			$email = $user->get_email();
		} else {
			$email = \MyHomeCore\My_Home_Core()->settings->props['mh-contact_form-send_to-email'];
		}

		$title = wp_kses(
			sprintf( __( 'Contact %s - %s', 'myhome-core' ), $msg_email, $estate->get_name() ),
			array()
		);
		$msg   = wp_kses(
			sprintf(
				__( 'From: %s<br>Phone: %s<br>Message: %s<br>Property: %s', 'myhome-core' ),
				$msg_email,
				$msg_phone,
				$msg_message,
				'<a href="' . esc_url( $estate->get_link() ) . '">' . esc_html( $estate->get_name() ) . '</a>'
			), array(
			'br' => array(),
			'a'  => array( 'href' => array() )
		)
		);

		wp_mail(
			$email,
			$title,
			$msg,
			array( 'Content-Type: text/html; charset=UTF-8' )
		);
		wp_die();
	}

}