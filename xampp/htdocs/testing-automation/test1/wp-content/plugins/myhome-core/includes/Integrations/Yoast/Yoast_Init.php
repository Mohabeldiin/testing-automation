<?php

namespace MyHomeCore\Integrations\Yoast;

/**
 * Class Yoast_Init
 * @package MyHomeCore\Integrations\Yoast
 */
class Yoast_Init {

	const READABILITY_ANALYSIS_NOTICE_OPTION = 'myhome_yoast_readability_analysis_notice';

	/**
	 * Yoast_Init constructor.
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'readability_analysis_notice' ) );
		add_action( 'wp_ajax_myhome_yoast_dismiss_notice', array( $this, 'dismiss_readability_analysis_notice' ) );
	}

	public function dismiss_readability_analysis_notice() {
		update_option( self::READABILITY_ANALYSIS_NOTICE_OPTION, 1 );
		wp_die();
	}

	public function readability_analysis_notice() {
		$option = get_option( self::READABILITY_ANALYSIS_NOTICE_OPTION );
		if ( empty( $option ) ) :
			?>
			<div class="notice notice-info is-dismissible mh-dismiss-yoast-notice">
				<p>
					<?php
					echo wp_kses_post(
						__(
							'<strong>To make Yoast SEO fully compatible with MyHome, you need to take one little action</strong>
						<br> Please go to Yoast Settings. Left /wp-admin/ Sidebar >> SEO >> Features and change Readability analysis to "Disabled". Scroll down and click the "Save" button.
						<br> You can read more about it <a href="https://myhometheme.zendesk.com/hc/en-us/articles/115002007653" target="_blank">here.</a>',
							'myhome-core'
						)
					);
					?>
				</p>
			</div>
			<?php
		endif;
	}

}