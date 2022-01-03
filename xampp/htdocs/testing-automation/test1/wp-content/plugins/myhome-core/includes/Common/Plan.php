<?php

namespace MyHomeCore\Common;

/**
 * Class Plan
 * @package MyHomeCore\Common
 */
class Plan {

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $image;

	/**
	 * @var int
	 */
	private $image_id;

	/**
	 * Plan constructor.
	 *
	 * @param string $label
	 * @param string $image
	 * @param int $image_id
	 */
	public function __construct( $label, $image, $image_id ) {
		$this->label    = $label;
		$this->image    = $image;
		$this->image_id = intval( $image_id );
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
	public function get_image() {
		return $this->image;
	}

	/**
	 * @return int
	 */
	public function get_image_id() {
		return $this->image_id;
	}

	/**
	 * @param array $data
	 *
	 * @return bool|Plan
	 */
	public static function get_from_acf_data( $data ) {
		$label    = $data['estate_plans_name'];
		$image    = isset( $data['estate_plans_image']['url'] ) ? $data['estate_plans_image']['url'] : '';
		$image_id = isset( $data['estate_plans_image']['id'] ) ? $data['estate_plans_image']['id'] : 0;

		return new Plan( $label, $image, $image_id );
	}

	/**
	 * @return array
	 */
	public function get_data() {
		return array(
			'label'    => $this->get_label(),
			'image'    => $this->get_image(),
			'image_id' => $this->get_image_id()
		);
	}

}