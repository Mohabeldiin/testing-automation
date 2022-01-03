<?php

namespace MyHomeCore\Attributes;


class Price_Attribute_Options_Page extends Number_Attribute_Options_Page {

	const OPTION_NAME = 'myhome_currency_settings';

	/**
	 * Price_Attribute_Options_Page constructor.
	 *
	 * @param Attribute $attribute
	 */
	public function __construct( Attribute $attribute ) {
		parent::__construct( $attribute );

		// remove show_card and show_property from options because price is displayed differently
		unset( $this->basic_fields['show_card'] );
		unset( $this->basic_fields['show_property'] );

		$basic_fields = array(
			array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_range',
				'label'         => esc_html__( 'Define price as range', 'myhome-core' ),
				'name'          => $this->attribute->get_slug() . '_range',
				'type'          => 'true_false',
				'default_value' => false
			),
			array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_assign_offer_type',
				'label'         => esc_html__( 'Assign offer type "display after" to default price', 'myhome-core' ),
				'name'          => $this->attribute->get_slug() . '_assign_offer_type',
				'type'          => 'true_false',
				'default_value' => true
			)
		);

		$this->basic_fields = array_merge( $this->basic_fields, $basic_fields );
	}

	/**
	 * @return array
	 */
	public static function get_settings() {
		$settings = get_option( self::OPTION_NAME );

		if ( empty( $settings ) ) {
			return self::get_default_settings();
		}

		return $settings;
	}

	/**
	 * @return bool
	 */
	public static function show_price() {
		$settings = self::get_settings();

		return $settings['hide_price'] != 'true';
	}

	/**
	 * @return string
	 */
	public static function get_default_value() {
		$settings = self::get_settings();

		return apply_filters( 'wpml_translate_single_string', $settings['default_value'], 'myhome-core', 'when price not set' );
	}

	/**
	 * @return array
	 */
	public static function get_currencies() {
		$settings = self::get_settings();

		return isset( $settings['currencies'] ) ? $settings['currencies'] : array();
	}

	/**
	 * @return array
	 */
	public static function get_currencies_list() {
		$currencies = array( 'any' => esc_html__( 'Default', 'myhome-core' ) );

		foreach ( self::get_currencies() as $currency ) {
			$currencies[ $currency['key'] ] = $currency['sign'];
		}

		return $currencies;
	}

	/**
	 * @param $currency_key
	 *
	 * @return bool|array
	 */
	public static function get_currency_by_key( $currency_key ) {
		foreach ( self::get_currencies() as $currency ) {
			if ( $currency['key'] == $currency_key ) {
				return $currency;
			}
		}

		return false;
	}

	public static function get_default_settings() {
		$settings = array(
			'default_value' => '',
			'hide_price'    => false,
			'currencies'    => array()
		);

		$currency        = array( 'name' => esc_html__( 'USD', 'myhome-core' ) );
		$currency['key'] = 'price';

		if ( isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate-currency_sign'] ) ) {
			$currency['sign'] = \MyHomeCore\My_Home_Core()->settings->props['mh-estate-currency_sign'];
		} else {
			$currency['sign'] = esc_html__( '$', 'myhome-core' );
		}

		if ( isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate-currency_location'] ) ) {
			$currency['location'] = \MyHomeCore\My_Home_Core()->settings->props['mh-estate-currency_location'];
		} else {
			$currency['location'] = 'before_price';
		}

		if ( isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate-price_thousands_sep'] ) ) {
			$currency['thousands_sep'] = \MyHomeCore\My_Home_Core()->settings->props['mh-estate-price_thousands_sep'];
		} else {
			$currency['thousands_sep'] = ',';
		}

		if ( isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate-price_decimal_sep'] ) ) {
			$currency['decimal_sep'] = \MyHomeCore\My_Home_Core()->settings->props['mh-estate-price_decimal_sep'];
		} else {
			$currency['decimal_sep'] = '.';
		}

		if ( isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate-price_decimal'] ) ) {
			$currency['decimal'] = \MyHomeCore\My_Home_Core()->settings->props['mh-estate-price_decimal'];
		} else {
			$currency['decimal'] = 0;
		}

		$settings['currencies'][] = $currency;

		update_option( self::OPTION_NAME, $settings, true );

		return $settings;
	}

	/**
	 * @param array $attribute_data
	 */
	public function update_options( $attribute_data ) {
		$option_names = array(
			'text'   => array(
				'search_form_control' => 'search_form_control',
				'static_values'       => 'static_values',
				'placeholder'         => 'placeholder',
				'placeholder_from'    => 'placeholder_from',
				'placeholder_to'      => 'placeholder_to',
				'display_after'       => 'display_after',
				'number_operator'     => 'number_operator',
				'icon'                => 'icon',
			),
			'number' => array(
				'parent_id' => 'parent_id'
			),
			'bool'   => array(
				'checkbox_full_width' => 'checkbox_full_width',
				'card_show'           => 'show_card',
				'property_show'       => 'show_property',
				'suggestions'         => 'suggestions',
				'range'               => 'range',
				'assign_offer_type'   => 'assign_offer_type'
			)
		);

		$this->save_options( $option_names, $attribute_data );
	}

}