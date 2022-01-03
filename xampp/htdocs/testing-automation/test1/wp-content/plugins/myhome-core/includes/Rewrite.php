<?php

namespace MyHomeCore;


use MyHomeCore\Common\Breadcrumbs\Breadcrumbs;

/**
 * Class Rewrite
 * @package MyHomeCore
 */
class Rewrite {

	/**
	 * Rewrite constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'homepage_listing_rewrite' ) );
		add_filter( 'redirect_canonical', array( $this, 'homepage_listing_canonical' ), 10, 2 );
		add_action( 'init', array( $this, 'flush_rewrite_rules' ), 100 );
		add_filter( 'init', array( $this, 'estate_attributes' ) );
	}

	public function estate_attributes() {
		if ( empty( My_Home_Core()->settings->props['mh-breadcrumbs'] ) ) {
			return;
		}
		
		$attributes = Breadcrumbs::get_attributes();
		$slug       = \MyHomeCore\My_Home_Core()->settings->get( 'estate-slug' );
		if ( empty( $slug ) ) {
			$slug = 'properties';
		}
		$match   = 'index.php?post_type=estate';
		$counter = 1;

		foreach ( $attributes as $attribute ) {
			$slug  .= '/([^/]+)';
			$match .= '&' . $attribute->get_slug() . '=$matches[' . $counter . ']';
			$counter ++;
		}
	}

	public function homepage_listing_rewrite() {
		$homepage_id = get_option( 'page_on_front' );
		add_rewrite_rule( '^mh/?$', 'index.php?page_id=' . $homepage_id, 'top' );
	}

	public function homepage_listing_canonical( $redirect_url, $requested_url ) {
		$homepage_id = get_option( 'page_on_front' );
		$post        = get_post();
		if ( is_page() && $post->ID == $homepage_id ) {
			$url = explode( 'mh/', $requested_url );
			if ( ! isset( $url[1] ) || mb_strlen( $url[1], 'UTF-8' ) == 0 ) {
				return get_the_permalink( $post->ID );
			}

			return get_the_permalink( $post->ID ) . 'mh/' . $url[1];
		}

		return $redirect_url;
	}

	public function flush_rewrite_rules() {
		if ( false !== ( $check = get_transient( 'myhome_flush_rewrite_rules' ) ) && $check ) {
			flush_rewrite_rules();
			set_transient( 'myhome_flush_rewrite_rules', false );
		}
	}

}