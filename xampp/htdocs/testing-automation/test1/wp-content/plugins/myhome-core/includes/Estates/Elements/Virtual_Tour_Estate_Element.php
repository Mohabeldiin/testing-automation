<?php

namespace MyHomeCore\Estates\Elements;


/**
 * Class Virtual_Tour_Estate_Element
 * @package MyHomeCore\Estates\Elements
 */
class Virtual_Tour_Estate_Element extends Estate_Element {

	/**
	 * @return string
	 */
	protected function get_template_name() {
		return 'virtual-tour';
	}

}