<?php

class bt_bb_headline extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'headline'      		=> '',
			'html_tag'      		=> '',
			'font'          		=> '',
			'font_subset'   		=> '',
			'size'     				=> '',
			'font_size'     		=> '',
			'font_weight'   		=> '',
			'color_scheme'  		=> '',
			'color'         		=> '',
			'supertitle_position'   => '',
			'dash'          		=> '',
			'align'         		=> '',
			'url'           		=> '',
			'target'        		=> '',
			'superheadline' 		=> '',
			'subheadline'   		=> ''
		) ), $atts, $this->shortcode ) );

		$superheadline = html_entity_decode( $superheadline, ENT_QUOTES, 'UTF-8' );
		$subheadline = html_entity_decode( $subheadline, ENT_QUOTES, 'UTF-8' );
		$headline = html_entity_decode( $headline, ENT_QUOTES, 'UTF-8' );

		if ( $font != '' && $font != 'inherit' ) {
			require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
			bt_bb_enqueue_google_font( $font, $font_subset );
		}

		$class = array( $this->shortcode );
		$data_override_class = array();
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}
		
		$html_tag_style = "";
		$html_tag_style_arr = array();
		if ( $font != '' && $font != 'inherit' ) {
			$el_style = $el_style . ';' . 'font-family:\'' . urldecode( $font ) . '\'';
			$html_tag_style_arr[] = 'font-family:\'' . urldecode( $font ) . '\'';
		}
		if ( $font_size != '' ) {
			$html_tag_style_arr[] = 'font-size:' . $font_size  ;
		}
		if ( count( $html_tag_style_arr ) > 0 ) {
			$html_tag_style = ' style="' . implode( '; ', $html_tag_style_arr ) . '"';
		}
		
		if ( $font_weight != '' ) {
			$class[] = $this->prefix . 'font_weight_' . $font_weight ;
		}
		
		if ( $color_scheme != '' ) {
			$class[] = $this->prefix . 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}

		if ( $color != '' ) {
			$el_style = $el_style . ';' . 'color:' . $color . ';border-color:' . $color . ';';
		}

		if ( $dash != '' ) {
			$class[] = $this->prefix . 'dash' . '_' . $dash;
		}
		
		$this->responsive_data_override_class(
			$class, $data_override_class,
			array(
				'prefix' => $this->prefix,
				'param' => 'size',
				'value' => $size
			)
		);
		
		if ( $target == '' ) {
			$target = '_self';
		}

		$superheadline_inside = '';
		$superheadline_outside = '';
		
		if ( $superheadline != '' ) {
			$class[] = $this->prefix . 'superheadline';
			if ( $supertitle_position == 'outside' ) { 
				$superheadline_outside = '<span class="' . esc_attr( $this->shortcode ) . '_superheadline">' . $superheadline . '</span>';
				$class[] = $this->prefix . 'superheadline_outside';
			} else {
				$superheadline_inside = '<span class="' . esc_attr( $this->shortcode ) . '_superheadline">' . $superheadline . '</span>';
			}
		}
		
		if ( $subheadline != '' ) {
			$class[] = $this->prefix . 'subheadline';
			$subheadline = '<div class="' . esc_attr( $this->shortcode ) . '_subheadline">' . $subheadline . '</div>';
			$subheadline = nl2br( $subheadline );
		}
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}

		$this->responsive_data_override_class(
			$class, $data_override_class,
			array(
				'prefix' => $this->prefix,
				'param' => 'align',
				'value' => $align
			)
		);
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );
		
		if ( $headline != '' ) {
			if ( $url != '' ) {
				$url_title = strip_tags( str_replace( array("\n", "\r"), ' ', $headline ) );
				$link = bt_bb_get_url( $url );
				// IMPORTANT: esc_attr must be used instead of esc_url(_raw)
				$headline = '<a href="' . esc_attr( $link ) . '" target="' . esc_attr( $target ) . '" title="' . esc_attr( $url_title )  . '">' . $headline . '</a>';
			}		
			$headline = '<span class="' . esc_attr( $this->shortcode ) . '_content"><span>' . $headline . '</span></span>';			
		}
		
		$headline = nl2br( $headline );

		$output = '<header' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . ' data-bt-override-class="' . htmlspecialchars( json_encode( $data_override_class, JSON_FORCE_OBJECT ), ENT_QUOTES, 'UTF-8' ) . '">';
		if ( $superheadline_outside != '' ) $output .= '<div class="' . $this->shortcode . '_superheadline_outside' . '">' . $superheadline_outside . '</div>';
		if ( $headline != '' || $superheadline_inside != '' ) $output .= '<' . $html_tag . $html_tag_style . ' class="bt_bb_headline_tag">' . $superheadline_inside . $headline . '</' . $html_tag . '>';
		$output .= $subheadline . '</header>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {

		require( dirname(__FILE__) . '/../../content_elements_misc/fonts1.php' );
		
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Headline', 'bold-builder' ), 'description' => esc_html__( 'Headline with custom Google fonts', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode, 'highlight' => true,
			'params' => array(
				array( 'param_name' => 'superheadline', 'type' => 'textfield', 'heading' => esc_html__( 'Superheadline', 'bold-builder' ) ),
				array( 'param_name' => 'headline', 'type' => 'textarea', 'heading' => esc_html__( 'Headline', 'bold-builder' ), 'preview' => true, 'preview_strong' => true ),
				array( 'param_name' => 'subheadline', 'type' => 'textarea', 'heading' => esc_html__( 'Subheadline', 'bold-builder' ) ),
				array( 'param_name' => 'html_tag', 'type' => 'dropdown', 'heading' => esc_html__( 'HTML tag', 'bold-builder' ), 'preview' => true,
					'value' => array(
						esc_html__( 'h1', 'bold-builder' ) 	=> 'h1',
						esc_html__( 'h2', 'bold-builder' ) 	=> 'h2',
						esc_html__( 'h3', 'bold-builder' ) 	=> 'h3',
						esc_html__( 'h4', 'bold-builder' ) 	=> 'h4',
						esc_html__( 'h5', 'bold-builder' ) 	=> 'h5',
						esc_html__( 'h6', 'bold-builder' ) 	=> 'h6'
				) ),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => esc_html__( 'Size', 'bold-builder' ), 'description' => esc_html__( 'Predefined heading sizes, independent of html tag', 'bold-builder' ), 'responsive_override' => true,
					'value' => array(
						esc_html__( 'Inherit', 'bold-builder' ) 	=> 'inherit',
						esc_html__( 'Extra Small', 'bold-builder' ) => 'extrasmall',
						esc_html__( 'Small', 'bold-builder' ) 		=> 'small',
						esc_html__( 'Medium', 'bold-builder' ) 		=> 'medium',
						esc_html__( 'Normal', 'bold-builder' ) 		=> 'normal',
						esc_html__( 'Large', 'bold-builder' ) 		=> 'large',
						esc_html__( 'Extra large', 'bold-builder' ) => 'extralarge',
						esc_html__( 'Huge', 'bold-builder' ) 		=> 'huge'
					)
				),				
				array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => esc_html__( 'Alignment', 'bold-builder' ), 'responsive_override' => true,
					'value' => array(
						esc_html__( 'Inherit', 'bold-builder' ) 	=> 'inherit',
						esc_html__( 'Left', 'bold-builder' ) 		=> 'left',
						esc_html__( 'Center', 'bold-builder' ) 		=> 'center',
						esc_html__( 'Right', 'bold-builder' )		=> 'right'
					)
				),
				array( 'param_name' => 'dash', 'type' => 'dropdown', 'heading' => esc_html__( 'Dash', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ),
					'value' => array(
						esc_html__( 'None', 'bold-builder' ) 			=> 'none',
						esc_html__( 'Top', 'bold-builder' ) 			=> 'top',
						esc_html__( 'Bottom', 'bold-builder' ) 			=> 'bottom',
						esc_html__( 'Top and bottom', 'bold-builder' ) 	=> 'top_bottom'
					)
				),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'bold-builder' ), 'description' => esc_html__( 'Define color schemes in Bold Builder settings or define accent and alternate colors in theme customizer (if avaliable)', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true ),
				array( 'param_name' => 'color', 'type' => 'colorpicker', 'heading' => esc_html__( 'Color', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'supertitle_position', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bold-builder' ) => 'outside' ), 'default' => 'outside', 'heading' => esc_html__( 'Put supertitle outside H tag', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'font', 'type' => 'dropdown', 'heading' => esc_html__( 'Font', 'bold-builder' ), 'group' => esc_html__( 'Font', 'bold-builder' ), 'preview' => true,
					'value' => array( esc_html__( 'Inherit', 'bold-builder' ) => 'inherit' ) + BT_BB_Root::$font_arr
				),
				array( 'param_name' => 'font_subset', 'type' => 'textfield', 'heading' => esc_html__( 'Google Font subset', 'bold-builder' ), 'group' => esc_html__( 'Font', 'bold-builder' ), 'value' => 'latin,latin-ext', 'description' => esc_html__( 'E.g. latin,latin-ext,cyrillic,cyrillic-ext', 'bold-builder' ) ),
				array( 'param_name' => 'font_size', 'type' => 'textfield', 'heading' => esc_html__( 'Custom font size', 'bold-builder' ), 'group' => esc_html__( 'Font', 'bold-builder' ), 'description' => esc_html__( 'E.g. 20px or 1.5rem', 'bold-builder' ) ),
				array( 'param_name' => 'font_weight', 'type' => 'dropdown', 'heading' => esc_html__( 'Font weight', 'bold-builder' ), 'group' => esc_html__( 'Font', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Default', 'bold-builder' ) 	=> '',
						esc_html__( 'Normal', 'bold-builder' ) 		=> 'normal',
						esc_html__( 'Bold', 'bold-builder' ) 		=> 'bold',
						esc_html__( 'Bolder', 'bold-builder' ) 		=> 'bolder',
						esc_html__( 'Lighter', 'bold-builder' ) 	=> 'lighter',
						esc_html__( 'Light', 'bold-builder' ) 		=> 'light',
						esc_html__( 'Thin', 'bold-builder' ) 		=> 'thin'
					)
				),
				array( 'param_name' => 'url', 'type' => 'link', 'heading' => esc_html__( 'URL', 'bold-builder' ), 'description' => esc_html__( 'Enter full or local URL (e.g. https://www.bold-themes.com or /pages/about-us) or post slug (e.g. about-us) or search for existing content.', 'bold-builder' ), 'group' => esc_html__( 'URL', 'bold-builder' ) ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'heading' => esc_html__( 'Target', 'bold-builder' ), 'group' => esc_html__( 'URL', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Self (open in same tab)', 'bold-builder' ) => '_self',
						esc_html__( 'Blank (open in new tab)', 'bold-builder' ) => '_blank'
					)
				)
			)
		) );
	}
}