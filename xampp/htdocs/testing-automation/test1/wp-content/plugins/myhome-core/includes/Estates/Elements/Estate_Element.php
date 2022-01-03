<?php

namespace MyHomeCore\Estates\Elements;


use MyHomeCore\Estates\Estate;

/**
 * Class Estate_Element
 * @package MyHomeCore\Estates\Elements
 */
abstract class Estate_Element {

	const DESCRIPTION = 'description';
	const VIDEO = 'video';
	const VIRTUAL_TOUR = 'virtual_tour';
	const PLANS = 'plans';
	const ATTACHMENTS = 'attachments';
	const ADDITIONAL_FEATURES = 'additional_features';
	const RELATED_PROPERTIES = 'related_properties';
	const MAP = 'map';
	const ATTRIBUTES = 'attributes';
	const TEXT = 'text';
	const GALLERY = 'gallery';
	const WIDGETS = 'Widgets';
	const INFO = 'info';
	const TEXTAREA = 'textarea';
	const SHORTCODE = 'shortcode';
	const CUSTOM = 'custom';
	const TEMPLATE_PATH = 'templates/single-estate-partials/';

	public static $IS_SINGLE = true;

	/**
	 * @return string
	 */
	abstract protected function get_template_name();

	/**
	 * @var array
	 */
	protected $element;

	/**
	 * @var Estate
	 */
	protected $estate;

	/**
	 * Estate_Element constructor.
	 *
	 * @param array  $element
	 * @param Estate $estate
	 */
	public function __construct( $element, Estate $estate ) {
		$this->element = $element;
		$this->estate  = $estate;
	}

	/**
	 * @param        $element
	 * @param Estate $estate
	 *
	 * @return Estate_Element
	 * @throws \ErrorException
	 */
	public static function get_instance( $element, $estate ) {
		if ( $element['type'] == self::VIDEO ) {
			return new Video_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::VIRTUAL_TOUR ) {
			return new Virtual_Tour_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::PLANS ) {
			return new Plans_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::DESCRIPTION ) {
			return new Description_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::ATTACHMENTS ) {
			return new Attachments_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::ATTRIBUTES ) {
			return new Attributes_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::RELATED_PROPERTIES ) {
			return new Related_Properties_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::MAP ) {
			return new Map_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::TEXTAREA ) {
			return new Text_Area_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::GALLERY ) {
			return new Gallery_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::WIDGETS ) {
			return new Widgets_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::INFO ) {
			return new Info_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::SHORTCODE ) {
			return new Shortcode_Estate_Element( $element, $estate );
		} elseif ( $element['type'] == self::CUSTOM ) {
			return new Custom_Estate_Element( $element, $estate );
		}

		throw new \ErrorException( die( 'Estate element not found.' ) );
	}

	/**
	 * @return bool
	 */
	public function is_single() {
		return self::$IS_SINGLE;
	}

	/**
	 * @return bool
	 */
	public function has_label() {
		return ! empty( $this->element['label'] );
	}

	/**
	 * @return string
	 */
	public function get_label() {
		if ( ! isset( $this->element['label'] ) ) {
			return '';
		}

		return apply_filters( 'wpml_translate_single_string', $this->element['label'], 'myhome-core', 'Property element - ' . $this->element['label'] );
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return isset( $this->element['slug'] ) ? $this->element['slug'] : '';
	}

	/**
	 * @return string
	 */
	public function get_template() {
		return self::TEMPLATE_PATH . $this->get_template_name();
	}

}