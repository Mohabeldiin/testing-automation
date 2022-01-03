<?php

namespace MyHomeCore\Estates\Elements;

use MyHomeCore\Estates\Estate;

/**
 * Class Estate_Element_Factory
 * @package MyHomeCore\Estates\Elements
 */
class Estate_Element_Factory {

	/**
	 * @param Estate $estate
	 *
	 * @return Estate_Element[]
	 */
	public static function get( Estate $estate ) {
		$elements      = array();
		$elements_list = get_option( Estate_Elements_Settings::OPTION_KEY );
		$elements_list = apply_filters( 'myhome_single_property_elements', $elements_list );

		if ( ! is_array( $elements_list ) ) {
			$elements_list = array();
		}

		foreach ( $elements_list as $element ) {
			$elements[] = Estate_Element::get_instance( $element, $estate );
		}

		$custom_elements = apply_filters( 'myhome_single_property_custom_sections', array() );
		if ( ! empty( $custom_elements ) && is_array( $custom_elements ) ) {
			foreach ( $custom_elements as $custom_element ) {
				$position = intval( $custom_element['position'] );
				if ( $position > count( $elements ) ) {
					$position = count( $elements );
				}
				$custom_element  = new Custom_Estate_Element( $custom_element, $estate );
				$temp_elements   = array_slice( $elements, 0, $position, true );
				$temp_elements[] = $custom_element;
				$elements        = array_merge( $temp_elements, array_slice( $elements, $position, count( $elements ) - $position, true ) );
			}
		}

		return $elements;
	}

}