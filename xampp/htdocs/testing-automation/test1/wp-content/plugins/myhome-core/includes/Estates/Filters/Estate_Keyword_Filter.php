<?php

namespace MyHomeCore\Estates\Filters;


/**
 * Class Estate_Keyword_Filter
 * @package MyHomeCore\Estates
 */
class Estate_Keyword_Filter extends Estate_Filter {

	/**
	 * @return string
	 */
	public function get_arg() {
		$keyword = '';
		foreach ( $this->values as $value ) {
			$keyword .= $value->get_name();
		}

		return $keyword;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return self::KEYWORD;
	}

}