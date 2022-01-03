<?php

class bt_bb_text extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array() ), $atts, $this->shortcode ) );
		
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
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );
		
		if ( strpos( $content, '[' ) == 0 && substr( $content, -1 ) == ']' ) {
			$output = '<div ' . $id_attr . ' class="' . implode( ' ', $class ) . '" ' . $style_attr . '>' . do_shortcode( $content ) . '</div>';
		} else {
			$output = '<div ' . $id_attr . ' class="' . implode( ' ', $class ) . '" ' . $style_attr . '>' . wptexturize( wpautop ( do_shortcode( $content ) ) ) . '</div>';
		}
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;
		
	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Text', 'bold-builder' ), 'description' => esc_html__( 'Text element', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode, 'container' => 'vertical', 'params' => array(), 'accept' => array( '_content' => true ), 'toggle' => true, 'show_settings_on_create' => false ) );
	} 

}