<?php
/**
 * Plugin Name: LearnPress - Course Review
 * Plugin URI: http://thimpress.com/learnpress
 * Description: Adding review for course.
 * Author: ThimPress
 * Version: 4.0.2
 * Author URI: http://thimpress.com
 * Tags: learnpress
 * Requires at least: 3.8
 * Tested up to: 5.7
 * Text Domain: learnpress-course-review
 * Domain Path: /languages/
 * Require_LP_Version: 3.0.0
 *
 * @package learnpress-course-review
 */

defined( 'ABSPATH' ) || exit;

define( 'LP_ADDON_COURSE_REVIEW_FILE', __FILE__ );

/**
 * Class LP_Addon_Course_Review_Preload
 */
class LP_Addon_Course_Review_Preload {
	/**
	 * @var array|string[]
	 */
	public static $addon_info = array();

	/**
	 * LP_Addon_Course_Review_Preload constructor.
	 */
	public function __construct() {
		// Set Base name plugin.
		define( 'LP_ADDON_COURSE_REVIEW_BASENAME', plugin_basename( LP_ADDON_COURSE_REVIEW_FILE ) );

		// Set version addon for LP check .
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		self::$addon_info = get_file_data(
			LP_ADDON_COURSE_REVIEW_FILE,
			array(
				'Name'               => 'Plugin Name',
				'Require_LP_Version' => 'Require_LP_Version',
				'Version'            => 'Version',
			)
		);

		define( 'LP_ADDON_COURSE_REVIEW_VER', self::$addon_info['Version'] );
		define( 'LP_ADDON_COURSE_REVIEW_REQUIRE_VER', self::$addon_info['Require_LP_Version'] );

		// Check LP activated .
		if ( ! is_plugin_active( 'learnpress/learnpress.php' ) ) {
			add_action( 'admin_notices', array( $this, 'show_note_errors_require_lp' ) );

			deactivate_plugins( LP_ADDON_COURSE_REVIEW_BASENAME );

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			return;
		}

		// Sure LP loaded.
		add_action( 'learn-press/ready', array( $this, 'load' ) );
	}

	/**
	 * Load addon
	 */
	public function load() {
		LP_Addon::load( 'LP_Addon_Course_Review', 'inc/load.php', __FILE__ );
	}

	public function show_note_errors_require_lp() {
		?>
		<div class="notice notice-error">
			<p><?php echo( 'Please active <strong>LearnPress version ' . LP_ADDON_COURSE_REVIEW_REQUIRE_VER . ' or later</strong> before active <strong>' . self::$addon_info['Name'] . '</strong>' ); ?></p>
		</div>
		<?php
	}
}

new LP_Addon_Course_Review_Preload();
