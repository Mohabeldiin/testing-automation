<?php

namespace MyHomeCore\Shortcodes;


use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Terms\Term_Factory;

class Mosaic_Shortcode extends Shortcode {

	/**
	 * @param array       $args
	 * @param string|null $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		$atts = array(
			'attribute'    => '',
			'total_number' => 5,
		);

		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$atts = array_merge( $atts, vc_map_get_attributes( 'mh_mosaic_attribute', $args ) );
		}

		if ( empty( $atts['attribute'] ) ) {
			return '';
		}

		$total_number = intval( $atts['total_number'] );
		$attribute    = Attribute_Factory::get_by_ID( $atts['attribute'] );
		if ( $attribute === false ) {
			return '';
		}
		/* @var \MyHomeCore\Attributes\Text_Attribute $attribute */
		$terms = Term_Factory::get( $attribute, $total_number, 'count', 'desc', true );
		global $myhome_mosaic;
		$myhome_mosaic = $terms;

		return $this->get_template();
	}

	public function get_vc_params() {
		$attributes = array();
		foreach ( Attribute_Factory::get_text() as $attr ) {
			$attributes[ $attr->get_name() ] = $attr->get_ID();
		}

		return array(
			// Attribute
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Attribute', 'myhome-core' ),
				'description' => esc_html__( 'It shows options with at least one property assigned e.g. it will not show Washington if it has not at least one property assigned', 'myhome-core' ),
				'param_name'  => 'attribute',
				'value'       => $attributes,
			),
			// Total number
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Total elements number', 'myhome-core' ),
				'param_name'  => 'total_number',
				'value'       => 5,
				'description' => esc_html__( '0 or empty = all elements', 'myhome-core' )
			),
		);
	}

}