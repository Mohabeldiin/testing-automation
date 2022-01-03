<?php

namespace MyHomeCore\Frontend_Panel;


use MyHomeCore\Attributes\Attribute;
use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Attribute_Options_Page;
use MyHomeCore\Attributes\Attribute_Values;
use MyHomeCore\Attributes\Number_Attribute;
use MyHomeCore\Attributes\Price_Attribute;
use MyHomeCore\Attributes\Text_Attribute;
use MyHomeCore\Estates\Elements\Estate_Element;
use MyHomeCore\Estates\Prices\Currencies;
use MyHomeCore\Terms\Term;
use MyHomeCore\Terms\Term_Factory;
use function MyHomeCore\My_Home_Core;

/**
 * Class Panel_Fields
 * @package MyHomeCore\Panel
 */
class Panel_Fields {

	const OPTIONS_KEY = 'myhome_panel_fields';

	public static function get_price_fields( Price_Attribute $attribute ) {
		$currencies = Currencies::get_all();

		if ( function_exists( 'icl_object_id' ) && ( \MyHomeCore\My_Home_Core()->current_language != \MyHomeCore\My_Home_Core()->default_language ) ) {
			Term_Factory::$offer_types = array();
			do_action( 'wpml_switch_language', \MyHomeCore\My_Home_Core()->default_language );
			$offer_types = Term_Factory::get_offer_types();
			do_action( 'wpml_switch_language', \MyHomeCore\My_Home_Core()->current_language );
		} else {
			$offer_types = Term_Factory::get_offer_types();
		}
		$price_fields   = array();
		$is_price_range = Price_Attribute::is_range();

		foreach ( $currencies as $currency ) {
			$currency_key = $currency->get_key();

			if ( $is_price_range ) {
				$price_keys = array( $currency_key . '_from', $currency_key . '_to' );
			} else {
				$price_keys = array( $currency_key );
			}

			$price_keys_count = count( $price_keys );

			foreach ( $price_keys as $key => $price_key ) {
				$label = $attribute->get_name();
				if ( $price_keys_count == 2 && $key == 0 ) {
					$label .= ' ' . esc_html__( 'from', 'myhome-core' );
				} elseif ( $price_keys_count == 2 && $key == 1 ) {
					$label .= ' ' . esc_html__( 'to', 'myhome-core' );
				}

				$price_fields[] = array(
					'name'         => $label . ' (' . $currency->get_sign() . ')',
					'base_slug'    => $attribute->get_base_slug(),
					'slug'         => $price_key,
					'label'        => '',
					'required'     => false,
					'width'        => '1',
					'instructions' => '',
					'multiple'     => false,
					'type'         => Panel_Field::TYPE_NUMBER,
					'id'           => $attribute->get_ID(),
					'currency_key' => $currency_key
				);
			}

			foreach ( $offer_types as $offer_type ) {
				if ( ! $offer_type->specify_price() ) {
					continue;
				}

				if ( $offer_type->is_price_range() ) {
					$price_keys = array( $currency_key . '_from', $currency_key . '_to' );
				} else {
					$price_keys = array( $currency_key );
				}

				$price_keys_count = count( $price_keys );

				foreach ( $price_keys as $key => $price_key ) {
					$label = $attribute->get_name();
					if ( $price_keys_count == 2 && $key == 0 ) {
						$label .= ' ' . esc_html__( 'from', 'myhome-core' );
					} elseif ( $price_keys_count == 2 && $key == 1 ) {
						$label .= ' ' . esc_html__( 'to', 'myhome-core' );
					}

					$field_key = $price_key . '_offer_' . $offer_type->get_ID();

					$price_fields[] = array(
						'name'         => $label . ' - ' . $offer_type->get_name() . ' (' . $currency->get_sign() . ')',
						'base_slug'    => $attribute->get_base_slug(),
						'slug'         => $field_key,
						'label'        => '',
						'required'     => false,
						'width'        => '1',
						'instructions' => '',
						'multiple'     => false,
						'type'         => Panel_Field::TYPE_NUMBER,
						'id'           => $attribute->get_ID(),
						'currency_key' => $currency_key,
						'offer_type'   => $offer_type->get_ID()
					);
				}
			}
		}

		return $price_fields;
	}

	/**
	 * @return array
	 */
	public static function get() {
		$fields = array(
			array(
				'name'         => esc_html__( 'Title', 'myhome-core' ),
				'slug'         => 'title',
				'label'        => '',
				'required'     => false,
				'width'        => '1',
				'instructions' => '',
				'multiple'     => false,
				'type'         => Panel_Field::TYPE_TITLE
			),
			array(
				'name'         => esc_html__( 'Description', 'myhome-core' ),
				'slug'         => 'description',
				'label'        => '',
				'required'     => false,
				'width'        => '1',
				'instructions' => '',
				'multiple'     => false,
				'type'         => Panel_Field::TYPE_DESCRIPTION
			),
			array(
				'name'         => esc_html__( 'Gallery', 'myhome-core' ),
				'slug'         => 'gallery',
				'label'        => '',
				'required'     => false,
				'width'        => '1',
				'instructions' => '',
				'multiple'     => false,
				'type'         => Panel_Field::TYPE_GALLERY
			),
			array(
				'name'         => esc_html__( 'Featured image', 'myhome-core' ),
				'slug'         => 'image',
				'label'        => '',
				'required'     => false,
				'width'        => '1',
				'instructions' => '',
				'multiple'     => false,
				'type'         => Panel_Field::TYPE_IMAGE
			),
			array(
				'name'         => esc_html__( 'Location', 'myhome-core' ),
				'slug'         => 'location',
				'label'        => '',
				'required'     => false,
				'width'        => '1',
				'instructions' => '',
				'multiple'     => false,
				'type'         => Panel_Field::TYPE_LOCATION
			),
			array(
				'name'         => esc_html__( 'Featured', 'myhome-core' ),
				'slug'         => 'is_featured',
				'label'        => '',
				'required'     => false,
				'width'        => '1',
				'instructions' => '',
				'multiple'     => false,
				'type'         => Panel_field::TYPE_FEATURED
			)
		);

		foreach ( Attribute_Factory::get_text() as $attribute ) {
			$fields[] = array(
				'name'             => $attribute->get_name(),
				'base_slug'        => $attribute->get_base_slug(),
				'slug'             => $attribute->get_slug(),
				'label'            => '',
				'required'         => false,
				'width'            => '2',
				'instructions'     => '',
				'multiple'         => false,
				'allow_new_values' => false,
				'autocomplete'     => false,
				'type'             => Panel_Field::TYPE_TEXT,
				'id'               => $attribute->get_ID(),
				'is_breadcrumbs'   => $attribute->is_in_breadcrumbs()
			);
		}

		foreach ( Attribute_Factory::get_number() as $attribute ) {
			if ( $attribute instanceof Price_Attribute ) {
				$fields = array_merge( $fields, self::get_price_fields( $attribute ) );
				continue;
			}

			$fields[] = array(
				'name'         => $attribute->get_name(),
				'base_slug'    => $attribute->get_base_slug(),
				'slug'         => $attribute->get_slug(),
				'label'        => '',
				'required'     => false,
				'width'        => '2',
				'instructions' => '',
				'multiple'     => false,
				'type'         => Panel_Field::TYPE_NUMBER,
				'id'           => $attribute->get_ID()
			);
		}

		foreach ( Attribute_Factory::get_text_areas() as $attribute ) {
			$fields[] = array(
				'name'         => $attribute->get_name(),
				'slug'         => $attribute->get_slug(),
				'label'        => '',
				'required'     => false,
				'width'        => '2',
				'instructions' => '',
				'multiple'     => false,
				'type'         => Panel_Field::TYPE_TEXT_AREA,
				'id'           => $attribute->get_ID(),
				'dependencies' => $attribute->get_property_type_dependency()
			);
		}

		$elements = array(
			'mh-estate_video'               => (object) array(
				'label'   => esc_html__( 'Video', 'myhome-core' ),
				'slug'    => Estate_Element::VIDEO,
				'type'    => Estate_Element::VIDEO,
				'default' => true
			),
			'mh-estate_virtual_tour'        => (object) array(
				'label'   => esc_html__( 'Virtual tour', 'myhome-core' ),
				'slug'    => Estate_Element::VIRTUAL_TOUR,
				'type'    => Estate_Element::VIRTUAL_TOUR,
				'default' => false
			),
			'mh-estate_plans'               => (object) array(
				'label'   => esc_html__( 'Plans', 'myhome-core' ),
				'slug'    => Estate_Element::PLANS,
				'type'    => Estate_Element::PLANS,
				'default' => true
			),
			'mh-estate_attachments'         => (object) array(
				'label'   => esc_html__( 'Attachments', 'myhome-core' ),
				'slug'    => Estate_Element::ATTACHMENTS,
				'type'    => Estate_Element::ATTACHMENTS,
				'default' => false
			),
			'mh-estate_additional-features' => (object) array(
				'label'   => esc_html__( 'Additional features', 'myhome-core' ),
				'slug'    => Estate_Element::ADDITIONAL_FEATURES,
				'type'    => Estate_Element::ADDITIONAL_FEATURES,
				'default' => true
			)
		);

		foreach ( $elements as $el_key => $el ) {
			if ( ! isset( \MyHomeCore\My_Home_Core()->settings->props[ $el_key ] ) && ! $el->default ) {
				$delete = true;
			} elseif ( ! isset( \MyHomeCore\My_Home_Core()->settings->props[ $el_key ] ) && $el->default ) {
				$delete = false;
			} elseif ( empty( \MyHomeCore\My_Home_Core()->settings->props[ $el_key ] ) ) {
				$delete = true;
			} else {
				$delete = false;
			}

			if ( $delete ) {
				unset( $elements[ $el_key ] );
			}
		}

		foreach ( $elements as $element ) {
			$fields[] = array(
				'name'         => $element->label,
				'slug'         => $element->slug,
				'label'        => '',
				'required'     => false,
				'width'        => '1',
				'instructions' => '',
				'multiple'     => false,
				'type'         => $element->type,
				'id'           => '',
				'dependencies' => array()
			);
		}

		//		print_r($fields);

		return $fields;
	}

	/**
	 * @param array $panel_fields
	 *
	 * @return array
	 */
	public static function get_selected( $panel_fields = array() ) {
		$fields = array();
		foreach ( self::get() as $field ) {
			$fields[] = $field['slug'];
		}

		$selected_filtered = array();
		if ( empty( $panel_fields ) ) {
			$selected = get_option( self::OPTIONS_KEY, self::get() );
		} else {
			$selected = $panel_fields;
		}

		foreach ( $selected as $key => $field ) {
			if ( ! in_array( $field['slug'], $fields ) ) {
				continue;
			}

			$field['required'] = filter_var( $field['required'], FILTER_VALIDATE_BOOLEAN );
			$field['multiple'] = filter_var( $field['multiple'], FILTER_VALIDATE_BOOLEAN );

			if ( isset( $field['autocomplete'] ) ) {
				$field['autocomplete'] = filter_var( $field['autocomplete'], FILTER_VALIDATE_BOOLEAN );
			} else {
				$field['autocomplete'] = false;
			}

			if ( isset( $field['is_location_part'] ) ) {
				$field['is_location_part'] = filter_var( $field['is_location_part'], FILTER_VALIDATE_BOOLEAN );
			} else {
				$field['is_location_part'] = false;
			}

			if ( isset( $field['allow_new_values'] ) ) {
				$field['allow_new_values'] = filter_var( $field['allow_new_values'], FILTER_VALIDATE_BOOLEAN );
			} else {
				$field['allow_new_values'] = false;
			}

			if ( isset( $field['instructions'] ) ) {
				$field['instructions'] = apply_filters( 'wpml_translate_single_string', $field['instructions'], 'myhome-core', 'Submit property (instructions) - ' . $field['instructions'] );
			}

			if ( ! empty( $field['id'] ) ) {

				if ( false === ( $attribute = Attribute::get_by_id( $field['id'] ) ) ) {
					continue;
				}

//                if ( !$attribute instanceof Price_Attribute ) {
				$field['name'] = $attribute->get_name();
//                }

				if ( $attribute instanceof Text_Attribute ) {
					$order = Term_Factory::ORDER_DESC;
					if ( isset( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-panel-order_by'] ) && ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-agent-panel-order_by'] ) ) {
						$order_by = \MyHomeCore\My_Home_Core()->settings->props['mh-agent-panel-order_by'];
						if ( $order_by == Term_Factory::ORDER_BY_NAME ) {
							$order = Term_Factory::ORDER_ASC;
						}
					} else {
						$order_by = Term_Factory::ORDER_BY_COUNT;
					}

					$field['parent_id']   = $attribute->get_parent_id();
					$field['parent_type'] = $attribute->get_parent_type();
					$field['values']      = array_map(
						function ( $attribute_values ) {
							/* @var Attribute_Values $attribute_values */
							return $attribute_values->get_data();
						}, $attribute->get_values(
						$attribute->get_default_values() == Attribute_Options_Page::STATIC_VALUES ? empty( My_Home_Core()->settings->get( 'frontend-use-static-values' ) ) : true,
						$order_by,
						$order
					)
					);

					if ( $attribute->is_in_breadcrumbs() ) {
						$field['is_breadcrumbs'] = true;
						$field['required']       = true;
						$field['multiple']       = false;
					}

					$field['tags'] = $attribute->get_tags();
				}

				if ( $attribute instanceof Price_Attribute ) {
					if ( ! empty( $field['currency_key'] ) ) {
						$currency_key = $field['currency_key'];
					} else {
						$currency_key = str_replace( array( '_to', '_from' ), '', $field['slug'] );
					}
					$currency      = Currencies::get_by_key( $currency_key );
					$currency_sign = $currency != false ? $currency->get_sign() : '';

					$label = '';

					if ( ! empty( $field['offer_type'] ) ) {
						if ( function_exists( 'icl_object_id' ) ) {
							do_action( 'wpml_switch_language', \MyHomeCore\My_Home_Core()->default_language );
							$offer_type = Term::get_term( $field['offer_type'] );
							do_action( 'wpml_switch_language', \MyHomeCore\My_Home_Core()->current_language );
						} else {
							$offer_type = Term::get_term( $field['offer_type'] );
						}

						$label = ! empty( $offer_type ) ? '(' . $offer_type->get_name() . ')' : '';
					}

					if ( strpos( $field['slug'], '_from' ) !== false ) {
						$label .= ' ' . esc_html__( 'from', 'myhome-core' );
					} elseif ( strpos( $field['slug'], '_to' ) !== false ) {
						$label .= ' ' . esc_html__( 'to', 'myhome-core' );
					}

					$field['display_before'] = trim( $label );
//					$field['display_after']  = '';
					$field['display_after'] = $currency_sign;
				} else if ( $attribute instanceof Number_Attribute ) {
					$field['display_after'] = $attribute->get_display_after();
				}
				$field['dependencies'] = $attribute->get_property_type_dependency();

				if ( empty( $field['control'] ) ) {
					$field['control'] = 'select';
				}
			} else {
				if ( $field['slug'] == Estate_Element::VIDEO ) {
					$field['name'] = esc_html__( 'Video', 'myhome-core' );
				} elseif ( $field['slug'] == Estate_Element::ATTACHMENTS ) {
					$field['name'] = esc_html__( 'Attachments', 'myhome-core' );
				} elseif ( $field['slug'] == Estate_Element::PLANS ) {
					$field['name'] = esc_html__( 'Plans', 'myhome-core' );
				} elseif ( $field['slug'] == Estate_Element::ADDITIONAL_FEATURES ) {
					$field['name'] = esc_html__( 'Additional features', 'myhome-core' );
				} elseif ( $field['slug'] == Estate_Element::GALLERY ) {
					$field['name'] = esc_html__( 'Gallery', 'myhome-core' );
				} elseif ( $field['slug'] == Estate_Element::VIRTUAL_TOUR ) {
					$field['name'] = esc_html__( 'Virtual tour', 'myhome-core' );
				} elseif ( $field['slug'] == Panel_Field::TYPE_LOCATION ) {
					$field['name'] = esc_html__( 'Location', 'myhome-core' );
				} elseif ( $field['slug'] == Panel_Field::TYPE_IMAGE ) {
					$field['name'] = esc_html__( 'Location', 'myhome-core' );
				} elseif ( $field['slug'] == Panel_Field::TYPE_DESCRIPTION ) {
					$field['name'] = esc_html__( 'Description', 'myhome-core' );
				} elseif ( $field['slug'] == Panel_Field::TYPE_TITLE ) {
					$field['name'] = esc_html__( 'Title', 'myhome-core' );
				} elseif ( $field['slug'] == 'is_featured' ) {
					$field['name'] = esc_html__( 'Featured', 'myhome-core' );
				}
			}

			$selected_filtered[] = $field;
		}

		if ( empty( $panel_fields ) ) {
			if ( count( $selected_filtered ) != count( $selected ) ) {
				update_option( self::OPTIONS_KEY, $selected_filtered );
			}
		}

		return $selected_filtered;
	}

	/**
	 * @param array $fields
	 *
	 * @return array
	 */
	public static function get_selected_backend( $fields = array() ) {
		if ( ! empty( $fields ) ) {
			$selected_fields = $fields;
		} else {
			$selected_fields = self::get_selected();
		}
		foreach ( $selected_fields as $key => $field ) {
			if ( ! empty( $field['id'] ) ) {

				if ( false === ( $attribute = Attribute::get_by_id( $field['id'] ) ) ) {
					continue;
				}

				if ( $attribute instanceof Text_Attribute ) {
					if ( $attribute->is_in_breadcrumbs() ) {
						$selected_fields[ $key ]['is_breadcrumbs'] = true;
						$selected_fields[ $key ]['required']       = true;
						$selected_fields[ $key ]['multiple']       = false;
					} else {
						$selected_fields[ $key ]['is_breadcrumbs'] = false;
					}
				}
			}

			if ( isset( $selected_fields[ $key ]['values'] ) ) {
				unset( $selected_fields[ $key ]['values'] );
			}

			if ( isset( $selected_fields[ $key ]['dependencies'] ) ) {
				unset( $selected_fields[ $key ]['dependencies'] );
			}

			if ( isset( $selected_fields[ $key ]['required'] ) ) {
				$selected_fields[ $key ]['required'] = filter_var( $selected_fields[ $key ]['required'], FILTER_VALIDATE_BOOLEAN );
			}

			if ( isset( $selected_fields[ $key ]['multiple'] ) ) {
				$selected_fields[ $key ]['multiple'] = filter_var( $selected_fields[ $key ]['multiple'], FILTER_VALIDATE_BOOLEAN );
			}

			if ( isset( $selected_fields[ $key ]['allow_new_values'] ) ) {
				$selected_fields[ $key ]['allow_new_values'] = filter_var( $selected_fields[ $key ]['allow_new_values'], FILTER_VALIDATE_BOOLEAN );
			}

			if ( isset( $selected_fields[ $key ]['autocomplete'] ) ) {
				$selected_fields[ $key ]['autocomplete'] = filter_var( $selected_fields[ $key ]['autocomplete'], FILTER_VALIDATE_BOOLEAN );
			}

			if ( isset( $selected_fields[ $key ]['is_location_part'] ) ) {
				$selected_fields[ $key ]['is_location_part'] = filter_var( $selected_fields[ $key ]['is_location_part'], FILTER_VALIDATE_BOOLEAN );
			}

			if ( empty( $field['id'] ) ) {
				if ( $field['slug'] == Estate_Element::VIDEO ) {
					$field['name'] = esc_html__( 'Video', 'myhome-core' );
				} elseif ( $field['slug'] == Estate_Element::ATTACHMENTS ) {
					$field['name'] = esc_html__( 'Attachments', 'myhome-core' );
				} elseif ( $field['slug'] == Estate_Element::PLANS ) {
					$field['name'] = esc_html__( 'Plans', 'myhome-core' );
				} elseif ( $field['slug'] == Estate_Element::ADDITIONAL_FEATURES ) {
					$field['name'] = esc_html__( 'Additional features', 'myhome-core' );
				} elseif ( $field['slug'] == Estate_Element::GALLERY ) {
					$field['name'] = esc_html__( 'Gallery', 'myhome-core' );
				} elseif ( $field['slug'] == Estate_Element::VIRTUAL_TOUR ) {
					$field['name'] = esc_html__( 'Virtual tour', 'myhome-core' );
				} elseif ( $field['slug'] == Panel_Field::TYPE_LOCATION ) {
					$field['name'] = esc_html__( 'Location', 'myhome-core' );
				} elseif ( $field['slug'] == Panel_Field::TYPE_IMAGE ) {
					$field['name'] = esc_html__( 'Location', 'myhome-core' );
				} elseif ( $field['slug'] == Panel_Field::TYPE_DESCRIPTION ) {
					$field['name'] = esc_html__( 'Description', 'myhome-core' );
				} elseif ( $field['slug'] == Panel_Field::TYPE_TITLE ) {
					$field['name'] = esc_html__( 'Title', 'myhome-core' );
				} elseif ( $field['slug'] == 'is_featured' ) {
					$field['name'] = esc_html__( 'Featured', 'myhome-core' );
				}
			} else {
				if ( false === ( $attribute = Attribute::get_by_id( $field['id'] ) ) ) {
					continue;
				}

				$field['name'] = $attribute->get_name();
			}
		}

		return $selected_fields;
	}

	/**
	 * @return array
	 */
	public static function get_current() {
		$fields = array();
		foreach ( self::get_selected() as $field ) {
			$field['name']         = apply_filters( 'wpml_translate_single_string', $field['name'], 'myhome-core', 'Submit property field (label) - ' . $field['name'] );
			$field['instructions'] = apply_filters( 'wpml_translate_single_string', $field['instructions'], 'myhome-core', 'Submit property (instructions) - ' . $field['instructions'] );

			if ( $field['type'] == Panel_Field::TYPE_LOCATION ) {
				$field['position'] = array(
					'lat' => floatval( \MyHomeCore\My_Home_Core()->settings->get( 'map-center_lat' ) ),
					'lng' => floatval( \MyHomeCore\My_Home_Core()->settings->get( 'map-center_lng' ) )
				);
			}

			$fields[] = $field;
		}

		return $fields;
	}

	/**
	 * @param array|\bool $selected_fields
	 *
	 * @return array
	 */
	public static function get_available( $selected_fields = false ) {
		$available_fields          = array();
		$currently_selected_fields = array();

		if ( $selected_fields === false ) {
			$selected_fields = self::get_selected();
		}

		foreach ( $selected_fields as $field ) {
			$currently_selected_fields[] = $field['slug'];
		}

		foreach ( self::get() as $field ) {
			if ( ! in_array( $field['slug'], $currently_selected_fields ) ) {
				$field['required'] = filter_var( $field['required'], FILTER_VALIDATE_BOOLEAN );
				$field['multiple'] = filter_var( $field['multiple'], FILTER_VALIDATE_BOOLEAN );
				if ( isset( $field['is_breadcrumbs'] ) ) {
					$field['is_breadcrumbs'] = filter_var( $field['is_breadcrumbs'], FILTER_VALIDATE_BOOLEAN );
				}
				$available_fields[] = $field;
			}
		}

		return $available_fields;
	}

}