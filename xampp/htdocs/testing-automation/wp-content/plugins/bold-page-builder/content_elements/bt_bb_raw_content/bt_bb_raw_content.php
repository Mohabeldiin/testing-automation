<?php

class bt_bb_raw_content extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'raw_content' => ''
		) ), $atts, $this->shortcode ) );
		
		$output = apply_filters( 'bt_bb_general_output', $raw_content, $atts );
		$output = apply_filters( $this->shortcode . '_output', $raw_content, $atts );
		
		return base64_decode( $raw_content );

	}
	
	function add_params() {
		// removes default params from BT_BB_Element
	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Raw Content', 'bold-builder' ), 'description' => esc_html__( 'Raw HTML/JS content', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'raw_content', 'type' => 'textarea_object', 'heading' => esc_html__( 'Raw content', 'bold-builder' ) )
			)
		) );
	}
}