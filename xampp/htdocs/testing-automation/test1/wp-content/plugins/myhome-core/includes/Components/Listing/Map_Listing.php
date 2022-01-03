<?php

namespace MyHomeCore\Components\Listing;


/**
 * Class Map_Listing
 * @package MyHomeCore\Components\Listing
 */
class Map_Listing {

	/**
	 * @var Listing_Map_Settings
	 */
	protected $settings;

	/**
	 * @var float
	 */
	private $lat;

	/**
	 * @var float
	 */
	private $lng;

	/**
	 * @var float
	 */
	private $zoom;

	/**
	 * Map_Listing constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args ) {
		$this->settings = new Listing_Map_Settings( $args );

		if ( isset( $args['lat'] ) && ! empty( $args['lat'] ) ) {
			$this->lat = floatval( $args['lat'] );
		}

		if ( isset( $args['lng'] ) && ! empty( $args['lng'] ) ) {
			$this->lng = floatval( $args['lng'] );
		}

		if ( isset( $args['zoom'] ) && ! empty( $args['zoom'] ) ) {
			$this->zoom = intval( $args['zoom'] );
		}
	}

	public function display() {
		ob_start();
		?>
		<listing-map
			id="myhome-listing-map"
			config-key="<?php echo esc_attr( $this->settings->get_config() ); ?>"
			<?php if ( ! empty( $this->lat ) && ! empty( $this->lng ) ) : ?>
				:center="<?php echo htmlspecialchars( json_encode( [ 'lat' => $this->lat, 'lng' => $this->lng ] ) ); ?>"
			<?php endif; ?>
			<?php if ( ! empty( $this->zoom ) ) : ?>
				:zoom="<?php echo esc_attr( $this->zoom ); ?>"
			<?php endif; ?>
		>
		</listing-map>
		<?php
		echo ob_get_clean();
	}

}