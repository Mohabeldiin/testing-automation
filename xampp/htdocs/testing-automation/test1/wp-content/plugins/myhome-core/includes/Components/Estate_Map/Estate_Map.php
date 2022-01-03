<?php

namespace MyHomeCore\Components\Estate_Map;


use MyHomeCore\Estates\Estate;
use MyHomeCore\Estates\Estate_Factory;
use MyHomeCore\Estates\Estates;

/**
 * Class Estate_Map
 * @package MyHomeCore\Common\Estate_Map
 */
class Estate_Map {

	/**
	 * @var Estate
	 */
	private $estate;

	/**
	 * Estate_Map constructor.
	 *
	 * @param Estate $estate
	 */
	public function __construct( Estate $estate ) {
		$this->estate = $estate;
	}

	public function display() {
		$config = array(
			'estate'            => $this->estate->get_marker_data(),
			'estatesNear'       => $this->get_estates_near(),
			'estatesNearActive' => empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate-show_near_active'] ) ? false : \MyHomeCore\My_Home_Core()->settings->props['mh-estate-show_near_active'],
			'mapStyle'          => empty( \MyHomeCore\My_Home_Core()->settings->props['mh-map-style'] ) ? 'gray' : \MyHomeCore\My_Home_Core()->settings->props['mh-map-style'],
			'mapType'           => \MyHomeCore\My_Home_Core()->settings->props['mh-estate_map']
		);

		$zoom = (int) trim( \MyHomeCore\My_Home_Core()->settings->get( 'estate_zoom' ) );

		ob_start();
		?>
        <div>
            <estate-map
                    id="myhome-estate-map"
                    :config='<?php echo esc_attr( json_encode( $config ) ); ?>'
				<?php if ( ! empty( $zoom ) )  : ?>
                    :zoom="<?php echo esc_attr( $zoom ); ?>"
				<?php endif; ?>
            ></estate-map>
        </div>
		<?php
		echo ob_get_clean();
	}

	/**
	 * @return array
	 */
	private function get_estates_near() {
		$estates_factory = new Estate_Factory();
		$estates_factory->set_limit( Estate_Factory::NO_LIMIT );
		$estates_factory->set_estates__in( $this->estate->get_near_estates_ids() );
		$estates = $estates_factory->get_results();

		return $estates->get_data( Estates::MARKER_DATA );
	}

}