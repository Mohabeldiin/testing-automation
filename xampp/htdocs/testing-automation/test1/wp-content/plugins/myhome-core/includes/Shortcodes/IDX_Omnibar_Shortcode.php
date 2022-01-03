<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class IDX_Omnibar_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class IDX_Omnibar_Shortcode extends Shortcode {

	/**
	 * @param array   $args
	 * @param \string $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$args = vc_map_get_attributes( 'mh_idx_omnibar', $args );
		}

		global $myhome_idx_omnibar_show_fields;
		$myhome_idx_omnibar_show_fields = isset( $args['additional_fields'] ) && ! empty( $args['additional_fields'] );

		return $this->get_template();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		return array(
			array(
				'type'       => 'checkbox',
				'param_name' => 'additional_fields',
				'heading'    => esc_html__( 'Additional fields', 'myhome-core' ),
				'value'      => '0'
			)
		);
	}

}