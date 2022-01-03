<?php

namespace MyHomeCore\Frontend_Panel;


use MyHomeCore\Attributes\Attribute;

/**
 * Class Panel_Field
 * @package MyHomeCore\Panel
 */
abstract class Panel_Field {

	const TYPE_LOCATION = 'location';
	const TYPE_TEXT = 'text';
	const TYPE_TEXT_AREA = 'text_area';
	const TYPE_NUMBER = 'number';
	const TYPE_GALLERY = 'gallery';
	const TYPE_IMAGE = 'image';
	const TYPE_TITLE = 'title';
	const TYPE_DESCRIPTION = 'description';
	const TYPE_PLANS = 'plans';
	const TYPE_FEATURED = 'featured';
	const TYPE_ADDITIONAL_FEATURES = 'additional_features';
	const TYPE_ATTACHMENTS = 'attachments';
	const TYPE_VIDEO = 'video';
	const TYPE_VIRTUAL_TOUR = 'virtual_tour';

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var string
	 */
	protected $instruction;

	/**
	 * @var Attribute
	 */
	protected $attribute;

	/**
	 * @var bool
	 */
	protected $required;

	/**
	 * Panel_Field constructor.
	 *
	 * @param string    $label
	 * @param string    $instructions
	 * @param Attribute $attribute
	 * @param bool      $required
	 */
	public function __construct( $label, $instructions, Attribute $attribute, $required = false ) {
		$this->label       = $label;
		$this->instruction = $instructions;
		$this->attribute   = $attribute;
		$this->required    = $required;
	}


}