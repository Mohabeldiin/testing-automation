<?php

namespace MyHomeCore\Estates\Elements;


use MyHomeCore\Attributes\Attribute;
use MyHomeCore\Attributes\Attribute_Factory;


/**
 * Class Attributes_Estate_Element
 * @package MyHomeCore\Estates\Elements
 */
class Attributes_Estate_Element extends Estate_Element {

	/**
	 * @return string
	 */
	protected function get_template_name() {
		return self::ATTRIBUTES;
	}

	/**
	 * @return Attribute[]
	 */
	public function get_attributes() {
		$attributes = array();

		foreach ( Attribute_Factory::get_search() as $attribute ) {
			if ( $attribute->get_property_show() ) {
				$attributes[] = $attribute;
			}
		}

		return $attributes;
	}

}