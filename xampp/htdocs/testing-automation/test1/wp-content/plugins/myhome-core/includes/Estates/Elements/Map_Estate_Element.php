<?php

namespace MyHomeCore\Estates\Elements;


use MyHomeCore\Components\Estate_Map\Estate_Map;

/**
 * Class Map_Estate_Element
 * @package MyHomeCore\Estates\Elements
 */
class Map_Estate_Element extends Estate_Element {

	/**
	 * @return string
	 */
	protected function get_template_name() {
		return self::MAP;
	}

	/**
	 * @return bool
	 */
	public function has_map() {
		$address = $this->estate->has_position( true );

		return ! empty( $address )
		       && ( ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_map'] )
		            || \MyHomeCore\My_Home_Core()->settings->props['mh-estate_map'] == 'small' )
		       && ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-google-api-key'] );
	}

	public function map() {
		$estate_map = new Estate_Map( $this->estate );
		$estate_map->display();
	}

}