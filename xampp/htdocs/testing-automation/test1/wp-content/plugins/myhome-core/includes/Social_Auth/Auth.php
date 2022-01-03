<?php

namespace MyHomeCore\Social_Auth;


use Hybridauth\Provider\Facebook;
use Hybridauth\Provider\Google;
use Hybridauth\Provider\Instagram;
use Hybridauth\Provider\LinkedIn;
use Hybridauth\Provider\Twitter;
use Hybridauth\Provider\Yahoo;

/**
 * Class Auth
 * @package MyHomeCore\Social_Auth
 */
class Auth {

	/**
	 * @var array
	 */
	private $social_networks = array();

	/**
	 * @var array
	 */
	private $enabled_social_networks = array();

	/**
	 * Auth constructor.
	 */
	public function __construct() {
		$social_login = \MyHomeCore\My_Home_Core()->settings->get( 'social_login' );
		if ( empty( $social_login ) ) {
			return;
		}

		$this->social_networks = array(
			'facebook' => esc_html__( 'Facebook', 'myhome-core' ),
			'twitter'  => esc_html__( 'Twitter', 'myhome-core' ),
			'google'   => esc_html__( 'Google', 'myhome-core' ),
			//			'instagram' => esc_html__( 'Instagram', 'myhome-core' ),
			'linkedin' => esc_html__( 'LinkedIn', 'myhome-core' ),
			//			'yahoo'     => esc_html__( 'Yahoo', 'myhome-core' )
		);

		foreach ( $this->social_networks as $network => $network_name ) {
			$is_enabled = \MyHomeCore\My_Home_Core()->settings->get( 'social_login-' . $network );
			if ( empty( $is_enabled ) ) {
				continue;
			}

			$this->enabled_social_networks[ $network ] = $network_name;

			if ( $network == 'linkedin' ) {
				add_action( 'init', array( $this, 'linkedin_callback' ) );
			} elseif ( $network == 'twitter' ) {
				add_action( 'init', array( $this, 'twitter_callback' ) );
			} else {
				add_action( 'admin_post_nopriv_myhome_social_login_callback_' . $network, array(
					$this,
					'login_callback'
				) );
			}
		}
		add_action( 'admin_post_nopriv_myhome_social_login', array( $this, 'login' ) );

		require_once MYHOME_CORE_DIR . '/includes/libs/hybridauth/autoload.php';
	}

	public function linkedin_callback() {
		add_feed( 'linkedin-callback', function () {
			$_GET['network'] = 'linkedin';
			$_GET['type']    = 'linkedin';
			$_GET['action']  = 'linkedin';
			$this->login_callback();
		} );
	}

	public function twitter_callback() {
		add_feed( 'twitter-callback', function () {
			$_GET['network'] = 'twitter';
			$_GET['type']    = 'twitter';
			$_GET['action']  = 'twitter';
			$this->login_callback();
		} );
	}

	/**
	 * @return array
	 */
	public function get_networks() {
		return $this->social_networks;
	}

	/**
	 * @return array
	 */
	public function get_enabled_networks() {
		return $this->enabled_social_networks;
	}

	public function login() {
		$panel_id = \MyHomeCore\My_Home_Core()->settings->get( 'agent-panel_page' );
		if ( ! empty( $panel_id ) ) {
			$panel_url = get_the_permalink( $panel_id );
		} else {
			$panel_url = \MyHomeCore\My_Home_Core()->settings->get( 'agent-panel_link' );
		}

		if ( ! isset( $_GET['type'] ) || empty( $_GET['type'] ) ) {
			wp_redirect( $panel_url . '/#/login-error' );
			die();
		}
		$network = sanitize_text_field( $_GET['type'] );

		if ( $network == 'yahoo' ) {
			$config = array(
				'callback' => admin_url( 'admin-post.php?action=myhome_social_login_callback_' . $network ),
				'keys'     => array(
					'id'     => trim( \MyHomeCore\My_Home_Core()->settings->get( 'social_login-' . $network . '-id' ) ),
					'secret' => trim( \MyHomeCore\My_Home_Core()->settings->get( 'social_login-' . $network . '-secret' ) )
				)
			);
		} else {
			$config = array(
				'callback' => admin_url( 'admin-post.php?action=myhome_social_login_callback_' . $network ),
				'keys'     => array(
					'key'    => trim( \MyHomeCore\My_Home_Core()->settings->get( 'social_login-' . $network . '-id' ) ),
					'secret' => trim( \MyHomeCore\My_Home_Core()->settings->get( 'social_login-' . $network . '-secret' ) )
				)
			);
		}

		if ( $network == 'linkedin' ) {
			$config['callback'] = site_url() . '/linkedin-callback/';
		} elseif ( $network == 'twitter' ) {
			$config['callback'] = site_url() . '/twitter-callback/';
		}

		try {
			switch ( $network ) {
				case 'facebook':
					$adapter = new Facebook( $config );
					break;
				case 'twitter':
					$adapter = new Twitter( $config );
					break;
				case 'google':
					$adapter = new Google( $config );
					break;
				case 'instagram':
					$adapter = new Instagram( $config );
					break;
				case 'linkedin':
					$adapter = new LinkedIn( $config );
					break;
				case 'yahoo':
					$adapter = new Yahoo( $config );
					break;
				default:
					throw new \Exception( esc_html__( 'Social Auth - Something went wrong', 'myhome-core' ) );
			}

			if ( $adapter->authenticate() ) {
				$adapter->disconnect();

				if ( $adapter->hasAccessTokenExpired() ) {
					$adapter->refreshAccessToken();
				}

				$user_profile = $adapter->getUserProfile();
				if ( isset( $user_profile->emailVerified ) && ! empty( $user_profile->emailVerified ) ) {
					$email = $user_profile->emailVerified;
				} elseif ( isset( $user_profile->email ) && ! empty( $user_profile->email ) ) {
					$email = $user_profile->email;
				} else {
					wp_redirect( $panel_url . '/#/login-error' );
					die();
				}

				$wp_user = get_user_by( 'email', $email );
				if ( $wp_user ) {
					wp_set_auth_cookie( $wp_user->ID );
					$this->check_draft( $wp_user->ID );
				} else {
					$this->register( $user_profile, $panel_url );
				}

				wp_redirect( $panel_url . '?t=' . time() );
				die();
			}
		} catch ( \Exception $e ) {
			wp_redirect( $panel_url . '/#/login-error' );
			die();
		}
	}

	public function login_callback() {
		$panel_id = \MyHomeCore\My_Home_Core()->settings->get( 'agent-panel_page' );
		if ( ! empty( $panel_id ) ) {
			$panel_url = get_the_permalink( $panel_id );
		} else {
			$panel_url = \MyHomeCore\My_Home_Core()->settings->get( 'agent-panel_link' );
		}

		switch ( $_GET['action'] ) {
			case strpos( $_GET['action'], 'facebook' ) !== false:
				$network = 'facebook';
				break;
			case strpos( $_GET['action'], 'twitter' ) !== false:
				$network = 'twitter';
				break;
			case strpos( $_GET['action'], 'google' ) !== false:
				$network = 'google';
				break;
			case strpos( $_GET['action'], 'instagram' ) !== false:
				$network = 'instagram';
				break;
			case strpos( $_GET['action'], 'linkedin' ) !== false:
				$network = 'linkedin';
				break;
			case strpos( $_GET['action'], 'yahoo' ) !== false:
				$network = 'yahoo';
				break;
			default:
				$network = '';
		}

		$config = array(
			'callback' => admin_url( 'admin-post.php?action=myhome_social_login_callback_' . $network ),
			'keys'     => array(
				'key'    => trim( \MyHomeCore\My_Home_Core()->settings->get( 'social_login-' . $network . '-id' ) ),
				'secret' => trim( \MyHomeCore\My_Home_Core()->settings->get( 'social_login-' . $network . '-secret' ) )
			)
		);

		if ( $network == 'linkedin' ) {
			$config['callback'] = site_url() . '/linkedin-callback/';
		} elseif ( $network == 'twitter' ) {
			$config['callback'] = site_url() . '/twitter-callback/';
		}

		try {
			switch ( $network ) {
				case 'facebook':
					$adapter = new Facebook( $config );
					break;
				case 'twitter':
					$adapter = new Twitter( $config );
					break;
				case 'google':
					$adapter = new Google( $config );
					break;
				case 'instagram':
					$adapter = new Instagram( $config );
					break;
				case 'linkedin':
					$adapter = new LinkedIn( $config );
					break;
				case 'yahoo':
					$adapter = new Yahoo( $config );
					break;
				default:
					throw new \Exception( esc_html__( 'Social Auth - Something went wrong', 'myhome-core' ) );
			}


			$adapter->authenticate();
			if ( $adapter->isConnected() ) {
				$user_profile = $adapter->getUserProfile();
				if ( isset( $user_profile->emailVerified ) && ! empty( $user_profile->emailVerified ) ) {
					$email = $user_profile->emailVerified;
				} elseif ( isset( $user_profile->email ) && ! empty( $user_profile->email ) ) {
					$email = $user_profile->email;
				} else {
					wp_redirect( $panel_url . '/#/login-error' );
					die();
				}

				$wp_user = get_user_by( 'email', $email );
				if ( $wp_user ) {
					wp_set_auth_cookie( $wp_user->ID );
					$this->check_draft( $wp_user->ID );
				} else {
					$this->register( $user_profile, $panel_url );
				}

				wp_redirect( $panel_url . '?t=' . time() );
				die();
			}

			wp_redirect( $panel_url . '/#/login-error' );
			die();
		} catch ( \Exception $e ) {
			wp_redirect( $panel_url . '/#/login-error' );
			die();
		}
	}

	private function register( $user_profile, $panel_url ) {
		$is_registration_open = \MyHomeCore\My_Home_Core()->settings->get( 'agent-registration' );
		if ( empty( $is_registration_open ) ) {
			wp_redirect( $panel_url . '/#/registration-closed' );
			die();
		}

		if ( isset( $user_profile->emailVerified ) && ! empty( $user_profile->emailVerified ) ) {
			$email = $user_profile->emailVerified;
		} elseif ( isset( $user_profile->email ) && ! empty( $user_profile->email ) ) {
			$email = $user_profile->email;
		} else {
			wp_redirect( $panel_url . '/#/login-error' );
			die();
		}

		$password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
		$user_id  = wp_create_user( $email, $password, $email );

		if ( is_wp_error( $user_id ) ) {
			wp_redirect( $panel_url . '/#/login-error' );
			die();
		}

		do_action( 'myhome_agent_created', $user_id );

		$initial_role = \MyHomeCore\My_Home_Core()->settings->get( 'agent-initial_role' );
		if ( empty( $initial_role ) ) {
			$initial_role = 'agent';
		}

		$user_id = wp_update_user( array(
			'ID'           => $user_id,
			'display_name' => $user_profile->displayName,
			'first_name'   => $user_profile->firstName,
			'last_name'    => $user_profile->lastName,
			'role'         => $initial_role
		) );

		if ( is_wp_error( $user_id ) ) {
			wp_redirect( $panel_url . '/#/login-error' );
			die();
		}

		wp_set_auth_cookie( $user_id );
		$this->check_draft( $user_id );
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