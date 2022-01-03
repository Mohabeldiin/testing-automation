<?php

namespace MyHomeCore\Estates\Prices;

use MyHomeCore\Attributes\Price_Attribute;
use MyHomeCore\Terms\Term;
use MyHomeCore\Terms\Term_Factory;


/**
 * Class Currency
 * @package MyHomeCore\Estates
 */
class Currency {

	const SIGN_AFTER = 'after_price';
	const SIGN_BEFORE = 'before_price';

	/**
	 * @var \string
	 */
	private $key;

	/**
	 * @var \string
	 */
	private $sign;

	/**
	 * @var \string
	 */
	private $location;

	/**
	 * @var \string
	 */
	private $thousands_sep;

	/**
	 * @var \string
	 */
	private $decimal_sep;

	/**
	 * @var \int
	 */
	private $decimal;

	/**
	 * Currency constructor.
	 *
	 * @param array $currency_data
	 */
	public function __construct( $currency_data ) {
		$this->key           = $currency_data['key'];
		$this->sign          = $currency_data['sign'];
		$this->location      = $currency_data['location'];
		$this->thousands_sep = $currency_data['thousands_sep'];
		$this->decimal_sep   = $currency_data['decimal_sep'];
		$this->decimal       = intval( $currency_data['decimal'] );
	}

	/**
	 * @param float[] $price_values
	 *
	 * @return string
	 */
	public function get_formatted_price( $price_values ) {
		$values = array();

		foreach ( $price_values as $value ) {
			$price = number_format( $value, $this->decimal, $this->decimal_sep, $this->thousands_sep );

			$price = apply_filters( 'myhome_price_value', $price, $this->key, $this->decimal_sep, $this->thousands_sep, $value );

			$values[] = $this->location == Currency::SIGN_BEFORE ? $this->sign . $price : $price . $this->sign;
		}

		return implode( ' - ', $values );
	}

	/**
	 * @return \string
	 */
	public function get_sign() {
		return $this->sign;
	}

	/**
	 * @return string
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * @param Term[] $selected_offer_types
	 *
	 * @return array
	 */
	public function get_price_keys( $selected_offer_types = array() ) {
		$keys = array();

		if ( empty( $selected_offer_types ) || Price_Attribute::assign_offer_type() ) {
			if ( Price_Attribute::is_range() ) {
				$keys[] = $this->key . '_from';
				$keys[] = $this->key . '_to';
			} else {
				$keys[] = $this->key;
			}
		}

		if ( empty( $selected_offer_types ) ) {
			$offer_types = Term_Factory::get_offer_types();
		} else {
			$offer_types = $selected_offer_types;
		}

		foreach ( $offer_types as $offer_type ) {
			if ( ! $offer_type->specify_price() ) {
				continue;
			}

			if ( $offer_type->is_price_range() ) {
				$keys[] = $this->key . '_from_offer_' . $offer_type->get_ID();
				$keys[] = $this->key . '_to_offer_' . $offer_type->get_ID();
			} else {
				$keys[] = $this->key . '_offer_' . $offer_type->get_ID();
			}
		}

		return $keys;
	}

	public function get_price_keys_list() {
		$keys = array();

		if ( empty( $selected_offer_types ) || Price_Attribute::assign_offer_type() ) {
			if ( Price_Attribute::is_range() ) {
				$keys[ $this->key . '_from' ] = sprintf( esc_html__( 'Price from (%s)', 'myhome-core' ), $this->get_sign() );
				$keys[ $this->key . '_to' ]   = sprintf( esc_html__( 'Price to (%s)', 'myhome-core' ), $this->get_sign() );
			} else {
				$keys[ $this->key ] = sprintf( esc_html__( 'Price (%s)', 'myhome-core' ), $this->get_sign() );
			}
		}

		if ( empty( $selected_offer_types ) ) {
			$offer_types = Term_Factory::get_offer_types();
		} else {
			$offer_types = $selected_offer_types;
		}

		foreach ( $offer_types as $offer_type ) {
			if ( ! $offer_type->specify_price() ) {
				continue;
			}

			if ( $offer_type->is_price_range() ) {
				$keys[ $this->key . '_from_offer_' . $offer_type->get_ID() ] = sprintf( esc_html__( 'Price from (%s) - %s', 'myhome-core' ), $this->get_sign(), $offer_type->get_name() );
				$keys[ $this->key . '_tom_offer_' . $offer_type->get_ID() ]  = sprintf( esc_html__( 'Price to (%s) - %s', 'myhome-core' ), $this->get_sign(), $offer_type->get_name() );
			} else {
				$keys[ $this->key . '_offer_' . $offer_type->get_ID() ] = sprintf( esc_html__( 'Price (%s) - %s', 'myhome-core' ), $this->get_sign(), $offer_type->get_name() );
			}
		}

		return $keys;
	}

	public function get_estate_ids() {
		$estate_ids = array();

		foreach ( $this->get_price_keys() as $price_key ) {
			$args = array(
				'post_type'      => 'estate',
				'fields'         => 'ids',
				'posts_per_page' => - 1,
				'meta_query'     => array(
					array(
						'key'     => 'estate_attr_' . $price_key,
						'value'   => array( '', '0' ),
						'compare' => 'NOT IN'
					)
				)
			);

			$wp_query   = new \WP_Query( $args );
			$estate_ids = array_merge( $estate_ids, array_values( $wp_query->posts ) );
		}

		return $estate_ids;
	}

}