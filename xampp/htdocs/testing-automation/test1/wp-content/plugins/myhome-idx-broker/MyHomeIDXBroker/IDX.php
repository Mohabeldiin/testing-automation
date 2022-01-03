<?php

namespace MyHomeIDXBroker;


use MyHomeCore\Estates\Estate;
use MyHomeCore\Users\User;

/**
 * Class IDX
 * @package MyHomeIDXBroker
 */
class IDX {

	/**
	 * @var Options
	 */
	public $options;

	private static $instance = false;
	public static $is_crone = false;

	private $auto_setup;

	/**
	 * @return IDX
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function init() {
		add_filter( 'upload_mimes', function ( $mimes ) {
			$mimes['.jpg'] = 'application/octet-stream';

			return $mimes;
		} );
		$this->options    = new Options();
		$this->auto_setup = new Auto_Setup();

		add_action( 'admin_menu', array( $this, 'add_menu' ), 99 );
		add_action( 'init', array( $this, 'load_text_domain' ) );

		add_action( 'admin_post_myhome_idx_broker_import_agents', array( $this, 'import_agents' ) );
		add_action( 'admin_post_myhome_idx_broker_import_fields', array( $this, 'import_fields' ) );
		add_action( 'admin_post_myhome_idx_broker_save_fields', array( $this, 'save_fields' ) );
		add_action( 'admin_post_myhome_idx_broker_save_options', array( $this, 'save_options' ) );
		add_action( 'admin_post_myhome_idx_broker_save_mls_ids', array( $this, 'save_mls_ids' ) );
		add_action( 'wp_ajax_myhome_idx_broker_import_init', array( $this, 'import_init' ) );
		add_action( 'wp_ajax_myhome_idx_broker_import_job', array( $this, 'import_job' ) );
		add_action( 'wp_ajax_myhome_idx_broker_generate_thumbnails', array( $this, 'generate_thumbnails' ) );
		add_action( 'upload_mimes', array( $this, 'mime_types' ) );
		add_action( 'admin_post_clear_cache_button', array( $this, 'clear_cache_button' ) );
//		add_action( 'admin_notices', array( $this, 'api_limit' ) );

		add_action( 'wp_head', static function () {
			?>
            <style>
                @font-face {
                    font-family: "Flaticon";
                    src: url("https://myhometheme.net/main/wp-content/themes/myhome/assets/fonts/Flaticon.eot");
                    src: url("https://myhometheme.net/main/wp-content/themes/myhome/assets/fonts/Flaticon.eot?#iefix") format("embedded-opentype"),
                    url("https://myhometheme.net/main/wp-content/themes/myhome/assets/fonts/Flaticon.woff") format("woff"),
                    url("https://myhometheme.net/main/wp-content/themes/myhome/assets/fonts/Flaticon.ttf") format("truetype"),
                    url("https://myhometheme.net/main/wp-content/themes/myhome/assets/fonts/Flaticon.svg#Flaticon") format("svg");
                }

                @media screen and (-webkit-min-device-pixel-ratio: 0) {
                    @font-face {
                        font-family: "Flaticon";
                        src: url("https://myhometheme.net/main/wp-content/themes/myhome/assets/fonts/Flaticon.svg#Flaticon") format("svg");
                    }
                }
            </style>
			<?php
		} );

		add_filter( 'myhome_listing_url', function ( $url, $listing ) {
			if ( empty( My_Home_IDX_Broker()->options->get( 'idx_broker_listing_links' ) ) ) {
				return $url;
			}

			/* @var Estate $listing */
			$idxUrl = get_post_meta( $listing->get_ID(), 'idx_url', true );
			if ( empty( $idxUrl ) ) {
				return $url;
			}

			return $idxUrl;
		}, 10, 2 );

		add_filter( 'myhome_agent_url', function ( $url, $agent ) {
			if ( empty( My_Home_IDX_Broker()->options->get( 'idx_broker_agent_links' ) ) ) {
				return $url;
			}

			/* @var User $agent */

			$idxUrl = get_user_meta( $agent->get_ID(), 'idx_url', true );
			if ( empty( $idxUrl ) ) {
				return $url;
			}

			return My_Home_IDX_Broker()->options->get( 'idx_url' ) . $idxUrl;
		}, 10, 2 );

		if ( is_admin() ) {
			$auto_setup = get_option( 'myhome_idx_broker_auto_setup' );
			if ( ! empty( $auto_setup ) ) {
				add_action( 'admin_notices', function () {
					?>
                    <div class="notice notice-success notice-auto-setup">
                        <p><?php esc_html_e( 'Pages has been imported', 'myhome-idx-broker' ); ?></p>
                    </div>
					<?php
				} );
				update_option( 'myhome_idx_broker_auto_setup', 0 );
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
			$api_key = My_Home_IDX_Broker()->options->get( 'api_key' );
			if ( ! empty( $api_key ) ) {
				$this->register_fields();
			}
		}

		add_action( 'admin_post_nopriv_myhome_idx_broker_cron_init', array( $this, 'cron_init' ) );
		add_action( 'admin_post_myhome_idx_broker_cron_init', array( $this, 'cron_init' ) );
		add_action( 'admin_post_nopriv_myhome_idx_broker_cron_job', array( $this, 'cron_job' ) );
		add_action( 'admin_post_myhome_idx_broker_cron_job', array( $this, 'cron_job' ) );
		add_action( 'admin_post_myhome_idx_broker_hash', array( $this, 'regenerate_hash' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ), 20 );
		add_action( 'init', array( $this, 'check_idx_broker_config' ) );
		//		add_action( 'save_post_page', array( $this, 'save_page' ), 100 );
		add_action( 'admin_post_myhome_idx_broker_clear_cache', array( $this, 'clear_cache' ) );
		add_action( 'save_post_page', array( $this, 'clear_wrapper_cache' ) );
	}

	public function clear_cache() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Something went wrong', 'myhome-idx-broker' ) );
		}

		delete_transient( 'idx_broker_saved_links' );
		delete_transient( 'idx_broker_system_links' );
		delete_transient( 'idx_broker_widgets' );

		wp_redirect( admin_url( 'admin.php?page=myhome_idx_broker' ) );
		die();
	}

	public function check_idx_broker_config() {
		$check = get_option( 'check_idx_broker_config' );
		if ( is_null( $check ) || empty( $check ) ) {
			$options = get_option( Options::OPTION_KEY );
			if ( ! isset( $options['load_style'] ) ) {
				$options['load_style'] = 1;
				update_option( Options::OPTION_KEY, $options );
			}
			update_option( 'check_idx_broker_config', 1, 'yes' );
		}
	}

	public function load_scripts() {
		wp_enqueue_style( 'myhome-idx-broker', plugins_url( MY_HOME_IDX_PATH . '/assets/css/main.css' ), array(), '2.1.23' );

		wp_enqueue_style(
			'myhome-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css', array(), My_Home_Theme()->version
		);
	}

	public function generate_thumbnails() {
		$importer = new Importer();
		$importer->generate_thumbnails();
		wp_die();
	}

	public function mime_types( $mimes = array() ) {
		$mimes['jpg'] = "image/jpeg";

		return $mimes;
	}

	public function scripts() {
		if ( isset( $_GET['page'] ) && strpos( $_GET['page'], 'myhome_idx_broker_properties' ) !== false ) {
			wp_enqueue_script( 'myhome-idx-broker', plugins_url( MY_HOME_IDX_PATH . '/assets/js/build.js' ), array(), false, true );
		}

		wp_enqueue_style( 'myhome-idx-broker', plugins_url( MY_HOME_IDX_PATH . '/assets/css/style.css' ), array(), '2.1.23' );

	}

	public function load_text_domain() {
		load_plugin_textdomain( 'myhome-idx-broker', false, MY_HOME_IDX_PATH . '/languages' );
	}

	public function add_menu() {
		add_menu_page(
			esc_html__( 'MyHome IDX Broker', 'myhome-idx-broker' ),
			esc_html__( 'MyHome IDX Broker', 'myhome-idx-broker' ),
			'administrator',
			'myhome_idx_broker',
			array( $this, 'admin_page' ),
			'',
			3
		);
		$api_key = My_Home_IDX_Broker()->options->get( 'api_key' );

		$pages = array(
			array(
				'title' => esc_html__( 'Settings', 'myhome-idx-broker' ),
				'slug'  => 'settings'
			),
			array(
				'title' => esc_html__( 'Live MLS feed', 'myhome-idx-broker' ),
				'slug'  => 'mls'
			),
			array(
				'title' => esc_html__( 'Import listings assigned to your account', 'myhome-idx-broker' ),
				'slug'  => 'properties'
			)
		);

		foreach ( $pages as $page ) {
			add_submenu_page(
				'myhome_idx_broker',
				$page['title'],
				$page['title'],
				'administrator',
				'myhome_idx_broker_' . $page['slug'],
				array( $this, $page['slug'] . '_page' )
			);
		}
	}

	public function admin_page() {
		require MY_HOME_IDX_VIEWS . 'admin-page.php';
	}

	public function properties_page() {
		require MY_HOME_IDX_VIEWS . 'import-page.php';
	}

	public function agents_page() {
		require MY_HOME_IDX_VIEWS . 'agents-page.php';
	}

	public function fields_page() {
		require MY_HOME_IDX_VIEWS . 'fields-page.php';
	}

	public function mls_page() {
		require MY_HOME_IDX_VIEWS . 'feed-page.php';
	}

	public function settings_page() {
		require MY_HOME_IDX_VIEWS . 'settings-page.php';
	}

	public function import_agents() {
		$agents = new Agents();
		$agents->import();

		wp_redirect( admin_url( 'admin.php?page=myhome_idx_broker_properties' ) );
		exit;
	}

	public
	function import_init() {
		$importer = new Importer();
		$importer->init();
		wp_die();
	}

	public function import_job() {
		$importer = new Importer();
		$importer->job();
		wp_die();
	}

	public function import_fields() {
		$fields = new Fields();
		$fields->import();

		wp_redirect( admin_url( 'admin.php?page=myhome_idx_broker_properties' ) );
		exit;
	}

	public function save_mls_ids() {
		$this->check_if_allowed( 'myhome_idx_broker_update_mls' );

		if ( ! isset( $_POST['mls_ids'] ) ) {
			return;
		}

		MLS::save();
		$this->refresh_account_info( false );
		wp_redirect( admin_url( 'admin.php?page=myhome_idx_broker_properties' ) );
		exit;
	}

	public function save_fields() {
		$fields = new Fields();
		$fields->save();

		wp_redirect( admin_url( 'admin.php?page=myhome_idx_broker_properties' ) );
		exit;
	}

	public function save_options() {
		$this->check_if_allowed( 'myhome_idx_broker_update_options' );

		$api_key = My_Home_IDX_Broker()->options->get( 'api_key' );

		if ( isset( $_POST['options'] ) && ! empty( $_POST['options'] ) ) {
			if ( ! isset( $_POST['options']['update_all_data'] ) ) {
				$_POST['options']['update_all_data'] = 0;
			}
			My_Home_IDX_Broker()->options->save( $_POST['options'] );
		}

		if ( isset( $_POST['options']['api_key'] ) && ! empty( $_POST['options']['api_key'] ) ) {
			if ( $api_key != $_POST['options']['api_key'] ) {
				$this->refresh_account_info();
			}

			add_filter( 'mod_rewrite_rules', function ( $rules ) {
				ob_start();
				?>
                # BEGIN MyHome IDX Broker
                <FilesMatch "\.(ttf|otf|eot|woff)$">
                <IfModule mod_headers.c>
                    Header set Access-Control-Allow-Origin "*"
                </IfModule>
                </FilesMatch>
                # END MyHome IDX Broker
				<?php
				$rules .= ob_get_clean();

				return $rules;
			} );
			flush_rewrite_rules();
		}

		wp_redirect( admin_url( 'admin.php?page=myhome_idx_broker_settings' ) );
		exit;
	}

	public function refresh_account_info( $reload_mls = true ) {
		delete_transient( 'idx_broker_system_links' );
		delete_transient( 'idx_broker_saved_links' );

		$api = new Api();
		if ( $reload_mls ) {
			$mls_list = $api->get_mls();
			if ( ! is_array( $mls_list ) ) {
				update_option( MLS::OPTION_KEY, array() );
			} else {
				$ids = array();
				foreach ( $mls_list as $mls ) {
					$ids[] = $mls->id;
				}
				update_option( MLS::OPTION_KEY, $ids );
			}
		}

		$account_info = $api->get_account_info();
		update_option( 'myhome_idx_account', $account_info );

		$fields = new Fields();
		$fields->import();

		$agents = new Agents();
		$agents->import();
	}

	private function check_if_allowed( $action ) {
		check_admin_referer( $action, 'check_sec' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You don\'t have right permissions to manage options.', 'myhome-idx-broker' ) );
		}
	}

	public function register_fields() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		/**
		 * Property fields
		 */
		acf_add_local_field_group( array(
			'key'        => 'myhome_idx_broker_property_fields',
			'title'      => esc_html__( 'MyHome IDX Broker', 'myhome-idx-broker' ),
			'fields'     => array(
				array(
					'key'   => 'myhome_idx_broker_property_id',
					'label' => esc_html__( 'Property IDX ID', 'myhome-idx-broker' ),
					'name'  => 'idx_broker_property_id',
					'type'  => 'text'
				)
			),
			'menu_order' => 11,
			'location'   => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'estate',
					),
				),
			),
		) );

		$wrapper_types = array(
			'default'    => esc_html__( 'Not set', 'myhome-idx-broker' ),
			'global'     => esc_html__( 'Global wrapper', 'myhome-idx-broker' ),
			'page'       => esc_html__( 'Page', 'myhome-idx-broker' ),
			'saved_link' => esc_html__( 'Saved link', 'myhome-idx-broker' )
		);
//
//		$api   = new Api();
//		$pages = array();
//		$links = $api->get_system_links();
//		foreach ( $links as $page ) {
//			$temp = explode( '-', $page['uid'] );
//			if ( ! isset( $page['name'] ) ) {
//				$page['name'] = '';
//			}
//			$pages[ $temp[1] ] = $page['name'];
//		}
//
//		$saved_links = array();
//		$links = $api->get_saved_links();
//		foreach ( $links as $page ) {
//			$temp = explode( '-', $page['uid'] );
//			if ( ! isset( $page['name'] ) ) {
//				$page['name'] = '';
//			}
//			$saved_links[ $temp[1] ] = $page['name'];
//		}

		//		acf_add_local_field_group( array(
		//			'key'      => 'myhome_idx_broker_page_fields',
		//			'title'    => esc_html__( 'MyHome IDX Broker', 'myhome-idx-broker' ),
		//			'position' => 'side',
		//			'fields'   => array(
		//				array(
		//					'key'     => 'myhome_idx_broker_wrapper_type',
		//					'label'   => esc_html__( 'Set as dynamic wrapper for:', 'myhome-idx-broker' ),
		//					'name'    => 'idx_broker_wrapper_type',
		//					'type'    => 'select',
		//					'choices' => $wrapper_types
		//				),
		//				array(
		//					'key'               => 'myhome_idx_broker_page_id',
		//					'label'             => esc_html__( 'Set as dynamic wrapper for:', 'myhome-idx-broker' ),
		//					'name'              => 'idx_broker_page_id',
		//					'type'              => 'select',
		//					'choices'           => $pages,
		//					'conditional_logic' => array(
		//						array(
		//							array(
		//								'field'    => 'myhome_idx_broker_wrapper_type',
		//								'operator' => '==',
		//								'value'    => 'page'
		//							)
		//						)
		//					)
		//				),
		//				array(
		//					'key'               => 'myhome_idx_broker_saved_link_id',
		//					'label'             => esc_html__( 'Saved links', 'myhome-idx-broker' ),
		//					'name'              => 'idx_broker_saved_link_id',
		//					'type'              => 'select',
		//					'choices'           => $saved_links,
		//					'conditional_logic' => array(
		//						array(
		//							array(
		//								'field'    => 'myhome_idx_broker_wrapper_type',
		//								'operator' => '==',
		//								'value'    => 'saved_link'
		//							)
		//						)
		//					)
		//				)
		//			),
		//			'location' => array(
		//				array(
		//					array(
		//						'param'    => 'post_type',
		//						'operator' => '==',
		//						'value'    => 'page',
		//					),
		//				),
		//			),
		//		) );
	}

	public static function cron_get_hash() {
		$hash = get_option( Importer::CRON_HASH );

		if ( empty( $hash ) ) {
			$hash = IDX::cron_create_hash();
		}

		return $hash;
	}

	public static function cron_create_hash() {
		$hash = md5( 'myhome_idx_broker_' . time() . '_' . rand( 1, 10000 ) );
		update_option( Importer::CRON_HASH, $hash );

		return $hash;
	}

	private function cron_check() {
		if ( ! isset( $_GET['myhome_idx_broker_hash'] ) || empty( $_GET['myhome_idx_broker_hash'] ) ) {
			return false;
		}

		$hash = sanitize_text_field( $_GET['myhome_idx_broker_hash'] );

		if ( ! ( $hash == IDX::cron_get_hash() ) ) {
			wp_die();
		}
	}

	public function cron_init() {
		$this->cron_check();
		update_option( Importer::CRON_JOB, Importer::CRON_JOB_INIT );
		update_option( 'mh_cron_init', date( 'Y-m-d H:i:s' ) );
	}

	public function cron_job() {
		update_option( 'myhome_idx_cron_date_start', date( "Y-m-d h:i:s" ) );

		IDX::$is_crone = true;

		$this->cron_check();
		$importer = new Importer();
		$importer->cron();

		update_option( 'myhome_idx_cron_date_end', date( "Y-m-d h:i:s" ) );
	}

	public function regenerate_hash() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}
		IDX::cron_create_hash();
		wp_redirect( admin_url( 'admin.php?page=myhome_idx_broker' ) );
	}

	/**
	 * @param int $post_id
	 */
	public function save_page( $post_id ) {
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) {
			return;
		}

		$wrapper_type = get_post_meta( $post_id, 'idx_broker_wrapper_type', true );
		if ( empty( $wrapper_type ) || $wrapper_type == 'default' ) {
			return;
		}

		$data = array( 'dynamicURL' => get_the_permalink( $post_id ) );
		if ( $wrapper_type == 'page' ) {
			$data['pageID'] = get_post_meta( $post_id, 'idx_broker_page_id', true );
		} elseif ( $wrapper_type == 'saved_link' ) {
			$data['savedLinkID'] = get_post_meta( $post_id, 'idx_broker_saved_link_id', true );
		}

		$api = new Api();
		$api->update_wrapper( $data );
	}

	public function clear_wrapper_cache(
		$post_id
	) {
		$api = new Api();
		if ( ! $api->has_key() ) {
			return;
		}
		$is_wrapper = get_post_meta( $post_id, 'myhome_idx_broker_wrapper_id', true );
		if ( ! empty( $is_wrapper ) ) {
			$api->clear_wrapper_cache();
		}
	}

	public function clear_cache_button() {
		$api = new Api();
		if ( ! $api->has_key() ) {
			return;
		}
		$api->clear_wrapper_cache();
		wp_redirect( admin_url( 'admin.php?page=myhome_idx_broker_mls' ) );
		die;
	}

	public function api_limit() {
		$limit = get_option( 'myhome_idx_broker_api_limit' );
		if ( ! empty( $limit ) ) :
			?>
            <div class="notice notice-error">
				<?php esc_html_e( 'IDX Broker Api limit exceeded', 'myhome-idx-broker' ); ?>
            </div>
		<?php
		endif;
	}

}