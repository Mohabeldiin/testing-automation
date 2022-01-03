<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class Shortcodes
 * @package MyHomeCore\Shortcodes
 */
class Shortcodes {

	const ICON = 'myhome-core/public/img/vc-icon.png';

	/**
	 * @var Shortcode[]
	 */
	private $shortcodes = array();

	/**
	 * Shortcodes constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	public function register() {
		foreach ( $this->get_data() as $shortcode ) {
			$shortcode = Shortcode::create( $shortcode );
			add_shortcode( $shortcode->get_slug(), array( $this, $shortcode->get_slug() ) );
			$this->shortcodes[ $shortcode->get_slug() ] = $shortcode;
		}

		if ( function_exists( 'vc_lean_map' ) ) {
			$vc_shortcodes = new Visual_Composer_Shortcodes( $this->shortcodes );
			$vc_shortcodes->register();
		}
	}

	/**
	 * @param $name
	 * @param $arguments
	 *
	 * @return string
	 */
	public function __call( $name, $arguments ) {
		if ( array_key_exists( $name, $this->shortcodes ) ) {
			return $this->shortcodes[ $name ]->display( $arguments[0], $arguments[1] );
		}
	}

	/**
	 * @return Shortcode[]
	 */
	public function get_shortcodes() {
		return $this->shortcodes;
	}

	/**
	 * @return array
	 */
	private function get_data() {
		$shortcodes = array(
			'mh_listing'               => (object) array(
				'slug'         => 'mh_listing',
				'name'         => esc_html__( 'Search Form - Property Listings', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'listing',
				'is_container' => true,
				'as_parent'    => array( 'except' => '' ),
				'js_view'      => 'VcColumnView',
				'class'        => '\\MyHomeCore\\Shortcodes\\Listing_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_listing_map'           => (object) array(
				'slug'         => 'mh_listing_map',
				'name'         => esc_html__( 'Map - Property Listings', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'listing-map',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Listing_Map_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_button'                => (object) array(
				'slug'         => 'mh_button',
				'name'         => esc_html__( 'Button', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'button',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Button_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_heading'               => (object) array(
				'slug'         => 'mh_heading',
				'name'         => esc_html__( 'Heading', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'heading',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Heading_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_carousel_agent'        => (object) array(
				'slug'         => 'mh_carousel_agent',
				'name'         => esc_html__( 'Agent Carousel', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'carousel-agent',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Agent_Carousel_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_service'               => (object) array(
				'slug'         => 'mh_service',
				'name'         => esc_html__( 'Service Box', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'service',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Service_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_carousel_estate'       => (object) array(
				'slug'         => 'mh_carousel_estate',
				'name'         => esc_html__( 'Property Carousel', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'carousel-estate',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Estate_Carousel_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_list_estate'           => (object) array(
				'slug'         => 'mh_list_estate',
				'name'         => esc_html__( 'Property List', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'list-estate',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Estate_List_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_carousel_attribute'    => (object) array(
				'slug'         => 'mh_carousel_attribute',
				'name'         => esc_html__( 'Attribute Carousel', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'carousel-attribute',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Attribute_Carousel_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_carousel_clients'      => (object) array(
				'slug'         => 'mh_carousel_clients',
				'name'         => esc_html__( 'Client Carousel', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'carousel-client',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Client_Carousel_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			//testimonial
			'mh_carousel_testimonials' => (object) array(
				'slug'         => 'mh_carousel_testimonials',
				'name'         => esc_html__( 'Testimonial Carousel', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'carousel-testimonial',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Testimonial_Carousel_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_carousel_post'         => (object) array(
				'slug'         => 'mh_carousel_post',
				'name'         => esc_html__( 'Post Carousel', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'carousel-post',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Post_Carousel_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_simple_box'            => (object) array(
				'slug'         => 'mh_simple_box',
				'name'         => esc_html__( 'Simple Box', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'simple-box',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Simple_Box_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_icon'                  => (object) array(
				'slug'         => 'mh_icon',
				'name'         => esc_html__( 'Icon', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'icon',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Icon_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_slider'                => (object) array(
				'slug'         => 'mh_slider',
				'name'         => esc_html__( 'Slider', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'slider',
				'is_container' => true,
				'as_parent'    => array(
					'only' => 'mh_listing,mh_search_form_submit,mh_idx_wrapper,mh_idx_widget,mh_idx_omnibar'
				),
				'js_view'      => 'VcColumnView',
				'class'        => '\\MyHomeCore\\Shortcodes\\Slider_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			//			'mh_search_form_basic'     => (object) array(
			//				'slug'         => 'mh_search_form_basic',
			//				'name'         => esc_html__( 'Search form basic', 'myhome-core' ),
			//				'icon'         => plugins_url( self::ICON ),
			//				'template'     => 'search-form-basic',
			//				'is_container' => false,
			//				'as_parent'    => '',
			//				'js_view'      => '',
			//				'class'        => '\\MyHomeCore\\Shortcodes\\Search_Form_Basic_Shortcode'
			//
			//			),
			'mh_slider_estate'         => (object) array(
				'slug'         => 'mh_slider_estate',
				'name'         => esc_html__( 'Properties Slider', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'slider-estate',
				'is_container' => true,
				'as_parent'    => array(
					'only' => 'mh_listing,mh_idx_wrapper'
				),
				'js_view'      => 'VcColumnView',
				'class'        => '\\MyHomeCore\\Shortcodes\\Slider_Estate_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_mosaic_attribute'      => (object) array(
				'slug'         => 'mh_mosaic_attribute',
				'name'         => esc_html__( 'Attribute Mosaic', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'mosaic',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Mosaic_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_list_attribute'        => (object) array(
				'slug'         => 'mh_list_attribute',
				'name'         => esc_html__( 'Attribute List', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'list-attribute',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Attribute_List_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_list_agent'            => (object) array(
				'slug'         => 'mh_list_agent',
				'name'         => esc_html__( 'Agent list', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'agent-list',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\List_Agent_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
			'mh_search_form_submit'    => (object) array(
				'slug'         => 'mh_search_form_submit',
				'name'         => esc_html__( 'Classic Search Form', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'search-form-submit',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Search_Form_Submit_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			),
		);

		$shortcodes = array_merge( $shortcodes, array(
			'mh_packages' => (object) array(
				'slug'         => 'mh_packages',
				'name'         => esc_html__( 'WooCommerce Pricing Table', 'myhome-core' ),
				'icon'         => plugins_url( self::ICON ),
				'template'     => 'packages',
				'is_container' => false,
				'as_parent'    => '',
				'js_view'      => '',
				'class'        => '\\MyHomeCore\\Shortcodes\\Packages_Shortcode',
				'group'        => esc_html__( 'MyHome', 'myhome-core' )
			)
		) );

		if ( class_exists( 'MyHomeIDXBroker\IDX' ) ) {
			$api_key = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'api_key' );
			if ( ! empty( $api_key ) ) {
				$shortcodes = array_merge( $shortcodes, array(
					'mh_idx_wrapper' => (object) array(
						'slug'         => 'mh_idx_wrapper',
						'name'         => esc_html__( 'IDX Broker Wrapper', 'myhome-core' ),
						'icon'         => plugins_url( self::ICON ),
						'template'     => 'idx-wrapper',
						'is_container' => false,
						'as_parent'    => '',
						'js_view'      => '',
						'class'        => '\\MyHomeCore\\Shortcodes\\IDX_Wrapper_Shortcode',
						'group'        => esc_html__( 'MyHome IDX Broker', 'myhome-core' )
					),
					'mh_idx_widget'  => (object) array(
						'slug'         => 'mh_idx_widget',
						'name'         => esc_html__( 'IDX Broker Widget', 'myhome-core' ),
						'icon'         => plugins_url( self::ICON ),
						'template'     => 'idx-widget',
						'is_container' => false,
						'as_parent'    => '',
						'js_view'      => '',
						'class'        => '\\MyHomeCore\\Shortcodes\\IDX_Widget_Shortcode',
						'group'        => esc_html__( 'MyHome IDX Broker', 'myhome-core' )
					),
					'mh_idx_omnibar' => (object) array(
						'slug'         => 'mh_idx_omnibar',
						'name'         => esc_html__( 'IDX Broker Omnibar', 'myhome-core' ),
						'icon'         => plugins_url( self::ICON ),
						'template'     => 'idx-omnibar',
						'is_container' => false,
						'as_parent'    => '',
						'js_view'      => '',
						'class'        => '\\MyHomeCore\\Shortcodes\\IDX_Omnibar_Shortcode',
						'group'        => esc_html__( 'MyHome IDX Broker', 'myhome-core' )
					)
				) );
			}
		}

		return $shortcodes;
	}

}