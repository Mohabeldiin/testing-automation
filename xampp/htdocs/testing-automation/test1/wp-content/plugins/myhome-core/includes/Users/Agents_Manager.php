<?php

namespace MyHomeCore\Users;

use MyHomeCore\Common\Social_Icon;
use MyHomeCore\Components\Save_Search\Save_Search;
use MyHomeCore\Users\Fields\Settings;


/**
 * Class Agents_Manager
 * @package MyHomeCore\Users
 */
class Agents_Manager {

	/**
	 * Agents_Manager constructor.
	 */
	public function __construct() {
		add_action( 'myhome_agent_created', array( $this, 'send_register_confirmation_email' ) );
		add_action( 'myhome_agent_welcome', array( $this, 'send_welcome_message_email' ) );
		add_action( 'admin_post_nopriv_myhome_user_panel_activate_user', array( $this, 'activate' ) );
		add_action( 'admin_post_myhome_user_panel_activate_user', array( $this, 'activate_redirect' ) );
		add_action( 'wp_ajax_nopriv_mh_agent_send_link', array( $this, 'send_link' ) );
		add_action( 'wp_mail_failed', array( $this, 'action_wp_mail_failed' ), 10, 1 );
		add_action( 'wp_ajax_myhome_user_panel_change_password', array( $this, 'change_password' ) );
		add_action( 'wp_ajax_myhome_user_panel_save_user', array( $this, 'save_user' ) );
		add_action( 'wp_ajax_myhome_add_to_favorite', array( $this, 'add_to_favorite' ) );
		add_action( 'wp_ajax_myhome_save_search', array( $this, 'save_search' ) );
		add_action( 'wp_ajax_myhome_remove_search', array( $this, 'remove_search' ) );

		add_action( 'wp_ajax_myhome_user_panel_invite_agent', array( $this, 'invite_agent' ) );
		add_action( 'wp_ajax_myhome_user_panel_accept_invite', array( $this, 'accept_invite' ) );
		add_action( 'wp_ajax_myhome_user_panel_decline_invite', array( $this, 'decline_invite' ) );
		add_action( 'wp_ajax_myhome_user_panel_remove_agent', array( $this, 'remove_agent' ) );
		add_action( 'wp_ajax_myhome_user_panel_regenerate_invite_code', array( $this, 'regenerate_invite_code' ) );
		add_action( 'wp_ajax_myhome_user_panel_join_agency', array( $this, 'join_agency' ) );
		add_action( 'wp_ajax_myhome_user_panel_remove_agency', array( $this, 'remove_agency' ) );

		add_action( 'admin_init', array( $this, 'disable_dashboard' ) );
		add_action( 'admin_post_myhome_saved_searches_check', array( $this, 'save_search_check' ) );
		add_action( 'admin_post_nopriv_myhome_saved_searches_check', array( $this, 'save_search_check' ) );
		add_action( 'admin_post_myhome_saved_searches_hash', array( $this, 'saved_searches_hash' ) );

		if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {
			show_admin_bar( false );
		}

		add_action( 'admin_menu', array( $this, 'saved_searches_menu' ), 100 );
		add_action( 'myhome_agent_created', array( $this, 'set_default_settings' ) );
		add_action( 'myhome_agent_invited', array( $this, 'agent_invite_notify' ), 10, 2 );
	}

	public function agent_invite_notify( $agent_id, $agency_id ) {
		$enabled = \MyHomeCore\My_Home_Core()->settings->get( 'panel-notify_agent-invited' );
		if ( empty( $enabled ) ) {
			return;
		}

		$page_id = \MyHomeCore\My_Home_Core()->settings->get( 'agent-panel_page' );
		if ( ! empty( $page_id ) ) {
			$panel_url = get_the_permalink( $page_id );
		} else {
			$panel_url = \MyHomeCore\My_Home_Core()->settings->props['mh-agent-panel_link'];
		}

		$agent   = User::get_user_by_id( $agent_id );
		$agency  = User::get_user_by_id( $agency_id );
		$message = \MyHomeCore\My_Home_Core()->settings->get( 'panel-notify_agent-invited-msg' );
		$message = str_replace( '{{username}}', $agent->get_name(), $message );
		$message = str_replace( '{{agency_name}}', $agency->get_name(), $message );
		$message = str_replace( '{{panel_link}}', $panel_url, $message );
		$subject = sprintf( esc_html__( '%s invitation', 'myhome-core' ), $agency->get_name() );
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$message = str_replace( PHP_EOL, '<br />', $message );

		wp_mail( $agent->get_email(), $subject, $message, $headers );
	}

	public function saved_searches_menu() {
		add_submenu_page(
			'myhome_attributes',
			esc_html__( 'Saved searches', 'myhome-core' ),
			esc_html__( 'Saved searches', 'myhome-core' ),
			'manage_options',
			'myhome_saved_searches',
			array( $this, 'saved_searches_menu_page' )
		);
	}

	public function saved_searches_menu_page() {
		$last_check = Save_Search::get_last_check();
		$hash       = Save_Search::get_hash();
		?>
        <div class="mh-saved-search-page">
            <h1><?php esc_html_e( 'Saved searches', 'myhome-core' ); ?></h1>

			<?php esc_html_e( 'You can click below button to send email notification to all your users who use saved searched feature. Email will be send only if there is at least 1 new property that match criteria.', 'myhome-core' ); ?>
            <br>
            <br><?php esc_html_e( 'Last time emails where changed', 'myhome-core' ); ?>
            : <?php echo esc_html( $last_check ); ?>
            <a class="button button-primary"
               href="<?php echo esc_url( admin_url( 'admin-post.php?action=myhome_saved_searches_check' ) ); ?>">
				<?php esc_html_e( 'Send emails', 'myhome-core' ); ?>
            </a>
            <br><br>
            <h2><?php esc_html_e( 'Cron jobs', 'myhome-core' ); ?></h2>

			<?php esc_html_e( 'You can use your server cron jobs to send emails automatically. Add there this link:', 'myhome-core' ); ?>
            <br>
            <br>
			<?php echo esc_url( admin_url( 'admin-post.php?action=myhome_saved_searches_check&hash=' . $hash ) ); ?><br>
            <br>
            <a href="<?php echo esc_url( admin_url( 'admin-post.php?action=myhome_saved_searches_hash' ) ); ?>"
               class="button">
				<?php esc_html_e( 'Regenerate', 'myhome-core' ); ?>
            </a>
        </div>
		<?php
	}

	public function saved_searches_hash() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		Save_Search::create_hash();
		wp_redirect( admin_url( 'admin.php?page=myhome_saved_searches' ) );
		die();
	}


	public function disable_dashboard() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$disable_backend = \MyHomeCore\My_Home_Core()->settings->get( 'agent-disable_backend' );
		if ( empty( $disable_backend ) ) {
			return;
		}

		$user = wp_get_current_user();
		if (
			! in_array( 'agent', $user->roles ) &&
			! in_array( 'agency', $user->roles ) &&
			! in_array( 'buyer', $user->roles )
		) {
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

	public function remove_agency() {
		if ( ! isset( $_POST['agency_id'] ) || empty( $_POST['agency_id'] ) ) {
			wp_die();
		}
		update_user_meta( get_current_user_id(), 'myhome_agency', 0 );
		echo json_encode( array(
			'user'    => \MyHomeCore\Frontend_Panel\User::get_current()->get_data(),
			'success' => true
		) );

		wp_die();
	}

	public function join_agency() {
		if ( ! isset( $_POST['invite_code'] ) || empty( $_POST['invite_code'] ) ) {
			wp_die();
		}

		$enabled = \MyHomeCore\My_Home_Core()->settings->get( 'agent-agency' );
		if ( empty( $enabled ) ) {
			wp_die();
		}

		$invite_code = sanitize_text_field( $_POST['invite_code'] );
		$users       = get_users( array(
			'meta_key'     => 'myhome_agency_invite_code',
			'meta_value'   => $invite_code,
			'meta_compare' => '=='
		) );

		if ( ! count( $users ) ) {
			echo json_encode( array(
				'success' => false,
				'message' => esc_html__( 'Agency not found', 'myhome-core' )
			) );
			wp_die();
		}

		foreach ( $users as $agency ) {
			update_user_meta( get_current_user_id(), 'myhome_agency', $agency->ID );
			break;
		}

		echo json_encode( array(
			'success' => true,
			'user'    => \MyHomeCore\Frontend_Panel\User::get_current()->get_data()
		) );
		wp_die();
	}

	public function regenerate_invite_code() {
		$enabled = \MyHomeCore\My_Home_Core()->settings->get( 'agent-agency' );
		if ( empty( $enabled ) ) {
			wp_die();
		}

		$agency = get_user_by( 'ID', get_current_user_id() );
		if ( ! $agency instanceof \WP_User || ! in_array( 'agency', $agency->roles ) ) {
			wp_die();
		}

		$agency   = \MyHomeCore\Frontend_Panel\User::get_current();
		$new_code = $agency->generate_invite_code();
		update_user_meta( get_current_user_id(), 'myhome_agency_invite_code', $new_code );
		echo json_encode( array(
			'success' => true,
			'user'    => \MyHomeCore\Frontend_Panel\User::get_current()->get_data()
		) );
		wp_die();
	}

	public function remove_agent() {
		$enabled = \MyHomeCore\My_Home_Core()->settings->get( 'agent-agency' );
		if ( empty( $enabled ) ) {
			wp_die();
		}

		$agent_id = intval( $_POST['agent_id'] );
		$agent    = get_user_by( 'ID', $agent_id );

		if ( ! $agent instanceof \WP_User || ( ! in_array( 'agent', $agent->roles ) && ! in_array( 'super_agent', $agent->roles ) ) ) {
			wp_die();
		}

		$agency_id = intval( get_user_meta( $agent_id, 'myhome_agency', true ) );
		if ( $agency_id != get_current_user_id() ) {
			wp_die();
		}

		update_user_meta( $agent_id, 'myhome_agency', '' );
		echo json_encode( array(
			'success' => true,
			'user'    => \MyHomeCore\Frontend_Panel\User::get_current()->get_data()
		) );
		wp_die();
	}

	public function invite_agent() {
		$enabled = \MyHomeCore\My_Home_Core()->settings->get( 'agent-agency' );
		if ( empty( $enabled ) ) {
			wp_die();
		}

		$agency_id = get_current_user_id();
		$agency    = get_user_by( 'ID', $agency_id );
		if ( ! $agency instanceof \WP_User || ! in_array( 'agency', $agency->roles ) ) {
			wp_die();
		}

		$agent_email = $_POST['email'];
		$wp_user     = get_user_by( 'email', $agent_email );

		if ( ! $wp_user instanceof \WP_User ) {
			echo json_encode( array(
				'success' => false,
				'message' => esc_html__( 'Agent not found', 'myhome-core' )
			) );
			wp_die();
		}

		if ( ! in_array( 'agent', $wp_user->roles ) && ! in_array( 'super_agent', $wp_user->roles ) ) {
			echo json_encode( array(
				'success' => false,
				'message' => esc_html__( 'Agent not found', 'myhome-core' )
			) );
			wp_die();
		}

		$invites = get_user_meta( $wp_user->ID, 'myhome_agency_invites', true );
		if ( ! is_array( $invites ) ) {
			$invites = array();
		}

		if ( ! in_array( $agency_id, $invites ) ) {
			$invites[] = $agency_id;
		} else {
			echo json_encode( array(
				'success' => false,
				'message' => esc_html__( 'You have already sent the invitation to this agent', 'myhome-core' )
			) );
			wp_die();
		}

		update_user_meta( $wp_user->ID, 'myhome_agency_invites', $invites );

		do_action( 'myhome_agent_invited', $wp_user->ID, $agency_id );

		echo json_encode( array(
			'success' => true,
			'message' => esc_html__( 'Agent invited', 'myhome-core' )
		) );
		wp_die();
	}

	public function accept_invite() {
		$enabled = \MyHomeCore\My_Home_Core()->settings->get( 'agent-agency' );
		if ( empty( $enabled ) ) {
			wp_die();
		}

		$agency_id = intval( $_POST['agency_id'] );
		$wp_user   = get_user_by( 'ID', get_current_user_id() );
		if ( ! $wp_user instanceof \WP_User ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$invites = get_user_meta( $wp_user->ID, 'myhome_agency_invites', true );
		if ( ! in_array( $agency_id, $invites ) ) {
			echo json_encode( array( 'success' => false, esc_html__( 'Agency invite not found', 'myhome-core' ) ) );
			wp_die();
		}

		update_user_meta( $wp_user->ID, 'myhome_agency', $agency_id );

		$key = array_search( $agency_id, $invites );
		unset( $invites[ $key ] );
		$invites = array_values( $invites );
		update_user_meta( $wp_user->ID, 'myhome_agency_invites', $invites );

		echo json_encode( array(
			'success' => true,
			'user'    => \MyHomeCore\Frontend_Panel\User::get_current()->get_data()
		) );
		wp_die();
	}

	public function decline_invite() {
		$enabled = \MyHomeCore\My_Home_Core()->settings->get( 'agent-agency' );
		if ( empty( $enabled ) ) {
			wp_die();
		}

		$agency_id = intval( $_POST['agency_id'] );
		$wp_user   = get_user_by( 'ID', get_current_user_id() );
		if ( ! $wp_user instanceof \WP_User ) {
			echo json_encode( array( 'success' => false ) );
			wp_die();
		}

		$invites = get_user_meta( $wp_user->ID, 'myhome_agency_invites', true );
		if ( ! in_array( $agency_id, $invites ) ) {
			echo json_encode( array( 'success' => false, esc_html__( 'Agency invite not found', 'myhome-core' ) ) );
			wp_die();
		}

		$key = array_search( $agency_id, $invites );
		unset( $invites[ $key ] );
		$invites = array_values( $invites );
		update_user_meta( $wp_user->ID, 'myhome_agency_invites', $invites );

		echo json_encode( array(
			'success' => true,
			'user'    => \MyHomeCore\Frontend_Panel\User::get_current()->get_data(),
			'message' => esc_html__( 'Invite declined successfully', 'myhome-core' )
		) );
		wp_die();
	}

	public function save_search() {
		if ( ! isset( $_POST['search'] ) || empty( $_POST['search'] ) ) {
			return;
		}

		$search         = $_POST['search'];
		$user_id        = get_current_user_id();
		$saved_searches = get_user_meta( $user_id, 'myhome_searches', true );

		if ( ! is_array( $saved_searches ) ) {
			$saved_searches = array();
		}
		$saved_searches[] = $search;

		update_user_meta( $user_id, 'myhome_searches', $saved_searches );
		wp_die();
	}

	public function remove_search() {
		if ( ! isset( $_POST['search'] ) || empty( $_POST['search'] ) ) {
			return;
		}

		$search         = $_POST['search'];
		$user_id        = get_current_user_id();
		$saved_searches = get_user_meta( $user_id, 'myhome_searches', true );

		if ( ! is_array( $saved_searches ) ) {
			$saved_searches = array();
		}

		foreach ( $saved_searches as $key => $s ) {
			if ( $s['name'] == $search['name'] && $s['url'] == $search['url'] ) {
				unset( $saved_searches[ $key ] );
				break;
			}
		}

		update_user_meta( $user_id, 'myhome_searches', $saved_searches );
		wp_die();
	}

	public function add_to_favorite() {
		if ( ! isset( $_POST['isFavorite'] ) || ! isset( $_POST['propertyID'] ) ) {
			return;
		}

		$is_favorite = $_POST['isFavorite'] == 'true';
		$property_id = intval( $_POST['propertyID'] );
		$user_id     = get_current_user_id();

		$favorite_properties = get_user_meta( $user_id, 'myhome_favorite', true );

		if ( ! is_array( $favorite_properties ) ) {
			$favorite_properties = array();
		}

		if ( $is_favorite ) {
			if ( ! in_array( $property_id, $favorite_properties ) ) {
				$favorite_properties[] = $property_id;
			}
		} else {
			$favorite_properties = array_filter( $favorite_properties, function ( $current ) use ( $property_id ) {
				return $current !== $property_id;
			} );
		}

		update_user_meta( $user_id, 'myhome_favorite', $favorite_properties );
		wp_die();
	}

	public function change_password() {
		// check if data provided
		if ( empty( $_POST['password'] ) || ! is_array( $_POST['password'] ) ) {
			echo json_encode( array(
				'success' => false,
				'message' => esc_html__( 'Some required data are missing.', 'myhome-core' )
			) );
			wp_die();
		}

		$password = $_POST['password'];

		// check if required data isn't empty
		if ( empty( $password['old'] ) || empty( $password['new'] ) ) {
			echo json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'Some required data are missing.', 'myhome-core' )
				)
			);
			wp_die();
		}

		// check if old password is correct
		$current_user = User::get_instance( get_current_user_id() );
		if ( ! wp_check_password( $password['old'], $current_user->get_hash(), get_current_user_id() ) ) {
			echo json_encode(
				array(
					'message' => esc_html__( 'Old password isn\'t correct', 'myhome-core' ),
					'success' => false
				)
			);
			wp_die();
		}

		// change password
		wp_set_password( $password['new'], get_current_user_id() );

		wp_set_auth_cookie( $current_user->get_ID() );
		wp_set_current_user( $current_user->get_ID() );

		echo json_encode(
			array(
				'message' => esc_html__( 'Password changed successfully', 'myhome-core' ),
				'success' => true
			)
		);
		wp_die();
	}

	public function action_wp_mail_failed( $wp_error ) {

	}

	// add the action
	public function activate() {
		if ( empty( $_GET['uid'] ) || empty( $_GET['hash'] ) ) {
			wp_die();
		}

		$user_id = intval( $_GET['uid'] );
		$hash    = get_user_meta( $user_id, 'myhome_user_confirm', true );
		$expired = get_user_meta( $user_id, 'myhome_user_confirm_expire', true );

		$page_id = \MyHomeCore\My_Home_Core()->settings->get( 'agent-panel_page' );
		if ( ! empty( $page_id ) ) {
			$panel_url = get_the_permalink( $page_id );
		} else {
			$panel_url = \MyHomeCore\My_Home_Core()->settings->props['mh-agent-panel_link'];
		}

		if ( empty( $hash ) || $hash != $_GET['hash'] || empty( $expired ) || time() > strtotime( $expired ) ) {
			wp_redirect( $panel_url . '#/account-activation-link-expired' );
		} else {
			update_user_meta( $user_id, 'myhome_agent_confirmed', true );
			delete_user_meta( $user_id, 'myhome_user_confirm' );

			do_action( 'myhome_agent_welcome', $user_id );
		}

		wp_set_auth_cookie( $user_id, true );
		$this->check_draft( $user_id );

		$pricing_table_id = \MyHomeCore\My_Home_Core()->settings->get( 'payment_pricing-page' );
		if ( ! empty( $pricing_table_id ) ) {
			$pricing_table_url = get_permalink( $pricing_table_id );
		} else {
			$pricing_table_url = '';
		}

		$redirect = \MyHomeCore\My_Home_Core()->settings->get( 'panel_register-redirect' );
		if ( ! empty( $redirect ) ) {
			if ( $redirect == 'submit_property' ) {
				wp_redirect( $panel_url . '#/submit-property' );
			} elseif ( $redirect == 'panel' ) {
				wp_redirect( $panel_url );
			} elseif ( $redirect == 'pricing_table' && ! empty( $pricing_table_url ) ) {
				wp_redirect( $pricing_table_url );
			} else {
				wp_redirect( $panel_url );
			}
		} elseif ( class_exists( 'WooCommerce' ) ) {
			if ( ! empty( $pricing_table_url ) ) {
				wp_redirect( $pricing_table_url );
			} else {
				wp_redirect( $panel_url );
			}
		} else {
			wp_redirect( $panel_url );
		}
		die();
	}

	public function activate_redirect() {
		$page_id = \MyHomeCore\My_Home_Core()->settings->get( 'agent-panel_page' );
		if ( ! empty( $page_id ) ) {
			$panel_url = get_the_permalink( $page_id );
		} else {
			$panel_url = \MyHomeCore\My_Home_Core()->settings->props['mh-agent-panel_link'];
		}
		if ( class_exists( 'WooCommerce' ) ) {
			$pricing_table_id = \MyHomeCore\My_Home_Core()->settings->get( 'payment_pricing-page' );
			if ( ! empty( $pricing_table_id ) ) {
				$pricing_table_url = get_permalink( $pricing_table_id );
			} else {
				$pricing_table_url = '';
			}
			if ( ! empty( $pricing_table_url ) ) {
				wp_redirect( $pricing_table_url );
			} else {
				wp_redirect( $panel_url );
			}
		} else {
			wp_redirect( $panel_url );
		}
		die();
	}

	public function send_link() {
		if ( ! isset( $_GET['uid'] ) ) {
			return;
		}

		$user_id = intval( $_GET['uid'] );
		$user    = User::get_instance( $user_id );

		if ( $user->is_confirmed() ) {
			return;
		}

		$this->send_register_confirmation_email( $user_id );

		echo json_encode( array( 'success' => true ) );
		wp_die();
	}

	public function send_register_confirmation_email( $user_id ) {
		if ( empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-email_confirmation'] ) ) {
			do_action( 'myhome_agent_welcome', $user_id );

			return;
		}

		$user = User::get_user_by_id( $user_id );
		Agents_Manager::send_activation_link( $user );
	}

	/**
	 * @param User $user
	 *
	 * @return bool
	 */
	public static function send_activation_link( User $user ) {
		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agents-msg_confirm-title'] ) ) {
			$subject = apply_filters(
				'wpml_translate_single_string',
				\MyHomeCore\My_Home_Core()->settings->props['mh-agents-msg_confirm-title'],
				'myhome-core',
				'Confirmation mail - title'
			);
		} else {
			$subject = esc_html__( 'Welcome to MyHome', 'myhome-core' );
		}

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agents-msg_confirm-msg'] ) ) {
			$message = apply_filters(
				'wpml_translate_single_string',
				\MyHomeCore\My_Home_Core()->settings->props['mh-agents-msg_confirm-msg'],
				'myhome-core',
				'Confirmation mail - text'
			);
		} else {
			$message = esc_html__( 'Last step - click link', 'myhome-core' );
		}

		$hash = md5( $user->get_ID() . '-' . time() );
		update_user_meta( $user->get_ID(), 'myhome_user_confirm', $hash );

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent_email_confirmation-expire'] ) ) {
			$hours = \MyHomeCore\My_Home_Core()->settings->props['mh-agent_email_confirmation-expire'];
		} else {
			$hours = 24;
		}

		$expire = date( "Y-m-d H:i:s", strtotime( '+' . $hours . ' hours' ) );
		update_user_meta( $user->get_ID(), 'myhome_user_confirm_expire', $expire );

		$subject = str_replace(
			array( '{{username}}' ),
			array( $user->get_name() ),
			$subject
		);

		$link    = admin_url( 'admin-post.php?action=myhome_user_panel_activate_user&uid=' . $user->get_ID() . '&hash=' . $hash );
		$message = str_replace(
			array( '{{username}}', '{{confirmation_link}}' ),
			array( $user->get_name(), '<a href="' . $link . '">' . $link . '</a>' ),
			$message
		);

		$message = apply_filters( 'myhome_mail_message', $message, 'user_activation' );

		$headers = apply_filters( 'myhome/confirmation_mail/headers', array( 'Content-Type: text/html; charset=UTF-8' ) );

		$message = str_replace( PHP_EOL, '<br />', $message );

		$attachments = apply_filters( 'myhome/confirmation_mail/attachments', [] );

		return wp_mail( $user->get_email(), $subject, $message, $headers, $attachments );
	}

	public function send_welcome_message_email( $user_id ) {
		if ( empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-email_welcome-message'] ) ) {
			return;
		}

		$user = User::get_instance( $user_id );

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agents-msg_welcome-title'] ) ) {
			$subject = apply_filters(
				'wpml_translate_single_string',
				\MyHomeCore\My_Home_Core()->settings->props['mh-agents-msg_welcome-title'],
				'myhome-core',
				'Welcome mail - title'
			);
		} else {
			$subject = esc_html__( 'Welcome mail - title', 'myhome-core' );
		}

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agents-msg_welcome-msg'] ) ) {
			$message = apply_filters(
				'wpml_translate_single_string',
				\MyHomeCore\My_Home_Core()->settings->props['mh-agents-msg_welcome-msg'],
				'myhome-core',
				'Welcome mail - message'
			);
		} else {
			$message = esc_html__( 'Welcome mail - message', 'myhome-core' );
		}
		$subject = str_replace(
			array( '{{username}}' ),
			array( $user->get_name() ),
			$subject
		);

		$message = str_replace(
			array( '{{username}}' ),
			array( $user->get_name() ),
			$message
		);

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		$message = str_replace( PHP_EOL, '<br />', $message );

		wp_mail( $user->get_email(), $subject, $message, $headers );
	}

	public function save_user() {
		if ( ! isset( $_POST['user'] ) || empty( $_POST['user'] ) || ! is_array( $_POST['user'] ) ) {
			wp_die();
		}

		$user_data    = $_POST['user'];
		$wp_user      = _wp_get_current_user();
		$updated_data = array(
			'ID'           => get_current_user_id(),
			'user_login'   => $wp_user->user_login,
			'display_name' => $user_data['display_name'],
			'user_email'   => $user_data['email']
		);

		$set_type = \MyHomeCore\My_Home_Core()->settings->get( 'agent-account_type' );
		if ( ! empty( $set_type ) && isset( $user_data['account_type'] ) && ! empty( $user_data['account_type'] ) && ! in_array( 'administrator', $wp_user->roles ) ) {
			$account_types = Agents_Manager::get_account_types();
			if ( isset( $account_types[ $user_data['account_type'] ] ) ) {
				$updated_data['role'] = $user_data['account_type'];
			} else {
				$initial_role = \MyHomeCore\My_Home_Core()->settings->get( 'agent-initial_role' );
				if ( empty( $initial_role ) ) {
					$initial_role = 'agent';
				}
				$updated_data['role'] = $initial_role;
			}
		}

		wp_update_user( $updated_data );

		if ( isset( $user_data['phone'] ) ) {
			update_user_meta( $wp_user->ID, 'agent_phone', $user_data['phone'] );
		}

		if ( isset( $user_data['image_id'] ) && ! empty( $user_data['image_id'] ) ) {
			update_user_meta( $wp_user->ID, 'agent_image', intval( $user_data['image_id'] ) );
		} else {
			delete_user_meta( $wp_user->ID, 'agent_image' );
		}

		$available_fields = array();
		foreach ( Settings::get_fields() as $field ) {
			$available_fields[] = $field['slug'];
		}

		foreach ( $user_data['fields'] as $field ) {
			if ( ! in_array( $field['slug'], $available_fields ) ) {
				continue;
			}

			update_user_meta( $wp_user->ID, 'agent_' . $field['slug'], $field['value'] );
		}

		if ( isset( $user_data['social'] ) ) {
			foreach ( Social_Icon::get_icons() as $icon ) {
				if ( isset( $user_data['social'][ $icon ] ) && isset( $user_data['social'][ $icon ]['value'] ) ) {
					update_user_meta( $wp_user->ID, 'agent_' . $icon, $user_data['social'][ $icon ]['value'] );
				}
			}
		}

		$wp_user = new \WP_User( get_current_user_id() );
		$user    = new \MyHomeCore\Frontend_Panel\User( $wp_user );

		echo json_encode( array(
			'success' => true,
			'user'    => $user->get_data(),
			'message' => esc_html__( 'Profile changes saved successfully', 'myhome-core' )
		) );
		wp_die();
	}

	/**
	 * @return array
	 */
	public static function get_account_types() {
		$types         = array(
			'agent'  => esc_html__( 'Agent', 'myhome-core' ),
			'agency' => esc_html__( 'Agency', 'myhome-core' ),
			'buyer'  => esc_html__( 'Buyer', 'myhome-core' ),
		);
		$enabled_types = array();

		foreach ( $types as $type_key => $type ) {
			if ( ! isset( \MyHomeCore\My_Home_Core()->settings->props[ 'mh-agent_role-' . $type_key ] ) || ! empty( \MyHomeCore\My_Home_Core()->settings->props[ 'mh-agent_role-' . $type_key ] ) ) {
				$role                       = \MyHomeCore\My_Home_Core()->settings->get( 'agent_role-' . $type_key . '-display' );
				$role                       = apply_filters( 'wpml_translate_single_string', $role, 'myhome-core', 'Role ' . $role );
				$enabled_types[ $type_key ] = $role;
			}
		}

		return $enabled_types;
	}

	public function save_search_check() {
		if ( ! current_user_can( 'manage_options' ) && ! isset( $_GET['hash'] ) ) {
			wp_die();
		} elseif ( isset( $_GET['hash'] ) && ! empty( $_GET['hash'] ) ) {
			$hash = Save_Search::get_hash();
			if ( $_GET['hash'] != $hash ) {
				wp_die();
			}
		}

		$save_search = new Save_Search();
		$save_search->init();

		if ( is_user_logged_in() ) {
			wp_redirect( admin_url( 'admin.php?page=myhome_saved_searches' ) );
			die();
		}
	}

	/**
	 * @param int $user_id
	 */
	public function set_default_settings( $user_id ) {
		$featured_number   = intval( \MyHomeCore\My_Home_Core()->settings->get( 'initial_featured_number' ) );
		$properties_number = intval( \MyHomeCore\My_Home_Core()->settings->get( 'initial_properties_number' ) );
		update_user_meta( $user_id, 'package_featured', $featured_number );
		update_user_meta( $user_id, 'package_properties', $properties_number );
	}

	private function check_draft( $user_id ) {
		if ( ! isset( $_COOKIE['myhome_frontend_draft_id'] ) || ! isset( $_COOKIE['myhome_frontend_draft'] ) ) {
			return;
		}

		$draft_id   = intval( $_COOKIE['myhome_frontend_draft_id'] );
		$draft_hash = md5( $_COOKIE['myhome_frontend_draft'] );

		$draft = get_post( $draft_id );
		if ( ! $draft instanceof \WP_Post ) {
			return;
		}

		$original_draft_hash = get_post_meta( $draft_id, 'myhome_frontend_draft', true );
		if ( empty( $original_draft_hash ) || $original_draft_hash !== $draft_hash ) {
			return;
		}

		setcookie( 'myhome_frontend_draft_id', '', time() + 60 * 60, '/' );
		setcookie( 'myhome_frontend_draft', '', time() + 60 * 60, '/' );

		update_post_meta( $draft_id, 'myhome_frontend_draft', '' );

		wp_update_post( array(
			'ID'          => $draft_id,
			'post_author' => $user_id
		) );
	}

}