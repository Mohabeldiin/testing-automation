<?php

class bt_bb_masonry_image_grid extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'images'      				=> '',
			'columns'     				=> '',
			'format'      				=> '',
			'gap'         				=> '',
			'img_base_size'     		=> 'large',
			'lightbox_img_base_size'    => 'full',
			'no_lightbox' 				=> ''
		) ), $atts, $this->shortcode ) );

		wp_enqueue_script( 'jquery-masonry' );

		wp_enqueue_script( 
			'bt_bb_image_grid',
			plugin_dir_url( __FILE__ ) . 'bt_bb_masonry_image_grid.js',
			array( 'jquery' ),
			BT_BB_VERSION
		);

		$class = array( $this->shortcode, 'bt_bb_grid_container' );

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

		if ( $columns != '' ) {
			$class[] = $this->prefix . 'columns' . '_' . $columns;
		}
		
		if ( $gap != '' ) {
			$class[] = $this->prefix . 'gap' . '_' . $gap;
		}

		if ( $no_lightbox == 'no_lightbox' ) {
			$class[] = $this->prefix . 'no_lightbox';
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$output = '';
		
		$output .= '<div class="bt_bb_grid_sizer"></div>';

		$images_arr = explode( ',', $images );
		$format_arr = explode( ',', $format );
		
		$n = 0;

		foreach( $images_arr as $id ) {
			$img = wp_get_attachment_image_src( $id, $img_base_size );
			$img_src = isset( $img[0] ) ? $img[0] : '';
			$img_full = wp_get_attachment_image_src( $id, $lightbox_img_base_size );
			$img_src_full = isset( $img_full[0] ) ? $img_full[0] : '';			
			$image_post = get_post( $id );
			if ( isset( $format_arr[ $n ] ) ) {
				$tile_format = 'bt_bb_tile_format';
				if ( $format_arr[ $n ] == '21' ) {
					$tile_format .= '_' . esc_attr( $format_arr[ $n ] );
				} else {
					$tile_format .= '11';
				}
			}
			$data_hw = '';
			if ( isset($img[1]) ) {
				if ( $img[1] > 0 ) {
					$data_hw = $img[2] / $img[1];
				}
			}
			$data_title = '';
			if ( is_object( $image_post ) ) {
				$data_title = $image_post->post_title;
			}
			$output .= '<div class="bt_bb_grid_item ' . $tile_format . '" data-hw="' . esc_attr( $data_hw ) . '" data-src="' . esc_url_raw( $img_src ) . '" data-src-full="' . esc_url_raw( $img_src_full ) . '" data-title="' . esc_attr( $data_title ) . '"><div class="bt_bb_grid_item_inner" data-hw="' . esc_attr( $data_hw ) . '" ><div class="bt_bb_grid_item_inner_image"></div><div class="bt_bb_grid_item_inner_content"></div></div></div>';
			$n++;
		}

		$output = '<div' . $id_attr . ' class="' . esc_attr( implode( ' ', $class ) ) . '"' . $style_attr . ' data-columns="' . esc_attr( $columns ) . '"><div class="bt_bb_masonry_post_image_content" data-columns="' . esc_attr( $columns ) . '">' . $output . '</div></div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Masonry Image Grid', 'bold-builder' ), 'description' => esc_html__( 'Masonry grid with images', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'images', 'type' => 'attach_images', 'heading' => esc_html__( 'Images', 'bold-builder' ) ),
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
				array( 'param_name' => 'gap', 'type' => 'dropdown', 'heading' => esc_html__( 'Gap', 'bold-builder' ),
					'value' => array(
						esc_html__( 'No gap', 'bold-builder' ) => 'no_gap',
						esc_html__( 'Extra small', 'bold-builder' ) => 'extrasmall',
						esc_html__( 'Small', 'bold-builder' ) => 'small',
						esc_html__( 'Normal', 'bold-builder' ) => 'normal',
						esc_html__( 'Large', 'bold-builder' ) => 'large'
					)
				),
				array( 'param_name' => 'img_base_size', 'type' => 'dropdown', 'default' => 'large', 'heading' => esc_html__( 'Base image size', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Large', 'bold-builder' ) => 'large',
						esc_html__( 'Medium', 'bold-builder' ) => 'medium',
						esc_html__( 'Thumbnail', 'bold-builder' ) => 'thumbnail'
					)
				),
				array( 'param_name' => 'lightbox_img_base_size', 'type' => 'dropdown', 'default' => 'full', 'heading' => esc_html__( 'Popup image size', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Full', 'bold-builder' ) => 'full',
						esc_html__( 'Large', 'bold-builder' ) => 'large',
						esc_html__( 'Medium', 'bold-builder' ) => 'medium',
						esc_html__( 'Thumbnail', 'bold-builder' ) => 'thumbnail'
					)
				),
				array( 'param_name' => 'format', 'type' => 'textfield', 'preview' => true, 'heading' => esc_html__( 'Tiles format', 'bold-builder' ), 'description' => esc_html__( 'e.g. 21, 11, 11', 'bold-builder' ) ),
				array( 'param_name' => 'no_lightbox', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bold-builder' ) => 'no_lightbox', esc_html__( 'No', 'bold-builder' ) => 'hide_share' ), 'heading' => esc_html__( 'Disable lightbox', 'bold-builder' ) )
			)
		) );
	} 
}