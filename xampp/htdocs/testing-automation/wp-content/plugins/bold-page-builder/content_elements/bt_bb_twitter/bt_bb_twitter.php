<?php

class bt_bb_twitter extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'number'              	=> '',
			'display_type'        	=> '',
			'slides_to_show' 		=> '',
			'gap' 					=> '',
			'auto_play' 			=> '',
			'show_avatar'         	=> '',
			'cache'               	=> '',
			'cache_id'            	=> '',
			'username'            	=> '',
			'consumer_key'        	=> '',
			'consumer_secret'     	=> '',
			'access_token'        	=> '',
			'access_token_secret' 	=> ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode, 'bt_bb_gap_large' );
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}

		$data_slick = '';
		if ( $display_type == 'slider' ) {
			$data_slick = ' ' . 'data-slick=\'{ "lazyLoad": "progressive", "cssEase": "ease-out", "speed": "300", "prevArrow": "&lt;button type=\"button\" class=\"slick-prev\"&gt;", "nextArrow": "&lt;button type=\"button\" class=\"slick-next\"&gt;" ';
			if ( $auto_play != '' ) {
				$data_slick .= ', "autoplay": true, "autoplaySpeed": ' . intval( $auto_play );
			}
			if ( $gap != '' ) {
				$data_slick .= ', "gap": "' . $gap . '"';
			}
			if ( is_rtl() ) {
				$data_slick .= ', "rtl": true' ;
			}
			if ( $slides_to_show != '' ) {
				$data_slick .= ', "slidesToShow": ' . intval( $slides_to_show );
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
			$class[] = $this->prefix . 'display_slider';
		} else {
			$class[] = $this->prefix . 'display_regular';
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}
		
		if ( $slides_to_show != '' ) {
			if ( $display_type == 'slider' ) {
				$class[] = $this->prefix . 'multiple_slides';	
			} else {
				$class[] = $this->prefix . 'columns_' . $slides_to_show;
			}
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );
		
		$twitter_data = bt_bb_get_twitter_data( $number, $cache, $cache_id, $username, $consumer_key, $consumer_secret, $access_token, $access_token_secret );
		
		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . $data_slick . '>';
			if ( $display_type == 'slider' ) {
				$output .= '<div class="slick-slider"' . $data_slick . '>';
			} else {
				$output .= '<div class="bt_bb_twitter_inner">';
			}
				if ( is_array( $twitter_data ) ) {
					foreach ( $twitter_data as $data ) {
						$user_avatar =  str_replace( '_normal', '_bigger', $data->user->profile_image_url_https );
						$user =  $data->user->screen_name;
						$profile_link = 'https://twitter.com/' . $user ;
						$link = 'https://twitter.com/' . $user . '/status/' . $data->id_str;
						$text = mb_convert_encoding( utf8_encode( $data->text ), 'HTML-ENTITIES', 'UTF-8' );
						$time = human_time_diff( strtotime( $data->created_at ) );

						$output .= '<div class="bt_bb_twitter_item">';
							$output .= '<div class="content">';
								if( $show_avatar == 'yes' ) {
									$output .= '<a href="' . esc_url( $profile_link ) . '" target="_blank" title="@' . esc_attr( $user ) . '"><img src="' . esc_url_raw( $user_avatar ) . '" class="bt_bb_twitter_avatar" alt="@' . esc_attr( $user ) . '" title="@' . esc_attr( $user ) . '"/></a>';
								}
								$output .= '<small class="bt_bb_twitter_username"><a href="' . esc_url( $link ) . '" target="_blank" title="@' . esc_attr( $user ) . '">@' . $user . ' - ' . $time . '</a></small>';
								$output .= '<p class="bt_bb_twitter_content">' . BB_Twitter_Widget::parse( $data->text ) . '</p>';
							$output .= '</div>';
						$output .= '</div>';
					}
				}
			$output .= '</div>';
		$output .= '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Twitter (deprecated)', 'bold-builder' ), 'description' => esc_html__( 'Twitter posts', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'number', 'type' => 'textfield', 'heading' => esc_html__( 'Number of Tweets', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'display_type', 'type' => 'dropdown', 'heading' => esc_html__( 'Type', 'bold-builder' ), 'preview' => true,
					'value' => array(
						esc_html__( 'Slider', 'bold-builder' ) => 'slider',
						esc_html__( 'Regular grid', 'bold-builder' ) => 'regular'
					) ),
				array( 'param_name' => 'show_avatar', 'type' => 'dropdown', 'heading' => esc_html__( 'Show avatar', 'bold-builder' ),
				'value' => array(
					esc_html__( 'No', 'bold-builder' )  => 'no',
					esc_html__( 'Yes', 'bold-builder' ) => 'yes'
				) ),
				array( 'param_name' => 'slides_to_show', 'type' => 'textfield', 'preview' => true, 'default' => 1, 'heading' => esc_html__( 'Number of slides to show', 'bold-builder' ), 'description' => esc_html__( '(1-6) e.g. 3', 'bold-builder' ) ),
				array( 'param_name' => 'gap', 'type' => 'dropdown', 'default' => 'small', 'heading' => esc_html__( 'Gap', 'bold-builder' ),
					'value' => array(
						esc_html__( 'No gap', 'bold-builder' ) => 'no_gap',
						esc_html__( 'Small', 'bold-builder' ) => 'small',
						esc_html__( 'Normal', 'bold-builder' ) => 'normal',
						esc_html__( 'Large', 'bold-builder' ) => 'large'
					)
				),
				array( 'param_name' => 'auto_play', 'type' => 'textfield', 'heading' => esc_html__( 'Autoplay interval (ms)', 'bold-builder' ), 'description' => esc_html__( 'e.g. 2000', 'bold-builder' ) ),
				array( 'param_name' => 'username', 'type' => 'textfield', 'heading' => esc_html__( 'Username (or #hashtag)', 'bold-builder' ) ),
				array( 'param_name' => 'cache', 'type' => 'textfield', 'heading' => esc_html__( 'Cache (minutes)', 'bold-builder' ) ),
				array( 'param_name' => 'cache_id', 'type' => 'hidden', 'value' => uniqid() ),
				array( 'param_name' => 'consumer_key', 'type' => 'textfield', 'heading' => esc_html__( 'Consumer Key', 'bold-builder' ), 'group' => esc_html__( 'API', 'bold-builder' ) ),
				array( 'param_name' => 'consumer_secret', 'type' => 'textfield', 'heading' => esc_html__( 'Consumer Secret', 'bold-builder' ), 'group' => esc_html__( 'API', 'bold-builder' ) ),
				array( 'param_name' => 'access_token', 'type' => 'textfield', 'heading' => esc_html__( 'Access Token', 'bold-builder' ), 'group' => esc_html__( 'API', 'bold-builder' ) ),
				array( 'param_name' => 'access_token_secret', 'type' => 'textfield', 'heading' => esc_html__( 'Access Token Secret', 'bold-builder' ), 'group' => esc_html__( 'API', 'bold-builder' ) )				
			) )
		);
	}
}