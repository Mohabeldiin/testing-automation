<?php

namespace MyHomeCore\Attributes;

use MyHomeCore\Components\Listing\Form\Field;
use MyHomeCore\Estates\Filters\Estate_Keyword_Filter;


/**
 * Class Keyword_Attribute
 * @package MyHomeCore\Attributes
 */
class Keyword_Attribute extends Core_Attribute {

	/**
	 * @var bool
	 */
	private $suggestions_enabled = false;

	/**
	 * @var string
	 */
	private $suggest_init = '';

	/**
	 * @var bool
	 */
	private $redirect = false;

	/**
	 * @var bool
	 */
	private $redirect_new_tab = false;

	/**
	 * @var array
	 */
	private $suggest_attributes = array();

	/**
	 * Keyword_Attribute constructor.
	 *
	 * @param $attribute
	 */
	public function __construct( $attribute ) {
		parent::__construct( $attribute );

		$this->set_keyword_attribute_data();
	}

	private function set_keyword_attribute_data() {
		if ( isset( $this->options[ 'options_' . $this->get_slug() . '_suggestions' ] ) && ! empty( $this->options[ 'options_' . $this->get_slug() . '_suggestions' ] ) ) {
			$this->suggestions_enabled = ! empty( $this->options[ 'options_' . $this->get_slug() . '_suggestions' ] );
		} else {
			$this->suggestions_enabled = false;
		}

		if ( isset( $this->options[ 'options_' . $this->get_slug() . '_redirect' ] ) && ! empty( $this->options[ 'options_' . $this->get_slug() . '_redirect' ] ) ) {
			$this->redirect = ! empty( $this->options[ 'options_' . $this->get_slug() . '_redirect' ] );
		} else {
			$this->redirect = false;
		}

		if ( isset( $this->options[ 'options_' . $this->get_slug() . '_redirect_new_tab' ] ) && ! empty( $this->options[ 'options_' . $this->get_slug() . '_redirect_new_tab' ] ) ) {
			$this->redirect_new_tab = ! empty( $this->options[ 'options_' . $this->get_slug() . '_redirect_new_tab' ] );
		} else {
			$this->redirect_new_tab = false;
		}

		if ( isset( $this->options[ 'options_' . $this->get_slug() . '_suggest_init' ] ) && ! empty( $this->options[ 'options_' . $this->get_slug() . '_suggest_init' ] ) ) {
			$this->suggest_init = ! empty( $this->options[ 'options_' . $this->get_slug() . '_suggest_init' ] ) ? intval( $this->options[ 'options_' . $this->get_slug() . '_suggest_init' ] ) : '';
		} else {
			$this->suggest_init = '';
		}

		$attributes = array();
		foreach ( Attribute_Factory::get_basic() as $attribute ) {
			if ( $attribute->attribute_type == Attribute::TEXT ) {
				if ( isset( $this->options[ 'options_' . $this->get_slug() . '_' . $attribute->ID . '_suggest' ] ) && ! empty( $this->options[ 'options_' . $this->get_slug() . '_' . $attribute->ID . '_suggest' ] ) ) {
					$suggest_attr = $this->options[ 'options_' . $this->get_slug() . '_' . $attribute->ID . '_suggest' ];
				} else {
					$suggest_attr = 0;
				}
				$attributes[ $attribute->ID ] = array(
					'id'      => $attribute->ID,
					'slug'    => $attribute->attribute_slug,
					'name'    => $attribute->attribute_name,
					'suggest' => ! empty( $suggest_attr ),
				);
			}
		}

		$this->suggest_attributes = $attributes;
	}

	public function get_suggestions() {
		return $this->suggestions_enabled;
	}

	public function get_suggest_init() {
		return $this->suggest_init;
	}

	public function get_suggest_attributes() {
		return $this->suggest_attributes;
	}

	public function get_redirect() {
		return $this->redirect;
	}

	public function get_redirect_new_tab() {
		return $this->redirect_new_tab;
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
			'placeholder'         => $this->get_placeholder(),
			'full_width'          => $this->get_full_width(),
			'suggestions_enabled' => $this->get_suggestions(),
			'suggest_init'        => $this->get_suggest_init(),
			'suggest_attributes'  => $this->get_suggest_attributes(),
			'redirect'            => $this->get_redirect(),
			'redirect_new_tab'    => $this->get_redirect_new_tab()
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
	 * @return Keyword_Attribute_Options_Page
	 */
	public function get_options_page() {
		return new Keyword_Attribute_Options_Page( $this );
	}

	/**
	 * @return string
	 */
	public function get_search_form_control() {
		return Field::KEYWORD;
	}

	/**
	 * @param Attribute_Values $attribute_values
	 * @param string           $compare
	 *
	 * @return Estate_Keyword_Filter
	 */
	public function get_estate_filter( Attribute_Values $attribute_values, $compare = '' ) {
		return new Estate_Keyword_Filter( $this, $attribute_values, $this->get_number_operator() );
	}

	/**
	 * @param int   $estate_id
	 * @param array $values
	 */
	public function update_estate_values( $estate_id, $values ) {
		return;
	}

}