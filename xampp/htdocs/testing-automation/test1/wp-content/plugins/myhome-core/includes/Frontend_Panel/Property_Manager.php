<?php

namespace MyHomeCore\Frontend_Panel;


use MyHomeCore\Estates\Estate;
use MyHomeCore\Estates\Estate_Factory;
use MyHomeCore\Frontend_Panel\Save_Field\Field;
use MyHomeCore\Frontend_Panel\Save_Field\Number_Field;
use MyHomeCore\Frontend_Panel\Save_Field\Text_Field;
use MyHomeCore\Payments\Payments;

/**
 * Class Property_Manager
 * @package Frontend_Panel
 */
class Property_Manager {

	/**
	 * Property_Manager constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_myhome_user_panel_upload_image', array( $this, 'upload_image' ) );
		add_action( 'wp_ajax_nopriv_myhome_user_panel_upload_image', array( $this, 'upload_image' ) );
		add_action( 'wp_ajax_myhome_user_panel_upload_attachment', array( $this, 'upload_attachment' ) );
		add_action( 'wp_ajax_nopriv_myhome_user_panel_upload_attachment', array( $this, 'upload_attachment' ) );
		add_action( 'wp_ajax_myhome_user_panel_upload_plan', array( $this, 'upload_plan' ) );
		add_action( 'wp_ajax_nopriv_myhome_user_panel_upload_plan', array( $this, 'upload_plan' ) );
		add_action( 'wp_ajax_nopriv_myhome_submit_property_steps', array( $this, 'get_steps' ) );
		add_action( 'wp_ajax_myhome_submit_property_steps', array( $this, 'get_steps' ) );

		add_action( 'wp_ajax_nopriv_myhome_user_panel_save_draft', array( $this, 'save_draft' ) );
		add_action( 'wp_ajax_myhome_user_panel_save_draft', array( $this, 'save_draft' ) );
		add_action( 'wp_ajax_myhome_user_panel_delete_property', array( $this, 'delete_property' ) );
		add_action( 'wp_ajax_myhome_user_panel_edit_property_form', array( $this, 'edit_property_form' ) );
		add_action( 'publish_estate', array( $this, 'set_expire' ) );
		add_action( 'init', array( $this, 'check_expire' ) );

		add_action( 'wp_ajax_myhome_user_panel_moderation', array( $this, 'moderation' ) );
		add_action( 'wp_ajax_myhome_user_panel_trash', array( $this, 'trash' ) );
		add_action( 'wp_ajax_myhome_user_panel_restore', array( $this, 'restore' ) );
		add_action( 'wp_ajax_myhome_user_panel_approve', array( $this, 'approve' ) );
		add_action( 'wp_ajax_myhome_user_panel_discard', array( $this, 'discard' ) );
	}

	public function check_expire() {
		global $wpdb;
		$query   = "SELECT * FROM {$wpdb->postmeta} WHERE meta_key = 'myhome_property_expire' && meta_value < '" . date( 'Y-m-d H:i:s' ) . "' AND meta_value != '0' AND meta_value != 0 AND meta_value != ''";
		$results = $wpdb->get_results( $query );

		foreach ( $results as $result ) {
			wp_update_post( array(
				'ID'          => $result->post_id,
				'post_status' => 'draft',
			) );

			update_post_meta( $result->post_id, 'is_expired', 1 );
			update_post_meta( $result->post_id, 'myhome_property_expire', 0 );
			update_post_meta( $result->post_id, 'myhome_state', 0 );
		}
	}

	public function set_expire( $property_ID ) {
		$expire = intval( \MyHomeCore\My_Home_Core()->settings->get( 'frontend-properties_expire' ) );
		if ( empty( $expire ) ) {
			return;
		}

		$expire_date = get_post_meta( $property_ID, 'myhome_property_expire', true );
		if ( ! empty( $expire_date ) ) {
			return;
		}

		$expire_date = date( 'Y-m-d H:i:s', strtotime( '+' . $expire . ' day', time() ) );
		update_post_meta( $property_ID, 'myhome_property_expire', $expire_date );
	}

	public function upload_image() {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$uploaded_file = $_FILES['file'];
		$max_size      = intval( \MyHomeCore\My_Home_Core()->settings->get( 'frontend-images_max-size' ) ) * 1024;
		$uploaded_size = intval( $uploaded_file['size'] / 1024 );

		if ( ! empty( $max_size ) && $uploaded_size > $max_size ) {
			echo json_encode( array(
				'success' => false,
				'message' => esc_html__( 'File exceeded allowed size.', 'myhome-core' ),
			) );
			wp_die();
		}

		$attachment_id = media_handle_upload( 'file', 0 );
		if ( isset( $_POST['type'] ) && $_POST['type'] == 'user' ) {
			$url = wp_get_attachment_image_url( $attachment_id, 'myhome-square-s' );
		} else {
			$url = wp_get_attachment_image_url( $attachment_id, 'myhome-standard-s' );
		}
		echo json_encode( array(
			'image_id' => $attachment_id,
			'url'      => $url,
		) );
		wp_die();
	}

	public function upload_attachment() {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$uploaded_file = $_FILES['file'];
		$max_size      = intval( \MyHomeCore\My_Home_Core()->settings->get( 'frontend-attachments_max-size' ) ) * 1024;
		$uploaded_size = intval( $uploaded_file['size'] / 1024 );

		if ( ! empty( $max_size ) && $uploaded_size > $max_size ) {
			echo json_encode( array(
				'success' => false,
				'message' => esc_html__( 'File exceeded allowed size.', 'myhome-core' ),
			) );
			wp_die();
		}

		$attachment_id = media_handle_upload( 'file', 0 );
		$url           = wp_get_attachment_image_url( $attachment_id, 'myhome-square-s' );
		echo json_encode( array(
			'image_id' => $attachment_id,
			'url'      => $url,
		) );
		wp_die();
	}

	public function upload_plan() {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$uploaded_file = $_FILES['file'];
		$max_size      = intval( \MyHomeCore\My_Home_Core()->settings->get( 'frontend-plans_max-size' ) ) * 1024;
		$uploaded_size = intval( $uploaded_file['size'] / 1024 );

		if ( ! empty( $max_size ) && $uploaded_size > $max_size ) {
			echo json_encode( array(
				'success' => false,
				'message' => esc_html__( 'File exceeded allowed size.', 'myhome-core' ),
			) );
			wp_die();
		}

		$attachment_id = media_handle_upload( 'file', 0 );
		$url           = wp_get_attachment_image_url( $attachment_id, 'myhome-square-s' );
		echo json_encode( array(
			'image_id' => $attachment_id,
			'url'      => $url,
		) );
		wp_die();
	}

	public function get_steps() {
		$steps = Submit_Property_Settings::get_steps();
		echo json_encode( $steps );
		wp_die();
	}

	public function save_draft() {
		if ( ! isset( $_POST['draft'] ) || ! is_array( $_POST['draft'] ) ) {
			$_POST['draft'] = array();
		}

		$only_registered = \MyHomeCore\My_Home_Core()->settings->get( 'frontend-submit_property_only_registered' );
		if ( ! empty( $only_registered ) && ! is_user_logged_in() ) {
			wp_die( esc_html__( 'Access denied', 'myhome-core' ) );
		}

		$property_data           = $_POST['draft'];
		$property_data['status'] = 'draft';

		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
		} else {
			$user_id = intval( \MyHomeCore\My_Home_Core()->settings->get( 'frontend-draft_user' ) );
			if ( empty( $user_id ) ) {
				wp_die( esc_html__( 'Temporary draft user not set. You can set it in the MyHome Theme options (Users -> Frontend settings)',
					'myhome-core' ) );
			}

			if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-captcha'] ) && ! $this->verify_captcha() ) {
				echo json_encode(
					array(
						'success' => false,
						'title'   => esc_html__( 'Authentication', 'myhome-core' ),
						'text'    => esc_html__( 'Captcha verification failed', 'myhome-core' ),
					)
				);
				wp_die();
			}
		}

		$fields = Submit_Property_Settings::get_selected_fields();

		if ( isset( $property_data['id'] ) ) {
			$is_new          = false;
			$post            = get_post( $property_data['id'] );
			$old_post_status = $post->post_status;
			$user            = wp_get_current_user();
			$all             = \MyHomeCore\My_Home_Core()->settings->get( 'agent-all' );
			if ( in_array( 'buyer', $user->roles ) ) {
				$all = false;
			}
			$all = apply_filters( 'myhome_panel_current_user_can_edit_all_properties', $all );
			if ( in_array( 'agency', $user->roles ) ) {
				$ids      = array();
				$wp_users = get_users( array(
					'meta_key'     => 'myhome_agency',
					'meta_value'   => $user->ID,
					'meta_compare' => '==',
				) );
				foreach ( $wp_users as $user ) {
					$ids[] = $user->ID;
				}
			} else {
				$ids = array();
			}

			if ( empty( $all ) && ( ! $post instanceof \WP_Post || intval( $post->post_author ) !== get_current_user_id() )
			     && ! in_array( 'administrator', $user->roles )
			     && ! in_array( 'super_agent', $user->roles )
			     && ! in_array( $post->post_author, $ids )
			) {
				echo json_encode( array( 'success' => false ) );
				wp_die();
			}
			$post_id = $post->ID;
		} else {
			$old_post_status = '';
			$is_new          = true;
			$post_id         = wp_insert_post( array(
				'post_title'  => esc_html__( 'Draft property', 'myhome-core' ),
				'post_type'   => 'estate',
				'post_author' => $user_id,
				'status'      => 'draft',
			) );
		}
		update_post_meta( $post_id, 'myhome_frontend', 1 );

		if ( ! is_user_logged_in() ) {
			$hash_value = 'myhome_draft_' . rand( 1, 10000 ) . '_' . time();
			update_post_meta( $post_id, 'myhome_frontend_draft', md5( $hash_value ) );
			update_post_meta( $post_id, 'myhome_frontend_draft_time', time() );
			setcookie( 'myhome_frontend_draft', $hash_value, time() + 60 * 60 * 24, '/' );
			setcookie( 'myhome_frontend_draft_id', $post_id, time() + 60 * 60 * 24, '/' );
		}

		try {
			$all_fields     = array();
			$property_types = array();
			foreach ( $fields as $field ) {
				$field_object = Field::get_instance( $field );
				if ( $field_object instanceof Text_Field && $field_object->is_property_type() ) {
					$field_object->save( $post_id, $property_data );
					$estate = Estate::get_instance( $post_id );
					foreach ( $estate->get_property_type() as $property_type ) {
						foreach ( $property_type->get_values() as $value ) {
							$property_types[] = $value->get_slug();
						}
					}
				} else {
					$all_fields[] = $field_object;
				}
			}

			foreach ( $all_fields as $field ) {
				/* @var $field \MyHomeCore\Frontend_Panel\Save_Field\Field */
				if ( ( $field instanceof Text_Field || $field instanceof Number_Field ) ) {
					if ( $field->fit_property_types( $property_types ) ) {
						$field->save( $post_id, $property_data );
					} else {
						$field->clear( $post_id );
					}
				} else {
					$field->save( $post_id, $property_data );
				}
			}
		} catch ( \Exception $e ) {
			echo json_encode( array(
				'success' => false,
				'message' => $e->getMessage(),
			) );
			wp_die();
		}

		$buy_package = false;
		if ( Panel_Settings::is_payment_enabled() && is_user_logged_in() ) {
			$payment_status = get_post_meta( $post_id, 'myhome_state', true );
			if ( $payment_status != 'payed' ) {
				$user   = User::get_current();
				$estate = Estate::get_instance( $post_id );
				if ( $estate->is_featured() ) {
					if ( $user->has_available_featured_listings() ) {
						$user->charge_featured_listing();
						update_post_meta( $post_id, 'myhome_state', 'payed' );
						$post_status = 'publish';
					} else {
						$post_status = 'draft';
						$buy_package = true;
					}
				} else {
					if ( $user->has_available_listings() ) {
						$user->charge_listing();
						update_post_meta( $post_id, 'myhome_state', 'payed' );
						$post_status = 'publish';
					} else {
						$post_status = 'draft';
						$buy_package = true;
					}
				}
			} else {
				$post_status = 'publish';
			}
		} else {
			$post_status = 'publish';
		}

		if ( Panel_Settings::is_moderation_enabled() && $post_status == 'publish' ) {
			if ( is_user_logged_in() ) {
				$user = wp_get_current_user();
				if ( in_array( 'administrator', $user->roles ) ) {
					$post_status = 'publish';
				} else {
					$post_status = 'pending';
				}
			} elseif ( Payments::is_enabled() ) {
				$post_status = 'draft';
			} else {
				$post_status = 'pending';
			}
		}

		if ( $post_status == 'pending' ) {
			do_action( 'myhome_property_added_moderation', $post_id );
		}

		if ( $is_new ) {
			do_action( 'myhome_property_added', $post_id );
		} else {
			do_action( 'myhome_property_updated', $post_id );
		}

		$post_status = apply_filters( 'myhome_property_status', $post_status, $post_id, $old_post_status );

		wp_update_post( array(
			'post_status' => $post_status,
			'ID'          => $post_id,
		) );

		$data = array(
			'success'     => true,
			'buy_package' => $buy_package,
			'is_logged'   => is_user_logged_in(),
			'redirect'    => apply_filters( 'myhome_panel_after_save_property_redirect', '' ),
		);

		if ( is_user_logged_in() ) {
			$data['user'] = User::get_current()->get_data();
			if ( $post_status == 'pending' ) {
				$data['pending'] = true;
				$data['message'] = esc_html__( 'Your property is awaiting moderation', 'myhome-core' );
			}
		} else {
			$data['message'] = esc_html__( 'Before your property is public you need to register.', 'myhome-core' );
		}

		echo json_encode( $data );
		wp_die();
	}

	public function delete_property() {
		if ( ! isset( $_POST['propertyID'] ) || empty( $_POST['propertyID'] ) ) {
			wp_die();
		}
		$user = wp_get_current_user();
		$all  = ! empty( \MyHomeCore\My_Home_Core()->settings->get( 'agent-all' ) );
		$all  = apply_filters( 'myhome_panel_current_user_can_edit_all_properties', $all );
		if ( in_array( 'buyer', $user->roles ) ) {
			$all = false;
		}

		$property_id = intval( $_POST['propertyID'] );
		$property    = get_post( $property_id );

		if ( in_array( 'agency', $user->roles ) ) {
			$ids      = array();
			$wp_users = get_users( array(
				'meta_key'     => 'myhome_agency',
				'meta_value'   => $user->ID,
				'meta_compare' => '==',
			) );
			foreach ( $wp_users as $user ) {
				$ids[] = $user->ID;
			}
		} else {
			$ids = array();
		}

		if ( ! $property instanceof \WP_Post || ( ! in_array( $property->post_author,
					$ids ) && $property->post_author != $user->ID && empty( $all ) && ! in_array( 'super_agent',
					$user->roles ) && ! in_array( 'administrator', $user->roles ) ) ) {
			wp_die();
		}

		$delete_type = \MyHomeCore\My_Home_Core()->settings->get( 'panel_delete_type' );
		if ( empty( $delete_type ) || $delete_type == 'delete' ) {
			wp_delete_post( $property_id );
		} else {
			wp_trash_post( $property_id );
		}

		wp_die();
	}

	public function edit_property_form() {
		$property_id = intval( $_POST['propertyID'] );

		try {
			$property = Estate::get_instance( $property_id );

			$all  = ! empty( \MyHomeCore\My_Home_Core()->settings->get( 'agent-all' ) );
			$all  = apply_filters( 'myhome_panel_current_user_can_edit_all_properties', $all );
			$user = wp_get_current_user();
			if ( current_user_can( 'administrator' ) ) {
				$all = true;
			}
			if ( in_array( 'buyer', $user->roles ) ) {
				$all = false;
			}

			if ( in_array( 'agency', $user->roles ) ) {
				$ids      = array();
				$wp_users = get_users( array(
					'meta_key'     => 'myhome_agency',
					'meta_value'   => $user->ID,
					'meta_compare' => '==',
				) );
				foreach ( $wp_users as $user ) {
					$ids[] = $user->ID;
				}
			} else {
				$ids = array();
			}

			if ( $property->get_user()->get_ID() != get_current_user_id() && empty( $all ) && ! in_array( 'super_agent',
					$user->roles ) && ! in_array( 'administrator',
					$user->roles ) && ! in_array( $property->get_user()->get_ID(), $ids ) ) {
				wp_die();
			}

			global $myhome_edit_form;
			$myhome_edit_form = true;

			echo json_encode( array(
				'property' => $property->get_full_data(),
				'success'  => true,
			) );
		} catch ( \Exception $e ) {
			echo json_encode( array( 'success' => false ) );
		}

		wp_die();
	}

	private function verify_captcha() {
		$secret_key = \MyHomeCore\My_Home_Core()->settings->get( 'agent_captcha_secret-key' );
		if ( empty( trim( $secret_key ) ) ) {
			return true;
		}

		$response      = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify', array(
				'body' => array(
					'secret'   => $secret_key,
					'response' => $_POST['captcha'],
					'remoteip' => $_SERVER['REMOTE_ADDR'],
				),
			)
		);
		$response_body = json_decode( $response['body'] );

		return ! empty( $response_body->success ) && $response_body->success;
	}

	public function moderation() {
		if ( ! is_user_logged_in() ) {
			wp_die();
		}

		$user = wp_get_current_user();
		if ( ! in_array( 'super_agent', $user->roles ) && ! in_array( 'administrator', $user->roles ) ) {
			wp_die();
		}

		$estates        = array();
		$estate_factory = new Estate_Factory();
		$estate_factory->set_limit( - 1 );
		$estate_factory->set_status( array( 'pending' ) );
		foreach ( $estate_factory->get_results() as $estate ) {
			$property = array(
				'ID'                => $estate->get_ID(),
				'name'              => $estate->get_name(),
				'link'              => $estate->get_link(),
				'status'            => $estate->get_status_formatted(),
				'created_at_string' => get_the_date( '', $estate->get_ID() ),
				'created_at_value'  => strtotime( $estate->get_publish_date() ),
				'image'             => '',
			);

			if ( ! empty( $expire ) ) {
				$property['expire'] = $estate->get_expire();
			}

			if ( $estate->has_image() ) {
				$property['image'] = wp_get_attachment_image_url( $estate->get_image_id(), 'myhome-standard-s' );
			}

			$estates[] = $property;
		}

		echo json_encode( $estates );
		wp_die();
	}

	public function trash() {
		if ( ! is_user_logged_in() ) {
			wp_die();
		}

		$user = wp_get_current_user();
		if ( ! in_array( 'super_agent', $user->roles ) && ! in_array( 'administrator', $user->roles ) ) {
			wp_die();
		}

		$estates        = array();
		$estate_factory = new Estate_Factory();
		$estate_factory->set_limit( - 1 );
		$estate_factory->set_status( array( 'trash' ) );
		foreach ( $estate_factory->get_results() as $estate ) {
			$property = array(
				'ID'                => $estate->get_ID(),
				'name'              => $estate->get_name(),
				'link'              => $estate->get_link(),
				'status'            => $estate->get_status_formatted(),
				'created_at_string' => get_the_date( '', $estate->get_ID() ),
				'created_at_value'  => strtotime( $estate->get_publish_date() ),
				'image'             => '',
			);

			if ( ! empty( $expire ) ) {
				$property['expire'] = $estate->get_expire();
			}

			if ( $estate->has_image() ) {
				$property['image'] = wp_get_attachment_image_url( $estate->get_image_id(), 'myhome-standard-s' );
			}

			$estates[] = $property;
		}

		echo json_encode( $estates );
		wp_die();
	}

	public function restore() {
		if ( ! is_user_logged_in() ) {
			wp_die();
		}

		$user = wp_get_current_user();
		if ( ! in_array( 'super_agent', $user->roles ) && ! in_array( 'administrator', $user->roles ) ) {
			wp_die();
		}

		if ( ! isset( $_POST['property_id'] ) || empty( $_POST['property_id'] ) ) {
			wp_die();
		}
		$id = intval( $_POST['property_id'] );
		wp_update_post( array(
			'ID'          => $id,
			'post_status' => 'draft',
		) );
		wp_die();
	}

	public function approve() {
		if ( ! is_user_logged_in() ) {
			wp_die();
		}

		$user = wp_get_current_user();
		if ( ! in_array( 'super_agent', $user->roles ) && ! in_array( 'administrator', $user->roles ) ) {
			wp_die();
		}

		if ( ! isset( $_POST['property_id'] ) || empty( $_POST['property_id'] ) ) {
			wp_die();
		}
		$id = intval( $_POST['property_id'] );
		wp_update_post( array(
			'ID'          => $id,
			'post_status' => 'publish',
		) );

		update_post_meta( $id, 'mh_approved', 1 );
		wp_die();
	}

	public function discard() {
		if ( ! is_user_logged_in() ) {
			wp_die();
		}

		$user = wp_get_current_user();
		if ( ! in_array( 'super_agent', $user->roles ) && ! in_array( 'administrator', $user->roles ) ) {
			wp_die();
		}

		if ( ! isset( $_POST['property_id'] ) || empty( $_POST['property_id'] ) ) {
			wp_die();
		}
		$id = intval( $_POST['property_id'] );
		wp_update_post( array(
			'ID'          => $id,
			'post_status' => 'draft',
		) );
		wp_die();
	}

}