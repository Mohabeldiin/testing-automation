<?php

namespace MyHomeCore\Users;

use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Price_Attribute;
use MyHomeCore\Attributes\Price_Attribute_Options_Page;
use MyHomeCore\Core;
use MyHomeCore\Estates\Estate;
use MyHomeCore\Estates\Estate_Data;
use MyHomeCore\Estates\Prices\Currencies;
use MyHomeCore\Terms\Term;
use MyHomeCore\Terms\Term_Factory;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;


/**
 * Class Users_Ajax
 * @package MyHomeCore\Users
 */
class Users_Ajax {

	/**
	 * Users_Ajax constructor.
	 */
	public function __construct() {
		if ( empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-panel'] ) ) {
			return;
		}

		add_action( 'wp_ajax_nopriv_myhome_user_panel_login', array( $this, 'login' ) );
		add_action( 'wp_ajax_nopriv_myhome_user_panel_reset_password', array( $this, 'reset_password' ) );
		add_action( 'wp_ajax_myhome_user_panel_logout', array( $this, 'logout' ) );
		add_action( 'wp_ajax_myhome_user_panel_get_property', array( $this, 'get_property' ) );
		add_action( 'wp_ajax_myhome_user_panel_get_property_lang_url', array( $this, 'get_property_lang_url' ) );
		add_action( 'wp_ajax_myhome_user_panel_create_property', array( $this, 'create_property' ) );
		add_action( 'wp_ajax_myhome_user_panel_save_property', array( $this, 'save_property' ) );
		add_action( 'wp_ajax_myhome_user_panel_delete_property', array( $this, 'delete_property' ) );
		add_action( 'wp_ajax_myhome_user_panel_stripe_payment', array( $this, 'stripe_payment' ) );
		add_action( 'wp_ajax_myhome_user_panel_paypal_payment', array( $this, 'paypal_payment' ) );
		add_action( 'wp_ajax_myhome_user_panel_add_image', array( $this, 'add_image' ) );
		add_action( 'wp_ajax_myhome_user_panel_add_plan_image', array( $this, 'add_plan_image' ) );
		add_action( 'wp_ajax_myhome_user_panel_get_embed', array( $this, 'get_embed' ) );
		add_action( 'wp_ajax_myhome_user_panel_save_agent', array( $this, 'save_agent' ) );
		add_action( 'wp_ajax_myhome_user_panel_change_password', array( $this, 'change_password' ) );

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-registration'] ) ) {
			add_action( 'wp_ajax_nopriv_myhome_user_panel_register', array( $this, 'register' ) );
		}

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-disable_backend'] ) ) {
			add_action( 'admin_init', array( $this, 'disable_backend' ) );
		}

		add_action( 'after_setup_theme', array( $this, 'remove_admin_bar' ) );
	}

	public function get_property_lang_url() {
		$property_id = apply_filters( 'wpml_object_id', $_POST['property_id'], 'estate', false, $_POST['lang_code'] );

		if ( is_null( $property_id ) ) {
			do_action( 'wpml_make_post_duplicates', $_POST['property_id'] );
			$property_id = apply_filters( 'wpml_object_id', $_POST['property_id'], 'estate', false, $_POST['lang_code'] );
		}
		$permalink = apply_filters( 'wpml_permalink', $_POST['url'], $_POST['lang_code'] );

		echo json_encode(
			array(
				'redirect' => $permalink . '#edit-property-' . $property_id
			)
		);

		wp_die();
	}

	public function disable_backend() {
		$user = wp_get_current_user();

		if ( ! in_array( 'agent', $user->roles ) ) {
			return;
		}

		if ( ! in_array( 'agency', $user->roles ) ) {
			return;
		}

		if ( ! in_array( 'buyer', $user->roles ) ) {
			return;
		}

		$file = basename( $_SERVER['PHP_SELF'] );
		if ( is_admin() && $file != 'admin-post.php' && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			if ( ! empty( $options['mh-agent-panel_link'] ) ) {
				$redirect = $options['mh-agent-panel_link'];
			} else {
				$redirect = home_url();
			}
			wp_redirect( $redirect );
			exit;
		}
	}

	public function login() {
		check_ajax_referer( 'myhome_user_panel' );

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-captcha'] ) ) {
			$this->verify_captcha();
		}

		if ( empty( $_POST['credentials'] ) || ! isset( $_POST['rememberMe'] ) ) {
			echo json_encode(
				array(
					'success' => false,
					'title'   => esc_html__( 'Authentication failed', 'myhome-core' ),
					'text'    => esc_html__( 'Some data are missing!', 'myhome-core' )
				)
			);

			wp_die();
		}

		$wp_user_login = $_POST['credentials']['login'];

		if ( filter_var( $wp_user_login, FILTER_VALIDATE_EMAIL ) ) {
			$wp_user = get_user_by( 'email', $wp_user_login );
		} else {
			$wp_user = get_user_by( 'login', $wp_user_login );
		}

		if ( is_wp_error( $wp_user ) || ! $wp_user ) {
			echo json_encode(
				array(
					'success' => false,
					'title'   => esc_html__( 'Authentication failed', 'myhome-core' ),
					'text'    => esc_html__( 'Wrong username or password!', 'myhome-core' )
				)
			);

			wp_die();
		}

		$user = User::get_instance( $wp_user );

		if (
			! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-registration'] )
			&& ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-email_confirmation'] )
			&& ! $user->is_confirmed()
		) {
			echo json_encode(
				array(
					'success'                 => false,
					'title'                   => esc_html__( 'Authentication failed', 'myhome-core' ),
					'text'                    => esc_html__( 'Account isn\'t active. Check your mailbox for activation link.', 'myhome-core' ),
					'request_activation_link' => admin_url( 'admin-ajax.php?action=mh_agent_send_link&uid=' . $user->get_ID() )
				)
			);
			wp_die();
		}


		$login_data = array(
			'user_login'    => $_POST['credentials']['login'],
			'user_password' => $_POST['credentials']['password'],
			'remember'      => $_POST['rememberMe']
		);

		$wp_user = wp_signon( $login_data, false );
		if ( is_wp_error( $wp_user ) ) {
			echo json_encode(
				array(
					'success' => false,
					'title'   => esc_html__( 'Authentication failed', 'myhome-core' ),
					'text'    => esc_html__( 'Wrong username or password!', 'myhome-core' )
				)
			);

			wp_die();
		}

		echo json_encode(
			array(
				'success' => true,
				'title'   => esc_html__( 'Login successful', 'myhome-core' ),
				'text'    => esc_html__( sprintf( 'Hello %s', $user->get_name() ), 'myhome-core' ),
				'user'    => $user->get_data(),
				'nonce'   => wp_create_nonce( 'myhome_user_panel_' . $user->get_ID() )
			)
		);
		wp_die();
	}

	public function reset_password() {
		check_ajax_referer( 'myhome_user_panel' );

		if ( empty( $_POST['email'] ) || ! is_email( $_POST['email'] ) ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$email     = sanitize_email( $_POST['email'] );
		$user_data = get_user_by( 'email', trim( wp_unslash( $email ) ) );

		if ( empty( $user_data ) ) {
			echo json_encode( array( 'success' => true ) );
			wp_die();
		}

		if ( is_multisite() ) {
			$site_name = get_network()->site_name;
		} else {
			$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		$key        = get_password_reset_key( $user_data );

		$title   = sprintf( esc_html__( '[%s] Password Reset', 'myhome-core' ), $site_name );
		$message = esc_html__( 'Someone has requested a password reset for the following account:', 'myhome-core' ) . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
		$message .= esc_html__( 'If this was a mistake, just ignore this email and nothing will happen.', 'myhome-core' ) . "\r\n\r\n";
		$message .= esc_html__( 'To reset your password, visit the following address:', 'myhome-core' ) . "\r\n\r\n";
		$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "> \r\n\r\n";

		if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		echo json_encode( array( 'success' => true ) );

		wp_die();
	}

	public function logout() {
		wp_logout();
		wp_die();
	}

	private function verify_captcha() {
		$secret_key = \MyHomeCore\My_Home_Core()->settings->get( 'agent_captcha_secret-key' );
		if ( empty( trim( $secret_key ) ) ) {
			return;
		}

		$response      = wp_remote_get(
			'https://www.google.com/recaptcha/api/siteverify', array(
				'body' => array(
					'secret'   => $secret_key,
					'response' => $_POST['captcha'],
					'remoteip' => $_SERVER['REMOTE_ADDR']
				)
			)
		);
		$response_body = json_decode( $response['body'] );
		if ( empty( $response_body->success ) || ! $response_body->success ) {
			echo json_encode(
				array(
					'success'        => false,
					'title'          => esc_html__( 'Authentication failed', 'myhome-core' ),
					'text'           => esc_html__( 'Wrong captcha', 'myhome-core' ),
					'captcha_reload' => true
				)
			);

			wp_die();
		}
	}

	public function register() {
		check_ajax_referer( 'myhome_user_panel' );

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-captcha'] ) ) {
			$this->verify_captcha();
		}

		if ( empty( $_POST['user'] ) ) {
			wp_die();
		}

		if ( empty( $_POST['user']['login'] ) || empty( $_POST['user']['password'] ) || empty( $_POST['user']['password'] ) ) {
			wp_die();
		}

		$user_login    = $_POST['user']['login'];
		$user_email    = $_POST['user']['email'];
		$user_password = $_POST['user']['password'];
		$results       = register_new_user( $user_login, $user_email );

		// check errors
		if ( is_wp_error( $results ) ) {
			echo json_encode(
				array(
					'success' => false,
					'message' => $results->get_error_message(),
				)
			);

			wp_die();
		}

		$wp_user = new \WP_User( $results );
		$wp_user->add_role( 'agent' );
		$wp_user->remove_role( 'subscriber' );

		wp_set_password( $user_password, $results );

		do_action( 'myhome_agent_created', $wp_user->ID );

		echo json_encode(
			array(
				'success'       => true,
				'activate_link' => ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-email_confirmation'] )
			)
		);

		wp_die();
	}

	public function create_property() {
		$this->check_caps();

		$post_id = wp_insert_post(
			array(
				'post_author' => get_current_user_id(),
				'post_type'   => 'estate'
			)
		);

		if ( $post_id instanceof \WP_Error ) {
			wp_die( new \WP_Error( esc_html__( 'Something went wrong.', 'myhome-core' ) ) );
		}

		do_action( 'wpml_make_post_duplicates', $post_id );

		$property = Estate::get_instance( $post_id );

		echo json_encode(
			array(
				'data'      => $property->get_data(),
				'full_data' => $property->get_full_data()
			)
		);
		wp_die();
	}

	public function add_plan_image() {
		$this->check_caps();

		$mime = $_FILES['file']['type'];
		if ( $mime != 'image/jpeg' && $mime != 'image/jpg' && $mime != 'image/png' ) {
			wp_die();
		}

		$image_id = media_handle_upload( 'file', 0 );
		if ( is_object( $image_id ) ) {
			wp_die();
		}

		echo json_encode(
			array(
				'image'    => wp_get_attachment_url( $image_id ),
				'image_id' => $image_id
			)
		);
		wp_die();
	}

	public function add_image() {
		$this->check_caps();

		$mime = $_FILES['file']['type'];
		if ( $mime != 'image/jpeg' && $mime != 'image/jpg' && $mime != 'image/png' ) {
			wp_die();
		}

		$image_id = media_handle_upload( 'file', 0 );
		if ( is_object( $image_id ) ) {
			wp_die();
		}

		echo json_encode(
			array(
				'image_id'     => $image_id,
				'image_srcset' => wp_get_attachment_image_srcset( $image_id ),
				'image_url'    => wp_get_attachment_url( $image_id )
			)
		);
		wp_die();
	}

	public function get_property() {
		$this->check_caps();

		if ( isset( $_POST['property_id'] ) ) {
			$property_id = intval( $_POST['property_id'] );
			$property    = Estate::get_instance( $property_id );
			if ( $property->get_user()->get_ID() !== get_current_user_id() ) {
				wp_die();
			}

			echo json_encode( $property->get_full_data() );
		}

		wp_die();
	}

	public function get_embed() {
		$this->check_caps();

		if ( ! isset( $_POST['url'] ) ) {
			wp_die();
		}

		echo wp_oembed_get( $_POST['url'] );

		wp_die();
	}

	public function save_agent() {
		$this->check_caps();

		if ( empty( $_POST['agent'] ) || ! is_array( $_POST['agent'] ) ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$agent = $_POST['agent'];

		if ( empty( $agent['id'] ) || get_current_user_id() != $agent['id'] ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$current_user = User::get_instance( get_current_user_id() );


		wp_update_user(
			array(
				'ID'            => $current_user->get_ID(),
				'user_nicename' => $agent['name'],
				'display_name'  => $agent['name']
			)
		);

		if ( isset( $agent['phone'] ) ) {
			update_field( 'myhome_agent_phone', sanitize_text_field( $agent['phone'] ), 'user_' . $current_user->get_ID() );
		}

		if ( isset( $agent['image']['id'] ) ) {
			$image_id = intval( $agent['image']['id'] );
		} else {
			$image_id = 0;
		}
		update_field( 'myhome_agent_image', $image_id, 'user_' . $current_user->get_ID() );

		if ( isset( $agent['social'] ) && is_array( $agent['social'] ) ) {
			foreach ( $agent['social'] as $social ) {
				update_field( 'myhome_agent_' . $social['key'], $social['value'], 'user_' . $current_user->get_ID() );
			}
		}

		if ( isset( $agent['fields'] ) && is_array( $agent['fields'] ) ) {
			foreach ( $agent['fields'] as $field ) {
				update_field( 'myhome_agent_' . $field['slug'], $field['value'], 'user_' . $current_user->get_ID() );
			}
		}

		echo json_encode( array( 'success' => true ) );

		wp_die();
	}

	public function change_password() {
		// check perms
		$this->check_caps();

		// check if data provided
		if ( empty( $_POST['data'] ) || ! is_array( $_POST['data'] ) ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$data = $_POST['data'];

		// check if required data isn't empty
		if ( empty( $data['user_id'] ) || empty( $data['old_password'] ) || empty( $data['new_password'] ) ) {
			echo json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'Some required data are missing.', 'myhome-core' )
				)
			);
			wp_die();
		}

		// check if current user
		if ( get_current_user_id() != intval( $data['user_id'] ) ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		// check if old password is correct
		$current_user = User::get_instance( get_current_user_id() );
		if ( ! wp_check_password( $data['old_password'], $current_user->get_hash(), get_current_user_id() ) ) {
			echo json_encode(
				array(
					'message' => esc_html__( 'Old password isn\'t correct', 'myhome-core' ),
					'success' => false
				)
			);
			wp_die();
		}

		// change password
		wp_set_password( $data['new_password'], get_current_user_id() );

		echo json_encode(
			array(
				'success' => true
			)
		);
		wp_die();
	}

	public function save_property() {
		$this->check_caps();

		if ( ! isset( $_POST['property'] ) || ! is_array( $_POST['property'] ) || empty( $_POST['property'] ) ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$property   = $_POST['property'];
		$post       = get_post( $property['id'] );
		$old_status = $post->post_status;

		if ( ! $post instanceof \WP_Post || $post->post_author != get_current_user_id() ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$moderation    = \MyHomeCore\My_Home_Core()->settings->props['mh-agent-moderation'];
		$payment       = \MyHomeCore\My_Home_Core()->settings->props['mh-payment'];
		$payment_state = get_post_meta( $post->ID, 'myhome_state', true );

		if ( ! empty( $payment ) && ( empty( $payment_state ) || $payment_state == 'pre_payment' ) ) {
			$new_status = 'draft';
			$message    = esc_html__( 'Please choose a payment method', 'myhome-core' );
			$redirect   = 'payment';
		} elseif ( ! empty( $moderation ) ) {
			$new_status = 'pending';
			$message    = esc_html__( 'Thank you for submitting your property information. Your property is waiting for approval.', 'myhome-core' );
			$redirect   = 'properties';
		} else {
			$new_status = 'publish';
			$message    = esc_html__( 'Thank you for submitting your property information', 'myhome-core' );
			$redirect   = 'properties';
		}

		wp_update_post(
			array(
				'ID'           => $property['id'],
				'post_title'   => isset( $property['post_title'] ) ? $property['post_title'] : '',
				'post_content' => isset( $property['post_content'] ) ? $property['post_content'] : '',
				'post_status'  => $new_status
			)
		);

		$estate = Estate::get_instance( $property['id'] );

		// Featured image
		if ( ! empty( $property['image_id'] ) ) {
			set_post_thumbnail( $estate->get_ID(), $property['image_id'] );
		} else {
			delete_post_thumbnail( $estate->get_ID() );
		}

		// Featured
		if ( isset( $property['is_featured'] ) ) {
			update_field( 'myhome_estate_featured', intval( $property['is_featured'] == 'true' ), $property['id'] );
		}

		// Gallery
		$gallery = array();
		if ( isset( $property['gallery'] ) && is_array( $property['gallery'] ) ) {
			foreach ( $property['gallery'] as $image ) {
				$gallery[] = $image['image_id'];
			}
		}
		update_post_meta( $estate->get_ID(), 'estate_gallery', $gallery );

		// Plans
		$plans = array();
		if ( isset( $property['plans'] ) && is_array( $property['plans'] ) ) {
			foreach ( $property['plans'] as $plan ) {
				$plans[] = array(
					'estate_plans_name'  => $plan['label'],
					'estate_plans_image' => intval( $plan['image_id'] )
				);
			}
		}
		update_field( 'myhome_estate_plans', $plans, $property['id'] );

		// Price
		$is_price_range = Price_Attribute::is_range();
		$currencies     = Currencies::get_all();

		if ( function_exists( 'icl_object_id' ) && ( \MyHomeCore\My_Home_Core()->current_language != \MyHomeCore\My_Home_Core()->default_language ) ) {
			Term_Factory::$offer_types = array();
			do_action( 'wpml_switch_language', \MyHomeCore\My_Home_Core()->default_language );
			$offer_types = Term_Factory::get_offer_types();
			do_action( 'wpml_switch_language', \MyHomeCore\My_Home_Core()->current_language );
		} else {
			$offer_types = Term_Factory::get_offer_types();
		}

		foreach ( $currencies as $currency ) {
			$currency_key = $currency->get_key();

			if ( $is_price_range ) {
				$price_keys = array( $currency_key . '_from', $currency_key . '_to' );
			} else {
				$price_keys = array( $currency_key );
			}

			foreach ( $price_keys as $price_key ) {
				if ( isset( $property['prices'][ $price_key ] ) ) {
					update_field( 'myhome_estate_attr_' . $price_key, floatval( $property['prices'][ $price_key ] ), $property['id'] );
				}
			}

			foreach ( $offer_types as $offer_type ) {
				if ( ! $offer_type->specify_price() ) {
					continue;
				}

				if ( $offer_type->is_price_range() ) {
					$price_keys = array( $currency_key . '_from', $currency_key . '_to' );
				} else {
					$price_keys = array( $currency_key );
				}

				foreach ( $price_keys as $price_key ) {
					$field_key = $price_key . '_offer_' . $offer_type->get_ID();
					if ( isset( $property['prices'][ $field_key ] ) ) {
						update_field( 'myhome_estate_attr_' . $field_key, floatval( $property['prices'][ $field_key ] ), $property['id'] );
					}
				}
			}
		}

		// Video
		if ( isset( $property['video'] ) ) {
			if ( isset( $property['video']['url'] ) ) {
				$video = $property['video']['url'];
			} else {
				$video = '';
			}

			update_field( 'myhome_estate_video', $video, $property['id'] );
		}

		// Virtual tour
		if ( isset( $property['virtual_tour'] ) ) {
			update_field( 'myhome_estate_virtual_tour', $property['virtual_tour'], $property['id'] );
		}

		// Map
		if (
			isset( $property['address'] ) && isset( $property['location'] ) && isset( $property['location']['lat'] )
			&& isset( $property['location']['lng'] ) && ! empty( $property['address'] ) && ! empty( $property['location']['lat'] )
			&& ! empty( $property['location']['lng'] )
		) {
			$map = array(
				'address' => $property['address'],
				'lat'     => $property['location']['lat'],
				'lng'     => $property['location']['lng'],
				'zoom'    => 15
			);

			update_field( 'myhome_estate_location', $map, $property['id'] );
		}

		// Attributes
		foreach ( $property['attributes'] as $attr ) {
			$attribute = Attribute_Factory::get_by_slug( $attr['slug'] );

			if ( $attribute instanceof Price_Attribute ) {
				continue;
			}

			if ( isset( $attr['values'] ) ) {
				$attribute->update_estate_values( $property['id'], $attr['values'] );
			}
		}

		$is_new = get_post_meta( $estate->get_ID(), 'mh_new_property', true );
		if ( empty( $is_new ) ) {
			update_post_meta( $estate->get_ID(), 'mh_new_property', true );
			do_action( 'myhome_property_added', $estate->get_ID() );
		} else {
			do_action( 'myhome_property_updated', $estate->get_ID() );
		}

		if ( $estate->is_awaiting_moderation() && $old_status != 'pending' ) {
			do_action( 'myhome_property_added_moderation', $estate->get_ID() );
		}

		// Response
		echo json_encode(
			array(
				'success'  => true,
				'property' => Estate_Data::get_data( Estate::get_instance( $property['id'] ) ),
				'message'  => $message,
				'context'  => $redirect
			)
		);
		wp_die();
	}

	public function delete_property() {
		$this->check_caps();

		if ( empty( $_POST['property_id'] ) ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$property_id = intval( $_POST['property_id'] );
		$estate      = Estate::get_instance( $property_id );
		if ( $estate->get_user()->get_ID() != get_current_user_id() ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		wp_delete_post( $estate->get_ID() );
		echo json_encode( array( 'success' => true ) );

		wp_die();
	}

	public function stripe_payment() {
		$this->check_caps();

		if ( empty( $_POST['property'] ) ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$property = $_POST['property'];
		$estate   = Estate::get_instance( $property['id'] );

		if ( $estate->get_user()->get_ID() !== get_current_user_id() ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		require_once plugin_dir_path( __DIR__ ) . 'libs/stripe-php-master/init.php';
		$options = get_option( 'myhome_redux' );

		Stripe::setApiKey( $options['mh-payment-stripe-secret_key'] );

		$token = $_POST['stripe_token'];
		$email = $_POST['stripe_email'];

		$state = get_post_meta( $estate->get_ID(), 'myhome_state', true );
		if ( ! empty( $state ) && $state == 'payed' ) {
			echo json_encode(
				array(
					'success' => false,
					'payed'   => true,
					'message' => esc_html__( 'Already payed', 'myhome-core' )
				)
			);
			wp_die();
		}

		try {
			$customer = Customer::create(
				array(
					'email'  => $email,
					'source' => $token
				)
			);

			$charge = Charge::create(
				array(
					'description' => sprintf( esc_html__( '%s listing, Estate ID: %d', 'myhome-core' ), get_bloginfo( 'name' ), $estate->get_ID() ),
					'customer'    => $customer->id,
					'amount'      => $options['mh-payment-stripe-cost'],
					'currency'    => $options['mh-payment-stripe-currency']
				)
			);

			if ( ! empty( $options['mh-agent-moderation'] ) ) {
				$new_status = 'pending';
				$message    = esc_html__( 'Thank you for your payment. Your transaction has been completed. Your property is waiting for approval.', 'myhome-core' );
			} else {
				$new_status = 'publish';
				$message    = esc_html__( 'Thank you for your payment. Your transaction has been completed. Your property is published.', 'myhome-core' );
			}

			update_post_meta( $estate->get_ID(), 'myhome_state', 'payed' );

			foreach ( \MyHomeCore\My_Home_Core()->languages as $lang ) {
				$property_id = apply_filters( 'wpml_object_id', $estate->get_ID(), 'estate', false, $lang['code'] );
				update_post_meta( $property_id, 'myhome_state', 'payed' );
			}

			wp_update_post(
				array(
					'ID'          => $estate->get_ID(),
					'post_status' => $new_status
				)
			);

			$response = array(
				'success'  => true,
				'message'  => $message,
				'property' => Estate::get_instance( $estate->get_ID() )->get_data()
			);
		} catch ( \Exception $e ) {
			$response = array(
				'success' => false,
				'message' => $e->getMessage()
			);
		}

		echo json_encode( $response );

		wp_die();
	}

	private function paypal_token() {
		$options = get_option( 'myhome_redux' );

		$credentials = array(
			$options['mh-payment-paypal-public_key'],
			$options['mh-payment-paypal-secret_key']
		);

		if ( ! empty( $options['mh-payment-paypal-sandbox'] ) && $options['mh-payment-paypal-sandbox'] ) {
			$url = 'https://api.sandbox.paypal.com/v1/oauth2/token';
		} else {
			$url = 'https://api.paypal.com/v1/oauth2/token';
		}

		$args = 'grant_type=client_credentials';

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_USERPWD, $credentials[0] . ':' . $credentials[1] );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $args );
		$response = curl_exec( $ch );
		curl_close( $ch );
		if ( $response ) {
			$data = json_decode( $response );
			if ( ! empty( $data->access_token ) ) {
				return $data->access_token;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function paypal_payment() {
		$this->check_caps();

		if ( false === ( $access_token = $this->paypal_token() ) ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$propertyId = intval( $_POST['propertyID'] );
		$estate     = Estate::get_instance( $propertyId );

		if ( $estate->get_user()->get_ID() != get_current_user_id() ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$state = get_post_meta( $estate->get_ID(), 'myhome_state', true );
		if ( ! empty( $state ) && $state == 'payed' ) {
			echo json_encode(
				array(
					'success' => false,
					'payed'   => true,
					'message' => esc_html__( 'Already payed', 'myhome-core' )
				)
			);
			wp_die();
		}

		$options    = get_option( 'myhome_redux' );
		$payment_id = $_POST['paymentID'];
		$args       = array( 'payer_id' => $_POST['payerID'] );
		if ( ! empty( $options['mh-payment-paypal-sandbox'] ) && $options['mh-payment-paypal-sandbox'] ) {
			$url = 'https://api.sandbox.paypal.com/v1/payments/payment/' . $payment_id . '/execute/';
		} else {
			$url = 'https://api.paypal.com/v1/payments/payment/' . $payment_id . '/execute/';
		}

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $args ) );
		curl_setopt(
			$ch, CURLOPT_HTTPHEADER, array(
				"Authorization:Bearer $access_token",
				"Content-Type:application/json"
			)
		);
		$response = curl_exec( $ch );

		curl_close( $ch );
		if ( $response ) {
			$data = json_decode( $response );
			if ( ! empty( $data->state ) && $data->state == 'approved' ) {
				if ( empty( $options['mh-agent-moderation'] ) && $options['mh-agent-moderation'] ) {
					$new_status = 'pending';
				} else {
					$new_status = 'publish';
				}

				update_post_meta( $estate->get_ID(), 'myhome_state', 'payed' );

				foreach ( \MyHomeCore\My_Home_Core()->languages as $lang ) {
					$property_id = apply_filters( 'wpml_object_id', $estate->get_ID(), 'estate', false, $lang['code'] );
					update_post_meta( $property_id, 'myhome_state', 'payed' );
				}

				add_post_meta( $estate->get_ID(), 'myhome_paypal_payment_id', $payment_id, true );
				wp_update_post(
					array(
						'ID'          => $estate->get_ID(),
						'post_status' => $new_status
					)
				);

				$estate = Estate::get_instance( $estate->get_ID() );

				echo json_encode(
					array(
						'success'  => true,
						'message'  => esc_html__( 'Thank you for your payment. Your transaction has been completed.', 'myhome-core' ),
						'property' => $estate->get_data(),
					)
				);
				wp_die();
			} else {
				echo json_encode( array( 'success' => false ) );
				wp_die();
			}
		} else {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		wp_die();
	}

	private function check_caps() {
		if ( ! is_user_logged_in() ) {
			wp_die( new \WP_Error( '401' ) );
		}

		if ( ! check_ajax_referer( 'myhome_user_panel_' . get_current_user_id(), $_POST['_wpnonce'] ) ) {
			wp_die( new \WP_Error( '401' ) );
		}
	}

	public function remove_admin_bar() {
		if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {
			show_admin_bar( false );
		}
	}

}