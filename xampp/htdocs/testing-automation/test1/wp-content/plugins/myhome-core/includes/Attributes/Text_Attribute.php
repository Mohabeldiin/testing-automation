<?php

namespace MyHomeCore\Attributes;


use MyHomeCore\Common\Breadcrumbs\Breadcrumbs;
use MyHomeCore\Estates\Estate;
use MyHomeCore\Estates\Filters\Estate_Text_Filter;
use MyHomeCore\Terms\Term_Factory;

/**
 * Class Text_Attribute
 * @package MyHomeCore\Attributes
 */
class Text_Attribute extends Attribute {

	const PARENT_TYPE_VALUES = 'values';
	const PARENT_TYPE_MANUAL = 'manual';


	/**
	 * Text_Attribute constructor.
	 *
	 * @param $attribute
	 */
	public function __construct( $attribute ) {
		parent::__construct( $attribute );
		$this->set_text_attribute_data();
	}

	public function set_text_attribute_data() {
		$data = array(
			'bool'    => array(
				'has_archive'         => true,
				'tags'                => false,
				'new_box'             => false,
				'new_box_independent' => false,
				'suggestions'         => false,
				'checkbox_move'       => true
			),
			'string'  => array(
				'default_values' => '',
				'order_by'       => 'count'
			),
			'numeric' => array(
				'most_popular_limit' => 0,
				'parent_id'          => 0
			)
		);

		foreach ( $data['string'] as $key => $value ) {
			if ( isset( $this->options[ 'options_' . $this->get_slug() . '_' . $key ] ) ) {
				$this->attribute_data[ $key ] = $this->options[ 'options_' . $this->get_slug() . '_' . $key ];
			} else {
				$this->attribute_data[ $key ] = $value;
			}
		}

		foreach ( $data['bool'] as $key => $value ) {
			if ( isset( $this->options[ 'options_' . $this->get_slug() . '_' . $key ] ) ) {
				$this->attribute_data[ $key ] = ! empty( $this->options[ 'options_' . $this->get_slug() . '_' . $key ] );
			} else {
				$this->attribute_data[ $key ] = $value;
			}
		}

		foreach ( $data['numeric'] as $key => $value ) {
			if ( isset( $this->options[ 'options_' . $this->get_slug() . '_' . $key ] ) ) {
				$this->attribute_data[ $key ] = intval( $this->options[ 'options_' . $this->get_slug() . '_' . $key ] );
			} else {
				$this->attribute_data[ $key ] = $value;
			}
		}
	}

	/**
	 * @return \bool
	 */
	public function get_checkbox_move() {
		return $this->attribute_data['checkbox_move'];
	}

	/**
	 * @return bool
	 */
	public function has_archive() {
		return $this->attribute_data['has_archive'];
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return self::TEXT;
	}

	/**
	 * @return string
	 */
	public function get_type_name() {
		return esc_html__( 'Text field', 'myhome-core' );
	}

	/**
	 * @return Text_Attribute_Options_Page
	 */
	public function get_options_page() {
		return new Text_Attribute_Options_Page( $this );
	}

	/**
	 * @param int   $estate_id
	 * @param array $values
	 */
	public function update_estate_values( $estate_id, $values ) {
		$data = array();

		foreach ( $values as $value ) {
			$data[] = is_array( $value ) ? $value['value'] : $value;
		}

		wp_set_post_terms( $estate_id, $data, $this->get_slug() );
	}

	/**
	 * @param Estate $estate
	 *
	 * @return Attribute_Values
	 */
	public function get_estate_values( $estate ) {
		$values = array();
		$terms  = Term_Factory::get_from_estate( $estate->get_ID(), $this );

		foreach ( $terms as $term ) {
			$values[] = new Attribute_Value( $term->get_name(), $term->get_name(), $term->get_link(), $term->get_slug() );
		}

		return new Attribute_Values( $values );
	}

	/**
	 * @return bool
	 */
	public function is_estate_attribute() {
		return true;
	}

	/**
	 * @return bool
	 */
	public function has_parent() {
		$parent_id = $this->get_parent_id();

		return ! empty( $parent_id );
	}

	/**
	 * @return bool|Attribute
	 */
	public function get_parent() {
		$parent_id = $this->get_parent_id();

		return Attribute::get_by_id( $parent_id );
	}

	/**
	 * @return \string
	 */
	public function get_parent_type() {
		if ( ! isset( $this->options[ 'options_' . $this->get_slug() . '_parent_type' ] ) ) {
			return Text_Attribute::PARENT_TYPE_VALUES;
		}

		return $this->options[ 'options_' . $this->get_slug() . '_parent_type' ];
	}

	/**
	 * @param $order_by
	 *
	 * @return Attribute_Values[]
	 */
	public function get_values_by_parent( $order_by = Term_Factory::ORDER_BY_COUNT ) {
		global $wpdb;
		$query    = "SELECT ID from {$wpdb->posts} WHERE post_type = 'estate' AND post_status = 'publish' ";
		$ids_list = $wpdb->get_col( $query );
		$ids      = array();
		foreach ( $ids_list as $id ) {
			$ids[] = $id;
		}

		if ( empty( $ids ) ) {
			return array();
		}

		$parent_id = $this->get_parent_id();

		if ( empty( $parent_id ) ) {
			return array();
		}

		$parent = Attribute::get_by_id( $this->get_parent_id() );

		if ( $parent == false ) {
			return array();
		}

		if ( $order_by == Term_Factory::ORDER_BY_NAME ) {
			$order        = Term_Factory::ORDER_ASC;
			$order_by_sql = 't2.name ASC';
		} else {
			$order        = Term_Factory::ORDER_DESC;
			$order_by_sql = 'count DESC';
		}

//		if ( empty( \MyHomeCore\My_Home_Core()->current_language ) || \MyHomeCore\My_Home_Core()->current_language == \MyHomeCore\My_Home_Core()->default_language ) {
			$query = "
				SELECT
					t2.term_id, t2.name, t2.slug, t.slug as parent_slug, COUNT(tt2.term_id) as count
				FROM 
					{$wpdb->posts} p
					LEFT OUTER JOIN  {$wpdb->term_relationships} tr ON p.ID = tr.object_id
					LEFT OUTER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
					LEFT OUTER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
					LEFT OUTER JOIN  {$wpdb->term_relationships} tr2 ON p.ID = tr2.object_id
					LEFT OUTER JOIN {$wpdb->term_taxonomy} tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id
					LEFT OUTER JOIN {$wpdb->terms} t2 ON tt2.term_id = t2.term_id
				WHERE p.ID IN(" . implode( ', ', $ids ) . ")
					AND tt.taxonomy = %s
					AND tt2.taxonomy = %s
				GROUP BY
					tt.term_id, tt2.term_id
				ORDER BY
					$order_by_sql
			";
//		} else {
//			$wpml_table = $wpdb->prefix . 'icl_translations';
//			$query      = "
//				SELECT
//					t2.term_id, t2.name, t2.slug, t.slug as parent_slug, COUNT(tt2.term_id) as count
//				FROM
//					{$wpdb->posts} p
//					LEFT OUTER JOIN  {$wpdb->term_relationships} tr ON p.ID = tr.object_id
//					LEFT OUTER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
//					LEFT OUTER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
//					LEFT OUTER JOIN  {$wpdb->term_relationships} tr2 ON p.ID = tr2.object_id
//					LEFT OUTER JOIN {$wpdb->term_taxonomy} tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id
//					LEFT OUTER JOIN {$wpdb->terms} t2 ON tt2.term_id = t2.term_id
//					LEFT OUTER JOIN {$wpml_table} it ON tt2.term_id = it.element_id
//				WHERE
//					p.ID IN(" . implode( ', ', $ids ) . ")
//					AND tt.taxonomy = %s
//					AND tt2.taxonomy = %s
//                    AND it.language_code = '" . ICL_LANGUAGE_CODE . "'
//				GROUP BY
//					tt.term_id, tt2.term_id
//				ORDER BY
//					count DESC
//			";
//		}

		$results = $wpdb->get_results( $wpdb->prepare( $query, $parent->get_slug(), $this->get_slug() ) );
//		print_r($results);

		if ( empty( $results ) || ! is_array( $results ) ) {
			return array();
		}

		$values = array();
		foreach ( $results as $term ) {
			if ( ! isset( $values[ $term->parent_slug ] ) ) {
				$values[ $term->parent_slug ] = new Attribute_Values();
			}
			$values[ $term->parent_slug ]->add( new Attribute_Value( $term->name, $term->name, '', $term->slug ) );
		}

		$terms         = Term_Factory::get( $this, Term_Factory::ALL, $order_by, $order );
		$values['any'] = new Attribute_Values();
		foreach ( $terms as $term ) {
			$values['any']->add( new Attribute_Value( $term->get_name(), $term->get_name(), '', $term->get_slug() ) );
		}

		return $values;
	}

	/**
	 * @return Attribute_Values[]
	 */
	public function set_values_by_parent() {
		global $wpdb;
		$query    = "SELECT ID from {$wpdb->posts} WHERE post_type = 'estate' AND post_status = 'publish' ";
		$ids_list = $wpdb->get_col( $query );
		$ids      = array();
		foreach ( $ids_list as $id ) {
			$ids[] = $id;
		}

		$parent_id = $this->get_parent_id();

		if ( empty( $parent_id ) ) {
			return array();
		}

		$parent = Attribute::get_by_id( $this->get_parent_id() );

		if ( $parent == false ) {
			return array();
		}

		if ( empty( \MyHomeCore\My_Home_Core()->current_language ) ) {
			$query = "
				SELECT
					t2.term_id, t2.name, t2.slug, t.slug as parent_slug, COUNT(tt2.term_id) as count
				FROM 
					{$wpdb->posts} p
					LEFT OUTER JOIN  {$wpdb->term_relationships} tr ON p.ID = tr.object_id
					LEFT OUTER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
					LEFT OUTER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
					LEFT OUTER JOIN  {$wpdb->term_relationships} tr2 ON p.ID = tr2.object_id
					LEFT OUTER JOIN {$wpdb->term_taxonomy} tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id
					LEFT OUTER JOIN {$wpdb->terms} t2 ON tt2.term_id = t2.term_id
				WHERE p.ID IN(" . implode( ', ', $ids ) . ")
					AND tt.taxonomy = %s
					AND tt2.taxonomy = %s
				GROUP BY
					tt.term_id, tt2.term_id
				ORDER BY
					count DESC
			";
		} else {
			$wpml_table = $wpdb->prefix . 'icl_translations';
			$query      = "
				SELECT
					t2.term_id, t2.name, t2.slug, t.slug as parent_slug, COUNT(tt2.term_id) as count
				FROM
					{$wpdb->posts} p
					LEFT OUTER JOIN  {$wpdb->term_relationships} tr ON p.ID = tr.object_id
					LEFT OUTER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
					LEFT OUTER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
					LEFT OUTER JOIN  {$wpdb->term_relationships} tr2 ON p.ID = tr2.object_id
					LEFT OUTER JOIN {$wpdb->term_taxonomy} tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id
					LEFT OUTER JOIN {$wpdb->terms} t2 ON tt2.term_id = t2.term_id
					LEFT OUTER JOIN {$wpml_table} it ON tt2.term_id = it.element_id
				WHERE
					p.ID IN(" . implode( ', ', $ids ) . ")
					AND tt.taxonomy = %s
					AND tt2.taxonomy = %s
                    AND it.language_code = '" . ICL_LANGUAGE_CODE . "'
				GROUP BY
					tt.term_id, tt2.term_id
				ORDER BY
					count DESC
			";
		}

		$results = $wpdb->get_results( $wpdb->prepare( $query, $parent->get_slug(), $this->get_slug() ) );

		if ( empty( $results ) || ! is_array( $results ) ) {
			return array();
		}

		$values = array();
		foreach ( $results as $term ) {
			if ( ! isset( $values[ $term->parent_slug ] ) ) {
				$values[ $term->parent_slug ] = new Attribute_Values();
			}
			$values[ $term->parent_slug ]->add( new Attribute_Value( $term->name, $term->name, '', $term->slug ) );
		}

		$terms         = Term_Factory::get( $this );
		$values['any'] = new Attribute_Values();
		foreach ( $terms as $term ) {
			$values['any']->add( new Attribute_Value( $term->get_name(), $term->get_name(), '', $term->get_slug() ) );
		}

		return $values;
	}

	/**
	 * @return string
	 */
	public function get_order_by() {
		return array_key_exists( 'order_by', $this->attribute_data ) ? $this->attribute_data['order_by'] : 'count';
	}

	/**
	 * @param bool   $all_values
	 * @param string $order_by
	 * @param string $order
	 *
	 * @return array|Attribute_Values[]
	 */
	public function get_values( $all_values = false, $order_by = Term_Factory::ORDER_BY_COUNT, $order = Term_Factory::ORDER_DESC, $hide_empty = false ) {
		$values         = array();
		$default_values = $this->get_default_values();

		$parent_attribute_id = $this->get_parent_id();
		if ( $parent_attribute_id && $this->get_parent_type() == Text_Attribute::PARENT_TYPE_VALUES ) {
			return $this->get_values_by_parent( $order_by );
		}

		if ( $default_values == Attribute_Options_Page::STATIC_VALUES && ! $all_values ) {
			foreach ( $this->get_static_values() as $value ) {
				$values[] = new Attribute_Value( $value->name, $value->name, '', $value->value );
			}

			return array( 'any' => new Attribute_Values( $values ) );
		}

		if ( $default_values == Attribute_Options_Page::MOST_POPULAR && ! $all_values ) {
			$terms = Term_Factory::get( $this, $this->get_most_popular_limit(), $order_by, $order, $hide_empty );
		} else {
			$terms = Term_Factory::get( $this, Term_Factory::ALL, $order_by, $order, $hide_empty );
		}

		foreach ( $terms as $term ) {
			$options = array( 'parent_term' => 0, 'parent_term_name' => '' );
			if ( $parent_attribute_id && $term->has_parent_term() ) {
				$parent_wp_term = $term->get_parent_term();

				if ( $parent_wp_term instanceof \WP_Term ) {
					$options['parent_term']      = $parent_wp_term->slug;
					$options['parent_term_name'] = $parent_wp_term->name;
				}
			}

			$values[] = new Attribute_Value( $term->get_name(), $term->get_name(), $term->get_link(), $term->get_slug(), $options );
		}

		return array( 'any' => new Attribute_Values( $values ) );
	}

	/**
	 * @return array
	 */
	public function get_default_values() {
		return $this->attribute_data['default_values'];
	}

	/**
	 * @return int
	 */
	public function get_most_popular_limit() {
		return $this->attribute_data['most_popular_limit'];
	}

	/**
	 * @return bool
	 */
	public function get_tags() {
		return $this->attribute_data['tags'];
	}

	/**
	 * @return bool
	 */
	public function get_new_box() {
		return $this->attribute_data['new_box'];
	}

	/**
	 * @return bool
	 */
	public function get_new_box_independent() {
		return $this->attribute_data['new_box_independent'];
	}

	/**
	 * @return int
	 */
	public function get_parent_id() {
		return $this->attribute_data['parent_id'];
	}

	/**
	 * @return int
	 */
	public function get_suggestions() {
		return $this->attribute_data['suggestions'];
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
			'has_archive'         => $this->has_archive(),
			'card_show'           => $this->get_card_show(),
			'property_show'       => $this->get_property_show(),
			'search_form_control' => $this->get_search_form_control(),
			'tags'                => $this->get_tags(),
			'most_popular_limit'  => $this->get_most_popular_limit(),
			'full_width'          => $this->get_full_width(),
			'placeholder'         => $this->get_placeholder(),
			'default_values'      => $this->get_default_values(),
			'static_values'       => $this->get_static_values(),
			'new_box'             => $this->get_new_box(),
			'new_box_independent' => $this->get_new_box_independent(),
			'parent_id'           => $this->get_parent_id(),
			'suggestions'         => $this->get_suggestions(),
			'icon'                => $this->get_icon(),
			'icon_class'          => $this->get_icon_class(),
			'checkbox_move'       => $this->get_checkbox_move(),
			'is_breadcrumb'       => $this->is_in_breadcrumbs(),
			'parent_type'         => $this->get_parent_type(),
			'order_by'            => $this->get_order_by()
		);
	}


	/**
	 * @param array $attribute_data
	 */
	public function update_options(
		$attribute_data
	) {
		$options_page = $this->get_options_page();
		$options_page->update_options( $attribute_data );
	}

	/**
	 * @param Attribute_Values $attribute_values
	 * @param string           $compare
	 *
	 * @return Estate_Text_Filter
	 */
	public function get_estate_filter(
		Attribute_Values $attribute_values, $compare = ''
	) {
		return new Estate_Text_Filter( $this, $attribute_values, $this->get_number_operator() );
	}

	/**
	 * @param array $fields
	 *
	 * @return array
	 */
	public function get_vc_control( $fields ) {
		$terms   = Term_Factory::get( $this );
		$options = array( esc_html__( 'Any', 'myhome-core' ) => 'any' );
		foreach ( $terms as $term ) {
			$options[ $term->get_name() ] = $term->get_slug();
		}

		$fields[] = array(
			'type'        => 'dropdown',
			'heading'     => $this->get_name(),
			'param_name'  => $this->get_slug(),
			'value'       => $options,
			'group'       => esc_html__( 'Default values', 'myhome-core' ),
			'save_always' => true,
			'std'         => 'any'
		);

		return $fields;
	}

	/**
	 * @return bool
	 */
	public function is_in_breadcrumbs() {
		foreach ( Breadcrumbs::get_attributes() as $attribute ) {
			if ( $attribute->get_ID() == $this->get_ID() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return \MyHomeCore\Terms\Term[]
	 */
	public function get_terms() {
		return Term_Factory::get( $this, Term_Factory::ALL, Term_Factory::ORDER_BY_NAME, Term_Factory::ORDER_ASC );
	}

}
