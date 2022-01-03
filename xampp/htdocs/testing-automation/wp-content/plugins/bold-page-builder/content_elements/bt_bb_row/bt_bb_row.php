<?php

class bt_bb_row extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {

		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'column_gap'  			=> '',
			'row_width'  			=> '',
			'color_scheme' 			=> '',
			'background_color' 		=> '',
			'opacity' 				=> ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );
		$outer_class = array( 'bt_bb_row_wrapper' );

		if ( $el_class != '' ) {
			$class[] = $el_class;
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = 'id="' . esc_attr( $el_id ) . '"';
		}
		
		if ( $background_color != '' ) {
			if ( strpos( $background_color, '#' ) !== false ) {
				$background_color = bt_bb_hex2rgb( $background_color );
				if ( $opacity == '' ) {
					$opacity = 1;
				}
				$el_style .= ' background-color: rgba(' . $background_color[0] . ', ' . $background_color[1] . ', ' . $background_color[2] . ', ' . $opacity . ');';
			} else {
				$el_style .= 'background-color:' . $background_color . ';';
			}
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . esc_attr( $el_style ) . '"';
		}

		if ( $color_scheme != '' ) {
			$class[] = $this->prefix . 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}

		if ( $column_gap != '' ) {
			$class[] = $this->prefix . 'column_gap' . '_' . $column_gap;
		}

		if ( $row_width != '' && $row_width != 'default' ) {
			// $outer_class[] = $this->prefix . 'row_width' . '_' . $row_width;
			$outer_class[] = $this->prefix . 'row_width' . '_' . 'boxed_1200';
			if ( $row_width == 'boxed_1200_left' ) { $outer_class[] = 'bt_bb_row_push_left'; }
			if ( $row_width == 'boxed_1200_left_content_wide' ) { $outer_class[] = 'bt_bb_row_push_left'; $outer_class[] = 'bt_bb_content_wide'; }
			if ( $row_width == 'boxed_1200_right' ) { $outer_class[] = 'bt_bb_row_push_right'; }
			if ( $row_width == 'boxed_1200_right_content_wide' ) { $outer_class[] = 'bt_bb_row_push_right'; $outer_class[] = 'bt_bb_content_wide'; }
			if ( $row_width == 'boxed_1200_left_right' ) { $outer_class[] = 'bt_bb_row_push_right'; $outer_class[] = 'bt_bb_row_push_left'; }
			if ( $row_width == 'boxed_1200_left_right_content_wide' ) { $outer_class[] = 'bt_bb_row_push_right'; $outer_class[] = 'bt_bb_row_push_left'; $outer_class[] = 'bt_bb_content_wide'; }
		}

		$style_attr = apply_filters( $this->shortcode . '_style_attr', $style_attr, $atts );

		$class = apply_filters( $this->shortcode . '_class', $class, $atts );
		$class_attr = implode( ' ', $class );
		$outer_class_attr = implode( ' ', $outer_class );

		$output = '<div class="' . esc_attr( $outer_class_attr ) . '">';
			$output .= '<div ' . $id_attr . ' class="' . esc_attr( $class_attr ) . '" ' . $style_attr . '>';
				$output .= do_shortcode( $content );
			$output .= '</div>';
		$output .= '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {
		
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();
		
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Row', 'bold-builder' ), 'description' => esc_html__( 'Row element', 'bold-builder' ), 'container' => 'horizontal', 'accept' => array( 'bt_bb_column' => true ), 'toggle' => true, 'auto_add' => 'bt_bb_column', 'show_settings_on_create' => false,
			'params' => array(
				array( 'param_name' => 'column_gap', 'type' => 'dropdown', 'heading' => esc_html__( 'Column gap', 'bold-builder' ), 'preview' => true,
					'value' => array(
						esc_html__( 'Default', 'bold-builder' ) => '',
						esc_html__( '0px', 'bold-builder' ) => '0',
						esc_html__( 'Extra small', 'bold-builder' ) => 'extra_small',
						esc_html__( 'Small', 'bold-builder' ) => 'small',		
						esc_html__( 'Normal', 'bold-builder' ) => 'normal',
						esc_html__( 'Medium', 'bold-builder' ) => 'medium',
						esc_html__( 'Large', 'bold-builder' ) => 'large',
						esc_html__( '5px', 'bold-builder' ) => '5',
						esc_html__( '10px', 'bold-builder' ) => '10',
						esc_html__( '15px', 'bold-builder' ) => '15',
						esc_html__( '20px', 'bold-builder' ) => '20',
						esc_html__( '25px', 'bold-builder' ) => '25',
						esc_html__( '30px', 'bold-builder' ) => '30',
						esc_html__( '35px', 'bold-builder' ) => '35',
						esc_html__( '40px', 'bold-builder' ) => '40',
						esc_html__( '45px', 'bold-builder' ) => '45',
						esc_html__( '50px', 'bold-builder' ) => '50',
						esc_html__( '60px', 'bold-builder' ) => '60',
						esc_html__( '70px', 'bold-builder' ) => '70',
						esc_html__( '80px', 'bold-builder' ) => '80',
						esc_html__( '90px', 'bold-builder' ) => '90',
						esc_html__( '100px', 'bold-builder' ) => '100'
					)
				),
				array( 'param_name' => 'row_width', 'type' => 'dropdown', 'heading' => esc_html__( 'Row width', 'bold-builder' ), 'description' => esc_html__( 'For the best experience set section width to wide.', 'bold-builder' ), 'preview' => true,
					'value' => array(
						esc_html__( 'Default', 'bold-builder' ) => 'default',
						esc_html__( 'Boxed 1200px', 'bold-builder' ) => 'boxed_1200',
						esc_html__( 'Boxed left 1200px', 'bold-builder' ) => 'boxed_1200_left',
						esc_html__( 'Boxed left 1200px, wide content', 'bold-builder' ) => 'boxed_1200_left_content_wide',
						esc_html__( 'Boxed right 1200px', 'bold-builder' ) => 'boxed_1200_right',
						esc_html__( 'Boxed right 1200px, wide content', 'bold-builder' ) => 'boxed_1200_right_content_wide',
						esc_html__( 'Boxed left and right 1200px', 'bold-builder' ) => 'boxed_1200_left_right',
						esc_html__( 'Boxed left and right 1200px, wide content', 'bold-builder' ) => 'boxed_1200_left_right_content_wide'
					)
				),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'bold-builder' ), 'description' => esc_html__( 'Define color schemes in Bold Builder settings or define accent and alternate colors in theme customizer (if avaliable)', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true, 'group' => esc_html__( 'Design', 'bold-builder' )  ),
				array( 'param_name' => 'background_color', 'type' => 'colorpicker', 'heading' => esc_html__( 'Background color', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'opacity', 'type' => 'textfield', 'heading' => esc_html__( 'Background color opacity (deprecated)', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ) )			
			)
		) );
	}

}