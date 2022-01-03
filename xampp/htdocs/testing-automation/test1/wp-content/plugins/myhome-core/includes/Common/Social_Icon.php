<?php

namespace MyHomeCore\Common;


/**
 * Class Social_Icon
 * @package MyHomeCore\Common
 */
class Social_Icon {

	const TEMPLATE = 'templates/common/social-icon';

	/**
	 * @var string
	 */
	private $css_class;

	/**
	 * @var string
	 */
	private $link;

	/**
	 * Social_Icon constructor.
	 *
	 * @param string $css_class
	 * @param string $link
	 */
	public function __construct( $css_class, $link = '' ) {
		$this->css_class = $css_class;
		$this->link      = $link;
	}

	/**
	 * @return string
	 */
	public function get_css_class() {
		return $this->css_class;
	}

	/**
	 * @return bool
	 */
	public function has_link() {
		return ! empty( $this->link );
	}

	/**
	 * @return string
	 */
	public function get_link() {
		return $this->link;
	}

	public function display() {
		global $myhome_social_icon;
		$myhome_social_icon = $this;
		get_template_part( self::TEMPLATE );
	}

	/**
	 * @return array
	 */
	public static function get_icons() {
		return array(
			'facebook',
			'twitter',
			'instagram',
			'linkedin',
		);
	}

}