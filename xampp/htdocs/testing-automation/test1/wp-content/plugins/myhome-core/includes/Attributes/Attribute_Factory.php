<?php

namespace MyHomeCore\Attributes;


/**
 * Class Attribute_Factory
 * @package MyHomeCore\Attributes
 */
class Attribute_Factory {

	/**
	 * @var Attribute[]
	 */
	private static $attributes = array();

	/**
	 * @var array
	 */
	private static $basic_attributes = array();

	/**
	 * @return Attribute[]
	 */
	public static function get() {
		if ( ! empty( self::$attributes ) ) {
			return self::$attributes;
		}

		$cache_key = 'myhome_cache_attributes';
		if ( ! empty( \MyHomeCore\My_Home_Core()->current_language ) ) {
			$cache_key .= \MyHomeCore\My_Home_Core()->current_language;
		}

		if ( ! \MyHomeCore\My_Home_Core()->development_mode && false !== ( self::$attributes = get_transient( $cache_key ) ) ) {
			return self::$attributes;
		}

		foreach ( self::get_basic() as $attribute ) {
			$attribute = Attribute::get_instance( $attribute );

			if ( $attribute instanceof Price_Attribute && ! Price_Attribute_Options_Page::show_price() ) {
				continue;
			}

			self::$attributes[] = $attribute;
		}

		if ( empty( self::$attributes ) ) {
			Attributes_Settings::create_table( false );
		}

		if ( ! \MyHomeCore\My_Home_Core()->development_mode ) {
			set_transient( $cache_key, self::$attributes );
		}

		return self::$attributes;
	}

	/**
	 * @return array
	 */
	public static function get_basic() {
		if ( ! empty( self::$basic_attributes ) ) {
			return self::$basic_attributes;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'myhome_attributes';
		$results    = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY form_order, ID" );

		if ( is_array( $results ) ) {
			self::$basic_attributes = $results;
		}

		return self::$basic_attributes;
	}

	/**
	 * @return string
	 */
	public static function get_all_unfiltered_data() {
		global $wpdb;
		$table_name      = $wpdb->prefix . 'myhome_attributes';
		$attributes      = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY form_order, ID" );
		$attributes_data = array();

		$id_attribute_found = false;

		foreach ( $attributes as $attr ) {
			$attribute = Attribute::get_instance( $attr );

			if ( $attribute instanceof Property_ID_Attribute ) {
				$id_attribute_found = true;
			}

			$attributes_data[] = $attribute->get_data();
		}

		if ( ! $id_attribute_found ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'myhome_attributes';
			$form_order = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

			$wpdb->insert(
				$table_name, array(
					'attribute_name' => esc_html__( 'Property ID', 'myhome-core' ),
					'attribute_slug' => 'property-id',
					'attribute_type' => 'core',
					'form_order'     => $form_order,
					'base'           => 2,
					'base_slug'      => 'property_id'
				)
			);

		}

		return json_encode( $attributes_data );
	}

	/**
	 * @return Attribute[]
	 */
	public static function get_unfiltered() {
		global $wpdb;
		$table_name      = $wpdb->prefix . 'myhome_attributes';
		$attributes_data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY form_order, ID" );
		$attributes      = array();

		foreach ( $attributes_data as $attribute_data ) {
			$attributes[] = Attribute::get_instance( $attribute_data );

		}

		return $attributes;
	}

	/**
	 * @return Text_Attribute[]|Number_Attribute[]
	 */
	public static function get_search() {
		$attributes = array();
		foreach ( self::get() as $attribute ) {
			if ( $attribute instanceof Text_Attribute || $attribute instanceof Number_Attribute
			     || $attribute instanceof Keyword_Attribute || $attribute instanceof Property_ID_Attribute
			) {
				$attributes[] = $attribute;
			}
		}

		return $attributes;
	}

	/**
	 * @return Text_Attribute[]
	 */
	public static function get_text() {
		$attributes = array();
		foreach ( self::get() as $attribute ) {
			if ( $attribute instanceof Text_Attribute ) {
				$attributes[] = $attribute;
			}
		}

		return $attributes;
	}

	/**
	 * @return Number_Attribute[]
	 */
	public static function get_number() {
		$attributes = array();
		foreach ( self::get() as $attribute ) {
			if ( $attribute instanceof Number_Attribute ) {
				$attributes[] = $attribute;
			}
		}

		return $attributes;
	}

	/**
	 * @return Attribute[]
	 */
	public static function get_tags() {
		$attributes = array();
		foreach ( self::get() as $attribute ) {
			if ( $attribute->get_tags() ) {
				$attributes[] = $attribute;
			}
		}

		return $attributes;
	}

	/**
	 * @return Attribute[]
	 */
	public static function get_text_not_tags() {
		$attributes = array();
		foreach ( self::get_text() as $attribute ) {
			if ( ! $attribute->get_tags() ) {
				$attributes[] = $attribute;
			}
		}

		return $attributes;
	}

	/**
	 * @return Attribute[]
	 */
	public static function get_not_core() {
		$attributes = array();
		foreach ( self::get() as $attribute ) {
			if ( $attribute instanceof Core_Attribute ) {
				continue;
			}
			$attributes[] = $attribute;
		}

		return $attributes;
	}

	/**
	 * @return bool|Property_Type_Attribute
	 */
	public static function get_property_type() {
		foreach ( self::get() as $attribute ) {
			if ( $attribute instanceof Property_Type_Attribute ) {
				return $attribute;
			}
		}

		return false;
	}

	/**
	 * @return bool|Price_Attribute
	 */
	public static function get_price() {
		foreach ( self::get() as $attribute ) {
			if ( $attribute instanceof Price_Attribute ) {
				return $attribute;
			}
		}

		return false;
	}

	/**
	 * @return Offer_Type_Attribute|false
	 */
	public static function get_offer_type() {
		foreach ( self::get() as $attribute ) {
			if ( $attribute instanceof Offer_Type_Attribute ) {
				return $attribute;
			}
		}

		return false;
	}

	/**
	 * @return string
	 */
	public static function get_data() {
		$data = array();
		foreach ( self::get() as $attribute ) {
			$data[] = $attribute->get_data();
		}

		return json_encode( $data );
	}

	/**
	 * @param $attribute_id
	 *
	 * @return bool|Attribute
	 */
	public static function get_by_ID( $attribute_id ) {
		$attribute_id = intval( $attribute_id );

		foreach ( self::get() as $attribute ) {
			if ( $attribute->get_ID() == $attribute_id ) {
				return $attribute;
			}
		}

		return false;
	}

	/**
	 * @param string $attribute_slug
	 *
	 * @return bool|Attribute
	 */
	public static function get_by_slug( $attribute_slug ) {
		foreach ( self::get() as $attribute ) {
			if ( $attribute->get_slug() == $attribute_slug ) {
				return $attribute;
			}
		}

		return false;
	}

	/**
	 * @return Text_Area_Attribute[]
	 */
	public static function get_text_areas() {
		$attributes = array();
		foreach ( self::get() as $attribute ) {
			if ( $attribute instanceof Text_Area_Attribute ) {
				$attributes[] = $attribute;
			}
		}

		return $attributes;
	}

	/**
	 * @return Widgets_Attribute[]
	 */
	public static function get_widgets() {
		$attributes = array();
		foreach ( self::get() as $attribute ) {
			if ( $attribute instanceof Widgets_Attribute ) {
				$attributes[] = $attribute;
			}
		}

		return $attributes;
	}

	/**
	 * @return Attribute[]
	 */
	public static function get_property_elements() {
		$elements = array();
		foreach ( self::get_text_areas() as $attribute ) {
			$elements[] = $attribute;
		}
		foreach ( self::get_widgets() as $attribute ) {
			$elements[] = $attribute;
		}

		return $elements;
	}

}