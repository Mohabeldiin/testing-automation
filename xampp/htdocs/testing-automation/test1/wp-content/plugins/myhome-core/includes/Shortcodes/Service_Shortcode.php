<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class Service_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class Service_Shortcode extends Shortcode {

	/**
	 * @param array $args
	 * @param string|null $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		$atts = vc_map_get_attributes( 'mh_service', $args );
		$atts = shortcode_atts( array(
			'image_id'     => '',
			'service_link' => '#',
			'title'        => '',
			'style'        => '',
			'button_show'  => '1',
			'button_style' => '',
			'button_text'  => esc_html__( 'Read More', 'myhome-core' ),
			'css'          => ''
		), $atts );

		// get custom css class
		if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$css_class = apply_filters(
				VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
				vc_shortcode_custom_css_class( $atts['css'], ' ' ),
				'mh_service',
				$atts
			);
		} else {
			$css_class = '';
		}

		global $myhome_service;
		$myhome_service            = $atts;
		$myhome_service['class']   = $atts['style'] . ' ' . $css_class;
		$myhome_service['content'] = wpb_js_remove_wpautop( $content, true );

		return $this->get_template();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		return array(
			// Title
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Title', 'myhome-core' ),
				'param_name' => 'title',
			),
			// Content
			array(
				'type'        => 'textarea_html',
				'heading'     => esc_html__( 'Content', 'myhome-core' ),
				'param_name'  => 'content',
				'save_always' => true
			),
			// Image
			array(
				'type'        => 'attach_image',
				'heading'     => esc_html__( 'Image', 'myhome-core' ),
				'param_name'  => 'image_id',
				'save_always' => true
			),
			// Link
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Link', 'myhome-core' ),
				'param_name'  => 'service_link',
				'value'       => '#',
				'description' => esc_html__( 'eg. http://xxxxxxx.xxx', 'myhome-core' ),
			),
			// Style
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Background color', 'myhome-core' ),
				'param_name' => 'style',
				'value'      => array(
					esc_html__( 'Default', 'myhome-core' )          => '',
					esc_html__( 'White Background', 'myhome-core' ) => 'mh-service--white-background',
					esc_html__( 'Dark Background', 'myhome-core' )  => 'mh-service--dark-background',
				),
			),
			// Button show
			array(
				'group'       => esc_html__( 'Button', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Display button', 'myhome-core' ),
				'param_name'  => 'button_show',
				'value'       => array(
					esc_html__( 'Yes', 'myhome-core' ) => 1,
					esc_html__( 'No', 'myhome-core' )  => 0,
				),
				'save_always' => true
			),
			// Style
			array(
				'group'       => esc_html__( 'Button', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Button style', 'myhome-core' ),
				'param_name'  => 'button_style',
				'value'       => array(
					esc_html__( 'Primary', 'myhome-core' )       => 'mdl-button--raised mdl-button--primary',
					esc_html__( 'Primary ghost', 'myhome-core' ) => 'mdl-button--raised mdl-button--primary-ghost',
					esc_html__( 'Transparent', 'myhome-core' )   => 'mdl-button--raised',
					esc_html__( 'White', 'myhome-core' )         => 'mdl-button--white',
					esc_html__( 'Dark', 'myhome-core' )          => 'mdl-button--raised mdl-button--dark',
				),
				'save_always' => true
			),
			array(
				'group'       => esc_html__( 'Button', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Button text', 'myhome-core' ),
				'param_name'  => 'button_text',
				'value'       => esc_html__( 'Read More', 'myhome-core' ),
				'save_always' => true
			),
			// Css
			array(
				'type'       => 'css_editor',
				'heading'    => esc_html__( 'Css', 'myhome-core' ),
				'param_name' => 'css',
				'group'      => esc_html__( 'Design options', 'myhome-core' ),
			),
		);
	}

}