<?php

namespace MyHomeCore\Estates\Elements;


/**
 * Class Shortcode_Estate_Element
 * @package MyHomeCore\Estates\Elements
 */
class Shortcode_Estate_Element extends Estate_Element {

	/**
	 * @return string
	 */
	protected function get_template_name() {
		return self::SHORTCODE;
	}

	/**
	 * @return bool
	 */
	public function is_single() {
		return false;
	}

	/**
	 * @return bool
	 */
	public function has_shortcode() {
		return isset( $this->element['shortcode'] ) && ! empty( $this->element['shortcode'] );
	}

	/**
	 * @return \string
	 */
	public function get_shortcode() {
		return wp_kses_stripslashes( $this->element['shortcode'] );
	}

}