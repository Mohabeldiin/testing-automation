<?php

class bt_bb_contact_form_7 extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'contact_form_id'       => ''
		) ), $atts, $this->shortcode ) );

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
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		if ( intval( $contact_form_id ) > 0 ) {
			$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>';
				$output .= do_shortcode('[contact-form-7 id="' . $contact_form_id . '"]');
			$output .= '</div>';			
		}

		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {
		
		$args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
		$forms_data = array();
		if ( $data = get_posts( $args ) ) {
			foreach( $data as $key ){
				$forms_data[ $key -> post_title ] = $key -> ID;
			}
		} else {
			$forms_data[ '0' ] = esc_html__( 'No Contact Form found', 'bold-builder' );
		}

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Contact Form 7', 'bold-builder' ), 'description' => esc_html__( 'Choose CF7 form', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'contact_form_id', 'type' => 'dropdown', 'heading' => esc_html__( 'Contact form 7', 'bold-builder' ), 'preview' => true,
					'value' => $forms_data )		
			) )
		);
	}
}