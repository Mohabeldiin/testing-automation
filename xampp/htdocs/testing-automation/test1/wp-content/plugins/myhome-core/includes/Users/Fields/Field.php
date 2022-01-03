<?php

namespace MyHomeCore\Users\Fields;


/**
 * Class Field
 * @package MyHomeCore\Users\Fields
 */
class Field {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $slug;

	/**
	 * @var int
	 */
	private $user_id;

	/**
	 * @var bool
	 */
	private $is_required;

	/**
	 * @var bool
	 */
	private $is_link = false;

	/**
	 * @var bool
	 */
	private $use_for_register = false;

	/**
	 * Field constructor.
	 *
	 * @param string $name
	 * @param string $slug
	 * @param int $user_id
	 * @param bool $is_required
	 * @param bool $is_link
	 * @param bool $use_for_register
	 */
	public function __construct( $name, $slug, $user_id, $is_required = false, $is_link = false, $use_for_register = false ) {
		$this->name             = $name;
		$this->slug             = $slug;
		$this->user_id          = intval( $user_id );
		$this->is_required      = filter_var( $is_required, FILTER_VALIDATE_BOOLEAN );
		$this->is_link          = filter_var( $is_link, FILTER_VALIDATE_BOOLEAN );
		$this->use_for_register = filter_var( $use_for_register, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * @return string
	 */
	public function get_name() {
		if ( empty( $this->name ) ) {
			return '';
		}

		$name = apply_filters(
			'wpml_translate_single_string',
			$this->name,
			'Agents',
			'mh-agent-field-' . $this->get_slug() . '-' . $this->user_id
		);

		return $name;
	}

	/**
	 * @return bool
	 */
	public function has_value() {
		$value = $this->get_value();

		return ! empty( $value );
	}

	/**
	 * @return string
	 */
	public function get_value() {
		$value = get_field( 'myhome_agent_' . $this->slug, 'user_' . $this->user_id );

		if ( empty( $value ) ) {
			return '';
		}

		$value = apply_filters(
			'wpml_translate_single_string',
			$value,
			'Agents',
			'mh-agent-field-' . $this->get_slug() . '-' . $this->user_id . '-value'
		);

		if ( $this->is_link() && ! empty( $value ) ) {
			return str_replace( array( 'http://', 'https://', 'www.' ), '', $value );
		}

		return $value;
	}

	/**
	 * @return bool
	 */
	public function is_required() {
		return $this->is_required;
	}

	/**
	 * @return bool
	 */
	public function is_link() {
		return $this->is_link;
	}

	/**
	 * @return string
	 */
	public function get_link() {
		if ( $this->is_link() ) {
			return $this->get_value();
		}

		return '';
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @return array
	 */
	public function get_data() {
		return array(
			'name'             => $this->get_name(),
			'slug'             => $this->get_slug(),
			'value'            => $this->get_value(),
			'link'             => $this->get_link(),
			'is_required'      => $this->is_required(),
			'is_link'          => $this->is_link(),
			'use_for_register' => $this->use_for_register()
		);
	}

	/**
	 * @return bool
	 */
	public function use_for_register() {
		return $this->use_for_register;
	}

}