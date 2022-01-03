<?php

class bt_bb_content_slider extends BT_BB_Element {
	
	public $auto_play = '';

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'height'    			=> '',
			'show_dots' 			=> '',
			'animation' 			=> 'slide',
			'gap' 					=> '',
			'arrows_size' 			=> '',
			'pause_on_hover'     	=> '',
			'slides_to_show' 		=> '',
			'auto_play' 			=> ''
		) ), $atts, $this->shortcode ) );
		
		$class = array( $this->shortcode );
		$slider_class = array( 'slick-slider' );
		
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
		
		if ( $gap != '' ) {
			$class[] = $this->prefix . 'gap' . '_' . $gap;
		}
		
		if ( $arrows_size != '' ) {
			$class[] = $this->prefix . 'arrows_size' . '_' . $arrows_size;
		}
		
		if ( $show_dots != '' ) {
			$class[] = $this->prefix . 'show_dots_' . $show_dots;
		}
		
		if ( $height != '' ) {
			$class[] = $this->prefix . 'height' . '_' . $height;
		}
		
		if ( $animation != '' ) {
			$class[] = $this->prefix . 'animation' . '_' . $animation;
		}
		
		$data_slick  = ' data-slick=\'{ "lazyLoad": "progressive", "cssEase": "ease-out", "speed": "600", "accessibility": false';
		
		if ( $animation == 'fade' ) {
			$data_slick .= ', "fade": true';
			$slider_class[] = 'fade';
			$slides_to_show = 1;
		}
		
		if ( $arrows_size != 'no_arrows' ) {
			$data_slick  .= ', "prevArrow": "&lt;button type=\"button\" class=\"slick-prev\" aria-label=\"' . esc_html__( 'Previous', 'bold-builder' ) . '\" tabindex=\"0\" role=\"button\"&gt;&lt;/button&gt;", "nextArrow": "&lt;button type=\"button\" class=\"slick-next\" aria-label=\"' . esc_html__( 'Next', 'bold-builder' ) . '\" tabindex=\"0\" role=\"button\"&gt;&lt;/button&gt;"';
		} else {
			$data_slick .= ', "arrows": false';
		}
		
		if ( $height != 'keep-height' ) {
			$data_slick .= ', "adaptiveHeight": true';
		}
		
		if ( $show_dots != 'hide' ) {
			$data_slick .= ', "dots": true' ;
		}
		
		if ( $slides_to_show > 1 ) {
			$data_slick .= ',"slidesToShow": ' . intval( $slides_to_show );
			$class[] = $this->prefix . 'multiple_slides';
		}
		
		if ( $auto_play != '' ) {
			$data_slick .= ',"autoplay": true, "autoplaySpeed": ' . intval( $auto_play );
		}
		
		if ( $pause_on_hover == 'no' ) {
			$data_slick .= ',"pauseOnHover": false';
		}

		if ( is_rtl() ) {
			$data_slick .= ', "rtl": true' ;
		}
		
		if ( $slides_to_show > 1 ) {
			$data_slick .= ', "responsive": [';
			if ( $slides_to_show > 1 ) {
				$data_slick .= '{ "breakpoint": 480, "settings": { "slidesToShow": 1, "slidesToScroll": 1 } }';	
			}
			if ( $slides_to_show > 2 ) {
				$data_slick .= ',{ "breakpoint": 768, "settings": { "slidesToShow": 2, "slidesToScroll": 2 } }';	
			}
			if ( $slides_to_show > 3 ) {
				$data_slick .= ',{ "breakpoint": 920, "settings": { "slidesToShow": 3, "slidesToScroll": 3 } }';	
			}
			if ( $slides_to_show > 4 ) {
				$data_slick .= ',{ "breakpoint": 1024, "settings": { "slidesToShow": 3, "slidesToScroll": 3 } }';	
			}				
			$data_slick .= ']';
		}
		$data_slick = $data_slick . '}\' ';
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '><div class="' . implode( ' ', $slider_class ) . '" ' . $data_slick .  '>' . do_shortcode( $content ) . '</div></div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );
		
		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Slider', 'bold-builder' ), 'description' => esc_html__( 'Slider with custom content', 'bold-builder' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_content_slider_item' => true ), 'toggle' => true, 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'height', 'type' => 'dropdown', 'preview' => true, 'heading' => esc_html__( 'Size', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Auto', 'bold-builder' ) => 'auto',
						esc_html__( 'Keep height', 'bold-builder' ) => 'keep-height',
						esc_html__( 'Half screen', 'bold-builder' ) => 'half_screen',
						esc_html__( 'Full screen', 'bold-builder' ) => 'full_screen'
					)
				),
				array( 'param_name' => 'animation', 'preview' => true, 'default' => 'slide', 'type' => 'dropdown', 'heading' => esc_html__( 'Animation', 'bold-builder' ), 'description' => esc_html__( 'If fade is selected, number of slides to show will be 1', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Default (slide)', 'bold-builder' ) => 'slide',
						esc_html__( 'Fade', 'bold-builder' ) => 'fade'
					)
				),
				array( 'param_name' => 'arrows_size', 'type' => 'dropdown', 'preview' => true, 'default' => 'normal', 'heading' => esc_html__( 'Navigation arrows size', 'bold-builder' ),
					'value' => array(
						esc_html__( 'No arrows', 'bold-builder' ) => 'no_arrows',
						esc_html__( 'Small', 'bold-builder' ) => 'small',
						esc_html__( 'Normal', 'bold-builder' ) => 'normal',
						esc_html__( 'Large', 'bold-builder' ) => 'large'
					)
				),
				array( 'param_name' => 'show_dots', 'type' => 'dropdown', 'heading' => esc_html__( 'Dots navigation', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Bottom', 'bold-builder' ) => 'bottom',
						esc_html__( 'Below', 'bold-builder' ) => 'below',
						esc_html__( 'Hide', 'bold-builder' ) => 'hide'
					)
				),
				array( 'param_name' => 'pause_on_hover', 'default' => 'yes', 'type' => 'dropdown', 'heading' => esc_html__( 'Pause slideshow on hover', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Yes', 'bold-builder' ) => 'yes',
						esc_html__( 'No', 'bold-builder' ) => 'no'
					)
				),
				array( 'param_name' => 'slides_to_show', 'type' => 'textfield', 'preview' => true, 'default' => 1, 'heading' => esc_html__( 'Number of slides to show', 'bold-builder' ), 'description' => esc_html__( 'e.g. 3', 'bold-builder' ) ),
				array( 'param_name' => 'gap', 'type' => 'dropdown', 'heading' => esc_html__( 'Gap', 'bold-builder' ),
					'value' => array(
						esc_html__( 'No gap', 'bold-builder' ) => 'no_gap',
						esc_html__( 'Small', 'bold-builder' ) => 'small',
						esc_html__( 'Normal', 'bold-builder' ) => 'normal',
						esc_html__( 'Large', 'bold-builder' ) => 'large'
					)
				),
				array( 'param_name' => 'auto_play', 'type' => 'textfield', 'heading' => esc_html__( 'Autoplay interval (ms)', 'bold-builder' ), 'description' => esc_html__( 'e.g. 2000' ) )
			)
		) );
	}
}