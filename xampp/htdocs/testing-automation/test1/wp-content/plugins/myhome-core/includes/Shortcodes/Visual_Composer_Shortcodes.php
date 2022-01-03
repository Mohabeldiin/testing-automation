<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class Visual_Composer_Shortcodes
 * @package MyHomeCore\Shortcodes
 */
class Visual_Composer_Shortcodes {

	/**
	 * @var Shortcode[]
	 */
	private $shortcodes;

	/**
	 * Visual_Composer_Shortcodes constructor.
	 *
	 * @param Shortcode[] $shortcodes
	 */
	public function __construct( $shortcodes ) {
		$this->shortcodes = $shortcodes;
	}

	public function register() {
		foreach ( $this->shortcodes as $shortcode ) {
			vc_lean_map( $shortcode->get_slug(), array( $this, $shortcode->get_slug() ) );
		}
	}

	/**
	 * @param $name
	 * @param $arguments
	 *
	 * @return array
	 */
	public function __call( $name, $arguments ) {
		if ( array_key_exists( $name, $this->shortcodes ) ) {
			return $this->shortcodes[ $name ]->vc_settings();
		}
	}

}