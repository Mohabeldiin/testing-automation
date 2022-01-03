<?php

namespace MyHomeCore\Shortcodes {


    use ErrorException;
    use MyHomeCore\Attributes\Attribute_Factory;
	use MyHomeCore\Attributes\Attribute_Value;
	use MyHomeCore\Attributes\Attribute_Values;
	use MyHomeCore\Attributes\Price_Attribute;
	use MyHomeCore\Components\Listing\Form\Field;
	use MyHomeCore\Estates\Estate_Factory;
	use MyHomeCore\Users\Users_Factory;

	/**
	 * Class Slider_Estate_Shortcode
	 * @package MyHomeCore\Shortcodes
	 */
	class Slider_Estate_Shortcode extends Shortcode {

		/**
		 * @param array $args
		 * @param null  $content
		 *
		 * @return string
         * @noinspection DuplicatedCode
         */
		public function display( $args = array(), $content = null ) {
			$content = wpb_js_remove_wpautop( $content );

			$estates_factory = new Estate_Factory();

			if ( ! empty( $args['featured'] ) && $args['featured'] === 'true' ) {
				$estates_factory->set_featured_only();
			}

			if ( ! empty( $args['limit'] ) ) {
				$estates_factory->set_limit( $args['limit'] );
			}

			if ( ! empty( $args['estates__in'] ) ) {
				$estate_ids = explode( ',', $args['estates__in'] );
				$estates_factory->set_estates__in( $estate_ids );
			}

			if ( ! empty( $args['agent_id'] ) ) {
				$estates_factory->set_user_id( $args['agent_id'] );
			}

			if ( ! empty( $args['sort'] ) ) {
				$estates_factory->set_sort_by( $args['sort'] );
			}

			foreach ( Attribute_Factory::get_search() as $attribute ) {
				$form_control = $attribute->get_search_form_control();
				if ( $form_control === Field::TEXT_RANGE || $form_control === Field::SELECT_RANGE ) {
					$keys = array( $attribute->get_slug() . '_from', $attribute->get_slug() . '_to' );
				} else {
					$keys = array( $attribute->get_slug() );
				}

				foreach ( $keys as $key ) {
					if ( empty( $args[ $key ] ) || $args[ $key ] === 'any' ) {
						continue;
					}

					$values = new Attribute_Values(
						array( new Attribute_Value( $args[ $key ], $args[ $key ], '', $args[ $key ] ) )
					);
					if ( ! $attribute instanceof Price_Attribute ) {
						$estates_factory->add_filter( $attribute->get_estate_filter( $values ) );
					} elseif ( strpos( $key, 'from' ) !== false ) {
						$estates_factory->add_filter( $attribute->get_estate_filter( $values, '>=' ) );
					} elseif ( strpos( $key, 'to' ) !== false ) {
						$estates_factory->add_filter( $attribute->get_estate_filter( $values, '<=' ) );
					} else {
						$estates_factory->add_filter( $attribute->get_estate_filter( $values ) );
					}
				}
			}

			global $myhome_estates;
			$myhome_estates = $estates_factory->get_results();
			global $myhome_slider_estate;
			$myhome_slider_estate            = $args;
			$myhome_slider_estate['content'] = $content;

			return $this->get_template();
		}

        /**
         * @return array|array[]
         * @throws ErrorException
         */
		public function get_vc_params() {
			$agents      = Users_Factory::get_agents( - 1 );
			$agents_list = array( esc_html__( 'Any', 'myhome-core' ) => 0 );
			foreach ( $agents as $agent ) {
				$agents_list[ $agent->get_name() ] = $agent->get_ID();
			}

			$fields = array(
				// Style
				array(
					'group'       => esc_html__( 'General', 'myhome-core' ),
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Slider Style', 'myhome-core' ),
					'param_name'  => 'estates_slider_style',
					'value'       => array(
						esc_html__( 'Card', 'myhome-core' )        => 'estate_slider_card',
						esc_html__( 'Card short', 'myhome-core' )  => 'estate_slider_card_short',
						esc_html__( 'Transparent', 'myhome-core' ) => 'estate_slider_transparent',
					),
					'save_always' => true
				),
				// Sort by
				array(
					'group'       => esc_html__( 'General', 'myhome-core' ),
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Sort by', 'myhome-core' ),
					'param_name'  => 'sort',
					'value'       => array(
						esc_html__( 'Newest', 'myhome-core' )              => 'newest',
						esc_html__( 'Price (high to low)', 'myhome-core' ) => 'priceHighToLow',
						esc_html__( 'Price (low to high)', 'myhome-core' ) => 'priceLowToHigh',
						esc_html__( 'Popular', 'myhome-core' )             => 'popular',
						esc_html__( 'Provided IDs', 'myhome-core' )        => 'post__in'
					),
					'save_always' => true
				),
				// Estates limit
				array(
					'group'       => esc_html__( 'General', 'myhome-core' ),
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Estates limit', 'myhome-core' ),
					'param_name'  => 'limit',
					'value'       => 3,
					'save_always' => true
				),
				// Agent
				array(
					'group'       => esc_html__( 'General', 'myhome-core' ),
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Narrow estates to single agent', 'myhome-core' ),
					'param_name'  => 'agent_id',
					'value'       => $agents_list,
					'save_always' => true
				),
				// Featured
				array(
					'group'       => esc_html__( 'General', 'myhome-core' ),
					'type'        => 'checkbox',
					'heading'     => esc_html__( 'Narrow estates to featured only', 'myhome-core' ),
					'param_name'  => 'featured',
					'save_always' => true,
					'value'       => 'true'
				),
				// Estates in
				array(
					'group'       => esc_html__( 'General', 'myhome-core' ),
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Narrow estates to IDs:', 'myhome-core' ),
					'param_name'  => 'estates__in',
					'value'       => '',
					'save_always' => true
				)
			);

			foreach ( Attribute_Factory::get_search() as $attribute ) {
				$fields = $attribute->get_vc_control( $fields );
			}

			return $fields;
		}

	}

}

namespace {
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_mh_slider_estate extends WPBakeryShortCodesContainer {
		}
	}
}