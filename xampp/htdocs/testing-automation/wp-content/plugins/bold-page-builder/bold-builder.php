<?php
 
/**
 * Plugin Name: Bold Builder
 * Description: WordPress page builder.
 * Version: 3.2.5
 * Author: BoldThemes
 * Author URI: https://www.bold-themes.com
 * Text Domain: bold-builder
 */
 
defined( 'ABSPATH' ) || exit;

// VERSION --------------------------------------------------------- \\
define( 'BT_BB_VERSION', '3.2.5' );
// VERSION --------------------------------------------------------- \\
 
/**
 * Enqueue scripts and styles
 */

if ( file_exists( plugin_dir_path( __FILE__ ) . 'css-crush/CssCrush.php' ) && strpos( $_SERVER['SERVER_NAME'], '-dev' ) ) {
	require_once( 'css-crush/CssCrush.php' );
}

if ( file_exists( get_template_directory() . '/bt_bb_config.php' ) ) {
	require_once( get_template_directory() . '/bt_bb_config.php' );
}

add_filter( 'the_content', 'bt_bb_parse_content', 20 );
function bt_bb_parse_content( $content ) {
	if ( bt_bb_active_for_post_type_fe() ) {
		$content = str_ireplace( array( '``', '`{`', '`}`' ), array( '&quot;', '&#91;', '&#93;' ), $content );
		return '<div class="bt_bb_wrapper">' . $content . '</div>';
	} else {
		return $content;
	}
}

add_filter( 'body_class', 'bt_bb_body_class' );
function bt_bb_body_class( $classes ) {
	return array_merge( $classes, array( 'bt_bb_plugin_active', 'bt_bb_fe_preview_toggle' ) );
}

// WP 5

function bt_bb_disable_gutenberg( $is_enabled, $post_type ) {
	$options = get_option( 'bt_bb_settings' );
	if ( ! $options || ( ! array_key_exists( $post_type, $options ) || ( array_key_exists( $post_type, $options ) && $options[ $post_type ] == '1' ) ) ) {
		return false;
	}
	return $is_enabled;
}
add_filter( 'use_block_editor_for_post_type', 'bt_bb_disable_gutenberg', 10, 2 );

function bt_bb_parse_content_admin( $content ) {

	$bt_bb_content = false;
	if ( strpos( $content, '[bt_bb' ) === 0 || strpos( $content, '[bt_section' ) === 0 ) {
		$bt_bb_content = true;
	}
	if ( ! $bt_bb_content ) {
		return $content;
	}
	
	global $bt_bb_map;
	
	foreach( BT_BB_Root::$elements as $base => $params ) {
		$proxy = new BT_BB_FE_Map_Proxy( $base, $params );
		$proxy->js_map();
	}
	
	global $bt_bb_array;
	$bt_bb_array = array();

	bt_bb_do_shortcode( $content );

	global $bt_bb_fe_array;
	$bt_bb_fe_array = array();
	
	global $bt_bb_fe_array_depth;
	$bt_bb_fe_array_depth = -1;
	
	bt_bb_fe_do_shortcode( $bt_bb_array );

	$wrap_arr = array();
	
	$count = 0;

	foreach( $bt_bb_fe_array as $item ) {

		if ( isset( $bt_bb_map[ $item['base'] ] ) && isset( $bt_bb_map[ $item['base'] ]['root'] ) && $bt_bb_map[ $item['base'] ]['root'] === true && $item['depth'] == 0 ) {
			$count++;
			$fe_wrap_open = '<div class="bt_bb_fe_wrap"><span class="bt_bb_fe_count" title="' . esc_html__( 'Edit', 'bold-builder' ) . '"><span class="bt_bb_fe_count_inner" data-edit_url="' . get_edit_post_link( get_the_ID(), '' ) . '">' . $count . '</span></span>';
			$fe_wrap_close = '</div>';
		} else {
			$fe_wrap_open = '';
			$fe_wrap_close = '';
		}

		$depth = $item['depth'];

		if ( isset( $wrap_arr[ 'd' . ( $depth + 1 ) ] ) ) {
			if ( isset( $wrap_arr[ 'd' . $depth  ] ) ) {
				$wrap_arr[ 'd' . $depth  ] = $wrap_arr[ 'd' . $depth  ] . $fe_wrap_open . $item['sc'] . $wrap_arr[ 'd' . ( $depth + 1 ) ] . $item['sc_close'] . $fe_wrap_close;
			} else {
				$wrap_arr[ 'd' . $depth  ] = $fe_wrap_open . $item['sc'] . $wrap_arr[ 'd' . ( $depth + 1 ) ] . $item['sc_close'] . $fe_wrap_close;
			}
			unset( $wrap_arr[ 'd' . ( $depth + 1 ) ] );
		} else {
			if ( isset( $wrap_arr[ 'd' . $depth  ] ) ) {
				$wrap_arr[ 'd' . $depth  ] = $wrap_arr[ 'd' . $depth  ] . $fe_wrap_open . $item['sc'] . $item['sc_close'] . $fe_wrap_close;
			} else {
				$wrap_arr[ 'd' . $depth  ] = $fe_wrap_open . $item['sc'] . $item['sc_close'] . $fe_wrap_close;
			}
		}

	}

	if ( isset( $wrap_arr['d0'] ) && $wrap_arr['d0'] != "" ) {
		if ( current_user_can( 'edit_pages' ) ) {
			$wrap_arr['d0'] .= '<span id="bt_bb_fe_preview_toggler" class="bt_bb_fe_preview_toggler">'. esc_html__( 'Show/hide edit mode UI', 'bold-builder' ) .'</span>';
		}
		return $wrap_arr['d0'];
	} else {
		return $content;
	}
	
}

function bt_bb_fe_do_shortcode( $bt_bb_array ) {
	
	global $bt_bb_fe_array;
	global $bt_bb_fe_array_depth;
	
	$bt_bb_fe_array_depth++;
	
	foreach( $bt_bb_array as $item ) {
		
		$sc = '';
		
		if ( $item['base'] == '_content' ) {
			
			$sc = $item['content'];
			
			$sc_close = '';
			
		} else {
		
			$sc = '[';
			
			$sc .= $item['base'];
			
			if ( isset( $item['attr'] ) ) {
				$attr_obj = bt_bb_json_decode( $item['attr'] );
				foreach( $attr_obj as $key => $value ) {
					$sc .= ' ' . $key . '="' . $value . '"';
				}
			}
			
			$sc .= ']';
			
			if ( isset( $item['children'] ) ) {
				bt_bb_fe_do_shortcode( $item['children'] );
				$bt_bb_fe_array_depth--;
			}
			
			$sc_close = '[/' . $item['base'] . ']';
		
		}
		
		$bt_bb_fe_array[] = array( 
			'base' => $item['base'],
			'sc' => $sc,
			'sc_close' => $sc_close,
			'container' => count( $item['children'] ) > 0 ? 'yes' : 'no',
			'depth' => $bt_bb_fe_array_depth
		);
		
	}
}
	
class BT_BB_Root {
	public static $elements = array();
	public static $path;
	public static $init_wpautop;
	public static $font_arr = array();
}

BT_BB_Root::$path = plugin_dir_url( __FILE__ );
BT_BB_Root::$init_wpautop = null;
 
function bt_bb_enqueue() {
	
	$screen = get_current_screen();
	if ( $screen->base != 'post' && $screen->base != 'widgets' && $screen->base != 'nav-menus' && $screen->base != 'customize' ) {
		return;
	}
	
	$options = get_option( 'bt_bb_settings' );
	$post_types = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ) );
	$active_pt = array();
	foreach( $post_types as $pt ) {
		if ( ! $options || ( ! array_key_exists( $pt, $options ) || ( array_key_exists( $pt, $options ) && $options[ $pt ] == '1' ) ) ) {
			$active_pt[] = $pt;
		}
	}
	$screens = $active_pt;
	if ( ! in_array( $screen->post_type, $screens ) && $screen->base == 'post' ) {
		return;
	}

	if ( function_exists( 'csscrush_file' ) && strpos( $_SERVER['SERVER_NAME'], '-dev' ) ) {
		csscrush_file( plugin_dir_path( __FILE__ ) . 'css/style.css', array( 'source_map' => true, 'minify' => false, 'output_file' => 'style.crush', 'formatter' => 'block', 'boilerplate' => false, 'plugins' => array( 'loop', 'ease' ) ) );
	}

	wp_enqueue_style( 'bt_bb_font-awesome.min', plugins_url( 'css/font-awesome.min.css', __FILE__ ), array(), BT_BB_VERSION );
	wp_enqueue_style( 'bt_bb', plugins_url( 'css/style.crush.css', __FILE__ ), array(), BT_BB_VERSION );

	wp_enqueue_script( 'bt_bb_react', plugins_url( 'react.min.js', __FILE__ ), array(), BT_BB_VERSION );
	wp_enqueue_script( 'bt_bb', plugins_url( 'script.min.js', __FILE__ ), array( 'jquery' ), BT_BB_VERSION );
	wp_enqueue_script( 'bt_bb_jsx', plugins_url( 'build/bundle.js', __FILE__ ), array( 'jquery' ), BT_BB_VERSION );
	wp_enqueue_script( 'bt_bb_autosize', plugins_url( 'autosize.min.js', __FILE__ ), array(), BT_BB_VERSION );
	wp_enqueue_script( 'bt_bb_imagesloaded', plugins_url( 'imagesloaded.pkgd.min.js', __FILE__ ), array( 'jquery' ), BT_BB_VERSION );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker-alpha', plugins_url( 'wp-color-picker-alpha.min.js', __FILE__ ), array( 'wp-color-picker' ), BT_BB_VERSION );

}
add_action( 'admin_enqueue_scripts', 'bt_bb_enqueue' );

function bt_bb_enqueue_fe_always() {
	if ( current_user_can( 'edit_pages' ) ) {
		wp_enqueue_script( 'bt_bb_fe', plugins_url( 'script_fe.js', __FILE__ ), array( 'jquery' ), BT_BB_VERSION );
	}
}

function bt_bb_wp_head() {
	$post_id = get_the_ID();
	$opt_arr = get_option( 'bt_bb_custom_css' );
	if ( isset( $opt_arr[ $post_id ] ) ) {
		echo '<style>' . stripslashes( wp_strip_all_tags( $opt_arr[ $post_id ] ) ) . '</style>';
	}
	if ( function_exists( 'bt_bb_add_color_schemes' ) ) {
		bt_bb_add_color_schemes();
	}	
}
add_action( 'wp_head', 'bt_bb_wp_head', 7 );

function bt_bb_enqueue_fe() {
	if ( function_exists( 'csscrush_file' ) && strpos( $_SERVER['SERVER_NAME'], '-dev' ) ) {
		csscrush_file( plugin_dir_path( __FILE__ ) . 'css/front_end/style.css', array( 'source_map' => true, 'minify' => false, 'output_file' => 'style.crush', 'formatter' => 'block', 'boilerplate' => false, 'plugins' => array( 'loop', 'ease' ) ) );
	}
	wp_enqueue_style( 'bt_bb', plugins_url( 'css/front_end/style.crush.css', __FILE__ ), array(), BT_BB_VERSION );
}

function bt_bb_enqueue_content_elements() {
	if ( function_exists( 'csscrush_file' ) && strpos( $_SERVER['SERVER_NAME'], '-dev' ) ) {
		csscrush_file( plugin_dir_path( __FILE__ ) . 'css/front_end/content_elements.css', array( 'source_map' => true, 'minify' => false, 'output_file' => 'content_elements.crush', 'formatter' => 'block', 'boilerplate' => false, 'plugins' => array( 'loop', 'ease' ) ) );
	}
	wp_enqueue_style( 'bt_bb_content_elements', plugins_url( 'css/front_end/content_elements.crush.css', __FILE__ ), array(), BT_BB_VERSION );
	
	wp_enqueue_script( 
		'bt_bb_slick',
		plugins_url( 'slick/slick.min.js', __FILE__ ),
		array( 'jquery' ),
		BT_BB_VERSION
	);
	wp_enqueue_style( 'bt_bb_slick', plugins_url( 'slick/slick.css', __FILE__ ), array(), BT_BB_VERSION );

	wp_enqueue_script(
		'bt_bb_magnific',
		plugins_url( 'content_elements_misc/js/jquery.magnific-popup.min.js', __FILE__ ),
		array( 'jquery' ),
		BT_BB_VERSION
	);

	wp_enqueue_script( 'bt_bb', plugins_url( 'content_elements_misc/js/content_elements.js', __FILE__ ), array( 'jquery' ), BT_BB_VERSION );
	
	$post_id = get_the_ID();

}

function bt_bb_activate() {
	$options = get_option( 'bt_bb_settings' );
	if ( ! $options ) {
		update_option( 'bt_bb_settings', array( 'tag_as_name' => '0', 'color_schemes' => "Black/White;#000;#fff\r\nWhite/Black;#fff;#000" ) );
	}
}
register_activation_hook( __FILE__, 'bt_bb_activate' );

function bt_bb_deactivate() {
	//delete_option( 'bt_bb_settings' );
}
register_deactivation_hook( __FILE__, 'bt_bb_deactivate' );

function bt_bb_admin_init() {
    register_setting( 'bt_bb_settings', 'bt_bb_settings' );
}
add_action( 'admin_init', 'bt_bb_admin_init' );

function bt_bb_load_plugin_textdomain() {

	$domain = 'bold-builder';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}
add_action( 'init', 'bt_bb_load_plugin_textdomain' );

/**
 * Save custom css
 */

function bt_bb_save_custom_css() {
	check_ajax_referer( 'bt-bb-custom-css-nonce', 'bt-bb-custom-css-nonce' );
	$post_id = intval( $_POST['post_id'] );
	if ( current_user_can( 'edit_posts', $post_id ) ) {
		$css = wp_strip_all_tags( $_POST['css'] );
		$opt_arr = get_option( 'bt_bb_custom_css' );
		if ( ! is_array( $opt_arr ) ) {
			$opt_arr = array();
		}
		if ( $css != '' ) {
			$opt_arr[ $post_id ] = $css;
		} else {
			unset( $opt_arr[ $post_id ] );
		}
		update_option( 'bt_bb_custom_css', $opt_arr );
		echo 'ok';
	}
	wp_die();
}
add_action( 'wp_ajax_bt_bb_save_custom_css', 'bt_bb_save_custom_css' );

/**
 * Get custom css
 */
 
function bt_bb_get_custom_css() {
	$post_id = intval( $_POST['post_id'] );
	$opt_arr = get_option( 'bt_bb_custom_css' );
	if ( isset( $opt_arr[ $post_id ] ) ) {
		echo stripslashes( wp_strip_all_tags( $opt_arr[ $post_id ] ) );
	}
	die();
}
add_action( 'wp_ajax_bt_bb_get_custom_css', 'bt_bb_get_custom_css' );

/**
 * Get links
 */
 
function bt_bb_search_links() {
	$search = sanitize_text_field( $_POST['search'] );
	$page = intval( $_POST['page'] );

    $pts = get_post_types( array( 'public' => true ), 'objects' );
    $pt_names = array_keys( $pts );
 
    $query = array(
        'post_type'              => $pt_names,
        'suppress_filters'       => true,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'post_status'            => 'publish',
        'posts_per_page'         => 20,
    );
	
	if ( $search != '' ) {
		$query['s'] = $search;
	}
	
	$query['offset'] = $page > 1 ? $query['posts_per_page'] * ( $page - 1 ) : 0;
	
    // Do main query.
    $get_posts = new WP_Query;
    $posts = $get_posts->query( $query );
 
    // Build results.
    $results = array();
    foreach ( $posts as $post ) {
        if ( 'post' === $post->post_type ) {
            $info = mysql2date( __( 'Y/m/d' ), $post->post_date );
        } else {
            $info = $pts[ $post->post_type ]->labels->singular_name;
        }
 
        $results[] = array(
            'ID'        => $post->ID,
            'title'     => trim( esc_html( strip_tags( get_the_title( $post ) ) ) ),
            'permalink' => get_permalink( $post->ID ),
			'slug'      => $post->post_name,
            'info'      => $info,
        );
    }
	
    if ( ! isset( $results ) ) {
        wp_die( 0 );
    }
 
    echo wp_json_encode( $results );
    echo "\n";
 
    die();
}
add_action( 'wp_ajax_bt_bb_search_links', 'bt_bb_search_links' );

/**
 * Settings menu
 */

function bt_bb_menu() {
	add_options_page( esc_html__( 'Bold Builder Settings', 'bold-builder' ), esc_html__( 'Bold Builder', 'bold-builder' ), 'manage_options', 'bt_bb_settings', 'bt_bb_settings' );
}
add_action( 'admin_menu', 'bt_bb_menu' );

/**
 * Settings page
 */
function bt_bb_settings() {
	
	$options = get_option( 'bt_bb_settings' );
	$tag_as_name = $options['tag_as_name'];

	$color_schemes = $options['color_schemes'];
	
	$post_types = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ) );

	?>
		<div class="wrap">
			<h2><?php _e( 'Bold Builder Settings', 'bold-builder' ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'bt_bb_settings' ); ?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><?php _e( 'Show shortcode tag instead of mapped name', 'bold-builder' ); ?></th>
						<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Show shortcode tags instead of mapped names', 'bold-builder' ); ?></span></legend>
						<p><label><input name="bt_bb_settings[tag_as_name]" type="radio" value="0" <?php echo $tag_as_name != '1' ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'bold-builder' ); ?></label><br>
						<label><input name="bt_bb_settings[tag_as_name]" type="radio" value="1" <?php echo $tag_as_name == '1' ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'bold-builder' ); ?></label></p>
						</fieldset></td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Post Types', 'bold-builder' ); ?></th>
						<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Post Types', 'bold-builder' ); ?></span></legend>
						<p>
						<?php 
						$n = 0;
						foreach( $post_types as $pt ) {
							$n++;
							$checked = '';
							if ( ! $options || ( ! array_key_exists( $pt, $options ) || ( array_key_exists( $pt, $options ) && $options[ $pt ] == '1' ) ) ) {
								$checked = ' ' . 'checked="checked"';
							}
							echo '<input type="hidden" name="bt_bb_settings[' . $pt . ']" value="0">';
							echo '<label><input name="bt_bb_settings[' . $pt . ']" type="checkbox" value="1"' . $checked . '> ' . $pt . '</label>';
							if ( $n < count( $post_types ) ) echo '<br>';
						} ?>
						</p>
						</fieldset></td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Color Schemes', 'bold-builder' ); ?></th>
						<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Color Schemes', 'bold-builder' ); ?></span></legend>
						<p>
						<textarea name="bt_bb_settings[color_schemes]" rows="10" cols="50"><?php echo $color_schemes; ?></textarea>
						</p>
						</fieldset></td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Tips', 'bold-builder' ); ?></th>
						<td>
						<p><?php
						$checked = '';
						if ( ! $options || ( ! array_key_exists( 'tips', $options ) || ( array_key_exists( 'tips', $options ) && $options['tips'] == '1' ) ) ) {
							$checked = ' ' . 'checked="checked"';
						}
						echo '<input type="hidden" name="bt_bb_settings[tips]" value="0">';
						echo '<label><input name="bt_bb_settings[tips]" type="checkbox" value="1"' . $checked . '> ' . esc_html__( 'Show Tips', 'bold-builder' ) . '</label>';
						?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Slug in URL', 'bold-builder' ); ?></th>
						<td>
						<p><?php
						$checked = '';
						if ( $options && array_key_exists( 'slug_url', $options ) && $options['slug_url'] == '1' ) {
							$checked = ' ' . 'checked="checked"';
						}
						echo '<input type="hidden" name="bt_bb_settings[slug_url]" value="0">';
						echo '<label><input name="bt_bb_settings[slug_url]" type="checkbox" value="1"' . $checked . '> ' . esc_html__( 'Use post slug in URL input (when selecting existing content search result)', 'bold-builder' ) . '</label>';
						?></p>
						</td>
					</tr>
					</tbody>
				</table>

				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save', 'bold-builder' ); ?>"></p>
			</form>
		</div>
	<?php

}

/**
 * Settings
 */

function bt_bb_js_settings() {
	$screen = get_current_screen();
	if ( $screen->base != 'post' ) {
        return;
    }
	
	$options = get_option( 'bt_bb_settings' );
	
	$post_types = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ) );
	$active_pt = array();
	foreach( $post_types as $pt ) {
		if ( ! $options || ( ! array_key_exists( $pt, $options ) || ( array_key_exists( $pt, $options ) && $options[ $pt ] == '1' ) ) ) {
			$active_pt[] = $pt;
		}
	}
	$screens = $active_pt;
	if ( ! in_array( $screen->post_type, $screens ) && $screen->base == 'post' ) {
		return;
	}	
	
	$tag_as_name = $options['tag_as_name'];
	$slug_url = array_key_exists( 'slug_url', $options ) ? $options['slug_url'] : '';
	
	echo '<script>';
	echo 'window.bt_bb_plugins_url = "' . plugins_url() . '";';
	echo 'window.bt_bb_loading_gif_url = "' . plugins_url( 'img/ajax-loader.gif', __FILE__ ) . '";';
	echo 'window.bt_bb_settings = [];';
	echo 'window.bt_bb_settings.tag_as_name = "' . esc_js( $tag_as_name ) . '";';
	echo 'window.bt_bb_settings.slug_url = "' . esc_js( $slug_url ) . '";';
	
	echo 'window.BTAJAXURL = "' . esc_js( admin_url( 'admin-ajax.php' ) ) . '";';
	
	global $shortcode_tags;
	$all_sc = $shortcode_tags;
	ksort( $all_sc );
	
	echo 'window.bt_bb.all_sc = ' . bt_bb_json_encode( array_keys( $all_sc ) ) . ';';

	global $bt_bb_is_bb_content;
	if ( $bt_bb_is_bb_content === 'true' || $bt_bb_is_bb_content === 'false' ) {
		echo 'window.bt_bb.is_bb_content = ' . $bt_bb_is_bb_content . ';';
	}
	$ajax_nonce = wp_create_nonce( 'bt-bb-custom-css-nonce' );
	?>
		jQuery( '#bt_bb_dialog' ).on( 'click', '.bt_bb_button_save_custom_css', function( e ) {

			if ( wp.codeEditor !== undefined ) {
				window.bt_bb_ce.codemirror.save();
			}				

			var css = jQuery( '.bt_bb_custom_css_content' ).val();
			
			window.bt_bb_custom_css = window.bt_bb_b64EncodeUnicode( css );

			window.bt_bb_dialog.hide();
			
			if ( css != '' ) {
				jQuery( '.bt_bb_custom_css' ).addClass( 'button-primary' );
			} else {
				jQuery( '.bt_bb_custom_css' ).removeClass( 'button-primary' );
			}

			var data = {
				'action': 'bt_bb_save_custom_css',
				'post_id': jQuery( '#post_ID' ).val(),
				'css': css,
				'bt-bb-custom-css-nonce': '<?php echo $ajax_nonce; ?>'
			};				

			jQuery.ajax({
				type: 'POST',
				url: window.BTAJAXURL,
				data: data,
				async: true,
				success: function( response ) {
					
				}
			});
		});
	<?php
	echo '</script>';
}
add_action( 'admin_footer', 'bt_bb_js_settings' );

/**
 * Translate
 */

function bt_bb_translate() {
	echo '<script>';
	echo 'window.bt_bb_text = [];';
	echo 'window.bt_bb_text.toggle = "' . esc_html__( 'Toggle', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.add = "' . esc_html__( 'Add', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.edit = "' . esc_html__( 'Edit', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.edit_content = "' . esc_html__( 'Edit Content', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.clone = "' . esc_html__( 'Clone', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.delete = "' . esc_html__( 'Delete', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.layout_error = "' . esc_html__( 'Layout error!', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.number_columns = "' . esc_html__( 'Number of columns not equal for all breakpoints!', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.add_element = "' . esc_html__( 'Add Element', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.select_layout = "' . esc_html__( 'Select Layout', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.edit_layout = "' . esc_html__( 'Edit Layout', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.select = "' . esc_html__( 'Select', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.select_images = "' . esc_html__( 'Select Images', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.submit = "' . esc_html__( 'Submit', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.search = "' . esc_html__( 'Search', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.search_content = "' . esc_html__( 'Search for existing content', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.copy = "' . esc_html__( 'Copy', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.copy_plus = "' . esc_html__( 'Copy+', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.paste = "' . esc_html__( 'Paste', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.export = "' . esc_html__( 'Export', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.import = "' . esc_html__( 'Import', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.not_allowed = "' . esc_html__( 'Not allowed!', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.manage_cb = "' . esc_html__( 'Manage Clipboard', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.filter = "' . esc_html__( 'Filter...', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.sc_mapper = "' . esc_html__( 'Shortcode Mapper', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.insert_mapping = "' . esc_html__( 'Insert Mapping', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.save = "' . esc_html__( 'Save', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.switch_editor = "' . esc_html__( 'Switch Editor', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.custom_css = "' . esc_html__( 'Custom CSS', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.switch_editor_confirm = "' . esc_html__( 'Are you sure you want to switch editor?', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.general = "' . esc_html__( 'General', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.preview = "' . esc_html__( 'Preview', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.no_results = "' . esc_html__( 'No results found.', 'bold-builder' ) . '";';
	
	echo 'window.bt_bb_text.prev_tip = "' . esc_html__( 'Previous Tip', 'bold-builder' ) . '";';
	echo 'window.bt_bb_text.next_tip = "' . esc_html__( 'Next Tip', 'bold-builder' ) . '";';
	
	echo 'window.bt_bb_text._tips = [];';
	
	//if ( ! class_exists( 'BoldThemes_BB_Settings' ) || ! BoldThemes_BB_Settings::$custom_content_elements ) { // only with native BB elements
	echo 'window.bt_bb_text._tips.push( "' . esc_html__( 'Bold Builder tip: Hold CTRL key while moving (dragging) element to quickly clone it to another location.', 'bold-builder' ) . '" );';
	echo 'window.bt_bb_text._tips.push( "' . esc_html__( 'Bold Builder tip: You can edit Bold Builder settings in Settings > Bold Builder.', 'bold-builder' ) . '" );';
	echo 'window.bt_bb_text._tips.push( "' . esc_html__( 'Bold Builder tip: Did you know that you can move columns between rows?', 'bold-builder' ) . '" );';
	echo 'window.bt_bb_text._tips.push( "' . esc_html__( 'Bold Builder tip: You can use CTRL key with Copy/Copy+ to cut the element.', 'bold-builder' ) . '" );';
	echo 'window.bt_bb_text._tips.push( "' . esc_html__( 'Bold Builder tip: Use Copy+ to copy multiple elements to clipboard.', 'bold-builder' ) . '" );';
	echo 'window.bt_bb_text._tips.push( "' . esc_html__( 'Bold Builder tip: Hold CTRL key and click Edit icon on front end to open back end editor in new tab.', 'bold-builder' ) . '" );';
	echo 'window.bt_bb_text._tips.push( "' . esc_html__( 'Bold Builder tip: Hold CTRL key and click Paste on content element toolbar to paste before the element.', 'bold-builder' ) . '" );';
	
	echo '</script>';
}
add_action( 'admin_footer', 'bt_bb_translate', 20 );
add_action( 'customize_controls_head', 'bt_bb_translate' );
 
/**
 * Map shortcodes
 */
 
global $bt_bb_map;
$bt_bb_map = array();

function bt_bb_map_js() {
	
	if ( is_admin() ) { // back end
		$screen = get_current_screen();
		if ( ! is_object( $screen ) || $screen->base != 'post' ) {
			return;
		}
		
		$options = get_option( 'bt_bb_settings' );
		$post_types = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ) );
		$active_pt = array();
		foreach( $post_types as $pt ) {
			if ( ! $options || ( ! array_key_exists( $pt, $options ) || ( array_key_exists( $pt, $options ) && $options[ $pt ] == '1' ) ) ) {
				$active_pt[] = $pt;
			}
		}
		$screens = $active_pt;
		if ( ! in_array( $screen->post_type, $screens ) && $screen->base == 'post' ) {
			return;
		}		
	}
	
	global $bt_bb_map;
	echo '<script>';
		BT_BB_Root::$elements = apply_filters( 'bt_bb_elements', BT_BB_Root::$elements );
		foreach( BT_BB_Root::$elements as $base => $params ) {
			$proxy = new BT_BB_Map_Proxy( $base, $params );
			$proxy->js_map();
		}
	echo '</script>';
	echo '<script>';
		$opt_arr = get_option( 'bt_bb_mapping_secondary' );
		if ( is_array( $opt_arr ) ) {
			foreach( $opt_arr as $k => $v ) {
				if ( shortcode_exists( $k ) ) {
					echo 'window.bt_bb_map["' . $k . '"] = ' . stripslashes( $v ) . ';';
					$bt_bb_map[ $k ] = json_decode( stripslashes( $v ), true );
					echo 'window.bt_bb_map_secondary["' . $k . '"] = true;';
				}
			}
		}
	echo '</script>';
}
add_action( 'admin_head', 'bt_bb_map_js' );

function bt_bb_fe_action() {
	if ( ! bt_bb_active_for_post_type_fe() ) {
		return;
	}
	add_action( 'wp_enqueue_scripts', 'bt_bb_enqueue_fe' );
	add_filter( 'the_content', 'bt_bb_parse_content_admin' );
}
add_action( 'admin_bar_init', 'bt_bb_fe_action' );

if ( ! class_exists( 'BoldThemes_BB_Settings' ) || ! BoldThemes_BB_Settings::$custom_content_elements ) {
	add_action( 'wp_enqueue_scripts', 'bt_bb_enqueue_content_elements' );
	add_action( 'admin_footer', function() {
			echo '<script>window.bt_bb_custom_elements = false;</script>';
			if ( isset( BT_BB_Root::$elements['bt_bb_column']['responsive_override'] ) && BT_BB_Root::$elements['bt_bb_column']['responsive_override'] && isset( BT_BB_Root::$elements['bt_bb_column_inner']['responsive_override'] ) && BT_BB_Root::$elements['bt_bb_column_inner']['responsive_override'] ) {
				echo '<script>window.bt_bb_responsive_override_layout = true;</script>';
			} else {
				echo '<script>window.bt_bb_responsive_override_layout = false;</script>';
			}
		}
	);
} else {
	add_action( 'admin_footer', function() { echo '<script>window.bt_bb_custom_elements = true;</script>'; } );
}

add_action( 'wp_enqueue_scripts', 'bt_bb_enqueue_fe_always' );

/**
 * Map shortcode elements
 */
//function bt_bb_map( $base, $params, $priority = 10 ) {
function bt_bb_map( $base, $params ) {
	$i = 0;
	if ( isset( $params['params'] ) ) {
		foreach( $params['params'] as $param ) {
			if ( ! isset( $param['weight'] ) ) {
				$params['params'][ $i ]['weight'] = $i;
			}
			$i++;
		}
	}
	BT_BB_Root::$elements[ $base ] = $params;
}

add_action( 'plugins_loaded', 'bt_rc_map_plugins_loaded', 5 );
function bt_rc_map_plugins_loaded() {
	if ( ! function_exists( 'bt_rc_map' ) ) {
		function bt_rc_map( $base, $params ) {
			bt_bb_map( $base, $params );
		}
	}
}

/* Deactivate RC */
register_activation_hook( __FILE__, 'bt_deactivate_rc' );
function bt_deactivate_rc () {
	deactivate_plugins( 'rapid_composer/rapid_composer.php' );
}

/**
 * Modify map
 */
function bt_bb_map_modify( $base, $params ) {
	$i = 0;
	if ( isset( $params['params'] ) ) {
		foreach( $params['params'] as $param ) {
			if ( ! isset( $param['weight'] ) ) {
				$params['params'][ $i ]['weight'] = $i;
			}
			$i++;
		}
	}
	foreach( $params as $key => $value ) {
		BT_BB_Root::$elements[ $base ][ $key ] = $value;
	}
}

/**
 * Add element param(s)
 */
function bt_bb_add_params( $base, $params ) {
	$proxy = new BT_BB_Add_Params_Proxy( $base, $params );
	add_filter( 'bt_bb_elements', array( $proxy, 'add_params' ) );
}

/**
 * Remove element param(s)
 */
function bt_bb_remove_params( $base, $params ) {
	$proxy = new BT_BB_Remove_Params_Proxy( $base, $params );
	add_filter( 'bt_bb_elements', array( $proxy, 'remove_params' ) );
}

global $bt_bb_root_base;
$bt_bb_root_base = array();

class BT_BB_Map_Proxy {
	function __construct( $base, $params ) {
		$this->base = $base;
		$params['base'] = $base;
		$this->params = $params;
	}

	public function js_map() {
		global $bt_bb_map;
		if ( shortcode_exists( $this->base ) ) {
			if ( isset( $this->params['admin_enqueue_css'] ) ) {
				foreach( $this->params['admin_enqueue_css'] as $item ) {
					wp_enqueue_style( 'bt_bb_admin_' . uniqid(), $item );
				}
			}
			echo 'window.bt_bb_map["' . $this->base . '"] = window.bt_bb_map_primary.' . $this->base . ' = ' . bt_bb_json_encode( $this->params ) . ';';
			$bt_bb_map[ $this->base ] = $this->params;
		}
	}
}

class BT_BB_FE_Map_Proxy {
	function __construct( $base, $params ) {
		$this->base = $base;
		$params['base'] = $base;
		$this->params = $params;

		global $bt_bb_root_base;
		if ( isset( $params['root'] ) && $params['root'] === true && isset( $params['base'] ) ) {
			$bt_bb_root_base[] = $params['base'];
		}
	}

	public function js_map() {
		global $bt_bb_map;
		if ( shortcode_exists( $this->base ) ) {
			$bt_bb_map[ $this->base ] = $this->params;
		}
	}
}

class BT_BB_Add_Params_Proxy {
	function __construct( $base, $params ) {
		$this->base = $base;
		$this->params = $params;
	}
	public function add_params( $elements ) {
		foreach( $this->params as $param ) {
			$last = end( $elements[ $this->base ]['params'] );
			if ( ! is_array( $param ) ) {
				if ( ! isset( $this->params['weight'] ) ) {
					if ( isset( $last['weight'] ) ) {
						$this->params['weight'] = $last['weight'] + 1;
					} else {
						$this->params['weight'] = 0;
					}
				}
				$elements[ $this->base ]['params'][] = $this->params;
				break;
			} else {
				if ( ! isset( $param['weight'] ) ) {
					if ( isset( $last['weight'] ) ) {
						$param['weight'] = $last['weight'] + 1;
					} else {
						$param['weight'] = 0;
					}
				}
				$elements[ $this->base ]['params'][] = $param;
			}
		}

		usort( $elements[ $this->base ]['params'], array( $this, 'sort_by_weight' ) );

		return $elements;
	}
	private function sort_by_weight( $a, $b ) {
		if ( $a['weight'] == $b['weight'] ) {
			return 0;
		}
		return ( $a['weight'] < $b['weight'] ) ? -1 : 1;
	}
}

class BT_BB_Remove_Params_Proxy {
	function __construct( $base, $params ) {
		$this->base = $base;
		$this->params = $params;
	}
	public function remove_params( $elements ) {
		if ( is_array( $this->params ) ) {
			foreach( $this->params as $param ) {
				$i = 0;
				foreach( $elements[ $this->base ]['params'] as $element_param ) {
					if ( $element_param['param_name'] == $param ) {
						unset( $elements[ $this->base ]['params'][ $i ] );
					}
					$i++;
				}
				$elements[ $this->base ]['params'] = array_values( $elements[ $this->base ]['params'] );
			}
		} else {
			$i = 0;
			foreach( $elements[ $this->base ]['params'] as $element_param ) {
				if ( $element_param['param_name'] == $this->params ) {
					unset( $elements[ $this->base ]['params'][ $i ] );
				}
				$i++;
			}
			$elements[ $this->base ]['params'] = array_values( $elements[ $this->base ]['params'] );
		}
		return $elements;
	}
}

/**
 * Remove wpautop
 */
function bt_bb_wpautop( $content ) {
	if ( BT_BB_Root::$init_wpautop === null ) {
		BT_BB_Root::$init_wpautop = has_filter( 'the_content', 'wpautop' );
	}
	if ( ! bt_bb_active_for_post_type_fe() ) {
		return $content;
	}
	foreach( BT_BB_Root::$elements as $base => $params ) {
		$proxy = new BT_BB_FE_Map_Proxy( $base, $params );
	}	
	global $bt_bb_root_base;
	$bt_bb_content = false;
	foreach( $bt_bb_root_base as $item ) {
		if ( strpos( trim( $content ), '[' . $item ) === 0 ) {
			$bt_bb_content = true;
			break;
		}
	}
	if ( $bt_bb_content ) {
		remove_filter( 'the_content', 'wpautop' );
	} else if ( BT_BB_Root::$init_wpautop !== false ) {
		add_filter( 'the_content', 'wpautop' );
	}
	return $content;
}
add_filter( 'the_content', 'bt_bb_wpautop', 1 );

// force visual editor
add_action( 'current_screen' , 'bt_bb_current_screen' );
function bt_bb_current_screen( $current_screen ) {
	$options = get_option( 'bt_bb_settings' );
	$post_types = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ) );
	$active_pt = array();
	foreach( $post_types as $pt ) {
		if ( ! $options || ( ! array_key_exists( $pt, $options ) || ( array_key_exists( $pt, $options ) && $options[ $pt ] == '1' ) ) ) {
			$active_pt[] = $pt;
		}
	}
	$screens = $active_pt;
	if ( in_array( $current_screen->post_type, $screens ) ) {
		add_filter( 'user_can_richedit' , '__return_true', 50 );
	}
}

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function bt_bb_add_meta_box() {
	$options = get_option( 'bt_bb_settings' );
	$post_types = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ) );
	$active_pt = array();
	foreach( $post_types as $pt ) {
		if ( ! $options || ( ! array_key_exists( $pt, $options ) || ( array_key_exists( $pt, $options ) && $options[ $pt ] == '1' ) ) ) {
			$active_pt[] = $pt;
		}
	}

	$screens = $active_pt;

	foreach( $screens as $screen ) {
		add_meta_box(
			'bt_bb_sectionid',
			esc_html__( 'Bold Builder', 'bold-builder' ),
			'bt_bb_meta_box_callback',
			$screen,
			'normal',
			'high',
			array(
				'__back_compat_meta_box' => true,
				/*'__block_editor_compatible_meta_box' => true,*/
				/*'__block_editor_compatible_meta_box' => false,*/
			)
		);
	}
}
add_action( 'add_meta_boxes', 'bt_bb_add_meta_box' );

/**
 * Get size information for all currently-registered image sizes.
 *
 * @global $_wp_additional_image_sizes
 * @uses   get_intermediate_image_sizes()
 * @return array $sizes Data for all currently-registered image sizes.
 */
function bt_bb_get_image_sizes() {
	global $_wp_additional_image_sizes;

	foreach ( get_intermediate_image_sizes() as $_size ) {
		$sizes[ $_size ] = $_size;
		/*if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}*/
	}

	$sizes[ 'full' ] = 'full';

	return array_reverse( $sizes );
}

/**
 * Initial data.
 */
class BT_BB_Data_Proxy {
	function __construct( $data ) {
		$this->data = $data;
	}

	public function js() {
		echo '<script>
			var bt_bb_data = {	
				title: "_root",
				base: "_root",
				key: "' . uniqid( 'bt_bb_' ) . '",
				children: ' . $this->data . '
			}
		</script>';
	}
}

function bt_bb_force_tinymce() {
    return 'tinymce';
}
add_filter( 'wp_default_editor', 'bt_bb_force_tinymce' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function bt_bb_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'bt_bb_meta_box', 'bt_bb_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, '_my_meta_value_key', true );

	$content = $post->post_content;
	
	if ( function_exists( 'mb_convert_encoding' ) ) {
		$content = mb_convert_encoding( $content, 'UTF-8' );
	}
	
	global $bt_bb_array;
	$bt_bb_array = array();

	bt_bb_do_shortcode( $content );

	$json_content = bt_bb_json_encode( $bt_bb_array );
	
	$screen = get_current_screen();
	
	global $bt_bb_is_bb_content;
	if ( $json_content == '[]' ) {
		$bt_bb_is_bb_content = 'false';
	} else {
		$bt_bb_is_bb_content = 'true';
	}
	
	if ( $screen->post_type == 'page' && $screen->action == 'add' ) {
		$bt_bb_is_bb_content = 'true';
	}
	
	if ( function_exists( 'wp_enqueue_code_editor' ) ) {
		wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
	}	
	
	echo '<div id="bt_bb"></div><div id="bt_bb_add_root"><i></i></div>';
	
	bt_bb_edit_dialog();
	
	echo '<div id="bt_bb_front_end_preview_container"><div id="bt_bb_front_end_preview" class="bt_bb_dialog">';
		echo '<div class="bt_bb_dialog_header">';
			echo '<div class="bt_bb_front_end_preview_resize bt_bb_front_end_preview_xl bt_bb_front_end_preview_selected" data-preview-size="xl"></div>';
			echo '<div class="bt_bb_front_end_preview_resize bt_bb_front_end_preview_lg" data-preview-size="lg"></div>';
			echo '<div class="bt_bb_front_end_preview_resize bt_bb_front_end_preview_md" data-preview-size="md"></div>';
			echo '<div class="bt_bb_front_end_preview_resize bt_bb_front_end_preview_sm" data-preview-size="sm"></div>';
			echo '<div class="bt_bb_front_end_preview_resize bt_bb_front_end_preview_xs" data-preview-size="xs"></div>';
			echo '<div class="bt_bb_front_end_preview_close bt_bb_dialog_close"></div>';
			echo '<span>' . esc_html__( 'Preview', 'bold-builder' ) . '</span>';
		echo '</div>';
		echo '<iframe name="bt_bb_front_end_preview_iframe"></iframe>';
	echo '</div></div>';
	
	echo '<div id="bt_bb_main_toolbar">';
	echo '<i class="bt_bb_undo" title="' . esc_html__( 'Undo', 'bold-builder' ) . '"></i>';
	echo '<i class="bt_bb_redo" title="' . esc_html__( 'Redo', 'bold-builder' ) . '"></i>';
		echo '<span class="bt_bb_separator">|</span>';
	echo '<i class="bt_bb_paste_root" title="' . esc_html__( 'Paste', 'bold-builder' ) . '"></i>';
	echo '<span class="bt_bb_cb_items"></span>';
	echo '<i class="bt_bb_manage_clipboard" title="' . esc_html__( 'Clipboard Manager', 'bold-builder' ) . '"></i>';
		echo '<span class="bt_bb_separator">|</span>';
	echo '<i class="bt_bb_preview" title="' . esc_html__( 'Preview', 'bold-builder' ) . '"></i>';
	echo '<i class="bt_bb_save" title="' . esc_html__( 'Save', 'bold-builder' ) . '"></i>';
	echo '</div>';

	add_action( 'admin_footer', array( new BT_BB_Data_Proxy( $json_content ), 'js' ) );

}

function bt_bb_do_shortcode( $content ) {
	global $shortcode_tags;
	if ( ! ( ( empty( $shortcode_tags ) || ! is_array( $shortcode_tags ) ) ) ) {
		$pattern = get_shortcode_regex();
		
		global $bt_bb_array;
		
		$callback = new BT_BB_Callback( $bt_bb_array );
		
		$preg_cb = preg_replace_callback( "/$pattern/s", array( $callback, 'bt_bb_do_shortcode_tag' ), $content );
	}
}

class BT_BB_Callback {

	private $bt_bb_array;

    function __construct( &$bt_bb_array ) {
        $this->bt_bb_array = &$bt_bb_array;
    }

	function bt_bb_do_shortcode_tag( $m ) {
		global $shortcode_tags;
		
		global $bt_bb_map;

		// allow [[foo]] syntax for escaping a tag
		if ( $m[1] == '[' && $m[6] == ']' ) {
			return $m[0];
		}

		$tag = $m[2];
		$attr = shortcode_parse_atts( $m[3] );

		if ( is_array( $attr ) ) {
			$this->bt_bb_array[] = array( 'title' => $tag, 'base' => $tag, 'key' => str_replace( '.', '', uniqid( 'bt_bb_', true ) ), 'attr' => bt_bb_json_encode( $attr ), 'children' => array() );
		} else {
			$this->bt_bb_array[] = array( 'title' => $tag, 'base' => $tag, 'key' => str_replace( '.', '', uniqid( 'bt_bb_', true ) ), 'children' => array() );
		}

		if ( isset( $m[5] ) && $m[5] != '' ) {
			// enclosing tag - extra parameter
			$pattern = get_shortcode_regex();
			
			if ( isset( $bt_bb_map[ $m[2] ]['accept']['_content'] ) && $bt_bb_map[ $m[2] ]['accept']['_content'] ) {
				$r = $m[5];
			} else {
				$callback = new BT_BB_Callback( $this->bt_bb_array[ count( $this->bt_bb_array ) - 1 ]['children'] );
				$r = preg_replace_callback( "/$pattern/s", array( $callback, 'bt_bb_do_shortcode_tag' ), $m[5] );
				$r = trim( $r );
			}
		
			if ( $r != '' ) {
				$this->bt_bb_array[ count( $this->bt_bb_array ) - 1 ]['children'][0] = array( 'title' => '_content', 'base' => '_content', 'content' => $r, 'children' => array() );
			}
		}
	}
}

function bt_bb_edit_dialog() {
	echo '<div id="bt_bb_dialog" class="bt_bb_dialog">';
		echo '<div class="bt_bb_dialog_header"><div class="bt_bb_dialog_close"></div><span></span></div>';
		echo '<div class="bt_bb_dialog_header_tools"></div>';
		echo '<div class="bt_bb_dialog_content">';
		echo '</div>';
		echo '<div class="bt_bb_dialog_tinymce">';
			echo '<div class="bt_bb_dialog_tinymce_editor_container">';
				wp_editor( '' , 'bt_bb_tinymce', array( 'textarea_rows' => 12 ) );
			echo '</div>';
			echo '<input type="button" class="bt_bb_dialog_button bt_bb_edit button button-small" value="' . esc_html__( 'Submit', 'bold-builder' ) . '">';
		echo '</div>';
	echo '</div>';
}

function bt_bb_active_for_post_type_fe() {
	$options = get_option( 'bt_bb_settings' );
	$post_types = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ) );
	$active_pt = array();
	foreach( $post_types as $pt ) {
		if ( ! $options || ( ! array_key_exists( $pt, $options ) || ( array_key_exists( $pt, $options ) && $options[ $pt ] == '1' ) ) ) {
			$active_pt[ $pt ] = $pt;
		}
	}
	$post_type = get_post_type();
	if ( $post_type ) {
		return array_key_exists( $post_type, $active_pt );
	}
	return false;
}

function bt_bb_json_encode( $arg ) {
	return json_encode($arg);
}

function bt_bb_json_decode( $arg ) {
	return json_decode($arg);
}

/* General publish date filter */

function bt_bb_publish_hide_callback( $output, $atts ) {
	$now = date("Y-m-d H:m", time());
	if ( !isset( $atts['publish_datetime'] ) || $atts['publish_datetime'] == '' ) {
		$publish_datetime = date("Y-m-d H:m", time() - 60 * 60 * 24);
	} else  {
		$publish_datetime =  str_replace( "T", " ", $atts['publish_datetime']);
	}
	if ( !isset( $atts['expiry_datetime'] ) || $atts['expiry_datetime'] == '' ) {
		$expiry_datetime = date("Y-m-d H:m", time() + 60 * 60 * 24);
	} else {
		$expiry_datetime = str_replace( "T", " ", $atts['expiry_datetime']);
	}
	
	if ( $now >= $publish_datetime && $now <= $expiry_datetime ) {
		return $output;	
	} else {
		return "";
	}
}

add_filter( 'bt_bb_general_output', 'bt_bb_publish_hide_callback', 10, 2 );

class BT_BB_Basic_Element {
	
	protected $shortcode;
	protected $prefix;
	protected $prefix_backend;
	
	function __construct() {
		$this->init();
	}

	function init() {
		$this->prefix = 'bt_bb_';
		$this->prefix_backend = 'bt_bb_';
		$this->shortcode = get_class( $this );
		add_shortcode( $this->shortcode, array( $this, 'handle_shortcode' ) );
		add_action( 'wp_loaded', array( $this, 'map_shortcode' ) );
		//$this->map_shortcode();
		$this->add_params();
		add_filter( 'bt_bb_extract_atts_' . $this->shortcode, array( $this, 'atts_callback' ) );
		add_filter( 'bt_bb_extract_atts', array( $this, 'atts_callback' ) ); // older themes override content elements
		add_filter( $this->shortcode . '_class', array( $this, 'responsive_hide_callback' ), 10, 2 );
		add_filter( $this->shortcode . '_class', array( $this, 'animation_callback' ), 10, 2 );
		//add_filter( $this->shortcode . '_output', array( $this, 'data_shortcode' ), 10, 2 );
	}
	
	function add_params() {
		
	}
	
	function atts_callback( $atts ) {
		
	}

	function responsive_hide_callback( $class, $atts ) {
		if ( isset( $atts['responsive'] ) && $atts['responsive'] != '' ) {
			$class = array_merge( $class, preg_filter('/^/', $this->prefix, explode( ' ', $atts['responsive'] ) ) );
		}
		return $class;
	}

	function animation_callback( $class, $atts ) {
		if ( isset( $atts['animation'] ) && $atts['animation'] != 'no_animation' ) {
			foreach ( explode( ' ', $atts['animation'] ) as $index) {
				$class[] = $this->prefix . 'animation' . '_' . $index;
			}
			/*$class[] = $this->prefix . 'animation' . '_' . $atts['animation'];*/
			$class[] = 'animate';
		}
		return $class;
	}
	
	function data_shortcode( $output, $atts ) {
		if ( current_user_can( 'edit_posts' ) ) {
			return preg_replace( '/(<\w+)/', '$1 data-shortcode="' . $this->shortcode . '" data-atts="' . htmlspecialchars( json_encode( $atts, JSON_FORCE_OBJECT ) ) . '"', $output, 1 );
		} else {
			return $output;
		}
	}

	function to_uc( $str ) {
		$str = str_replace( '_', ' ', $str );
		$str = ucwords( $str );
		$str = str_replace( ' ', '', $str );
		return $str;
	}		
	
	function to_camel( $str ) {
		$str = $this->to_uc( $str );
		$str = lcfirst( $str );
		return $str;
	}

}

class BT_BB_Element extends BT_BB_Basic_Element {

	function add_params() {
		
		$params = array(
			array( 'param_name' => 'responsive', 'type' => 'checkbox_group', 'heading' => esc_html__( 'Hide element on screens', 'bold-builder' ), 'group' => esc_html__( 'Responsive', 'bold-builder' ), 'preview' => true,
				'value' => array(
					esc_html__( '≤480px', 'bold-builder' ) => 'hidden_xs',
					esc_html__( '481-768px', 'bold-builder' ) => 'hidden_ms',
					esc_html__( '769-992px', 'bold-builder' ) => 'hidden_sm',
					esc_html__( '993-1200px', 'bold-builder' ) => 'hidden_md',
                    esc_html__( '>1200px', 'bold-builder' ) => 'hidden_lg',
				)
			),
			array( 'param_name' => 'publish_datetime', 'type' => 'datetime-local', 'heading' => esc_html__( 'Publish date', 'bold-builder' ), 'description' => esc_html__( 'Please, fill both the date and time', 'bold-builder' ), 'group' => esc_html__( 'Custom', 'bold-builder' ), 'weight' => 990 ),
			array( 'param_name' => 'expiry_datetime', 'type' => 'datetime-local', 'heading' => esc_html__( 'Expiry date', 'bold-builder' ), 'description' => esc_html__( 'Please, fill both the date and time', 'bold-builder' ), 'group' => esc_html__( 'Custom', 'bold-builder' ), 'weight' => 995 ),
			array( 'param_name' => 'el_id', 'type' => 'textfield', 'heading' => esc_html__( 'Element ID', 'bold-builder' ), 'group' => esc_html__( 'Custom', 'bold-builder' ), 'weight' => 1000 ),
			array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => esc_html__( 'Extra class name(s)', 'bold-builder' ), 'group' => esc_html__( 'Custom', 'bold-builder' ), 'weight' => 1005 ),
			array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => esc_html__( 'Inline style', 'bold-builder' ), 'group' => esc_html__( 'Custom', 'bold-builder' ), 'weight' => 1010 )
		);
		
		$bt_bb_skip_animation_arr = [ 'bt_bb_slider', 'bt_bb_content_slider', 'bt_bb_content_slider_item', 'bt_bb_accordion_item', 'bt_bb_tab_item', 'bt_bb_google_maps_location', 'bt_bb_leaflet_map_location' ];
		
		if ( !in_array( $this->shortcode, $bt_bb_skip_animation_arr ) ) {
			$params[] = array( 'param_name' => 'animation', 'type' => 'dropdown', 'heading' => esc_html__( 'Animation', 'bold-builder' ), 'group' => esc_html__( 'Custom', 'bold-builder' ), 'preview' => true, 'weight' => 997,
				'value' => array(
					esc_html__( 'No Animation', 'bold-builder' ) => 'no_animation',
					esc_html__( 'Fade In', 'bold-builder' ) => 'fade_in',
					esc_html__( 'Move Up', 'bold-builder' ) => 'move_up',
					esc_html__( 'Move Left', 'bold-builder' ) => 'move_left',
					esc_html__( 'Move Right', 'bold-builder' ) => 'move_right',
					esc_html__( 'Move Down', 'bold-builder' ) => 'move_down',
					esc_html__( 'Zoom in', 'bold-builder' ) => 'zoom_in',
					esc_html__( 'Zoom out', 'bold-builder' ) => 'zoom_out',
					esc_html__( 'Fade In / Move Up', 'bold-builder' ) => 'fade_in move_up',
					esc_html__( 'Fade In / Move Left', 'bold-builder' ) => 'fade_in move_left',
					esc_html__( 'Fade In / Move Right', 'bold-builder' ) => 'fade_in move_right',
					esc_html__( 'Fade In / Move Down', 'bold-builder' ) => 'fade_in move_down',
					esc_html__( 'Fade In / Zoom in', 'bold-builder' ) => 'fade_in zoom_in',
					esc_html__( 'Fade In / Zoom out', 'bold-builder' ) => 'fade_in zoom_out'
				)
			);
		}
		
		bt_bb_add_params( $this->shortcode, $params );

		/*if ( $this->shortcode == 'bt_bb_content_slider' ) {      
			bt_bb_remove_params( $this->shortcode, 'animation' );
		}*/
	}
	
	function atts_callback( $atts ) {
		return array(
			'responsive' 	=> '',
			'animation' 	=> '',
			'el_id'      	=> '',
			'el_class'   	=> '',
			'el_style'   	=> ''
		) + $atts;
	}
	
	function responsive_override_class( &$class, $arr ) {
		if ( $arr['value'] != '' ) {
			$value_arr = explode( '%$%', $arr['value'] );
			if ( isset( $arr['suffix'] ) ) {
				$suffix = $arr['suffix'];
			} else {
				$suffix = '_';
			}
			$main = $arr['prefix'] . $arr['param'] . $suffix . $value_arr[0];
			if ( ! ( isset( $arr['ignore'] ) && $arr['ignore'] == $value_arr[0] ) ) {
				$class[] = $main;
			}
			if ( count( $value_arr ) == 5 ) {
				if ( $value_arr[1] != '' ) {
					$class[] = $arr['prefix'] . $arr['param'] . '_lg' . $suffix . $value_arr[1];
				}
				if ( $value_arr[2] != '' ) {
					$class[] = $arr['prefix'] . $arr['param'] . '_md' . $suffix . $value_arr[2];
				}
				if ( $value_arr[3] != '' ) {
					$class[] = $arr['prefix'] . $arr['param'] . '_sm' . $suffix . $value_arr[3];
				}
				if ( $value_arr[4] != '' ) {
					$class[] = $arr['prefix'] . $arr['param'] . '_xs' . $suffix . $value_arr[4];
				}
			}
		}
	}
	
	function responsive_data_override_class( &$class, &$data_override_class, $arr ) {
		if ( $arr['value'] != '' ) {
			$value_arr = explode( '%$%', $arr['value'] );
			if ( isset( $arr['suffix'] ) ) {
				$suffix = $arr['suffix'];
			} else {
				$suffix = '_';
			}
			
			$main = $arr['prefix'] . $arr['param'] . $suffix . $value_arr[0];
			
			$class[] = $main;
			
			if ( count( $value_arr ) == 5 ) {
				$data_override_class[ $arr['prefix'] . $arr['param'] . $suffix ][ 'current_class' ] = $main;
				$data_override_class[ $arr['prefix'] . $arr['param'] . $suffix ][ 'xl' ] = $value_arr[0];

				if ( $value_arr[1] != '' ) {
					$data_override_class[ $arr['prefix'] . $arr['param'] . $suffix ][ 'lg' ] = $value_arr[1];
				}
				if ( $value_arr[2] != '' ) {
					$data_override_class[ $arr['prefix'] . $arr['param'] . $suffix ][ 'md' ] = $value_arr[2];
				}
				if ( $value_arr[3] != '' ) {
					$data_override_class[ $arr['prefix'] . $arr['param'] . $suffix ][ 'sm' ] = $value_arr[3];
				}
				if ( $value_arr[4] != '' ) {
					$data_override_class[ $arr['prefix'] . $arr['param'] . $suffix ][ 'xs' ] = $value_arr[4];
				}
			}
		}
	}
	
}

$elements = array();

if ( ! class_exists( 'BoldThemes_BB_Settings' ) || ! BoldThemes_BB_Settings::$custom_content_elements ) {

	$glob_match = glob( plugin_dir_path( __FILE__ ) . 'content_elements/*/*.php' );
	$glob_match = apply_filters( 'bt_bb_content_elements', $glob_match );
	if ( $glob_match ) {
		foreach( $glob_match as $file ) {
			if ( preg_match( '/(\w+)\/\1.php$/', $file, $match ) ) {
				$elements[ $match[1] ] = $file;
			}
		}
	}

	$glob_match = glob( WP_PLUGIN_DIR . '/' . get_template() . '/bold-page-builder/content_elements/*/*.php' );
	if ( $glob_match ) {
		foreach( $glob_match as $file ) {
			if ( preg_match( '/(\w+)\/\1.php$/', $file, $match ) ) {
				$elements[ $match[1] ] = $file;
			}
		}
	}
}

$glob_match = glob( get_template_directory() . '/bold-page-builder/content_elements/*/*.php' );
if ( $glob_match ) {
	foreach( $glob_match as $file ) {
		if ( preg_match( '/(\w+)\/\1.php$/', $file, $match ) ) {
			$elements[ $match[1] ] = $file;
		}
	}
}

$glob_match = glob( get_stylesheet_directory() . '/bold-page-builder/content_elements/*/*.php' );
if ( $glob_match ) {
	foreach( $glob_match as $file ) {
		if ( preg_match( '/(\w+)\/\1.php$/', $file, $match ) ) {
			$elements[ $match[1] ] = $file;
		}
	}
}

foreach( $elements as $key => $value ) {
	require( $value );
	new $key();
}

/* Widgets */

if ( ! class_exists( 'BoldThemes_BB_Settings' ) || ! BoldThemes_BB_Settings::$custom_content_elements ) { // only with native BB elements
	require_once( plugin_dir_path( __FILE__ ) . 'widgets/init.php' );
	//include 'admin-notice.php'; // ### Instagram ###
	include 'admin-notice-rating.php';
}

$options = get_option( 'bt_bb_settings' );
if ( ! $options || ( ! array_key_exists( 'tips', $options ) || ( array_key_exists( 'tips', $options ) && $options['tips'] == '1' ) ) ) {
	include 'tips.php';
}
