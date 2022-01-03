<?php

namespace MyHomeCore\Components\Listing;


/**
 * Class Listing
 * @package MyHomeCore\Listing
 */
class Listing {

	/**
	 * @var Listing_Settings
	 */
	protected $settings;

	/**
	 * Listing constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		$this->settings = new Listing_Settings( $args );
	}

	/**
	 * @param $content
	 */
	public function display( $content = null ) {
		$content = wpb_js_remove_wpautop( $content );
		$class   = '';
		$config  = $this->settings->get_config();
		if ( $config['search_form_position'] == 'left' ) {
			$class = 'mh-search-left';
		} elseif ( $config['search_form_position'] == 'right' ) {
			$class = 'mh-search-right';
		}
		ob_start();
		?>
		<listing-grid
			id="myhome-listing-grid"
			class="<?php echo esc_attr( $class ); ?>"
			config-key='<?php echo esc_attr( $config['key'] ); ?>'
		>
			<?php if ( ! empty( $content ) ) : ?>
				<div class="mh-sidebar-more">
					<div class="mh-sidebar-more__content">
						<?php echo myhome_filter( $content ); ?>
					</div>
					<div class="mh-sidebar-more__info">
						<h3><?php echo esc_html__( 'Placeholder', 'myhome-core' ); ?></h3>
						<div><?php echo esc_html__( 'To add or modify elements in this area, please use the "Backend Editor"', 'myhome-core' ); ?></div>
					</div>
				</div>
			<?php endif; ?>
		</listing-grid>
		<?php

		echo ob_get_clean();
	}

}