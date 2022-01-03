<?php

namespace MyHomeCore\Shortcodes {


	use MyHomeCore\Components\Listing\Listing;
	use MyHomeCore\Components\Listing\Listing_Settings;

	/**
	 * Class Estate_Listing_Shortcode
	 * @package MyHomeCore\Shortcodes
	 */
	class Listing_Shortcode extends Shortcode {

		/**
		 * @return array
		 */
		public function get_vc_params() {
			return Listing_Settings::get_vc_settings();
		}

		/**
		 * @param array       $args
		 * @param string|null $content
		 *
		 * @return string
		 */
		public function display( $args = array(), $content = null ) {
			$listing = new Listing( $args );
			ob_start();
			$listing->display( $content );

			return ob_get_clean();
		}

	}
}

namespace {
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_mh_listing extends WPBakeryShortCodesContainer {
		}
	}
}