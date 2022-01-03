<?php

namespace MyHomeCore\Integrations\Facebook;


/**
 * Class Facebook
 * @package MyHomeCore\Integrations\Facebook
 */
class Facebook {

	/**
	 * Facebook constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'single_property_page' ) );
	}

	public function single_property_page() {
		if ( ! is_singular( 'estate' ) ) {
			return;
		}

		/**
		 * @var $post \WP_Post
		 */
		global $post;

		?>
		<meta property="og:url" content="<?php the_permalink( $post ); ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="<?php echo esc_attr( $post->post_name ); ?>" />
		<meta property="og:description" content="<?php echo esc_attr( $post->post_excerpt ); ?>" />
		<?php if ( has_post_thumbnail( $post ) ) : ?>
			<meta property="og:image" content="<?php echo esc_attr( get_the_post_thumbnail_url( $post ) ); ?>" />
		<?php endif;
	}

}