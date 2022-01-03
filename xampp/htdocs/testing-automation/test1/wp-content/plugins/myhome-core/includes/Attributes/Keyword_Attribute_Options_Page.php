<?php

namespace MyHomeCore\Attributes;


/**
 * Class KeywordAttribute_Options_Page
 * @package MyHomeCore\Attributes
 */
class Keyword_Attribute_Options_Page extends Attribute_Options_Page {

	public function __construct( Attribute $attribute ) {
		parent::__construct( $attribute );

		$attributes = Attribute_Factory::get_basic();;
		$suggest_attributes = array();

		foreach ( $attributes as $attribute ) {
			if ( $attribute->attribute_type !== 'taxonomy' ) {
				continue;
			}

			$suggest_attributes[] = array(
				'key'   => 'myhome_' . $this->attribute->get_slug() . '_' . $attribute->ID . '_suggest',
				'label' => sprintf( esc_html__( 'Suggest %s', 'myhome-core' ), $attribute->attribute_name ),
				'name'  => $this->attribute->get_slug() . '_' . $attribute->ID . '_suggest',
				'type'  => 'true_false'
			);
		}

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
					'key'   => 'myhome_' . $this->attribute->get_slug() . '_suggestions',
					'label' => esc_html__( 'Suggestions', 'myhome-core' ),
					'name'  => $this->attribute->get_slug() . '_suggestions',
					'type'  => 'true_false'
				),
				array(
					'key'   => 'myhome_' . $this->attribute->get_slug() . '_suggest_init',
					'label' => esc_html__( 'Suggest at start', 'myhome-core' ),
					'name'  => $this->attribute->get_slug() . '_suggest_init',
					'type'  => 'text'
				),
				array(
					'key'   => 'myhome_' . $this->attribute->get_slug() . '_redirect',
					'label' => esc_html__( 'Redirect on select', 'myhome-core' ),
					'name'  => $this->attribute->get_slug() . '_redirect',
					'type'  => 'true_false'
				),
				array(
					'key'     => 'myhome_' . $this->attribute->get_slug() . '_redirect_new_tab',
					'label'   => esc_html__( 'Open in new tab', 'myhome-core' ),
					'name'    => $this->attribute->get_slug() . '_redirect_new_tab',
					'type'    => 'true_false',
					'default' => false
				),
			)
		);
		$this->basic_fields    = array_merge( $this->basic_fields, $suggest_attributes );
		$this->advanced_fields = array();
	}

	public function update_options( $attribute_data ) {
		$option_names = array(
			'text'   => array(
				'placeholder' => 'placeholder'
			),
			'bool'   => array(
				'full_width'          => 'checkbox_full_width',
				'suggestions_enabled' => 'suggestions',
				'redirect'            => 'redirect',
				'redirect_new_tab'    => 'redirect_new_tab'
			),
			'number' => array(
				'suggest_init' => 'suggest_init'
			)
		);

		foreach ( Attribute_Factory::get_text() as $attribute ) {
			$field_key                          = $attribute->get_ID() . '_suggest';
			$option_names['bool'][ $field_key ] = $field_key;
		}

		foreach ( $attribute_data['suggest_attributes'] as $attr ) {
			$attribute_data[ $attr['id'] . '_suggest' ] = $attr['suggest'];
		}

		$this->save_options( $option_names, $attribute_data );
	}

}