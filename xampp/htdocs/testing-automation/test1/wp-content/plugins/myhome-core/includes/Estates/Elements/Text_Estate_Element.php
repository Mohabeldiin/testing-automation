<?php

namespace MyHomeCore\Estates\Elements;


/**
 * Class Text_Estate_Element
 * @package MyHomeCore\Estates\Elements
 */
class Text_Estate_Element extends Estate_Element {

	public static $IS_SINGLE = false;

	/**
	 * @return string
	 */
	protected function get_template_name() {
		return 'text';
	}

}