<?php

namespace MyHomeCore\Estates\Prices;

use MyHomeCore\Attributes\Price_Attribute;
use MyHomeCore\Estates\Estate;
use MyHomeCore\Terms\Term_Factory;


/**
 * Class Currency_Prices
 * @package MyHomeCore\Estates\Prices
 */
class Currency_Prices {

	/**
	 * @var Estate
	 */
	private $estate;

	/**
	 * @var Currency
	 */
	private $currency;

	/**
	 * @var Price[]
	 */
	private $prices = array();

	/**
	 * @var array
	 */
	private $price_keys = array();

	/**
	 * Currency_Prices constructor.
	 *
	 * @param  Estate  $estate
	 * @param  Currency  $currency
	 */
	public function __construct( Estate $estate, $currency ) {
		$this->estate   = $estate;
		$this->currency = $currency;
	}

	/**
	 * @return Currency
	 */
	public function get_currency() {
		return $this->currency;
	}

	private function set_prices() {
		$values         = array();
		$offer_types    = Term_Factory::get_offer_types();
		$currency_key   = $this->currency->get_key();
		$is_price_range = Price_Attribute::is_range();

		if ( $is_price_range ) {
			$price_keys = array( $currency_key . '_from', $currency_key . '_to' );
		} else {
			$price_keys = array( $currency_key );
		}

		foreach ( $price_keys as $price_key ) {
			$price_metas = $this->estate->get_meta( 'estate_attr_' . $price_key );
			$price_value = empty( $price_metas ) ? 0 : floatval( $price_metas[0] );

			if ( empty( $price_value ) ) {
				break;
			}
			$this->price_keys[] = $price_key;

			$values[] = $price_value;
		}

		if ( ( ! $is_price_range && ! empty( $values ) ) || ( $is_price_range && count( $values ) == 2 ) ) {
			$found = false;

			if ( Price_Attribute::assign_offer_type() ) {
				foreach ( $this->estate->get_offer_type() as $estate_offer_type ) {
					foreach ( $offer_types as $offer_type ) {
						if ( $offer_type->get_slug() == $estate_offer_type->get_slug() && ( $offer_type->get_after_price() != '' || $offer_type->get_before_price() != '' ) ) {
							$this->prices[] = new Price( $values, $this->currency, $offer_type );
							$found          = true;
						}
					}
				}
			}

			if ( ! $found ) {
				$this->prices[] = new Price( $values, $this->currency );
			}
		}

		foreach ( $offer_types as $offer_type ) {
			if ( ! $offer_type->specify_price() ) {
				continue;
			}

			$is_price_range = $offer_type->is_price_range();
			if ( $is_price_range ) {
				$price_keys = array( $currency_key . '_from', $currency_key . '_to' );
			} else {
				$price_keys = array( $currency_key );
			}

			$values = array();

			foreach ( $price_keys as $price_key ) {
				$price_metas = $this->estate->get_meta( 'estate_attr_' . $price_key . '_offer_' . $offer_type->get_ID() );
				$price_value = empty( $price_metas ) ? 0 : floatval( $price_metas[0] );

				if ( empty( $price_value ) ) {
					break;
				}
				$this->price_keys[] = $price_key;

				$values[] = $price_value;
			}

			if ( ( ! $is_price_range && ! empty( $values ) ) || ( $is_price_range && count( $values ) == 2 ) ) {
				$this->prices[] = new Price( $values, $this->currency, $offer_type );
			}
		}
	}

	/**
	 * @return array
	 */
	public function get_price_keys() {
		if ( empty( $this->prices ) ) {
			$this->set_prices();
		}

		return $this->price_keys;
	}

	/**
	 * @return Price[]
	 */
	public function get_prices() {
		if ( empty( $this->prices ) ) {
			$this->set_prices();
		}

		return $this->prices;
	}

	/**
	 * @return int
	 */
	public function count() {
		$prices = $this->get_prices();

		return count( $prices );
	}

	/**
	 * @return array
	 */
	public function get_data() {
		$prices = array();

		foreach ( $this->get_prices() as $price ) {
			$prices[] = array(
				'price'    => $price->get_formatted(),
				'is_range' => $price->is_range(),
			);
		}

		return $prices;
	}

}