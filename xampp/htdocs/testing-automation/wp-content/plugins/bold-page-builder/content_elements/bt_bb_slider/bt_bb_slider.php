<?php

class bt_bb_slider extends BT_BB_Element {
	
	public $auto_play = '';

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'images'    			=> '',
			'height'    			=> '',
			'size'     				=> 'full',
			'show_arrows'     		=> '',
			'show_dots'     		=> '',
			'animation' 			=> '',
			'slides_to_show' 		=> '',
			'auto_play' 			=> '',
			'pause_on_hover'     	=> '',
			'use_lightbox' 			=> ''
		) ), $atts, $this->shortcode ) );
		
		$slider_class = array( 'slick-slider' );
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

		if ( $height != '' ) {
			$class[] = $this->prefix . 'height' . '_' . $height;
		}	
		
		$data_slick = ' ' . 'data-slick=\'{ "lazyLoad": "progressive", "cssEase": "ease-out", "speed": "300"';
		
		if ( $animation == 'fade' ) {
			$data_slick .= ', "fade": true';
			$slider_class[] = 'fade';
			$slides_to_show = 1;
		}
		
		if ( $height != 'keep-height' ) {
			$data_slick .= ', "adaptiveHeight": true';
		}
		
		if ( $show_dots != 'hide' ) {
			$data_slick .= ', "dots": true' ;
			$class[] = $this->prefix . 'show_dots' . '_' . $show_dots;
		}

		if ( $show_arrows != 'hide' ) {
			$data_slick  .= ', "prevArrow": "&lt;button type=\"button\" class=\"slick-prev\"&gt;", "nextArrow": "&lt;button type=\"button\" class=\"slick-next\"&gt;"';
		} else {
			$data_slick  .= ', "prevArrow": "", "nextArrow": ""';
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

		if ( $use_lightbox == 'use_lightbox' ) {
			$class[] = $this->prefix . 'use_lightbox';
		}
		
		// $class[] = $this->prefix . 'use_lightbox';
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );
		
		$output = '';

		if ( $images != '' ) {
			$image_array = explode( ',', $images );
			foreach( $image_array as $image ) {
				$alt = '';
				$title = '';
				$image_thumb_src = plugins_url( 'placeholder.png', __FILE__ );
				$image_full_src = plugins_url( 'placeholder.png', __FILE__ );
				if ( is_numeric( $image ) ) {
					$post_image = get_post( $image );
					if ( is_object( $post_image ) ) {
						$alt = get_post_meta( $post_image->ID, '_wp_attachment_image_alt', true );
						if ( $alt == '' ) {
							$alt = $post_image->post_excerpt;
						} 
						if ( $alt == '' ) {
							$alt = $post_image->post_title;
						}
						$title = $post_image->post_title;
						if ( $title != '' ) {
							$title = ' title="' . esc_attr( $title ) . '"';	
						}
						$image_thumb = wp_get_attachment_image_src( $image, $size );
						$image_full = wp_get_attachment_image_src( $image, 'full' );
						$image_thumb_src = $image_thumb[0];
						$image_full_src = $image_full[0];
					}
				}
				if ( $height == 'auto' || $height == 'keep-height' ) {
					$output .= '<div class="bt_bb_slider_item" data-src-full="' . esc_url_raw( $image_full_src ) . '"><img src="' . esc_url_raw( $image_thumb_src ) . '" alt="' . esc_attr( $alt ) . '" ' . ( $title ) . '></div>';
				} else {
					$output .= '<div class="bt_bb_slider_item" style="background-image:url(\'' . esc_url_raw( $image_thumb_src ) . '\')" data-src-full="' . esc_url_raw( $image_full_src ) . '"></div>';
				}
				
			}
		}

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '><div class="' . esc_attr( implode( ' ', $slider_class ) ) . '" ' . $data_slick . '>' . $output . '</div></div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );
		
		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Image Slider', 'bold-builder' ), 'description' => esc_html__( 'Slider with images', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'images', 'type' => 'attach_images', 'heading' => esc_html__( 'Images', 'bold-builder' ) ),
				array( 'param_name' => 'height', 'type' => 'dropdown', 'heading' => esc_html__( 'Height', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Auto', 'bold-builder' ) => 'auto',
						esc_html__( 'Keep height', 'bold-builder' ) => 'keep-height',
						esc_html__( 'Half screen', 'bold-builder' ) => 'half_screen',
						esc_html__( 'Full screen', 'bold-builder' ) => 'full_screen'
					)
				),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => esc_html__( 'Images size', 'bold-builder' ), 'preview' => true,
					'value' => bt_bb_get_image_sizes()
				),
				array( 'param_name' => 'animation', 'type' => 'dropdown', 'heading' => esc_html__( 'Animation', 'bold-builder' ), 'description' => esc_html__( 'If fade is selected, number of slides to show will be 1', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Default', 'bold-builder' ) => 'slide',
						esc_html__( 'Fade', 'bold-builder' ) => 'fade'
					)
				),
				array( 'param_name' => 'show_arrows', 'default' => 'show', 'type' => 'dropdown', 'heading' => esc_html__( 'Navigation arrows', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Show', 'bold-builder' ) => 'show',
						esc_html__( 'Hide', 'bold-builder' ) => 'hide'
					)
				),
				array( 'param_name' => 'show_dots', 'type' => 'dropdown', 'heading' => esc_html__( 'Dots navigation', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Bottom', 'bold-builder' ) => 'bottom',
						esc_html__( 'Below', 'bold-builder' ) => 'below',
						esc_html__( 'Hide', 'bold-builder' ) => 'hide'
					)
				),
				array( 'param_name' => 'slides_to_show', 'type' => 'textfield', 'preview' => true, 'default' => 1, 'heading' => esc_html__( 'Number of slides to show', 'bold-builder' ), 'description' => esc_html__( 'E.g. 3, but if fade animation is selected, number will  be 1 anyway', 'bold-builder' ) ),
				array( 'param_name' => 'auto_play', 'type' => 'textfield', 'heading' => esc_html__( 'Autoplay interval (ms)', 'bold-builder' ), 'description' => esc_html__( 'e.g. 2000', 'bold-builder' ),
				array( 'param_name' => 'pause_on_hover', 'default' => 'yes', 'type' => 'dropdown', 'heading' => esc_html__( 'Pause slideshow on hover', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Yes', 'bold-builder' ) => 'yes',
						esc_html__( 'No', 'bold-builder' ) => 'no'
					)
				) ),
				array( 'param_name' => 'use_lightbox', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bold-builder' ) => 'use_lightbox', esc_html__( 'No', 'bold-builder' ) => 'dont_use_lightbox' ), 'heading' => esc_html__( 'Use lightbox (opens image in full size on click)', 'bold-builder' ) )
			)
		) );
	}
}