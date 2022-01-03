<?php

class bt_bb_cost_calculator extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'currency'     => '',
			'color_scheme' => ''
		) ), $atts, $this->shortcode ) );

		wp_enqueue_script( 
			'bt_bb_cost_calculator',
			plugin_dir_url( __FILE__ ) . 'bt_bb_cost_calculator.js',
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
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$content = do_shortcode( $content );
		
		$content .= '<div class="' . esc_attr( $this->shortcode ) . '_total"><div class="' . esc_attr( $this->shortcode ) . '_total_text">' . esc_html__( 'Total', 'bold-builder' ) . '</div><div class="' . esc_attr( $this->shortcode ) . '_total_amount">0.00</div></div>';

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $content . '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {
		
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();
		
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Simple Cost Calculator', 'bold-builder' ), 'description' => esc_html__( 'Simple cost calculator container', 'bold-builder' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_cost_calculator_item' => true ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode, 'show_settings_on_create' => false,
			'params' => array(
				array( 'param_name' => 'currency', 'type' => 'textfield', 'heading' => esc_html__( 'Currency', 'bold-builder' ) ),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'bold-builder' ), 'description' => esc_html__( 'Define color schemes in Bold Builder settings or define accent and alternate colors in theme customizer (if avaliable)', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true ),
			)
		) );
	}
}