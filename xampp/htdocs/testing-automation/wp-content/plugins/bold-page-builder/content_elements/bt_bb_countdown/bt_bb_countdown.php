<?php

class bt_bb_countdown extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'datetime'        => '',
			'size'            => '',
			'hide_indication' => ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode, 'btCounterHolder' );

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

		$class[] = $size;

		$datetime = sanitize_text_field( $datetime );
		
		$target = strtotime( $datetime );
		$now = strtotime( 'now' );
		
		$init_seconds = $target - $now;
		if ( $init_seconds < 0 ) {
			$init_seconds = 0;
		}
		
		$d_text = esc_html__( 'Days', 'bold-builder' );
		$h_text = esc_html__( 'Hours', 'bold-builder' );
		$m_text = esc_html__( 'Minutes', 'bold-builder' );
		$s_text = esc_html__( 'Seconds', 'bold-builder' );
		
		if ( $hide_indication == 'yes' ) {
			$d_text = '';
			$h_text = '';
			$m_text = '';
			$s_text = '';
		}

		$class = apply_filters( $this->shortcode . '_class', $class, $atts );		

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>';
			$output .= '<div class="btCountdownHolder" data-init-seconds="' . esc_attr( $init_seconds ) . '">';
							
				$output .= '<span class="days" data-text="' . esc_attr( $d_text ) . '"></span>';
				
				$output .= '<span class="hours"><span class="n0"><span></span><span></span></span><span class="n1"><span></span><span></span></span><span class="hours_text"><span>' . $h_text . '</span></span></span>';
				
				$output .= '<span class="minutes"><span class="n0"><span></span><span></span></span><span class="n1"><span></span><span></span></span><span class="minutes_text"><span>' . $m_text . '</span></span></span>';
				
				$output .= '<span class="seconds"><span class="n0"><span></span><span></span></span><span class="n1"><span></span><span></span></span><span class="seconds_text"><span>' . $s_text . '</span></span></span>';
			$output .= '</div>';
		$output .= '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );		

		return $output;
	}

	function map_shortcode() {

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Countdown', 'bold-builder' ), 'description' => esc_html__( 'Animated countdown', 'bold-builder' ),  
			'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'datetime', 'type' => 'textfield', 'heading' => esc_html__( 'Target date and time', 'bold-builder' ), 'description' => esc_html__( 'YY-mm-dd HH:mm:ss, e.g. 2017-02-22 22:45:00' ), 'preview' => true ),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => esc_html__( 'Size', 'bold-builder' ), 'preview' => true,
					'value' => array(
						esc_html__( 'Normal', 'bold-builder' ) => 'btCounterNormalSize',
						esc_html__( 'Large', 'bold-builder' ) => 'btCounterLargeSize'
				) ),
				array( 'param_name' => 'hide_indication', 'type' => 'dropdown', 'heading' => esc_html__( 'Hide indication', 'bold-builder' ), 'description' => esc_html__( 'Hide indication of days, hours, minutes and seconds', 'bold-builder' ),
					'value' => array(
						esc_html__( 'No', 'bold-builder' ) => 'no',
						esc_html__( 'Yes', 'bold-builder' ) => 'yes'
				) )
			) 
		) );

	}
}