<?php

namespace MyHomeCore\Estates\Elements;

/**
 * Class Widgets_Estate_Element
 * @package MyHomeCore\Estates\Elements
 */
class Widgets_Estate_Element extends Estate_Element {

	/**
	 * @return string
	 */
	protected function get_template_name() {
		return self::WIDGETS;
	}

	/**
	 * @return bool
	 */
	protected function is_active() {
		return is_active_sidebar( $this->get_slug() );
	}

}