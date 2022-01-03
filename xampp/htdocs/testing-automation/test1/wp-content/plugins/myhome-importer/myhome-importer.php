<?php
/*
Plugin Name: MyHome Demo Importer
Description: This plugin adds possibility to load MyHome demo content
Version: 3.15
Plugin URI: https://myhometheme.net
 */

if ( version_compare( PHP_VERSION, '5.6.0', '<' ) ) {
	return;
}

if ( isset( $_GET['page'] ) && $_GET['page'] === 'myhome_importer' && is_admin() ) {
	function myhome_importer_plugins_error() {
		?>
        <div style="padding: 24px;
    background: red;
    color: #fff;
    font-size: 24px;
    line-height: 1.5;
    margin: 10px 20px 20px 0;">
            <div style="margin-bottom:10px;"><strong>Before importing demo content please install and activate all
                    required plugins:</strong></div>
			<?php echo is_plugin_active( 'myhome-core/myhome-core.php' ) ? '' : '<div>- MyHome Core</div>'; ?>
			<?php echo is_plugin_active( 'js_composer/js_composer.php' ) ? '' : '<div>- WPBakery Visual Composer</div>'; ?>
			<?php echo is_plugin_active( 'revslider/revslider.php' ) ? '' : '<div>- Slider Revolution</div>'; ?>
			<?php echo is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ? '' : '<div>- Advanced Custom Fields PRO </div>'; ?>
			<?php echo is_plugin_active( 'mega_main_menu/mega_main_menu.php' ) ? '' : '<div>- Mega Main Menu</div>'; ?>
			<?php echo is_plugin_active( 'redux-framework/redux-framework.php' ) ? '' : '<div>- Redux Framework</div>'; ?>
            <div>
                If you cannot install one of the plugins because you see "Installation failed: Destination folder already exists." - <a href="https://myhometheme.zendesk.com/hc/en-us/articles/360019834314" target="_blank" style="color:#fff!important; text-decoration:underline!important;">please click here to read how to fix it</a>
            </div>
        </div>
		<?php
	}

	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	if (
		! is_plugin_active( 'myhome-core/myhome-core.php' )
		|| ! is_plugin_active( 'js_composer/js_composer.php' )
		|| ! is_plugin_active( 'revslider/revslider.php' )
		|| ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' )
		|| ! is_plugin_active( 'mega_main_menu/mega_main_menu.php' )
		|| ! is_plugin_active( 'redux-framework/redux-framework.php' )
	) {
		add_action( 'admin_notices', 'myhome_importer_plugins_error' );
	}

}
include_once 'includes/class-myhome-importer.php';
$myhome_importer = new My_Home_Importer();