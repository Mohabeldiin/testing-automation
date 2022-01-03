<?php

namespace MyHomeCore\Terms;


use MyHomeCore\Attributes\Attribute;
use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Attribute_Value;
use MyHomeCore\Attributes\Attribute_Values;
use MyHomeCore\Common\Image;
use MyHomeCore\Components\Listing\Listing;
use MyHomeCore\Estates\Filters\Estate_Text_Filter;

/**
 * Class Term
 * @package MyHomeCore\Terms
 */
class Term {

	/**
	 * @var \WP_Term
	 */
	protected $term;

	/**
	 * @var array
	 */
	protected $meta;

	/**
	 * Term constructor.
	 *
	 * @param \WP_Term $term
	 */
	public function __construct( \WP_Term $term ) {
		$this->term = $term;
		$this->meta = get_term_meta( $term->term_id );
	}

	/**
	 * @return int
	 */
	public function get_ID() {
		return $this->term->term_id;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->term->name;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->term->slug;
	}

	/**
	 * @return int
	 */
	public function get_count() {
		return $this->term->count;
	}

	/**
	 * @return string|\WP_Error
	 */
	public function get_link() {
		return get_term_link( $this->term );
	}

	/**
	 * @return bool
	 */
	public function has_image() {
		if ( ! isset( $this->meta['term_image'] ) || empty( $this->meta['term_image'] ) ) {
			return false;
		}

		return ! empty( $this->meta['term_image'][0] );
	}

	/**
	 * @return array
	 */
	public function get_image() {
		return $this->meta['term_image'][0];
	}

	/**
	 * @return bool
	 */
	public function is_excluded_from_search() {
		if ( ! isset( $this->meta['offer_type_exclude'] ) || empty( $this->meta['offer_type_exclude'] ) ) {
			return false;
		}

		return ! empty( $this->meta['offer_type_exclude'][0] );
	}

	/**
	 * @return bool|int
	 */
	public function get_image_id() {
		if ( isset( $this->meta['term_image'][0] ) ) {
			return intval( $this->meta['term_image'][0] );
		}

		return false;
	}

	/**
	 * @param string $thumbnail
	 * @param string $class
	 */
	public function image( $thumbnail = 'square', $class = '' ) {
		Image::the_image( $this->get_image_id(), $thumbnail, $this->get_name(), $class );
	}

	/**
	 * @return bool
	 */
	public function has_image_wide() {
		if ( ! isset( $this->meta['term_image_wide'] ) || empty( $this->meta['term_image_wide'] ) ) {
			return false;
		}

		return ! empty( $this->meta['term_image_wide'][0] );
	}

	/*
	 * @return string
	 */
	public function get_image_wide() {
		if ( isset( $this->meta['term_image_wide'][0] ) ) {
			return wp_get_attachment_image_url( $this->meta['term_image_wide'][0] );
		}

		return '';
	}

	/**
	 * @return mixed
	 */
	public function get_after_price() {
		if ( ! isset( $this->meta['offer_type_after_price'] ) || empty( $this->meta['offer_type_after_price'] ) ) {
			return '';
		}

		return apply_filters( 'wpml_translate_single_string', $this->meta['offer_type_after_price'][0], 'myhome-core', 'After price' );
	}

	/**
	 * @return mixed
	 */
	public function get_before_price() {
		if ( ! isset( $this->meta['offer_type_before_price'] ) || empty( $this->meta['offer_type_before_price'] ) ) {
			return '';
		}

		return apply_filters( 'wpml_translate_single_string', $this->meta['offer_type_before_price'][0], 'myhome-core', 'After price' );
	}

	/**
	 * @return bool
	 */
	public function specify_price() {
		if ( ! isset( $this->meta['offer_type_specify_price'] ) || empty( $this->meta['offer_type_specify_price'] ) ) {
			return false;
		}

		return ! empty( $this->meta['offer_type_specify_price'][0] );
	}

	/**
	 * @return bool
	 */
	public function is_price_range() {
		if ( ! isset( $this->meta['offer_type_is_price_range'] ) || empty( $this->meta['offer_type_is_price_range'] ) ) {
			return false;
		}

		return ! empty( $this->meta['offer_type_is_price_range'][0] );
	}

	/**
	 * @return bool
	 */
	public function has_label() {
		if ( ! isset( $this->meta['offer_type_label'] ) || empty( $this->meta['offer_type_label'] ) ) {
			return true;
		}

		return ! empty( $this->meta['offer_type_label'][0] );
	}

	/**
	 * @return mixed
	 */
	public function get_bg_color() {
		if ( ! isset( $this->meta['offer_type_label_bg'] ) || empty( $this->meta['offer_type_label_bg'] ) ) {
			$bg_color = '';
		} else {
			$bg_color = $this->meta['offer_type_label_bg'][0];
		}

		if ( empty( $bg_color ) && isset( \MyHomeCore\My_Home_Core()->settings->props['mh-color-primary']['color'] ) ) {
			$bg_color = \MyHomeCore\My_Home_Core()->settings->props['mh-color-primary']['color'];
		}

		return $bg_color;
	}

	/**
	 * @return mixed
	 */
	public function get_color() {
		if ( ! isset( $this->meta['offer_type_label_color'] ) || empty( $this->meta['offer_type_label_color'] ) ) {
			$color = '';
		} else {
			$color = $this->meta['offer_type_label_color'][0];
		}

		if ( empty( $color ) ) {
			$color = '#fff';
		}

		return $color;
	}

	/**
	 * @return bool|int
	 */
	public function get_image_wide_id() {
		$image_wide_id = get_term_meta( $this->term->term_id, 'term_image_wide', true );
		if ( isset( $image_wide_id ) ) {
			return intval( $image_wide_id );
		}

		return false;
	}

	public function listing() {
		$settings = array(
			'show_advanced',
			'show_clear',
			'show_sort_by',
			'show_view_types',
			'search_form_advanced_number'
		);

		$atts = array(
			'lazy_loading'         => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-lazy_loading'] ? 'true' : 'false',
			'lazy_loading_limit'   => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-load_more_button_number'],
			'load_more_button'     => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-load_more_button_label'],
			'listing_default_view' => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-default_view'],
			'estates_per_page'     => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-estates_limit'],
			'search_form_position' => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-search_form_position'],
			'label'                => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-label'],
			$this->term->taxonomy  => $this->get_slug()
		);

		foreach ( $settings as $opt ) {
			if ( isset( \MyHomeCore\My_Home_Core()->settings->props[ 'mh-listing-' . $opt ] ) ) {
				$atts[ $opt ] = intval( \MyHomeCore\My_Home_Core()->settings->props[ 'mh-listing-' . $opt ] );
			}
		}

		foreach ( Attribute_Factory::get_search() as $attribute ) {
			if ( isset( \MyHomeCore\My_Home_Core()->settings->props[ 'mh-listing-' . $attribute->get_slug() . '_show' ] ) ) {
				$atts[ $attribute->get_slug() . '_show' ] = ! empty( \MyHomeCore\My_Home_Core()->settings->props[ 'mh-listing-' . $attribute->get_slug() . '_show' ] );
			} else {
				$atts[ $attribute->get_slug() . '_show' ] = true;
			}
		}

		$atts[ $this->term->taxonomy . '_show' ] = false;

		$listing = new Listing( $atts );

		?>
		<div class="mh-listing--full-width mh-listing--horizontal-boxed">
			<?php $listing->display(); ?>
		</div>
		<?php
	}

	/**
	 * @return Estate_Text_Filter
	 */
	public function get_estate_filter() {
		$attribute        = Attribute_Factory::get_by_slug( $this->term->taxonomy );
		$attribute_values = new Attribute_Values( array( new Attribute_Value( $this->get_name(), $this->get_name(), $this->get_link(), $this->get_slug() ) ) );

		return new Estate_Text_Filter( $attribute, $attribute_values );
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return $this->term->description;
	}

	/**
	 * @param int|null $term_id
	 *
	 * @return bool|Term
	 */
	public static function get_term( $term_id = null ) {
		if ( is_null( $term_id ) ) {
			$term_id = get_queried_object()->term_id;
		}
		$term = get_term( $term_id );

		if ( ! $term instanceof \WP_Term ) {
			return false;
		}

		return new Term( $term );
	}

	/**
	 * @param           $slug
	 * @param Attribute $attribute
	 *
	 * @return bool|Term
	 */
	public static function get_by_slug( $slug, $attribute ) {
		$wp_term = get_term_by( 'slug', $slug, $attribute->get_slug() );

		if ( ! $wp_term instanceof \WP_Term ) {
			return false;
		}

		return new Term( $wp_term );
	}

	/**
	 * @param string    $name
	 * @param Attribute $attribute
	 *
	 * @return bool|Term
	 */
	public static function get_by_name( $name, $attribute ) {
		$wp_term = get_term_by( 'name', $name, $attribute->get_slug() );

		if ( ! $wp_term instanceof \WP_Term ) {
			return false;
		}

		return new Term( $wp_term );
	}

	/**
	 * @return \WP_Term
	 */
	public function get_wp_term() {
		return $this->term;
	}

	/**
	 * @return bool
	 */
	public function has_parent_term() {
		$attribute = Attribute_Factory::get_by_slug( $this->term->taxonomy );
		if ( ! $attribute ) {
			return false;
		}

		$meta_key = 'term_parent_' . $attribute->get_ID();
		if ( ! isset( $this->meta[ $meta_key ] ) || empty( $this->meta[ $meta_key ] ) || ! is_array( $this->meta[ $meta_key ] ) ) {
			return false;
		}

		return ! empty( $this->meta[ $meta_key ][0] );

	}

	/**
	 * @return \WP_Term|\bool
	 */
	public function get_parent_term() {
		$attribute = Attribute_Factory::get_by_slug( $this->term->taxonomy );
		if ( ! $attribute ) {
			return false;
		}

		$meta_key = 'term_parent_' . $attribute->get_ID();
		if ( ! isset( $this->meta[ $meta_key ] ) || ! isset( $this->meta[ $meta_key ] [0] ) ) {
			return false;
		}
		$parent_term_id = $this->meta[ $meta_key ][0];
		$parent_term    = get_term( $parent_term_id );

		if ( ! $parent_term instanceof \WP_Term ) {
			return false;
		}

		return $parent_term;
	}

}