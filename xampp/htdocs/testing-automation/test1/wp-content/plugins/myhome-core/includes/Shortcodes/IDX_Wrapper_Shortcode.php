<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class IDX_Wrapper_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class IDX_Wrapper_Shortcode extends Shortcode {

	/**
	 * @param array $args
	 * @param null  $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		if ( isset( $args['type'] ) ) {
			$type = $args['type'];
		} else {
			$type = 'global';
		}

		$classes = array( 'myhome-idx-wrapper', 'myhome-idx-wrapper__' . $type );

		if ( $type == 'basic' ) {
			$classes = array();
		} else {
			$type_key     = strtolower( $type );
			$template_key = 'template_' . strtolower( $type_key );
			if ( isset( $args[ $template_key ] ) ) {
				$template  = $args[ $template_key ];
				$classes[] = 'myhome-idx-wrapper__' . $type_key . '-' . $template;

				$version_key = $template_key . '_version_' . strtolower( $template );
				if ( isset( $args[ $version_key ] ) ) {
					$version   = $args[ $version_key ];
					$classes[] = 'myhome-idx-wrapper__' . $type_key . '-' . $template . '-' . $version;
				}
			}
		}

		global $myhome_idx_wrapper;
		$myhome_idx_wrapper['class'] = implode( ' ', $classes );

		return $this->get_template();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		$pages = array(
			array(
				'key'   => 'basic',
				'label' => esc_html__( 'Basic (simple wrapper)', 'myhome-core' )
			),
			array(
				'key'       => 'search_page',
				'label'     => esc_html__( 'Search Page', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'searchBase',
						'label'    => esc_html__( 'Basic', 'myhome-core' ),
						'versions' => array(
							'1005' => '1,005',
							'1004' => '1,004',
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'scrollCheckboxes',
						'label'    => esc_html__( 'Checkboxes', 'myhome-core' ),
						'versions' => array(
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'searchLegacy',
						'label'    => esc_html__( 'Legacy', 'myhome-core' ),
						'versions' => array(
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'mobileFirstSearch',
						'label'    => esc_html__( 'Mobile First Search', 'myhome-core' ),
						'versions' => array(
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'simple',
						'label'    => esc_html__( 'Simple', 'myhome-core' ),
						'versions' => array(
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'searchStandard',
						'label'    => esc_html__( 'Standard', 'myhome-core' ),
						'versions' => array(
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					)
				)
			),
			array(
				'key'       => 'map_search_page',
				'label'     => esc_html__( 'Map Search Page', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'mapsearch',
						'label'    => esc_html__( 'Map Search', 'myhome-core' ),
						'versions' => array(
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'mobileFirstMapSearch',
						'label'    => esc_html__( 'Responsive Map Search', 'myhome-core' ),
						'versions' => array(
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					)
				)
			),
			array(
				'key'   => 'agent_bio_listings',
				'label' => esc_html__( 'Agent Bio - Listings', 'myhome-core' ),
			),
			array(
				'key'   => 'featured',
				'label' => esc_html__( 'Featured', 'myhome-core' ),
			),
			array(
				'key'   => 'featured_open_houses',
				'label' => esc_html__( 'Featured Open Houses', 'myhome-core' ),
			),
			array(
				'key'   => 'featured_virtual_tours',
				'label' => esc_html__( 'Featured Virtual Tours', 'myhome-core' ),
			),
			array(
				'key'       => 'results',
				'label'     => esc_html__( 'Results', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'resultsContent',
						'label'    => esc_html__( 'Content', 'myhome-core' ),
						'versions' => array(
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'resultsContent2',
						'label'    => esc_html__( 'Content 2', 'myhome-core' ),
						'versions' => array(
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'mobileFirstResults',
						'label'    => esc_html__( 'Mobile First Results', 'myhome-core' ),
						'versions' => array(
							'1006' => '1,006',
							'1005' => '1,005',
							'1004' => '1,004',
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'resultsNarrow',
						'label'    => esc_html__( 'Narrow', 'myhome-core' ),
						'versions' => array(
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'resultsPlatinumGrid',
						'label'    => esc_html__( 'Platinum Grid', 'myhome-core' ),
						'versions' => array(
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'resultsSpread',
						'label'    => esc_html__( 'Spread', 'myhome-core' ),
						'versions' => array(
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					)
				)
			),
			array(
				'key'   => 'sold_pending',
				'label' => esc_html__( 'Sold ', 'myhome-core' ),
			),
			array(
				'key'   => 'supplemental',
				'label' => esc_html__( 'Supplemental', 'myhome-core' ),
			),
			array(
				'key'       => 'details',
				'label'     => esc_html__( 'Details', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'detailsContent',
						'label'    => esc_html__( 'Content', 'myhome-core' ),
						'versions' => array(
							'1007' => '1.007',
							'1006' => '1.006',
							'1005' => '1.005',
							'1004' => '1.004',
							'1003' => '1.003',
							'1002' => '1.002',
							'1001' => '1.001',
							'1000' => '1.000',
						)
					),
					array(
						'key'      => 'detailsDynamic',
						'label'    => esc_html__( 'Dynamic', 'myhome-core' ),
						'versions' => array(
							'1008' => '1.008',
							'1007' => '1.007',
							'1006' => '1.006',
							'1005' => '1.005',
							'1004' => '1.004',
							'1003' => '1.003',
							'1002' => '1.002',
							'1001' => '1.001',
							'1000' => '1.000',
						)
					),
					array(
						'key'      => 'detailsGallery',
						'label'    => esc_html__( 'Gallery', 'myhome-core' ),
						'versions' => array(
							'1008' => '1.008',
							'1007' => '1.007',
							'1006' => '1.006',
							'1005' => '1.005',
							'1004' => '1.004',
							'1003' => '1.003',
							'1002' => '1.002',
							'1001' => '1.001',
							'1000' => '1.000',
						)
					),
					array(
						'key'      => 'detailsGrid',
						'label'    => esc_html__( 'Grid', 'myhome-core' ),
						'versions' => array(
							'1007' => '1.007',
							'1006' => '1.006',
							'1005' => '1.005',
							'1004' => '1.004',
							'1003' => '1.003',
							'1002' => '1.002',
							'1001' => '1.001',
							'1000' => '1.000',
						)
					),
					array(
						'key'      => 'detailsHomeBasic700',
						'label'    => esc_html__( 'Home Basic', 'myhome-core' ),
						'versions' => array(
							'1007' => '1.007',
							'1006' => '1.006',
							'1005' => '1.005',
							'1004' => '1.004',
							'1003' => '1.003',
							'1002' => '1.002',
							'1001' => '1.001',
							'1000' => '1.000',
						)
					),
					array(
						'key'      => 'mobileFirstDetails',
						'label'    => esc_html__( 'Mobile First Details', 'myhome-core' ),
						'versions' => array(
							'1005' => '1.005',
							'1004' => '1.004',
							'1003' => '1.003',
							'1002' => '1.002',
							'1001' => '1.001',
							'1000' => '1.000',
						)
					),
				)
			),
			array(
				'key'   => 'link_showcase',
				'label' => esc_html__( 'Link Showcase', 'myhome-core' ),
			),
			array(
				'key'       => 'mortgage_calculator',
				'label'     => esc_html__( 'Mortgage Calculator', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'mobileFirstMortgage',
						'label'    => esc_html__( 'Mobile First Page', 'myhome-core' ),
						'versions' => array(
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'mortgage',
						'label'    => esc_html__( 'Mortgage', 'myhome-core' ),
						'versions' => array(
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
				)
			),
			array(
				'key'       => 'photo_gallery',
				'label'     => esc_html__( 'Photo Gallery', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'mobileFirstPhotoGallery',
						'label'    => esc_html__( 'Mobile First Photo Gallery', 'myhome-core' ),
						'versions' => array(
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'photogallery',
						'label'    => esc_html__( 'Photo Gallery', 'myhome-core' ),
						'versions' => array(
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					)
				)
			),
			array(
				'key'       => 'contact',
				'label'     => esc_html__( 'Contact', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'contact',
						'label'    => esc_html__( 'Contact', 'myhome-core' ),
						'versions' => array(
							'1004' => '1,004',
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'mobileFirstContact',
						'label'    => esc_html__( 'Mobile First Contact', 'myhome-core' ),
						'versions' => array(
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					)
				)
			),
			array(
				'key'       => 'home_valuation',
				'label'     => esc_html__( 'Home Valuation', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'contact',
						'label'    => esc_html__( 'Contact', 'myhome-core' ),
						'versions' => array(
							'1004' => '1,004',
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'mobileFirstContact',
						'label'    => esc_html__( 'Mobile First Contact', 'myhome-core' ),
						'versions' => array(
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					)
				)
			),
			array(
				'key'       => 'more_info',
				'label'     => esc_html__( 'More Info', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'contact',
						'label'    => esc_html__( 'Contact', 'myhome-core' ),
						'versions' => array(
							'1004' => '1,004',
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'mobileFirstContact',
						'label'    => esc_html__( 'Mobile First Contact', 'myhome-core' ),
						'versions' => array(
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					)
				)
			),
			array(
				'key'       => 'schedule_showing',
				'label'     => esc_html__( 'Schedule Showing', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'contact',
						'label'    => esc_html__( 'Contact', 'myhome-core' ),
						'versions' => array(
							'1004' => '1,004',
							'1003' => '1,003',
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					),
					array(
						'key'      => 'mobileFirstContact',
						'label'    => esc_html__( 'Mobile First Contact', 'myhome-core' ),
						'versions' => array(
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000',
						)
					)
				)
			),
			array(
				'key'       => 'my_account',
				'label'     => esc_html__( 'My Account', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'myaccount',
						'label'    => esc_html__( 'My Account', 'myhome-core' ),
						'versions' => array(
							'1000' => '1,000'
						)
					),
					array(
						'key'      => 'myListingsManager',
						'label'    => esc_html__( 'My Listings Manager', 'myhome-core' ),
						'versions' => array(
							'1000' => '1,000'
						)
					)
				)
			),
			array(
				'key'       => 'user_login',
				'label'     => esc_html__( 'User Login', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'mobileFirstUserLogin',
						'label'    => esc_html__( 'Mobile Responsive Login', 'myhome-core' ),
						'versions' => array(
							'1000' => '1,000'
						)
					),
					array(
						'key'      => 'userlogin',
						'label'    => esc_html__( 'User Login', 'myhome-core' ),
						'versions' => array(
							'1001' => '1,001',
							'1000' => '1,000'
						)
					)
				)
			),
			array(
				'key'       => 'user_signup',
				'label'     => esc_html__( 'User Signup', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'mobileFirstUserSignup',
						'label'    => esc_html__( 'Mobile Responsive Signup', 'myhome-core' ),
						'versions' => array(
							'1000' => '1,000'
						)
					),
					array(
						'key'      => 'usersignup',
						'label'    => esc_html__( 'User Signup', 'myhome-core' ),
						'versions' => array(
							'1002' => '1,002',
							'1001' => '1,001',
							'1000' => '1,000'
						)
					)
				)
			),
			array(
				'key'   => 'browser_by_city',
				'label' => esc_html__( 'Browse by City', 'myhome-core' ),
			),
			array(
				'key'   => 'sitemap',
				'label' => esc_html__( 'Sitemap', 'myhome-core' ),
			),
			array(
				'key'       => 'roster',
				'label'     => esc_html__( 'Roster', 'myhome-core' ),
				'templates' => array(
					array(
						'key'      => 'mobileFirstRoster',
						'label'    => esc_html__( 'Mobile First Roster', 'myhome-core' ),
						'versions' => array(
							'1001' => '1,001',
							'1000' => '1,000'
						)
					),
					array(
						'key'      => 'rosterContent',
						'label'    => esc_html__( 'Roster Content', 'myhome-core' ),
						'versions' => array(
							'1001' => '1,001',
							'1000' => '1,000'
						)
					)
				)
			),
		);

		$fields   = array();
		$types    = array();
		$versions = array();

		foreach ( $pages as $page ) {
			$types[ $page['label'] ] = $page['key'];

			if ( ! isset( $page['templates'] ) ) {
				continue;
			}

			$templates = array();
			foreach ( $page['templates'] as $template ) {
				$templates[ $template['label'] ] = $template['key'];

				$versions_list = array();
				foreach ( $template['versions'] as $version_key => $version ) {
					$versions_list[ $version ] = $version_key;
				}

				$versions[] = array(
					'heading'     => esc_html__( 'Version', 'myhome-core' ),
					'param_name'  => 'template_' . $page['key'] . '_version_' . $template['key'],
					'type'        => 'dropdown',
					'value'       => $versions_list,
					'save_always' => true,
					'dependency'  => array(
						'element' => 'template_' . $page['key'],
						'value'   => array( $template['key'] )
					)
				);
			}

			$fields[] = array(
				'heading'     => esc_html__( 'Template', 'myhome-core' ),
				'param_name'  => 'template_' . $page['key'],
				'type'        => 'dropdown',
				'value'       => $templates,
				'save_always' => true,
				'dependency'  => array(
					'element' => 'type',
					'value'   => array( $page['key'] )
				)
			);
		}

		$fields = array_merge( array(
			array(
				'heading'     => esc_html__( 'Page Type', 'myhome-core' ),
				'param_name'  => 'type',
				'type'        => 'dropdown',
				'value'       => $types,
				'save_always' => true,
			)
		), $fields, $versions );

		return $fields;
	}


}