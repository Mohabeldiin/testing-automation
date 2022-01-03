<?php

namespace MyHomeCore\Common;

/**
 * Class Attachment
 * @package MyHomeCore\Common
 */
class Attachment {

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $icon;

	/**
	 * Attachment constructor.
	 *
	 * @param string $label
	 * @param string $file
	 * @param string $type
	 * @param string $icon
	 */
	public function __construct( $label, $file, $type, $icon ) {
		$this->label = $label;
		$this->file  = $file;
		$this->type  = $type;
		$this->icon  = $icon;
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
	public function get_icon() {
		switch ( $this->type ) {
			case 'image':
				return $this->icon;
				break;
			default:
				return $this->icon;
		}
	}

	/**
	 * @return string
	 */
	public function get_file() {
		return $this->file;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param $data
	 *
	 * @return bool|Attachment
	 */
	public static function get_from_acf_data( $data ) {
		if ( empty( $data['estate_attachment_name'] ) || empty( $data['estate_attachment_file']['url'] )
		     || empty( $data['estate_attachment_file']['type'] ) || empty( $data['estate_attachment_file']['icon'] )
		) {
			return false;
		}

		$label = $data['estate_attachment_name'];
		$file  = $data['estate_attachment_file']['url'];
		$type  = $data['estate_attachment_file']['type'];
		$icon  = $data['estate_attachment_file']['icon'];

		return new Attachment( $label, $file, $type, $icon );
	}

}