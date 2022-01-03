<?php

namespace MyHomeCore\Estates\Prices;


use MyHomeCore\Attributes\Price_Attribute_Options_Page;

/**
 * Class Currencies
 * @package MyHomeCore\Estates\Prices
 */
class Currencies {

	/**
	 * @var Currency[]
	 */
	private static $currencies = array();

	/**
	 * @return bool|Currency
	 */
	public static function get_current() {
		if ( ! empty( \MyHomeCore\My_Home_Core()->currency ) && \MyHomeCore\My_Home_Core()->currency != 'any' ) {
			$currency_key = \MyHomeCore\My_Home_Core()->currency;
			$currency     = self::get_by_key( $currency_key );

			if ( $currency != false ) {
				return $currency;
			}
		}

		$currencies = self::get_all();

		if ( isset( $currencies[0] ) ) {
			return $currencies[0];
		}

		return false;
	}

	/**
	 * @param \string $key
	 *
	 * @return bool|Currency
	 */
	public static function get_by_key( $key ) {
		foreach ( self::get_all() as $currency ) {
			if ( $currency->get_key() == $key ) {
				return $currency;
			}
		}

		return false;
	}

	/**
	 * @return Currency[]
	 */
	public static function get_all() {
		if ( ! empty( self::$currencies ) ) {
			return self::$currencies;
		}

		$price_settings = Price_Attribute_Options_Page::get_settings();
		$currencies     = isset( $price_settings['currencies'] ) ? $price_settings['currencies'] : array();

		foreach ( $currencies as $currency ) {
			self::$currencies[] = new Currency( $currency );
		}

		return self::$currencies;
	}

}