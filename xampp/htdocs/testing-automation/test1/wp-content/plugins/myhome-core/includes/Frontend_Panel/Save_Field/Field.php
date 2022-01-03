<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;


use MyHomeCore\Frontend_Panel\Panel_Field;

/**
 * Class Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
abstract class Field {

	protected $field;

	/**
	 * Field constructor.
	 *
	 * @param $field
	 */
	public function __construct( $field ) {
		$this->field = $field;
	}

	/**
	 * @param int   $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public abstract function save( $property_id, $property_data );

	/**
	 * @param $field
	 *
	 * @return Field
	 * @throws \Exception
	 */
	public static function get_instance( $field ) {
		if ( $field['type'] == Panel_Field::TYPE_TITLE ) {
			return new Title_Field( $field );
		} elseif ( $field['type'] == Panel_Field::TYPE_DESCRIPTION ) {
			return new Description_Field( $field );
		} elseif ( $field['type'] == Panel_Field::TYPE_TEXT ) {
			return new Text_Field( $field );
		} elseif ( $field['type'] == Panel_Field::TYPE_NUMBER ) {
			return new Number_Field( $field );
		} elseif ( $field['type'] == Panel_FIELD::TYPE_GALLERY ) {
			return new Gallery_Field( $field );
		} elseif ( $field['type'] == Panel_Field::TYPE_IMAGE ) {
			return new Image_Field( $field );
		} elseif ( $field['type'] == Panel_Field::TYPE_LOCATION ) {
			return new Location_Field( $field );
		} elseif ( $field['type'] == Panel_Field::TYPE_ADDITIONAL_FEATURES ) {
			return new Additional_Features_Field( $field );
		} elseif ( $field['type'] == Panel_Field::TYPE_PLANS ) {
			return new Plans_Field( $field );
		} elseif ( $field['type'] == Panel_Field::TYPE_ATTACHMENTS ) {
			return new Attachments_Field( $field );
		} elseif ( $field['type'] == Panel_Field::TYPE_VIDEO ) {
			return new Video_Field( $field );
		} elseif ( $field['type'] == Panel_Field::TYPE_VIRTUAL_TOUR ) {
			return new Virtual_Tour_Field( $field );
		} elseif ( $field['type'] == Panel_Field::TYPE_FEATURED ) {
			return new Featured_Field( $field );
		}

		throw new \Exception( 'Field type not found' );
	}

	public function get_slug() {
		return $this->field['slug'];
	}

}