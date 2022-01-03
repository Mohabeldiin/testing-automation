<?php

namespace MyHomeCore\Attributes;


/**
 * Class Attribute_Options_Page
 * @package MyHomeCore\Attributes
 */
abstract class Attribute_Options_Page {

	const MOST_POPULAR = 'most_popular';
	const STATIC_VALUES = 'static';
	const ALL_VALUES = 'all';

	/**
	 * @var Attribute
	 */
	protected $attribute;

	/**
	 * @var array
	 */
	protected $basic_fields = array();

	/**
	 * @var array
	 */
	protected $advanced_fields = array();

	/**
	 * @param array $attribute_data
	 */
	abstract public function update_options( $attribute_data );

	/**
	 * Attribute_Option_Page constructor.
	 *
	 * @param Attribute $attribute
	 */
	public function __construct( Attribute $attribute ) {
		$this->attribute         = $attribute;
		$this->basic_fields[]    = array(
			'key'       => 'myhome_' . $this->attribute->get_slug() . '_general',
			'label'     => esc_html__( 'Basic', 'myhome-core' ),
			'type'      => 'tab',
			'placement' => 'top',
		);
		$this->advanced_fields[] = array(
			'key'       => 'myhome_' . $this->attribute->get_slug() . '_advanced',
			'label'     => esc_html__( 'Advanced', 'myhome-core' ),
			'type'      => 'tab',
			'placement' => 'left',
		);
	}

	/**
	 * @return array
	 */
	protected function get_fields() {
		return array_merge( $this->basic_fields, $this->advanced_fields );
	}

	public function register() {
		if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
			return;
		}

		acf_add_options_sub_page(
			array(
				'page_title'  => sprintf(
					wp_kses(
						__(
							'%1$s - view on the search form<div class="acf-settings-wrap__subtitle">%2$s</div>',
							'myhome-core'
						), array( 'div' => array( 'class' => array() ) )
					),
					$this->attribute->get_name(),
					$this->attribute->get_type_name()
				),
				'menu_title'  => $this->attribute->get_name(),
				'menu_slug'   => 'acf-options-' . $this->attribute->get_slug(),
				'parent_slug' => 'myhome_attributes_hidden',
				'autoload'    => true
			)
		);

		acf_add_local_field_group(
			array(
				'key'      => 'myhome_' . $this->attribute->get_slug() . '_attribute',
				'title'    => sprintf( esc_html__( '%s Settings', 'myhome-core' ), $this->attribute->get_name() ),
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-' . $this->attribute->get_slug(),
						)
					)
				),
				'autoload' => true,
				'fields'   => $this->get_fields()
			)
		);
	}

	/**
	 * @param array $option_names
	 * @param array $attribute_data
	 */
	protected function save_options( $option_names, $attribute_data ) {
		foreach ( $option_names as $type => $type_options ) {
			foreach ( $type_options as $key_opt => $key_acf ) {
				switch ( $type ) {
					case 'text':
						$value = empty( $attribute_data[ $key_opt ] ) ? '' : $attribute_data[ $key_opt ];
						break;
					case 'number':
						if ( isset( $attribute_data[ $key_opt ] ) ) {
							$value = intval( $attribute_data[ $key_opt ] );
						} else {
							$value = 0;
						}
						break;
					case 'bool':
						if ( isset( $attribute_data[ $key_opt ] ) ) {
							$value = intval( filter_var( $attribute_data[ $key_opt ], FILTER_VALIDATE_BOOLEAN ) );
						} else {
							$value = false;
						}
						break;
					default:
						$value = $attribute_data[ $key_opt ];
				}

				update_field(
					'myhome_' . $this->attribute->get_slug() . '_' . $key_acf,
					$value,
					'option'
				);
			}
		}
	}
}