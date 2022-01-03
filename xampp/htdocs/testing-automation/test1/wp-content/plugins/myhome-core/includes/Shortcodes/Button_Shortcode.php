<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class Button_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class Button_Shortcode extends Shortcode {

	/**
	 * @param array       $args
	 * @param string|null $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		$atts = array(
			'button_style'  => '',
			'button_align'  => 'left',
			'button_size'   => '',
			'button_text'   => esc_html__( 'Read More', 'myhome-core' ),
			'button_url'    => '#',
			'button_page'   => '',
			'button_target' => '_self',
			'css'           => ''
		);

		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$atts = array_merge( $atts, vc_map_get_attributes( 'mh_button', $args ) );
		}

		$button_align = 'text-align:' . $atts['button_align'] . ';';
		$button_style = $atts['button_style'] . ' ' . $atts['button_size'];

		if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$css_class = apply_filters(
				VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
				vc_shortcode_custom_css_class( $atts['css'], ' ' ),
				'mh_button',
				$atts
			);
		} else {
			$css_class = '';
		}

		if ( ! empty( $atts['button_page'] ) && $atts['button_page'] == 'mh-blog' ) {
			$atts['button_url'] = get_post_type_archive_link( 'post' );
		} elseif ( ! empty( $atts['button_page'] ) ) {
			$atts['button_url'] = get_page_link( $atts['button_page'] );
		} elseif ( strpos( $atts['button_url'], 'http' ) === false ) {
			$atts['button_url'] = site_url() . $atts['button_url'];
		}

		global $myhome_button;
		$myhome_button = array(
			'heading' => esc_html__( 'Page link', 'myhome-core' ),
			'class'   => $css_class,
			'align'   => $button_align,
			'url'     => $atts['button_url'],
			'text'    => $atts['button_text'],
			'style'   => $button_style,
			'target'  => $atts['button_target']
		);

		return $this->get_template();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		$pages_list = array(
			esc_html__( 'Set page', 'myhome-core' ) => 0,
			esc_html__( 'Blog', 'myhome-core' )     => 'mh-blog'
		);
		$pages      = get_pages();

		foreach ( $pages as $page ) {
			$pages_list[ $page->post_title ] = $page->ID;
		}

		return array(
			// Text
			array(
				'group'       => esc_html__( 'Button', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Text', 'myhome-core' ),
				'param_name'  => 'button_text',
				'value'       => esc_html__( 'Read More', 'myhome-core' ),
				'save_always' => true
			),
			// Style
			array(
				'group'       => esc_html__( 'Button', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'myhome-core' ),
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
			// Size
			array(
				'group'       => esc_html__( 'Button', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Size', 'myhome-core' ),
				'param_name'  => 'button_size',
				'value'       => array(
					esc_html__( 'Default', 'myhome-core' ) => '',
					esc_html__( 'Big', 'myhome-core' )     => 'mdl-button--lg'
				),
				'save_always' => true
			),
			// Align
			array(
				'group'       => esc_html__( 'Button', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Align', 'myhome-core' ),
				'param_name'  => 'button_align',
				'value'       => array(
					esc_html__( 'Left', 'myhome-core' )   => 'left',
					esc_html__( 'Center', 'myhome-core' ) => 'center',
					esc_html__( 'Right', 'myhome-core' )  => 'right'
				),
				'save_always' => true
			),
			array(
				'group'       => esc_html__( 'Button', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Target', 'myhome-core' ),
				'param_name'  => 'button_target',
				'value'       => array(
					esc_html__( 'Same window', 'myhome-core' ) => '_self',
					esc_html__( 'New window', 'myhome-core' )  => '_blank',
				),
				'save_always' => true
			),
			array(
				'group'       => esc_html__( 'Button', 'myhome-core' ),
				'type'        => 'dropdown',
				'param_name'  => 'button_page',
				'value'       => $pages_list,
				'save_always' => true
			),
			// Link
			array(
				'group'       => esc_html__( 'Button', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'or Link', 'myhome-core' ),
				'param_name'  => 'button_url',
				'value'       => '#',
				'description' => esc_html__( 'eg. http://xxxxxxx.xxx', 'myhome-core' ),
				'save_always' => true
			),
			// Css
			array(
				'type'       => 'css_editor',
				'heading'    => esc_html__( 'Css', 'myhome-core' ),
				'param_name' => 'css',
				'group'      => esc_html__( 'Design options', 'myhome-core' ),
			)
		);
	}

}