<?php

namespace MyHomeCore\Components\Save_Search;


use MyHomeCore\Api\Estates_Api;

/**
 * Class Save_Search
 * @package MyHomeCore\Components\Save_Search
 */
class Save_Search {

	const OPTION_CHECK = 'myhome_saved_searches_check_date';
	const OPTION_HASH = 'myhome_saved_searches_check_date_hash';

	private $check_date;

	/**
	 * Save_Search constructor.
	 */
	public function __construct() {
		$this->check_date = Save_Search::get_last_check();
		$this->check_date = '2017-01-01';
	}

	/**
	 * @return \string
	 */
	public static function get_last_check() {
		$last_check = get_option( Save_Search::OPTION_CHECK );
		if ( empty( $last_check ) ) {
			$last_check = date( "Y-m-d H:i:s" );
		}

		return $last_check;
	}

	public function init() {
		foreach ( get_users() as $user ) {
			/* @var \WP_User $user */
			$saved_searches = get_user_meta( $user->ID, 'myhome_searches', true );
			if ( empty( $saved_searches ) ) {
				continue;
			}

			$this->check_user( $user, $saved_searches );
		}

		update_option( Save_Search::OPTION_CHECK, date( "Y-m-d H:i:s" ) );
	}

	/**
	 * @param \WP_User $user
	 * @param array    $searches
	 */
	public function check_user( \WP_User $user, $searches ) {
		foreach ( $searches as $search ) {
			$params                    = $search['data'];
			$params['published_after'] = $this->check_date;
			$params['limit']           = - 1;
			$params['map']             = 'true';

			$results = Estates_Api::get( $params );
			if ( $results['found_results'] > 0 ) {
				$this->send_email( $user, $results['results'], $search );
			}
		}
	}

	/**
	 * @param \WP_User $user
	 * @param          $results
	 * @param          $search
	 */
	public function send_email( \WP_User $user, $results, $search ) {
		$ids = array();
		foreach ( $results as $property ) {
			$ids[] = $property['id'];
		}

		$ids     = implode( ',', $ids );
		$title   = sprintf( esc_html__( '%d new properties - %s', 'myhome-core' ), count( $results ), $search['name'] );
		$message = '<a href="' . get_post_type_archive_link( 'estate' ) . '?property_ids=' . esc_attr( $ids ) . '">' . esc_html__( 'Click', 'myhome-core' ) . '</a>';
		ob_start();
		?>
		<h3><?php esc_html( $search['name'] ); ?></h3>

		<p><?php echo sprintf( esc_html__( 'Found %d new properties.', 'myhome-core' ), count( $results ) ); ?></p>

		<a href="'<?php echo esc_url( get_post_type_archive_link( 'estate' ) . '?property_ids=' . $ids ); ?>">
			<?php esc_html_e( 'See results', 'myhome-core' ); ?>
		</a>
		<?php
		ob_get_clean();
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $user->user_email, $title, $message, $headers );
	}

	/**
	 * @return string
	 */
	public static function create_hash() {
		$key  = 'myhome_saved_searches_hash_' . time() . '_' . rand( 1, 1000000 );
		$hash = md5( $key );
		update_option( Save_Search::OPTION_HASH, $hash );

		return $hash;
	}

	/**
	 * @return \string
	 */
	public static function get_hash() {
		$hash = get_option( Save_Search::OPTION_HASH );
		if ( empty( $hash ) ) {
			$hash = Save_Search::create_hash();
		}

		return $hash;
	}

}