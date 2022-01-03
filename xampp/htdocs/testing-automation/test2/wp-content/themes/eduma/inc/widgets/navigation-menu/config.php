<?php
/**
 * Thim_Builder Navigation Menu config class
 *
 * @version     1.0.0
 * @author      ThimPress
 * @package     Thim_Builder/Classes
 * @category    Classes
 * @author      Thimpress, tuanta
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Thim_Builder_Config_Navigation_Menu' ) ) {
	/**
	 * Class Thim_Builder_Config_Navigation_Menu
	 */
	class Thim_Builder_Config_Navigation_Menu extends Thim_Builder_Abstract_Config {

		/**
		 * Thim_Builder_Config_Navigation_Menu constructor.
		 */
		public function __construct() {
			// info
			self::$base = 'navigation-menu';
			self::$name = esc_html__( 'Thim: Navigation Menu', 'eduma' );
			self::$desc = '';
			self::$icon = 'thim-widget-icon thim-widget-icon-link';
			parent::__construct();
		}

		public function get_navigation_menu() {
			$menus        = wp_get_nav_menus();
			$options      = array();
			$options[esc_html__( '&mdash; Select &mdash;', 'eduma' )] = 0;

			foreach ( $menus as $menu ) {
				$options [$menu->name] = $menu->term_id;
			}

			return $options;
		}

		/**
		 * @return array
		 */
		public function get_options() {

			// options
			return array(
				array(
					'type'        => 'textfield',
					'admin_label' => true,
					'heading'     => esc_html__( 'Title', 'eduma' ),
					'param_name'  => 'title',
				),


				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Select Menu', 'eduma' ),
					'param_name' => 'menu',
					'value'      => $this->get_navigation_menu(),
					'std'        => 0
				),


			);
		}

	}
}