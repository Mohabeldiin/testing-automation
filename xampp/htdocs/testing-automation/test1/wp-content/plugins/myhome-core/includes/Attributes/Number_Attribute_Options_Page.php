<?php

namespace MyHomeCore\Attributes;


use MyHomeCore\Components\Listing\Form\Field;

/**
 * Class Number_Attribute_Options_Page
 * @package MyHomeCore\Attributes
 */
class Number_Attribute_Options_Page extends Attribute_Options_Page {

	/**
	 * Number_Attribute_Option_Page constructor.
	 *
	 * @param Attribute $attribute
	 */
	public function __construct( Attribute $attribute ) {
		parent::__construct( $attribute );

		$basic_fields       = array(
			'search_form_control' => array(
				'key'     => 'myhome_' . $this->attribute->get_slug() . '_search_form_control',
				'label'   => esc_html__( 'Search form - display as:', 'myhome-core' ),
				'name'    => $this->attribute->get_slug() . '_search_form_control',
				'type'    => 'select',
				'choices' => array(
					Field::TEXT         => esc_html__( 'Input field', 'myhome-core' ),
					Field::TEXT_RANGE   => esc_html__( 'Input field based on a range ( from - to )', 'myhome-core' ),
					Field::SELECT       => esc_html__( 'Drop-down list ', 'myhome-core' ),
					Field::SELECT_RANGE => esc_html__( 'Drop-down list based on a range ( from - to )', 'myhome-core' ),
				)
			),
			'number_operator'     => array(
				'key'   => 'myhome_' . $this->attribute->get_slug() . '_number_operator',
				'label' => esc_html__( 'Number operator', 'myhome-core' ),
				'name'  => $this->attribute->get_slug() . '_number_operator',
				'type'  => 'text'
			),
			'status_values'       => array(
				'key'          => 'myhome_' . $this->attribute->get_slug() . '_static_values',
				'label'        => esc_html__( 'Static values', 'myhome-core' ),
				'name'         => $this->attribute->get_slug() . '_static_values',
				'type'         => 'repeater',
				'button_label' => esc_html__( 'Add', 'myhome-core' ),
				'sub_fields'   => array(
					array(
						'key'   => 'myhome_' . $this->attribute->get_slug() . '_static_values_name',
						'label' => esc_html__( 'Name (visible)', 'myhome-core' ),
						'name'  => 'name',
						'type'  => 'text',
					),
					array(
						'key'   => 'myhome_' . $this->attribute->get_slug() . '_static_values_value',
						'label' => esc_html__( 'Value', 'myhome-core' ),
						'name'  => 'value',
						'type'  => 'text',
					),
				)
			),
			'show_card'           => array(
				'key'   => 'myhome_' . $this->attribute->get_slug() . '_show_card',
				'label' => esc_html__( 'Display on property card', 'myhome-core' ),
				'name'  => $this->attribute->get_slug() . '_show_card',
				'type'  => 'true_false'
			),
			'show_property'       => array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_show_property',
				'label'         => esc_html__( 'Display on single property page', 'myhome-core' ),
				'name'          => $this->attribute->get_slug() . '_show_property',
				'type'          => 'true_false',
				'default_value' => true
			),
			'suggestion'          => array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_suggestions',
				'label'         => esc_html__( 'Show suggestion', 'myhome-core' ),
				'name'          => $this->attribute->get_slug() . '_suggestions',
				'type'          => 'true_false',
				'default_value' => false
			),
			'icon'                => array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_icon',
				'label'         => esc_html__( 'Icon', 'myhome-core' ),
				'name'          => $this->attribute->get_slug() . '_icon',
				'type'          => 'text',
				'default_value' => ''
			),
			'checkbox_move'       => array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_checkbox_move',
				'label'         => esc_html__( 'Checkbox - move checked elements to the beginning of the list', 'myhome-core' ),
				'name'          => $this->attribute->get_slug() . '_checkbox_move',
				'type'          => 'true_false',
				'default_value' => true
			)
		);
		$this->basic_fields = array_merge( $this->basic_fields, $basic_fields );

		$advanced_fields       = array(
			'display_after'    => array(
				'key'          => 'myhome_' . $this->attribute->get_slug() . '_display_after',
				'label'        => esc_html__( 'Unit of measure', 'myhome-core' ),
				'name'         => $this->attribute->get_slug() . '_display_after',
				'instructions' => esc_html__( 'It will be displayed after name eg. you can use that to add (sq feet) next to lot size, but it will be not used in link so it is much more useful.', 'myhome-core' ),
				'type'         => 'text'
			),
			'placeholder'      => array(
				'key'          => 'myhome_' . $this->attribute->get_slug() . '_placeholder',
				'label'        => esc_html__( 'Placeholder', 'myhome-core' ),
				'name'         => $this->attribute->get_slug() . '_placeholder',
				'type'         => 'text',
				'instructions' => esc_html__( 'Placeholder\'s name is by default a name of a field, but it can be changed below', 'myhome-core' )
			),
			'placeholder_from' => array(
				'key'     => 'myhome_' . $this->attribute->get_slug() . '_placeholder_from',
				'label'   => esc_html__( 'Placeholder (default: from)', 'myhome-core' ),
				'name'    => $this->attribute->get_slug() . '_placeholder_from',
				'type'    => 'text',
				'wrapper' => array(
					'width' => '50%',
				)
			),
			'placeholder_to'   => array(
				'key'     => 'myhome_' . $this->attribute->get_slug() . '_placeholder_to',
				'label'   => esc_html__( 'Placeholder (default: to)', 'myhome-core' ),
				'name'    => $this->attribute->get_slug() . '_placeholder_to',
				'type'    => 'text',
				'wrapper' => array(
					'width' => '50%',
				)
			)
		);
		$this->advanced_fields = array_merge( $this->advanced_fields, $advanced_fields );
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
				'checkbox_move'       => 'checkbox_move'
			)
		);

		$this->save_options( $option_names, $attribute_data );
	}

}