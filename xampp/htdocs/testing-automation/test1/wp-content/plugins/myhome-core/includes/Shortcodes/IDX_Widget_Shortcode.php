<?php

namespace MyHomeCore\Shortcodes;


use MyHomeIDXBroker\Api;

/**
 * Class IDX_Widget_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class IDX_Widget_Shortcode extends Shortcode {

	public static $id = 1;

	public function display( $args = array(), $content = null ) {
		$widget_url = isset( $args['widget'] ) ? $args['widget'] : '';
		$class      = array(
			'mh-idx-widget'
		);

		global $myhome_idx_widget;
		$myhome_idx_widget = array(
			'url' => $args['widget'],
			'id'  => 'mh-idx-widget__' . IDX_Widget_Shortcode::$id
		);
		IDX_Widget_Shortcode::$id ++;

		if ( ! empty( $widget_url ) ) {
			if ( strpos( $widget_url, 'featuredagent.php' ) !== false ) {
				$class[] = 'mh-idx-widget__featured-agent';
			} elseif ( strpos( $widget_url, 'mapwidgetjs.php' ) ) {
				$class[] = 'mh-idx-widget__map';
			} elseif ( strpos( $widget_url, 'customshowcasejs.php' ) !== false ) {
				$class[] = 'mh-idx-widget__showcase';
			} elseif ( strpos( $widget_url, 'customslideshowjs.php' ) !== false ) {
				$class[] = 'mh-idx-widget__slide-show';
			} elseif ( strpos( $widget_url, 'leadloginwidget.php' ) !== false ) {
				$class[] = 'mh-idx-widget__lead-login';
			} elseif ( strpos( $widget_url, 'leadsignupwidget.php' ) !== false ) {
				$class[] = 'mh-idx-widget__lead-signup';
			} elseif ( strpos( $widget_url, 'quicksearchjs.php' ) !== false ) {
				$class[] = 'mh-idx-widget__quick-search';

				if ( isset( $args['quick_search_type'] ) ) {
					$class[] = 'mh-idx-widget__quick-search--type--' . $args['quick_search_type'];
				} else {
					$class[] = 'mh-idx-widget_-quick-search--type--vertical';
				}
			} elseif ( strpos( $widget_url, 'carousel.php' ) !== false ) {
				$class[] = 'mh-idx-widget__carousel';

				if ( isset( $args['carousel_width'] ) ) {
					$myhome_idx_widget['carousel_width'] = $args['carousel_width'];
				} else {
					$myhome_idx_widget['carousel_width'] = 300;
				}

				if ( isset( $args['carousel_card_bg'] ) ) {
					$myhome_idx_widget['carousel_card_bg'] = $args['carousel_card_bg'];
				} else {
					$myhome_idx_widget['carousel_card_bg'] = '#f4f4f4';
				}
			}
		}

		$myhome_idx_widget['class'] = implode( ' ', $class );

		return $this->get_template();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		$api                     = new Api();
		$widgets                 = $api->get_widget_src();
		$widgets_list            = array();
		$carousel_dependency     = array();
		$quick_search_dependency = array();

		foreach ( $widgets as $widget ) {
			$widgets_list[ $widget['name'] ] = $widget['url'];

			if ( strpos( $widget['url'], 'carousel.php' ) !== false ) {
				$carousel_dependency[] = $widget['url'];
			} elseif ( strpos( $widget['url'], 'quicksearchjs.php' ) !== false ) {
				$quick_search_dependency[] = $widget['url'];
			}
		}

		$params = array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Widget', 'myhome-core' ),
				'param_name'  => 'widget',
				'value'       => $widgets_list,
				'save_always' => true
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_html__( 'Card background', 'myhome-core' ),
				'param_name'  => 'carousel_card_bg',
				'value'       => '#f4f4f4',
				'save_always' => true,
				'dependency'  => array(
					'element' => 'widget',
					'value'   => $carousel_dependency
				)
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Max Column width (px)', 'myhome-core' ),
				'param_name'  => 'carousel_width',
				'value'       => 300,
				'save_always' => true,
				'dependency'  => array(
					'element' => 'widget',
					'value'   => $carousel_dependency
				)
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Type', 'myhome-core' ),
				'param_name'  => 'quick_search_type',
				'value'       => array(
					esc_html__( 'Vertical', 'myhome-core' )   => 'vertical',
					esc_html__( 'Horizontal', 'myhome-core' ) => 'horizontal'
				),
				'save_always' => true,
				'dependency'  => array(
					'element' => 'widget',
					'value'   => $quick_search_dependency
				)
			)
		);

		return $params;
	}

}