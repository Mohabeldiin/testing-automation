<?php

if ( ! class_exists( 'Thim_Login_Popup_Widget' ) ) {
	class Thim_Login_Popup_Widget extends Thim_Widget {

		public $ins = array();

		function __construct() {
			parent::__construct(
				'login-popup',
				esc_html__( 'Thim: Login Popup', 'eduma' ),
				array(
					'panels_groups' => array( 'thim_builder_so_widgets' ),
					'panels_icon'   => 'thim-widget-icon thim-widget-icon-login-popup'
				),
				array(),
				array(
					'text_register' => array(
						'type'    => 'text',
						'label'   => esc_html__( 'Register Label', 'eduma' ),
						'default' => 'Register',
					),
					'text_login'    => array(
						'type'    => 'text',
						'label'   => esc_html__( 'Login Label', 'eduma' ),
						'default' => 'Login',
					),
					'text_logout'   => array(
						'type'    => 'text',
						'label'   => esc_html__( 'Logout Label', 'eduma' ),
						'default' => 'Logout',
					),
					'text_profile'  => array(
						'type'    => 'text',
						'label'   => esc_html__( 'Profile Label', 'eduma' ),
						'default' => 'Profile',
					),
					'layout'        => array(
						'type'    => 'select',
						'label'   => esc_html__( 'Layout', 'eduma' ),
						'default' => 'base',
						'options' => array(
							'base' => esc_html__( 'Default', 'eduma' ),
							'icon' => esc_html__( 'Icon', 'eduma' ),
						)
					),

					'captcha'   => array(
						'type'        => 'checkbox',
						'label'       => esc_html__( 'Use captcha?', 'eduma' ),
						'description' => esc_html__( 'Use captcha in register and login form.', 'eduma' ) . esc_html__( '(not show when Enable register form of LearnPress.)', 'eduma' ),
						'default'     => false,
					),
					'term'      => array(
						'type'        => 'text',
						'label'       => esc_html__( 'Terms of Service link', 'eduma' ),
						'description' => esc_html__( 'Leave empty to disable this field.', 'eduma' ) . esc_html__( '(not show when Enable register form of LearnPress.)', 'eduma' ),
						'default'     => '',
					),
					'shortcode' => array(
						'type'        => 'text',
						'label'       => esc_html__( 'Shortcode', 'eduma' ),
						'description' => esc_html__( 'Enter shortcode to show in form Login.', 'eduma' ),
						'default'     => '',
					)

				)
			);
		}

		/**
		 * Initialize the CTA widget
		 */
		function get_template_name( $instance ) {
			$this->ins = $instance;
			add_action( 'wp_footer', array( $this, 'thim_display_login_popup_form' ), 5 );

			return 'base';
		}

		function get_style_name( $instance ) {
			return false;
		}

		function thim_display_login_popup_form() {
			$instance = $this->ins;

			if ( ! is_user_logged_in() ) {
				$registration_enabled       = get_option( 'users_can_register' );
//				$lp_register_form = false;
//				if ( class_exists( 'LearnPress' ) && thim_is_new_learnpress( '4.0' ) ) {
//					$lp_register_form = get_theme_mod( 'thim_form_lp_register',false );
//					$lp_enable_register_profile = get_option( 'learn_press_enable_register_profile' );
//				}
				?>
				<div id="thim-popup-login">
					<div
						class="popup-login-wrapper<?php echo ( ! empty( $instance['shortcode'] ) ) ? ' has-shortcode' : ''; ?>">
						<div class="thim-login-container">
							<?php
							if ( ! empty( $instance['shortcode'] ) ) {
								echo do_shortcode( $instance['shortcode'] );
							}
							$current_page_id = get_queried_object_id();
							?>

							<div class="thim-popup-inner">
								<div class="thim-login">
									<h4 class="title"><?php esc_html_e( 'Login with your site account', 'eduma' ); ?></h4>
									<form name="loginpopopform"
										  action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>"
										  method="post">

										<?php do_action( 'thim_before_login_form' ); ?>

										<p class="login-username">
											<input type="text" name="log"
												   placeholder="<?php esc_html_e( 'Username or email', 'eduma' ); ?>"
												   class="input required" value="" size="20"/>
										</p>
										<p class="login-password">
											<input type="password" name="pwd"
												   placeholder="<?php esc_html_e( 'Password', 'eduma' ); ?>"
												   class="input required" value="" size="20"/>
										</p>

										<?php
										/**
										 * Fires following the 'Password' field in the login form.
										 *
										 * @since 2.1.0
										 */
										do_action( 'login_form' );
										?>

										<?php if ( ! empty( $instance['captcha'] ) ): ?>
											<p class="thim-login-captcha">
												<?php
												$value_1 = rand( 1, 9 );
												$value_2 = rand( 1, 9 );
												?>
												<input type="text"
													   data-captcha1="<?php echo esc_attr( $value_1 ); ?>"
													   data-captcha2="<?php echo esc_attr( $value_2 ); ?>"
													   placeholder="<?php echo esc_attr( $value_1 . ' &#43; ' . $value_2 . ' &#61;' ); ?>"
													   class="captcha-result required"
													   name="thim-eduma-recaptcha-rs"/>
												<input name="thim-eduma-recaptcha[]" type="hidden"
													   value="<?php echo $value_1 ?>"/>
												<input name="thim-eduma-recaptcha[]" type="hidden"
													   value="<?php echo $value_2 ?>"/>
											</p>
										<?php endif; ?>

										<?php echo '<a class="lost-pass-link" href="' . thim_get_lost_password_url() . '" title="' . esc_attr__( 'Lost Password', 'eduma' ) . '">' . esc_html__( 'Lost your password?', 'eduma' ) . '</a>'; ?>
										<p class="forgetmenot login-remember">
											<label for="popupRememberme"><input name="rememberme" type="checkbox"
																				value="forever"
																				id="popupRememberme"/> <?php esc_html_e( 'Remember Me', 'eduma' ); ?>
											</label></p>
										<p class="submit login-submit">
											<input type="submit" name="wp-submit"
												   class="button button-primary button-large"
												   value="<?php esc_attr_e( 'Login', 'eduma' ); ?>"/>
											<input type="hidden" name="redirect_to"
												   value="<?php echo esc_url( thim_eduma_get_current_url() ); ?>"/>
											<input type="hidden" name="testcookie" value="1"/>
											<input type="hidden" name="nonce"
												   value="<?php echo wp_create_nonce( 'thim-loginpopopform' ) ?>"/>
											<input type="hidden" name="eduma_login_user">
										</p>

										<?php do_action( 'thim_after_login_form' ); ?>

									</form>
									<?php
									if ( $registration_enabled ) {
										echo '<p class="link-bottom">' . esc_html__( 'Not a member yet? ', 'eduma' ) . ' <a class="register" href="' . esc_url( thim_get_register_url() ) . '">' . esc_html__( 'Register now', 'eduma' ) . '</a></p>';
									}
									?>
									<?php do_action( 'thim-message-after-link-bottom' ); ?>
								</div>

								<?php if ( $registration_enabled ): ?>
									<div class="thim-register">
 											<h4 class="title"><?php echo esc_html_x( 'Register a new account', 'Login popup form', 'eduma' ); ?></h4>
											<form class="<?php if ( get_theme_mod( 'thim_auto_login', true ) ) {
												echo 'auto_login';
											} ?>" name="registerformpopup" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>"
												  method="post" novalidate="novalidate">

												<?php wp_nonce_field( 'ajax_register_nonce', 'register_security' ); ?>

												<p>
													<input placeholder="<?php esc_attr_e( 'Username', 'eduma' ); ?>"
														   type="text" name="user_login" class="input required"/>
												</p>

												<p>
													<input placeholder="<?php esc_attr_e( 'Email', 'eduma' ); ?>"
														   type="email" name="user_email" class="input required"/>
												</p>

												<?php if ( get_theme_mod( 'thim_auto_login', true ) ) { ?>
													<p>
														<input placeholder="<?php esc_attr_e( 'Password', 'eduma' ); ?>"
															   type="password" name="password" class="input required"/>
													</p>
													<p>
														<input
															placeholder="<?php esc_attr_e( 'Repeat Password', 'eduma' ); ?>"
															type="password" name="repeat_password"
															class="input required"/>
													</p>
												<?php } ?>
 
												<?php
												if ( is_multisite() && function_exists( 'gglcptch_login_display' ) ) {
													gglcptch_login_display();
												}

												do_action( 'register_form' );
												?>

												<?php if ( ! empty( $instance['captcha'] ) ) : ?>
													<p class="thim-login-captcha">
														<?php
														$value_1 = rand( 1, 9 );
														$value_2 = rand( 1, 9 );
														?>
														<input type="text"
															   data-captcha1="<?php echo esc_attr( $value_1 ); ?>"
															   data-captcha2="<?php echo esc_attr( $value_2 ); ?>"
															   placeholder="<?php echo esc_attr( $value_1 . ' &#43; ' . $value_2 . ' &#61;' ); ?>"
															   class="captcha-result required"/>
													</p>
												<?php endif; ?>

												<?php if ( ! empty( $instance['term'] ) ): ?>
													<p>
														<input type="checkbox" class="required" name="term"
															   id="termFormFieldPopup">
														<label
															for="termFormFieldPopup"><?php printf( __( 'I accept the <a href="%s" target="_blank">Terms of Service</a>', 'eduma' ), esc_url( $instance['term'] ) ); ?></label>
													</p>
												<?php endif; ?>

												<?php //do_action( 'signup_hidden_fields', 'create-another-site' ); ?>

												<p class="submit">
													<input type="submit" name="wp-submit"
														   class="button button-primary button-large"
														   value="<?php echo esc_attr_x( 'Sign up', 'Login popup form', 'eduma' ); ?>"/>
												</p>
												<input type="hidden" name="redirect_to"
													   value="<?php echo esc_url( thim_eduma_get_current_url() ); ?>"/>
												<!--<input type="hidden" name="modify_user_notification" value="1">-->
												<input type="hidden" name="eduma_register_user">
											</form>
 										<?php echo '<p class="link-bottom">' . esc_html_x( 'Are you a member? ', 'Login popup form', 'eduma' ) . ' <a class="login" href="' . esc_url( thim_get_login_page_url() ) . '">' . esc_html_x( 'Login now', 'Login popup form', 'eduma' ) . '</a></p>'; ?>
										<?php do_action( 'thim-message-after-link-bottom' ); ?>
										<div class="popup-message"></div>
									</div>
								<?php endif; ?>
							</div>

							<span class="close-popup"><i class="fa fa-times" aria-hidden="true"></i></span>
								<div class="cssload-container"> <div class="cssload-loading"><i></i><i></i><i></i><i></i></div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}
}

function thim_login_popup_widget() {
	register_widget( 'Thim_Login_Popup_Widget' );

}

add_action( 'widgets_init', 'thim_login_popup_widget' );
