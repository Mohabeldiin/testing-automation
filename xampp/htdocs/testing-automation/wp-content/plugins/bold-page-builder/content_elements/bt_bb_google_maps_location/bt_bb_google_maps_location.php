<?php

class bt_bb_google_maps_location extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'latitude'  => '',
			'longitude' => '',
			'icon'      => ''
		) ), $atts, $this->shortcode ) );
		
        $class_master = 'bt_bb_map_location'; 
		
		$class = array( $this->shortcode, $class_master );  
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		if ( $content == '' ) {
			$class[] = $this->shortcode . '_without_content';
            $class[] = $class_master . '_without_content';
		}
		
		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}

		if ( $icon != '' ) {
			$icon = wp_get_attachment_image_src( $icon, 'full' );
			$icon = $icon[0];
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . ' data-lat="' . esc_attr( $latitude ) . '" data-lng="' . esc_attr( $longitude ) . '" data-icon="' . esc_attr( $icon ) . '">' . wptexturize( do_shortcode( $content ) ) . '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Google Maps Location', 'bold-builder' ), 'description' => esc_html__( 'Google Map Location', 'bold-builder' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_headline' => true, 'bt_bb_text' => true, 'bt_bb_button' => true, 'bt_bb_icon' => true, 'bt_bb_service_icon' => true, 'bt_bb_service' => true, 'bt_bb_image' => true, 'bt_bb_separator' => true ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'latitude', 'type' => 'textfield', 'heading' => esc_html__( 'Latitude', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'longitude', 'type' => 'textfield', 'heading' => esc_html__( 'Longitude', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'icon', 'type' => 'attach_image', 'heading' => esc_html__( 'Icon', 'bold-builder' ), 'preview' => true )
			)
		) );
	}
}