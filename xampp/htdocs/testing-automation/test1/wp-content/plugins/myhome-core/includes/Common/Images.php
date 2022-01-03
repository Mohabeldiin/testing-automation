<?php

namespace MyHomeCore\Common;


/**
 * Class Images
 * @package MyHomeCore\Common
 */
class Images {

	private $images = array();

	const CACHE_KEY = 'myhome_cache_images';

	public function __construct() {
		$images = get_transient( Images::CACHE_KEY );
		if ( $images !== false && is_array( $images ) ) {
			$this->images = $images;
		}
	}

	/**
	 * @param $image_id
	 * @param $thumbnail_size
	 *
	 * @return \string
	 */
	public function get( $image_id, $thumbnail_size ) {
		$image_id = intval( $image_id );
		$key      = $image_id . '_' . $thumbnail_size;

		if ( isset( $this->images[ $key ] ) ) {
			return $this->images[ $key ];
		}

		$image_srcset         = wp_get_attachment_image_srcset( $image_id, $thumbnail_size );
		$this->images[ $key ] = $image_srcset;
		set_transient( Images::CACHE_KEY, $this->images );

		return $image_srcset;
	}

}