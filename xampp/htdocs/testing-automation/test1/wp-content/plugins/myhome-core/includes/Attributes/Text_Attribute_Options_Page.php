<?php

namespace MyHomeCore\Attributes;


use MyHomeCore\Components\Listing\Form\Field;

/**
 * Class Text_Attribute_Options_Page
 * @package MyHomeCore\Attributes
 */
class Text_Attribute_Options_Page extends Attribute_Options_Page {

	public function __construct( Attribute $attribute ) {
		parent::__construct( $attribute );

		$basic_fields       = array(
			'search_form_control' => array(
				'key'     => 'myhome_' . $this->attribute->get_slug() . '_search_form_control',
				'label'   => esc_html__( 'Search form - display as:', 'myhome-core' ),
				'name'    => $this->attribute->get_slug() . '_search_form_control',
				'type'    => 'select',
				'choices' => array(
					Field::SELECT       => esc_html__( 'Dropdown list', 'myhome-core' ),
					Field::TEXT         => esc_html__( 'Input Text', 'myhome-core' ),
					Field::CHECKBOX     => esc_html__( 'Checkbox', 'myhome-core' ),
					Field::RADIO_BUTTON => esc_html__( 'Left/right: radio button || top/bottom - drop-down list', 'myhome-core' )
				)
			),
			'default_values'      => array(
				'key'     => 'myhome_' . $this->attribute->get_slug() . '_default_values',
				'label'   => esc_html__( 'Default values', 'myhome-core' ),
				'name'    => $this->attribute->get_slug() . '_default_values',
				'type'    => 'select',
				'choices' => array(
					Attribute_Options_Page::ALL_VALUES    => esc_html__( 'All existing values', 'myhome-core' ),
					Attribute_Options_Page::MOST_POPULAR  => esc_html__( 'Most popular', 'myhome-core' ),
					Attribute_Options_Page::STATIC_VALUES => esc_html__( 'Static values', 'myhome-core' )
				)
			),
			'most_popular_limit'  => array(
				'key'   => 'myhome_' . $this->attribute->get_slug() . '_most_popular_limit',
				'label' => esc_html__( 'Most popular values limit (number)', 'myhome-core' ),
				'name'  => $this->attribute->get_slug() . '_most_popular_limit',
				'type'  => 'text'
			),
			'static_values'       => array(
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
				),
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
			'icon'                => array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_icon',
				'label'         => esc_html__( 'Icon', 'myhome-core' ),
				'name'          => $this->attribute->get_slug() . '_icon',
				'type'          => 'text',
				'default_value' => ''
			),
			'order_by'            => array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_order_by',
				'label'         => esc_html__( 'Order by', 'myhome-core' ),
				'name'          => $this->attribute->get_slug() . '_order_by',
				'type'          => 'text',
				'default_value' => 'count'
			)
		);
		$this->basic_fields = array_merge( $this->basic_fields, $basic_fields );

		$advanced_fields       = array(
			array(
				'key'   => 'myhome_' . $this->attribute->get_slug() . '_tags',
				'label' => esc_html__( 'Use as tags', 'myhome-core' ),
				'name'  => $this->attribute->get_slug() . '_tags',
				'type'  => 'true_false',
			),
			array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_new_box',
				'label'         => esc_html__( 'Display values in a separate section', 'myhome-core' ),
				'instructions'  => '',
				'name'          => $this->attribute->get_slug() . '_new_box',
				'default_value' => true,
				'type'          => 'true_false'
			),
			array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_new_box_independent',
				'label'         => esc_html__( 'Display values in a separate section', 'myhome-core' ),
				'instructions'  => '',
				'name'          => $this->attribute->get_slug() . '_new_box_independent',
				'default_value' => false,
				'type'          => 'true_false'
			),
			array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_has_archive',
				'label'         => esc_html__( 'Single property page: show as link', 'myhome-core' ),
				'name'          => $this->attribute->get_slug() . '_has_archive',
				'type'          => 'true_false',
				'default_value' => true
			),
			array(
				'key'          => 'myhome_' . $this->attribute->get_slug() . '_checkbox_full_width',
				'label'        => esc_html__( 'Search Form - display full width', 'myhome-core' ),
				'instructions' => '',
				'name'         => $this->attribute->get_slug() . '_checkbox_full_width',
				'type'         => 'true_false'
			),
			'placeholder'   => array(
				'key'          => 'myhome_' . $this->attribute->get_slug() . '_placeholder',
				'label'        => esc_html__( 'Placeholder', 'myhome-core' ),
				'name'         => $this->attribute->get_slug() . '_placeholder',
				'type'         => 'text',
				'instructions' => esc_html__( 'Placeholder\'s name is by default a name of a field, but it can be changed below', 'myhome-core' )
			),
			'parent_id'     => array(
				'key'   => 'myhome_' . $this->attribute->get_slug() . '_parent_id',
				'label' => esc_html__( 'Parent attribute', 'myhome-core' ),
				'name'  => $this->attribute->get_slug() . '_parent_id',
				'type'  => 'text'
			),
			'parent_type'   => array(
				'key'   => 'myhome_' . $this->attribute->get_slug() . '_parent_type',
				'label' => esc_html__( 'Parent type', 'myhome-core' ),
				'name'  => $this->attribute->get_slug() . '_parent_type',
				'type'  => 'text'
			),
			'suggestion'    => array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_suggestions',
				'label'         => esc_html__( 'Show suggestion', 'myhome-core' ),
				'name'          => $this->attribute->get_slug() . '_suggestions',
				'type'          => 'true_false',
				'default_value' => false
			),
			'checkbox_move' => array(
				'key'           => 'myhome_' . $this->attribute->get_slug() . '_checkbox_move',
				'label'         => esc_html__( 'Checkbox - move checked elements to the beginning of the list', 'myhome-core' ),
				'name'          => $this->attribute->get_slug() . '_checkbox_move',
				'type'          => 'true_false',
				'default_value' => true
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
				'default_values'      => 'default_values',
				'static_values'       => 'static_values',
				'placeholder'         => 'placeholder',
				'icon'                => 'icon',
				'parent_type'         => 'parent_type',
				'order_by'            => 'order_by'
			),
			'number' => array(
				'most_popular_limit' => 'most_popular_limit',
				'parent_id'          => 'parent_id'
			),
			'bool'   => array(
				'tags'                => 'tags',
				'new_box'             => 'new_box',
				'new_box_independent' => 'new_box_independent',
				'full_width'          => 'checkbox_full_width',
				'has_archive'         => 'has_archive',
				'card_show'           => 'show_card',
				'property_show'       => 'show_property',
				'suggestions'         => 'suggestions',
				'checkbox_move'       => 'checkbox_move'
			)
		);

		$this->save_options( $option_names, $attribute_data );
	}

}