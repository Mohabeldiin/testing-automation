<?php

namespace MyHomeCore\Estates\Prices;


use MyHomeCore\Estates\Estate;

/**
 * Class Prices
 * @package MyHomeCore\Estates
 */
class Prices {

	/**
	 * @var Currency_Prices[]
	 */
	private $currency_prices = array();

	/**
	 * Prices constructor.
	 *
	 * @param Estate $estate
	 */
	public function __construct( $estate ) {
		foreach ( Currencies::get_all() as $currency ) {
			$this->currency_prices[] = new Currency_Prices( $estate, $currency );
		}
	}

	/**
	 * @return bool|Currency_Prices
	 */
	public function get_current_currency() {
		$current_currency     = Currencies::get_current();
		$current_currency_key = $current_currency->get_key();

		if ( ! $current_currency instanceof Currency ) {
			return false;
		}

		foreach ( $this->currency_prices as $currency_price ) {
			if ( $currency_price->get_currency()->get_key() == $current_currency_key ) {
				return $currency_price;
			}
		}

		return false;
	}

	/**
	 * @return Currency_Prices[]
	 */
	public function get_all_currencies() {
		return $this->currency_prices;
	}

	/**
	 * @return bool
	 */
	public function has_price() {
		foreach ( $this->currency_prices as $currency_prices ) {
			if ( $currency_prices->count() ) {
				return true;
			}
		}

		return false;
	}

}