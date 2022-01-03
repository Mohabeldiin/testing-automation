<?php

namespace MyHomeCore\Users;


use MyHomeCore\Users\Fields\Settings;

/**
 * Class User_Settings
 * @package MyHomeCore\Users
 */
class User_Settings {

	/**
	 * @var Settings
	 */
	private $fields_settings;

	/**
	 * @var User_Permissions
	 */
	private $permissions;

	/**
	 * User_Settings constructor.
	 */
	public function __construct() {
		add_filter( 'wp_dropdown_users_args', array( $this, 'users_dropdown' ) );
		add_filter( 'author_link', array( $this, 'rewrite_url' ), 100, 2 );
		add_action( 'init', array( $this, 'user_base_url' ) );

		add_action( 'redux/options/myhome_redux/saved', array( $this, 'reload_roles' ) );
		add_action( 'redux/options/myhome_redux/reset', array( $this, 'reload_roles' ) );

		$this->fields_settings = new Settings();
		$this->permissions     = new User_Permissions();
		$this->register_fields();
	}

	public function reload_roles() {
		self::create_roles();
	}

	public function user_base_url() {
		global $wp_rewrite;
		$user_roles = array();
		$roles      = array( 'agent', 'agency', 'other' );
		foreach ( $roles as $role ) {
			$key   = 'agent_' . $role . '-base';
			$value = \MyHomeCore\My_Home_Core()->settings->get( $key );
			if ( ! empty( $value ) ) {
				$user_roles[] = $value;
			}
		}

		if ( empty( $user_roles ) ) {
			$user_roles[] = 'author';
		}

		add_rewrite_tag( '%author_level%', '(' . implode( '|', $user_roles ) . ')' );
		$wp_rewrite->author_base = '%author_level%';
	}

	public function rewrite_url( $link, $user_id ) {
		$wp_user = get_user_by( 'ID', $user_id );
		if ( ! $wp_user instanceof \WP_User ) {
			return $link;
		}

		if ( in_array( 'agent', $wp_user->roles ) ) {
			$value = \MyHomeCore\My_Home_Core()->settings->get( 'agent_agent-base' );
			if ( empty( $value ) ) {
				$value = 'author';
			}

			$link = str_replace( '%author_level%', $value, $link );

			return str_replace( '%author_level%', $value, $link );
		} elseif ( in_array( 'agency', $wp_user->roles ) ) {
			$value = \MyHomeCore\My_Home_Core()->settings->get( 'agent_agency-base' );
			if ( empty( $value ) ) {
				$value = 'author';
			}

			return str_replace( '%author_level%', $value, $link );
		}

		$value = \MyHomeCore\My_Home_Core()->settings->get( 'agent_other-base' );
		if ( empty( $value ) ) {
			$value = 'author';
		}

		return str_replace( '%author_level%', $value, $link );
	}

	/**
	 * @param array $query_args
	 *
	 * @return array
	 */
	public function users_dropdown( $query_args ) {
		$query_args['who'] = '';

		return $query_args;
	}

	public function register_fields() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		$fields = array(
			'myhome_agent_image'     => array(
				'key'   => 'myhome_agent_image',
				'label' => esc_html__( 'Image', 'myhome-core' ),
				'name'  => 'agent_image',
				'type'  => 'image',
			),
			'myhome_agent_phone'     => array(
				'key'   => 'myhome_agent_phone',
				'label' => esc_html__( 'Phone', 'myhome-core' ),
				'name'  => 'agent_phone',
				'type'  => 'text',
			),
			'myhome_agent_facebook'  => array(
				'key'   => 'myhome_agent_facebook',
				'label' => esc_html__( 'Facebook', 'myhome-core' ),
				'name'  => 'agent_facebook',
				'type'  => 'text',
			),
			'myhome_agent_twitter'   => array(
				'key'   => 'myhome_agent_twitter',
				'label' => esc_html__( 'Twitter', 'myhome-core' ),
				'name'  => 'agent_twitter',
				'type'  => 'text',
			),
			'myhome_agent_instagram' => array(
				'key'   => 'myhome_agent_instagram',
				'label' => esc_html__( 'Instagram', 'myhome-core' ),
				'name'  => 'agent_instagram',
				'type'  => 'text',
			),
			'myhome_agent_linkedin'  => array(
				'key'   => 'myhome_agent_linkedin',
				'label' => esc_html__( 'Linkedin', 'myhome-core' ),
				'name'  => 'agent_linkedin',
				'type'  => 'text',
			),
		);

//		$moderation = \MyHomeCore\My_Home_Core()->settings->get( 'agent_moderation' );
//		if ( ! empty( $moderation ) ) {
//			$fields[] = array(
//				'key'          => 'myhome_myhome_accepted',
//				'label'        => esc_html__( 'User manually approved', 'myhome-core' ),
//				'instructions' => esc_html__( 'Check is required to display user profile because MyHome Theme > User > User moderation is on', 'myhome-core' ),
//				'name'         => 'myhome_accepted',
//				'type'         => 'true_false'
//			);
//		}

		$fields[] = array(
			'key'   => 'myhome_myhome_agent_confirmed',
			'label' => esc_html__( 'User clicked email confirmation link', 'myhome-core' ),
			'name'  => 'myhome_agent_confirmed',
			'type'  => 'true_false'
		);

		foreach ( Settings::get_fields() as $field ) {
			$fields[] = array(
				'key'   => 'myhome_agent_' . $field['slug'],
				'label' => $field['name'],
				'name'  => 'agent_' . $field['slug'],
				'type'  => 'text'
			);
		}

		acf_add_local_field_group(
			array(
				'key'      => 'myhome_agent',
				'title'    => esc_html__( 'MH User', 'myhome-core' ),
				'location' => array(
					array(
						array(
							'param'    => 'user_role',
							'operator' => '==',
							'value'    => 'agent',
						)
					),
					array(
						array(
							'param'    => 'user_role',
							'operator' => '==',
							'value'    => 'super_agent',
						)
					),
					array(
						array(
							'param'    => 'user_role',
							'operator' => '==',
							'value'    => 'agency'
						)
					),
					array(
						array(
							'param'    => 'user_role',
							'operator' => '==',
							'value'    => 'buyer'
						)
					),
					array(
						array(
							'param'    => 'user_role',
							'operator' => '==',
							'value'    => 'subscriber'
						)
					),
					array(
						array(
							'param'    => 'user_role',
							'operator' => '==',
							'value'    => 'editor'
						)
					),
					array(
						array(
							'param'    => 'user_role',
							'operator' => '==',
							'value'    => 'administrator',
						)
					)
				),
				'fields'   => $fields
			)
		);

		$payments_enabled = \MyHomeCore\My_Home_Core()->settings->get( 'payment' );
		if ( empty( $payments_enabled ) ) {
			return;
		}

		$fields = array(
			array(
				'key'           => 'myhome_package_properties',
				'name'          => 'package_properties',
				'label'         => esc_html__( 'Properties number', 'myhome-core' ),
				'type'          => 'number',
				'default_value' => 0
			),
			array(
				'key'           => 'myhome_package_featured',
				'name'          => 'package_featured',
				'label'         => esc_html__( 'Featured number', 'myhome-core' ),
				'type'          => 'number',
				'default_value' => 0
			),
		);

		acf_add_local_field_group(
			array(
				'key'      => 'myhome_agent_package',
				'title'    => esc_html__( 'MyHome Package', 'myhome-core' ),
				'location' => array(
					array(
						array(
							'param'    => 'user_role',
							'operator' => '==',
							'value'    => 'agent',
						)
					),
					array(
						array(
							'param'    => 'user_role',
							'operator' => '==',
							'value'    => 'super_agent',
						)
					),
					array(
						array(
							'param'    => 'user_role',
							'operator' => '==',
							'value'    => 'agency'
						)
					),
					array(
						array(
							'param'    => 'user_role',
							'operator' => '==',
							'value'    => 'administrator',
						)
					)
				),
				'fields'   => $fields
			)
		);
	}

	public static function create_roles() {
		$options         = get_option( 'myhome_redux' );
		$disable_backend = isset( $options['mh-agent-disable_backend'] ) && ! empty( $options['mh-agent-disable_backend'] );
		remove_role( 'agent' );
		add_role(
			'agent', esc_html__( 'Agent', 'myhome-core' ), array(
				'read'                   => ! $disable_backend,
				'edit_posts'             => ! $disable_backend,
				'edit_published_posts'   => ! $disable_backend,
				'delete_posts'           => ! $disable_backend,
				'delete_private_posts'   => ! $disable_backend,
				'delete_published_posts' => ! $disable_backend,
				'publish_posts'          => ! $disable_backend,
				'create_posts'           => ! $disable_backend,
				'level_0'                => ! $disable_backend,
				'level_1'                => ! $disable_backend,
				'upload_files'           => true
			)
		);

		remove_role( 'agency' );
		add_role(
			'agency', esc_html__( 'Agency', 'myhome-core' ), array(
				'read'                   => ! $disable_backend,
				'edit_posts'             => ! $disable_backend,
				'edit_published_posts'   => ! $disable_backend,
				'delete_posts'           => ! $disable_backend,
				'publish_posts'          => ! $disable_backend,
				'create_posts'           => ! $disable_backend,
				'delete_private_posts'   => ! $disable_backend,
				'delete_published_posts' => ! $disable_backend,
				'level_0'                => ! $disable_backend,
				'level_1'                => ! $disable_backend,
				'upload_files'           => true
			)
		);

		remove_role( 'buyer' );
		add_role(
			'buyer', esc_html__( 'Buyer', 'myhome-core' ), array(
				'read'                 => ! $disable_backend,
				'edit_posts'           => ! $disable_backend,
				'edit_published_posts' => ! $disable_backend,
				'delete_posts'         => ! $disable_backend,
				'publish_posts'        => ! $disable_backend,
				'create_posts'         => ! $disable_backend,
				'level_0'              => ! $disable_backend,
				'level_1'              => ! $disable_backend,
				'upload_files'         => true
			)
		);

		remove_role( 'super_agent' );
		add_role(
			'super_agent', esc_html__( 'Super Agent', 'myhome-core' ), array(
				'read'                   => true,
				'edit_posts'             => true,
				'delete_posts'           => true,
				'publish_posts'          => true,
				'edit_others_posts'      => true,
				'upload_files'           => true,
				'delete_others_posts'    => true,
				'delete_private_posts'   => true,
				'delete_published_posts' => true,
				'edit_private_posts'     => true,
				'edit_published_posts'   => true,
				'manage_categories'      => true,
				'moderate_comments'      => true,
				'read_private_posts'     => true,
			)
		);
	}

}