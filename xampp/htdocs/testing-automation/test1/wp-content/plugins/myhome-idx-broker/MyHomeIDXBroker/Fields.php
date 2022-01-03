<?php

namespace MyHomeIDXBroker;


use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Price_Attribute;
use MyHomeCore\Estates\Prices\Currencies;


/**
 * Class Fields
 * @package MyHomeIDXBroker
 */
class Fields {

	const OPTION_KEY = 'myhome_idx_broker_fields';

	public function import() {
		$skip                = array(
			'cityID',
			'coListingAgentID',
			'countyID',
			'farCityID',
			'parentPtID',
			'parentPtID',
			'latitude',
			'longitude',
			'mlsPtID',
			'listingOfficeID',
			'coListingOfficeID',
			'tertiaryListingAgentID',
			'tertiaryListingOfficeID',
			'customGeocode',
			'listingAgentID',
			'mlsPhotoCount',
			'TLNFIRMID',
			'TLNREALTORID'
		);
		$api                 = new Api();
		$mls_imported_fields = array();
		$fields              = array();
		$current_fields      = array();

		foreach ( MLS::get() as $mls_id ) {
			$mls_imported_fields[] = $api->get_search_fields( $mls_id );
		}

		$keys = array();
		foreach ( $mls_imported_fields as $imported_fields ) {
			foreach ( $imported_fields as $field ) {
				$keys[] = $field->name;
			}
		}

		foreach ( $mls_imported_fields as $imported_fields ) {
			foreach ( $imported_fields as $field ) {
				if ( ! in_array( $field->name, array(
					'address',
					'streetName',
					'streetNumber',
					'streetDirection',
					'unitNumber',
					'postalcode',
					'cityName',
					'countyName',
					'state',
					'zipcode',
					'price',
					'remarksConcat',
					'bedrooms',
					'partialBaths',
					'fullBaths',
					'totalBaths',
					'acres',
					'sqFt',
					'idxPropType',
					'fullBaths',
					'listingPrice',
					'propType',
					'listingID'
				) ) ) {
					continue;
				}

				if ( in_array( $field->name, $skip ) ) {
					continue;
				}

				if ( isset( $current_fields[ $field->name ] ) ) {
					$fields[ $field->name ] = array(
						'name'         => $current_fields[ $field->name ]->get_name(),
						'display_name' => $current_fields[ $field->name ]->get_display_name(),
						'value'        => $current_fields[ $field->name ]->get_value()
					);
					continue;
				}

				$data = array(
					'name'         => $field->name,
					'display_name' => $field->displayName
				);
				$name = $field->name;
				if ( $name == 'price' ) {
					$data['value'] = 'myhome_price';
				} elseif ( $name == 'cityName' ) {
					$data['value'] = 'myhome_attribute_10';
				} elseif ( $name == 'bedrooms' ) {
					$data['value'] = 'myhome_attribute_5';
				} elseif ( $name == 'idxPropType' ) {
					$data['value'] = 'myhome_attribute_1';
				} elseif ( $name == 'propType' ) {
					$data['value'] = 'myhome_attribute_1';
				} elseif ( $name == 'propSubType' ) {
					$data['value'] = 'myhome_attribute_1';
				} elseif ( $name == 'fullBaths' ) {
					if ( in_array( 'totalBaths', $keys ) ) {
						$data['value'] = 'myhome_additional_features';
					} else {
						$data['value'] = 'myhome_attribute_7';
					}
				} elseif ( $name == 'partialBaths' ) {
					if ( in_array( 'totalBaths', $keys ) || in_array( 'fullBaths', $keys ) ) {
						$data['value'] = 'myhome_additional_features';
					} else {
						$data['value'] = 'myhome_attribute_7';
					}
				} elseif ( $name == 'sqFt' ) {
					$data['value'] = 'myhome_attribute_8';
				} elseif ( $name == 'acres' ) {
					$data['value'] = 'myhome_additional_features';
				} elseif ( $name == 'totalBaths' ) {
					$data['value'] = 'myhome_attribute_7';
				} elseif ( $name == 'address' ) {
					$data['value'] = 'myhome_name';
				} elseif ( $name == 'remarksConcat' ) {
					$data['value'] = 'myhome_description';
				} elseif ( $name == 'zipcode' ) {
					$data['value'] = 'myhome_attribute_16';
				} elseif ( $name == 'streetName' ) {
					$data['value'] = 'myhome_attribute_15';
				} elseif ( $name == 'countyName' ) {
					$data['value'] = 'myhome_additional_features';
				} elseif ( $name == 'state' ) {
					$data['value'] = 'myhome_additional_features';
				} elseif ( $name == 'listingPrice' ) {
					if ( ! in_array( 'price', $keys ) ) {
						$data['value'] = 'myhome_price';
					}
				}

				$fields[ $field->name ] = $data;
			}
		}

		update_option( Fields::OPTION_KEY, $fields );
	}

	public function save() {
		if ( ! isset( $_POST['fields'] ) ) {
			return;
		}

		$current_fields = Fields::get();
		$saved_fields   = $_POST['fields'];
		$fields         = array();

		foreach ( $saved_fields as $field_key => $field_value ) {
			$fields[] = array(
				'name'         => $field_key,
				'display_name' => $current_fields[ $field_key ]->get_display_name(),
				'value'        => $field_value
			);
		}

		update_option( Fields::OPTION_KEY, $fields );
	}

	/**
	 * @return Field[]
	 */
	public static function get() {
		$fields         = array();
		$current_fields = get_option( Fields::OPTION_KEY, array() );

		foreach ( $current_fields as $field_data ) {
			$field                        = new Field( $field_data );
			$fields[ $field->get_name() ] = $field;
		}

		return $fields;
	}

	/**
	 * @param string $key
	 *
	 * @return bool|Field
	 */
	public static function get_by_key( $key ) {
		foreach ( Fields::get() as $field ) {
			if ( $key == $field->get_name() ) {
				return $field;
			}
		}

		return false;
	}

	/**
	 * @return array
	 */
	public static function get_attributes() {
		$attributes = array(
			'myhome_name'                => esc_html__( 'Property name', 'myhome-idx-broker' ),
			'myhome_description'         => esc_html__( 'Property description', 'myhome-idx-broker' ),
			'myhome_additional_features' => esc_html__( 'Additional features', 'myhome-idx-broker' )
		);

		foreach ( Attribute_Factory::get_text() as $attribute ) {
			$attributes[ 'myhome_attribute_' . $attribute->get_ID() ] = $attribute->get_name();
		}

		foreach ( Attribute_Factory::get_number() as $attribute ) {
			if ( $attribute instanceof Price_Attribute ) {
				continue;
			}

			$attributes[ 'myhome_attribute_' . $attribute->get_ID() ] = $attribute->get_name();
		}

		foreach ( Currencies::get_all() as $currency ) {
			foreach ( $currency->get_price_keys_list() as $key => $label ) {
				$attributes[ 'myhome_' . $key ] = $label;
			}
		}

		asort( $attributes );

		return $attributes;
	}

}