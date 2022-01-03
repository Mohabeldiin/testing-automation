<?php

namespace MyHomeCore\Components\Listing;


use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Attribute_Value;
use MyHomeCore\Attributes\Attribute_Values;
use MyHomeCore\Attributes\Price_Attribute_Options_Page;
use MyHomeCore\Components\Listing\Form\Field;
use MyHomeCore\Components\Listing\Form\Fields;
use MyHomeCore\Components\Listing\Search_Forms\Search_Form;
use MyHomeCore\Core;
use MyHomeCore\Estates\Estate_Factory;
use MyHomeCore\Estates\Estates;
use MyHomeCore\Users\Users_Factory;

class Listing_Map_Settings {

	/**
	 * @var array
	 */
	private $settings = array(
		'string' => array(
			'search_form_position' => 'top',
			'label'                => '',
			'map_height'           => 'height-standard'
		),
		'number' => array(
			'search_form_advanced_number' => 3
		),
		'bool'   => array(
			'show_advanced'    => true,
			'show_clear'       => true,
			'search_form_wide' => false,
			'featured'         => false
		)
	);

	/**
	 * @var array
	 */
	private $args;

	/**
	 * Listing_Map_Settings constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args ) {
		$this->args = $args;
	}

	/**
	 * @return string
	 */
	public function get_config() {
		$hash      = md5( json_encode( $this->args ) );
		$cache_key = 'myhome_cache_listing_map_' . $hash;

		if ( ! empty( \MyHomeCore\My_Home_Core()->current_language ) ) {
			$cache_key .= \MyHomeCore\My_Home_Core()->current_language;
		}

		if ( ! is_null( \MyHomeCore\My_Home_Core()->currency ) ) {
			$cache_key .= \MyHomeCore\My_Home_Core()->currency;
		}

		if ( ! empty( $_GET ) || \MyHomeCore\My_Home_Core()->development_mode || false === ( $config = get_transient( $cache_key ) ) ) {
			$config = array(
				'fields'       => Fields::get( $this->args )->get_data(),
				'api_endpoint' => get_rest_url() . 'myhome/v1/estates',
			);

			foreach ( $this->settings['string'] as $param_key => $param ) {
				$config[ $param_key ] = isset( $this->args[ $param_key ] ) ? $this->args[ $param_key ] : $param;
			}

			foreach ( $this->settings['number'] as $param_key => $param ) {
				$config[ $param_key ] = isset( $this->args[ $param_key ] ) ? intval( $this->args[ $param_key ] ) : $param;
			}

			foreach ( $this->settings['bool'] as $param_key => $param ) {
				$config[ $param_key ] = isset( $this->args[ $param_key ] ) ? filter_var( $this->args[ $param_key ], FILTER_VALIDATE_BOOLEAN ) : $param;
			}

			$config['map_style']        = empty( \MyHomeCore\My_Home_Core()->settings->props['mh-map-style'] ) ? 'gray' : \MyHomeCore\My_Home_Core()->settings->props['mh-map-style'];
			$config['results']          = $this->get_estates();
			$config['dependencies']     = $this->get_dependencies();
			$config['default_values']   = $this->get_values( $this->args );
			$config['current_values']   = $this->get_values( $_GET );
			$config['site']             = site_url();
			$config['currencies']       = Price_Attribute_Options_Page::get_currencies();
			$config['current_currency'] = \MyHomeCore\My_Home_Core()->currency;
			$config['lang']             = \MyHomeCore\My_Home_Core()->current_language;

			if ( ! empty( $this->args['agent_id'] ) && $this->args['agent_id'] !== 'any' ) {
				$config['user_id'] = intval( $this->args['agent_id'] );
			}
		}

		$config['key'] = 'MyHomeMapListing' . time();
		wp_localize_script( 'myhome-min', $config['key'], $config );

		return $config['key'];
	}

	/**
	 * @return array
	 */
	private function get_estates() {
		$estates_factory = new Estate_Factory();
		$this->args      = array_merge( $this->args, $_GET );
		$estates_factory->set_limit( Estate_Factory::NO_LIMIT );

		if ( ! empty( $this->args['agent_id'] ) && $this->args['agent_id'] !== 'any' ) {
			$estates_factory->set_user_id( $this->args['agent_id'] );
		}

		if ( ! empty( $this->args['featured'] ) && $this->args['featured'] == 'true' ) {
			$estates_factory->set_featured_only();
		}

		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$form_control = $attribute->get_search_form_control();
			if ( $form_control == Field::TEXT_RANGE || $form_control == Field::SELECT_RANGE ) {
				$keys = array( $attribute->get_slug() . '_from', $attribute->get_slug() . '_to' );
			} else {
				$keys = array( $attribute->get_slug() );
			}

			foreach ( $keys as $key ) {
				if ( empty( $this->args[ $key ] ) || $this->args[ $key ] == 'any' ) {
					continue;
				}

				$values = new Attribute_Values(
					array( new Attribute_Value( $this->args[ $key ], $this->args[ $key ], '', $this->args[ $key ] ) )
				);
				$estates_factory->add_filter( $attribute->get_estate_filter( $values ) );
			}
		}

		return array(
			'estates'      => $estates_factory->get_results()->get_data( Estates::MARKER_DATA ),
			'totalResults' => $estates_factory->get_found_number()
		);
	}

	/**
	 * @return array
	 */
	private function get_dependencies() {
		$dependencies = array();
		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$dependencies[ $attribute->get_slug() ] = $attribute->get_property_type_dependency();
		}

		return $dependencies;
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	private function get_values( $args ) {
		$default_values = array();
		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$form_control = $attribute->get_search_form_control();
			if ( $form_control == Field::TEXT_RANGE || $form_control == Field::SELECT_RANGE ) {
				$keys = array( $attribute->get_slug() . '_from', $attribute->get_slug() . '_to' );
			} else {
				$keys = array( $attribute->get_slug() );
			}

			foreach ( $keys as $key ) {
				if ( empty( $args[ $key ] ) || $args[ $key ] == 'any' ) {
					continue;
				}

				if ( $attribute instanceof Text_Attribute ) {
					$filters = array();
					if ( is_array( $args[ $key ] ) ) {
						$values = $args[ $key ];
					} else {
						$values = array( $args[ $key ] );
					}

					foreach ( $values as $value ) {
						$term = Term::get_by_slug( $value, $attribute );

						if ( $term == false ) {
							continue;
						}

						$filters[] = array(
							'name'  => $term->get_name(),
							'value' => $term->get_slug()
						);
					}


					$default_values[ $key ] = array(
						'id'     => $attribute->get_ID(),
						'values' => $filters
					);
				} else {
					$default_values[ $key ] = array(
						'id'     => $attribute->get_ID(),
						'values' => array(
							array(
								'name'  => $args[ $key ],
								'value' => $args[ $key ]
							)
						)
					);
				}
			}
		}

		return $default_values;
	}

	/**
	 * @return array
	 */
	public static function get_vc_settings() {
		$agents      = Users_Factory::get_agents( - 1, false, array(), false, false, false );
		$agents_list = array( esc_html__( 'Any', 'myhome-core' ) => 'any' );

		foreach ( $agents as $agent ) {
			$agents_list[ $agent->get_name() ] = $agent->get_ID();
		}

		$fields = array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Search form', 'myhome-core' ),
				'param_name'  => 'search_form',
				'dependency'  => array(
					'element' => 'search_form_position',
					'value'   => array( 'top', 'right', 'left', 'bottom' )
				),
				'value'       => Search_Form::get_vc_search_form_list(),
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'save_always' => true
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Search form position', 'myhome-core' ),
				'param_name'  => 'search_form_position',
				'value'       => array(
					esc_html__( 'Bottom', 'myhome-core' ) => 'bottom',
					esc_html__( 'Top', 'myhome-core' )    => 'top',
					esc_html__( 'Hidden', 'myhome-core' ) => 'hide',
				),
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' )
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Wide search form', 'myhome-core' ),
				'param_name'  => 'search_form_wide',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'value'       => 'false',
				'std'         => 'false',
				'dependency'  => array(
					'element' => 'search_form_position',
					'value'   => array(
						'top',
						'bottom'
					)
				)
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Label', 'myhome-core' ),
				'param_name'  => 'label',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'dependency'  => array(
					'element' => 'search_form_position',
					'value'   => array( 'top', 'bottom' )
				)
			),
			// Map height
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Map height', 'myhome-core' ),
				'param_name'  => 'map_height',
				'value'       => array(
					esc_html__( 'Standard', 'myhome-core' ) => 'height-standard',
					esc_html__( 'Tall', 'myhome-core' )     => 'height-tall',
				),
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' )
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Number of filters to show before the "Advanced" button', 'myhome-core' ),
				'param_name'  => 'search_form_advanced_number',
				'value'       => 3,
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'dependency'  => array(
					'element' => 'search_form_position',
					'value'   => array( 'top', 'bottom' )
				)
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display "advanced" button', 'myhome-core' ),
				'param_name'  => 'show_advanced',
				'value'       => 'true',
				'std'         => 'true',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'dependency'  => array(
					'element' => 'search_form_position',
					'value'   => array( 'top', 'bottom' )
				)
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display "clear" button', 'myhome-core' ),
				'param_name'  => 'show_clear',
				'value'       => 'true',
				'std'         => 'true',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'dependency'  => array(
					'element' => 'search_form_position',
					'value'   => array( 'top', 'bottom' )
				)
			),
            array(
                'type'        => 'textfield',
                'heading'     => esc_html__( 'Force position - Zoom (leave empty for auto)', 'myhome-core' ),
                'description' => esc_html__( 'Choose number between 1-30.', 'myhome-core' ) . '<br><br>' .
                esc_html__( 'Important - All 3 options: Zoom, Longitude, Latitude need to be set to force map position', 'myhome-core' ),
                'param_name'  => 'zoom',
                'save_always' => true,
                'group'       => esc_html__( 'General', 'myhome-core' ),
            ),
            array(
                'type'        => 'textfield',
                'heading'     => esc_html__( 'Force position - Latitude (leave empty for auto)', 'myhome-core' ),
                'description' => '<a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360000444753-">' . esc_html__( 'Click here to learn how to get Google Maps latitude and longitude', 'myhome-core' ) . '</a><br><br>' .
                    esc_html__( 'Important - All 3 options: Zoom, Longitude, Latitude need to be set to force map position', 'myhome-core' ),
                'param_name'  => 'lat',
                'save_always' => true,
                'group'       => esc_html__( 'General', 'myhome-core' ),
            ),
            array(
                'type'        => 'textfield',
                'heading'     => esc_html__( 'Force position - Longitude (leave empty for auto)', 'myhome-core' ),
                'description' => '<a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360000444753-">' . esc_html__( 'Click here to learn how to get Google Maps latitude and longitude', 'myhome-core' ) . '</a><br><br>' .
                    esc_html__( 'Important - All 3 options: Zoom, Longitude, Latitude need to be set to force map position', 'myhome-core' ),
                'param_name'  => 'lng',
                'save_always' => true,
                'group'       => esc_html__( 'General', 'myhome-core' ),
            ),
			array(
				'group'       => esc_html__( 'Default values', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'User', 'myhome-core' ),
				'param_name'  => 'agent_id',
				'value'       => $agents_list,
				'save_always' => true,
				'default'     => 'any'
			),
		);

		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$fields[] = array(
				'type'        => 'checkbox',
				'heading'     => sprintf( esc_html__( 'Show %s', 'myhome-core' ), $attribute->get_name() ),
				'param_name'  => $attribute->get_slug() . '_show',
				'group'       => esc_html__( 'Show filters', 'myhome-core' ),
				'save_always' => true,
				'value'       => 'true',
				'std'         => 'true',
			);
			$fields   = $attribute->get_vc_control( $fields );
		}

		return $fields;
	}

}
