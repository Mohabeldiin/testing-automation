<?php

namespace MyHomeCore\Estates\Sidebar_Elements;


/**
 * Class Sidebar_Elements
 */
class Sidebar_Elements {

	/**
	 * @var Sidebar_Element[]
	 */
	private $elements;

	/**
	 * Sidebar_Elements constructor.
	 *
	 * @param $estate_id
	 */
	public function __construct( $estate_id ) {
		$elements = get_field( 'myhome_estate_sidebar_elements' );

		if ( ! empty( $elements ) ) {
			foreach ( $elements as $element ) {
				$this->elements[] = new Sidebar_Element( $element );
			}
		}
	}

	/**
	 * @return bool
	 */
	public function has_elements() {
		return ! empty( $this->elements );
	}

	/**
	 * @return Sidebar_Element[]
	 */
	public function get_elements() {
		return $this->elements;
	}

}