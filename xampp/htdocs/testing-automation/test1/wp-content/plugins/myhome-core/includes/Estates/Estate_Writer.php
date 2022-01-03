<?php

namespace MyHomeCore\Estates;


use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Common\Plan;
use MyHomeCore\Components\Contact_Form\Contact_Form_7;
use MyHomeCore\Components\Contact_Form\Contact_Form_Single_Property;
use MyHomeCore\Components\Estate_Map\Estate_Map;
use MyHomeCore\Estates\Elements\Estate_Element;
use MyHomeCore\Estates\Elements\Estate_Element_Factory;
use MyHomeCore\Estates\Prices\Price;
use MyHomeCore\Estates\Sidebar_Elements\Sidebar_Element;
use MyHomeCore\Estates\Sidebar_Elements\Sidebar_Elements;
use MyHomeCore\Users\User;

/**
 * Class Estate_Writer
 * @package MyHomeCore\Estates
 */
class Estate_Writer {

	const SLIDER_PHOTOS = 'single-estate-slider';
	const GALLERY_PHOTOS = 'single-estate-gallery';
	const GALLERY_AUTO_HEIGHT_PHOTOS = 'single-estate-gallery-auto-height';
	const WITH_SIDEBAR_CLASS = 'mh-layout__content-left';
	const WITHOUT_SIDEBAR_CLASS = 'mh-estate__no-sidebar';

	/**
	 * @var Estate
	 */
	private $estate;

	/**
	 * @var User
	 */
	public $agent;

	/**
	 * @var Sidebar_Elements
	 */
	private $sidebar_elements;

	/**
	 * Estate_Writer constructor.
	 *
	 * @param Estate $estate
	 */
	private function __construct( Estate $estate ) {
		$this->estate = $estate;
		$this->agent  = $this->estate->get_user();
		$this->estate->increment_views();
		$this->sidebar_elements = new Sidebar_elements( $estate->get_ID() );
	}

	/**
	 * @return int
	 */
	public function get_ID() {
		return $this->estate->get_ID();
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->estate->get_name();
	}

	/**
	 * @return Estate_Writer
	 * @throws \ErrorException
	 */
	public static function get_instance() {
		global $post;

		if ( is_null( $post ) ) {
			throw new \ErrorException( die( 'Property not found.' ) );
		}

		$estate = new Estate( $post );

		return new Estate_Writer( $estate );
	}

	/**
	 * @return Estate_Element[]
	 */
	public function get_elements() {
		return Estate_Element_Factory::get( $this->estate );
	}

	/**
	 * @param bool $maps
	 *
	 * @return bool
	 */
	public function has_address( $maps = false ) {
		$address = $this->estate->get_address( $maps );

		return ! empty( $address );
	}

	/**
	 * @param bool $maps
	 *
	 * @return string
	 */
	public function get_address( $maps = false ) {
		return $this->estate->get_address( $maps );
	}

	/**
	 * @return bool
	 */
	public function has_gallery() {
		return $this->estate->has_gallery();
	}

	/**
	 * @return bool
	 */
	public function has_additional_features() {
		return $this->estate->has_additional_features();
	}

	/**
	 * @return array
	 */
	public function get_additional_features() {
		return $this->estate->get_additional_features();
	}

	/**
	 * @return bool
	 */
	public function is_gallery_auto_height() {
		$gallery_type = \MyHomeCore\My_Home_Core()->settings->get( 'estate_slider' );

		return $gallery_type == 'single-estate-gallery-auto-height';
	}

	/**
	 * @return array
	 */
	public function get_gallery() {
		return $this->estate->get_gallery();
	}

	/**
	 * @return mixed|string
	 */
	public function get_gallery_transition() {
		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_slider-transition'] ) ) {
			return \MyHomeCore\My_Home_Core()->settings->props['mh-estate_slider-transition'];
		}

		return 'parallaxhorizontal';
	}

	/**
	 * @return bool
	 */
	public function has_price() {
		return $this->estate->has_price();
	}

	/**
	 * @return Price[]
	 */
	public function get_prices() {
		$show_all_prices = \MyHomeCore\My_Home_Core()->settings->get( 'single-estate_all-prices' );

		if ( ! empty( $show_all_prices ) ) {
			$prices = array();
			foreach ( $this->estate->get_currency_prices() as $currency_prices ) {
				$prices = array_merge( $prices, $currency_prices->get_prices() );
			}
		} else {
			$currency_prices = $this->estate->get_current_currency_prices();
			$prices          = $currency_prices->get_prices();
		}

		if ( ! empty( $prices ) ) {
			return $prices;
		}

		$prices = array();
		foreach ( $this->estate->get_currency_prices() as $currency_prices ) {
			$prices = array_merge( $prices, $currency_prices->get_prices() );
		}

		return $prices;
	}

	/**
	 * @return string
	 */
	public function get_slider_name() {
		return \MyHomeCore\My_Home_Core()->settings->get( 'estate_slider' );
	}

	/**
	 * @return bool
	 */
	public function is_gallery_type() {
		return \MyHomeCore\My_Home_Core()->settings->get( 'estate_slider' ) == self::GALLERY_PHOTOS
		       || \MyHomeCore\My_Home_Core()->settings->get( 'estate_slider' ) == self::GALLERY_AUTO_HEIGHT_PHOTOS;
	}

	/**
	 * @return bool
	 */
	public function is_slider_type() {
		return \MyHomeCore\My_Home_Core()->settings->get( 'estate_slider' ) == self::SLIDER_PHOTOS;
	}

	/**
	 * @return bool
	 */
	public function has_contact_form() {
		if ( ! $this->has_sidebar() ) {
			return false;
		}

		if ( ! array_key_exists( 'mh-estate_sidebar_contact_form', \MyHomeCore\My_Home_Core()->settings->props ) ) {
			return true;
		}

		return ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_sidebar_contact_form'] );
	}

	/**
	 * @param bool $maps
	 *
	 * @return bool
	 */
	public function has_map( $maps = false ) {
		return $this->estate->has_position( $maps )
		       && ( ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_map'] )
		            || \MyHomeCore\My_Home_Core()->settings->props['mh-estate_map'] == 'big' )
		       && ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-google-api-key'] );
	}

	public function map() {
		$estate_map = new Estate_Map( $this->estate );
		$estate_map->display();
	}

	/**
	 * @return bool
	 */
	public function has_video_plan() {
		$video_plan = get_post_meta( $this->estate->get_ID(), 'estate_video', true );

		return ! empty( $video_plan );
	}

	public function video_plan() {
		$video_plan = get_field( 'estate_video', $this->estate->get_ID() );

		if ( strpos( $video_plan, '<iframe' ) !== false || strpos( $video_plan, '<object' ) !== false
		     || strpos( $video_plan, '<embed' ) !== false
		) {
			echo myhome_filter( $video_plan );
		} elseif ( strpos( $video_plan, '[video' ) !== false ) {
			echo do_shortcode( $video_plan );
		} elseif ( strpos( $video_plan, 'fb-video' ) ) {
			echo myhome_filter( $video_plan );
		} else {
			global $wp_embed;
			echo myhome_filter( $wp_embed->run_shortcode( '[embed]' . $video_plan . '[/embed]' ) );
		}
	}

	/**
	 * @return bool
	 */
	public function has_plans() {
		return $this->estate->has_plans();
	}

	/**
	 * @return Plan[]
	 */
	public function get_plans() {
		return $this->estate->get_plans();
	}

	/**
	 * @return bool
	 */
	public function has_virtual_tour() {
		$virtual_tour = get_field( 'myhome_estate_virtual_tour', $this->estate->get_ID() );

		return ! empty( $virtual_tour );
	}

	public function virtual_tour() {
		the_field( 'myhome_estate_virtual_tour', $this->estate->get_ID() );
	}

	/**
	 * @return bool
	 */
	public function has_sidebar() {
		if ( ! array_key_exists( 'mh-estate_sidebar', \MyHomeCore\My_Home_Core()->settings->props ) ) {
			return true;
		}

		return ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_sidebar'] );
	}

	/**
	 * @return string
	 */
	public function get_container_class() {
		return $this->has_sidebar() ? self::WITH_SIDEBAR_CLASS : self::WITHOUT_SIDEBAR_CLASS;
	}

	/**
	 * @return Estate_Attribute[]
	 */
	public function get_attributes() {
		return $this->estate->get_attributes();
	}

	/**
	 * @return int
	 */
	public function get_views() {
		return $this->estate->get_views();
	}

	/**
	 * @return bool
	 */
	public function has_user_profile() {
		return ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_sidebar_user_profile'] )
		       || ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_sidebar_user_profile'] );
	}

	public function has_image() {
		return $this->estate->has_image();
	}

	public function user_profile() {
		$myhome_agents = array( $this->agent );

		$ids = get_post_meta( $this->get_ID(), 'myhome_agents', true );
		if ( is_array( $ids ) ) {
			foreach ( $ids as $id ) {
				$id = intval( $id );
				try {
					$myhome_agents[] = User::get_instance( $id );
				} catch ( \ErrorException $e ) {
				}
			}
		}

		global $myhome_agent;
		foreach ( $myhome_agents as $myhome_agent ) {
			get_template_part( 'templates/single-estate-partials/profile' );
		}
	}

	public function has_agency_profile() {
		return isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_sidebar_agency_profile'] )
		       && ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_sidebar_agency_profile'] )
		       && $this->agent->has_agency();
	}

	public function agency_profile() {
		global $myhome_agency;
		$myhome_agency = $this->agent->get_agency();
		get_template_part( 'templates/single-estate-partials/agency' );
	}

	/**
	 * @return bool
	 */
	public function has_attachments() {
		return $this->estate->has_attachments();
	}

	/**
	 * @return \MyHomeCore\Common\Attachment[]
	 */
	public function get_attachments() {
		return $this->estate->get_attachments();
	}

	public function contact_form() {
		if ( ! array_key_exists( 'mh-contact_form-type', \MyHomeCore\My_Home_Core()->settings->props ) ) {
			$type = 'default';
		} else {
			$type = \MyHomeCore\My_Home_Core()->settings->props['mh-contact_form-type'];
		}

		global $myhome_single_property_form;
		if ( $type == 'cf7' ) {
			$key = 'contact_form-cf7_form';
			if ( ! empty( \MyHomeCore\My_Home_Core()->current_language ) ) {
				$key .= '_' . \MyHomeCore\My_Home_Core()->current_language;
			}

			$form_id                     = intval( \MyHomeCore\My_Home_Core()->settings->get( $key ) );
			$myhome_single_property_form = Contact_Form_7::get_by_ID( $form_id );
		} else {
			$myhome_single_property_form = new Contact_Form_Single_Property( $this->estate );
		}

		get_template_part( 'templates/single-estate-partials/contact-form' );
	}

	/**
	 * @return bool|mixed
	 */
	public function show_date() {
		if ( isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_show_date'] ) ) {
			$show_date = filter_var( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_show_date'], FILTER_VALIDATE_BOOLEAN );
		} else {
			$show_date = true;
		}

		return $show_date;
	}

	/**
	 * @return string
	 */
	public function get_publish_date() {
		return $this->estate->get_publish_date();
	}

	/**
	 * @return string
	 */
	public function get_modified_date() {
		return $this->estate->get_modified_date();
	}

	/**
	 * @return string
	 */
	public function get_attribute_classes() {
		return $this->estate->get_attribute_classes();
	}

	/**
	 * @return bool
	 */
	public function has_background_image() {
		if ( empty( \MyHomeCore\My_Home_Core()->settings->props['mh-single-estate-gallery-top-header'] ) ) {
			return false;
		}

		return $this->estate->has_image();
	}

	/**
	 * @return int|string
	 */
	public function get_image_id() {
		return $this->estate->get_image_id();
	}

	/**
	 * @return string
	 */
	public function get_breadcrumbs_class() {
		$class = '';

		if ( $this->is_gallery_type() || $this->is_gallery_auto_height() ) {
			$class = ' mh-breadcrumbs-wrapper--single-property-gallery';
		} elseif ( $this->is_slider_type() ) {
			$class = ' mh-breadcrumbs-wrapper--single-property-slider';
		}

		return $class;
	}

	/**
	 * @return bool
	 */
	public function has_breadcrumbs() {
		return ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-breadcrumbs'] );
	}

	/**
	 * @return bool
	 */
	public function has_back_to_results_button() {
		return $this->has_breadcrumbs() && ! empty( $_COOKIE['mh_results'] );
	}

	/**
	 * @return string
	 */
	public function get_google_maps_link() {
		return $this->estate->get_google_maps_link();
	}


	/**
	 * @return \string
	 */
	public function get_back_to_results_url() {
		if ( ! $this->has_back_to_results_button() ) {
			return '';
		}

		return $_COOKIE['mh_results'] . '#results';
	}

	/**
	 * @return Estates
	 */
	public function get_related_properties() {
		$related_by = array();

		foreach ( Attribute_Factory::get_text() as $attribute ) {
			if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props[ 'mh-related-by__' . $attribute->get_ID() ] ) ) {
				$related_by[] = $attribute->get_ID();
			}
		}

		$estate_factory = new Estate_Factory();

		foreach ( Attribute_Factory::get() as $attribute ) {
			if ( in_array( $attribute->get_ID(), $related_by ) ) {
				$estate_attribute = new Estate_Attribute( $attribute, $this->estate );
				$values           = $estate_attribute->get_values();

				if ( count( $values ) === 0 ) {
					return new Estates();
				}
				$estate_factory->add_filter( $estate_attribute->get_filter() );
			}
		}

		foreach ( $this->estate->get_attributes() as $estate_attribute ) {
			if ( in_array( $estate_attribute->attribute->get_ID(), $related_by ) ) {
				$estate_factory->add_filter( $estate_attribute->get_filter() );
			}
		}

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-related_related-limit'] ) ) {
			$limit = intval( \MyHomeCore\My_Home_Core()->settings->props['mh-related_related-limit'] );
		} else {
			$limit = 6;
		}
		$estate_factory->set_limit( $limit );
		$estate_factory->set_estates__not_in( array( $this->get_ID() ) );

		return $estate_factory->get_results();
	}

	/**
	 * @return bool
	 */
	public function is_sidebar_sticky() {
		return ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_sidebar-sticky'] );
	}

	/**
	 * @return bool
	 */
	public function use_icons() {
		return ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_icons'] );
	}

	/**
	 * @return bool
	 */
	public function show_sidebar_elements() {
		return $this->is_gallery_type()
		       && ( $this->has_price() || $this->has_map() || $this->agent->has_phone() || $this->has_sidebar_elements() );
	}

	/**
	 * @return bool
	 */
	public function has_sidebar_elements() {
		return $this->sidebar_elements->has_elements();
	}

	/**
	 * @return Sidebar_Element[]
	 */
	public function get_sidebar_elements() {
		return $this->sidebar_elements->get_elements();
	}

	public function get_data() {
		return $this->estate->get_data();
	}

	/**
	 * @return bool
	 */
	public function is_compare_enabled() {
		$is_enabled = \MyHomeCore\My_Home_Core()->settings->get( 'compare' );

		return ! empty( $is_enabled );
	}

	/**
	 * @return bool
	 */
	public function is_favorite_enabled() {
		$is_enabled = \MyHomeCore\My_Home_Core()->settings->get( 'favorite' );

		return ! empty( $is_enabled );
	}

	/**
	 * @return bool
	 */
	public function is_featured() {
		return $this->estate->is_featured();
	}

}