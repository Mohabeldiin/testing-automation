<?php

namespace MyHomeCore\Attributes;


use MyHomeCore\Components\Listing\Form\Field;
use MyHomeCore\Estates\Estate;
use MyHomeCore\Estates\Filters\Estate_Number_Filter;

/**
 * Class Number_Attribute
 * @package MyHomeCore\Attributes
 */
class Number_Attribute extends Attribute {

	/**
	 * Number_Attribute constructor.
	 *
	 * @param $attribute
	 */
	public function __construct( $attribute ) {
		parent::__construct( $attribute );

		$this->set_number_attribute_data();
	}

	public function set_number_attribute_data() {
		$data = array(
			'bool'   => array(
				'suggestions'   => false,
				'checkbox_move' => true
			),
			'string' => array(
				'number_operator' => 'equal'
			)
		);

		foreach ( $data['bool'] as $key => $value ) {
			if ( isset( $this->options[ 'options_' . $this->get_slug() . '_' . $key ] ) ) {
				$this->attribute_data[ $key ] = ! empty( $this->options[ 'options_' . $this->get_slug() . '_' . $key ] );
			} else {
				$this->attribute_data[ $key ] = $value;
			}
		}

		foreach ( $data['string'] as $key => $value ) {
			if ( isset( $this->options[ 'options_' . $this->get_slug() . '_' . $key ] ) ) {
				$this->attribute_data[ $key ] = $this->options[ 'options_' . $this->get_slug() . '_' . $key ];
			} else {
				$this->attribute_data[ $key ] = $value;
			}
		}
	}

	/**
	 * @return \bool
	 */
	public function get_checkbox_move() {
		return $this->attribute_data['checkbox_move'];
	}

	/**
	 * @return bool
	 */
	public function has_archive() {
		return false;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return self::NUMBER;
	}

	/**
	 * @return string
	 */
	public function get_type_name() {
		return esc_html__( 'Number field', 'myhome-core' );
	}

	/**
	 * @return Number_Attribute_Options_Page
	 */
	public function get_options_page() {
		return new Number_Attribute_Options_Page( $this );
	}

	/**
	 * @param Estate $estate
	 *
	 * @return Attribute_Values
	 */
	public function get_estate_values( $estate ) {
		$meta = $estate->get_meta( 'estate_attr_' . $this->get_slug() );

		if ( empty( $meta ) || empty( $meta[0] ) ) {
			$values = array();
		} else {
			if ( ( $display_after = $this->get_display_after() ) !== '' ) {
				$name = $meta[0] . ' ' . $display_after;
			} else {
				$name = $meta[0];
			}

			$values = array( new Attribute_Value( $name, $meta[0], '', $this->get_slug() ) );
		}

		return new Attribute_Values( $values );
	}

	/**
	 * @return bool
	 */
	public function is_estate_attribute() {
		return true;
	}

	/**
	 * @param bool $all_values
	 *
	 * @return Attribute_Values[]
	 */
	public function get_values( $all_values = false ) {
		$form_control = $this->get_search_form_control();

		if ( ( $form_control == Field::TEXT || $form_control == Field::TEXT_RANGE ) && ! $this->get_suggestions() ) {
			return array( 'any' => new Attribute_Values() );
		}

		$values        = array();
		$static_values = $this->get_static_values();
		foreach ( $static_values as $static_value ) {
			$values[] = new Attribute_Value( $static_value->name, $static_value->name, '', $static_value->value );
		}

		return array( 'any' => new Attribute_Values( $values ) );
	}

	/**
	 * @return bool
	 */
	public function get_suggestions() {
		return $this->attribute_data['suggestions'];
	}

	/**
	 * @return string
	 */
	public function get_number_operator() {
		return $this->attribute_data['number_operator'];
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
			'icon'                => $this->get_icon(),
			'icon_class'          => $this->get_icon_class(),
			'checkbox_move'       => $this->get_checkbox_move()
		);
	}

	/**
	 * @param array $attribute_data
	 */
	public function update_options( $attribute_data ) {
		$options_page = $this->get_options_page();
		$options_page->update_options( $attribute_data );
	}

	/**
	 * @param Attribute_Values $attribute_values
	 * @param string           $compare
	 *
	 * @return Estate_Number_Filter
	 */
	public function get_estate_filter( Attribute_Values $attribute_values, $compare = '=' ) {
		return new Estate_Number_Filter( $this, $attribute_values, $compare );
	}

	/**
	 * @param array $fields
	 *
	 * @return array
	 */
	public function get_vc_control( $fields ) {
		$form_control = $this->get_search_form_control();
		if ( $form_control == Field::SELECT_RANGE || $form_control == Field::TEXT_RANGE ) {
			$fields[] = array(
				'type'        => 'textfield',
				'heading'     => sprintf( esc_html__( '%s from', 'myhome-core' ), $this->get_name() ),
				'param_name'  => $this->get_slug() . '_from',
				'group'       => esc_html__( 'Default values', 'myhome-core' ),
				'save_always' => true
			);
			$fields[] = array(
				'type'        => 'textfield',
				'heading'     => sprintf( esc_html__( '%s to', 'myhome-core' ), $this->get_name() ),
				'param_name'  => $this->get_slug() . '_to',
				'group'       => esc_html__( 'Default values', 'myhome-core' ),
				'save_always' => true
			);
		} else {
			$fields[] = array(
				'type'        => 'textfield',
				'heading'     => $this->get_name(),
				'param_name'  => $this->get_slug(),
				'group'       => esc_html__( 'Default values', 'myhome-core' ),
				'save_always' => true
			);
		}

		return $fields;
	}

	/**
	 * @param int   $estate_id
	 * @param array $values
	 */
	public function update_estate_values( $estate_id, $values ) {
		$value = count( $values ) > 0 ? $values[0] : '';

		update_post_meta( $estate_id, 'estate_attr_' . $this->get_slug(), $value );
	}

	protected function set_defaults() {
		update_field( $this->get_slug() . '_search_form_control', Field::TEXT_RANGE, 'option' );
	}

}