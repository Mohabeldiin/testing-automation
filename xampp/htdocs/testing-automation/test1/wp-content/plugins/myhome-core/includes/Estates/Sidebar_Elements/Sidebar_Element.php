<?php

namespace MyHomeCore\Estates\Sidebar_Elements;


/**
 * Class Sidebar_Element
 */
class Sidebar_Element {

	/**
	 * @var array
	 */
	private $image;

	/**
	 * @var string
	 */
	private $text = '';

	/**
	 * @var string
	 */
	private $link = '';

	/**
	 * Sidebar_Element constructor.
	 *
	 * @param array $element_data
	 */
	public function __construct( $element_data ) {
		if ( ! empty( $element_data['estate_sidebar_element_image'] ) ) {
			$this->image = $element_data['estate_sidebar_element_image'];
		}

		if ( ! empty( $element_data['estate_sidebar_element_text'] ) ) {
			$this->text = $element_data['estate_sidebar_element_text'];
		}

		if ( ! empty( $element_data['estate_sidebar_element_link'] ) ) {
			$this->link = $element_data['estate_sidebar_element_link'];
		}
	}

	/**
	 * @return bool
	 */
	public function has_image() {
		return ! empty( $this->image ) && isset( $this->image['url'] );
	}

	/**
	 * @return \string
	 */
	public function get_image() {
		return isset( $this->image['url'] ) ? $this->image['url'] : '';
	}

	/**
	 * @return bool
	 */
	public function has_link() {
		return ! empty( $this->link );
	}

	/**
	 * @return string
	 */
	public function get_link() {
		return $this->link;
	}

	/**
	 * @return bool
	 */
	public function has_text() {
		return ! empty( $this->text );
	}

	/**
	 * @return string
	 */
	public function get_text() {
		return $this->text;
	}

}