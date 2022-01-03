<?php
/**
 * Thim_Builder Elementor Icon Box widget
 *
 * @version     1.0.0
 * @author      ThimPress
 * @package     Thim_Builder/Classes
 * @category    Classes
 * @author      Thimpress, tuanta
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Thim_Builder_El_Icon_Box' ) ) {
	/**
	 * Class Thim_Builder_El_Icon_Box
	 */
	class Thim_Builder_El_Icon_Box extends Thim_Builder_El_Widget {

		/**
		 * @var string
		 */
		protected $config_class = 'Thim_Builder_Config_Icon_Box';

		/**
		 * Register controls.
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'el-heading', [ 'label' => esc_html__( 'Thim: Heading', 'eduma' ) ]
			);

			$controls = \Thim_Builder_El_Mapping::mapping( $this->options() );

			foreach ( $controls as $key => $control ) {
				if ( isset( $control['start_section'] ) ) {
					$this->end_controls_section();
					$this->start_controls_section(
						$control['start_section'], [ 'label' => $control['section_name'] ]
					);
				}
				$this->add_control( $key, $control );
			}

			$this->end_controls_section();
		}

		// convert setting
		function thim_convert_setting( $settings ) {
			$settings['title_group'] = array(
				'title'            => $settings['title'],
				'color_title'      => $settings['color_title'],
				'size'             => $settings['size'],
				'font_heading'     => $settings['font_heading'],
				'custom_heading'   => array(
					'custom_font_size'   => $settings['custom_font_size_heading'],
					'custom_font_weight' => $settings['custom_font_weight_heading'],
					'custom_mg_bt'       => $settings['custom_mg_bt'],
					'custom_mg_top'      => $settings['custom_mg_top']
				),
				'line_after_title' => $settings['line_after_title']
			);

			$settings['desc_group'] = array(
				'content'              => $settings['content'],
				'custom_font_size_des' => $settings['custom_font_size_des'],
				'custom_font_weight'   => $settings['custom_font_weight_des'],
				'color_description'    => $settings['color_description']
			);

			$settings['read_more_group'] = array(
				'link'                   => $settings['link']['url'],
				'target'                 => ! empty( $settings['link']['is_external'] ) ? '_blank' : '_self',
				'nofollow'               => ! empty( $settings['link']['nofollow'] ) ? ' rel="nofollow"' : '',
				'read_more'              => $settings['read_more'],
				'link_to_icon'           => $settings['link_to_icon'],
				'button_read_more_group' => array(
					'read_text'                  => $settings['read_text'],
					'read_more_text_color'       => $settings['read_more_text_color'],
					'border_read_more_text'      => $settings['border_read_more_text'],
					'bg_read_more_text'          => $settings['bg_read_more_text'],
					'read_more_text_color_hover' => $settings['read_more_text_color_hover'],
					'bg_read_more_text_hover'    => $settings['bg_read_more_text_hover']
				)
			);

			$settings['font_awesome_group'] = array(
				'icon'      => $settings['icon'],
				'icon_size' => $settings['icon_size']
			);

			$settings['font_flaticon_group'] = array(
				'icon'      => $settings['flat_icon'],
				'icon_size' => $settings['flat_icon_size']
			);

			$settings['font_7_stroke_group']          = array(
				'icon'      => $settings['stroke_icon'],
				'icon_size' => $settings['stroke_icon_size']
			);
			$settings['font_ionicons_group']          = array(
				'icon'      => $settings['font_ionicons'],
				'icon_size' => $settings['font_ionicons_size'],
			);
			$settings['font_image_group']['icon_img'] = isset($settings['icon_img']) ? $settings['icon_img']['id'] : '';
			$settings['width_icon_box']               = $settings['width_icon_box']['size'];
			$settings['height_icon_box']              = $settings['height_icon_box']['size'];

			$settings['color_group'] = array(
				'icon_color'              => $settings['icon_color'],
				'icon_border_color'       => $settings['icon_border_color'],
				'icon_bg_color'           => $settings['icon_bg_color'],
				'icon_hover_color'        => $settings['icon_hover_color'],
				'icon_border_color_hover' => $settings['icon_border_color_hover'],
				'icon_bg_color_hover'     => $settings['icon_bg_color_hover']
			);

			$settings['layout_group'] = array(
				'box_icon_style' => $settings['box_icon_style'],
				'pos'            => $settings['pos'],
				'text_align_sc'  => $settings['text_align_sc'],
				'style_box'      => $settings['style_box'],
				'dot_line'       => $settings['dot_line'],
				'bg_image_box'   => $settings['bg_image_box'],
				'bg_image_pos'   => $settings['bg_image_pos'],
			);


			return $settings;
		}
	}
}