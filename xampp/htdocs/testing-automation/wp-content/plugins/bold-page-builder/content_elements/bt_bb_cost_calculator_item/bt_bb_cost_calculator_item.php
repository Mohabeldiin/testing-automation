<?php

class bt_bb_cost_calculator_item extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {	
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'title' => '',
			'type'  => '',
			'value' => ''
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
		
		if ( $type == 'number' ) {
			
			$output .= '<div class="bt_bb_widget_number" data-unit-value="' . esc_attr( $value ) . '"><input type="number" step="1" min="0"></div>';
			
		} else if ( $type == 'select' ) {
			
			$items_arr = preg_split( '/$\R?^/m', $value );
			
			$output .= '<div class="bt_bb_widget_select">';
				$output .= '<div class="bt_bb_widget_select_selected">';
					$output .= '<div>' . esc_html__( 'Select...', 'bold-builder' ) . '</div>';
					$output .= '<div></div>';
				$output .= '</div>';
				$output .= '<div class="bt_bb_widget_select_items">';
					$i = 0;
					foreach( $items_arr as $item ) {
						if ( $i == 0 ) {
							$output .= '<div data-value="0">';
								$output .= '<div>' . esc_html__( 'Select...', 'bold-builder' ) . '</div>';
								$output .= '<div></div>';
							$output .= '</div>';
						}
						$item_arr = explode( ';', $item );
						$output .= '<div data-value="' . esc_attr( $item_arr[1] ) . '">';
							$output .= '<div>' . $item_arr[0] . '</div>';
							$output .= '<div>' . $item_arr[2] . '</div>';
						$output .= '</div>';
						$i++;
					}
				$output .= '</div>';
			$output .= '</div>';
			
		} else if ( $type == 'switch' ) {
			
			$output .= '<div class="bt_bb_widget_switch" data-on="' . esc_attr( $value ) . '"><div></div></div>';
			
		}
		
		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $output . '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );
		
		return $output;

	}	

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Simple Cost Calculator Item', 'bold-builder' ), 'description' => esc_html__( 'Simple cost calculator item element', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'title', 'type' => 'textfield', 'heading' => esc_html__( 'Title', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'type', 'type' => 'dropdown', 'heading' => esc_html__( 'Input type', 'bold-builder' ), 'preview' => true, 
					'value' => array(
						esc_html__( 'Number', 'bold-builder' ) => 'number',
						esc_html__( 'Select', 'bold-builder' ) => 'select',
						esc_html__( 'Switch', 'bold-builder' ) => 'switch'
					) 
				),
				array( 'param_name' => 'value', 'type' => 'textarea', 'heading' => esc_html__( 'Value', 'bold-builder' ), 'description' => esc_html__( 'Unit value for Number / name;value;description separated by new line for Select / value for Switch', 'bold-builder' ) ),
			)
		) );
	}
}