<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class Packages_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class Packages_Shortcode extends Shortcode {

	/**
	 * @param array $args
	 * @param null $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		if ( ! function_exists( 'wc_get_products' ) ) {
			?>
            <div>
                <h2 style="padding:24px; text-align:center;"><?php esc_html_e( 'WooCommerce Plugin is not installed', 'myhome-core' ); ?></h2>
            </div>
			<?php
			return;
		}

		global $myhome_packages;
		if ( isset( $args['packages_number'] ) ) {
			$number = intval( $args['packages_number'] );
		} else {
			$number = 5;
		}

		$product_ids = array();
		for ( $i = 1; $i <= $number; $i ++ ) {
			if ( isset( $args[ 'package_' . $i ] ) ) {
				$product_ids[] = $args[ 'package_' . $i ];
			}
		}

		$products            = array();
		$products_not_sorted = wc_get_products( array(
			'type'    => 'myhome_package',
			'limit'   => $number,
			'include' => $product_ids
		) );

		foreach ( $product_ids as $product_id ) {
			foreach ( $products_not_sorted as $product ) {
				if ( intval( $product_id ) == $product->get_id() ) {
					$products[] = $product;
					break;
				}
			}
		}

		$products_number = count( $products );
		if ( $products_number < $number ) {
			$number = $products_number;
		}

		$myhome_packages['number']   = $number;
		$myhome_packages['products'] = is_array( $products ) ? $products : array();

		return $this->get_template();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		if ( ! function_exists( 'wc_get_products' ) ) {
			return;
		}

		$products_list = array();
		$products      = wc_get_products( array(
			'type' => 'myhome_package',
		) );

		foreach ( $products as $product ) {
			$products_list[ $product->get_name() ] = $product->get_id();
		}

		return array(
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'How many packages to display', 'myhome-core' ),
				'param_name' => 'packages_number',
				'value'      => array(
					'1',
					'2',
					'3',
					'4',
					'5'
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Package 1', 'myhome-core' ),
				'param_name' => 'package_1',
				'value'      => $products_list,
				'dependency' => array(
					'element' => 'packages_number',
					'value'   => array( '1', '2', '3', '4', '5' )
				)
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Package 2', 'myhome-core' ),
				'param_name' => 'package_2',
				'value'      => $products_list,
				'dependency' => array(
					'element' => 'packages_number',
					'value'   => array( '2', '3', '4', '5' )
				)
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Package 3', 'myhome-core' ),
				'param_name' => 'package_3',
				'value'      => $products_list,
				'dependency' => array(
					'element' => 'packages_number',
					'value'   => array( '3', '4', '5' )
				)
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Package 4', 'myhome-core' ),
				'param_name' => 'package_4',
				'value'      => $products_list,
				'dependency' => array(
					'element' => 'packages_number',
					'value'   => array( '4', '5' )
				)
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Package 5', 'myhome-core' ),
				'param_name' => 'package_5',
				'value'      => $products_list,
				'dependency' => array(
					'element' => 'packages_number',
					'value'   => array( '5' )
				)
			)
		);
	}

}