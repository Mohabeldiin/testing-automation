<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class Shortcode
 * @package MyHomeCore\Shortcodes
 */
abstract class Shortcode {

	const TEMPLATES = 'templates/shortcodes/';

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $slug;

	/**
	 * @var string
	 */
	protected $icon;

	/**
	 * @var string
	 */
	protected $template;

	/**
	 * @var bool
	 */
	protected $is_container;

	/**
	 * @var array
	 */
	protected $as_parent;

	/**
	 * @var string
	 */
	protected $js_view;

	/**
	 * @var string
	 */
	protected $group;

	/**
	 * @param array       $args
	 * @param string|null $content
	 *
	 * @return string
	 */
	abstract public function display( $args = array(), $content = null );

	/**
	 * @return array
	 */
	public abstract function get_vc_params();

	/**
	 * Shortcode constructor.
	 *
	 * @param string  $name
	 * @param string  $slug
	 * @param string  $icon
	 * @param string  $template
	 * @param bool    $is_container
	 * @param array   $as_parent
	 * @param string  $js_view
	 * @param \string $group
	 */
	public function __construct( $name, $slug, $icon, $template, $is_container = false, $as_parent = array(), $js_view = '', $group ) {
		$this->name         = $name;
		$this->slug         = $slug;
		$this->icon         = $icon;
		$this->template     = $template;
		$this->is_container = $is_container;
		$this->as_parent    = $as_parent;
		$this->js_view      = $js_view;
		$this->group        = $group;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * @return string
	 */
	public function get_template() {
		ob_start();
		get_template_part( self::TEMPLATES . $this->template );

		return ob_get_clean();
	}

	/**
	 * @return array
	 */
	public function vc_settings() {
		$vc_settings = array(
			'name'         => $this->name,
			'base'         => $this->slug,
			'icon'         => $this->icon,
			'category'     => $this->group,
			'params'       => $this->get_vc_params(),
			'is_container' => $this->is_container,
			'js_view'      => $this->js_view
		);

		if ( ! empty( $this->as_parent ) ) {
			$vc_settings['as_parent'] = $this->as_parent;
		}

		return $vc_settings;
	}

	/**
	 * @param \stdClass $shortcode
	 *
	 * @return Shortcode
	 */
	public static function create( $shortcode ) {
		$sc = new \ReflectionClass( $shortcode->class );

		return $sc->newInstance(
			$shortcode->name,
			$shortcode->slug,
			$shortcode->icon,
			$shortcode->template,
			$shortcode->is_container,
			$shortcode->as_parent,
			$shortcode->js_view,
			$shortcode->group
		);
	}
}