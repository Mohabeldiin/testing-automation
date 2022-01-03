<?php

namespace MyHomeCore\Estates\Elements;


/**
 * Class Custom_Estate_Element
 * @package MyHomeCore\Estates\Elements
 */
class Custom_Estate_Element extends Estate_Element {

	/**
	 * @return string
	 */
	protected function get_template_name() {
		return isset( $this->element['template'] ) ? $this->element['template'] : '';
	}

}