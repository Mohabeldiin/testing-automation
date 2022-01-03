<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Remove each style one by one
add_filter( 'woocommerce_enqueue_styles', 'thim_jk_dequeue_styles' );
function thim_jk_dequeue_styles( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-smallscreen'] );    // Remove the smallscreen optimisation

	return $enqueue_styles;
}

// remove woocommerce_breadcrumb
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

add_filter( 'loop_shop_per_page', 'thim_loop_shop_per_page' );
function thim_loop_shop_per_page() {
	$product_per_page = get_theme_mod( 'thim_woo_product_per_page', false );
	parse_str( $_SERVER['QUERY_STRING'], $params );
	if ( ! empty( $product_per_page ) ) {
		$per_page = $product_per_page;
	} else {
		$per_page = 12;
	}
	$pc = ! empty( $params['product_count'] ) ? $params['product_count'] : $per_page;

	return $pc;
}

/*****************quick view*****************/
//remove_action( 'woocommerce_single_product_summary_quick', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_single_product_summary_quick', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_single_product_summary_quick', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary_quick', 'woocommerce_template_single_rating', 15 );
add_action( 'woocommerce_single_product_summary_quick', 'woocommerce_template_single_add_to_cart', 20 );
add_action( 'woocommerce_single_product_summary_quick', 'woocommerce_template_single_excerpt', 30 );

//remove_action( 'woocommerce_single_product_summary_quick', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary_quick', 'woocommerce_template_single_meta', 7 );

add_action( 'woocommerce_single_product_summary_quick', 'woocommerce_template_single_sharing', 50 );

//overwrite content product.
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item_title_rating', 'woocommerce_template_loop_rating', 5 );

add_action( 'wp_ajax_jck_quickview', 'thim_jck_quickview' );
add_action( 'wp_ajax_nopriv_jck_quickview', 'thim_jck_quickview' );
/** The Quickview Ajax Output **/
function thim_jck_quickview() {
	global $post, $product;
	$prod_id = $_POST['product'];
	$post    = get_post( $prod_id );
	$product = wc_get_product( $prod_id );
	// Get category permalink
	ob_start();

	wc_get_template_part( 'content', 'single-product-lightbox' );

	$output = ob_get_contents();
	ob_end_clean();
	echo ent2ncr( $output );
	die();
}

/* End PRODUCT QUICK VIEW */


/* Share Product */
add_action( 'woocommerce_share', 'thim_social_share' );

/* custom WC_Widget_Cart */
function thim_get_current_cart_info() {
	global $woocommerce;
	$items = '';
	if ( ! is_admin() ) {
		$items = count( $woocommerce->cart->get_cart() );
	}

	return array(
		$items,
		get_woocommerce_currency_symbol(),
	);
}

add_filter( 'woocommerce_add_to_cart_fragments', 'thim_add_to_cart_success_ajax' );
function thim_add_to_cart_success_ajax( $count_cat_product ) {
	list( $cart_items ) = thim_get_current_cart_info();
	if ( $cart_items < 0 ) {
		$cart_items = '0';
	} else {
		$cart_items = $cart_items;
	}
	$count_cat_product['#header-mini-cart .cart-items-number .items-number'] = '<span class="items-number">' . $cart_items . '</span>';

	return $count_cat_product;
}

// Override WooCommerce Widgets
add_action( 'widgets_init', 'thim_override_woocommerce_widgets', 15 );
function thim_override_woocommerce_widgets() {
	if ( class_exists( 'WC_Widget_Cart' ) ) {
		unregister_widget( 'WC_Widget_Cart' );
		$file_child = get_stylesheet_directory() . '/woocommerce/widgets/class-wc-widget-cart.php';
		if ( file_exists( $file_child ) ) {
			include_once( get_stylesheet_directory() . '/woocommerce/widgets/class-wc-widget-cart.php' );
		} else {
			include_once( 'widgets/class-wc-widget-cart.php' );
		}
		register_widget( 'Thim_Custom_WC_Widget_Cart' );
	}
}

/**
 * Shop layout grid/list
 */
$shop_layout = get_theme_mod( 'thim_woo_cate_display_layout', 'grid' );
if ( 'grid' == $shop_layout ) {
	add_action( 'woocommerce_before_shop_loop', 'thim_woocommerce_product_filter', 15 );
}
if ( ! function_exists( 'thim_woocommerce_product_filter' ) ) {
	function thim_woocommerce_product_filter() {
		echo '
		<div class="thim-product-switch-wrap switch-layout-container">
		 	<div class="thim-product-switch-layout switch-layout">
					<a href="javascript:;" class="list switchToGrid"><i class="fa fa-th-large"></i></a>
					<a href="javascript:;" class="grid switchToList"><i class="fa fa-th-list"></i></a>
			</div>';
	}
}

add_filter( 'woocommerce_account_menu_items', 'thim_woocommerce_account_menu_items' );

if ( ! function_exists( 'thim_woocommerce_account_menu_items' ) ) {
	function thim_woocommerce_account_menu_items( $items ) {
		unset( $items['customer-logout'] );

		return $items;
	}
}

/**
 * Custom WooCommerce breadcrumbs
 *
 * @return array
 */
if ( ! function_exists( 'thim_woocommerce_breadcrumbs' ) ) {
	function thim_woocommerce_breadcrumbs() {
		return array(
			'delimiter'   => '',
			'wrap_before' => '<ul class="breadcrumbs" id="breadcrumbs">',
			'wrap_after'  => '</ul>',
			'before'      => '<li>',
			'after'       => '</li>',
			'home'        => esc_html__( 'Home', 'eduma' ),
		);
	}
}
add_filter( 'woocommerce_breadcrumb_defaults', 'thim_woocommerce_breadcrumbs' );
