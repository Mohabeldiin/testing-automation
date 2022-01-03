<?php

class bt_bb_section extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {

		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'layout'                	=> '',
			'full_screen'           	=> '',
			'vertical_align'        	=> '',
			'top_spacing'           	=> '',
			'bottom_spacing'        	=> '',
			'color_scheme'          	=> '',
			'background_color'      	=> '',
			'background_image'      	=> '',
			'lazy_load'  				=> 'no',
			'background_overlay'    	=> '',
			'background_video_yt'   	=> '',
			'yt_video_settings'     	=> '',
			'background_video_mp4'  	=> '',
			'background_video_ogg'  	=> '',
			'background_video_webm' 	=> '',
			'show_video_on_mobile' 		=> '',
			'parallax'              	=> '',
			'parallax_offset'       	=> ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );

		wp_enqueue_script(
			'bt_bb_elements',
			plugin_dir_url( __FILE__ ) . 'bt_bb_elements.js',
			array( 'jquery' ),
			BT_BB_VERSION,
			true
		);
		
		$show_video_on_mobile = ( $show_video_on_mobile == 'show_video_on_mobile' || $show_video_on_mobile == 'yes' ) ? 1 : 0;

		if ( $top_spacing != '' ) {
			$class[] = $this->prefix . 'top_spacing' . '_' . $top_spacing;
		}

		if ( $bottom_spacing != '' ) {
			$class[] = $this->prefix . 'bottom_spacing' . '_' . $bottom_spacing;
		}

		if ( $color_scheme != '' ) {
			$class[] = $this->prefix . 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}
		
		if ( $background_color != '' ) {
			$el_style = $el_style . ';' . 'background-color:' . $background_color . ';';
		}

		if ( $layout != '' ) {
			$class[] = $this->prefix . 'layout' . '_' . $layout;
		}

		if ( $full_screen == 'yes' ) {
			$class[] = $this->prefix . 'full_screen';
		}

		if ( $vertical_align != '' ) {
			$class[] = $this->prefix . 'vertical_align' . '_' . $vertical_align;
		}
		$background_data_attr = '';

		if ( $background_image != '' ) {
			$background_image = wp_get_attachment_image_src( $background_image, 'full' );
		}
		
		$background_image_url = '';
		$data_parallax_attr = '';
			
		if ( $background_image ) {
			$background_image_url = isset($background_image[0]) ? $background_image[0] : '';
		}
		if ( $background_image_url != '' ) {
			if ( $lazy_load == 'yes' ) {
				$blank_image_src = BT_BB_Root::$path . 'img/blank.gif';
				$background_image_style = 'background-image:url(\'' . $blank_image_src . '\');';
				$background_data_attr .= ' data-background_image_src="\'' . $background_image_url . '\'"';
				$class[] = 'btLazyLoadBackground';
			} else {
				$background_image_style = 'background-image:url(\'' . $background_image_url . '\');';
				
			}
			$el_style = $background_image_style . $el_style;	
			$class[] = $this->prefix . 'background_image';

			if ( $parallax != '' ) {
				$data_parallax_attr = 'data-parallax="' . esc_attr( $parallax ) . '" data-parallax-offset="' . esc_attr( intval( $parallax_offset ) ) . '"';
				$class[] = $this->prefix . 'parallax';
			}
		}

		if ( $background_overlay != '' ) {
			$class[] = $this->prefix . 'background_overlay' . '_' . $background_overlay;
		}

		$id_attr = '';
		if ( $el_id == '' ) {
			$el_id = uniqid( 'bt_bb_section' );
		}
		$id_attr = 'id="' . esc_attr( $el_id ) . '"';

		$background_video_attr = '';

		$video_html = '';

		if ( $background_video_yt != '' ) {
			wp_enqueue_style( 'bt_bb_style_yt', plugin_dir_url( __FILE__ ) . 'jquery.mb.YTPlayer.min.css', array(), BT_BB_VERSION );
			wp_enqueue_script( 
				'bt_bb_yt',
				plugin_dir_url( __FILE__ ) . 'jquery.mb.YTPlayer.min.js',
				array( 'jquery' ),
				BT_BB_VERSION,
				true
			);

			$class[] = $this->prefix . 'background_video_yt';

			if ( $yt_video_settings == '' ) {
				$yt_video_settings = 'showControls:false,showYTLogo:false,startAt:0,loop:true,mute:true,stopMovieOnBlur:false,opacity:1';
				// $yt_video_settings = '';
			}
			
			$yt_video_settings .= $show_video_on_mobile ? ',useOnMobile:true' : ',useOnMobile:false';
			
			$yt_video_settings .= '';
			// $yt_video_settings .= ',cc_load_policy:false,showAnnotations:false,optimizeDisplay:true,anchor:\'center,center\'';
			$yt_video_settings .= ',useNoCookie:false';

			$background_video_attr = ' ' . 'data-property="{videoURL:\'' . $background_video_yt . '\',containment:\'#' . $el_id . '\',' . $yt_video_settings . '}"';
			
			$video_html .= '<div class="' . $this->prefix . 'background_video_yt_inner" ' . $background_video_attr . ' ></div>';
			
			$proxy = new BT_BB_YT_Video_Proxy( $this->prefix, $el_id );
			add_action( 'wp_footer', array( $proxy, 'js_init' ) );

		} else if ( ( $background_video_mp4 != '' || $background_video_ogg != '' || $background_video_webm != '' ) && ! ( wp_is_mobile() && ! $show_video_on_mobile ) ) {
			$class[] = $this->prefix . 'video';
			$video_html = '<video autoplay loop muted playsinline onplay="bt_bb_video_callback( this )">';
			if ( $background_video_mp4 != '' ) {
				$video_html .= '<source src="' . esc_url_raw( $background_video_mp4 ) . '" type="video/mp4">';
			}
			if ( $background_video_ogg != '' ) {
				$video_html .= '<source src="' . esc_url_raw( $background_video_ogg ) . '" type="video/ogg">';
			}
			if ( $background_video_webm != '' ) {
				$video_html .= '<source src="' . esc_url_raw( $background_video_webm ) . '" type="video/webm">';
			}
			$video_html .= '</video>';
		}

		$class = apply_filters( $this->shortcode . '_class', $class, $atts );
		$class_attr = implode( ' ', $class );

		if ( $el_class != '' ) {
			$class_attr = $class_attr . ' ' . $el_class;
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . esc_attr( $el_style ) . '"';
		}

		$output = '<section ' . $id_attr . ' ' . $data_parallax_attr . $background_data_attr . ' class="' . esc_attr( $class_attr ) . '" ' . $style_attr . '>';
		$output .= $video_html;
			$output .= '<div class="' . esc_attr( $this->prefix ) . 'port">';
				$output .= '<div class="' . esc_attr( $this->prefix ) . 'cell">';
					$output .= '<div class="' . esc_attr( $this->prefix ) . 'cell_inner">';
					$output .= do_shortcode( $content );
					$output .= '</div>';
				$output .= '</div>';
		$output .= '</div>';

		$output .= '</section>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {
		
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Section', 'bold-builder' ), 'description' => esc_html__( 'Basic root element', 'bold-builder' ), 'root' => true, 'container' => 'vertical', 'accept' => array( 'bt_bb_row' => true ), 'toggle' => true, 'auto_add' => 'bt_bb_row', 'show_settings_on_create' => false,
			'params' => array( 
				array( 'param_name' => 'layout', 'type' => 'dropdown', 'default' => 'boxed_1200', 'heading' => esc_html__( 'Layout', 'bold-builder' ), 'group' => esc_html__( 'General', 'bold-builder' ), 'weight' => 0, 'preview' => true,
					'value' => array(
						esc_html__( 'Boxed (800px)', 'bold-builder' ) => 'boxed_800',
						esc_html__( 'Boxed (900px)', 'bold-builder' ) => 'boxed_900',
						esc_html__( 'Boxed (1000px)', 'bold-builder' ) => 'boxed_1000',
						esc_html__( 'Boxed (1100px)', 'bold-builder' ) => 'boxed_1100',
						esc_html__( 'Boxed (1200px)', 'bold-builder' ) => 'boxed_1200',
						esc_html__( 'Boxed (1300px)', 'bold-builder' ) => 'boxed_1300',
						esc_html__( 'Boxed (1400px)', 'bold-builder' ) => 'boxed_1400',
						esc_html__( 'Boxed (1500px)', 'bold-builder' ) => 'boxed_1500',
						esc_html__( 'Boxed (1600px)', 'bold-builder' ) => 'boxed_1600',
						esc_html__( 'Wide', 'bold-builder' ) => 'wide'
					)
				),
				array( 'param_name' => 'top_spacing', 'type' => 'dropdown', 'heading' => esc_html__( 'Top spacing', 'bold-builder' ), 'preview' => true,
					'value' => array(
						esc_html__( 'No spacing', 'bold-builder' ) => '',
						esc_html__( 'Extra small', 'bold-builder' ) => 'extra_small',
						esc_html__( 'Small', 'bold-builder' ) => 'small',		
						esc_html__( 'Normal', 'bold-builder' ) => 'normal',
						esc_html__( 'Medium', 'bold-builder' ) => 'medium',
						esc_html__( 'Large', 'bold-builder' ) => 'large',
						esc_html__( 'Extra large', 'bold-builder' ) => 'extra_large'
					)
				),
				array( 'param_name' => 'bottom_spacing', 'type' => 'dropdown', 'heading' => esc_html__( 'Bottom spacing', 'bold-builder' ), 'preview' => true,
					'value' => array(
						esc_html__( 'No spacing', 'bold-builder' ) 	=> '',
						esc_html__( 'Extra small', 'bold-builder' ) => 'extra_small',
						esc_html__( 'Small', 'bold-builder' ) 		=> 'small',		
						esc_html__( 'Normal', 'bold-builder' ) 		=> 'normal',
						esc_html__( 'Medium', 'bold-builder' ) 		=> 'medium',
						esc_html__( 'Large', 'bold-builder' ) 		=> 'large',
						esc_html__( 'Extra large', 'bold-builder' ) => 'extra_large'
					)
				),
				array( 'param_name' => 'full_screen', 'type' => 'dropdown', 'heading' => esc_html__( 'Full screen', 'bold-builder' ), 
					'value' => array(
						esc_html__( 'No', 'bold-builder' ) 		=> '',
						esc_html__( 'Yes', 'bold-builder' ) 	=> 'yes'
					)
				),
				array( 'param_name' => 'vertical_align', 'type' => 'dropdown', 'heading' => esc_html__( 'Vertical align (for fullscreen section)', 'bold-builder' ), 'preview' => true,
					'value' => array(
						esc_html__( 'Top', 'bold-builder' )     => 'top',
						esc_html__( 'Middle', 'bold-builder' )  => 'middle',
						esc_html__( 'Bottom', 'bold-builder' )  => 'bottom'					
					)
				),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'bold-builder' ), 'description' => esc_html__( 'Define color schemes in Bold Builder settings or define accent and alternate colors in theme customizer (if avaliable)', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true, 'group' => esc_html__( 'Design', 'bold-builder' )  ),
				array( 'param_name' => 'background_color', 'type' => 'colorpicker', 'heading' => esc_html__( 'Background color', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'background_image', 'type' => 'attach_image',  'preview' => true, 'heading' => esc_html__( 'Background image', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'lazy_load', 'type' => 'dropdown', 'default' => 'yes', 'heading' => esc_html__( 'Lazy load background image', 'bold-builder' ),
					'value' => array(
						esc_html__( 'No', 'bold-builder' ) => 'no',
						esc_html__( 'Yes', 'bold-builder' ) => 'yes'
					)
				),
				array( 'param_name' => 'background_overlay', 'type' => 'dropdown', 'heading' => esc_html__( 'Background overlay', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ), 
					'value' => array(
						esc_html__( 'No overlay', 'bold-builder' )    => '',
						esc_html__( 'Light stripes', 'bold-builder' ) => 'light_stripes',
						esc_html__( 'Dark stripes', 'bold-builder' )  => 'dark_stripes',
						esc_html__( 'Light solid', 'bold-builder' )	  => 'light_solid',
						esc_html__( 'Dark solid', 'bold-builder' )	  => 'dark_solid',
						esc_html__( 'Light gradient', 'bold-builder' )	  => 'light_gradient',
						esc_html__( 'Dark gradient', 'bold-builder' )	  => 'dark_gradient'
					)
				),
				array( 'param_name' => 'parallax', 'type' => 'textfield', 'heading' => esc_html__( 'Parallax (e.g. 0.7)', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'parallax_offset', 'type' => 'textfield', 'heading' => esc_html__( 'Parallax offset in px (e.g. -100)', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'background_video_yt', 'type' => 'textfield', 'heading' => esc_html__( 'YouTube background video', 'bold-builder' ), 'group' => esc_html__( 'Video', 'bold-builder' ) ),
				array( 'param_name' => 'yt_video_settings', 'type' => 'textfield', 'heading' => esc_html__( 'YouTube video settings (e.g. startAt:20, mute:true, stopMovieOnBlur:false)', 'bold-builder' ), 'group' => esc_html__( 'Video', 'bold-builder' ) ),
				array( 'param_name' => 'background_video_mp4', 'type' => 'textfield', 'heading' => esc_html__( 'MP4 background video', 'bold-builder' ), 'group' => esc_html__( 'Video', 'bold-builder' ) ),
				array( 'param_name' => 'background_video_ogg', 'type' => 'textfield', 'heading' => esc_html__( 'OGG background video', 'bold-builder' ), 'group' => esc_html__( 'Video', 'bold-builder' ) ),
				array( 'param_name' => 'background_video_webm', 'type' => 'textfield', 'heading' => esc_html__( 'WEBM background video', 'bold-builder' ), 'group' => esc_html__( 'Video', 'bold-builder' ) ),
				array( 'param_name' => 'show_video_on_mobile',  'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bold-builder' ) => 'yes' ), 'default' => '', 'heading' => esc_html__( 'Show Video on Mobile Devices', 'bold-builder' ), 'group' => esc_html__( 'Video', 'bold-builder' ) )
			)
		) );		

	} 

}

class BT_BB_YT_Video_Proxy {
	function __construct( $prefix, $el_id ) {
		$this->prefix = $prefix;
		$this->el_id = $el_id;
	}
	public function js_init() {
		// wp_register_script( 'boldthemes-script-bt-bb-section-js-init', '' );
		// wp_enqueue_script( 'boldthemes-script-bt-bb-section-js-init' );
		// wp_add_inline_script( 'boldthemes-script-bt-bb-section-js-init', 'jQuery(function() { jQuery( "#' . esc_html( $this->el_id ) . ' .' . esc_html( $this->prefix ) . 'background_video_yt_inner" ).YTPlayer();});' );
		wp_add_inline_script( 'bt_bb_yt', 'jQuery(function() { jQuery( "#' . esc_html( $this->el_id ) . ' .' . esc_html( $this->prefix ) . 'background_video_yt_inner" ).YTPlayer();});' );
    }

}