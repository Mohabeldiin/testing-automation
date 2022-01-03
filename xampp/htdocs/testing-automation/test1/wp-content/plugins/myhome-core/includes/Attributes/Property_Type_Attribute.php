<?php

namespace MyHomeCore\Attributes;


class Property_Type_Attribute extends Text_Attribute {

	/**
	 * @return Property_Type_Attribute_Options_Page
	 */
	public function get_options_page() {
		return new Property_Type_Attribute_Options_Page( $this );
	}

}