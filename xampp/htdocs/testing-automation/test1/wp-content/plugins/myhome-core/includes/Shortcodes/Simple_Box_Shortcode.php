<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class Simple_Box_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class Simple_Box_Shortcode extends Shortcode {

	/**
	 * @param array $args
	 * @param string|null $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		$atts = array(
			'title'             => '',
			'style'             => 'mh-simple-box--left',
			'icon'              => 'house',
			'text_color'        => '',
			'text_color_other'  => '',
			'title_color'       => '',
			'title_color_other' => '',
			'icon_color'        => '',
			'icon_color_other'  => '',
			'button_show'       => '',
			'button_style'      => '',
			'button_align'      => 'left',
			'button_size'       => '',
			'button_text'       => esc_html__( 'More', 'myhome-core' ),
			'button_url'        => '#',
		);

		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$atts = array_merge( $atts, vc_map_get_attributes( 'mh_simple_box', $args ) );
		}

		global $myhome_simple_box;
		$myhome_simple_box = array_merge( $atts, array(
			'content'       => wpb_js_remove_wpautop( $content, true ),
			'class'         => $atts['style'],
			'btn_class'     => $atts['button_style'] . ' ' . $atts['button_size'],
			'icon_style'    => ! empty( $atts['icon_color_other'] ) ? 'color:' . $atts['icon_color_other'] . ';' : '',
			'heading_style' => ! empty( $atts['title_color_other'] ) ? 'color:' . $atts['title_color_other'] . ';' : '',
			'text_style'    => ! empty( $atts['text_color_other'] ) ? 'color:' . $atts['text_color_other'] . ';' : ''
		) );

		return $this->get_template();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		return array(
			// Icon
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Icon', 'myhome-core' ),
				'param_name' => 'icon',
				'value'      => array(
					esc_html__( 'home', 'myhome-core' )            => 'home',
					esc_html__( 'air conditioner', 'myhome-core' ) => 'air-conditioner',
					esc_html__( 'apartment', 'myhome-core' )       => 'apartment',
					esc_html__( 'area', 'myhome-core' )            => 'area',
					esc_html__( 'bath', 'myhome-core' )            => 'bath',
					esc_html__( 'bath 2', 'myhome-core' )          => 'bath-2',
					esc_html__( 'bathtub', 'myhome-core' )         => 'bathtub',
					esc_html__( 'bed', 'myhome-core' )             => 'bed',
					esc_html__( 'bulb', 'myhome-core' )            => 'bulb',
					esc_html__( 'city', 'myhome-core' )            => 'city',
					esc_html__( 'city 2', 'myhome-core' )          => 'city-2',
					esc_html__( 'computer', 'myhome-core' )        => 'computer',
					esc_html__( 'construction', 'myhome-core' )    => 'construction',
					esc_html__( 'construction 2', 'myhome-core' )  => 'construction-2',
					esc_html__( 'date', 'myhome-core' )            => 'date',
					esc_html__( 'dishwasher', 'myhome-core' )      => 'dishwasher',
					esc_html__( 'door', 'myhome-core' )            => 'door',
					esc_html__( 'fence', 'myhome-core' )           => 'fence',
					esc_html__( 'fireplace', 'myhome-core' )       => 'fireplace',
					esc_html__( 'full size', 'myhome-core' )       => 'full-size',
					esc_html__( 'furniture', 'myhome-core' )       => 'furniture',
					esc_html__( 'garage', 'myhome-core' )          => 'garage',
					esc_html__( 'home', 'myhome-core' )            => 'home',
					esc_html__( 'home 2', 'myhome-core' )          => 'home-2',
					esc_html__( 'home 3', 'myhome-core' )          => 'home-3',
					esc_html__( 'home 4', 'myhome-core' )          => 'home-4',
					esc_html__( 'home 5', 'myhome-core' )          => 'home-5',
					esc_html__( 'home 6', 'myhome-core' )          => 'home-6',
					esc_html__( 'plan', 'myhome-core' )            => 'house-plan',
					esc_html__( 'plan 2', 'myhome-core' )          => 'house-plan-2',
					esc_html__( 'interface', 'myhome-core' )       => 'interface',
					esc_html__( 'layers', 'myhome-core' )          => 'layers',
					esc_html__( 'lift', 'myhome-core' )            => 'lift',
					esc_html__( 'location', 'myhome-core' )        => 'location',
					esc_html__( 'location 2', 'myhome-core' )      => 'location-2',
					esc_html__( 'mail', 'myhome-core' )            => 'mail',
					esc_html__( 'mail 2', 'myhome-core' )          => 'mail-2',
					esc_html__( 'map', 'myhome-core' )             => 'map',
					esc_html__( 'medical', 'myhome-core' )         => 'medical',
					esc_html__( 'microwave', 'myhome-core' )       => 'microwave',
					esc_html__( 'multimedia', 'myhome-core' )      => 'multimedia',
					esc_html__( 'office', 'myhome-core' )          => 'office',
					esc_html__( 'office 2', 'myhome-core' )        => 'office-2',
					esc_html__( 'owen', 'myhome-core' )            => 'owen',
					esc_html__( 'parquet', 'myhome-core' )         => 'parquet',
					esc_html__( 'phone', 'myhome-core' )           => 'phone',
					esc_html__( 'pin', 'myhome-core' )             => 'pin',
					esc_html__( 'prize', 'myhome-core' )           => 'prize',
					esc_html__( 'rent', 'myhome-core' )            => 'rent',
					esc_html__( 'roof', 'myhome-core' )            => 'roof',
					esc_html__( 'school', 'myhome-core' )          => 'school',
					esc_html__( 'school 2', 'myhome-core' )        => 'school-2',
					esc_html__( 'shower', 'myhome-core' )          => 'shower',
					esc_html__( 'sofa', 'myhome-core' )            => 'sofa',
					esc_html__( 'sofa 2', 'myhome-core' )          => 'sofa-2',
					esc_html__( 'sold', 'myhome-core' )            => 'sold',
					esc_html__( 'stairs', 'myhome-core' )          => 'stairs',
					esc_html__( 'swimming pool', 'myhome-core' )   => 'swimming-pool',
					esc_html__( 'technology', 'myhome-core' )      => 'technology',
					esc_html__( 'transport', 'myhome-core' )       => 'transport',
					esc_html__( 'wall', 'myhome-core' )            => 'wall',
					esc_html__( 'wardrobe', 'myhome-core' )        => 'wardrobe',
					esc_html__( 'wifi', 'myhome-core' )            => 'wifi',
					esc_html__( 'window', 'myhome-core' )          => 'window',
				),
			),
			// Title
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Title', 'myhome-core' ),
				'param_name' => 'title',
			),
			// Text
			array(
				'type'        => 'textarea_html',
				'heading'     => esc_html__( 'Content', 'myhome-core' ),
				'param_name'  => 'content',
				'save_always' => true
			),
			// Style
			array(
				'group'      => esc_html__( 'Style', 'myhome-core' ),
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Style', 'myhome-core' ),
				'param_name' => 'style',
				'value'      => array(
					esc_html__( 'Icon Left', 'myhome-core' )       => 'mh-simple-box--left',
					esc_html__( 'Icon Center', 'myhome-core' )     => 'mh-simple-box--center',
					esc_html__( 'Icon Right', 'myhome-core' )      => 'mh-simple-box--right',
					esc_html__( 'Big Icon Left', 'myhome-core' )   => 'mh-simple-box--big-left',
					esc_html__( 'Big Icon Center', 'myhome-core' ) => 'mh-simple-box--big-center',
					esc_html__( 'Big Icon Right', 'myhome-core' )  => 'mh-simple-box--big-right',
				),
			),
			// Icon Color
			array(
				'group'      => esc_html__( 'Style', 'myhome-core' ),
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Icon Color', 'myhome-core' ),
				'param_name' => 'icon_color',
				'value'      => array(
					esc_html__( 'Default', 'myhome-core' ) => 'mh-color-default',
					esc_html__( 'Primary', 'myhome-core' ) => 'mh-color-primary',
					esc_html__( 'Other', 'myhome-core' )   => 'mh-color-other',
				),
			),
			// Icon Color Other
			array(
				'group'      => esc_html__( 'Style', 'myhome-core' ),
				'type'       => 'colorpicker',
				'heading'    => esc_html__( 'Icon Color Other', 'myhome-core' ),
				'param_name' => 'icon_color_other',
				'dependency' => array(
					'element'   => 'icon_color',
					'value'     => 'mh-color-other',
					'not_empty' => false
				)
			),
			// Title Color
			array(
				'group'      => esc_html__( 'Style', 'myhome-core' ),
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Title Color', 'myhome-core' ),
				'param_name' => 'title_color',
				'value'      => array(
					esc_html__( 'Default', 'myhome-core' ) => 'mh-color-default',
					esc_html__( 'Primary', 'myhome-core' ) => 'mh-color-primary',
					esc_html__( 'Other', 'myhome-core' )   => 'mh-color-other',
				),
			),
			// Title Color Other
			array(
				'group'      => esc_html__( 'Style', 'myhome-core' ),
				'type'       => 'colorpicker',
				'heading'    => esc_html__( 'Title Color Other', 'myhome-core' ),
				'param_name' => 'title_color_other',
				'dependency' => array(
					'element'   => 'title_color',
					'value'     => 'mh-color-other',
					'not_empty' => false
				)
			),
			// Text Color
			array(
				'group'      => esc_html__( 'Style', 'myhome-core' ),
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Text Color', 'myhome-core' ),
				'param_name' => 'text_color',
				'value'      => array(
					esc_html__( 'Default', 'myhome-core' ) => 'mh-color-default',
					esc_html__( 'Primary', 'myhome-core' ) => 'mh-color-primary',
					esc_html__( 'Other', 'myhome-core' )   => 'mh-color-other',
				),
			),
			// Text Color Other
			array(
				'group'      => esc_html__( 'Style', 'myhome-core' ),
				'type'       => 'colorpicker',
				'heading'    => esc_html__( 'Text Color Other', 'myhome-core' ),
				'param_name' => 'text_color_other',
				'dependency' => array(
					'element'   => 'text_color',
					'value'     => 'mh-color-other',
					'not_empty' => false
				)
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
				'save_always' => false
			),
			array(
				'group'       => esc_html__( 'Button', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Button text', 'myhome-core' ),
				'param_name'  => 'button_text',
				'value'       => 'Read More',
				'dependency'  => array(
					'element'   => 'button_show',
					'value'     => '1',
					'not_empty' => false
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
				'dependency'  => array(
					'element'   => 'button_show',
					'value'     => '1',
					'not_empty' => false
				),
				'save_always' => true
			),
			// Link
			array(
				'group'       => esc_html__( 'Button', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Button link', 'myhome-core' ),
				'param_name'  => 'button_url',
				'value'       => '#',
				'description' => esc_html__( 'eg. http://xxxxxxx.xxx', 'myhome-core' ),
				'dependency'  => array(
					'element'   => 'button_show',
					'value'     => '1',
					'not_empty' => false
				),
				'save_always' => true
			)
		);
	}


}