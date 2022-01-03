<?php

namespace MyHomeCore\Attributes;

/**
 * Class Widgets_Attribute_Options_Page
 * @package MyHomeCore\Attributes
 */
class Widgets_Attribute_Options_Page extends Attribute_Options_Page {

	/**
	 * Widgets_Attribute_Options_Page constructor.
	 *
	 * @param Attribute $attribute
	 */
	public function __construct( Attribute $attribute ) {
		parent::__construct( $attribute );

		$this->basic_fields[]  = array(
			'key'       => 'myhome_' . $this->attribute->get_slug() . '_columns',
			'label'     => esc_html__( 'Columns', 'myhome-core' ),
			'name'      => $this->attribute->get_slug() . '_columns',
			'type'      => 'select',
			'choices'   => array(
				'mh-grid__1of1' => esc_html__( '1 column', 'myhome-core' ),
				'mh-grid__1of2' => esc_html__( '2 columns', 'myhome-core' )
			),
			'placement' => 'top',
		);
		$this->advanced_fields = array();
	}

	public function update_options( $attribute_data ) {
		// TODO: Implement update_options() method.
	}


}