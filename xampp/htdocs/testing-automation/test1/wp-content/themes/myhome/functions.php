<?php

function myhome_php_error() {
	echo '<div style="background: red; padding: 24px; font-size: 36px; line-height: 1.5; margin: 10px 20px 20px 0; position: relative; color: #fff;">';
	echo 'Your PHP version is ' . PHP_VERSION . '. MyHome requires server PHP version 5.6. or higher. ';
	echo '<a style="color:#fff" target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360001522353-Server-requirements-PHP-version">Click here to read more about this problem and how to fix it</a>.';
	echo '</div>';
}

if ( version_compare( PHP_VERSION, '5.6.0', '<' ) ) {
	add_action( 'admin_notices', 'myhome_php_error' );

	return;
}

define( 'DISABLE_ULTIMATE_GOOGLE_MAP_API', true );

if ( ! isset( $content_width ) ) {
	$content_width = 1920;
}

require_once get_template_directory() . '/includes/class-myhome.php';

function My_Home_Theme() {
	return My_Home::get_instance();
}

// initiate MyHome theme
My_Home_Theme()->init();
