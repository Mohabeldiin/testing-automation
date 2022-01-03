<?php

namespace MyHomeCore\Common;


/**
 * Class Image
 * @package MyHomeCore\Common
 */
class Image {

	const STANDARD = 'standard';
	const ADDITIONAL = 'additional';
	const SQUARE = 'square';

	/**
	 * @param int|null $thumbnail_id
	 * @param string   $size
	 * @param string   $alt
	 * @param string   $class
	 */
	public static function the_image( $thumbnail_id = null, $size = self::STANDARD, $alt = '', $class = '' ) {
		$thumbnail_id = is_null( $thumbnail_id ) ? get_post_thumbnail_id() : $thumbnail_id;
		$prefix       = 'myhome-';

		if ( $size == self::STANDARD
		     || $size == self::SQUARE
		) {
			$thumbnail_size = $prefix . $size . '-xxxs';
		} else {
			$thumbnail_size = $prefix . $size;
		}

		$image_srcset = \MyHomeCore\My_Home_Core()->images->get( $thumbnail_id, $thumbnail_size );
		ob_start();
		?>
		<img
			<?php if ( ! empty( $image_srcset ) ) : ?>
				data-srcset="<?php echo esc_attr( $image_srcset ); ?>"
				class="lazyload <?php echo esc_attr( $class ); ?>"
				data-sizes="auto"
			<?php else : ?>
				src="<?php echo esc_url( wp_get_attachment_image_url( $thumbnail_id, 'full' ) ); ?>"
			<?php endif; ?>
			alt="<?php echo esc_attr( $alt ); ?>"
		>
		<?php
		echo ob_get_clean();
	}

}