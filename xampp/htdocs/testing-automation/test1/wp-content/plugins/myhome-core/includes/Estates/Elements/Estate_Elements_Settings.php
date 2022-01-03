<?php

namespace MyHomeCore\Estates\Elements;


use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Text_Area_Attribute;
use MyHomeCore\Attributes\Widgets_Attribute;

/**
 * Class Estate_Elements_Settings
 * @package MyHomeCore\Estates\Elements
 */
class Estate_Elements_Settings {

	const OPTION_KEY = 'myhome_estate_elements';

	/**
	 * Estate_Elements_Settings constructor.
	 */
	public function __construct() {
		add_action( 'admin_post_myhome_estate_elements_save', array( $this, 'save' ) );
		add_action( 'redux/options/myhome_redux/reset', array( $this, 'check_elements' ) );
		add_action( 'redux/options/myhome_redux/saved', array( $this, 'check_elements' ) );
		add_action( 'myhome_attribute_deleted_textarea', array( $this, 'delete_textarea' ) );
		add_action( 'myhome_attribute_deleted_widgets', array( $this, 'delete_widgets' ) );
	}

	public function save() {
		if ( ! current_user_can( 'manage_options' ) || ! check_ajax_referer( 'myhome_backend_panel_' . get_current_user_id() ) ) {
			throw new \ErrorException( die( 'No permissions.' ) );
		}
		$elements = empty( $_POST['elements'] ) || ! is_array( $_POST['elements'] ) ? array() : $_POST['elements'];

		update_option( self::OPTION_KEY, $elements );
	}

	/**
	 * @return array
	 */
	public static function get() {
		$elements = get_option( self::OPTION_KEY );

		if ( empty( $elements ) ) {
			$elements = array(
				array(
					'label' => esc_html__( 'Details', 'myhome-core' ),
					'slug'  => Estate_Element::DESCRIPTION,
					'type'  => Estate_Element::DESCRIPTION
				),
				array(
					'label' => esc_html__( 'Attributes', 'myhome-core' ),
					'slug'  => Estate_Element::ATTRIBUTES,
					'type'  => Estate_Element::ATTRIBUTES
				)
			);

			update_option( self::OPTION_KEY, $elements, false );
		}

		foreach ( $elements as $key => $element ) {
			if ( $element['type'] == 'shortcode' ) {
				if ( isset( $element['shortcode'] ) && ! empty( $element['shortcode'] ) ) {
					$elements[ $key ]['shortcode'] = wp_kses_stripslashes( $element['shortcode'] );
				}
			} elseif ( $element['type'] == Estate_Element::GALLERY ) {
				$element['label'] = esc_html__( 'Gallery', 'myhome-core' );
			} elseif ( $element['type'] == Estate_Element::ATTRIBUTES ) {
				$element['label'] = esc_html__( 'Attributes', 'myhome-core' );
			} elseif ( $element['type'] == Estate_Element::INFO ) {
				$element['label'] = esc_html__( 'Additional info', 'myhome-core' );
			}
		}

		return $elements;
	}

	/**
	 * @return array
	 */
	public static function get_types() {
		$types = array(
			Estate_Element::DESCRIPTION        => array(
				'name' => esc_html__( 'Description', 'myhome-core' ),
				'type' => Estate_Element::DESCRIPTION
			),
			Estate_Element::VIDEO              => array(
				'name' => esc_html__( 'Video', 'myhome-core' ),
				'type' => Estate_Element::VIDEO
			),
			Estate_Element::VIRTUAL_TOUR       => array(
				'name' => esc_html__( 'Virtual tour', 'myhome-core' ),
				'type' => Estate_Element::VIRTUAL_TOUR
			),
			Estate_Element::PLANS              => array(
				'name' => esc_html__( 'Plans', 'myhome-core' ),
				'type' => Estate_Element::PLANS
			),
			Estate_Element::ATTACHMENTS        => array(
				'name' => esc_html__( 'Attachments', 'myhome-core' ),
				'type' => Estate_Element::ATTACHMENTS
			),
			Estate_Element::RELATED_PROPERTIES => array(
				'name' => esc_html__( 'Related properties', 'myhome-core' ),
				'type' => Estate_Element::RELATED_PROPERTIES
			),
			Estate_Element::MAP                => array(
				'name' => esc_html__( 'Map', 'myhome-core' ),
				'type' => Estate_Element::MAP
			),
			Estate_Element::ATTRIBUTES         => array(
				'name' => esc_html__( 'Attributes', 'myhome-core' ),
				'type' => Estate_Element::ATTRIBUTES
			),
			Estate_Element::TEXT               => array(
				'name' => esc_html__( 'Text', 'myhome-core' ),
				'type' => Estate_Element::TEXT
			),
			Estate_Element::GALLERY            => array(
				'name' => esc_html__( 'Gallery', 'myhome-core' ),
				'type' => Estate_Element::GALLERY
			),
			Estate_Element::WIDGETS            => array(
				'name' => esc_html__( 'Widgets', 'myhome-core' ),
				'type' => Estate_Element::WIDGETS
			),
			Estate_Element::TEXTAREA           => array(
				'name' => esc_html__( 'Text area', 'myhome-core' ),
				'type' => Estate_Element::TEXTAREA
			),
			Estate_Element::SHORTCODE          => array(
				'name' => esc_html__( 'Shortcode', 'myhome-core' ),
				'type' => Estate_Element::SHORTCODE
			)
		);

		return $types;
	}

	/**
	 * Add or remove small map from single property elements
	 */
	public function map() {
		$elements = self::get();

		if ( ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_map-size'] )
		     || \MyHomeCore\My_Home_Core()->settings->props['mh-estate_map-size'] == 'big'
		) {
			foreach ( $elements as $key => $element ) {
				if ( $element['type'] == Estate_Element::MAP ) {
					unset( $elements[ $key ] );
					update_option( self::OPTION_KEY, $elements );

					return;
				}
			}

			return;
		}

		foreach ( $elements as $element ) {
			if ( $element['type'] == Estate_Element::MAP ) {
				return;
			}
		}

		array_unshift(
			$elements, array(
				'label' => esc_html__( 'Gallery', 'myhome-core' ),
				'slug'  => Estate_Element::MAP,
				'type'  => Estate_Element::MAP
			)
		);

		update_option( self::OPTION_KEY, $elements );
	}

	/**
	 * Add or remove gallery from single property elements
	 */
	public function gallery() {
		$elements = self::get();

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_slider'] )
		     && \MyHomeCore\My_Home_Core()->settings->props['mh-estate_slider'] == 'single-estate-slider'
		) {
			foreach ( $elements as $key => $element ) {
				if ( $element->type == Estate_Element::GALLERY ) {
					array_splice( $elements, $key, 1 );
					update_option( self::OPTION_KEY, $elements );

					return;
				}
			}

			return;
		}

		foreach ( $elements as $element ) {
			if ( $element['type'] == Estate_Element::GALLERY ) {
				return;
			}
		}

		array_unshift(
			$elements, array(
				'label' => esc_html__( 'Gallery', 'myhome-core' ),
				'slug'  => Estate_Element::GALLERY,
				'type'  => Estate_Element::GALLERY
			)
		);

		update_option( self::OPTION_KEY, $elements );
	}

	public static function get_available() {
		$elements = array(
			'mh-estate_video'              => (object) array(
				'label'   => esc_html__( 'Video', 'myhome-core' ),
				'slug'    => Estate_Element::VIDEO,
				'type'    => Estate_Element::VIDEO,
				'default' => true
			),
			'mh-estate_virtual_tour'       => (object) array(
				'label'   => esc_html__( 'Virtual tour', 'myhome-core' ),
				'slug'    => Estate_Element::VIRTUAL_TOUR,
				'type'    => Estate_Element::VIRTUAL_TOUR,
				'default' => false
			),
			'mh-estate_plans'              => (object) array(
				'label'   => esc_html__( 'Plans', 'myhome-core' ),
				'slug'    => Estate_Element::PLANS,
				'type'    => Estate_Element::PLANS,
				'default' => true
			),
			'mh-estate_attachments'        => (object) array(
				'label'   => esc_html__( 'Attachments', 'myhome-core' ),
				'slug'    => Estate_Element::ATTACHMENTS,
				'type'    => Estate_Element::ATTACHMENTS,
				'default' => false
			),
			'mh-estate_related-properties' => (object) array(
				'label'   => esc_html__( 'Related properties', 'myhome-core' ),
				'slug'    => Estate_Element::RELATED_PROPERTIES,
				'type'    => Estate_Element::RELATED_PROPERTIES,
				'default' => false
			),
			'mh-estate_description'        => (object) array(
				'label'   => esc_html__( 'Details', 'myhome-core' ),
				'slug'    => Estate_Element::DESCRIPTION,
				'type'    => Estate_Element::DESCRIPTION,
				'default' => true
			),
			'mh-estate_attributes'         => (object) array(
				'label'   => esc_html__( 'Attributes', 'myhome-core' ),
				'slug'    => Estate_Element::ATTRIBUTES,
				'type'    => Estate_Element::ATTRIBUTES,
				'default' => true
			),
			'mh-estate_info'               => (object) array(
				'label'   => esc_html__( 'Additional info', 'myhome-core' ),
				'slug'    => Estate_Element::INFO,
				'type'    => Estate_Element::INFO,
				'default' => true
			)
		);

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_slider'] )
		     && \MyHomeCore\My_Home_Core()->settings->props['mh-estate_slider'] != 'single-estate-slider'
		) {
			$found = false;
			foreach ( $elements as $key => $element ) {
				if ( $element->type == Estate_Element::GALLERY ) {
					$found = true;
					break;
				}
			}

			if ( ! $found ) {
				$elements['mh-estate_gallery'] = (object) array(
					'label'   => esc_html__( 'Gallery', 'myhome-core' ),
					'slug'    => Estate_Element::GALLERY,
					'type'    => Estate_Element::GALLERY,
					'default' => true
				);
			}
		}

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_map'] )
		     && \MyHomeCore\My_Home_Core()->settings->props['mh-estate_map'] == 'small'
		) {
			$found = false;
			foreach ( $elements as $key => $element ) {
				if ( $element->type == Estate_Element::MAP ) {
					$found = true;
					break;
				}
			}

			if ( ! $found ) {
				$elements['mh-estate_map'] = (object) array(
					'label'   => esc_html__( 'Map', 'myhome-core' ),
					'slug'    => Estate_Element::MAP,
					'type'    => Estate_Element::MAP,
					'default' => true
				);
			}
		}

		$available_elements = array();
		$selected_elements  = self::get();

		foreach ( $elements as $el_key => $el ) {
			if ( ! isset( \MyHomeCore\My_Home_Core()->settings->props[ $el_key ] ) && ! $el->default ) {
				$delete = true;
			} elseif ( ! isset( \MyHomeCore\My_Home_Core()->settings->props[ $el_key ] ) && $el->default ) {
				$delete = false;
			} elseif ( empty( \MyHomeCore\My_Home_Core()->settings->props[ $el_key ] ) ) {
				$delete = true;
			} else {
				$delete = false;
			}

			if ( $delete ) {
				unset( $elements[ $el_key ] );
			}
		}

		foreach ( Attribute_Factory::get_property_elements() as $attribute ) {
			$elements[] = (object) array(
				'label' => $attribute->get_name(),
				'slug'  => $attribute->get_slug(),
				'type'  => $attribute->get_type()
			);
		}

		foreach ( $elements as $element ) {
			$found = false;

			foreach ( $selected_elements as $selected_element ) {
				if ( isset( $selected_element['slug'] ) && $element->slug == $selected_element['slug'] ) {
					$found = true;
					break;
				}
			}

			if ( ! $found ) {
				$available_elements[] = $element;
			}
		}

		$available_elements[] = (object) array(
			'label'   => esc_html__( 'Shortcode', 'myhome-core' ),
			'slug'    => Estate_Element::SHORTCODE,
			'type'    => Estate_Element::SHORTCODE,
			'default' => false
		);

		return $available_elements;
	}

	public function check_elements() {
		$remove_types   = array();
		$check_elements = array(
			Estate_Element::ATTACHMENTS        => 'mh-estate_attachments',
			Estate_Element::RELATED_PROPERTIES => 'mh-estate_related-properties',
			Estate_Element::PLANS              => 'mh-estate_plans',
			Estate_Element::VIRTUAL_TOUR       => 'mh-estate_virtual_tour',
			Estate_Element::VIDEO              => 'mh-estate_video',
			Estate_Element::INFO               => 'mh-estate_info'
		);

		$elements = self::get();
		foreach ( $check_elements as $type => $element ) {
			if ( empty( \MyHomeCore\My_Home_Core()->settings->props[ $element ] ) ) {
				$remove_types[] = $type;
			}
		}

		if ( empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_map'] ) || \MyHomeCore\My_Home_Core()->settings->props['mh-estate_map'] != 'small' ) {
			$remove_types[] = Estate_Element::MAP;
		}

//		if ( empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_slider'] ) || \MyHomeCore\My_Home_Core()->settings->props['mh-estate_slider'] == 'single-estate-slider' ) {
//			$remove_types[] = Estate_Element::GALLERY;
//		}

		$elements_updated = array();

		foreach ( $elements as $element ) {
			if ( ! in_array( $element['type'], $remove_types ) ) {
				$elements_updated[] = $element;
			}
		}

		update_option( self::OPTION_KEY, $elements_updated );
	}

	/**
	 * @param Text_Area_Attribute $attribute
	 */
	public function delete_textarea( $attribute ) {
		$elements = self::get();
		foreach ( $elements as $key => $element ) {
			if ( $element['slug'] == $attribute->get_slug() ) {
				unset( $elements[ $key ] );
			}
		}

		update_option( self::OPTION_KEY, $elements );
	}

	/**
	 * @param Widgets_Attribute $attribute
	 */
	public function delete_widgets( $attribute ) {
		$elements = self::get();
		foreach ( $elements as $key => $element ) {
			if ( $element['slug'] == $attribute->get_slug() ) {
				unset( $elements[ $key ] );
			}
		}

		update_option( self::OPTION_KEY, $elements );
	}

}