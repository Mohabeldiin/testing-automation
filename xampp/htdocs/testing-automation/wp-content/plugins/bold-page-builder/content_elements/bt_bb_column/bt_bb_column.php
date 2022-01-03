<?php

class bt_bb_column extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {

		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'width'                  => '',
			'width_lg'               => '',
			'width_md'               => '',
			'width_sm'               => '',
			'width_xs'               => '',
			'align'                  => 'left',
			'vertical_align'         => 'top',
			'padding'                => 'normal',
			'order'                  => '',
			'background_image'       => '',
			'lazy_load'              => 'no',
			'inner_background_image' => '',
			'color_scheme'           => '',
			'background_color'       => '',
			'inner_background_color' => '',
			'opacity'                => ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );
		$data_override_class = array();
		
		$class[] = $this->get_responsive_class( $width, 'xl' );
		
		if ( $width_xs != '' ) {
			$class[] = $this->get_responsive_class( $width_xs, 'xs' );
		}
		if ( $width_sm != '' ) {
			$class[] = $this->get_responsive_class( $width_sm, 'sm' );
		}
		if ( $width_md != '' ) {
			$class[] = $this->get_responsive_class( $width_md, 'md' );
		}
		if ( $width_lg != '' ) {
			$class[] = $this->get_responsive_class( $width_lg, 'lg' );
		}

		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = 'id="' . esc_attr( $el_id ) . '"';
		}

		if ( $color_scheme != '' ) {
			$class[] = $this->prefix . 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}
		
		$this->responsive_data_override_class(
			$class, $data_override_class,
			array(
				'prefix' => $this->prefix,
				'param' => 'align',
				'value' => $align
			)
		);
		
		if ( $vertical_align != '' ) {
			$class[] = $this->prefix . 'vertical_align' . '_' . $vertical_align;
		}

		if ( $padding != '' ) {
			$class[] = $this->prefix . 'padding' . '_' . $padding;
		}
		
		$this->responsive_override_class(
			$class,
			array(
				'prefix' => $this->prefix,
				'ignore' => '0',
				'param'  => 'order',
				'value'  => $order
			)
		);

		if ( $background_color != '' ) {
			if ( strpos( $background_color, '#' ) !== false ) {
				$background_color = bt_bb_hex2rgb( $background_color );
				if ( $opacity == '' ) {
					$opacity = 1;
				}
				$el_style .= 'background-color:rgba(' . $background_color[0] . ', ' . $background_color[1] . ', ' . $background_color[2] . ', ' . $opacity . ');';
			} else {
				$el_style .= 'background-color:' . $background_color . ';';
			}
		}
		
		$el_inner_style = '';
		
		if ( $inner_background_color != '' ) {
			if ( strpos( $inner_background_color, '#' ) !== false ) {
				$inner_background_color = bt_bb_hex2rgb( $inner_background_color );
				if ( $opacity == '' ) {
					$opacity = 1;
				}
				$el_inner_style .= 'background-color:rgba(' . $inner_background_color[0] . ', ' . $inner_background_color[1] . ', ' . $inner_background_color[2] . ', ' . $opacity . ');';
			} else {
				$el_inner_style .= 'background-color:' . $inner_background_color . ';';
			}
		}
		
		$inner_class = array( $this->shortcode . '_content' );
		$background_data_attr = '';
		$inner_background_data_attr = '';
		
		if ( $background_image != '' ) {
			$background_image = wp_get_attachment_image_src( $background_image, 'full' );
			if ( $background_image ) {
				$background_image_url = $background_image[0];
				if ( $lazy_load == 'yes' ) {
					$blank_image_src = BT_BB_Root::$path . 'img/blank.gif';
					$el_style .= 'background-image:url(\'' . $blank_image_src . '\');';
					$background_data_attr .= ' data-background_image_src=\'' . $background_image_url . '\'';
					$class[] = 'btLazyLoadBackground';
				} else {
					$el_style .= 'background-image:url(\'' . $background_image_url . '\');';				
				}
			}
				
			$class[] = 'bt_bb_column_background_image';
		}
		
		if ( $inner_background_image != '' ) {
			$inner_background_image = wp_get_attachment_image_src( $inner_background_image, 'full' );
			$inner_background_image_url = $inner_background_image[0];
			if ( $lazy_load == 'yes' ) {
				$blank_image_src = BT_BB_Root::$path . 'img/blank.gif';
				$el_inner_style .= 'background-image:url(\'' . $blank_image_src . '\');';
				$inner_background_data_attr .= ' data-background_image_src="' . $inner_background_image_url . '"';
				$inner_class[] = 'btLazyLoadBackground';
			} else {
				$el_inner_style .= 'background-image:url(\'' . $inner_background_image_url . '\');';				
			}
			$class[] = 'bt_bb_column_inner_background_image';
		}
		
		if ( $el_inner_style != '' ) {
			$el_inner_style = ' style="' . esc_attr( $el_inner_style ) . '"';
		}
		
		$style_attr = '';

		if ( $el_style != '' ) {
			$style_attr = 'style="' . esc_attr( $el_style ) . '"';
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$output = '<div ' . $id_attr . ' class="' . implode( ' ', $class ) . '" ' . $style_attr . $background_data_attr . ' data-width="' . esc_attr( $this->get_width2( $width ) ) . '" data-bt-override-class="' . htmlspecialchars( json_encode( $data_override_class, JSON_FORCE_OBJECT ), ENT_QUOTES, 'UTF-8' ) . '">';
			$output .= '<div class="' . esc_attr( implode( ' ', $inner_class ) ) . '"' . $el_inner_style . $inner_background_data_attr . '>';
				$output .= '<div class="' . esc_attr( $this->shortcode . '_content_inner' ) . '">';
					$output .= do_shortcode( $content );
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}
	
	function get_responsive_class( $width, $size ) {
		
		$width = $this->get_width1( $width );

		$class = 'col-' . $size . '-' . $width;
		
		return $class;
	}
	
	function get_width1( $width ) {
		$array = explode( '/', $width );

		if ( empty( $array ) || $array[0] == 0 || $array[1] == 0 ) {
			$width = 12;
		} else {
			$top = $array[0];
			$bottom = $array[1];
			$width = 12 * $top / $bottom;
			if ( $width < 1 || $width > 12 ) {
				$width = 12;
			}
		}
		
		$width = str_replace( '.', '_', $width );
		
		return $width;
	}
	
	function get_width2( $width ) {
		$array = explode( '/', $width );

		if ( empty( $array ) || $array[0] == 0 || $array[1] == 0 ) {
			$width = 12;
		} else {
			$top = $array[0];
			$bottom = $array[1];
			$width = 12 * $top / $bottom;
			if ( $width < 1 || $width > 12 ) {
				$width = 12;
			}
		}
		
		return $width;
	}

	function map_shortcode() {
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();
		
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Column', 'bold-builder' ), 'description' => esc_html__( 'Column element', 'bold-builder' ), 'width_param' => 'width', 'container' => 'vertical', 'accept' => array( 'bt_bb_section' => false, 'bt_bb_row' => false, 'bt_bb_column' => false, 'bt_bb_column_inner' => false, 'bt_bb_tab_item' => false, 'bt_bb_accordion_item' => false, 'bt_bb_cost_calculator_item' => false, 'bt_cc_group' => false, 'bt_cc_multiply' => false, 'bt_cc_item' => false, 'bt_bb_content_slider_item' => false, 'bt_bb_google_maps_location' => false, '_content' => false ), 'accept_all' => true, 'toggle' => false, 'responsive_override' => true,
			'params' => array(
				array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => esc_html__( 'Align', 'bold-builder' ), 'preview' => true, 'responsive_override' => true,
					'value' => array(
						esc_html__( 'Left', 'bold-builder' ) => 'left',
						esc_html__( 'Center', 'bold-builder' ) => 'center',
						esc_html__( 'Right', 'bold-builder' ) => 'right'
					)
				),
				array( 'param_name' => 'vertical_align', 'type' => 'dropdown', 'heading' => esc_html__( 'Vertical align', 'bold-builder' ), 'preview' => true,
					'value' => array(
						esc_html__( 'Top', 'bold-builder' )     => 'top',
						esc_html__( 'Middle', 'bold-builder' )  => 'middle',
						esc_html__( 'Bottom', 'bold-builder' )  => 'bottom'
					)
				),				
				array( 'param_name' => 'padding', 'type' => 'dropdown', 'heading' => esc_html__( 'Padding', 'bold-builder' ), 'preview' => true,
					'value' => array(
						esc_html__( 'Normal', 'bold-builder' ) => 'normal',
						esc_html__( 'Double', 'bold-builder' ) => 'double',
						esc_html__( 'Text Indent', 'bold-builder' ) => 'text_indent'				
					)
				),
				array( 'param_name' => 'order', 'type' => 'dropdown', 'heading' => esc_html__( 'Order', 'bold-builder' ), 'default' => '0', 'responsive_override' => true, 'description' => esc_html__( 'Columns are placed in the visual order according to selected order, lowest values first.', 'bold-builder' ),
					'value' => array(
						esc_html__( ' -5', 'bold-builder' ) => '-5',
						esc_html__( ' -4', 'bold-builder' ) => '-4',
						esc_html__( ' -3', 'bold-builder' ) => '-3',
						esc_html__( ' -2', 'bold-builder' ) => '-2',
						esc_html__( ' -1', 'bold-builder' ) => '-1',
						esc_html__( ' 0 (default)', 'bold-builder' ) => '0',
						esc_html__( ' 1', 'bold-builder' ) => '1',
						esc_html__( ' 2', 'bold-builder' ) => '2',
						esc_html__( ' 3', 'bold-builder' ) => '3',
						esc_html__( ' 4', 'bold-builder' ) => '4',
						esc_html__( ' 5', 'bold-builder' ) => '5'
					)
				),
				array( 'param_name' => 'background_image', 'type' => 'attach_image',  'preview' => true, 'heading' => esc_html__( 'Background image', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'inner_background_image', 'type' => 'attach_image',  'preview' => true, 'heading' => esc_html__( 'Inner background image', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'lazy_load', 'type' => 'dropdown', 'default' => 'yes', 'heading' => esc_html__( 'Lazy load background image', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ),
					'value' => array(
						esc_html__( 'No', 'bold-builder' ) => 'no',
						esc_html__( 'Yes', 'bold-builder' ) => 'yes'
					) ),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'bold-builder' ), 'description' => esc_html__( 'Define color schemes in Bold Builder settings or define accent and alternate colors in theme customizer (if avaliable)', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true, 'group' => esc_html__( 'Design', 'bold-builder' )  ),
				array( 'param_name' => 'background_color', 'type' => 'colorpicker', 'heading' => esc_html__( 'Background color', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'inner_background_color', 'type' => 'colorpicker', 'heading' => esc_html__( 'Inner background color', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'opacity', 'type' => 'textfield', 'heading' => esc_html__( 'Background color opacity (deprecated)', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ) )
			)
		) );
	}
	
	static function hex2rgb( $hex ) {
		$hex = str_replace( '#', '', $hex );
		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = array( $r, $g, $b );
		return $rgb;
	}
}