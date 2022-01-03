<?php

class bt_bb_instagram extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'username'     => '',
			'number'       => '',
			'target'       => '',
			'columns'      => '',
			'gap'      	   => '',
			'hashtag'      => '',
			'cache'        => '',
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		if ( $columns != '' ) {
			$class[] = $this->prefix . 'columns' . '_' . $columns;
		}
		
		if ( $gap != '' ) {
			$class[] = $this->prefix . 'gap' . '_' . $gap;
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

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>';
			ob_start();
			the_widget( 'BB_Instagram', array( 'username' => $username, 'number' => $number, 'target' => $target, 'hashtag' => $hashtag, 'cache' => $cache ) );
			$output .= ob_get_contents();
			ob_end_clean();
		$output .= '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Instagram (deprecated)', 'bold-builder' ), 'description' => esc_html__( 'Recent Instagram images', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'username', 'type' => 'textfield', 'heading' => esc_html__( 'Instagram username', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'number', 'type' => 'textfield', 'heading' => esc_html__( 'Number of images', 'bold-builder' ), 'preview' => true, 'value' => '8' ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'default' => '_self', 'heading' => esc_html__( 'Target', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Self', 'bold-builder' ) => '_self',
						esc_html__( 'Blank', 'bold-builder' ) => '_blank'
					)
				),
				array( 'param_name' => 'columns', 'type' => 'dropdown', 'heading' => esc_html__( 'Columns', 'bold-builder' ), 'preview' => true,
					'value' => array(
						esc_html__( '1', 'bold-builder' ) => '1',
						esc_html__( '2', 'bold-builder' ) => '2',
						esc_html__( '3', 'bold-builder' ) => '3',
						esc_html__( '4', 'bold-builder' ) => '4',
						esc_html__( '5', 'bold-builder' ) => '5',
						esc_html__( '6', 'bold-builder' ) => '6'
					)
				),
				array( 'param_name' => 'gap', 'type' => 'dropdown', 'default' => 'small', 'heading' => esc_html__( 'Gap', 'bold-builder' ),
					'value' => array(
						esc_html__( 'No gap', 'bold-builder' ) => 'no_gap',
						esc_html__( 'Extra small', 'bold-builder' ) => 'extrasmall',
						esc_html__( 'Small', 'bold-builder' ) => 'small',
						esc_html__( 'Normal', 'bold-builder' ) => 'normal',
						esc_html__( 'Large', 'bold-builder' ) => 'large'
					)
				),
				array( 'param_name' => 'hashtag', 'type' => 'textfield', 'heading' => esc_html__( 'Hashtag', 'bold-builder' ) ),
				array( 'param_name' => 'cache', 'type' => 'textfield', 'heading' => esc_html__( 'Cache (minutes)', 'bold-builder' ), 'value' => '15', 'default' => '15' ),
			) )
		);
	}
}