<?php

namespace MyHomeCore\Estates\Elements;

/**
 * Class Text_Area_Estate_Element
 * @package MyHomeCore\Estates\Elements
 */
class Text_Area_Estate_Element extends Estate_Element {

	/**
	 * @return string
	 */
	protected function get_template_name() {
		return self::TEXTAREA;
	}

	/**
	 * @return string
	 */
	public function text() {
		the_field( 'estate_attr_' . $this->get_slug(), $this->estate->get_ID() );
	}

	/**
	 * @return bool
	 */
	public function has_text() {
		$text = get_field( 'estate_attr_' . $this->get_slug(), $this->estate->get_ID() );

		return ! empty( $text );
	}

}