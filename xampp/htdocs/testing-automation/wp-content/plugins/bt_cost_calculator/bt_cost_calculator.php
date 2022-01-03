<?php

/**
 * Plugin Name: Cost Calculator
 * Description: Cost Calculator by BoldThemes
 * Version: 1.2.7
 * Author: BoldThemes
 * Author URI: http://codecanyon.net/user/boldthemes
 */

function bt_cc_enqueue() {
	wp_enqueue_script( 'bt_cc_dd', plugins_url() . '/bt_cost_calculator/jquery.dd.js', array( 'jquery' ) );
	wp_enqueue_script( 'bt_cc_main', plugins_url() . '/bt_cost_calculator/cc.main.js', array( 'jquery' ) );
	wp_enqueue_style( 'bt_cc_style', plugins_url() . '/bt_cost_calculator/style.min.css' );
}
add_action( 'wp_enqueue_scripts', 'bt_cc_enqueue' );

global $bt_recaptcha;
$bt_recaptcha = false;

function bt_enqueue_recaptcha() {
	global $bt_recaptcha;
	if ( ! $bt_recaptcha ) {
		?>
		<script>
			var BTCaptchaCallback = function() {
				jQuery( '.g-rec' ).each(function() {
					var widget_id = grecaptcha.render( jQuery( this ).attr( 'id' ), { 'sitekey' : jQuery( this ).data( 'sk' ) } );
					jQuery( this ).data( 'widget_id', widget_id );
				});
			};
		</script>
		<?php
		echo '<script src="https://www.google.com/recaptcha/api.js?onload=BTCaptchaCallback&render=explicit" async defer></script>';
		$bt_recaptcha = true;
	}
}

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function bt_cc_load_textdomain() {
  load_plugin_textdomain( 'bt_plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'bt_cc_load_textdomain' );

// [bt_cost_calculator]
class bt_cost_calculator {
	
	public static $date_text;
	public static $time_text;

	static function init() {
		add_shortcode( 'bt_cost_calculator', array( __CLASS__, 'handle_shortcode' ) );
		add_action( 'wp_ajax_bt_cc', array( __CLASS__, 'bt_cc_callback' ) );
		add_action( 'wp_ajax_nopriv_bt_cc', array( __CLASS__, 'bt_cc_callback' ) );
	}
	
	static function bt_cc_callback() {
		$recaptcha_response = $_POST['recaptcha_response'];
		$recaptcha_secret = $_POST['recaptcha_secret'];
		$admin_email = $_POST['admin_email'];
		$email_client = $_POST['email_client'];
		$url_confirmation = $_POST['url_confirmation'];
		$currency = $_POST['currency'];
		$currency_after = $_POST['currency_after'];		
		$email_confirmation = $_POST['email_confirmation'];
		$subject = urldecode( $_POST['subject'] );
		$quote = urldecode( $_POST['quote'] );
		$total = $_POST['total'];
		$name = $_POST['name'];
		$email = strip_tags( $_POST['email'] );
		$phone = $_POST['phone'];
		$address = $_POST['address'];
		$date = isset($_POST['date'])?$_POST['date']:'';
		$time = isset($_POST['time'])?$_POST['time']:'';
		$date_text = isset($_POST['date_text'])?$_POST['date_text']:'';
		$time_text = isset($_POST['time_text'])?$_POST['time_text']:'';
		$message = $_POST['message'];
		
		if ( $recaptcha_response == '' && $recaptcha_secret != '' ) {
			die();
		}
		
		if ( $recaptcha_response != '' && $recaptcha_secret != '' ) {
			$recaptcha_post = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array( 'body' => array( 'secret' => $recaptcha_secret, 'response' => $recaptcha_response ) ) );
			if ( is_wp_error( $recaptcha_post ) ) {
				echo 'recaptcha post error';
				die();
			} else {
				$json = json_decode( $recaptcha_post['body'] );
				if ( $json->success != 1 ) {
					echo 'recaptcha response false';
					die();
				}
			}
		}
		
		$message_to_admin = '<html><body>' . "\r\n";
		
		$message_to_admin .= '<table style="width:100%" cellspacing="0">' . "\r\n";
		if ( $quote != '' ) $message_to_admin .= $quote;
		
		if ( $currency != '' ) {
			if ( $currency_after == 'yes' ) {
				$total = $total . ' ' . $currency;
			} else {
				$total = $currency . ' ' . $total;
			}
		}
		
		$message_to_admin .= '<tr><td style="font-weight:bold;border-top:1px solid #888;padding:.5em;">' . __( 'Total', 'bt_plugin' ) . '</td><td style="text-align:right;font-weight:bold;border-top:1px solid #888;padding:.5em;">' . $total . '</td></tr>' . "\r\n";
		
		$message_to_admin .= '</table>' . "\r\n";
		
		$message_to_admin .= '<br>' . "\r\n";
	
		if ( $name != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Name', 'bt_plugin' ) . '</b>: ' . stripslashes( $name ) . '</div>' . "\r\n";
		if ( $email != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Email', 'bt_plugin' ) . '</b>: <a href="mailto:' . $email . '">' . $email . '</a></div>' . "\r\n";
		if ( $phone != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Phone', 'bt_plugin' ) . '</b>: ' . $phone . '</div>' . "\r\n";
		if ( $address != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Address', 'bt_plugin' ) . '</b>: ' . stripslashes( $address ) . '</div>' . "\r\n";
		if ( $date != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . $date_text . '</b>: ' . $date . '</div>' . "\r\n";
		if ( $time != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . $time_text . '</b>: ' . $time . '</div>' . "\r\n";
		if ( $message != '' ) $message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Message', 'bt_plugin' ) . '</b>: ' . stripslashes( $message ) . '</div>' . "\r\n";
		
		$message_to_admin .= '</body></html>';
		
		//$message_to_admin = quoted_printable_encode( $message_to_admin );
	
		$s = $subject;
		if ( $name != '' ) $s = $s . ' / ' . $name;
		
		try{
			$r = true;
			if ( $email_client == 'yes' && $email != '' &&  $email_confirmation == 'yes' ) {
				$headers = "From: " . $admin_email . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				//$headers .= "Content-Transfer-Encoding: quoted-printable";
				$r = wp_mail( $email, $s, $message_to_admin, $headers );
			}
			$headers = '';
			//if ( $email != '' ) $headers = "From: " . $email . "\r\n"; // todo: email validation
			$headers .= "Reply-to: " . $email . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			//$headers .= "Content-Transfer-Encoding: quoted-printable";
			$r1 = wp_mail( $admin_email, $s, $message_to_admin, $headers );
			if ( $r && $r1 ) echo 'ok';
		} catch ( Exception $e ) {
			echo $e->getMessage();
		}
		
		die();
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'admin_email'        => '',
			'cf7_id'        => '',
			'subject'            => '',
			'email_client'       => '',
			'email_confirmation' => '',
			'url_confirmation'	 => '',
			'time_start'         => '',
			'time_end'           => '',
			'currency'           => '',
			'currency_after'     => '',
			'm_name'             => '',
			'm_email'            => '',
			'm_phone'            => '',
			'm_address'          => '',
			'm_date'             => '',
			'm_time'             => '',
			'm_message'          => '',
			'show_next'			 => '',
			'accent_color'       => '',
			'show_booking'       => '',
			'date_text'          => __( 'Preferred Service Date', 'bt_plugin' ),
			'time_text'          => __( 'Preferred Service Time', 'bt_plugin' ),
			'total_text'         => __( 'Total', 'bt_plugin' ),
			'rec_site_key'       => '',
			'rec_secret_key'     => '',
			'paypal_email'       => '',
			'paypal_cart_name'   => '',
			'paypal_currency'    => '',
			'el_class'           => '',
			'el_style'           => ''
		), $atts, 'bt_cost_calculator' ) );
		
		bt_cost_calculator::$date_text = $date_text;
		bt_cost_calculator::$time_text = $time_text;
		
		$admin_email = sanitize_text_field( $admin_email );
		$cf7_id = sanitize_text_field( $cf7_id );
		$subject = sanitize_text_field( $subject );
		$email_client = sanitize_text_field( $email_client );
		$email_confirmation = sanitize_text_field( $email_confirmation );
		$url_confirmation = sanitize_text_field( $url_confirmation );
		$time_start = sanitize_text_field( $time_start );
		$time_end = sanitize_text_field( $time_end );
		$currency = sanitize_text_field( $currency );
		$m_name = sanitize_text_field( $m_name );
		$m_email = sanitize_text_field( $m_email );
		$m_phone = sanitize_text_field( $m_phone );
		$m_address = sanitize_text_field( $m_address );
		$m_date = sanitize_text_field( $m_date );
		$m_time = sanitize_text_field( $m_time );
		$m_message = sanitize_text_field( $m_message );
		$show_next = sanitize_text_field( $show_next );
		$accent_color = sanitize_text_field( $accent_color );
		$show_booking = sanitize_text_field( $show_booking );
		$total_text = sanitize_text_field( $total_text );
		$rec_site_key = sanitize_text_field( $rec_site_key );
		$rec_secret_key = sanitize_text_field( $rec_secret_key );
		$paypal_email = sanitize_text_field( $paypal_email );
		$paypal_cart_name = sanitize_text_field( $paypal_cart_name );
		$paypal_currency = sanitize_text_field( $paypal_currency );
		$el_class = sanitize_text_field( $el_class );
		$el_style = sanitize_text_field( $el_style );
		
		
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		
		wp_enqueue_script( 'jquery-ui-slider' );
		
		wp_enqueue_script( 'bt_touch-punch_js', plugins_url() . '/bt_cost_calculator/jquery.ui.touch-punch.min.js', array( 'jquery-ui-slider' ) );
		
		$css_class = uniqid( 'c' );
		
		$proxy = new Cost_Proxy( $admin_email, $email_client, $email_confirmation, $url_confirmation, $subject, $m_name, $m_email, $m_phone, $m_address, $m_message, $m_date, $m_time, $accent_color, $rec_secret_key, $css_class, $currency, $currency_after );
		add_action( 'wp_footer', array( $proxy, 'js_init' ), 20 );
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = 'style="' . $el_style . '"';
		}
		
		if ( $m_name != '' ) $m_name = ' ' . 'btContactField' . $m_name;
		if ( $m_email != '' ) $m_email = ' ' . 'btContactField' . $m_email;
		if ( $m_phone != '' ) $m_phone = ' ' . 'btContactField' . $m_phone;
		if ( $m_address != '' ) $m_address = ' ' . 'btContactField' . $m_address;
		if ( $m_message != '' ) $m_message = ' ' . 'btContactField' . $m_message;
		if ( $m_date != '' ) $m_date = ' ' . 'btContactField' . $m_date;
		if ( $m_time != '' ) $m_time = ' ' . 'btContactField' . $m_time;

		$total_next_wrapper_style = "";
		//$total_next_wrapper_style = ( $show_next == '' ) ? ' style="padding-right: 0px !important;"' : '';		

		$output = '<div class="btQuoteBooking ' . $el_class . ' ' . $css_class . '" ' . $style_attr . ' data-admin_email="' .  $admin_email. '" data-cf7_id="' .  $cf7_id. '" data-email_client="' . $email_client . '" data-currency="' . $currency . '" data-currency_after="' . $currency_after . '" data-url_confirmation="' . $url_confirmation . '" data-url_ajax="' . admin_url( 'admin-ajax.php' ) . '" data-subject="' . $subject . '" data-date_text="' . $date_text . '" data-time_text="' . $time_text . '" data-message_please_wait="' . __( 'Please wait...', 'bt_plugin' ) . '" data-message_success="'. __( 'Thank you, we will contact you soon!', 'bt_plugin' ) .'" data-message_error="' . __( 'Error! Please try again later.', 'bt_plugin' ) . '" data-message_mandatory="' .  __( 'Please fill out all required fields.', 'bt_plugin' ) . '" data-rec_secret_key="' . $rec_secret_key . '"><div class="btQuoteBookingWrap">';
			$output .= '<div class="btQuoteBookingForm btActive">';
				$output .= wptexturize( do_shortcode( $content ) );
				$output .= '<div class="btTotalNextWrapper"' . $total_next_wrapper_style . '>';
					
					if ( $currency_after == 'yes' ) {
						$output .= '<div class="btQuoteTotal currencyAfter"><span class="btQuoteTotalText">' . $total_text . '</span><span class="btQuoteTotalCalc"></span><span class="btQuoteTotalCurrency">' . $currency . '</span></div>';
					} else {
						$output .= '<div class="btQuoteTotal"><span class="btQuoteTotalText">' . $total_text . '</span><span class="btQuoteTotalCurrency">' . $currency . '</span><span class="btQuoteTotalCalc"></span></div>';
					}
					
					if ( $paypal_email == '' ) {
						if ( $show_next != '' ) {
							$output .= '<div class="boldBtn btnAccent btnSmall btnIco"><button type="submit" class="btContactNext">' . __( 'Next', 'bt_plugin' ) . '</button></div>';
						}
					} else {
						$output .= '<div class="btPayPalButton" style="background-image:url(' . plugin_dir_url( __FILE__ ) . 'paypal.png);"></div><form class="btPayPalForm" action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="cmd" value="_cart">
						<input type="hidden" name="upload" value="1">
						<input type="hidden" name="business" value="' . $paypal_email . '">
						<input type="hidden" name="item_name" value="' . $paypal_cart_name . '">
						<input type="hidden" name="currency_code" value="' . $paypal_currency . '">
						<input type="image" src="' . plugin_dir_url( __FILE__ ) . 'paypal.png" name="submit" alt="PayPal">
						</form>';
					}
				$output .= '</div>';
			$output .= '</div>';
			
			if ( $paypal_email == '' && $show_next != '' ) {
			
				if ( $cf7_id == "" ) {
					
					$output .= '<div class="btTotalQuoteContactGroup">';

						$output .= '<div class="btQuoteContact">';
							$output .= '<div class="btQuoteItem' . $m_name . '"><input type="text" class="btContactName btContactField" placeholder="' . __( 'Name', 'bt_plugin' ) . '"></div>';
							$output .= '<div class="btQuoteItem' . $m_email . '"><input type="text" class="btContactEmail btContactField" placeholder="' . __( 'Email', 'bt_plugin' ) . '"></div>';
							$output .= '<div class="btQuoteItem' . $m_phone . '"><input type="text" class="btContactPhone btContactField" placeholder="' . __( 'Phone', 'bt_plugin' ) . '"></div>';
							$output .= '<div class="btQuoteItem' . $m_address . '"><input type="text" class="btContactAddress btContactField" placeholder="' . __( 'Address', 'bt_plugin' ) . '"></div>';
							
							if ( $show_booking != '' ) {
								$output .= '<div class="btQuoteItem' . $m_date . '"><input type="text" class="btContactDate btContactField" placeholder="' . $date_text . '"></div>';
								$output .= '<div class="btQuoteItem' . $m_time . '">';
									$output .= '<div class="btContactTime btContactField btDropDown"></div>';
									if ( $time_start == '' ) $time_start = 0;
									if ( $time_end == '' ) $time_end = 23;
									$proxy = new CostTime_Proxy( $time_start, $time_end, $time_text, $css_class );
									add_action( 'wp_footer', array( $proxy, 'js_init' ), 20 );
								$output .= '</div>';
							}
							
							$output .= '<div class="btQuoteItem' . $m_message . ' btQuoteItemFullWidth"><textarea class="btContactMessage btContactField" placeholder="' . __( 'Message', 'bt_plugin' ) . '"></textarea></div>';
							
							if ( $email_confirmation == 'yes' ) {
								$id = uniqid();
								$output .= '<div class="bt_cc_email_confirmation_container"><input id="' . $id . '" class="bt_cc_email_confirmation" type="checkbox" value="yes"><label for="' . $id . '">' . __( 'Email me quote!', 'bt_plugin' ) . '</label></div>';
							}						
							
							if ( $rec_site_key != '' && $rec_secret_key != '' ) {
								$id = uniqid();
								$output .= '<div id=' . $id . ' class="g-rec" data-sk="' . $rec_site_key . '"></div>';
								add_action( 'wp_footer', 'bt_enqueue_recaptcha' );
							}
							
							$output .= '<div class="boldBtn btnAccent btnSmall btnIco"><button type="submit" class="btContactSubmit">' . __( 'Submit', 'bt_plugin' ) . '</button></div>';
							
							$output .= '<div class="btSubmitMessage"></div>';
							
						$output .= '</div>';
					
					$output .= '</div>';				
				} else {
					// CF 7 support 
					
					$output .= '<div class="btTotalQuoteContactGroup"><div class="btQuoteContact btQuoteContactForm7">';
					$output .= do_shortcode('[contact-form-7 id="' . $cf7_id . '"]');
					$output .= '</div></div>';	
				} 
				

				
				
				
			}
			
		$output .= '</div>';
		$output .= '</div>';
		
		return $output;
	}
}

class Cost_Proxy {
	function __construct( $admin_email, $email_client, $email_confirmation, $url_confirmation, $subject, $m_name, $m_email, $m_phone, $m_address, $m_date, $m_time, $m_message, $accent_color, $rec_secret_key, $css_class, $currency, $currency_after ) {
		$this->admin_email = $admin_email;
		$this->email_client = $email_client;
		$this->email_confirmation = $email_confirmation;
		$this->url_confirmation = $url_confirmation;
		$this->subject = $subject;
		$this->m_name = $m_name;
		$this->m_email = $m_email;
		$this->m_phone = $m_phone;
		$this->m_address = $m_address;
		$this->m_date = $m_date;
		$this->m_time = $m_time;
		$this->m_message = $m_message;
		$this->accent_color = $accent_color;
		$this->rec_secret_key = $rec_secret_key;
		$this->css_class = $css_class;
		$this->currency = $currency;
		$this->currency_after = $currency_after;
	}	

	public function js_init() { ?>
		<script>
			(function( $ ) {
				
				var css_class = '<?php echo $this->css_class; ?>';
				var c = $( '.' + css_class );
				
				$( window ).ready(function() {
					setTimeout( function(){ c.css( 'opacity', '1' ); }, 200 );
				});
			
				var accent_color = '<?php echo $this->accent_color; ?>';
				
				if ( accent_color != '' ) {
					$( 'head' ).append( '<style>.btQuoteBooking.' + css_class + ' .btContactNext { color: ' + accent_color + ' !important; border: ' + accent_color + ' 2px solid !important; }.btQuoteBooking.' + css_class + '  input[type="text"]:hover, .btQuoteBooking.' + css_class + '  input[type="email"]:hover, .btQuoteBooking.' + css_class + '  input[type="password"]:hover, .btQuoteBooking.' + css_class + '  textarea:hover, .btQuoteBooking.' + css_class + '  .fancy-select .trigger:hover {	box-shadow: 0 0 0 ' + accent_color + ' inset, 0 1px 5px rgba(0,0,0,0.2) !important;}.btQuoteBooking.' + css_class + ' .dd.ddcommon.borderRadius:hover .ddTitleText {	box-shadow: 0 0 0 ' + accent_color + ' inset, 0 1px 5px rgba(0,0,0,0.2) !important;}.btQuoteBooking.' + css_class + '  input[type="text"]:focus, .btQuoteBooking.' + css_class + '  input[type="email"]:focus, .btQuoteBooking.' + css_class + '  textarea:focus, .btQuoteBooking.' + css_class + '  .fancy-select .trigger.open {	box-shadow: 5px 0 0 ' + accent_color + ' inset, 0 2px 10px rgba(0,0,0,0.2) !important;}.btQuoteBooking.' + css_class + ' .dd.ddcommon.borderRadiusTp .ddTitleText, .btQuoteBooking.' + css_class + ' .dd.ddcommon.borderRadiusBtm .ddTitleText {	box-shadow: 5px 0 0 ' + accent_color + ' inset, 0 2px 10px rgba(0,0,0,0.2) !important;}.btQuoteBooking.' + css_class + '  .ui-slider .ui-slider-handle {	background: ' + accent_color + ' !important;}.btQuoteBooking.' + css_class + ' .btQuoteBookingForm .btQuoteTotal {	background: ' + accent_color + ' !important;}.btQuoteBooking.' + css_class + '  .btContactFieldMandatory input:hover, .btQuoteBooking.' + css_class + '  .btContactFieldMandatory textarea:hover {	box-shadow: 0 0 0 1px #AAA inset, 0 0 0 ' + accent_color + ' inset, 0 1px 5px rgba(0,0,0,0.2) !important;}.btQuoteBooking.' + css_class + ' .btContactFieldMandatory .dd.ddcommon.borderRadius:hover .ddTitleText {	box-shadow: 0 0 0 1px #AAA inset, 0 0 0 ' + accent_color + ' inset, 0 1px 5px rgba(0,0,0,0.2) !important;}.btQuoteBooking.' + css_class + '  .btContactFieldMandatory input:focus, .btQuoteBooking.' + css_class + '  .btContactFieldMandatory textarea:focus {	box-shadow: 0 0 0 1px #AAA inset, 5px 0 0 ' + accent_color + ' inset, 0 1px 5px rgba(0,0,0,0.2) !important;}.btQuoteBooking.' + css_class + ' .btContactFieldMandatory .dd.ddcommon.borderRadiusTp .ddTitleText {	box-shadow: 0 0 0 1px #AAA inset, 5px 0 0 ' + accent_color + ' inset, 0 1px 5px rgba(0,0,0,0.2) !important;}.btQuoteBooking.' + css_class + '  .btContactFieldMandatory.btContactFieldError input, .btQuoteBooking.' + css_class + '  .btContactFieldMandatory.btContactFieldError textarea {	border: 1px solid ' + accent_color + ' !important;	box-shadow: 0 0 0 1px ' + accent_color + ' inset !important;}.btQuoteBooking.' + css_class + ' .btContactFieldMandatory.btContactFieldError .dd.ddcommon.borderRadius .ddTitleText {	border: 1px solid ' + accent_color + ' !important;	box-shadow: 0 0 0 1px ' + accent_color + ' inset !important;}.btQuoteBooking.' + css_class + '  .btContactFieldMandatory.btContactFieldError input:hover, .btQuoteBooking.' + css_class + '  .btContactFieldMandatory.btContactFieldError textarea:hover {	box-shadow: 0 0 0 1px ' + accent_color + ' inset, 0 0 0 ' + accent_color + ' inset, 0 1px 5px rgba(0,0,0,0.2) !important;}.btQuoteBooking.' + css_class + ' .btContactFieldMandatory.btContactFieldError .dd.ddcommon.borderRadius:hover .ddTitleText {	box-shadow: 0 0 0 1px ' + accent_color + ' inset, 0 0 0 ' + accent_color + ' inset, 0 1px 5px rgba(0,0,0,0.2) !important;}.btQuoteBooking.' + css_class + '  .btContactFieldMandatory.btContactFieldError input:focus, .btQuoteBooking.' + css_class + '  .btContactFieldMandatory.btContactFieldError textarea:focus {	box-shadow: 0 0 0 1px ' + accent_color + ' inset, 5px 0 0 ' + accent_color + ' inset, 0 1px 5px rgba(0,0,0,0.2) !important;}.btQuoteBooking.' + css_class + ' .btContactFieldMandatory.btContactFieldError .dd.ddcommon.borderRadiusTp .ddTitleText {	box-shadow: 0 0 0 1px ' + accent_color + ' inset, 5px 0 0 ' + accent_color + ' inset, 0 1px 5px rgba(0,0,0,0.2) !important;}.btQuoteBooking.' + css_class + ' .btSubmitMessage {	color: ' + accent_color + ' !important;}.btDatePicker .ui-datepicker-header {	background-color: ' + accent_color + ' !important;}.btQuoteBooking.' + css_class + ' .btContactSubmit {	background-color: ' + accent_color + ' !important;}.btQuoteBooking.' + css_class + ' .btQuoteSwitch.on .btQuoteSwitchInner{background: ' + accent_color + ' !important;}</style>' );
				}
				
	        })( jQuery );
			
		</script>
		
	<?php }
}

class CostTime_Proxy {
	function __construct( $time_start, $time_end, $title, $css_class ) {
		$this->time_start = $time_start;
		$this->time_end = $time_end;
		$this->title = $title;
		$this->css_class = $css_class;
	}	

	public function js_init() { ?>
		<script>
			(function( $ ) {
				var css_class = '<?php echo $this->css_class; ?>';
				var c = $( '.' + css_class );
				
				var bt_time_ddData = [
				<?php
					echo '{ text:\'' . $this->title . '\', value:\'\' },';
					for ( $i = intval( $this->time_start ); $i <= intval( $this->time_end ); $i++ ) {
						if ( $i < 10 ) $i = '0' . $i;
						echo '{ text: \'' . $i . ':00\', value: \'' . $i . ':00\' },';
					}
				?>
				];
				
				c.find( '.btContactTime' ).msDropDown({
					byJson:{data:bt_time_ddData},
					on:{change:function( data, ui ) {
						var val = data.value;
					}}
				});
				
	        })( jQuery );
			
		</script>
	<?php }
}

class CostDD_Proxy {
	function __construct( $dd_id, $items_arr, $title, $img_height, $initial_index ) {
		$this->dd_id = $dd_id;
		$this->items_arr = $items_arr;
		$this->title = $title;
		$this->img_height = $img_height;
		
		$this->vrednost = "";
		if ( $initial_index > 0 ){

			$items_arr2 =	$items_arr[$initial_index-1];
			$vrednost_arr = explode( ';', $items_arr2 );
			$this->vrednost = $vrednost_arr[1];
		}		
		
		if ( $initial_index > count($this->items_arr) ){
			$initial_index = count($this->items_arr);
		}
		$this->initial_index = $initial_index > 0 ? $initial_index : 0;
	}	

	public function js_init() { ?>
		<script>
			(function( $ ) {
			
				var img_height = '<?php echo $this->img_height; ?>';
				if ( img_height != '' ) {
					$( 'head' ).append( '<style>.ddImage img {height:' + img_height + 'px !important;}</style>' );
				}			
			
				var ddData = [<?php
					echo '{ text:\'' . $this->title . '\', value:\'\' }';             
					foreach ( $this->items_arr as $item ) {
						if ( trim( $item ) != '' ) {
							$arr = explode( ';', $item );
							if ( ! isset( $arr[3] ) ) {
								$arr[3] = '';
							}
							echo ',{ text: \'' . $arr[0] . '\', value: \'' . floatval( $arr[1] ) . '\', description: \'' . sanitize_text_field( $arr[2] ) . '\', image: \'' . $arr[3] . '\' }';							
						}
					}
				?>];
				
				var oDropdown = $( '#<?php echo $this->dd_id; ?>' ).msDropDown({
					byJson:{ data:ddData },
					on:{change:function( data, ui ) {
						var val = data.value;
						ui.data( 'value', val );
						//alert( $( ui ).closest( '.btQuoteSelect' ).data( 'condition' ) );
						bt_cc_eval_conditions( val, $( ui ).closest( '.btQuoteSelect' ).data( 'condition' ) );
						bt_quote_total( $( ui ).closest( '.btQuoteBooking' ) );
						bt_paypal_items( $( ui ).closest( '.btQuoteBooking' ) );
					}}
				}).data('dd');
				
				// bt_bb_init_dropdown( oDropdown, <?php echo $this->initial_index; ?> );
				$( document ).ready(function() {
					if ( oDropdown ) {
						bt_cc_init_dropdown( oDropdown, '#<?php echo $this->dd_id; ?>', <?php echo $this->initial_index; ?> );
					}
				});
				
				
	        })( jQuery );
			
		</script>
	<?php }
}

// [bt_cc_item]
class bt_cc_item {
	static function init() {
		add_shortcode( 'bt_cc_item', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'name'					=> '',
			'type'					=> 'text',
			'value'					=> '',           
			'initial_value'			=> '',
			'images'				=> '',
			'img_height'			=> '',
			'condition'				=> '',
			'item_el_id'			=> '',
			'item_el_class'         => '',
			'item_el_style'         => ''
		), $atts, 'bt_cc_item' ) );
		
		$name = sanitize_text_field( $name );
		$type = sanitize_text_field( $type );
		$initial_value = sanitize_text_field( $initial_value );
		$images = sanitize_text_field( $images );
		$img_height = sanitize_text_field( $img_height );
		// $condition = sanitize_text_field( $condition );
		$item_el_id = sanitize_text_field( $item_el_id );
		$item_el_class = sanitize_text_field( $item_el_class );
		$item_el_style = sanitize_text_field( $item_el_style ); 

		$value = str_replace( "'", "\'", $value );		
   
		$item_id_attr = '';
		if ( $item_el_id == '' ) {
			$item_el_id = uniqid( 'btQuoteItem' );
		} else {
			$item_el_id ='btQuoteItem'  . $item_el_id;
		}
		$item_id_attr = 'id="' . $item_el_id . '"';

		$item_class = array();
		if ( $item_el_class != '' ) {
			$item_class[] = $item_el_class;
		}
		
		$item_style_attr = '';
		if ( $item_el_style != '' ) {
			$item_style_attr = 'style="' . $item_el_style . '"';
		}

		$images = explode( ',', $images );
		
		if ( $condition != '' ) {
			$condition = preg_replace( '/$\R?^/m', "#bt_cc_nl#", $condition );	
			/*$condition = str_replace( '%3E', "&gt;", $condition );
			$condition = str_replace( '%3C', "&lt;", $condition );			
			$condition = sanitize_text_field( $condition );*/
			$condition = str_replace( '#gt#', "&gt;", $condition );
			$condition = str_replace( '#lt#', "&lt;", $condition );
			$condition = strip_tags( $condition );/**/
		}

		if ( $type == 'text' ) {
			$price = round( floatval( $value ), 2 );
			$input = '<input type="text" class="btQuoteText btQuoteElement" data-condition="' . $condition . '" data-price="' . $price . '" value="' . $initial_value . '" data-initial-value="' . $initial_value  . '"/>';
			
		} else if ( $type == 'select' ) {
			
			$items_arr = preg_split( '/$\R?^/m', $value );
			
			$i = 0;
			foreach ( $items_arr as $item ) {
				if ( isset( $images[ $i ] ) ) {
					$items_arr[ $i ] = sanitize_text_field( $items_arr[ $i ] . ';' . wp_get_attachment_thumb_url( $images[ $i ] ) );
				}  
				$i++;
			}

			$dd_id = uniqid() . "W" . rand(100,999);
			
			//$input = '<div id="' . $dd_id . '" class="btQuoteSelect btContactField btDropDown" data-value="'.$initial_value.'"></div>';
			$input = '<div id="' . $dd_id . '" class="btQuoteSelect btContactField btDropDown btQuoteElement" data-condition="' . $condition . '" data-initial-value="' . $initial_value  . '"></div>';
			
			$proxy = new CostDD_Proxy( $dd_id, $items_arr, __( 'Select...', 'bt_plugin' ), $img_height, $initial_value );
			add_action( 'wp_footer', array( $proxy, 'js_init' ), 20 );			
			
		} else if ( $type == 'slider' ) {    
		
			$arr = explode( ';', $value );
			$price = round( floatval( $arr[3] ), 2 );
			$offset = isset( $arr[4] ) ? round( floatval( $arr[4] ), 2 ) : 0;

			if ( $initial_value > $arr[1] ){
				$initial_value =  $arr[1];
			}

			if ( $initial_value < $arr[0] ){
				$initial_value =  $arr[0];
			}
				
			$input = '<div class="btQuoteSlider btQuoteElement" data-value="' . $initial_value . '"  data-initial-value="' . $initial_value  . '" data-min="' . $arr[0] . '" data-max="' . $arr[1] . '" data-step="' . $arr[2] . '" data-price="' . $price . '" data-offset="' . $offset . '" data-condition="' . $condition . '"></div><span class="btQuoteSliderValue"></span>';
			
		} else if ( $type == 'switch' ) {
		
			$arr = explode( ';', $value );
			if ( ! is_array( $arr ) || count( $arr ) < 2 ) {
				$arr = array( 0, 1 );
			}

			$class_on = '';
			if ( $initial_value ==  $arr[1] ){
				$class_on = ' on';
			}

			$input = '<div class="btQuoteSwitch btQuoteElement' . $class_on . '" data-off="' . $arr[0] . '" data-on="' . $arr[1] . '" data-condition="' . $condition . '" data-initial-value="' . $initial_value  . '"><div class="btQuoteSwitchInner"></div></div>';
			
		}
		
		$output = '<div class="btQuoteItem ' . implode( ' ', $item_class ) . '" ' . $item_id_attr . ' ' . $item_style_attr . '><label>' . $name . '</label>
		<div class="btQuoteItemInput">' . $input . '</div></div>';

		return $output;
	}
}

// [bt_cc_multiply]
class bt_cc_multiply {
	static function init() {
		add_shortcode( 'bt_cc_multiply', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(

		), $atts, 'bt_cc_multiply' ) );
		
		$output = '<div class="btQuoteMBlock">' . wptexturize( do_shortcode( $content ) ) . '</div>';

		return $output;
	}
}

// [bt_cc_group]
class bt_cc_group {
	static function init() {
		add_shortcode( 'bt_cc_group', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'eval'         			=> '',
			'paypal_label' 			=> '',
			'item_el_id'			=> '',
			'item_el_class'         => '',
			'item_el_style'         => ''
		), $atts, 'bt_cc_group' ) );
		
		$item_el_id = sanitize_text_field( $item_el_id );
		$item_el_class = sanitize_text_field( $item_el_class );
		$item_el_style = sanitize_text_field( $item_el_style ); 
		
		$item_id_attr = '';
		if ( $item_el_id == '' ) {
			$item_el_id = uniqid( 'btQuoteItem' );
		} else {
			$item_el_id ='btQuoteItem'  . $item_el_id;
		}
		$item_id_attr = 'id="' . $item_el_id . '"';

		$item_class = array();
		if ( $item_el_class != '' ) {
			$item_class[] = $item_el_class;
		}
		
		$item_style_attr = '';
		if ( $item_el_style != '' ) {
			$item_style_attr = 'style="' . $item_el_style . '"';
		}
		
		/*$eval = sanitize_text_field( $eval );*/
		$eval = preg_replace( '/$\R?^/m', "", $eval );
		$eval = str_replace( '#lt#', "&lt;", $eval );
		$eval = str_replace( '#gt#', "&gt;", $eval );
		$eval = strip_tags($eval);
		
		$output = '<div class="btQuoteGBlock ' . implode( ' ', $item_class ) . '" data-eval="' . $eval . '" data-paypal_label="' . $paypal_label . '" ' . $item_id_attr . ' ' . $item_style_attr . '>' . wptexturize( do_shortcode( $content ) ) . '</div>';

		return $output;
	}
}

// [bt_cc_text]
class bt_cc_text {
	static function init() {
		add_shortcode( 'bt_cc_text', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'name'		=> '',
			'value'		=> '',
			'initial_value'	=> '',
			'condition'	=> '',
			'item_el_id'	=> '',
			'item_el_class' => '',
			'item_el_style' => ''
		), $atts, 'bt_cc_text' ) );
 		
		$name = sanitize_text_field( $name );
		$type = sanitize_text_field( 'text' );
		$value = sanitize_text_field( $value );
		$initial_value = sanitize_text_field( $initial_value );
		// $condition = sanitize_text_field( $condition );
		$item_el_id = sanitize_text_field( $item_el_id );
		$item_el_class = sanitize_text_field( $item_el_class );
		$item_el_style = sanitize_text_field( $item_el_style );
		
		if ( $condition != '' ) $condition = preg_replace( '/$\R?^/m', "#bt_cc_nl#", $condition );
                
		$output = wptexturize( do_shortcode( '[bt_cc_item '
				. 'name="' . $name . '" '
				. 'type="' . $type . '" '
				. 'value="' . $value . '" '
				. 'initial_value="' . $initial_value . '" '
				. 'condition="' . $condition . '" '
				. 'item_el_id="' . $item_el_id . '" '
				. 'item_el_class="' . $item_el_class . '" '
				. 'item_el_style="' . $item_el_style . '" '
				. ']' ) );
		
		return $output;
	}
}

// [bt_cc_select]
class bt_cc_select {
	static function init() {
		add_shortcode( 'bt_cc_select', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'name'		=> '',
			'value'		=> '',
			'initial_value'	=> '',
			'images'	=> '',
			'img_height'	=> '',
			'condition'	=> '',
			'item_el_id'	=> '',
			'item_el_class' => '',
			'item_el_style' => ''
		), $atts, 'bt_cc_select' ) );
		
		$name = sanitize_text_field( $name );
		$type = sanitize_text_field( 'select' );
		$initial_value = sanitize_text_field( $initial_value );
		$images = sanitize_text_field( $images );
		$img_height = sanitize_text_field( $img_height );
		// $condition = sanitize_text_field( $condition );
		$item_el_id = sanitize_text_field( $item_el_id );
		$item_el_class = sanitize_text_field( $item_el_class );
		$item_el_style = sanitize_text_field( $item_el_style );
		
		if ( $condition != '' ) $condition = preg_replace( '/$\R?^/m', "#bt_cc_nl#", $condition );
                
		 $output = wptexturize( do_shortcode( '[bt_cc_item '
				. 'name="' . $name . '" '
				. 'type="' . $type . '" '
				. 'value="' . $value . '" '
				. 'initial_value="' . $initial_value . '" '
				. 'images="' . $images . '" '
				. 'img_height="' . $img_height . '" '
				. 'condition="' . $condition . '" '
				. 'item_el_id="' . $item_el_id . '" '
				. 'item_el_class="' . $item_el_class . '" '
				. 'item_el_style="' . $item_el_style . '" '
				. ']' ) );
                
		return $output;
	}
}


// [bt_cc_slider]
class bt_cc_slider {
	static function init() {
		add_shortcode( 'bt_cc_slider', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'name'			=> '',
			'value_min'		=> '',
			'value_max'		=> '',
			'value_step'	=> '',
			'value_unit'	=> '',
			'value_offset'	=> '',
			'initial_value'	=> '',
			'condition'	=> '',
			'item_el_id'	=> '',
			'item_el_class' => '',
			'item_el_style' => ''
		), $atts, 'bt_cc_slider' ) );
		
		$name = sanitize_text_field( $name );
		$type = sanitize_text_field( 'slider' );                
		$value_min = sanitize_text_field( $value_min );
		$value_max = sanitize_text_field( $value_max );
		$value_step = sanitize_text_field( $value_step );
		$value_unit = sanitize_text_field( $value_unit );
		$value_offset = sanitize_text_field( $value_offset );                
		$initial_value = sanitize_text_field( $initial_value );
		// $condition = sanitize_text_field( $condition );
		
		$item_el_id = sanitize_text_field( $item_el_id );
		$item_el_class = sanitize_text_field( $item_el_class );
		$item_el_style = sanitize_text_field( $item_el_style );
                
		$value = $value_min . ";" . $value_max . ";" . $value_step . ";" . $value_unit . ";" . $value_offset ;
		
		if ( $condition != '' ) $condition = preg_replace( '/$\R?^/m', "#bt_cc_nl#", $condition );
		
		$output = wptexturize( do_shortcode( '[bt_cc_item '
				. 'name="' . $name . '" '
				. 'type="' . $type . '" '
				. 'value="' . $value . '" '
				. 'initial_value="' . $initial_value . '" '
				. 'condition="' . $condition . '" '
				. 'item_el_id="' . $item_el_id . '" '
				. 'item_el_class="' . $item_el_class . '" '
				. 'item_el_style="' . $item_el_style . '" '
				. ']' ) );

		return $output;
	}
}


// [bt_cc_switch]
class bt_cc_switch {
	static function init() {
		add_shortcode( 'bt_cc_switch', array( __CLASS__, 'handle_shortcode' ) );
	}

	static function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'name'			=> '',
			'value_off'		=> '',
			'value_on'		=> '',
			'initial_value'	=> '',
			'condition'		=> '',
			'item_el_id'	=> '',
			'item_el_class' => '',
			'item_el_style' => ''
		), $atts, 'bt_cc_switch' ) );
		
		$name = sanitize_text_field( $name );
		$type = sanitize_text_field( 'switch' );
		$value_off = sanitize_text_field( $value_off );
		$value_on = sanitize_text_field( $value_on );
		$initial_value = sanitize_text_field( $initial_value );
		// $condition = sanitize_text_field( $condition );
		$item_el_id = sanitize_text_field( $item_el_id );
		$item_el_class = sanitize_text_field( $item_el_class );
		$item_el_style = sanitize_text_field( $item_el_style );
                
		$value = $value_off . ";" . $value_on;
		
		if ( $condition != '' ) $condition = preg_replace( '/$\R?^/m', "#bt_cc_nl#", $condition );
		
		$output = wptexturize( do_shortcode( '[bt_cc_item '
				. 'name="' . $name . '" '
				. 'type="' . $type . '" '
				. 'value="' . $value . '" '
				. 'initial_value="' . $initial_value . '" '
				. 'condition="' . $condition . '" '
				. 'item_el_id="' . $item_el_id . '" '
				. 'item_el_class="' . $item_el_class . '" '
				. 'item_el_style="' . $item_el_style . '" '
				. ']' ) );

		return $output;
	}
}


bt_cost_calculator::init();
bt_cc_item::init();
bt_cc_multiply::init();
bt_cc_group::init();
bt_cc_text::init();
bt_cc_select::init();
bt_cc_slider::init();
bt_cc_switch::init();

/*
 * * * * * * * * * *
 * RC / VC MAPPING *
 * * * * * * * * * *
 */

function bt_quote_map_sc() {

	$time_array = array();
	$time_array[ '' ] = '';
	for ( $i = 0; $i <= 23; $i++ ) {
		if ( $i < 10 ) $i = '0' . $i;
		$time_array[ $i . ':00' ] =  $i . ':00';
	}
	
	$bt_quote_params = array(

		array( 'param_name' => 'accent_color', 'type' => 'colorpicker', 'heading' => __( 'Accent Color', 'bt_plugin' ) ),
		array( 'param_name' => 'total_text', 'type' => 'textfield', 'heading' => __( 'Total Title', 'bt_plugin' ), 'value' => __( 'Total', 'bt_plugin' ) ),		
		array( 'param_name' => 'currency', 'type' => 'textfield', 'heading' => __( 'Currency', 'bt_plugin' ) ),
		array( 'param_name' => 'currency_after', 'type' => 'checkbox', 'value' => array( 'Yes' => 'yes' ), 'heading' => __( 'Currency After Total', 'bt_plugin' ) ),	

		array( 'param_name' => 'show_next', 'type' => 'checkbox', 'value' => array( 'Yes' => 'yes' ), 'heading' => __( 'Display Next Button and Contact Form', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'cf7_id', 'type' => 'textfield', 'heading' => __( 'Contact form 7 Id', 'bt_plugin' ), 'preview' => true, 'group' => __( 'Contact-Form', 'bt_plugin' ), 'description' => __( 'Leave blank to use default contact form (settings below)', 'bt_plugin' ) ),
		array( 'param_name' => 'admin_email', 'type' => 'textfield', 'heading' => __( 'Admin Email', 'bt_plugin' ), 'preview' => true, 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'subject', 'type' => 'textfield', 'heading' => __( 'Email Subject', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'email_client', 'type' => 'checkbox', 'value' => array( 'Yes' => 'yes' ), 'heading' => __( 'Send Email to Client', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'email_confirmation', 'type' => 'checkbox', 'value' => array( 'Show confirmation checkbox for sending email to client' => 'yes' ), 'heading' => __( 'Email Confirmation', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),		
		array( 'param_name' => 'url_confirmation', 'type' => 'textfield', 'heading' => __( 'Optional redirection URL for confirmation', 'bt_plugin' ), 'description' => __( 'User will be redirected to this URL after submit', 'bt_plugin' ), 'preview' => true, 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'm_name', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Name', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'm_email', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Email', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'm_phone', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Phone', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'm_address', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Address', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'm_message', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Message', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'show_booking', 'type' => 'checkbox', 'value' => array( 'Yes' => 'yes' ), 'heading' => __( 'Show Date/Time Inputs', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'm_date', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Preferred Date', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'm_time', 'type' => 'checkbox', 'value' => array( 'Yes' => 'Mandatory' ), 'heading' => __( 'Mandatory Preferred Time', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'time_start', 'type' => 'dropdown', 'heading' => __( 'Time Input Start', 'bt_plugin' ), 'value' => $time_array, 'group' => __( 'Contact-Form', 'bt_plugin' )),
		array( 'param_name' => 'time_end', 'type' => 'dropdown', 'heading' => __( 'Time Input End', 'bt_plugin' ), 'value' => $time_array, 'group' => __( 'Contact-Form', 'bt_plugin' )),	
		array( 'param_name' => 'time_text', 'type' => 'textfield', 'heading' => __( 'Time Input Title', 'bt_plugin' ), 'value' => __( 'Preferred Service Time', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),
		array( 'param_name' => 'date_text', 'type' => 'textfield', 'heading' => __( 'Date Input Title', 'bt_plugin' ), 'value' => __( 'Preferred Service Date', 'bt_plugin' ), 'group' => __( 'Contact-Form', 'bt_plugin' ) ),	
	
		array( 'param_name' => 'rec_site_key', 'type' => 'textfield', 'heading' => __( 'reCAPTCHA Site key', 'bt_plugin' ), 'group' => __( 'reCAPTCHA', 'bt_plugin' ) ),
		array( 'param_name' => 'rec_secret_key', 'type' => 'textfield', 'heading' => __( 'reCAPTCHA Secret key', 'bt_plugin' ), 'group' => __( 'reCAPTCHA', 'bt_plugin' ) ),
		
		array( 'param_name' => 'paypal_email', 'type' => 'textfield', 'heading' => __( 'Your PayPal account email address', 'bt_plugin' ), 'group' => __( 'PayPal', 'bt_plugin' ) ),
		array( 'param_name' => 'paypal_cart_name', 'type' => 'textfield', 'heading' => __( 'Shopping cart name', 'bt_plugin' ), 'group' => __( 'PayPal', 'bt_plugin' ) ),
		array( 'param_name' => 'paypal_currency', 'type' => 'textfield', 'heading' => __( 'Currency code (USD, EUR, GBP, CAD, JPY)', 'bt_plugin' ), 'group' => __( 'PayPal', 'bt_plugin' ) ),
		
		array( 'param_name' => 'el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ) ),
		array( 'param_name' => 'el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ) )
	);
	
	$bt_cc_item_params = array(	
		array( 'param_name' => 'name', 'type' => 'textfield', 'heading' => __( 'Name', 'bt_plugin' ), 'holder' => 'div' ),
		array( 'param_name' => 'type', 'type' => 'dropdown', 'heading' => __( 'Input Type', 'bt_plugin' ), 'holder' => 'div',
			'value' => array(
				__( 'Text', 'bt_plugin' ) => 'text',
				__( 'Select', 'bt_plugin' ) => 'select',
				__( 'Slider', 'bt_plugin' ) => 'slider',
				__( 'Switch', 'bt_plugin' ) => 'switch'
		) ),
		array( 'param_name' => 'value', 'type' => 'textarea', 'heading' => __( 'Value', 'bt_plugin' ), 'description' => __( 'unit_value for Text / name;value;description separated by new line for Select / min;max;step;unit_value;offset_value for Slider / value_off;value_on for Switch', 'bt_plugin' ) ),
		array( 'param_name' => 'initial_value', 'type' => 'textfield', 'heading' => __( 'Initial value or select list index', 'bt_plugin' ), 'description' => __( 'value for Text / index for Select ( index 0 for Select... item in list ) / value between min and max values for Slider / off or on value for Switch', 'bt_plugin' ) , 'preview' => true),
		array( 'param_name' => 'images', 'type' => 'attach_images', 'heading' => __( 'Images for Select input type', 'bt_plugin' ) ),
		array( 'param_name' => 'img_height', 'type' => 'textfield', 'heading' => __( 'Images Height in px', 'bt_plugin' ) ),
		array( 'param_name' => 'condition', 'type' => 'textarea', 'heading' => __( 'Change Event Condition', 'bt_plugin' ), 'description' => __( 'clause operator (e.g. ==0 or >0); target element id; action (e.g. fadeTo(\'slow\',0.2)); lock target (lock/unlock); separated by new line', 'bt_plugin' ) ),
		array( 'param_name' => 'item_el_id', 'type' => 'textfield', 'heading' => __( 'Custom Id Attribute', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true ),
		array( 'param_name' => 'item_el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true ),
		array( 'param_name' => 'item_el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true )
	);	
	
	$bt_cc_multiply_params = array();
	$bt_cc_group_params = array(
		array( 'param_name' => 'eval', 'type' => 'textarea', 'heading' => __( 'JS pseudo code', 'bt_plugin' ), 'description' => __( '$1, $2, etc. can be used to reference values of items inside this group; always use return to return the value', 'bt_plugin' ) ),
		array( 'param_name' => 'paypal_label', 'type' => 'textfield', 'heading' => __( 'PayPal Label', 'bt_plugin' ), 'description' => __( 'If label is not entered, this group will not be included in PayPal payment', 'bt_plugin' ) ),
		array( 'param_name' => 'item_el_id', 'type' => 'textfield', 'heading' => __( 'Custom Id Attribute', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true ),
		array( 'param_name' => 'item_el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true ),
		array( 'param_name' => 'item_el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true )
	);
        
    $bt_cc_text_params = array(
		array( 'param_name' => 'name', 'type' => 'textfield', 'heading' => __( 'Name', 'bt_plugin' ), 'holder' => 'div' ),
        array( 'param_name' => 'value', 'type' => 'textfield', 'heading' => __( 'Unit', 'bt_plugin' ), 'description' => __( 'unit value', 'bt_plugin' ) ),
		array( 'param_name' => 'initial_value', 'type' => 'textfield', 'heading' => __( 'Initial value', 'bt_plugin' ), 'description' => __( 'initial value', 'bt_plugin' ) , 'preview' => true),
		array( 'param_name' => 'condition', 'type' => 'textarea', 'heading' => __( 'Change Event Condition', 'bt_plugin' ), 'description' => __( 'clause operator (e.g. ==0 or >0); target element id; action (e.g. fadeTo(\'slow\',0.2)); lock target (lock/unlock); separated by new line', 'bt_plugin' ) ),
		array( 'param_name' => 'item_el_id', 'type' => 'textfield', 'heading' => __( 'Custom Id Attribute', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true ),
		array( 'param_name' => 'item_el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true ),
		array( 'param_name' => 'item_el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true )
	
	);
        
    $bt_cc_select_params = array(	
		array( 'param_name' => 'name', 'type' => 'textfield', 'heading' => __( 'Name', 'bt_plugin' ), 'holder' => 'div' ),
		array( 'param_name' => 'value', 'type' => 'textarea', 'heading' => __( 'Value', 'bt_plugin' ), 'description' => __( 'name;value;description separated by new line', 'bt_plugin' ) ),
		array( 'param_name' => 'initial_value', 'type' => 'textfield', 'heading' => __( 'Initial select list index', 'bt_plugin' ), 'description' => __( 'initial selected index ( index 0 for Select... item in list )', 'bt_plugin' ) , 'preview' => true),
		array( 'param_name' => 'images', 'type' => 'attach_images', 'heading' => __( 'Images for Select list', 'bt_plugin' ) ),
		array( 'param_name' => 'img_height', 'type' => 'textfield', 'heading' => __( 'Images Height in px', 'bt_plugin' ) ),
		array( 'param_name' => 'condition', 'type' => 'textarea', 'heading' => __( 'Change Event Condition', 'bt_plugin' ), 'description' => __( 'clause operator (e.g. ==0 or >0); target element id; action (e.g. fadeTo(\'slow\',0.2)); lock target (lock/unlock); separated by new line', 'bt_plugin' ) ),
		array( 'param_name' => 'item_el_id', 'type' => 'textfield', 'heading' => __( 'Custom Id Attribute', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true ),
		array( 'param_name' => 'item_el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true ),
		array( 'param_name' => 'item_el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true )
	);	
        
    $bt_cc_slider_params = array(	
		array( 'param_name' => 'name', 'type' => 'textfield', 'heading' => __( 'Name', 'bt_plugin' ), 'holder' => 'div' ),
		array( 'param_name' => 'value_min', 'type' => 'textfield', 'heading' => __( 'Min Value', 'bt_plugin' ), 'description' => __( 'min value', 'bt_plugin' ) ),
		array( 'param_name' => 'value_max', 'type' => 'textfield', 'heading' => __( 'Max Value', 'bt_plugin' ), 'description' => __( 'max value', 'bt_plugin' ) ),
		array( 'param_name' => 'value_step', 'type' => 'textfield', 'heading' => __( 'Step', 'bt_plugin' ), 'description' => __( 'step value', 'bt_plugin' ) ),
		array( 'param_name' => 'value_unit', 'type' => 'textfield', 'heading' => __( 'Unit', 'bt_plugin' ), 'description' => __( 'unit value', 'bt_plugin' ) ),
		array( 'param_name' => 'value_offset', 'type' => 'textfield', 'heading' => __( 'Offset', 'bt_plugin' ), 'description' => __( 'offset value', 'bt_plugin' ) ),
		array( 'param_name' => 'initial_value', 'type' => 'textfield', 'heading' => __( 'Initial value', 'bt_plugin' ), 'description' => __( 'value between min and max values', 'bt_plugin' ) , 'preview' => true),
		array( 'param_name' => 'condition', 'type' => 'textarea', 'heading' => __( 'Change Event Condition', 'bt_plugin' ), 'description' => __( 'clause operator (e.g. ==0 or >0); target element id; action (e.g. fadeTo(\'slow\',0.2)); lock target (lock/unlock); separated by new line', 'bt_plugin' ) ),
		array( 'param_name' => 'item_el_id', 'type' => 'textfield', 'heading' => __( 'Custom Id Attribute', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true ),
		array( 'param_name' => 'item_el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true ),
		array( 'param_name' => 'item_el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true )
	);
        
    $bt_cc_switch_params = array(	
		array( 'param_name' => 'name', 'type' => 'textfield', 'heading' => __( 'Name', 'bt_plugin' ), 'holder' => 'div' ),
		array( 'param_name' => 'value_off', 'type' => 'textfield', 'heading' => __( 'Value Off', 'bt_plugin' ), 'description' => __( 'value_off', 'bt_plugin' ) ),
		array( 'param_name' => 'value_on', 'type' => 'textfield', 'heading' => __( 'Value On', 'bt_plugin' ), 'description' => __( 'value_on', 'bt_plugin' ) ),
		array( 'param_name' => 'initial_value', 'type' => 'textfield', 'heading' => __( 'Initial value', 'bt_plugin' ), 'description' => __( 'value  off or value on', 'bt_plugin' ) , 'preview' => true),
		array( 'param_name' => 'condition', 'type' => 'textarea', 'heading' => __( 'Change Event Condition', 'bt_plugin' ), 'description' => __( 'clause operator (e.g. ==0 or >0); target element id; action (e.g. fadeTo(\'slow\',0.2)); lock target (lock/unlock); separated by new line', 'bt_plugin' ) ),
		array( 'param_name' => 'item_el_id', 'type' => 'textfield', 'heading' => __( 'Custom Id Attribute', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true ),
		array( 'param_name' => 'item_el_class', 'type' => 'textfield', 'heading' => __( 'Extra Class Name(s)', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true ),
		array( 'param_name' => 'item_el_style', 'type' => 'textfield', 'heading' => __( 'Inline Style', 'bt_plugin' ), 'group' => __( 'Design', 'bt_plugin' ), 'preview' => true )
	);	

	if ( function_exists( 'bt_rc_map' ) ) {

		bt_rc_map( 'bt_cost_calculator', array( 'name' => __( 'Cost Calculator', 'bt_plugin' ), 'description' => __( 'Cost calculator container', 'bt_plugin' ), 'container' => 'vertical', 'icon' => 'bt_bb_icon_bt_bb_cost_calculator', 'accept' => array( 'bt_cc_item' => true, 'bt_cc_multiply' => true, 'bt_cc_group' => true, 'bt_hr' => true, 'bt_header' => true, 'bt_text' => true, 'bt_bb_separator' => true, 'bt_bb_headline' => true, 'bt_bb_text' => true , 'bt_cc_text' => true, 'bt_cc_select' => true, 'bt_cc_slider' => true, 'bt_cc_switch' => true), 'toggle' => true,
			'params' => $bt_quote_params
		));
		
		bt_rc_map( 'bt_cc_item', array( 'name' => __( 'Cost Calculator Item (Deprecated)', 'bt_plugin' ), 'description' => __( 'Single cost calculator element (all in one)', 'bt_plugin' ), 'icon' => 'bt_bb_icon_bt_bb_cost_calculator_item',
			'params' => $bt_cc_item_params
		));
		
		bt_rc_map( 'bt_cc_multiply', array( 'name' => __( 'Cost Calculator Multiply', 'bt_plugin' ), 'description' => __( 'Cost calculator multiply container', 'bt_plugin' ), 'icon' => 'bt_bb_icon_bt_bb_cost_calculator_item', 'container' => 'vertical', 'accept' => array( 'bt_cc_item' => true, 'bt_cc_text' => true, 'bt_cc_select' => true, 'bt_cc_slider' => true, 'bt_cc_switch' => true ), 'show_settings_on_create' => false,
			'params' => $bt_cc_multiply_params
		));
		
		bt_rc_map( 'bt_cc_group', array( 'name' => __( 'Cost Calculator Group', 'bt_plugin' ), 'description' => __( 'Cost calculator group container', 'bt_plugin' ), 'container' => 'vertical', 'icon' => 'bt_bb_icon_bt_bb_cost_calculator_item', 'accept' => array( 'bt_cost_calculator' => false, 'bt_cc_multiply' => false, 'bt_cc_group' => false, 'bt_row' => false, 'bt_row_inner' => false, 'bt_column' => false, 'bt_column_inner' => false ), 'accept_all' => true, 'show_settings_on_create' => false,
			'params' => $bt_cc_group_params
		));
                
		bt_rc_map( 'bt_cc_text', array( 'name' => __( 'Cost Calculator Text', 'bt_plugin' ), 'description' => __( 'Cost calculator text input', 'bt_plugin' ), 'icon' => 'bt_bb_icon_bt_bb_cost_calculator_item', 'container' => 'vertical',  'show_settings_on_create' => false,
				'params' => $bt_cc_text_params
		));

		bt_rc_map( 'bt_cc_select', array( 'name' => __( 'Cost Calculator Select', 'bt_plugin' ), 'description' => __( 'Cost calculator select list', 'bt_plugin' ), 'icon' => 'bt_bb_icon_bt_bb_cost_calculator_item', 'container' => 'vertical',  'show_settings_on_create' => false,
				'params' => $bt_cc_select_params
		));

		bt_rc_map( 'bt_cc_slider', array( 'name' => __( 'Cost Calculator Slider', 'bt_plugin' ), 'description' => __( 'Cost calculator slider', 'bt_plugin' ), 'icon' => 'bt_bb_icon_bt_bb_cost_calculator_item', 'container' => 'vertical',  'show_settings_on_create' => false,
				'params' => $bt_cc_slider_params
		));

		bt_rc_map( 'bt_cc_switch', array( 'name' => __( 'Cost Calculator Switch', 'bt_plugin' ), 'description' => __( 'Cost calculator switch checkbox', 'bt_plugin' ), 'icon' => 'bt_bb_icon_bt_bb_cost_calculator_item', 'container' => 'vertical',  'show_settings_on_create' => false,
				'params' => $bt_cc_switch_params
		));

	}
	
	if ( function_exists( 'vc_map' ) ) {
		
		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
			class WPBakeryShortCode_bt_cost_calculator extends WPBakeryShortCodesContainer {
			}
		}
		
		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
			class WPBakeryShortCode_bt_cc_multiply extends WPBakeryShortCodesContainer {
			}
		}
		
		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
			class WPBakeryShortCode_bt_cc_group extends WPBakeryShortCodesContainer {
			}
		}
	
		$data = array();
		$data['name']              = __( 'Cost Calculator', 'bt_plugin' );
		$data['base']              = 'bt_cost_calculator';
		$data['as_parent']         = array( 'except' => 'vc_row,vc_column,vc_row_inner,vc_column_inner,bt_cost_calculator' );
		$data['content_element']   = true;
		$data['js_view']           = 'VcColumnView';
		$data['category']          = 'Content';
		$data['icon']              = 'bt_quote_icon';
		$data['admin_enqueue_css'] = array( plugins_url() . '/bt_cost_calculator/vc_style.css' );
		$data['admin_enqueue_js']  = array( plugins_url() . '/bt_cost_calculator/vc_js.js' );
		$data['description']       = __( 'Cost calculator container', 'bt_plugin' );

		$data['params'] = $bt_quote_params;

		vc_map( $data );
		
		$data = array();
		$data['name']              = __( 'Cost Calculator Multiply', 'bt_plugin' );
		$data['base']              = 'bt_cc_multiply';
		$data['as_parent']         = array( 'except' => 'vc_row,vc_column' );
		$data['as_child']          = array( 'only' => 'bt_cost_calculator' );
		$data['content_element']   = true;
		$data['js_view']           = 'VcColumnView';
		$data['category']          = 'Content';
		$data['icon']              = 'bt_quote_icon_multiply';
		$data['admin_enqueue_css'] = array( plugins_url() . '/bt_cost_calculator/vc_style.css' );
		$data['description']       = __( 'Cost calculator multiply container', 'bt_plugin' );

		$data['params'] = $bt_cc_multiply_params;
		
		vc_map( $data );		
		
		$data = array();
		$data['name']              = __( 'Cost Calculator Group', 'bt_plugin' );
		$data['base']              = 'bt_cc_group';
		$data['as_parent']         = array( 'except' => 'vc_row,vc_column' );
		$data['as_child']          = array( 'only' => 'bt_cost_calculator' );
		$data['content_element']   = true;
		$data['js_view']           = 'VcColumnView';
		$data['category']          = 'Content';
		$data['icon']              = 'bt_quote_icon_group';
		$data['admin_enqueue_css'] = array( plugins_url() . '/bt_cost_calculator/vc_style.css' );
		$data['description']       = __( 'Cost calculator group container', 'bt_plugin' );

		$data['params'] = $bt_cc_group_params;
		
		vc_map( $data );
				
		$data = array();
		$data['name']              = __( 'Cost Calculator Item (Deprecated)', 'bt_plugin' );
		$data['base']              = 'bt_cc_item';
		$data['content_element']   = true;
		$data['js_view']           = 'BTCCItem';
		$data['category']          = 'Content';
		$data['as_child']          = array( 'only' => 'bt_cost_calculator,bt_cc_multiply,bt_cc_group' );
		$data['icon']              = 'bt_quote_icon_item';
		$data['admin_enqueue_css'] = array( plugins_url() . '/bt_cost_calculator/vc_style.css' );
		$data['description']       = __( 'Cost calculator item', 'bt_plugin' );

		$data['params'] = $bt_cc_item_params;
		
		vc_map( $data );

		$data = array();
		$data['name']              = __( 'Cost Calculator Text', 'bt_plugin' );
		$data['base']              = 'bt_cc_text';
		$data['content_element']   = true;
		$data['js_view']           = 'BTCCItem';
		$data['category']          = 'Content';
		$data['as_child']          = array( 'only' => 'bt_cost_calculator,bt_cc_multiply,bt_cc_group' );
		$data['icon']              = 'bt_quote_icon_item';
		$data['admin_enqueue_css'] = array( plugins_url() . '/bt_cost_calculator/vc_style.css' );
		$data['description']       = __( 'Cost calculator text control', 'bt_plugin' );

		$data['params'] = $bt_cc_text_params;
		
		vc_map( $data );

		$data = array();
		$data['name']              = __( 'Cost Calculator Select', 'bt_plugin' );
		$data['base']              = 'bt_cc_select';
		$data['content_element']   = true;
		$data['js_view']           = 'BTCCItem';
		$data['category']          = 'Content';
		$data['as_child']          = array( 'only' => 'bt_cost_calculator,bt_cc_multiply,bt_cc_group' );
		$data['icon']              = 'bt_quote_icon_item';
		$data['admin_enqueue_css'] = array( plugins_url() . '/bt_cost_calculator/vc_style.css' );
		$data['description']       = __( 'Cost calculator select control', 'bt_plugin' );

		$data['params'] = $bt_cc_select_params;
		
		vc_map( $data );

		$data = array();
		$data['name']              = __( 'Cost Calculator Slider', 'bt_plugin' );
		$data['base']              = 'bt_cc_slider';
		$data['content_element']   = true;
		$data['js_view']           = 'BTCCItem';
		$data['category']          = 'Content';
		$data['as_child']          = array( 'only' => 'bt_cost_calculator,bt_cc_multiply,bt_cc_group' );
		$data['icon']              = 'bt_quote_icon_item';
		$data['admin_enqueue_css'] = array( plugins_url() . '/bt_cost_calculator/vc_style.css' );
		$data['description']       = __( 'Cost calculator slider control', 'bt_plugin' );

		$data['params'] = $bt_cc_slider_params;
		
		vc_map( $data );

		$data = array();
		$data['name']              = __( 'Cost Calculator Switch', 'bt_plugin' );
		$data['base']              = 'bt_cc_switch';
		$data['content_element']   = true;
		$data['js_view']           = 'BTCCItem';
		$data['category']          = 'Content';
		$data['as_child']          = array( 'only' => 'bt_cost_calculator,bt_cc_multiply,bt_cc_group' );
		$data['icon']              = 'bt_quote_icon_item';
		$data['admin_enqueue_css'] = array( plugins_url() . '/bt_cost_calculator/vc_style.css' );
		$data['description']       = __( 'Cost calculator switch control', 'bt_plugin' );

		$data['params'] = $bt_cc_switch_params;
		
		vc_map( $data );
		
	}

}
add_action( 'plugins_loaded', 'bt_quote_map_sc' );