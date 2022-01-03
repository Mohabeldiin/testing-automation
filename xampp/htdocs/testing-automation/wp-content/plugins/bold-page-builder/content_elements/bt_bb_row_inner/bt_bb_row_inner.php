<?php

class bt_bb_row_inner extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'column_gap'     	=> '',
			'row_width'     	=> ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );

		if ( $el_class != '' ) {
			$class[] = $el_class;
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = 'id="' . esc_attr( $el_id ) . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . esc_attr( $el_style ) . '"';
		}

		if ( $column_gap != '' ) {
			$class[] = $this->prefix . 'column_inner_gap' . '_' . $column_gap;
		}

		if ( $row_width != '' && $row_width != 'default' ) {
			// $outer_class[] = $this->prefix . 'row_width' . '_' . $row_width;
			$class[] = $this->prefix . 'row_width' . '_' . 'boxed_1200';
			if ( $row_width == 'boxed_1200_left' ) { $class[] = 'bt_bb_row_push_left'; }
			if ( $row_width == 'boxed_1200_left_content_wide' ) { $class[] = 'bt_bb_row_push_left'; $class[] = 'bt_bb_content_wide'; }
			if ( $row_width == 'boxed_1200_right' ) { $class[] = 'bt_bb_row_push_right'; }
			if ( $row_width == 'boxed_1200_right_content_wide' ) { $class[] = 'bt_bb_row_push_right'; $class[] = 'bt_bb_content_wide'; }
			if ( $row_width == 'boxed_1200_left_right' ) { $class[] = 'bt_bb_row_push_right'; $class[] = 'bt_bb_row_push_left'; }
			if ( $row_width == 'boxed_1200_left_right_content_wide' ) { $class[] = 'bt_bb_row_push_right'; $class[] = 'bt_bb_row_push_left'; $class[] = 'bt_bb_content_wide'; }
		}

		$class = apply_filters( $this->shortcode . '_class', $class, $atts );		
	
		$output = '<div ' . $id_attr . ' class="' . implode( ' ', $class ) . '" ' . $style_attr . '>';
			$output .= do_shortcode( $content );
		$output .= '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {		
		
			bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Inner Row', 'bold-builder' ), 'description' => esc_html__( 'Inner Row element', 'bold-builder' ), 'container' => 'horizontal', 
				'accept' => array( 'bt_bb_column_inner' => true ), 'toggle' => true,  'show_settings_on_create' => false, 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
				'params' => array(
					array( 'param_name' => 'column_gap', 'type' => 'dropdown', 'heading' => esc_html__( 'Column gap', 'bold-builder' ), 'preview' => true,
						'value' => array(
							esc_html__( 'Default', 'bold-builder' ) => '',
							esc_html__( '0px', 'bold-builder' ) => '0',
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
					)
				)
			) 
		);
	}

}