<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class Heading_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class Heading_Shortcode extends Shortcode {

	/**
	 * @param array       $args
	 * @param string|null $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		$atts = array(
			'heading_text'                   => 'Heading',
			'heading_subheading'             => '',
			'heading_tag'                    => 'h2',
			'heading_font_family'            => '',
			'heading_size'                   => '',
			'heading_align'                  => '',
			'heading_family'                 => 'mh-heading--style-3',
			'heading_color'                  => '',
			'heading_color_other'            => '',
			'heading_subheading_color'       => '',
			'heading_subheading_color_other' => '',
			'heading_font_weight'            => '700',
			'heading_style'                  => 'mh-heading--bottom-separator',
			'align'                          => '',
			'heading_image_id'               => '',
			'css'                            => '',
		);
		$atts = array_merge( $atts, vc_map_get_attributes( 'mh_heading', $args ) );

		// get custom css class
		if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$css_class = apply_filters(
				VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
				vc_shortcode_custom_css_class( $atts['css'], ' ' ),
				'mh_heading',
				$atts
			);
		} else {
			$css_class = '';
		}

		if ( ! empty( $atts['heading_style'] ) && $atts['heading_style'] == 'mh-heading--no-separator' ) {
			$atts['heading_style'] = '';
		}


		global $myhome_heading;
		$myhome_heading = $atts;

		$myhome_heading['class'] = $atts['heading_color'] . ' ' . $atts['heading_style'] . ' ' . $atts['heading_size']
		                           . ' ' . $atts['heading_font_family'] . ' ' . $css_class;
		$myhome_heading['style'] = 'font-weight:' . $atts['heading_font_weight'] . ';';
		$myhome_heading['style'] .= ! empty( $atts['heading_color_other'] ) ? 'color:' . $atts['heading_color_other'] . ';' : '';

		return $this->get_template();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		return array(
			// Heading text
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Heading text', 'myhome-core' ),
				'param_name'  => 'heading_text',
				'default'     => 'Heading',
				'save_always' => true
			),
			// Subheading
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Subheading text', 'myhome-core' ),
				'param_name'  => 'heading_subheading',
				'save_always' => true
			),
			// Style
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Heading style', 'myhome-core' ),
				'param_name'  => 'heading_style',
				'save_always' => true,
				'value'       => array(
					esc_html__( 'Bottom Separator', 'myhome-core' ) => 'mh-heading--bottom-separator',
					esc_html__( 'Top Separator', 'myhome-core' )    => 'mh-heading--top-separator',
					esc_html__( 'No separator', 'myhome-core' )     => 'mh-heading--no-separator',
				),
			),
			// Align
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Align', 'myhome-core' ),
				'param_name'  => 'heading_align',
				'save_always' => true,
				'value'       => array(
					esc_html__( 'Center', 'myhome-core' ) => '',
					esc_html__( 'Left', 'myhome-core' )   => 'mh-heading-wrapper--left',
					esc_html__( 'Right', 'myhome-core' )  => 'mh-heading-wrapper--right'
				),
			),
			// Font Weight
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Font Weight', 'myhome-core' ),
				'param_name'  => 'heading_font_weight',
				'save_always' => true,
				'value'       => array(
					esc_html__( 'Bold', 'myhome-core' ) => 700,
					esc_html__( 'Thin', 'myhome-core' ) => 400,
				),
			),
			// Size
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Size', 'myhome-core' ),
				'param_name'  => 'heading_size',
				'save_always' => true,
				'value'       => array(
					esc_html__( 'Default', 'myhome-core' ) => '',
					esc_html__( 'XXXL', 'myhome-core' )    => 'mh-font-size-xxxl',
					esc_html__( 'XXL', 'myhome-core' )     => 'mh-font-size-xxl',
					esc_html__( 'XL', 'myhome-core' )      => 'mh-font-size-xl',
					esc_html__( 'L', 'myhome-core' )       => 'mh-font-size-l',
					esc_html__( 'M', 'myhome-core' )       => 'mh-font-size-m',
					esc_html__( 'S', 'myhome-core' )       => 'mh-font-size-s',
					esc_html__( 'XS', 'myhome-core' )      => 'mh-font-size-xs',
					esc_html__( 'XXS', 'myhome-core' )     => 'mh-font-size-xxs',
				),
			),
			// Tag
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Tag', 'myhome-core' ),
				'param_name'  => 'heading_tag',
				'save_always' => true,
				'value'       => array(
					esc_html__( 'H2', 'myhome-core' ) => 'h2',
					esc_html__( 'H1', 'myhome-core' ) => 'h1',
					esc_html__( 'H2', 'myhome-core' ) => 'h2',
					esc_html__( 'H3', 'myhome-core' ) => 'h3',
					esc_html__( 'H4', 'myhome-core' ) => 'h4',
					esc_html__( 'H5', 'myhome-core' ) => 'h5',
				),
			),
			// Heading color
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Heading color', 'myhome-core' ),
				'param_name'  => 'heading_color',
				'save_always' => true,
				'value'       => array(
					esc_html__( 'Default', 'myhome-core' ) => '',
					esc_html__( 'White', 'myhome-core' )   => 'mh-color-white',
					esc_html__( 'Primary', 'myhome-core' ) => 'mh-color-primary',
					esc_html__( 'Other', 'myhome-core' )   => 'mh-color-other',
				),
			),
			// Heading color
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Font family', 'myhome-core' ),
				'param_name'  => 'heading_font_family',
				'save_always' => true,
				'value'       => array(
					esc_html__( 'Default', 'myhome-core' )   => '',
					esc_html__( 'Font body', 'myhome-core' ) => 'mh-font-body',
				),
			),
			// Heading color other
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'colorpicker',
				'heading'     => esc_html__( 'Heading color other', 'myhome-core' ),
				'param_name'  => 'heading_color_other',
				'save_always' => true,
				'dependency'  => array(
					'element'   => 'heading_color',
					'value'     => 'mh-color-other',
					'not_empty' => false
				)
			),
			// Subheading color
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Subheading color', 'myhome-core' ),
				'param_name'  => 'heading_subheading_color',
				'save_always' => true,
				'value'       => array(
					esc_html__( 'Default', 'myhome-core' ) => '',
					esc_html__( 'White', 'myhome-core' )   => 'mh-color-white',
					esc_html__( 'Primary', 'myhome-core' ) => 'mh-color-primary',
					esc_html__( 'Other', 'myhome-core' )   => 'mh-color-other',
				),
			),
			// Subheading color other
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'colorpicker',
				'heading'     => esc_html__( 'Subheading color other', 'myhome-core' ),
				'param_name'  => 'heading_subheading_color_other',
				'save_always' => true,
				'dependency'  => array(
					'element'   => 'heading_subheading_color',
					'value'     => 'mh-color-other',
					'not_empty' => false
				)
			),
			// Image
			array(
				'group'       => esc_html__( 'Heading', 'myhome-core' ),
				'type'        => 'attach_image',
				'heading'     => esc_html__( 'Heading Background', 'myhome-core' ),
				'param_name'  => 'heading_image_id',
				'save_always' => true
			),
			// Css
			array(
				'type'        => 'css_editor',
				'heading'     => esc_html__( 'Css', 'myhome-core' ),
				'param_name'  => 'css',
				'group'       => esc_html__( 'Design options', 'myhome-core' ),
				'save_always' => true
			)
		);
	}

}