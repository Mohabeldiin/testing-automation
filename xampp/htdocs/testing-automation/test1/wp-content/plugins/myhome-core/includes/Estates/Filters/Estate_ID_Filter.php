<?php

namespace MyHomeCore\Estates\Filters;


/**
 * Class Estate_ID_Filter
 * @package MyHomeCore\Estates
 */
class Estate_ID_Filter extends Estate_Filter {

	/**
	 * @return string
	 */
	public function get_arg() {
		$id = '';
		foreach ( $this->values as $value ) {
			$id .= $value->get_name();
		}

		return $id;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return self::ID;
	}

}