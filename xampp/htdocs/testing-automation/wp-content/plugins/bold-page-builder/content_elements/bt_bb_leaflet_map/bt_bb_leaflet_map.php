<?php

class bt_bb_leaflet_map extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'zoom'                  => '9',
			'max_zoom'              => '15',
			'height'                => '',
			'center_map'            => '',
			'predefined_style'      => '1',
			'custom_style'          => '',
			'scroll_wheel'          => '',
			'zoom_control'          => ''
		) ), $atts, $this->shortcode ) );

		$zoom		= $zoom == '' ? '9' : $zoom;
		$max_zoom	= $max_zoom == '' ? '15' : $max_zoom;
		
		$max_zoom = $max_zoom > $zoom ? $max_zoom : $zoom;
		$scroll_wheel = ( $scroll_wheel == 'scroll_wheel' || $scroll_wheel == 'yes' ) ? 1 : 0;
		$zoom_control = ( $zoom_control == 'zoom_control' || $zoom_control == 'yes' ) ? 1 : 0;
                
		// enqueue leaflet framework js and css 
		$leaflet_framework_path = plugin_dir_url( __FILE__ ) . '/';
		require_once( 'enqueue_lib.php' );
		// wp_enqueue_script( 'bt_bb_leaflet_map_js', plugin_dir_url( __FILE__ ) . '/bt_bb_leaflet_map.js' ); 
                
		$class_master = 'bt_bb_map';
		
		$class = array( $this->shortcode, $class_master );
		
        if ( $el_class != '' ) {
			$class[] = $el_class;
		}

		if ( $center_map == 'yes_no_overlay' ) {
			$class[] = $this->shortcode . '_no_overlay';
			$class[] = $class_master . '_no_overlay';
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr($el_id) . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr($el_style) . '"';
		}
                
		$style_height = '';
		if ( $height != '' ) {
			$style_height = ' ' . 'style="height:' . $height . '"';
		}
	
		$map_id = uniqid( 'map_canvas' );               
        $content_html = wptexturize( do_shortcode( $content ) );               
		$locations = substr_count( $content_html, '"bt_bb_leaflet_map_location' );
		$locations_without_content = substr_count( $content_html, 'bt_bb_leaflet_map_location_without_content' );
              
		if ( $content != '' && $locations != $locations_without_content ) {
			$content = '<span class="' . esc_attr( $this->shortcode ) . '_content_toggler ' . esc_attr( $class_master ) . '_content_toggler"></span>'; 
			$content .= '<div class="' . esc_attr( $this->shortcode ) . '_content ' . esc_attr($class_master) . '_content">';
				$content .= '<div class="' . esc_attr( $this->shortcode ) . '_content_wrapper ' . esc_attr( $class_master ) . '_content_wrapper">' ;
				$content .= $content_html ;
				$content .= '</div>';
			$content .= '</div>';
			$class[] = $this->shortcode . '_with_content';
			$class[] = $class_master . '_with_content';
			$style_height = '';
		} else {
		   $content = $content_html;
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$output = '<div class="' . esc_attr($this->shortcode) . '_map ' . esc_attr( $class_master ) . '_map" id="' . esc_attr( $map_id ) . '"' . $style_height . '></div>';
		$output .= $content;
		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . ' data-center="' . esc_attr( $center_map ) . '">' . $output . '</div>';
		
		$output_script = 'var bt_bb_leaflet_' . $map_id . '_init_finished = false; ';
		$output_script .= ' document.addEventListener("readystatechange", function() { ';
			$output_script .= ' if ( !bt_bb_leaflet_' . $map_id . '_init_finished && ( document.readyState === "interactive" || document.readyState === "complete" ) ) { ';
				$output_script .= ' if ( typeof( bt_bb_leaflet_init ) !== typeof(Function) ) { return false; } ';
				$output_script .= ' bt_bb_leaflet_init( "' . $map_id . '", ' . $zoom . ', ' . $max_zoom . ', ' . $predefined_style . ', "' . $custom_style . '", ' . $scroll_wheel . ', ' . $zoom_control . ' );';
				$output_script .= ' bt_bb_leaflet_' . $map_id . '_init_finished = true; ';
			$output_script .= '};';
		$output_script .= '}, false);';
		
		wp_register_script( 'boldthemes-script-bt-bb-leaflet-maps-js-init', '' );
		wp_enqueue_script( 'boldthemes-script-bt-bb-leaflet-maps-js-init' );
		wp_add_inline_script( 'boldthemes-script-bt-bb-leaflet-maps-js-init', $output_script );		
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;
                
        }
        
       function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'OpenStreetMap', 'boldthemes_framework_textdomain' ), 'description' => esc_html__( 'OpenStreetMap with custom content', 'boldthemes_framework_textdomain' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_openmap_location' => true ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'zoom', 'type' => 'textfield', 'heading' => esc_html__( 'Zoom (e.g. 9)', 'boldthemes_framework_textdomain' ), 'default' => '9', 'preview' => true ),
				array( 'param_name' => 'max_zoom', 'type' => 'textfield', 'heading' => esc_html__( 'Max Zoom (e.g. 15)', 'boldthemes_framework_textdomain' ), 'default' => '15', 'preview' => true ),
				array( 'param_name' => 'height', 'type' => 'textfield', 'heading' => esc_html__( 'Height (e.g. 250px)', 'boldthemes_framework_textdomain' ), 'description' => esc_html__( 'Used when there is no content', 'boldthemes_framework_textdomain' ) ),
				array( 'param_name' => 'predefined_style', 'type' => 'dropdown', 'default' => '1', 'heading' => esc_html__( 'Predefined (base) map layer', 'boldthemes_framework_textdomain' ), 'preview' => true, 
					'value' => array(
						__( 'No base layer (use only additional map layers)', 'boldthemes_framework_textdomain' ) => '0',
						__( 'Mapnik OSM', 'boldthemes_framework_textdomain' ) => '1',
						//__( 'Wikimedia', 'boldthemes_framework_textdomain' ) => '2',
						__( 'OSM Hot', 'boldthemes_framework_textdomain' ) => '3',
						__( 'Stamen Watercolor', 'boldthemes_framework_textdomain' ) => '4',
						__( 'Stamen Terrain', 'boldthemes_framework_textdomain' ) => '5',
						__( 'Stamen Toner', 'boldthemes_framework_textdomain' ) => '6',
						__( 'Carto Dark', 'boldthemes_framework_textdomain' ) => '7',
						__( 'Carto Light', 'boldthemes_framework_textdomain' ) => '8'
					)
				),
				array( 'param_name' => 'custom_style', 'type' => 'textarea_object', 'heading' => esc_html__( 'Additional map layers', 'boldthemes_framework_textdomain' ), 'description' => esc_html__( 'Map tiles url. Ex. https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png, Attribution text separated by new line', 'boldthemes_framework_textdomain' ) ),
				array( 'param_name' => 'center_map', 'type' => 'dropdown', 'heading' => esc_html__( 'Center map', 'boldthemes_framework_textdomain' ),
					'value' => array(
						__( 'No (use first location as center)', 'boldthemes_framework_textdomain' ) => 'no',
						__( 'Yes', 'boldthemes_framework_textdomain' ) => 'yes',
						__( 'Yes (without overlay initially)', 'boldthemes_framework_textdomain' ) => 'yes_no_overlay'
					)
				),
				array( 'param_name' => 'scroll_wheel',  'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'boldthemes_framework_textdomain' ) => 'yes' ), 'default' => 'yes', 'heading' => esc_html__( 'Enable Scroll Wheel Zoom on Map', 'boldthemes_framework_textdomain' ), 'preview' => true
				),
				array( 'param_name' => 'zoom_control',  'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'boldthemes_framework_textdomain' ) => 'yes' ), 'default' => 'yes', 'heading' => esc_html__( 'Enable Zoom Control on Map', 'boldthemes_framework_textdomain' ), 'preview' => true
				),
				
			)
		) );
	}
}

