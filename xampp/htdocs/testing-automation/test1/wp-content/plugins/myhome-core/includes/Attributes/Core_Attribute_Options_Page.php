<?php

namespace MyHomeCore\Attributes;


/**
 * Class Core_Attribute_Options_Page
 * @package MyHomeCore\Attributes
 */
class Core_Attribute_Options_Page extends Attribute_Options_Page {

	/**
	 * Core_Attribute_Options_Page constructor.
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
				'full_width' => 'checkbox_full_width'
			)
		);

		$this->save_options( $option_names, $attribute_data );
	}

}