<?php

namespace MyHomeCore\Components\Listing\Form;


use MyHomeCore\Attributes\Attribute;
use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Keyword_Attribute;
use MyHomeCore\Attributes\Number_Attribute;
use \MyHomeCore\Attributes\Attribute_Values;
use MyHomeCore\Attributes\Text_Attribute;
use MyHomeCore\Terms\Term_Factory;

/**
 * Class Field
 * @package MyHomeCore\Components\Listing\Form
 */
class Field {

	const TEXT = 'text';
	const TEXT_RANGE = 'text_range';
	const SELECT = 'select';
	const SELECT_RANGE = 'select_range';
	const SELECT_MULTIPLE = 'select_multiple';
	const RADIO_BUTTON = 'radio_button';
	const CHECKBOX = 'checkbox';
	const KEYWORD = 'keyword';
	const ID = 'id';

	/**
	 * @var Attribute
	 */
	protected $attribute;

	/**
	 * @var mixed
	 */
	protected $values;

	/**
	 * Field constructor.
	 *
	 * @param Attribute $attribute
	 */
	public function __construct( $attribute ) {
		$this->attribute = $attribute;
	}

    /**
     * @param  false  $hide_empty
     *
     * @return array
     */
	public function get_data($hide_empty = false) {
		if ( $this->attribute instanceof Text_Attribute ) {
			$order_by = $this->attribute->get_order_by();
			if ( $order_by == 'name' ) {
				$values = $this->attribute->get_values( false, Term_Factory::ORDER_BY_NAME, Term_Factory::ORDER_ASC, $hide_empty );
			} else {
				$values = $this->attribute->get_values(false, Term_Factory::ORDER_BY_COUNT, Term_Factory::ORDER_DESC, $hide_empty);
			}
		} else {
			$values = $this->attribute->get_values();
		}

		$valuesData = array_map(
			function ( $attribute_values ) {
				/* @var Attribute_Values $attribute_values */
				return $attribute_values->get_data();
			}, $values
		);

		$data = array(
			'id'               => $this->attribute->get_ID(),
			'name'             => $this->attribute->get_name(),
			'slug'             => $this->attribute->get_slug(),
			'base_slug'        => $this->attribute->get_base_slug(),
			'parent_id'        => $this->attribute->get_parent_id(),
			'type'             => $this->attribute->get_search_form_control(),
			'is_number'        => $this->attribute instanceof Number_Attribute,
			'is_text'          => $this->attribute instanceof Text_Attribute,
			'compare_operator' => $this->attribute->get_number_operator(),
			'display_after'    => $this->attribute->get_display_after(),
			'full_width'       => $this->attribute->get_full_width(),
			'values'           => $valuesData,
			'suggestions'      => $this->attribute->get_suggestions(),
			'placeholder'      => $this->attribute->get_placeholder(),
			'placeholder_from' => $this->attribute->get_placeholder_from(),
			'placeholder_to'   => $this->attribute->get_placeholder_to()
		);

		if ( $this->attribute instanceof Text_Attribute || $this->attribute instanceof Number_Attribute ) {
			$data['checkbox_move'] = $this->attribute->get_checkbox_move();
		}

		if ( $this->attribute instanceof Text_Attribute ) {
			$data['parent_type'] = $this->attribute->get_parent_type();
		}

		if ( $this->attribute instanceof Keyword_Attribute && $this->attribute->get_suggestions() ) {
			$data_sets = array();
			foreach ( Attribute_Factory::get_text() as $attribute ) {
				$suggest = get_field( $this->attribute->get_slug() . '_' . $attribute->get_ID() . '_suggest', 'option' );

				if ( empty( $suggest ) ) {
					continue;
				}

				$attribute_data = array(
					'label' => $attribute->get_name(),
					'slug'  => $attribute->get_slug()
				);

				$data_sets[] = $attribute_data;
			}

			$data['redirect']         = $this->attribute->get_redirect();
			$data['redirect_new_tab'] = $this->attribute->get_redirect_new_tab();
			$data['local']            = false;
			$attribute_id             = $this->attribute->get_suggest_init();

			if ( ! empty( $attribute_id ) ) {
				$attribute = Attribute_Factory::get_by_ID( $attribute_id );

				if ( $attribute != false && $attribute instanceof Text_Attribute ) {
					$data['local'] = array(
						'label'          => $attribute->get_name(),
						'slug'           => $attribute->get_slug(),
						'attribute_slug' => $attribute->get_slug(),
						'id'             => $attribute->get_ID(),
						'data_set'       => array()
					);
					$terms         = Term_Factory::get( $attribute, apply_filters( 'mh_suggestions_limit', 0 ) );
					foreach ( $terms as $term ) {
						$data['local']['data_set'][] = array(
							'id'             => $term->get_ID(),
							'slug'           => $term->get_slug(),
							'attribute_slug' => $attribute->get_slug(),
							'name'           => $term->get_name(),
							'link'           => $term->get_link(),
						);
					}
				}
			}

			$data['data_sets']    = $data_sets;
			$data['api_endpoint'] = get_rest_url() . 'myhome/v1/suggestions';
		}

		return $data;
	}

}