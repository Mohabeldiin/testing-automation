<?php

class bt_bb_accordion extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'color_scheme' => '',
			'style'        => '',
			'shape'        => '',
			'closed'       => ''
		) ), $atts, $this->shortcode ) );
		
		wp_enqueue_script( 
			'bt_bb_accordion',
			plugin_dir_url( __FILE__ ) . 'bt_bb_accordion.js',
			array( 'jquery' ),
			BT_BB_VERSION,
			true
		);

		$class = array( $this->shortcode );

		if ( $el_class != '' ) {
			$class[] = $el_class;
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}

		if ( $color_scheme != '' ) {
			$class[] = $this->prefix . 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}

		if ( $style != '' ) {
			$class[] = $this->prefix . 'style' . '_' . $style;
		}		

		if ( $shape != '' ) {
			$class[] = $this->prefix . 'shape' . '_' . $shape;
		}

		$data_attr = '';
		if ( $closed == 'closed' ) {
			$data_attr = ' ' . 'data-closed=closed';
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$content = do_shortcode( $content );

		$output = '';

		$output .= '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . $data_attr . '>' . $content . '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {
		
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();			
		
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Accordion', 'bold-builder' ), 'description' => esc_html__( 'Accordion container', 'bold-builder' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_accordion_item' => true ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'bold-builder' ), 'description' => esc_html__( 'Define color schemes in Bold Builder settings or define accent and alternate colors in theme customizer (if avaliable)', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true ),
				array( 'param_name' => 'style', 'type' => 'dropdown', 'heading' => esc_html__( 'Style', 'bold-builder' ), 'preview' => true,
					'value' => array(
						esc_html__( 'Outline', 'bold-builder' ) => 'outline',
						esc_html__( 'Filled', 'bold-builder' ) => 'filled',
						esc_html__( 'Simple', 'bold-builder' ) => 'simple'
					)
				),
				array( 'param_name' => 'shape', 'type' => 'dropdown', 'heading' => esc_html__( 'Shape', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Square', 'bold-builder' ) => 'square',
						esc_html__( 'Rounded', 'bold-builder' ) => 'rounded',
						esc_html__( 'Round', 'bold-builder' ) => 'round'
					)
				),
				array( 'param_name' => 'closed', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bold-builder' ) => 'closed' ), 'heading' => esc_html__( 'All items closed initially', 'bold-builder' ), 'preview' => true )
			)
		) );
	}
}