<?php

namespace MyHomeCore\Attributes;


use MyHomeCore\Estates\Filters\Estate_Price_Filter;
use MyHomeCore\Estates\Prices\Currencies;

/**
 * Class Price_Attribute
 * @package MyHomeCore\Attributes
 */
class Price_Attribute extends Number_Attribute {

	/**
	 * @var \bool
	 */
	public static $is_range;
	public static $assign_offer_type;

	/**
	 * @return string
	 */
	public function get_full_name() {
		$full_name     = $this->get_name();
		$currency_sign = \MyHomeCore\My_Home_Core()->settings->get( 'estate-currency_sign' );
		if ( ! empty( $currency_sign ) ) {
			$full_name .= ' (' . $currency_sign . ')';
		}

		return $full_name;
	}

	/**
	 * @return string
	 */
	public function get_display_after() {
		$currency_sign = Currencies::get_current()->get_sign();

		return empty( $currency_sign ) ? '' : $currency_sign;
	}

	/**
	 * @return bool
	 */
	public function get_range() {
		$range = get_option( 'options_' . $this->get_slug() . '_range' );

		return ! empty( $range );
	}

	/**
	 * @return bool
	 */
	public function get_assign_offer_type() {
		$assign_offer_type = get_option( 'options_' . $this->get_slug() . '_assign_offer_type' );

		return ! empty( $assign_offer_type );
	}

	/**
	 * @return bool
	 */
	public static function is_range() {
		if ( is_null( self::$is_range ) ) {
			$price_attribute = Attribute_Factory::get_price();

			if ( $price_attribute == false ) {
				return false;
			}

			if ( function_exists( 'icl_object_id' ) ) {
				add_filter( 'acf/settings/current_language', function () {
					return \MyHomeCore\My_Home_Core()->default_language;
				} );
				self::$is_range = $price_attribute->get_range();
				add_filter( 'acf/settings/current_language', function () {
					return \MyHomeCore\My_Home_Core()->current_language;
				} );
			} else {
				self::$is_range = $price_attribute->get_range();
			}
		}

		return self::$is_range;
	}

	/**
	 * @return bool
	 */
	public static function assign_offer_type() {
		if ( is_null( self::$assign_offer_type ) ) {
			$price_attribute = Attribute_Factory::get_price();

			if ( $price_attribute == false ) {
				return true;
			}

			if ( function_exists( 'icl_object_id' ) ) {
				add_filter( 'acf/settings/current_language', function () {
					return \MyHomeCore\My_Home_Core()->default_language;
				} );
				self::$assign_offer_type = $price_attribute->get_assign_offer_type();
				add_filter( 'acf/settings/current_language', function () {
					return \MyHomeCore\My_Home_Core()->current_language;
				} );
			} else {
				self::$assign_offer_type = $price_attribute->get_assign_offer_type();
			}

		}

		return self::$assign_offer_type;
	}

	/**
	 * @param Attribute_Values $attribute_values
	 * @param string           $compare
	 *
	 * @return Estate_Price_Filter
	 */
	public function get_estate_filter( Attribute_Values $attribute_values, $compare = '=' ) {
		return new Estate_Price_Filter( $this, $attribute_values, $compare );
	}

	/**
	 * @return bool
	 */
	public function get_property_show() {
		return false;
	}

	/**
	 * @return bool
	 */
	public function get_card_show() {
		return false;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return self::PRICE;
	}

	/**
	 * @return string
	 */
	public function get_type_name() {
		return esc_html__( 'Price field', 'myhome-core' );
	}

	/**
	 * @return Price_Attribute_Options_Page
	 */
	public function get_options_page() {
		return new Price_Attribute_Options_Page( $this );
	}

	/**
	 * @return array
	 */
	public function get_data() {
		return array(
			'id'                  => $this->get_ID(),
			'name'                => $this->get_name(),
			'full_name'           => $this->get_full_name(),
			'base_slug'           => $this->get_base_slug(),
			'slug'                => $this->get_slug(),
			'type'                => $this->get_type(),
			'type_name'           => $this->get_type_name(),
			'form_order'          => $this->get_form_order(),
			'display_after'       => $this->get_display_after(),
			'placeholder'         => $this->get_placeholder(),
			'placeholder_from'    => $this->get_placeholder_from(),
			'placeholder_to'      => $this->get_placeholder_to(),
			'search_form_control' => $this->get_search_form_control(),
			'static_values'       => $this->get_static_values(),
			'full_width'          => $this->get_full_width(),
			'card_show'           => $this->get_card_show(),
			'property_show'       => $this->get_property_show(),
			'number_operator'     => $this->get_number_operator(),
			'suggestions'         => $this->get_suggestions(),
			'range'               => $this->get_range(),
			'assign_offer_type'   => $this->get_assign_offer_type(),
			'currency_settings'   => Price_Attribute_Options_Page::get_settings()
		);
	}

	/**
	 * @param array $attribute
	 *
	 * @return Attribute
	 * @throws \ErrorException
	 */
	public function update( $attribute ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'myhome_attributes';
		$result     = $wpdb->update(
			$table_name,
			array(
				'attribute_name' => $attribute['name'],
				'form_order'     => $attribute['form_order'],
			),
			array( 'ID' => intval( $attribute['id'] ) ),
			array( '%s', '%d' )
		);

		if ( $result === false ) {
			// @todo sprawdzic jak dokladnie wyswietlac errory
			throw new \ErrorException(
				esc_html__( 'Something went wrong during fields order update.', 'myhome-core' ),
				400
			);
		}

		foreach ( $attribute['currency_settings']['currencies'] as $key => $currency ) {
            $attribute['currency_settings']['currencies'][ $key ]['thousands_sep'] = stripslashes_deep($attribute['currency_settings']['currencies'][ $key ]['thousands_sep']);

			if ( empty( $currency['key'] ) ) {
				$currency_key = intval( get_option( 'myhome_currency_key', 1 ) ) + 1;
				update_option( 'myhome_currency_key', $currency_key );
				$attribute['currency_settings']['currencies'][ $key ]['key'] = 'price_' . $currency_key;
			}
		}

		$this->update_options( $attribute );
		update_option( Price_Attribute_Options_Page::OPTION_NAME, $attribute['currency_settings'], true );

		return Attribute::get_by_id( $attribute['id'] );
	}

}