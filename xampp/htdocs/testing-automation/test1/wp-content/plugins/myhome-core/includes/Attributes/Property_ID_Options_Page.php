<?php

namespace MyHomeCore\Attributes;


/**
 * Class Text_Attribute_Options_Page
 * @package MyHomeCore\Attributes
 */
class Property_ID_Options_Page extends Core_Attribute_Options_Page {

	/**
	 * Property_ID_Options_Page constructor.
	 *
	 * @param Attribute $attribute
	 */
	public function __construct( Attribute $attribute ) {
		parent::__construct( $attribute );

		$this->basic_fields    = array_merge(
			$this->basic_fields, array(
				array(
					'key'          => 'myhome_' . $this->attribute->get_slug() . '_placeholder',
					'label'        => esc_html__( 'Placeholder', 'myhome-core' ),
					'name'         => $this->attribute->get_slug() . '_placeholder',
					'type'         => 'text',
					'instructions' => esc_html__( 'Placeholder\'s name is by default a name of a field, but it can be changed below', 'myhome-core' )
				),
				array(
					'key'   => 'myhome_' . $this->attribute->get_slug() . '_checkbox_full_width',
					'label' => esc_html__( 'Search Form - display full width', 'myhome-core' ),
					'name'  => $this->attribute->get_slug() . '_checkbox_full_width',
					'type'  => 'true_false'
				),
				array(
					'key'     => 'myhome_' . $this->attribute->get_slug() . '_show_card',
					'label'   => esc_html__( 'Display on property card', 'myhome-core' ),
					'name'    => $this->attribute->get_slug() . '_show_card',
					'type'    => 'true_false',
					'default' => false
				)
			)
		);
		$this->advanced_fields = array();
	}

	/**
	 * @param array $attribute_data
	 */
	public function update_options( $attribute_data ) {
		$option_names = array(
			'text' => array(
				'placeholder' => 'placeholder'
			),
			'bool' => array(
				'full_width'    => 'checkbox_full_width',
				'card_show'     => 'show_card',
			)
		);

		$this->save_options( $option_names, $attribute_data );
	}

}