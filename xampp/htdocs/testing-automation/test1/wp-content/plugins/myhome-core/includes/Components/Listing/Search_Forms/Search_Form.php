<?php

namespace MyHomeCore\Components\Listing\Search_Forms;


use MyHomeCore\Attributes\Attribute;
use MyHomeCore\Attributes\Attribute_Factory;

/**
 * Class Search_Form
 * @package MyHomeCore\Components\Listing\Search_Forms
 */
class Search_Form {

	const SEARCH_FORM_KEY = 'myhome_search_form_';
	const SEARCH_FORMS_OPTION = 'myhome_search_forms';
	const SEARCH_FORMs_COUNTER = 'myhome_search_form_number';

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var array
	 */
	private $selected_elements = array();

	/**
	 * @var array
	 */
	private $options = array();

	/**
	 * Search_Form constructor.
	 *
	 * @param $search_form_key
	 */
	public function __construct( $search_form_key ) {
		$search_form = get_option( $search_form_key );

		$this->key               = $search_form_key;
		$this->label             = $search_form['label'];
		$this->selected_elements = $search_form['selected_elements'];
		$this->options           = $search_form['options'];
	}

	/**
	 * @return array
	 */
	public function get_available_elements() {
		$available_elements = array();
		$selected_elements  = array();

		foreach ( $this->get_selected_elements() as $element ) {
			$selected_elements[] = $element['id'];
		}

		foreach ( Attribute_Factory::get_search() as $attribute ) {
			if ( ! in_array( $attribute->get_ID(), $selected_elements ) ) {
				$available_elements[] = array(
					'id'   => $attribute->get_ID(),
					'name' => $attribute->get_name(),
				);
			}
		}

		return $available_elements;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * @return Attribute[]
	 */
	public function get_attributes() {
		$attributes = array();

		foreach ( $this->get_selected_elements() as $attribute ) {
			$attr = Attribute::get_by_id( $attribute['id'] );
			if ( $attr != false ) {
				$attributes[] = $attr;
			}
		}

		return $attributes;
	}

	/**
	 * @return array
	 */
	public function get_selected_elements() {
		$selected_elements = array();

		if ( empty( $this->selected_elements ) ) {
			return $selected_elements;
		}

		foreach ( $this->selected_elements as $attribute_id ) {
			$attribute = Attribute::get_by_id( $attribute_id );

			if ( $attribute === false ) {
				continue;
			}

			$selected_elements[] = array(
				'id'   => $attribute->get_ID(),
				'name' => $attribute->get_name(),
			);
		}

		return $selected_elements;
	}

	/**
	 * @return array
	 */
	public function get_data() {
		return array(
			'key'                => $this->key,
			'label'              => $this->label,
			'selected_elements'  => $this->get_selected_elements(),
			'available_elements' => $this->get_available_elements(),
			'options'            => $this->options
		);
	}

	/**
	 * @param string $label
	 *
	 * @return Search_Form
	 */
	public static function create( $label ) {
		$counter = intval( get_option( self::SEARCH_FORMs_COUNTER, 1 ) );
		$counter ++;
		update_option( self::SEARCH_FORMs_COUNTER, $counter );
		$key            = self::SEARCH_FORM_KEY . $counter;
		$search_forms   = get_option( self::SEARCH_FORMS_OPTION, array() );
		$search_forms[] = $key;
		update_option( self::SEARCH_FORMS_OPTION, $search_forms );

		$search_form = array(
			'key'                => $key,
			'label'              => $label,
			'selected_elements'  => array(),
			'available_elements' => array(),
			'options'            => array()
		);

		update_option( $key, $search_form );

		return new Search_Form( $key );
	}

	/**
	 * @param array $search_form_data
	 */
	public static function update( $search_form_data ) {
		$search_form = array(
			'key'               => $search_form_data['key'],
			'label'             => $search_form_data['label'],
			'options'           => array(),
			'selected_elements' => array()
		);

		foreach ( $search_form_data['selected_elements'] as $element ) {
			$search_form['selected_elements'][] = $element['id'];
		}

		update_option( $search_form_data['key'], $search_form );
	}

	/**
	 * @param $elements
	 */
	public function update_elements( $elements ) {
		$search_form = array(
			'key'               => $this->get_key(),
			'label'             => $this->get_label(),
			'options'           => array(),
			'selected_elements' => $elements
		);

		update_option( $this->get_key(), $search_form );
	}

	/**
	 * @param string $search_form_key
	 */
	public static function delete( $search_form_key ) {
		$search_forms = get_option( self::SEARCH_FORMS_OPTION, array() );
		if ( ( $key = array_search( $search_form_key, $search_forms ) ) !== false ) {
			unset( $search_forms[ $key ] );
		}
		update_option( self::SEARCH_FORMS_OPTION, $search_forms );
		delete_option( $search_form_key );
	}

	/**
	 * @return array
	 */
	public static function get_all_search_forms_data() {
		$search_forms_data = array();

		foreach ( Search_Form::get_all_search_forms() as $search_form ) {
			$search_forms_data[] = $search_form->get_data();
		}

		return $search_forms_data;
	}

	/**
	 * @return Search_Form[]
	 */
	public static function get_all_search_forms() {
		$search_form_keys = get_option( self::SEARCH_FORMS_OPTION, array() );
		$search_forms     = array();

		if ( is_array( $search_form_keys ) ) {
			foreach ( $search_form_keys as $search_form_key ) {
				$search_forms[] = new Search_Form( $search_form_key );
			}
		}

		return $search_forms;
	}

	/**
	 * @return array
	 */
	public static function get_vc_search_form_list() {
		$search_forms      = Search_Form::get_all_search_forms();
		$search_forms_list = array( esc_html__( 'Default', 'myhome-core' ) => 'default' );
		foreach ( $search_forms as $search_form ) {
			$search_forms_list[ $search_form->get_label() ] = $search_form->get_key();
		}

		return $search_forms_list;
	}

	/**
	 * @param $search_form_key
	 *
	 * @return bool
	 */
	public static function exists( $search_form_key ) {
		$search_form = get_option( $search_form_key );

		return ! empty( $search_form );
	}

}
