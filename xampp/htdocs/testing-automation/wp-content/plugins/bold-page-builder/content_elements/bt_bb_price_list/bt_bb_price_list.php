<?php

class bt_bb_price_list extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'title'        => '',
			'subtitle'     => '',
			'currency'     => '',
			'price'        => '',			
			'items'        => '',
			'color_scheme' => ''
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
		
		$output = '<div class="' . esc_attr( $this->shortcode ) . '_title">' . $title . '</div>';
		$output .= '<div class="' . esc_attr( $this->shortcode ) . '_subtitle">' . $subtitle . '</div>';
		$output .= '<div class="' . esc_attr( $this->shortcode ) . '_price"><span class="' . esc_attr( $this->shortcode ) . '_currency">' . $currency . '</span><span class="' . esc_attr( $this->shortcode ) . '_amount">' . $price . '</span></div>';

		if ( $items != '' ) {
                    
                        if ( base64_encode(base64_decode($items, true)) === $items){
                            $items = base64_decode( $items );
                        }
                        
			$items_arr = preg_split( '/$\R?^/m', $items );

			$output .= '<ul>';
				foreach ( $items_arr as $item ) {
					$output .= '<li>' . $item . '</li>';
				}
			$output .= '</ul>';
		
		}
		
		if ( $color_scheme != '' ) {
			$class[] = $this->prefix . 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $output . '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {
		
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();			
		
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Price List', 'bold-builder' ), 'description' => esc_html__( 'List of items with total price', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'title', 'type' => 'textfield', 'heading' => esc_html__( 'Title', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'subtitle', 'type' => 'textfield', 'heading' => esc_html__( 'Subtitle', 'bold-builder' ) ),
				array( 'param_name' => 'currency', 'type' => 'textfield', 'heading' => esc_html__( 'Currency', 'bold-builder' ) ),
				array( 'param_name' => 'price', 'type' => 'textfield', 'heading' => esc_html__( 'Price', 'bold-builder' ) ),				
				array( 'param_name' => 'items', 'type' => 'textarea_object', 'heading' => esc_html__( 'Items', 'bold-builder' ) ),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'bold-builder' ), 'description' => esc_html__( 'Define color schemes in Bold Builder settings or define accent and alternate colors in theme customizer (if avaliable)', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true ),			
			)
		) );
	}
}