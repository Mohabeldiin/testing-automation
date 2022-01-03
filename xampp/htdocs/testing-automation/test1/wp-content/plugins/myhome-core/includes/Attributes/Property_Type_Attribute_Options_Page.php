<?php

namespace MyHomeCore\Attributes;


class Property_Type_Attribute_Options_Page extends Text_Attribute_Options_Page {

	public function __construct( Attribute $attribute ) {
		parent::__construct( $attribute );

		$this->basic_fields['basic_fields'] = array(
			'key'     => 'myhome_' . $this->attribute->get_slug() . '_search_form_control',
			'label'   => esc_html__( 'Search form - display as:', 'myhome-core' ),
			'name'    => $this->attribute->get_slug() . '_search_form_control',
			'type'    => 'select',
			'choices' => array(
				'select'       => esc_html__( 'Dropdown list', 'myhome-core' ),
				'text'         => esc_html__( 'Input Text', 'myhome-core' ),
				'radio_button' => esc_html__( 'Left/right: radio button || top/bottom - drop-down list', 'myhome-core' )
			)
		);
	}

}