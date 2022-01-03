<?php

/*
 * My_Home_Redux
 *
 * Setup theme option with help of ReduxFramework (https://reduxframework.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

if ( ! class_exists( 'My_Home_Redux' ) ) :

	class My_Home_Redux {

		// options name
		private $opt_name = 'myhome_redux';
		// option prefix
		private $prefix = 'mh-';

		public function __construct() {
			if ( ! class_exists( 'Redux' ) ) {
				return;
			}

			add_action( 'redux/loaded', array( $this, 'redux_disable_ads' ) );
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'redux/options/myhome_redux/saved', array( $this, 'wpml_strings' ) );
			add_action( 'redux/options/myhome_redux/reset', array( $this, 'wpml_strings' ) );
		}

		/*
		 * init
		 *
		 * Initiate options
		 */
		public function init() {
			$design_migration = get_option( 'myhome_design_migration' );
			// check if design migrated
			if ( empty( $design_migration ) ) {
				$this->migrate_design();
				update_option( 'myhome_design_migration', 1, true );
			}

			/*
			 * Set Arguments
			 */
			$this->set_args();
			/*
			 * Sections
			 */
			$this->set_general_options();
			$this->set_header_options();
			$this->set_estate_options();
			$this->set_listing_options();
			$this->set_agent_options();
			//			$this->set_design_options(); // old design
			$this->set_design_options_menu(); // new design
			$this->set_map_options();
			$this->set_breadcrumbs_options();
			$this->set_blog();
			$this->set_footer_options();
			$this->set_typography_options();
			$this->set_more_options();
			Redux::init( $this->opt_name );
		}

		/*
		 * get
		 *
		 * Get specific option
		 */
		public function get( $first_param, $second_param = null ) {
			if ( ! class_exists( 'Redux' ) ) {
				return '';
			}


			global $myhome_redux;

			$first_param = $this->prefix . $first_param;

			if ( is_null( $second_param ) ) {
				if ( isset( $myhome_redux[ $first_param ] ) ) {
					return $myhome_redux[ $first_param ];
				} else {
					return '';
				}
			} else {
				if ( isset( $myhome_redux[ $first_param ] ) && isset( $myhome_redux[ $first_param ][ $second_param ] ) ) {
					return $myhome_redux[ $first_param ][ $second_param ];
				} else {
					return '';
				}
			}
		}

		/*
		 * Set redux arguments
		 */
		public function set_args() {
			$theme = wp_get_theme();

			$args = array(
				'opt_name'            => $this->opt_name,
				'display_name'        => $theme->get( 'Name' ),
				'display_version'     => $theme->get( 'Version' ),
				'menu_type'           => 'menu',
				'allow_sub_menu'      => true,
				'menu_title'          => esc_html__( 'MyHome Theme', 'myhome' ),
				'page_title'          => 'MyHome Options',
				'google_api_key'      => '',
				'async_typography'    => false,
				'admin_bar'           => false,
				'admin_bar_icon'      => 'dashicons-portfolio',
				'admin_bar_priority'  => 50,
				'global_variable'     => '',
				'dev_mode'            => false,
				'show_options_object' => false,
				'update_notice'       => false,
				'customizer'          => false,
				'page_priority'       => 2,
				'page_parent'         => 'themes.php',
				'page_permissions'    => 'manage_options',
				'last_tab'            => '',
				'page_icon'           => 'icon-themes',
				'page_slug'           => '',
				'use_cdn'             => true,
				'default_show'        => true,
				'default_mark'        => '',
				'show_import_export'  => true,
				'output'              => true,
				'output_tag'          => true,
				'ajax_save'           => false,
				'color'               => true,
				'font_display'        => 'block',
			);

			Redux::setArgs( $this->opt_name, $args );
		}

		/*
		 * set_general_options
		 *
		 * General theme options
		 */
		public function set_general_options() {
			$section = array(
				'title'  => esc_html__( 'General', 'myhome' ),
				'id'     => 'myhome-general-opts',
				'icon'   => 'el el-cog',
				'fields' => array(
					// Primary color
					array(
						'id'       => 'mh-color-primary',
						'type'     => 'color_rgba',
						'title'    => esc_html__( 'Primary color', 'myhome' ),
						'subtitle' => esc_html__( 'Set primary color for all elements', 'myhome' ),
						'output'   => array(
							'background-color' => '
                              html body.myhome-body .mh-menu-primary-color-background .mh-header:not(.mh-header--transparent) #mega_main_menu.mh-primary > .menu_holder > .menu_inner > span.nav_logo,
                              html body.myhome-body .mh-menu-primary-color-background .mh-header:not(.mh-header--transparent) #mega_main_menu.mh-primary > .menu_holder > .mmm_fullwidth_container,
                              .myhome-body .mh-thumbnail__featured,
                              .myhome-body .calendar_wrap table tbody td a:hover,
                              .myhome-body .dropdown-menu > li.selected a,
                              .myhome-body .mdl-button.mdl-button--raised.mdl-button--primary,
                              .myhome-body .mdl-button.mdl-button--primary-ghost:hover,
                              .myhome-body .mdl-button.mdl-button--primary-ghost:active,
                              .myhome-body .mdl-button.mdl-button--primary-ghost:focus,
                              .myhome-body .mdl-button.mdl-button--compare-active,
                              .myhome-body .mdl-button.mdl-button--compare-active:hover,
                              .myhome-body .mdl-button.mdl-button--compare-active:active,
                              .myhome-body .mdl-button.mdl-button--compare-active:focus,
                              .myhome-body .mh-accordion .ui-accordion-header.ui-accordion-header-active,
                              .myhome-body .mh-caption__inner,
                              .myhome-body .mh-compare__price,
                              .myhome-body .mh-estate__slider__price,
                              .myhome-body .mh-estate__details__price,
                              .myhome-body .mh-heading--top-separator:after,
                              .myhome-body .mh-heading--bottom-separator:after,
                              .myhome-body .mh-loader,
                              .myhome-body .wpcf7-form .wpcf7-form-control.wpcf7-submit,
                              .myhome-body .mh-loader:before,
                              .myhome-body .mh-loader:after,
                              .myhome-body .mh-map-panel__element button:hover,
                              .myhome-body .mh-map-panel .mh-map-panel__element button.mh-button--active,
                              .myhome-body .mh-map-panel .mh-map-panel__element button.mh-button--active:hover,
                              .myhome-body .mh-map-panel .mh-map-panel__element button.mh-button--active:active,
                              .myhome-body .mh-map-panel .mh-map-panel__element button.mh-button--active:focus,
                              .myhome-body .mh-map-zoom__element button:hover,
                              .myhome-body .mh-map-infobox,
                              .myhome-body .mh-post-single__nav__prev:before,
                              .myhome-body .mh-post-single__nav__next:before,
                              .myhome-body .mh-slider__card-short__price,
                              .myhome-body .mh-slider__card-default__price,
                              .myhome-body #estate_slider_card .tparrows:hover:before,
                              .myhome-body #estate_slider_card_short .tparrows:hover:before,
                              .myhome-body #mh_rev_slider_single .tparrows:hover:before,
                              .myhome-body #mh_rev_gallery_single .tparrows:hover:before,
                              .myhome-body .mh-social-icon:hover,
                              .myhome-body .mh-top-header--primary,
                              .myhome-body .mh-top-header-big:not(.mh-top-header-big--primary) .mh-top-header-big__panel,
                              .myhome-body .mh-top-header-big.mh-top-header-big--primary,
                              .myhome-body .mh-browse-estate__row:first-child,
                              .myhome-body .mh-widget-title__text:before,
                              .myhome-body .owl-carousel .owl-dots .owl-dot.active span,
                              .myhome-body .tagcloud a:hover,
                              .myhome-body .tagcloud a:active,
                              .myhome-body .tagcloud a:focus,
                              .myhome-body .mh-menu ul li a:before,
                              .myhome-body .widget_pages ul li a:before,
                              .myhome-body .widget_meta ul li a:before,
                              .myhome-body .widget_recent_entries ul li a:before,
                              .myhome-body .widget_nav_menu ul li a:before,
                              .myhome-body .widget_categories ul li a:before,
                              .myhome-body .widget_archive ul li a:before,
                              .myhome-body .calendar_wrap table #today,
                              .myhome-body .mh-background-color-primary,
                              .myhome-body .mh-user-panel__menu ul li.mh-user-panel__menu__li--active button,
                              .myhome-body .mh-user-panel__menu ul li.mh-user-panel__menu__li--active a,
                              .myhome-body .mh-top-header--primary .mh-top-bar-user-panel__user-info,
                              .myhome-body .mh-top-header-big .mh-top-bar-user-panel__user-info,
                              .myhome-body .awesomplete mark,
                              .myhome-body .idx-omnibar-form.idx-omnibar-original-form button,
                              .myhome-body .idx-omnibar-form.idx-omnibar-original-form .awesomplete > ul > li mark,
                              .myhome-body #IDX-main #IDX-resultsRow .IDX-resultsDetailsLink a:hover,
                              .myhome-body #IDX-main #IDX-formSubmit,
                              .myhome-body #IDX-main #IDX-submitBtn,
                              .myhome-body #IDX-main #IDX-scheduleShowing,
                              .myhome-body #IDX-main #IDX-photoGalleryLink,
                              .myhome-body #IDX-main .IDX-detailsVirtualTourLink,   
                              .myhome-body #IDX-main #IDX-detailsVirtualTour,
                              .myhome-body .IDX-qsInput.IDX-qsButtonInput, 
                              .myhome-body #IDX-main.IDX-category-map #IDX-criteriaText, 
                              .myhome-body .mh-fixed-menu--active .mh-menu-primary-color-background .mega_main_menu,
                              .myhome-body.mh-active-input-primary .mh-search__panel > div:not(:first-child) .is-checked .mdl-radio__inner-circle,
                              .myhome-body #myhome-idx-wrapper #IDX-leadToolsBar,
                              .myhome-body #myhome-idx-wrapper #IDX-submitBtn,
                              .myhome-body #myhome-idx-wrapper #IDX-formSubmit,
                              .myhome-body #myhome-idx-wrapper #IDX-submitBtn:hover,
                              .myhome-body #myhome-idx-wrapper #IDX-formSubmit:hover,
                              .myhome-body #myhome-idx-wrapper__details-detailsDynamic-1008 .IDX-detailsVirtualTourLink,
                              .myhome-body #myhome-idx-wrapper .IDX-page-listing .IDX-detailsVirtualTourLink,
                              .myhome-body #myhome-idx-wrapper__details-detailsDynamic-1008 .IDX-detailsVirtualTourLink:hover,
                              .myhome-body #myhome-idx-wrapper .IDX-page-listing .IDX-detailsVirtualTourLink:hover,
                              .myhome-body #myhome-idx-wrapper__details-detailsDynamic-1008 #IDX-main.IDX-category-details #IDX-photoGalleryLink,
                              .myhome-body #myhome-idx-wrapper__details-detailsDynamic-1008 #IDX-main.IDX-category-details #IDX-scheduleShowing,
                              .myhome-body #myhome-idx-wrapper .IDX-page-listing #IDX-photoGalleryLink,
                              .myhome-body #myhome-idx-wrapper .IDX-page-listing #IDX-scheduleShowing,
                              .myhome-body #myhome-idx-wrapper__details-detailsDynamic-1008 #IDX-main.IDX-category-details #IDX-photoGalleryLink:hover,
                              .myhome-body #myhome-idx-wrapper__details-detailsDynamic-1008 #IDX-main.IDX-category-details #IDX-scheduleShowing:hover,
                              .myhome-body #myhome-idx-wrapper .IDX-page-listing #IDX-photoGalleryLink:hover,
                              .myhome-body #myhome-idx-wrapper .IDX-page-listing #IDX-scheduleShowing:hover,
                              .myhome-body .myhome-idx-wrapper__mortgage_calculator-mobileFirstMortgage-1002 .IDX-input-group-addon,
                              .myhome-body .myhome-idx-wrapper__map_search_page-mapsearch-1000 #IDX-criteriaText,
                              .myhome-body .myhome-idx-wrapper__map_search_page-mapsearch-1000 #IDX-criteriaWindow .ui-widget-content .ui-slider-range,
                              .myhome-body .myhome-idx-wrapper__map_search_page-mapsearch-1000 #IDX-criteriaWindow .ui-widget-content,
                              .myhome-body .idx-omnibar-form button,
                              .myhome-body .myhome-idx-wrapper__results-mobileFirstResults-1006 .IDX-resultsDetailsLink a:hover,
                              .myhome-body .IDX-type-roster #IDX-rosterFilterSubmit,
                              .myhome-body .IDX-type-roster #IDX-rosterFilterSubmit:hover,
                              .myhome-body .myhome-idx-wrapper__search_page-searchBase-1005 #IDX-loginSubmit,
                              .myhome-body #myhome-idx-wrapper .IDX-category-search #IDX-loginSubmit, 
                              .myhome-body .myhome-idx-wrapper__search_page-searchBase-1005 #IDX-loginSubmit:hover,
                              .myhome-body #myhome-idx-wrapper .IDX-category-search #IDX-loginSubmit:hover,
                              .myhome-body .myhome-idx-wrapper__my_account-myaccount-1000 input[type=submit],
                              .myhome-body .myhome-idx-wrapper__my_account-myaccount-1000 input[type=submit]:hover,
                              .myhome-body .myhome-idx-wrapper__user_signup-usersignup-1002 #IDX-submitBtn,
                              .myhome-body .myhome-idx-wrapper__user_signup-usersignup-1002 #IDX-submitBtn:hover,
                              .myhome-body .myhome-idx-wrapper__user_login-userlogin-1001 #IDX-loginSubmit,
                              .myhome-body .myhome-idx-wrapper__user_login-userlogin-1001 #IDX-loginSubmit:hover,
                              .myhome-body #IDX-widgetLeadLoginWrapper.IDX-widgetLeadLoginWrapper input[type=submit],
                              .myhome-body #IDX-widgetLeadLoginWrapper.IDX-widgetLeadLoginWrapper input[type=submit]:hover,
                              .myhome-body #LeadSignup.LeadSignup input[type=submit],
                              .myhome-body #LeadSignup.LeadSignup input[type=submit]:hover,
                              .myhome-body .IDX-quicksearchWrapper .IDX-quicksearchForm .IDX-qsInput.IDX-qsButtonInput,
                              .myhome-body #myhome-idx-wrapper.myhome-idx-wrapper__mortgage_calculator-mobileFirstMortgage-1002 .IDX-input-group-addon,
                              .myhome-body #myhome-idx-wrapper.myhome-idx-wrapper__mortgage_calculator-mobileFirstMortgage-1002 .IDX-btn-primary,
                              .myhome-body #myhome-idx-wrapper.myhome-idx-wrapper__mortgage_calculator-mobileFirstMortgage-1002 .IDX-btn-primary:hover,
                               html body.myhome-body .ui-dialog[aria-labelledby*=IDX-loadingScreen] #IDX-loadingScreen,
                               html body.myhome-body .ui-dialog[aria-labelledby*=IDX-loadingScreen] #IDX-loadingScreen:before,
                               html body.myhome-body .ui-dialog[aria-labelledby*=IDX-loadingScreen] #IDX-loadingScreen:after,
                               .IDX-registrationModal #IDX-registration .IDX-btn-primary,
                               .IDX-registrationModal #IDX-registration .IDX-btn-primary:hover,
                               .myhome-body .myhome-idx-wrapper__photo_gallery-mobileFirstPhotoGallery-1003 #IDX-photoGallery .IDX-arrow:hover,
                               .myhome-body div[id*=IDX-carouselGallery-] + a:hover,
                               .myhome-idx-wrapper__results-mobileFirstResults-1006 #IDX-resultsRefineSubmit,
                               .myhome-idx-wrapper__results-mobileFirstResults-1006 #IDX-resultsRefineSubmit:hover,                               
                               .myhome-body .mh-app-wrapper .primary,
                               .myhome-body div.awesomplete mark,
                               .myhome-body .mh-popup-login .mh-popup-login__tab-button.active,
                               .myhome-body .mh-app__sidebar-nav__avatar-placeholder:hover,
                               .myhome-body .mh-pricing-table__row--name,
                               .myhome-body .woocommerce #respond input#submit.alt,
                               .myhome-body .woocommerce a.button.alt,
                               .myhome-body .woocommerce button.button.alt,
                               .myhome-body .woocommerce input.button.alt,
                               .myhome-body .mh-field-plans__list__image__icon       
                            ',
							'border-color'     => '
                              .myhome-body blockquote,
                              .myhome-body html body .mh-menu-primary-color-background #mega_main_menu.mh-primary > .menu_holder > .mmm_fullwidth_container,
                              .myhome-body input[type=text]:focus,
                              .myhome-body input[type=text]:active,
                              .myhome-body input[type=password]:focus,
                              .myhome-body input[type=password]:active,
                              .myhome-body input[type=email]:focus,
                              .myhome-body input[type=email]:active,
                              .myhome-body input[type=search]:focus,
                              .myhome-body input[type=search]:active,
                              .myhome-body input[type=tel]:focus,
                              .myhome-body input[type=tel]:active,
                              .myhome-body textarea:focus,
                              .myhome-body textarea:active,
                              .myhome-body .sticky,
                              .myhome-body .mh-active-input input,
                              .myhome-body .mh-active-input .bootstrap-select.btn-group > .btn,
                              .myhome-body .mdl-button.mdl-button--primary-ghost,
                              .myhome-body .mh-compare,
                              .myhome-body .tagcloud a:hover, 
                              .myhome-body .tagcloud a:active,
                              .myhome-body .tagcloud a:focus,
                              .myhome-body .mh-map-panel,
                              .myhome-body .mh-map-zoom,
                              .myhome-body .mh-map-infobox:after,
                              .myhome-body .mh-map-infobox .mh-map-infobox__img-wrapper,
                              .myhome-body .mh-search-horizontal,
                              .myhome-body .mh-search-map-top .mh-search-horizontal,
                              .myhome-body .mh-social-icon:hover:after,
                              .myhome-body .mh-top-header--primary,
                              .myhome-body .owl-carousel .owl-dots .owl-dot.active span,
                              .myhome-body .mh-border-color-primary,
                              .myhome-body .mh-post .post-content blockquote,
                              .myhome-body .mh-user-panel-info,                       
                              .myhome-body.mh-active-input-primary .mh-search__panel > div:not(:first-child) .is-checked .mdl-radio__outer-circle,
                              html body.myhome-body .mh-menu-primary-color-background .mh-header:not(.mh-header--transparent) #mega_main_menu.mh-primary > .menu_holder > .mmm_fullwidth_container,
                              .myhome-body .myhome-idx-wrapper__photo_gallery-photogallery-1002 .IDX-photoGallery,
                              .myhome-body .myhome-idx-wrapper__map_search_page-mapsearch-1000 #IDX-searchNavWrapper,
                              .myhome-body .myhome-idx-wrapper__results-mobileFirstResults-1006 .IDX-propertyTypeHeader,
                              .myhome-body .myhome-idx-wrapper__results-mobileFirstResults-1006 .IDX-resultsDetailsLink a,
                              .myhome-body .myhome-idx-wrapper__search_page-searchBase-1005 #IDX-searchNavWrapper,
                              .myhome-body #myhome-idx-wrapper .IDX-category-search #IDX-searchNavWrapper,
                              .myhome-body .myhome-idx-wrapper__search_page-searchStandard-1002 #IDX-searchNavWrapper,
                              .myhome-body #myhome-idx-wrapper.myhome-idx-wrapper__mortgage_calculator-mobileFirstMortgage-1002 .IDX-well,
                              .myhome-body div[id*=IDX-carouselGallery-] + a,
                              .myhome-body .mh-app-wrapper .primary,
                              .myhome-body .tabs
                              ',
							'color'            => '
                              .myhome-body .mh-navbar__menu ul:first-child > li:hover > a,
                              .myhome-body .mh-navbar__container .mh-navbar__menu ul:first-child > li:hover > a:first-child,
                              .myhome-body .mh-pagination a:hover,
                              .myhome-body .page-numbers.current,
                              .myhome-body .mh-footer-top--dark a:hover,
                              .myhome-body .mh-footer-top--dark a:active,
                              .myhome-body .mh-footer-top--dark a:focus,                              
                              .myhome-body.input-myhome .mh-active-input input,
                              .myhome-body .tt-highlight,
                              .myhome-body .mh-breadcrumbs__item a:hover, 
                              .myhome-body .mh-breadcrumbs__back:hover,
                              .myhome-body .mh-breadcrumbs__back:hover i,
                              .myhome-body .mh-active-input .bootstrap-select.btn-group > .btn,
                              .myhome-body .mh-active-input .bootstrap-select.btn-group .dropdown-toggle .filter-option,
                              .myhome-body .mdl-button.mdl-button--primary-ghost,
                              .myhome-body .mdl-button.mdl-button--primary-ghost:hover,
                              .myhome-body .mdl-button.mdl-button--primary-ghost:active,
                              .myhome-body .mdl-button.mdl-button--primary-ghost:focus,
                              .myhome-body .mdl-button.mdl-button--primary-font,
                              html body #mega_main_menu.mh-primary #mh-submit-button a,
                              html body.myhome-body #mega_main_menu.mh-primary #mh-submit-button a i,
                              html body.myhome-body #mega_main_menu.mh-primary > .menu_holder > .menu_inner > ul > li:hover > a:after,
                              html body.myhome-body  #mega_main_menu.mh-primary > .menu_holder > .menu_inner > ul > li:hover > .item_link *,
                              .myhome-body .comment-edit-link:hover,
                              .myhome-body .comment-reply-link:hover,
                              .myhome-body .mh-compare__feature-list li a:hover,
                              .myhome-body .mh-compare__list__element a:hover,
                              .myhome-body .mh-compare__list__element a:hover i,
                              .myhome-body .mh-estate__list__element a:hover,
                              .myhome-body .mh-estate__list__element a:hover i,
                              .myhome-body .mh-estate-horizontal__primary,
                              .myhome-body .mh-estate-vertical__primary,
                              .myhome-body .mh-filters__button.mh-filters__button--active,
                              .myhome-body .mh-filters__button.mh-filters__button--active:hover,
                              .myhome-body button.mh-filters__right__button--active,
                              .myhome-body .mh-loader-wrapper-map,
                              .myhome-body .mh-loader,
                              .myhome-body .mh-register-terms .mh-register-terms__text a,
                              .myhome-body .mh-register-field__terms .mh-register-field__terms__text a,
                              .myhome-body .mh-form-container__reset:hover,
                              .myhome-body .mh-map-wrapper__noresults,
                              .myhome-body .mh-map-pin i,
                              .myhome-body .mh-navbar__wrapper #mh-submit-button a:hover,
                              .myhome-body .mh-pagination--single-post,
                              .myhome-body .mh-post-single__meta a:hover,
                              .myhome-body .mh-search__heading-big,
                              .myhome-body .mh-button-transparent:hover,
                              .myhome-body .mh-user-panel__plans__row .mh-user-panel__plans__cell-4 button:hover,
                              .myhome-body .mh-browse-estate__cell-3 a:hover,
                              .myhome-body .mh-browse-estate__cell-payment a:hover,
                              .myhome-body .mh-user-pagination li:hover,
                              .myhome-body .mh-user-pagination li.mh-user-pagination__element-active,
                              .myhome-body .mh-top-header-big__element:not(.mh-top-header-big__panel) a:hover,
                              .myhome-body .mh-color-primary,
                              .myhome-body .mh-top-header:not(.mh-top-header--primary) a:hover,
                              .myhome-body .mh-top-header-big .mh-top-header-big__social-icons a:hover,                              
                              .myhome-body .mh-top-header-big .mh-top-header-big__social-icons button:hover,
                              .myhome-body .mh-estate__details > div a:hover,
                              .myhome-body .recentcomments a:hover,
                              .myhome-body .rsswidget:hover,
                              .myhome-body .mh-post .post-content a:hover,
                              .myhome-body .link-primary:hover,                              
                              .myhome-body .mh-estate__agent__content a:hover,     
                              .myhome-body .mh-pagination--properties li.active a,  
                              .myhome-body .mh-page-type-v2__content a,
                              .myhome-body .idx-omnibar-form.idx-omnibar-original-form .awesomplete > ul > li:hover,
                              .myhome-body .idx-omnibar-form.idx-omnibar-original-form .awesomplete > ul > li[aria-selected="true"],
                              .myhome-body #IDX-main #IDX-resultsRow .IDX-field-listingPrice.IDX-field-price.IDX-field .IDX-text,
                              .myhome-body #IDX-main #IDX-resultsRow .IDX-resultsDetailsLink a,
                              .myhome-body #IDX-main.IDX-category-details #IDX-detailsTopNav .IDX-topLink a:hover,
                              .myhome-body #IDX-main.IDX-category-details .IDX-listAsRow li span,
                              .myhome-body #IDX-main.IDX-category-details .IDX-listAsRow li a:hover,
                              .myhome-body #IDX-main.IDX-category-search .IDX-listAsRow li span,
                              .myhome-body #IDX-main.IDX-category-map .IDX-listAsRow li span,
                              .myhome-body #IDX-main.IDX-category-search .IDX-listAsRow li a:hover,
                              .myhome-body #IDX-main.IDX-category-map .IDX-listAsRow li a:hover,
                              .myhome-body #IDX-main.IDX-category-search .IDX-listAsRow li span,
                              .myhome-body #IDX-main.IDX-category-map .IDX-listAsRow li span,
                              .myhome-body #IDX-main.IDX-category-search .IDX-listAsRow li a:hover,
                              .myhome-body #IDX-main.IDX-category-map .IDX-listAsRow li a:hover,
                              .myhome-body #IDX-main.IDX-category-details #IDX-detailsField-listingPrice #IDX-detailsPrice,
                              .myhome-body .mh-rs-search #myhome-search-form-submit .mh-search__panel--keyword .mh-search__panel.mh-active-input:after,                        
                              .myhome-body.mh-active-input-primary .mh-search__panel > div:not(:first-child) .is-checked .mdl-radio__label,
                              .myhome-body #myhome-idx-wrapper__details-detailsDynamic-1008 #IDX-nextLastButtons #IDX-nextProp,
                              .myhome-body #myhome-idx-wrapper .IDX-page-listing #IDX-nextLastButtons #IDX-nextProp,
                              .myhome-body #myhome-idx-wrapper__details-detailsDynamic-1008 #IDX-hotLinks a:hover,
                              .myhome-body #myhome-idx-wrapper .IDX-page-listing #IDX-hotLinks a:hover,
                              .myhome-body #myhome-idx-wrapper__details-detailsDynamic-1008 #IDX-main.IDX-category-details #IDX-detailsField-listingPrice #IDX-detailsPrice,
                              .myhome-body #myhome-idx-wrapper .IDX-page-listing #IDX-detailsField-listingPrice #IDX-detailsPrice,
                              .myhome-body #myhome-idx-wrapper__details-detailsDynamic-1008 #IDX-main.IDX-category-details #IDX-detailsTopNav .IDX-topLink a:hover,
                              .myhome-body #myhome-idx-wrapper .IDX-page-listing #IDX-detailsTopNav .IDX-topLink a:hover,
                              .myhome-body #myhome-idx-wrapper__details-detailsDynamic-1008 #IDX-main.IDX-category-details .IDX-listAsRow li span,
                              .myhome-body #myhome-idx-wrapper .IDX-page-listing .IDX-listAsRow li span,
                              .myhome-body #myhome-idx-wrapper__details-detailsDynamic-1008 #IDX-main.IDX-category-details .IDX-listAsRow li a:hover,
                              .myhome-body #myhome-idx-wrapper .IDX-page-listing .IDX-listAsRow li a:hover,
                              .myhome-body .myhome-idx-wrapper__photo_gallery-photogallery-1002 .IDX-page-photogallery #IDX-previousPage a:hover,
                              .myhome-body .idx-omnibar-form .awesomplete > ul > li:hover,
                              .myhome-body .idx-omnibar-form .awesomplete > ul > li[aria-selected="true"],
                              .myhome-body .myhome-idx-wrapper__results-mobileFirstResults-1006 .IDX-propertyTypeHeader,
                              .myhome-body .myhome-idx-wrapper__results-mobileFirstResults-1006 .IDX-field-listingPrice.IDX-field-price.IDX-field .IDX-text,
                              .myhome-body .myhome-idx-wrapper__results-mobileFirstResults-1006 .IDX-resultsDetailsLink a,
                              .myhome-body .myhome-idx-wrapper__search_page-searchBase-1005 .IDX-emailUpdateSignupText,
                              .myhome-body #myhome-idx-wrapper .IDX-category-search .IDX-emailUpdateSignupText,
                              .myhome-body .myhome-idx-wrapper__my_account-myaccount-1000 .IDX-backLink:hover,
                              .myhome-body .myhome-idx-wrapper__user_signup-usersignup-1002 #IDX-loginText a,
                              .myhome-body div[id*=IDX-carouselGallery-] .IDX-carouselPrice,
                              .myhome-body .IDX-showcaseTable .IDX-showcasePrice,
                              .myhome-body .IDX-slideshowWrapper .IDX-slideshowPrice,                            
                              .myhome-body .myhome-idx-wrapper__results-mobileFirstResults-1006 #IDX-agentbio .IDX-actionLinks a,
                              .myhome-body .IDX-searchNavItem > span,
                              html body.myhome-body .ui-dialog[aria-labelledby*=IDX-loadingScreen] #IDX-loadingScreen,
                              .myhome-body .myhome-idx-wrapper__photo_gallery-mobileFirstPhotoGallery-1003 .IDX-showcaseThumbnails-button.IDX-active,
                              .myhome-body div[id*=IDX-carouselGallery-] + a,
                              .myhome-body .mh-popup-top-info i,
                              .myhome-body .mh-pricing-table__row--sold,
                              .myhome-body.mh-active-input-primary .mh-active-input input,
                              .myhome-body .mh-estate__list .mh-estate__list__inner .mh-estate__list__element--attachment a:hover                               
                            ',
						),
						'default'  => array(
							'color' => '#29aae3',
						),
					),
					// Development mode
					array(
						'id'       => 'mh-development',
						'type'     => 'switch',
						'default'  => true,
						'title'    => esc_html__( 'Development mode', 'myhome' ),
						'subtitle' => esc_html__( 'This option disable built in "MyHome Cache" so you can easier make changes to cacheable content (e.g. it will be easier to make MyHome multilingual with WPML Plugin). Turn it "off" when WordPress is "live" so MyHome will work much faster.',
							'myhome' ),
					),
					array(
						'id'      => 'mh-performance_css',
						'type'    => 'switch',
						'default' => false,
						'title'   => esc_html__( 'Load combined and minified MyHome Theme CSS files', 'myhome' )
					),
				),
			);
			Redux::setSection( $this->opt_name, $section );
		}

		public function set_design_options() {
			$section = array(
				'title' => esc_html__( 'Custom colors', 'myhome' ),
				'id'    => 'myhome-design-opts',
				'icon'  => 'el el-brush',
			);
			Redux::setSection( $this->opt_name, $section );

			$sections = array(
				array(
					'title'  => esc_html__( 'General', 'myhome' ),
					'id'     => 'myhome-design-general',
					'colors' => array(
						array(
							'id'       => 'mh-color__body-bg',
							'title'    => esc_html__( 'Page background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__button-primary-background',
							'title'    => esc_html__( 'Primary button background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__button-primary-color',
							'title'    => esc_html__( 'Primary button font color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__button-ghost-primary-color',
							'title'    => esc_html__( 'Ghost button main color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__button-ghost-primary-color-hover',
							'title'    => esc_html__( 'Ghost button font color hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__general-separator',
							'title'    => esc_html__( 'Heading separator color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__owl-carousel-dot',
							'title'    => esc_html__( 'Carousels - dot', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__owl-carousel-dot-active',
							'title'    => esc_html__( 'Carousel - dot active color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						)
					)
				),
				array(
					'title'  => esc_html__( 'Top bar', 'myhome' ),
					'id'     => 'myhome-design-top_bar',
					'colors' => array(
						array(
							'id'       => 'mh-color__mh-top-header-bg',
							'title'    => esc_html__( 'Background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-top-header-border',
							'title'    => esc_html__( 'Border bottom color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-top-header-font',
							'title'    => esc_html__( 'Text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-top-header-separator',
							'title'    => esc_html__( 'Separator color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__top-bar-panel-bg',
							'title'    => esc_html__( 'User menu - link background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__top-bar-panel-font',
							'title'    => esc_html__( 'User menu - link text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__top-bar-panel-bg-hover',
							'title'    => esc_html__( 'User menu - link hover background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__top-bar-panel-font-hover',
							'title'    => esc_html__( 'User menu - link hover text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Menu', 'myhome' ),
					'id'     => 'myhome-design-menu',
					'colors' => array(
						array(
							'id'       => 'mh-color-menu-bg-color',
							'title'    => esc_html__( 'Menu background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color-menu-border-bottom-color',
							'title'    => esc_html__( 'Menu border bottom color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color-menu-first-level-font',
							'title'    => esc_html__( 'First level menu text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color-menu-submit-property-button',
							'title'    => esc_html__( 'Submit property button text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color-menu-submenu-background',
							'title'    => esc_html__( 'Submenu link background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color-menu-submenu-color-font',
							'title'    => esc_html__( 'Submenu link text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color-menu-submenu-background-hover',
							'title'    => esc_html__( 'Submenu link hover background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color-menu-submenu-font-hover',
							'title'    => esc_html__( 'Submenu link hover text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color-menu-flyout-border',
							'title'    => esc_html__( 'Flyout menu border color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Page title', 'myhome' ),
					'id'     => 'myhome-design-page-title',
					'colors' => array(
						array(
							'id'       => 'mh-color__mh-page-title-bg',
							'title'    => esc_html__( 'Background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-page-title-heading-color',
							'title'    => esc_html__( 'Heading text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-page-title-other-color',
							'title'    => esc_html__( 'Text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						)
					)
				),
				array(
					'title'  => esc_html__( 'Breadcrumbs', 'myhome' ),
					'id'     => 'myhome-design-breadcrumbs',
					'colors' => array(
						array(
							'id'       => 'mh-color__breadcrumbs-bg',
							'title'    => esc_html__( 'Background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__breadcrumbs-link-color',
							'title'    => esc_html__( 'Links color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__breadcrumbs-separator-color',
							'title'    => esc_html__( 'Separators color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__breadcrumbs-color',
							'title'    => esc_html__( 'Property name text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__breadcrumbs-border',
							'title'    => esc_html__( 'Border top color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						)
					)
				),
				array(
					'title'  => esc_html__( 'Footer', 'myhome' ),
					'id'     => 'myhome-design-footer',
					'colors' => array(
						array(
							'id'       => 'mh-color__mh-footer-bg',
							'title'    => esc_html__( 'Background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-footer-color',
							'title'    => esc_html__( 'Contact widget text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-footer-col-heading-color',
							'title'    => esc_html__( 'Column headers text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-footer-widgets-border',
							'title'    => esc_html__( 'Widgets border color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-footer-tag-color',
							'title'    => esc_html__( 'Tags main color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-footer-social-bg',
							'title'    => esc_html__( 'Social icons - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-footer-social-border',
							'title'    => esc_html__( 'Social icons - border color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-footer-social-font',
							'title'    => esc_html__( 'Social icons - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-footer-social-bg-hover',
							'title'    => esc_html__( 'Social icons - background hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-footer-social-border-hover',
							'title'    => esc_html__( 'Social icons - border hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-footer-social-font-hover',
							'title'    => esc_html__( 'Social icons - font hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-footer-bottom-bg',
							'title'    => esc_html__( 'Copyright area - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-footer-bottom-color',
							'title'    => esc_html__( 'Copyright area - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Single property page', 'myhome' ),
					'id'     => 'myhome-design-single-property',
					'colors' => array(
						array(
							'id'       => 'mh-color__single-property-page-bg',
							'title'    => esc_html__( 'Page background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__single-property-page-section-bg',
							'title'    => esc_html__( 'Sections - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__single-property-page-heading-color',
							'title'    => esc_html__( 'Sections - heading color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__single-property-page-heading-sep',
							'title'    => esc_html__( 'Sections - heading separator', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__single-property-page-dot',
							'title'    => esc_html__( 'Sections - lists dots color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__single-property-price-bg',
							'title'    => esc_html__( 'Price background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__single-property-price-color',
							'title'    => esc_html__( 'Price text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__single-property-details-bg',
							'title'    => esc_html__( 'More info (below price) - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__single-property-details-color',
							'title'    => esc_html__( 'More info (below price) - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__single-property-sidebar-heading-color',
							'title'    => esc_html__( 'Sidebar heading - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__single-property-sidebar-heading-sep',
							'title'    => esc_html__( 'Sidebar heading - separator color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__single-property-contact-send-bg',
							'title'    => esc_html__( 'Contact Form - send button background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__single-property-contact-send-font',
							'title'    => esc_html__( 'Contact Form - send button text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						)
					)
				),
				array(
					'title'  => esc_html__( 'Property slider', 'myhome' ),
					'id'     => 'myhome-design-property-slider',
					'colors' => array(
						array(
							'id'       => 'mh-color__slider-price-bg',
							'title'    => esc_html__( 'Property slider - price background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__slider-price-font',
							'title'    => esc_html__( 'Property slider - price text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__slider-bg',
							'title'    => esc_html__( 'Property slider - card background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__slider-heading',
							'title'    => esc_html__( 'Property slider - heading text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__arrows-bg',
							'title'    => esc_html__( 'Slider arrows - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__arrows-color',
							'title'    => esc_html__( 'Slider arrows - icon color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__arrows-bg-hover',
							'title'    => esc_html__( 'Slider arrows - background - hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__arrows-color-hover',
							'title'    => esc_html__( 'Slider arrows - icon color - hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__img-popup-close',
							'title'    => esc_html__( 'Single property - image popup - close text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__img-popup-counter',
							'title'    => esc_html__( 'Single property - image popup - counter text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Property search form', 'myhome' ),
					'id'     => 'myhome-design-search-form-dynamic',
					'colors' => array(
						array(
							'id'       => 'mh-color__search-horizontal-background',
							'title'    => esc_html__( 'Search Form Top / Bottom - background color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__search-horizontal-border',
							'title'    => esc_html__( 'Search Form Top / Bottom - border top color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__search-horizontal-label',
							'title'    => esc_html__( 'Search Form Top / Bottom - labels color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__search-vertical-label',
							'title'    => esc_html__( 'Search Form Left / Right - labels color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__search-horizontal-button-advanced',
							'title'    => esc_html__( 'Horizontal - Advanced button - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__search-horizontal-button-advanced-font',
							'title'    => esc_html__( 'Horizontal - Advanced Button - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__search-horizontal-button-clear',
							'title'    => esc_html__( 'Horizontal - Clear button - color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__search-horizontal-button-clear-hover',
							'title'    => esc_html__( 'Horizontal - Clear button - text color hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__filters-bg',
							'title'    => esc_html__( 'Filters - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__filters-sort-by-label',
							'title'    => esc_html__( 'Filters - "Sort by:" text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__filters-sort',
							'title'    => esc_html__( 'Filters - not active sorting type - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__filters-sort-active',
							'title'    => esc_html__( 'Filters - active sorting type - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__filters-grid-icon',
							'title'    => esc_html__( 'Filters - grid icon', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__filters-grid-icon-active',
							'title'    => esc_html__( 'Filters - grid icon active', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__search-results',
							'title'    => esc_html__( 'Search results - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__search-filters-color',
							'title'    => esc_html__( 'Search - labels e.g. "for sale (x)"', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__search-end-border',
							'title'    => esc_html__( 'Search end - border', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__search-load-more-background',
							'title'    => esc_html__( 'Load More button - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__search-load-more-color',
							'title'    => esc_html__( 'Load More button - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Property card', 'myhome' ),
					'id'     => 'myhome-design-property-card',
					'colors' => array(
						array(
							'id'       => 'mh-color__property-card-background',
							'title'    => esc_html__( 'Background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__property-card-heading-color',
							'title'    => esc_html__( 'Heading text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__property-card-address-color',
							'title'    => esc_html__( 'Address text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__property-card-price-color',
							'title'    => esc_html__( 'Price text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__property-card-info-color',
							'title'    => esc_html__( 'Additional information text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__property-card-date-color',
							'title'    => esc_html__( 'Date text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__details-button-ghost-primary-color',
							'title'    => esc_html__( 'Details button main color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__details-button-ghost-primary-color-hover',
							'title'    => esc_html__( 'Details button text color hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__property-card-compare-color',
							'title'    => esc_html__( '"Compare" text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__property-card-compare-active-background',
							'title'    => esc_html__( '"Compare" active - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__property-card-compare-active-color',
							'title'    => esc_html__( '"Compare" active - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Compare', 'myhome' ),
					'id'     => 'myhome-design-compare-bar',
					'colors' => array(
						array(
							'id'       => 'mh-color__compare-bg',
							'title'    => esc_html__( 'Background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__compare-border',
							'title'    => esc_html__( 'Bar border top color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__compare-font-one',
							'title'    => esc_html__( '"Compare: X" and "Clear" text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__compare-font-two',
							'title'    => esc_html__( '"SHOW" / "HIDE" text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__compare-column-bg',
							'title'    => esc_html__( 'Open - column background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__compare-column-title',
							'title'    => esc_html__( 'Open - column title text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__compare-column-price-bg',
							'title'    => esc_html__( 'Open - price background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__compare-column-price-font',
							'title'    => esc_html__( 'Open - price text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__compare-column-address',
							'title'    => esc_html__( 'Open - address text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__compare-column-other-text',
							'title'    => esc_html__( 'Open - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__compare-column-separators',
							'title'    => esc_html__( 'Open - horizontal lines color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						)
					)
				),
				array(
					'title'  => esc_html__( 'Post card', 'myhome' ),
					'id'     => 'myhome-design-post-card',
					'colors' => array(
						array(
							'id'       => 'mh-color__post-card-background',
							'title'    => esc_html__( 'Background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__post-card-title',
							'title'    => esc_html__( 'Post title', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__post-card-decription',
							'title'    => esc_html__( 'Description text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__post-card-date-bg',
							'title'    => esc_html__( 'Date background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__post-card-date-font',
							'title'    => esc_html__( 'Date text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__post-button-ghost-primary-color',
							'title'    => esc_html__( 'Button main color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__post-button-ghost-primary-color-hover',
							'title'    => esc_html__( 'Button text color hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Blog post', 'myhome' ),
					'id'     => 'myhome-design-blog-post',
					'colors' => array(
						array(
							'id'       => 'mh-color__post-title',
							'title'    => esc_html__( 'Post title text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__post-meta',
							'title'    => esc_html__( 'Post meta - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__post-meta-hover',
							'title'    => esc_html__( 'Post meta - link hover text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__post-meta-separators',
							'title'    => esc_html__( 'Post meta - separators color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__post-color',
							'title'    => esc_html__( 'Post text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__tag-general-color',
							'title'    => esc_html__( 'Tags main color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__nav-border-top-color',
							'title'    => esc_html__( 'Navigation - border top color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__nav-link-hover-box',
							'title'    => esc_html__( 'Navigation - hover block color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__nav-link',
							'title'    => esc_html__( 'Navigation - prev / next text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__nav-link-span',
							'title'    => esc_html__( 'Navigation - post name text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__blog-author-card-bg',
							'title'    => esc_html__( 'Author card - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__blog-author-card-label',
							'title'    => esc_html__( 'Author card - label', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__blog-author-card-name',
							'title'    => esc_html__( 'Author card - name', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__blog-author-card-par',
							'title'    => esc_html__( 'Author card - description', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__blog-comment-author',
							'title'    => esc_html__( 'Comment - author', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__blog-comment-date',
							'title'    => esc_html__( 'Comment - date', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__blog-comment-edit-link',
							'title'    => esc_html__( 'Comment - edit link', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__blog-comment-text',
							'title'    => esc_html__( 'Comment - text', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__blog-comment-reply',
							'title'    => esc_html__( 'Comment - reply', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__blog-comment-reply',
							'title'    => esc_html__( 'Comment - edit link', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__blog-comment-border',
							'title'    => esc_html__( 'Comment - comment horizontal line', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__blog-comment-log-info',
							'title'    => esc_html__( 'Comment - edit link', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Widget sidebar', 'myhome' ),
					'id'     => 'myhome-design-widget-sidebar',
					'colors' => array(
						array(
							'id'       => 'mh-color__mh-sidebar-title',
							'title'    => esc_html__( 'Headings text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-sidebar-title-separator',
							'title'    => esc_html__( 'Headings top border', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-sidebar-infobox-text',
							'title'    => esc_html__( 'Infobox widget text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-sidebar-link-colors',
							'title'    => esc_html__( 'Menus - link color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-sidebar-menu-border',
							'title'    => esc_html__( 'Menus - border color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-sidebar-social-bg',
							'title'    => esc_html__( 'Social icons - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-sidebar-social-border',
							'title'    => esc_html__( 'Social icons - border', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-sidebar-social-font',
							'title'    => esc_html__( 'Social icons - font', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-sidebar-social-bg-hover',
							'title'    => esc_html__( 'Social icons - background hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-sidebar-social-border-hover',
							'title'    => esc_html__( 'Social icons - border hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__mh-sidebar-social-font-hover',
							'title'    => esc_html__( 'Social icons - font hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						)
					)
				),
				array(
					'title'  => esc_html__( 'User card', 'myhome' ),
					'id'     => 'myhome-design-user-card',
					'colors' => array(
						array(
							'id'            => 'mh-color__user-background',
							'title'         => esc_html__( 'Background', 'myhome' ),
							'subtitle'      => esc_html__( 'Click here to see what will change', 'myhome' ),
							'default_color' => '#000'
						),
						array(
							'id'            => 'mh-color__user-name',
							'title'         => esc_html__( 'Name color', 'myhome' ),
							'subtitle'      => esc_html__( 'Click here to see what will change', 'myhome' ),
							'default_color' => '#000'
						),
						array(
							'id'            => 'mh-color__user-text',
							'title'         => esc_html__( 'Description text color', 'myhome' ),
							'subtitle'      => esc_html__( 'Click here to see what will change', 'myhome' ),
							'default_color' => '#000'
						),
						array(
							'id'            => 'mh-color__user-card-info',
							'title'         => esc_html__( 'Additional info text color', 'myhome' ),
							'subtitle'      => esc_html__( 'Click here to see what will change', 'myhome' ),
							'default_color' => '#000'
						),
						array(
							'id'            => 'mh-color__user-social-icons',
							'title'         => esc_html__( 'Social icons color', 'myhome' ),
							'subtitle'      => esc_html__( 'Click here to see what will change', 'myhome' ),
							'default_color' => '#000'
						),
						array(
							'id'       => 'mh-color__agent-button-ghost-primary-color',
							'title'    => esc_html__( 'Button main color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__agent-button-ghost-primary-color-hover',
							'title'    => esc_html__( 'Button text color hover', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Map', 'myhome' ),
					'id'     => 'myhome-design-map',
					'colors' => array(
						array(
							'id'       => 'mh-color__map-property-pin',
							'title'    => esc_html__( 'Property pin color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__map-panel-background',
							'title'    => esc_html__( 'Panel background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__map-panel-border',
							'title'    => esc_html__( 'Panel border', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__map-panel-font',
							'title'    => esc_html__( 'Panel text / icon color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__map-panel-button-active',
							'title'    => esc_html__( 'Button active background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__map-panel-button-active-font',
							'title'    => esc_html__( 'Button active text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__map-property-background',
							'title'    => esc_html__( 'Map card - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__map-property-name',
							'title'    => esc_html__( 'Map card - property name', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__map-property-price',
							'title'    => esc_html__( 'Map card - price color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Form elements', 'myhome' ),
					'id'     => 'myhome-design-input-styles',
					'colors' => array(
						array(
							'id'       => 'mh-color__input-select-background',
							'title'    => esc_html__( 'Input / select / textarea - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__input-select-font-initial',
							'title'    => esc_html__( 'Input / select / textarea - font initial', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__input-select-border',
							'title'    => esc_html__( 'Input / select / textarea - border', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__input-select-active-bg',
							'title'    => esc_html__( 'Input / select / textarea ACTIVE - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__input-select-active-border',
							'title'    => esc_html__( 'Input / select / textarea ACTIVE - border', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__input-select-dropdown-bg',
							'title'    => esc_html__( 'Select drop-down - background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__input-select-dropdown-border',
							'title'    => esc_html__( 'Select drop-down - border', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__input-select-active-font',
							'title'    => esc_html__( 'Input / Select active - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__input-select-dropdown-active-font',
							'title'    => esc_html__( 'Drop-down active - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__input-select-dropdown-active-bg',
							'title'    => esc_html__( 'Drop-down active - background color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__input-select-dropdown-hover-font',
							'title'    => esc_html__( 'Drop-down hover - text color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__input-select-dropdown-hover-bg',
							'title'    => esc_html__( 'Drop-down hover - background color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__checkbox-label',
							'title'    => esc_html__( 'Checkbox / radio - label', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__checkbox-outline',
							'title'    => esc_html__( 'Checkbox / radio border color', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						),
						array(
							'id'       => 'mh-color__checkbox-outline-bg',
							'title'    => esc_html__( 'Checkbox / radio checked background', 'myhome' ),
							'subtitle' => esc_html__( 'Click here to see what will change', 'myhome' ),
						)
					)
				),
			);

			foreach ( $sections as $section ) {
				$fields = array();
				foreach ( $section['colors'] as $color ) {
					$fields[] = array(
						'id'      => $color['id'],
						'type'    => 'color_rgba',
						'title'   => $color['title'],
						// 'subtitle' => '<a target="_blank" href="#' . $color['id'] . '">' . $color['subtitle'] . '</a>',
						'options' => array(
							'show_input'             => true,
							'show_initial'           => false,
							'show_alpha'             => true,
							'show_palette'           => false,
							'show_selection_palette' => false,
							'allow_empty'            => true,
							'clickout_fires_change'  => false,
							'choose_text'            => esc_html__( 'Choose', 'myhome' ),
							'cancel_text'            => esc_html__( 'Cancel', 'myhome' ),
							'input_text'             => esc_html__( 'Select Color', 'myhome' )
						),
					);
				}

				Redux::setSection( $this->opt_name, array(
					'title'      => $section['title'],
					'id'         => $section['id'],
					'subsection' => true,
					'fields'     => $fields
				) );
			}
		}

		public function set_design_options_menu() {
			$theme = wp_get_theme();
			$args  = array(
				'opt_name'            => 'myhome_design',
				'display_name'        => 'MyHome Design',
				'display_version'     => $theme->get( 'Version' ),
				'menu_type'           => 'menu',
				'allow_sub_menu'      => true,
				'menu_title'          => esc_html__( 'MyHome Design', 'myhome' ),
				'page_title'          => esc_html__( 'MyHome Design', 'myhome' ),
				'google_api_key'      => '',
				'async_typography'    => false,
				'admin_bar'           => false,
				'admin_bar_icon'      => 'dashicons-portfolio',
				'admin_bar_priority'  => 50,
				'global_variable'     => '',
				'dev_mode'            => false,
				'show_options_object' => false,
				'update_notice'       => false,
				'customizer'          => false,
				'page_priority'       => 2,
				'page_permissions'    => 'manage_options',
				'last_tab'            => '',
				'page_icon'           => 'icon-themes',
				'page_slug'           => '',
				'use_cdn'             => true,
				'save_defaults'       => false,
				'default_show'        => true,
				'default_mark'        => '',
				'show_import_export'  => true,
				'output'              => true,
				'output_tag'          => true,
				'ajax_save'           => false,
				'color'               => true
			);

			Redux::setArgs( 'myhome_design', $args );

			$sections = array(
				array(
					'title'  => esc_html__( 'General', 'myhome' ),
					'id'     => 'myhome-design-general',
					'colors' => array(
						array(
							'id'    => 'mh-color__body-bg',
							'title' => esc_html__( 'Page background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__button-primary-background',
							'title' => esc_html__( 'Primary button background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__button-primary-color',
							'title' => esc_html__( 'Primary button font color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__button-ghost-primary-color',
							'title' => esc_html__( 'Ghost button main color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__button-ghost-primary-color-hover',
							'title' => esc_html__( 'Ghost button font color hover', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__general-separator',
							'title' => esc_html__( 'Heading separator color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__owl-carousel-dot',
							'title' => esc_html__( 'Carousels - dot', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__owl-carousel-dot-active',
							'title' => esc_html__( 'Carousel - dot active color', 'myhome' ),
						)
					)
				),
				array(
					'title'  => esc_html__( 'Top bar', 'myhome' ),
					'id'     => 'myhome-design-top_bar',
					'colors' => array(
						array(
							'id'    => 'mh-color__mh-top-header-bg',
							'title' => esc_html__( 'Background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-top-header-border',
							'title' => esc_html__( 'Border bottom color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-top-header-font',
							'title' => esc_html__( 'text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-top-header-separator',
							'title' => esc_html__( 'Separator color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__top-bar-panel-bg',
							'title' => esc_html__( 'User menu - link background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__top-bar-panel-font',
							'title' => esc_html__( 'User menu - link text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__top-bar-panel-bg-hover',
							'title' => esc_html__( 'User menu - link hover background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__top-bar-panel-font-hover',
							'title' => esc_html__( 'User menu - link hover text color', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Menu', 'myhome' ),
					'id'     => 'myhome-design-menu',
					'colors' => array(
						array(
							'id'    => 'mh-color-menu-bg-color',
							'title' => esc_html__( 'Menu background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color-menu-border-bottom-color',
							'title' => esc_html__( 'Menu border bottom color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color-menu-first-level-font',
							'title' => esc_html__( 'First level menu text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color-menu-submit-property-button',
							'title' => esc_html__( 'Submit property button text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color-menu-submenu-background',
							'title' => esc_html__( 'Submenu link background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color-menu-submenu-color-font',
							'title' => esc_html__( 'Submenu link text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color-menu-submenu-background-hover',
							'title' => esc_html__( 'Submenu link hover background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color-menu-submenu-font-hover',
							'title' => esc_html__( 'Submenu link hover text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color-menu-flyout-border',
							'title' => esc_html__( 'Flyout menu border color', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Page title', 'myhome' ),
					'id'     => 'myhome-design-page-title',
					'colors' => array(
						array(
							'id'    => 'mh-color__mh-page-title-bg',
							'title' => esc_html__( 'Background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-page-title-heading-color',
							'title' => esc_html__( 'Heading text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-page-title-other-color',
							'title' => esc_html__( 'Text color', 'myhome' ),
						)
					)
				),
				array(
					'title'  => esc_html__( 'Breadcrumbs', 'myhome' ),
					'id'     => 'myhome-design-breadcrumbs',
					'colors' => array(
						array(
							'id'    => 'mh-color__breadcrumbs-bg',
							'title' => esc_html__( 'Background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__breadcrumbs-link-color',
							'title' => esc_html__( 'Links color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__breadcrumbs-separator-color',
							'title' => esc_html__( 'Separators color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__breadcrumbs-color',
							'title' => esc_html__( 'Property name text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__breadcrumbs-border',
							'title' => esc_html__( 'Border top color', 'myhome' ),
						)
					)
				),
				array(
					'title'  => esc_html__( 'Footer', 'myhome' ),
					'id'     => 'myhome-design-footer',
					'colors' => array(
						array(
							'id'    => 'mh-color__mh-footer-bg',
							'title' => esc_html__( 'Background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-footer-color',
							'title' => esc_html__( 'Contact widget text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-footer-col-heading-color',
							'title' => esc_html__( 'Column headers text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-footer-widgets-border',
							'title' => esc_html__( 'Widgets border color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-footer-tag-color',
							'title' => esc_html__( 'Tags main color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-footer-social-bg',
							'title' => esc_html__( 'Social icons - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-footer-social-border',
							'title' => esc_html__( 'Social icons - border color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-footer-social-font',
							'title' => esc_html__( 'Social icons - text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-footer-social-bg-hover',
							'title' => esc_html__( 'Social icons - background hover', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-footer-social-border-hover',
							'title' => esc_html__( 'Social icons - border hover', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-footer-social-font-hover',
							'title' => esc_html__( 'Social icons - font hover', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-footer-bottom-bg',
							'title' => esc_html__( 'Copyright area - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-footer-bottom-color',
							'title' => esc_html__( 'Copyright area - text color', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Single property page', 'myhome' ),
					'id'     => 'myhome-design-single-property',
					'colors' => array(
						array(
							'id'    => 'mh-color__single-property-page-bg',
							'title' => esc_html__( 'Page background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__single-property-page-section-bg',
							'title' => esc_html__( 'Sections - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__single-property-page-heading-color',
							'title' => esc_html__( 'Sections - heading color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__single-property-page-heading-sep',
							'title' => esc_html__( 'Sections - heading separator', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__single-property-page-dot',
							'title' => esc_html__( 'Sections - lists dots color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__single-property-price-bg',
							'title' => esc_html__( 'Price background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__single-property-price-color',
							'title' => esc_html__( 'Price text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__single-property-details-bg',
							'title' => esc_html__( 'More info (below price) - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__single-property-details-color',
							'title' => esc_html__( 'More info (below price) - text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__single-property-sidebar-heading-color',
							'title' => esc_html__( 'Sidebar heading - text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__single-property-sidebar-heading-sep',
							'title' => esc_html__( 'Sidebar heading - separator color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__single-property-contact-send-bg',
							'title' => esc_html__( 'Contact Form - send button background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__single-property-contact-send-font',
							'title' => esc_html__( 'Contact Form - send button text color', 'myhome' ),
						)
					)
				),
				array(
					'title'  => esc_html__( 'Property slider', 'myhome' ),
					'id'     => 'myhome-design-property-slider',
					'colors' => array(
						array(
							'id'    => 'mh-color__slider-price-bg',
							'title' => esc_html__( 'Property slider - price background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__slider-price-font',
							'title' => esc_html__( 'Property slider - price text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__slider-bg',
							'title' => esc_html__( 'Property slider - card background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__slider-heading',
							'title' => esc_html__( 'Property slider - heading text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__arrows-bg',
							'title' => esc_html__( 'Slider arrows - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__arrows-color',
							'title' => esc_html__( 'Slider arrows - icon color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__arrows-bg-hover',
							'title' => esc_html__( 'Slider arrows - background - hover', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__arrows-color-hover',
							'title' => esc_html__( 'Slider arrows - icon color - hover', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__img-popup-close',
							'title' => esc_html__( 'Single property - image popup - close text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__img-popup-counter',
							'title' => esc_html__( 'Single property - image popup - counter text color', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Property search form', 'myhome' ),
					'id'     => 'myhome-design-search-form-dynamic',
					'colors' => array(
						array(
							'id'    => 'mh-color__search-horizontal-background',
							'title' => esc_html__( 'Search Form Top / Bottom - background color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__search-horizontal-border',
							'title' => esc_html__( 'Search Form Top / Bottom - border top color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__search-horizontal-label',
							'title' => esc_html__( 'Search Form Top / Bottom - labels color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__search-vertical-label',
							'title' => esc_html__( 'Search Form Left / Right - labels color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__search-horizontal-button-advanced',
							'title' => esc_html__( 'Horizontal - Advanced button - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__search-horizontal-button-advanced-font',
							'title' => esc_html__( 'Horizontal - Advanced Button - text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__search-horizontal-button-clear',
							'title' => esc_html__( 'Horizontal - Clear button - color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__search-horizontal-button-clear-hover',
							'title' => esc_html__( 'Horizontal - Clear button - text color hover', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__filters-bg',
							'title' => esc_html__( 'Filters - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__filters-sort-by-label',
							'title' => esc_html__( 'Filters - "Sort by:" text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__filters-sort',
							'title' => esc_html__( 'Filters - not active sorting type - text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__filters-sort-active',
							'title' => esc_html__( 'Filters - active sorting type - text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__filters-grid-icon',
							'title' => esc_html__( 'Filters - grid icon', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__filters-grid-icon-active',
							'title' => esc_html__( 'Filters - grid icon active', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__search-results',
							'title' => esc_html__( 'Search results - text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__search-filters-color',
							'title' => esc_html__( 'Search - labels e.g. "for sale (x)"', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__search-end-border',
							'title' => esc_html__( 'Search end - border', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__search-load-more-background',
							'title' => esc_html__( 'Load More button - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__search-load-more-color',
							'title' => esc_html__( 'Load More button - text color', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Property card', 'myhome' ),
					'id'     => 'myhome-design-property-card',
					'colors' => array(
						array(
							'id'    => 'mh-color__property-card-background',
							'title' => esc_html__( 'Background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__property-card-heading-color',
							'title' => esc_html__( 'Heading text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__property-card-address-color',
							'title' => esc_html__( 'Address text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__property-card-price-color',
							'title' => esc_html__( 'Price text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__property-card-info-color',
							'title' => esc_html__( 'Additional information text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__property-card-date-color',
							'title' => esc_html__( 'Date text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__details-button-ghost-primary-color',
							'title' => esc_html__( 'Details button main color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__details-button-ghost-primary-color-hover',
							'title' => esc_html__( 'Details button text color hover', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__property-card-compare-color',
							'title' => esc_html__( '"Compare" text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__property-card-compare-active-background',
							'title' => esc_html__( '"Compare" active - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__property-card-compare-active-color',
							'title' => esc_html__( '"Compare" active - text color', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Compare', 'myhome' ),
					'id'     => 'myhome-design-compare-bar',
					'colors' => array(
						array(
							'id'    => 'mh-color__compare-bg',
							'title' => esc_html__( 'Background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__compare-border',
							'title' => esc_html__( 'Bar border top color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__compare-font-one',
							'title' => esc_html__( '"Compare: X" and "Clear" text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__compare-font-two',
							'title' => esc_html__( '"SHOW" / "HIDE" text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__compare-column-bg',
							'title' => esc_html__( 'Open - column background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__compare-column-title',
							'title' => esc_html__( 'Open - column title text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__compare-column-price-bg',
							'title' => esc_html__( 'Open - price background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__compare-column-price-font',
							'title' => esc_html__( 'Open - price text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__compare-column-address',
							'title' => esc_html__( 'Open - address text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__compare-column-other-text',
							'title' => esc_html__( 'Open - text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__compare-column-separators',
							'title' => esc_html__( 'Open - horizontal lines color', 'myhome' ),
						)
					)
				),
				array(
					'title'  => esc_html__( 'Post card', 'myhome' ),
					'id'     => 'myhome-design-post-card',
					'colors' => array(
						array(
							'id'    => 'mh-color__post-card-background',
							'title' => esc_html__( 'Background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__post-card-title',
							'title' => esc_html__( 'Post title', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__post-card-decription',
							'title' => esc_html__( 'Description text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__post-card-date-bg',
							'title' => esc_html__( 'Date background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__post-card-date-font',
							'title' => esc_html__( 'Date text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__post-button-ghost-primary-color',
							'title' => esc_html__( 'Button main color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__post-button-ghost-primary-color-hover',
							'title' => esc_html__( 'Button text color hover', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Blog post', 'myhome' ),
					'id'     => 'myhome-design-blog-post',
					'colors' => array(
						array(
							'id'    => 'mh-color__post-title',
							'title' => esc_html__( 'Post title text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__post-meta',
							'title' => esc_html__( 'Post meta - text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__post-meta-hover',
							'title' => esc_html__( 'Post meta - link hover text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__post-meta-separators',
							'title' => esc_html__( 'Post meta - separators color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__post-color',
							'title' => esc_html__( 'Post text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__tag-general-color',
							'title' => esc_html__( 'Tags main color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__nav-border-top-color',
							'title' => esc_html__( 'Navigation - border top color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__nav-link-hover-box',
							'title' => esc_html__( 'Navigation - hover block color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__nav-link',
							'title' => esc_html__( 'Navigation - prev / next text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__nav-link-span',
							'title' => esc_html__( 'Navigation - post name text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__blog-author-card-bg',
							'title' => esc_html__( 'Author card - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__blog-author-card-label',
							'title' => esc_html__( 'Author card - label', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__blog-author-card-name',
							'title' => esc_html__( 'Author card - name', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__blog-author-card-par',
							'title' => esc_html__( 'Author card - description', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__blog-comment-author',
							'title' => esc_html__( 'Comment - author', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__blog-comment-date',
							'title' => esc_html__( 'Comment - date', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__blog-comment-edit-link',
							'title' => esc_html__( 'Comment - edit link', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__blog-comment-text',
							'title' => esc_html__( 'Comment - text', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__blog-comment-reply',
							'title' => esc_html__( 'Comment - reply', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__blog-comment-reply',
							'title' => esc_html__( 'Comment - edit link', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__blog-comment-border',
							'title' => esc_html__( 'Comment - comment horizontal line', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__blog-comment-log-info',
							'title' => esc_html__( 'Comment - edit link', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Widget sidebar', 'myhome' ),
					'id'     => 'myhome-design-widget-sidebar',
					'colors' => array(
						array(
							'id'    => 'mh-color__mh-sidebar-title',
							'title' => esc_html__( 'Headings text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-sidebar-title-separator',
							'title' => esc_html__( 'Headings top border', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-sidebar-infobox-text',
							'title' => esc_html__( 'Infobox widget text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-sidebar-link-colors',
							'title' => esc_html__( 'Menus - link color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-sidebar-menu-border',
							'title' => esc_html__( 'Menus - border color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-sidebar-social-bg',
							'title' => esc_html__( 'Social icons - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-sidebar-social-border',
							'title' => esc_html__( 'Social icons - border', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-sidebar-social-font',
							'title' => esc_html__( 'Social icons - font', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-sidebar-social-bg-hover',
							'title' => esc_html__( 'Social icons - background hover', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-sidebar-social-border-hover',
							'title' => esc_html__( 'Social icons - border hover', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__mh-sidebar-social-font-hover',
							'title' => esc_html__( 'Social icons - font hover', 'myhome' ),
						)
					)
				),
				array(
					'title'  => esc_html__( 'User card', 'myhome' ),
					'id'     => 'myhome-design-user-card',
					'colors' => array(
						array(
							'id'            => 'mh-color__user-background',
							'title'         => esc_html__( 'Background', 'myhome' ),
							'subtitle'      => esc_html__( 'Click here to see what will change', 'myhome' ),
							'default_color' => '#000'
						),
						array(
							'id'            => 'mh-color__user-name',
							'title'         => esc_html__( 'Name color', 'myhome' ),
							'subtitle'      => esc_html__( 'Click here to see what will change', 'myhome' ),
							'default_color' => '#000'
						),
						array(
							'id'            => 'mh-color__user-text',
							'title'         => esc_html__( 'Description text color', 'myhome' ),
							'subtitle'      => esc_html__( 'Click here to see what will change', 'myhome' ),
							'default_color' => '#000'
						),
						array(
							'id'            => 'mh-color__user-card-info',
							'title'         => esc_html__( 'Additional info text color', 'myhome' ),
							'subtitle'      => esc_html__( 'Click here to see what will change', 'myhome' ),
							'default_color' => '#000'
						),
						array(
							'id'            => 'mh-color__user-social-icons',
							'title'         => esc_html__( 'Social icons color', 'myhome' ),
							'subtitle'      => esc_html__( 'Click here to see what will change', 'myhome' ),
							'default_color' => '#000'
						),
						array(
							'id'    => 'mh-color__agent-button-ghost-primary-color',
							'title' => esc_html__( 'Button main color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__agent-button-ghost-primary-color-hover',
							'title' => esc_html__( 'Button text color hover', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Map', 'myhome' ),
					'id'     => 'myhome-design-map',
					'colors' => array(
						array(
							'id'    => 'mh-color__map-property-pin',
							'title' => esc_html__( 'Property pin color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__map-panel-background',
							'title' => esc_html__( 'Panel background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__map-panel-border',
							'title' => esc_html__( 'Panel border', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__map-panel-font',
							'title' => esc_html__( 'Panel text / icon color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__map-panel-button-active',
							'title' => esc_html__( 'Button active background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__map-panel-button-active-font',
							'title' => esc_html__( 'Button active text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__map-property-background',
							'title' => esc_html__( 'Map card - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__map-property-name',
							'title' => esc_html__( 'Map card - property name', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__map-property-price',
							'title' => esc_html__( 'Map card - price color', 'myhome' ),
						),
					)
				),
				array(
					'title'  => esc_html__( 'Form elements', 'myhome' ),
					'id'     => 'myhome-design-input-styles',
					'colors' => array(
						array(
							'id'    => 'mh-color__input-select-background',
							'title' => esc_html__( 'Input / select / textarea - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__input-select-font-initial',
							'title' => esc_html__( 'Input / select / textarea - font initial', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__input-select-border',
							'title' => esc_html__( 'Input / select / textarea - border', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__input-select-active-bg',
							'title' => esc_html__( 'Input / select / textarea ACTIVE - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__input-select-active-border',
							'title' => esc_html__( 'Input / select / textarea ACTIVE - border', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__input-select-dropdown-bg',
							'title' => esc_html__( 'Select drop-down - background', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__input-select-dropdown-border',
							'title' => esc_html__( 'Select drop-down - border', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__input-select-active-font',
							'title' => esc_html__( 'Input / Select active - text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__input-select-dropdown-active-font',
							'title' => esc_html__( 'Drop-down active - text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__input-select-dropdown-active-bg',
							'title' => esc_html__( 'Drop-down active - background color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__input-select-dropdown-hover-font',
							'title' => esc_html__( 'Drop-down hover - text color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__input-select-dropdown-hover-bg',
							'title' => esc_html__( 'Drop-down hover - background color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__checkbox-label',
							'title' => esc_html__( 'Checkbox / radio - label', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__checkbox-outline',
							'title' => esc_html__( 'Checkbox / radio border color', 'myhome' ),
						),
						array(
							'id'    => 'mh-color__checkbox-outline-bg',
							'title' => esc_html__( 'Checkbox / radio checked background', 'myhome' ),
						)
					)
				),
			);

			foreach ( $sections as $section ) {
				$fields = array();
				foreach ( $section['colors'] as $color ) {
					$fields[] = array(
						'id'       => $color['id'],
						'type'     => 'color_rgba',
						'title'    => $color['title'],
						'subtitle' => esc_html__( 'Click here to see what change', 'myhome' ),
						// 'subtitle' => '<a target="_blank" href="#' . $color['id'] . '">' . $color['subtitle'] . '</a>',
						'options'  => array(
							'show_input'             => true,
							'show_initial'           => false,
							'show_alpha'             => true,
							'show_palette'           => false,
							'show_selection_palette' => false,
							'allow_empty'            => true,
							'clickout_fires_change'  => false,
							'choose_text'            => esc_html__( 'Choose', 'myhome' ),
							'cancel_text'            => esc_html__( 'Cancel', 'myhome' ),
							'input_text'             => esc_html__( 'Select Color', 'myhome' )
						),
					);
				}

				Redux::setSection( 'myhome_design', array(
					'title'      => $section['title'],
					'id'         => $section['id'],
					'subsection' => false,
					'fields'     => $fields
				) );
			}

			Redux::init( 'myhome_design' );
		}

		public function set_map_options() {
			$section = array(
				'title'  => esc_html__( 'Map', 'myhome' ),
				'id'     => 'myhome-map-opts',
				'icon'   => 'el el-map-marker',
				'fields' => array(
					// google api key, required by maps and street view
					array(
						'id'       => 'mh-google-api-key',
						'type'     => 'text',
						'title'    => esc_html__( 'Google API Key', 'myhome' ),
						'subtitle' => '<a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/115001338554">' .
						              __( 'Click here to read how to obtain Google Map API Key', 'myhome' ) . '</a>',
						'default'  => '',
					),
					array(
						'id'       => 'mh-map-center_lat',
						'type'     => 'text',
						'title'    => esc_html__( 'Add new property - default map location - latitude', 'myhome' ),
						'subtitle' => '<a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360000444753">' . esc_html__( 'Click here to read how to get Google Maps latitude and longitude',
								'myhome' ) . '</a><br>' . esc_html__( 'New York default value: 40.7128', 'myhome' ),
						'default'  => '40.7128'
					),
					array(
						'id'       => 'mh-map-center_lng',
						'type'     => 'text',
						'title'    => esc_html__( 'Add new property - default map location - longitude', 'myhome' ),
						'subtitle' => '<a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360000444753">' . esc_html__( 'Click here to read how to get Google Maps latitude and longitude',
								'myhome' ) . '</a><br>' . esc_html__( 'New York default value: -73.971485', 'myhome' ),
						'default'  => '-73.971485'
					),
					array(
						'id'      => 'mh-map-style',
						'title'   => esc_html__( 'Map style', 'myhome' ),
						'type'    => 'select',
						'options' => array(
							'gray'   => esc_html__( 'MyHome gray palette', 'myhome' ),
							'google' => esc_html__( 'Default by Google', 'myhome' ),
							'custom' => esc_html__( 'Custom (Snazzy Maps)', 'myhome' ),
						),
						'default' => 'gray',
					),
					array(
						'id'       => 'mh-map-type',
						'title'    => esc_html__( 'Map type', 'myhome' ),
						'type'     => 'select',
						'options'  => array(
							'roadmap'   => esc_html__( 'Roadmap', 'myhome' ),
							'satellite' => esc_html__( 'Satellite', 'myhome' ),
							'hybrid'    => esc_html__( 'Hybrid', 'myhome' ),
							'terrain'   => esc_html__( 'Terrain', 'myhome' )
						),
						'default'  => 'roadmap',
						'required' => array(
							array( 'mh-map-style', '=', 'google' )
						)
					),
					array(
						'id'       => 'mh-map-style_custom',
						'title'    => esc_html__( 'Snazzy Maps', 'myhome' ),
						'subtitle' => esc_html__( 'Visit: https://snazzymaps.com/ - find your favorite map and copy "JAVASCRIPT STYLE ARRAY".',
							'myhome' ),
						'type'     => 'textarea',
						'required' => array(
							array( 'mh-map-style', '=', 'custom' ),
						),
					),
					array(
						'id'      => 'mh-map-clustering',
						'type'    => 'switch',
						'default' => true,
						'title'   => esc_html__( '"Marker clustering" - option', 'myhome' )
					),
					array(
						'id'      => 'mh-map-street',
						'type'    => 'switch',
						'default' => true,
						'title'   => esc_html__( '"Street View" - option', 'myhome' )
					),
					array(
						'id'      => 'mh-google-map-api-all',
						'type'    => 'switch',
						'default' => false,
						'title'   => esc_html__( 'Load Google Maps API on every page', 'myhome' ),
					),
				)
			);
			Redux::setSection( $this->opt_name, $section );

		}

		public function set_breadcrumbs_options() {
			if ( ! class_exists( 'MyHomeCore\Core' ) ) {
				return;
			}
			$fields          = array(
				array(
					'id'      => 'mh-breadcrumbs',
					'title'   => esc_html__( 'Breadcrumbs', 'myhome' ),
					'type'    => 'switch',
					'default' => false
				),
				array(
					'id'       => 'mh-breadcrumbs_show-count',
					'title'    => esc_html__( 'Breadcrumbs - show number of available properties next to the category link',
						'myhome' ),
					'type'     => 'switch',
					'default'  => false,
					'required' => array( 'mh-breadcrumbs', '=', '1' )
				)
			);
			$attributes_list = array();
			$attributes      = \MyHomeCore\Attributes\Attribute_Factory::get_text();

			foreach ( $attributes as $attribute ) {
				$attributes_list[ $attribute->get_ID() ] = $attribute->get_name();
			}

			foreach ( $attributes as $key => $attribute ) {
				$position = $key + 1;
				$fields[] = array(
					'id'       => 'mh-breadcrumbs_' . $key,
					'title'    => sprintf( esc_html__( 'Position %s', 'myhome' ), $position ),
					'type'     => 'select',
					'options'  => $attributes_list,
					'required' => array( 'mh-breadcrumbs', '=', '1' )
				);
			}

			$section = array(
				'title'  => esc_html__( 'Breadcrumbs', 'myhome' ),
				'desc'   => esc_html__( 'Breadcrumbs "ON" changes the property page URL to /parameter1/parameter2/.../name-of-the-property',
						'myhome' ) . '<br><br><b>' . esc_html__( ' To make sure it works correctly please:',
						'myhome' ) . '</b><br>' . esc_html__( '- choose only this parameters that have single value (e.g. city, property type)',
						'myhome' ) . '<br>' . esc_html__( '- make sure the field is always filled. You can make it required(*) on your front-end submit property form ( /wp-admin/ > MyHome Panel > submit property > edit field > check required > save)',
						'myhome' ),
				'id'     => 'myhome-breadcrumbs-opts',
				'fields' => $fields
			);

			Redux::setSection( $this->opt_name, $section );
		}

		/*
		 * set_typography_options
		 *
		 * Header options
		 */
		public function set_typography_options() {
			$section = array(
				'title'  => esc_html__( 'Typography', 'myhome' ),
				'id'     => 'myhome-typography-opts',
				'icon'   => 'el el-font',
				'fields' => array(
					// font default
					array(
						'id'          => 'mh-typography-default',
						'type'        => 'typography',
						'title'       => esc_html__( 'Main font', 'myhome' ),
						'google'      => true,
						'font-backup' => true,
						'font-size'   => false,
						'line-height' => false,
						'font-style'  => false,
						'text-align'  => false,
						'output'      => array(
							'
                            body,
                            button,
                            input,
                            optgroup,
                            select,
                            textarea,
                            .mh-accordion .ui-accordion-header,
                            .mh-estate-horizontal__subheading,
                            .mh-estate-horizontal__primary,
                            .mh-estate-vertical__subheading,
                            .mh-estate-vertical__primary,
                            .mh-map-infobox,
                            .mh-user-panel-info__heading,
                            .mh-font-body
                        ',
						),
						'color'       => false,
						'units'       => 'px',
						'default'     => array(
							'google'      => true,
							'font-family' => 'Lato',
							'font-weight' => '400',
							'subsets'     => 'latin-ext',
						),
					),
					// font default italic
					array(
						'id'          => 'mh-typography-default-italic',
						'type'        => 'typography',
						'title'       => esc_html__( 'Main font - italic', 'myhome' ),
						'subtitle'    => esc_html__( 'Leave empty if "Main Font" has no separate italic version.',
							'myhome' ),
						'google'      => true,
						'font-backup' => true,
						'font-size'   => false,
						'line-height' => false,
						'text-align'  => false,
						'output'      => array( ' .mh-main-font-italic' ),
						'color'       => false,
						'units'       => 'px',
						'default'     => array(
							'google'      => true,
							'font-family' => 'Lato',
							'font-style'  => 'italic',
							'font-weight' => '400',
							'subsets'     => 'latin-ext',
						),
					),
					// font default bold
					array(
						'id'          => 'mh-typography-default-bold',
						'type'        => 'typography',
						'title'       => esc_html__( 'Main font - bold (700)', 'myhome' ),
						'google'      => true,
						'font-backup' => true,
						'font-size'   => false,
						'font-style'  => false,
						'line-height' => false,
						'text-align'  => false,
						'output'      => array(
							'                     
                      .mh-estate-horizontal__primary,
                      .mh-estate-vertical__primary   
                     ',
						),
						'color'       => false,
						'units'       => 'px',
						'default'     => array(
							'google'      => true,
							'font-family' => 'Lato',
							'font-weight' => '700',
							'subsets'     => 'latin-ext',
						),
					),
					// font heading
					array(
						'id'          => 'mh-typography-heading',
						'type'        => 'typography',
						'title'       => esc_html__( 'Heading font', 'myhome' ),
						'google'      => true,
						'font-backup' => true,
						'font-size'   => false,
						'line-height' => false,
						'font-style'  => false,
						'text-align'  => false,
						'output'      => array(
							'
							h1,
                            h2,
                            h3,
                            h4,
                            h5,
                            h6,
                            .mh-estate__details__price,
                            .mh-top-header,
                            .mh-top-header-big__panel,   
                            .mh-caption__inner,
                            .mh-slider-single__price,
                            .mh-heading-font-bold,
                            .mh-search__results,
                            .mh-user-panel__user__content
                        ',
						),
						'color'       => false,
						'units'       => 'px',
						'default'     => array(
							'google'      => true,
							'font-family' => 'Play',
							'font-weight' => '400',
							'subsets'     => 'latin-ext',
						),
					),
					// font heading bold
					array(
						'id'          => 'mh-typography-heading-bold',
						'type'        => 'typography',
						'title'       => esc_html__( 'Heading font - bold (700)', 'myhome' ),
						'google'      => true,
						'font-backup' => false,
						'font-size'   => false,
						'font-style'  => false,
						'line-height' => false,
						'text-align'  => false,
						'output'      => array(
							'
                                 h1,
                                 .mh-caption__inner,
                                 .mh-slider-single__price,
                                 .mh-heading-font-bold,
                                 .mh-search__results,
                                 .mh-user-panel__user__content,                     
                                 #IDX-main .IDX-control-label,
                                 .mh-top-title__heading, 
                                 #myhome-idx-wrapper .IDX-control-label,
                                 #myhome-idx-wrapper .IDX-addressField label,
                                 #myhome-idx-wrapper__details-detailsDynamic-1008 #IDX-detailsFeaturedAgentdisplayname,
                                 #myhome-idx-wrapper .IDX-page-listing #IDX-detailsFeaturedAgentdisplayname,
                                .myhome-idx-wrapper__results-mobileFirstResults-1006 .IDX-bioName,
                                #IDX-featuredAgentWrap.IDX-featuredAgentWrap .IDX-featuredAgentContact,
                                .IDX-showcaseTable .IDX-showcasePrice,
                                .IDX-slideshowWrapper .IDX-slideshowPrice                
                            ',
						),
						'color'       => false,
						'units'       => 'px',
						'default'     => array(
							'google'      => true,
							'font-family' => 'Play',
							'font-weight' => '700',
							'subsets'     => 'latin-ext',
						),
					),
				),
			);
			Redux::setSection( $this->opt_name, $section );
		}

		/*
		 * set_header_options
		 *
		 * Header options
		 */
		public function set_header_options() {
			if ( class_exists( 'MyHomeCore\Core' ) ) {
				$currencies = \MyHomeCore\Attributes\Price_Attribute_Options_Page::get_currencies_list();
			} else {
				$currencies = array();
			}

			$section = array(
				'title' => esc_html__( 'Header', 'myhome' ),
				'id'    => 'myhome-header-opts',
				'icon'  => 'el el-cog',
			);
			Redux::setSection( $this->opt_name, $section );
			/*
			 * Top bar
			 */
			$section = array(
				'title'      => esc_html__( 'Header general', 'myhome' ),
				'desc'       => esc_html__( 'To make menu options works, your menu "display location" must be set to "MH Primary" (edit menu, scroll to the bottom, set display location checkbox). Mega Main Menu plugin must be active.',
					'myhome' ),
				'id'         => 'myhome-top-header-general',
				'subsection' => true,
				'fields'     => array(
					// Logo
					array(
						'id'       => 'mh-logo',
						'default'  => array( 'url' => get_template_directory_uri() . '/assets/images/logo.png' ),
						'subtitle' => esc_html__( 'This is a default logo for desktop and mobile menu', 'myhome' ),
						'type'     => 'media',
						'title'    => esc_html__( 'Logo default', 'myhome' ),
					),
					// Logo dark
					array(
						'id'       => 'mh-logo-dark',
						'default'  => array( 'url' => get_template_directory_uri() . '/assets/images/logo-transparent-menu.png' ),
						'subtitle' => esc_html__( 'This logo will be used on pages with transparent menu only',
							'myhome' ),
						'type'     => 'media',
						'title'    => esc_html__( 'Transparent menu logo', 'myhome' ),
					),
					// Sticky
					array(
						'id'      => 'mh-sticky-menu',
						'type'    => 'switch',
						'title'   => esc_html__( 'Sticky menu', 'myhome' ),
						'default' => 0,
					),
					// Sticky Transparent
					array(
						'id'       => 'mh-sticky-menu-transparent',
						'type'     => 'select',
						'title'    => esc_html__( 'Sticky transparent Style', 'myhome' ),
						'options'  => array(
							'light'    => esc_html__( 'Light background', 'myhome' ),
							'advanced' => esc_html__( 'Custom colors', 'myhome' ),
						),
						'default'  => 'light',
						'required' => array(
							'mh-sticky-menu',
							'!=',
							'0',
						),
					),
					// Show submit property button
					array(
						'id'       => 'mh-agent-submit_property',
						'type'     => 'switch',
						'title'    => esc_html__( 'Display "Submit Property" button', 'myhome' ),
						'required' => array(
							array( 'mh-agent-panel', '=', true ),
						),
						'default'  => true,
					),
					// Top Wide
					array(
						'id'       => 'mh-top-wide',
						'type'     => 'switch',
						'title'    => esc_html__( 'Full width menu and top bars container', 'myhome' ),
						'subtitle' => esc_html__( 'By default max width is 1170px, but you can change it for fullwidth',
							'myhome' ),
						'default'  => 0,
					),
					// Menu primary
					array(
						'id'       => 'mh-menu-primary',
						'type'     => 'switch',
						'title'    => esc_html__( 'Menu background "primary color"', 'myhome' ),
						'subtitle' => esc_html__( 'Change the menu background into primary color and first level items text color into white',
							'myhome' ),
						'default'  => 0,
					),
					// Menu Height
					array(
						'id'      => 'mh-menu-height',
						'type'    => 'text',
						'default' => '80',
						'title'   => esc_html__( 'Desktop menu height (px)', 'myhome' ),
					),
					// Logo height
					array(
						'id'       => 'mh-logo-height',
						'type'     => 'text',
						'default'  => '40',
						'title'    => esc_html__( 'Logo height (px)', 'myhome' ),
						'subtitle' => esc_html__( 'Height of the default logo and transparent menu logo. Cannot be bigger than menu height.',
							'myhome' ),
					),
					// Logo margin top
					array(
						'id'      => 'mh-logo-margin_top',
						'type'    => 'text',
						'default' => '0',
						'title'   => esc_html__( 'Logo margin top (px)', 'myhome' ),
					),
					// Logo margin right
					array(
						'id'      => 'mh-logo-margin-right',
						'type'    => 'text',
						'default' => '48',
						'title'   => esc_html__( 'Logo margin right (px)', 'myhome' ),
					),
					array(
						'id'       => 'mh-logo-hide',
						'default'  => false,
						'type'     => 'switch',
						'title'    => esc_html__( 'Hide menu logo on the desktop', 'myhome' ),
						'subtitle' => esc_html__( 'Menu logo will be displayed only on the mobile. This option can be useful if you have logo in the top bar, but you do not want to display top bar on the mobile.',
							'myhome' )
					),
					// First level item align
					array(
						'id'      => 'mh-menu-first-level-item-align',
						'type'    => 'select',
						'title'   => esc_html__( 'First level item align', 'myhome' ),
						'options' => array(
							'left'   => esc_html__( 'left', 'myhome' ),
							'right'  => esc_html__( 'right', 'myhome' ),
							'center' => esc_html__( 'center', 'myhome' ),
						),
						'default' => 'left',
					),
					// Font size of first level item
					array(
						'id'      => 'mh-menu-first-level-item-size',
						'type'    => 'text',
						'default' => '14',
						'title'   => esc_html__( 'Font size of first level item (px)', 'myhome' ),
					),
					// Logo margin right
					array(
						'id'       => 'mh-menu-link-margin-right',
						'type'     => 'text',
						'default'  => '10',
						'title'    => esc_html__( 'First level item margin right (px)', 'myhome' ),
						'subtitle' => esc_html__( 'Default: 10',
								'myhome' ) . '<br>' . esc_html__( 'You can also use negative values e.g. -5.', 'myhome' )
					),
					// Font size of the dropdown menu item
					array(
						'id'      => 'mh-menu-dropdown-item-height',
						'type'    => 'text',
						'default' => '12',
						'title'   => esc_html__( 'Font size of the dropdown menu item (px)', 'myhome' )
					),
					// Menu drop-down width
					array(
						'id'      => 'mh-menu-drop-down-width',
						'type'    => 'text',
						'default' => '225',
						'title'   => esc_html__( 'Desktop menu - drop-down width (px)', 'myhome' ),
					)
				),
			);
			Redux::setSection( $this->opt_name, $section );

			/*
			 * Top bar
			 */
			$section = array(
				'title'      => esc_html__( 'Top bar', 'myhome' ),
				'id'         => 'myhome-top-header',
				'subsection' => true,
				'fields'     => array(
					// Header style
					array(
						'id'       => 'mh-top-header-style',
						'type'     => 'select',
						'title'    => esc_html__( 'Top bar', 'myhome' ),
						'subtitle' => esc_html__( 'Additional bar with contact information at the top of the menu',
							'myhome' ),
						'options'  => array(
							'none'          => esc_html__( 'none', 'myhome' ),
							'small'         => esc_html__( 'Small - white background', 'myhome' ),
							'small-primary' => esc_html__( 'Small - primary color background', 'myhome' ),
							'big'           => esc_html__( 'Big - white background', 'myhome' ),
						),
						'default'  => 'small',
					),
					// Hide top bar on mobile
					array(
						'id'       => 'mh-top-header-mobile',
						'type'     => 'switch',
						'title'    => esc_html__( 'Hide top bar on mobile', 'myhome' ),
						'default'  => true,
						'required' => array(
							array( 'mh-top-header-style', '!=', 'none' ),
						),
					),
					array(
						'id'       => 'mh-agent_show-user-bar',
						'title'    => esc_html__( 'Display user bar', 'myhome' ),
						'type'     => 'switch',
						'default'  => true,
						'required' => array(
							array( 'mh-top-header-style', '!=', 'none' ),
						),
					),
					array(
						'id'       => 'mh-agent_user-bar-text',
						'type'     => 'text',
						'title'    => esc_html__( 'User bar login text', 'myhome' ),
						'default'  => esc_html__( 'Login / Register', 'myhome' ),
						'required' => array(
							'mh-agent_show-user-bar',
							'=',
							1,
						),
					),

					array(
						'id'       => 'mh-agent-register_popup',
						'type'     => 'switch',
						'title'    => esc_html__( 'Login / Register link - open popup', 'myhome' ),
						'default'  => false,
						'required' => array(
							'mh-agent_show-user-bar',
							'=',
							1,
						),
					),
					// Logo Big
					array(
						'id'       => 'mh-logo-top-bar',
						'default'  => array( 'url' => get_template_directory_uri() . '/assets/images/logo-top-bar.png' ),
						'subtitle' => esc_html__( 'This logo will be displayed for screens larger than 1024px only. On mobile theme will still use "Default Logo"',
							'myhome' ),
						'type'     => 'media',
						'title'    => esc_html__( 'Logo - Top Bar Big', 'myhome' ),
						'required' => array(
							'mh-top-header-style',
							'=',
							'big',
						),
					),
					// Logo Big height
					array(
						'id'       => 'mh-logo-top-bar_height',
						'title'    => esc_html__( 'Logo - Top Bar Big height (px)', 'myhome' ),
						'type'     => 'text',
						'default'  => '50',
						'required' => array(
							'mh-top-header-style',
							'=',
							'big',
						),
					),
					// Logo Big margin top
					array(
						'id'       => 'mh-logo-top-bar_margin_top',
						'title'    => esc_html__( 'Logo - Top Bar Big margin top (px)', 'myhome' ),
						'default'  => '0',
						'type'     => 'text',
						'required' => array(
							'mh-top-header-style',
							'=',
							'big',
						),
					),
					// Address
					array(
						'id'       => 'mh-header-address',
						'default'  => esc_html__( '518-520 5th Ave, New York, USA', 'myhome' ),
						'type'     => 'text',
						'title'    => esc_html__( 'Address', 'myhome' ),
						'required' => array(
							'mh-top-header-style',
							'!=',
							'none',
						),
					),
					// Phone
					array(
						'id'       => 'mh-header-phone',
						'default'  => esc_html__( '(123) 345-6789', 'myhome' ),
						'type'     => 'text',
						'title'    => esc_html__( 'Phone', 'myhome' ),
						'required' => array(
							'mh-top-header-style',
							'!=',
							'none',
						),
					),
					// Email
					array(
						'id'       => 'mh-header-email',
						'default'  => esc_html__( 'support@tangibledesign.net', 'myhome' ),
						'type'     => 'text',
						'title'    => esc_html__( 'Email', 'myhome' ),
						'required' => array(
							'mh-top-header-style',
							'!=',
							'none',
						),
					),
					// Facebook
					array(
						'id'       => 'mh-header-facebook',
						'default'  => esc_html__( '#', 'myhome' ),
						'type'     => 'text',
						'title'    => esc_html__( 'Facebook (URL)', 'myhome' ),
						'required' => array(
							'mh-top-header-style',
							'!=',
							'none',
						),
					),
					// Linkedin
					array(
						'id'       => 'mh-header-linkedin',
						'default'  => esc_html__( '#', 'myhome' ),
						'type'     => 'text',
						'title'    => esc_html__( 'Linkedin (URL)', 'myhome' ),
						'required' => array(
							'mh-top-header-style',
							'!=',
							'none',
						),
					),
					// Twitter
					array(
						'id'       => 'mh-header-twitter',
						'default'  => esc_html__( '#', 'myhome' ),
						'type'     => 'text',
						'title'    => esc_html__( 'Twitter (URL)', 'myhome' ),
						'required' => array(
							'mh-top-header-style',
							'!=',
							'none',
						),
					),
					// Instagram
					array(
						'id'       => 'mh-header-instagram',
						'default'  => esc_html__( '#', 'myhome' ),
						'type'     => 'text',
						'title'    => esc_html__( 'Instagram (URL)', 'myhome' ),
						'required' => array(
							'mh-top-header-style',
							'!=',
							'none',
						),
					),
					// Currency switcher
					array(
						'id'       => 'mh-currency_switcher',
						'type'     => 'switch',
						'required' => array(
							array( 'mh-top-header-style', '!=', 'none' ),
							array( 'mh-top-header-style', '!=', 'big' ),
						),
						'title'    => esc_html__( 'Currency switcher', 'myhome' ),
						'default'  => 0
					),
					// Currency Switcher - default value
					array(
						'id'       => 'mh-currency_switcher-default',
						'type'     => 'select',
						'title'    => esc_html__( 'Default currency', 'myhome' ),
						'options'  => $currencies,
						'default'  => 'any',
						'required' => array( 'mh-currency_switcher', '=', 1 )
					),
					array(
						'id'       => 'mh-top-bar_show-language-switcher',
						'type'     => 'switch',
						'default'  => false,
						'title'    => esc_html__( 'WPML - show language switcher (flags)', 'myhome' ),
						'required' => array(
							array( 'mh-top-header-style', '!=', 'none' ),
						),
					)
				),
			);
			Redux::setSection( $this->opt_name, $section );
		}


		/*
		 * set_blog
		 *
		 * Blog related options
		 */
		public function set_blog() {
			$section = array(
				'title' => esc_html__( 'Blog', 'myhome' ),
				'id'    => 'myhome-blog-opts',
				'icon'  => 'el el-file-edit',
			);
			Redux::setSection( $this->opt_name, $section );

			/*
			 * Blog general section
			 */
			$section = array(
				'title'      => esc_html__( 'Blog general', 'myhome' ),
				'id'         => 'myhome-blog-general-opts',
				'subsection' => true,
				'fields'     => array(
					// sidebar position
					array(
						'id'      => 'mh-blog-sidebar-position',
						'type'    => 'select',
						'title'   => esc_html__( 'Sidebar position', 'myhome' ),
						'options' => array(
							'left'  => esc_html__( 'Left', 'myhome' ),
							'right' => esc_html__( 'Right', 'myhome' ),
						),
						'default' => 'right',
					),
					// archive style
					array(
						'id'      => 'mh-blog-archive-style',
						'type'    => 'select',
						'title'   => esc_html__( 'Post grid style', 'myhome' ),
						'options' => array(
							'vertical'    => esc_html__( '1 column', 'myhome' ),
							'vertical-2x' => esc_html__( '2 columns', 'myhome' ),
							'vertical-3x' => esc_html__( '3 columns', 'myhome' ),
							'vertical-4x' => esc_html__( '4 columns', 'myhome' ),
						),
						'default' => 'vertical',
					),
					array(
						'id'      => 'mh-blog-title',
						'type'    => 'text',
						'title'   => esc_html__( 'Blog title', 'myhome' ),
						'default' => esc_html__( 'Blog', 'myhome' )
					),
					array(
						'id'      => 'mh-blog-subtitle',
						'type'    => 'text',
						'title'   => esc_html__( 'Blog subtitle', 'myhome' ),
						'default' => ''
					),
					// read more text
					array(
						'id'      => 'mh-blog-more',
						'type'    => 'text',
						'title'   => esc_html__( 'Blog: "Read More" button text', 'myhome' ),
						'default' => esc_html__( 'Read more', 'myhome' ),
					),
					array(
						'id'       => 'mh-blog-sidebar_other',
						'type'     => 'switch',
						'title'    => esc_html__( 'Use custom sidebar for blog', 'myhome' ),
						'subtitle' => esc_html__( 'New "MH Blog Sidebar" will appear at /wp-admin/ >> Appearance > Widgets and it will be displayed on your blog.',
							'myhome' ),
						'default'  => false,
					),
				),
			);
			Redux::setSection( $this->opt_name, $section );

			/*
			 * Blog Single post section
			 */
			$section = array(
				'title'      => esc_html__( 'Single post', 'myhome' ),
				'id'         => 'myhome-blog-single-opts',
				'subsection' => true,
				'fields'     => array(
					// Show author
					array(
						'id'      => 'mh-blog-show-author',
						'type'    => 'switch',
						'title'   => esc_html__( 'Display an author', 'myhome' ),
						'default' => true,
					),
					// Show tags
					array(
						'id'      => 'mh-blog-show-tags',
						'type'    => 'switch',
						'title'   => esc_html__( 'Display tags', 'myhome' ),
						'default' => true,
					),
					// Show posts navigation
					array(
						'id'      => 'mh-blog-show-nav',
						'type'    => 'switch',
						'title'   => esc_html__( 'Display navigation', 'myhome' ),
						'default' => true,
					),
					// Show comments
					array(
						'id'      => 'mh-blog-show-comments',
						'type'    => 'switch',
						'title'   => esc_html__( 'Display comments', 'myhome' ),
						'default' => true,
					),
					// Show related posts
					array(
						'id'      => 'mh-blog-show-related',
						'type'    => 'switch',
						'title'   => esc_html__( 'Display related posts', 'myhome' ),
						'default' => true,
					),
					// Related posts number
					array(
						'id'       => 'mh-blog-related-number',
						'type'     => 'text',
						'title'    => esc_html__( 'Total number of related posts to display', 'myhome' ),
						'default'  => '4',
						'required' => array(
							'mh-blog-show-related',
							'=',
							1,
						),
					),
					// Related posts style
					array(
						'id'       => 'mh-blog-related-style',
						'type'     => 'select',
						'title'    => esc_html__( 'Related posts style', 'myhome' ),
						'options'  => array(
							'vertical'    => esc_html__( '1 column', 'myhome' ),
							'vertical-2x' => esc_html__( '2 columns', 'myhome' ),
							'vertical-3x' => esc_html__( '3 columns', 'myhome' ),
							'vertical-4x' => esc_html__( '4 columns', 'myhome' ),
						),
						'default'  => 'vertical-2x',
						'required' => array(
							'mh-blog-show-related',
							'=',
							1,
						),
					),
				),
			);
			Redux::setSection( $this->opt_name, $section );

			/*
			 * Blog Top title section
			 */
			$section = array(
				'title'      => esc_html__( 'Blog top title', 'myhome' ),
				'id'         => 'myhome-top-title',
				'subsection' => true,
				'fields'     => array(
					// show top title
					array(
						'id'      => 'mh-top-title-show',
						'type'    => 'switch',
						'title'   => esc_html__( 'Display top title on blog', 'myhome' ),
						'default' => 1,
					),
					// Top title style
					array(
						'id'       => 'mh-top-title-style',
						'type'     => 'select',
						'title'    => esc_html__( 'Blog top title style', 'myhome' ),
						'options'  => array(
							'default' => esc_html__( 'Gray', 'myhome' ),
							'image'   => esc_html__( 'Image', 'myhome' ),
						),
						'default'  => 'image',
						'required' => array(
							'mh-top-title-show',
							'=',
							'1',
						),
					),
					// Top title background
					array(
						'id'       => 'mh-top-title-background-image-url',
						'type'     => 'media',
						'title'    => esc_html__( 'Upload background image', 'myhome' ),
						'subtitle' => esc_html__( 'Recommended size 1920x500', 'myhome' ),
						'required' => array(
							'mh-top-title-style',
							'=',
							array( 'image' ),
						),
					),
				),
			);
			Redux::setSection( $this->opt_name, $section );
		}

		/*
		 * set_estate_options
		 *
		 * Estate options
		 */
		public function set_estate_options() {
			$section = array(
				'title' => esc_html__( 'Property options', 'myhome' ),
				'id'    => 'myhome-estate-opts',
				'icon'  => 'el el-home',
			);
			Redux::setSection( $this->opt_name, $section );

			$fields = array(
				array(
					'id'      => 'mh-estate_video',
					'type'    => 'switch',
					'title'   => esc_html__( '"Property Video" - module', 'myhome' ),
					'default' => true,
				),
				// Show estate virtual tour
				array(
					'id'      => 'mh-estate_virtual_tour',
					'type'    => 'switch',
					'title'   => esc_html__( '"Virtual Tour" - module', 'myhome' ),
					'default' => false,
				),
				// Show estate plans
				array(
					'id'      => 'mh-estate_plans',
					'type'    => 'switch',
					'title'   => esc_html__( '"Property Plans" - module', 'myhome' ),
					'default' => true,
				),
				array(
					'id'      => 'mh-estate_attachments',
					'type'    => 'switch',
					'title'   => esc_html__( '"Attachments" - module', 'myhome' ),
					'default' => true,
				),
				array(
					'id'      => 'mh-favorite',
					'type'    => 'switch',
					'title'   => esc_html__( '"Favorite Properties" - module', 'myhome' ),
					'default' => true
				),
				array(
					'id'      => 'mh-save_search',
					'type'    => 'switch',
					'title'   => esc_html__( '"Save Search" - module', 'myhome' ),
					'default' => true
				),
				// Compare
				array(
					'id'      => 'mh-compare',
					'title'   => esc_html__( '"Compare Properties" - module', 'myhome' ),
					'type'    => 'switch',
					'default' => true
				),
				// Show date
				array(
					'id'       => 'mh-estate_show_date',
					'type'     => 'switch',
					'title'    => esc_html__( 'Display dates when property was added (e.g. 3 days ago)', 'myhome' ),
					'subtitle' => esc_html__( 'Date on the property cards and single property "Additional Info"',
						'myhome' ),
					'default'  => true
				),
				array(
					'id'      => 'mh-estate_hide-address',
					'type'    => 'switch',
					'title'   => esc_html__( 'Disable Property Google Map Address', 'myhome' ),
					'default' => false
				),
				array(
					'id'       => 'mh-estate_hide-address-text',
					'type'     => 'switch',
					'title'    => esc_html__( 'Display map on the single property page', 'myhome' ),
					'default'  => false,
					'required' => array(
						array( 'mh-estate_hide-address', '=', true ),
					)
				),
				// Property Card Gallery
				array(
					'id'       => 'mh-listing-show_gallery',
					'type'     => 'switch',
					'default'  => true,
					'title'    => esc_html__( '"Property cards gallery" - on the search form', 'myhome' ),
					'subtitle' => esc_html__( 'It changes how property card looks like on search form results. If this option is "OFF" - the featured image is displayed. If it is "ON" it is gallery.',
						'myhome' )
				),
				array(
					'id'       => 'mh-listing-gallery_limit',
					'type'     => 'text',
					'default'  => 5,
					'title'    => esc_html__( '"Property cards gallery" - max number of photos', 'myhome' ),
					'subtitle' => esc_html__( 'Empty field = unlimited', 'myhome' ),
					'required' => array( 'mh-listing-show_gallery', '=', true )
				),
				array(
					'id'      => 'mh-estate_archive-name',
					'type'    => 'text',
					'title'   => esc_html__( 'Archive title', 'myhome' ),
					'default' => esc_html__( 'Properties', 'myhome' )
				),
				array(
					'id'       => 'mh-estate_archive-image',
					'type'     => 'media',
					'title'    => esc_html__( 'Archive title image', 'myhome' ),
					'subtitle' => esc_html__( 'Please use big image e.g. 1920x500', 'myhome' ),
				),
				// slug for estate post type
				array(
					'id'       => 'mh-estate-slug',
					'type'     => 'text',
					'title'    => esc_html__( 'Single property - slug', 'myhome' ),
					'subtitle' => esc_html__( 'Change slug from http://yourdomain/properties/estate-name to http://yourdomain/newslug/estate-name',
						'myhome' ),
					'default'  => 'properties',
				),
				array(
					'id'       => 'mh-estate_link-blank',
					'type'     => 'switch',
					'title'    => esc_html__( 'Clicking property - open in the new tab', 'myhome' ),
					'subtitle' => esc_html__( 'Change behavior of: search forms, maps, property carousels, property lists, related properties cards. Property slider will still open property in the same window.',
						'myhome' ),
					'default'  => false
				),
				array(
					'id'      => 'mh-estate_delete-images',
					'type'    => 'switch',
					'title'   => esc_html__( 'Delete pictures when property is deleted', 'myhome' ),
					'default' => false
				),
			);

			/*
			 * General section
			 */
			$section = array(
				'title'      => esc_html__( 'General', 'myhome' ),
				'id'         => 'myhome-estate-general-opts',
				'subsection' => true,
				'fields'     => $fields
			);
			Redux::setSection( $this->opt_name, $section );

			$fields = array(
				// Show sidebar
				array(
					'id'      => 'mh-estate_sidebar',
					'type'    => 'switch',
					'title'   => esc_html__( 'Display sidebar', 'myhome' ),
					'default' => true,
				),
				// Show sidebar contact form
				array(
					'id'       => 'mh-estate_sidebar_contact_form',
					'type'     => 'switch',
					'title'    => esc_html__( 'Display "Contact Form"', 'myhome' ),
					'default'  => true,
					'required' => array(
						array( 'mh-estate_sidebar', '=', true ),
					),
				),
				// Show sidebar agent
				array(
					'id'       => 'mh-estate_sidebar_user_profile',
					'type'     => 'switch',
					'title'    => esc_html__( 'Display "User Profile"', 'myhome' ),
					'default'  => true,
					'required' => array(
						array( 'mh-estate_sidebar', '=', true ),
					),
				),
				array(
					'id'       => 'mh-estate_sidebar_agency_profile',
					'type'     => 'switch',
					'title'    => esc_html__( 'Display "Agency Profile"', 'myhome' ),
					'default'  => true,
					'required' => array(
						array( 'mh-estate_sidebar', '=', true ),
					),
				),
				array(
					'id'      => 'mh-estate_sidebar-sticky',
					'type'    => 'switch',
					'title'   => esc_html__( 'Use sticky sidebar', 'myhome' ),
					'default' => true,
				),
				array(
					'id'      => 'mh-estate_icons',
					'type'    => 'switch',
					'title'   => esc_html__( 'Use attribute icons on single property page', 'myhome' ),
					'default' => false
				),
				// Show estate info
				array(
					'id'       => 'mh-estate_info',
					'type'     => 'switch',
					'title'    => esc_html__( 'Display additional info', 'myhome' ),
					'subtitle' => esc_html__( 'ID, Views and dates if it is not off (Published Date, Last Update Date)',
						'myhome' ),
					'default'  => true
				),
				// set slider for gallery on single estate page
				array(
					'id'      => 'mh-estate_slider',
					'type'    => 'select',
					'default' => 'single-estate-gallery',
					'title'   => esc_html__( 'Single property - style', 'myhome' ),
					'options' => array(
						'single-estate-gallery'             => esc_html__( 'Gallery', 'myhome' ),
						'single-estate-gallery-auto-height' => esc_html__( 'Gallery - Auto Height', 'myhome' ),
						'single-estate-slider'              => esc_html__( 'Slider', 'myhome' ),
					),
				),
				// Single estate gallery header
				array(
					'id'       => 'mh-single-estate-gallery-top-header',
					'title'    => esc_html__( 'Single Property - big header with featured image background', 'myhome' ),
					'type'     => 'switch',
					'default'  => false,
					'required' => array(
						array( 'mh-estate_slider', '!=', 'single-estate-slider' ),
					),
				),
				array(
					'id'       => 'mh-single-estate_all-prices',
					'title'    => esc_html__( 'Single property - display all prices / multiple currencies', 'myhome' ),
					'subtitle' => esc_html__( 'If you have more than 1 currency it will make all currencies visible in the same time on the single property page',
						'myhome' ),
					'type'     => 'switch',
					'default'  => false
				),
				// Single property map size
				array(
					'id'      => 'mh-estate_map',
					'type'    => 'select',
					'default' => 'big',
					'title'   => esc_html__( 'Single property - map size', 'myhome' ),
					'desc'    => esc_html__( 'If you change map size to small you need to visit MyHome Panel >> Single Property and set its position to make it visible.',
						'myhome' ),
					'options' => array(
						'big'   => esc_html__( 'Full width - at the bottom of the property page', 'myhome' ),
						'small' => esc_html__( 'Small - available in the Property Fields >> Property Page', 'myhome' ),
						'hide'  => esc_html__( 'Hide', 'myhome' )
					)
				),
				array(
					'id'       => 'mh-estate_zoom',
					'type'     => 'text',
					'title'    => esc_html__( 'Single Property - map zoom', 'myhome' ),
					'subtitle' => 'Choose number between 1-30. Empty = auto. 30 = biggest zoom.'
				),
				array(
					'id'       => 'mh-property-enabled_comments',
					'type'     => 'switch',
					'default'  => false,
					'title'    => esc_html__( 'Enable comments support', 'myhome' ),
					'subtitle' => esc_html__( 'You can read how it works here - ',
							'myhome' ) . "<a href='https://myhometheme.zendesk.com/hc/en-us/articles/360001382813' target='_blank'>https://myhometheme.zendesk.com/hc/en-us/articles/360001382813</a>",
				),
				// Show related properties
				array(
					'id'      => 'mh-estate_related-properties',
					'type'    => 'switch',
					'title'   => esc_html__( 'Single property - display related properties', 'myhome' ),
					'desc'    => esc_html__( 'If you turn on related property, please visit MyHome Panel >> Single Property and set its position to make it visible.',
						'myhome' ),
					'default' => true,
				),
				array(
					'id'       => 'mh-related_related-limit',
					'type'     => 'text',
					'title'    => esc_html__( 'Number of related properties to display', 'myhome' ),
					'default'  => 6,
					'required' => array(
						array( 'mh-estate_related-properties', '=', true )
					)
				)
			);

			if ( class_exists( '\MyHomeCore\Attributes\Attribute_Factory' ) ) {
				foreach ( \MyHomeCore\Attributes\Attribute_Factory::get_text() as $attribute ) {
					$fields[] = array(
						'id'       => 'mh-related-by__' . $attribute->get_ID(),
						'type'     => 'switch',
						'title'    => sprintf( esc_html__( 'Related by %s', 'myhome' ), $attribute->get_name() ),
						'default'  => false,
						'required' => array(
							array( 'mh-estate_related-properties', '=', true )
						)
					);
				}
			}

			$section = array(
				'title'      => esc_html__( 'Single property page', 'myhome' ),
				'id'         => 'myhome-estate-single-property-opts',
				'subsection' => true,
				'fields'     => $fields
			);
			Redux::setSection( $this->opt_name, $section );

			if ( class_exists( '\MyHomeCore\Components\Contact_Form\Contact_Form_7' ) ) {
				$opts             = \MyHomeCore\Components\Contact_Form\Contact_Form_7::get_forms_list();
				$active_languages = apply_filters( 'wpml_active_languages', null );

				if ( ! empty( $active_languages ) ) {
					$forms = array();
					foreach ( $active_languages as $lang ) {
						$forms[] = array(
							'id'       => 'mh-contact_form-cf7_form_' . $lang['language_code'],
							'title'    => esc_html__( 'Select form', 'myhome' ) . ' (' . $lang['translated_name'] . ')',
							'type'     => 'select',
							'options'  => $opts,
							'required' => array(
								array( 'mh-contact_form-type', '=', 'cf7' )
							)
						);
					}
				} else {
					$forms = array(
						array(
							'id'       => 'mh-contact_form-cf7_form',
							'title'    => esc_html__( 'Select form', 'myhome' ),
							'type'     => 'select',
							'options'  => $opts,
							'required' => array(
								array( 'mh-contact_form-type', '=', 'cf7' )
							)
						)
					);
				}
			}

			$fields = array(
				array(
					'id'      => 'mh-contact_form-label',
					'title'   => esc_html__( 'Label', 'myhome' ),
					'type'    => 'text',
					'default' => esc_html__( 'Reply to the listing', 'myhome' ),
				),
				array(
					'id'      => 'mh-contact_form-type',
					'title'   => esc_html__( 'Type', 'myhome' ),
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'myhome' ),
						'cf7'     => esc_html__( 'Contact Form 7 (plugin)', 'myhome' )
					),
					'default' => 'default'
				),
				array(
					'id'       => 'mh-contact_form-send_to',
					'title'    => esc_html__( 'Send emails to', 'myhome' ),
					'type'     => 'select',
					'default'  => 'agents',
					'options'  => array(
						'agents'        => esc_html__( 'User assigned to property', 'myhome' ),
						'specify_email' => esc_html__( 'Specify one email', 'myhome' )
					),
					'required' => array(
						array( 'mh-contact_form-type', '=', 'default' )
					)
				),
				array(
					'id'       => 'mh-contact_form-send_to-email',
					'title'    => esc_html__( 'Specify email', 'myhome' ),
					'type'     => 'text',
					'default'  => '',
					'required' => array(
						array( 'mh-contact_form-type', '=', 'default' ),
						array( 'mh-contact_form-send_to', '=', 'specify_email' )
					)
				),
			);

			if ( ! empty( $forms ) ) {
				$fields = array_merge( $fields, $forms );
			}

			$section = array(
				'title'      => esc_html__( 'Single property contact', 'myhome' ),
				'id'         => 'myhome-estate-contact-form-opts',
				'subsection' => true,
				'fields'     => $fields
			);
			Redux::setSection( $this->opt_name, $section );

			$section = array(
				'title'      => esc_html__( 'Show near', 'myhome' ),
				'id'         => 'myhome-near-by-opts',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'      => 'mh-estate-show_near_active',
						'type'    => 'switch',
						'title'   => esc_html__( 'Active at start', 'myhome' ),
						'default' => false,
					),
					// distance units, important for near estates radius
					array(
						'id'      => 'mh-estate-distance_unit',
						'type'    => 'select',
						'title'   => esc_html__( 'Distance unit', 'myhome' ),
						'options' => array(
							'km'    => esc_html__( 'km', 'myhome' ),
							'miles' => esc_html__( 'miles', 'myhome' ),
						),
						'default' => 'miles',
					),
					// range for near estates feature
					array(
						'id'       => 'mh-estate-near_estates_range',
						'type'     => 'text',
						'title'    => esc_html__( '"show near" radius (unit set above)', 'myhome' ),
						'subtitle' => esc_html__( 'Properties around the selected pin with the set radius will be displayed after clicking "Show near" button.',
							'myhome' ),
						'default'  => '20',
					),
				),
			);
			Redux::setSection( $this->opt_name, $section );
		}

		/*
		 * Agents options
		 */
		public function set_agent_options() {
			$section = array(
				'title' => esc_html__( 'Users', 'myhome' ),
				'id'    => 'myhome-agents-opts',
				'icon'  => 'el el-user',
			);
			Redux::setSection( $this->opt_name, $section );

			$all_pages_list = array();
			$all_pages      = get_pages(
				array(
					'sort_order'  => 'asc',
					'sort_column' => 'post_title',
				)
			);
			foreach ( $all_pages as $page ) {
				/* @var $page \WP_Post */
				$all_pages_list[ $page->ID ] = $page->post_title;
			}
			ksort( $all_pages_list );


			$pages_list = array();
			$pages      = get_pages(
				array(
					'sort_order'  => 'asc',
					'sort_column' => 'post_title',
					'meta_key'    => '_wp_page_template',
					'meta_value'  => 'page_agents.php',
				)
			);

			foreach ( $pages as $page ) {
				/* @var $page \WP_Post */
				$pages_list[ $page->ID ] = $page->post_title;
			}
			ksort( $pages_list );

			$all_pages = array();
			foreach ( get_pages() as $page ) {
				$all_pages[ $page->ID ] = $page->post_title;
			}

			$section = array(
				'title'      => esc_html__( 'General', 'myhome' ),
				'id'         => 'myhome-agents-general',
				'subsection' => true,
				'fields'     => array(
					// Enable frontend agent panel
					array(
						'id'      => 'mh-agent-panel',
						'type'    => 'switch',
						'title'   => esc_html__( 'Enable Frontend user panel', 'myhome' ),
						'default' => true,
					),
					array(
						'id'      => 'mh-agent-all',
						'type'    => 'switch',
						'default' => false,
						'title'   => esc_html__( 'Every agent can edit every property', 'myhome' )
					),
					array(
						'id'      => 'mh-buyer-submit-property',
						'type'    => 'switch',
						'default' => false,
						'title'   => esc_html__( 'Buyer can submit property', 'myhome' )
					),
					array(
						'id'      => 'mh-frontend-submit_property_only_registered',
						'title'   => esc_html__( 'Only logged in users can add properties', 'myhome' ),
						'type'    => 'switch',
						'default' => true
					),
					// Moderation
					array(
						'id'       => 'mh-agent-moderation',
						'type'     => 'switch',
						'title'    => esc_html__( 'Moderation of properties added by users', 'myhome' ),
						'subtitle' => esc_html__( 'If it is on, property added by user must be accepted by admin to show',
							'myhome' ),
						'default'  => true,
						'required' => array(
							array( 'mh-agent-panel', '=', true ),
						),
					),
					// Disable backend for agent role
					array(
						'id'      => 'mh-agent-disable_backend',
						'type'    => 'switch',
						'title'   => esc_html__( 'Disable backend for agent/agency/buyer users', 'myhome' ),
						'default' => true,
					),
					array(
						'title'   => esc_html__( 'Show agents on agency page', 'myhome' ),
						'id'      => 'mh-agent_show-agents',
						'type'    => 'switch',
						'default' => true
					),
					// Agent panel page
					array(
						'id'       => 'mh-agent-panel_page',
						'type'     => 'select',
						'title'    => esc_html__( 'Choose panel page', 'myhome' ),
						'default'  => '',
						'options'  => $pages_list,
						'required' => array(
							array( 'mh-agent-panel', '=', true ),
						),
					),
					// Agent panel page link
					array(
						'id'       => 'mh-agent-panel_link',
						'type'     => 'text',
						'title'    => esc_html__( 'or type page panel URL', 'myhome' ),
						'subtitle' => esc_html__( 'Usually: http://yourdomain/panel/', 'myhome' ),
						'default'  => '',
						'required' => array(
							array( 'mh-agent-panel', '=', true ),
						),
					),
					array(
						'id'       => 'mh-panel_register-redirect',
						'title'    => esc_html__( 'Redirect after user email activation', 'myhome' ),
						'type'     => 'select',
						'options'  => array(
							'submit_property' => esc_html__( 'Submit property page', 'myhome' ),
							'panel'           => esc_html__( 'Panel page', 'myhome' ),
							'pricing_table'   => esc_html__( 'Pricing table', 'myhome' )
						),
						'default'  => 'pricing_table',
						'required' => array(
							array( 'mh-agent-panel', '=', true ),
						),
					),
					array(
						'id'       => 'mh-panel_delete_type',
						'title'    => esc_html__( 'Frontend panel delete type', 'myhome' ),
						'subtitle' => esc_html__( 'When user delete property using frontend panel, property can be permanently deleted or trashed',
							'myhome' ),
						'type'     => 'select',
						'default'  => 'trash',
						'options'  => array(
							'delete' => esc_html__( 'Delete', 'myhome' ),
							'trash'  => esc_html__( 'Trash', 'myhome' )
						),
						'required' => array(
							array( 'mh-agent-panel', '=', true ),
						),
					)
				),
			);
			Redux::setSection( $this->opt_name, $section );

			$section = array(
				'title'      => esc_html__( 'User roles', 'myhome' ),
				'id'         => 'myhome-user-roles',
				'subsection' => true,
				'fields'     => array(
					array(
						'title'   => esc_html__( 'Agent role', 'myhome' ),
						'id'      => 'mh-agent_role-agent',
						'type'    => 'switch',
						'default' => true
					),
					array(
						'title'   => esc_html__( 'Agent role display', 'myhome' ),
						'id'      => 'mh-agent_role-agent-display',
						'type'    => 'text',
						'default' => esc_html__( 'Agent', 'myhome' )
					),
					array(
						'title'   => esc_html__( 'Agency role', 'myhome' ),
						'id'      => 'mh-agent_role-agency',
						'type'    => 'switch',
						'default' => true
					),
					array(
						'title'   => esc_html__( 'Agency role display', 'myhome' ),
						'id'      => 'mh-agent_role-agency-display',
						'type'    => 'text',
						'default' => esc_html__( 'Agency', 'myhome' )
					),
					array(
						'title'   => esc_html__( 'Buyer role', 'myhome' ),
						'id'      => 'mh-agent_role-buyer',
						'type'    => 'switch',
						'default' => true
					),
					array(
						'title'   => esc_html__( 'Buyer role display', 'myhome' ),
						'id'      => 'mh-agent_role-buyer-display',
						'type'    => 'text',
						'default' => esc_html__( 'Buyer', 'myhome' )
					),
					array(
						'title'   => esc_html__( 'Agent base url', 'myhome' ),
						'id'      => 'mh-agent_agent-base',
						'type'    => 'text',
						'default' => 'agent'
					),
					array(
						'title'   => esc_html__( 'Agency base url', 'myhome' ),
						'id'      => 'mh-agent_agency-base',
						'type'    => 'text',
						'default' => 'agency'
					),
					array(
						'title'   => esc_html__( 'Other roles base url', 'myhome' ),
						'id'      => 'mh-agent_other-base',
						'type'    => 'text',
						'default' => 'user'
					),
				)
			);
			Redux::setSection( $this->opt_name, $section );

			$section = array(
				'title'      => esc_html__( 'Registration', 'myhome' ),
				'desc'       => esc_html__( 'If you do not get WordPress emails probably your server is blocking sending it. In this case please use SMTP solution - ',
						'myhome' ) . "<a href='https://myhometheme.zendesk.com/hc/en-us/articles/115001343234' target='_blank'>https://myhometheme.zendesk.com/hc/en-us/articles/115001343234</a>",
				'id'         => 'myhome-register-general',
				'subsection' => true,
				'fields'     => array(
					// Disable frontend registration
					array(
						'id'       => 'mh-agent-registration',
						'type'     => 'switch',
						'title'    => esc_html__( 'Frontend registration', 'myhome' ),
						'required' => array(),
						'default'  => false,
					),
					array(
						'id'       => 'mh-agent_show-rules',
						'title'    => esc_html__( 'Add "I agree to the Terms of Service" checkbox', 'myhome' ),
						'type'     => 'switch',
						'default'  => false,
						'required' => array(
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-panel', '=', true ),
						)
					),
					array(
						'id'       => 'mh-agent_rules-link',
						'title'    => esc_html__( 'Terms of Service Page', 'myhome' ),
						'type'     => 'select',
						'options'  => $all_pages_list,
						'required' => array(
							array( 'mh-agent_show-rules', '=', true ),
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-panel', '=', true ),
						)
					),
					array(
						'id'       => 'mh-agent-agency',
						'type'     => 'switch',
						'title'    => esc_html__( 'Agent can join agency', 'myhome' ),
						'default'  => true,
						'required' => array(
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-panel', '=', true ),
						)
					),
//					array(
//						'id'       => 'mh-agent_moderation',
//						'type'     => 'switch',
//						'title'    => esc_html__( 'Moderate users', 'myhome' ),
//						'subtitle' => esc_html__( "Users need to be manually approved", 'myhome' ),
//						'default'  => false,
//						'required' => array(
//							array( 'mh-agent-registration', '=', true ),
//							array( 'mh-agent-panel', '=', true ),
//						)
//					),
					array(
						'id'       => 'mh-agent-account_type',
						'type'     => 'switch',
						'title'    => esc_html__( 'User can select account type', 'myhome' ),
						'default'  => false,
						'required' => array(
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-panel', '=', true ),
						)
					),
					array(
						'id'       => 'mh-agent-initial_role',
						'type'     => 'select',
						'title'    => esc_html__( 'Initial role', 'myhome' ),
						'subtitle' => esc_html__( "Important when user can't select account type", 'myhome' ),
						'options'  => array(
							'agent'  => esc_html__( 'Agent', 'myhome' ),
							'agency' => esc_html__( 'Agency', 'myhome' ),
							'buyer'  => esc_html__( 'Buyer', 'myhome' )
						),
						'default'  => 'agent',
						'required' => array(
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-panel', '=', true ),
						)
					),
					array(
						'id'       => 'mh-agent-panel_active_tab',
						'type'     => 'select',
						'options'  => array(
							'login'    => esc_html__( 'Login', 'myhome' ),
							'register' => esc_html__( 'Register', 'myhome' )
						),
						'default'  => 'login',
						'title'    => esc_html__( 'Which tab should be active at start', 'myhome' ),
						'required' => array(
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-panel', '=', true ),
						)
					),
					array(
						'id'       => 'mh-agent-email_confirmation',
						'type'     => 'switch',
						'title'    => esc_html__( 'Confirmation email', 'myhome' ),
						'required' => array(
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-panel', '=', true ),
						)
					),
					array(
						'id'       => 'mh-agent_email_confirmation-expire',
						'type'     => 'text',
						'title'    => esc_html__( 'Confirmation email - link expire in (hours):', 'myhome' ),
						'default'  => '48',
						'required' => array(
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-email_confirmation', '=', true ),
							array( 'mh-agent-panel', '=', true )
						)
					),
					array(
						'id'       => 'mh-agents-msg_confirm-title',
						'title'    => esc_html__( 'Confirmation email - subject', 'myhome' ),
						'subtitle' => esc_html__( 'Available: {{username}}', 'myhome' ),
						'type'     => 'text',
						'default'  => sprintf( esc_html__( 'Account Details for %s at MyHome', 'myhome' ), '{{username}}' ),
						'required' => array(
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-email_confirmation', '=', true ),
							array( 'mh-agent-panel', '=', true )
						)
					),
					array(
						'id'       => 'mh-agents-msg_confirm-msg',
						'title'    => esc_html__( 'Confirmation email - body', 'myhome' ),
						'subtitle' => esc_html__( 'Available: {{username}}, {{confirmation_link}}', 'myhome' ),
						'type'     => 'editor',
						'default'  => sprintf( esc_html__( 'Thank you for registering at MyHome. Your account is created and must be activated before you can use it. To activate the account click on the following link or copy-paste it in your browser: %s',
							'myhome' ), '{{confirmation_link}}' ),
						'required' => array(
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-email_confirmation', '=', true ),
							array( 'mh-agent-panel', '=', true )
						)
					),
					array(
						'id'       => 'mh-agent-email_welcome-message',
						'type'     => 'switch',
						'title'    => esc_html__( 'Welcome email', 'myhome' ),
						'required' => array(
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-panel', '=', true ),
						)
					),
					array(
						'id'       => 'mh-agents-msg_welcome-title',
						'title'    => esc_html__( 'Welcome email - subject', 'myhome' ),
						'subtitle' => esc_html__( 'Available: {{username}}', 'myhome' ),
						'type'     => 'text',
						'default'  => esc_html__( 'Welcome to MyHome!', 'myhome' ),
						'required' => array(
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-email_welcome-message', '=', true ),
							array( 'mh-agent-panel', '=', true )
						)
					),
					array(
						'id'       => 'mh-agents-msg_welcome-msg',
						'title'    => esc_html__( 'Welcome email - body', 'myhome' ),
						'subtitle' => esc_html__( 'Available: {{username}}', 'myhome' ),
						'type'     => 'editor',
						'default'  => esc_html__( 'You have successfully joined MyHome.', 'myhome' ),
						'required' => array(
							array( 'mh-agent-registration', '=', true ),
							array( 'mh-agent-email_welcome-message', '=', true ),
							array( 'mh-agent-panel', '=', true )
						)
					),

				)
			);
			Redux::setSection( $this->opt_name, $section );

			$section = array(
				'title'      => esc_html__( 'Social login and register', 'myhome' ),
				'id'         => 'myhome-social-login-general',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'      => 'mh-social_login',
						'title'   => esc_html__( 'Enable social login', 'myhome' ),
						'type'    => 'switch',
						'default' => false
					),
					array(
						'id'       => 'mh-social_login-facebook',
						'title'    => esc_html__( 'Facebook', 'myhome' ),
						'subtitle' => '<a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360003770253#facebook">' .
						              esc_html__( 'Click here to read how to configure Facebook Social Login',
							              'myhome' ) . '</a><br><br>' .
						              esc_html__( 'Facebook callback link: ', 'myhome' ) . '<br><br>' .
						              '<strong>' . admin_url( 'admin-post.php?action=myhome_social_login_callback_facebook' ) . '</strong>',
						'type'     => 'switch',
						'default'  => false,
						'required' => array(
							array( 'mh-social_login', '=', true )
						)
					),
					array(
						'id'       => 'mh-social_login-facebook-id',
						'title'    => esc_html__( 'Facebook - App ID', 'myhome' ),
						'type'     => 'text',
						'default'  => '',
						'required' => array(
							array( 'mh-social_login', '=', true ),
							array( 'mh-social_login-facebook', '=', true ),
						)
					),
					array(
						'id'       => 'mh-social_login-facebook-secret',
						'title'    => esc_html__( 'Facebook - App Secret', 'myhome' ),
						'type'     => 'text',
						'default'  => '',
						'required' => array(
							array( 'mh-social_login', '=', true ),
							array( 'mh-social_login-facebook', '=', true )
						)
					),
					array(
						'id'       => 'mh-social_login-twitter',
						'title'    => esc_html__( 'Twitter', 'myhome' ),
						'subtitle' => '<a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360003770253#twitter">' .
						              esc_html__( 'Click here to read how to configure Twitter Social Login',
							              'myhome' ) . '</a><br><br>' .
						              esc_html__( 'Twitter callback link: ', 'myhome' ) . '<br><br>' .
						              '<strong>' . site_url() . '/twitter-callback/' . '</strong>',
						'type'     => 'switch',
						'default'  => false,
						'required' => array(
							array( 'mh-social_login', '=', true )
						)
					),
					array(
						'id'       => 'mh-social_login-twitter-id',
						'title'    => esc_html__( 'Twitter - Consumer Key (API Key)', 'myhome' ),
						'type'     => 'text',
						'default'  => '',
						'required' => array(
							array( 'mh-social_login', '=', true ),
							array( 'mh-social_login-twitter', '=', true ),
						)
					),
					array(
						'id'       => 'mh-social_login-twitter-secret',
						'title'    => esc_html__( 'Twitter - Consumer Secret (API Secret)', 'myhome' ),
						'type'     => 'text',
						'default'  => '',
						'required' => array(
							array( 'mh-social_login', '=', true ),
							array( 'mh-social_login-twitter', '=', true )
						)
					),
					array(
						'id'       => 'mh-social_login-google',
						'title'    => esc_html__( 'Google', 'myhome' ),
						'subtitle' => '<a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360003770253#google">' .
						              esc_html__( 'Click here to read how to configure Google Social Login',
							              'myhome' ) . '</a><br><br>' .
						              esc_html__( 'Google callback link: ', 'myhome' ) . '<br><br>' .
						              '<strong>' . admin_url( 'admin-post.php?action=myhome_social_login_callback_google' ) . '</strong>',
						'type'     => 'switch',
						'default'  => false,
						'required' => array(
							array( 'mh-social_login', '=', true )
						)
					),
					array(
						'id'       => 'mh-social_login-google-id',
						'title'    => esc_html__( 'Google - Client ID', 'myhome' ),
						'type'     => 'text',
						'default'  => '',
						'required' => array(
							array( 'mh-social_login', '=', true ),
							array( 'mh-social_login-google', '=', true ),
						)
					),
					array(
						'id'       => 'mh-social_login-google-secret',
						'title'    => esc_html__( 'Google - Client secret', 'myhome' ),
						'type'     => 'text',
						'default'  => '',
						'required' => array(
							array( 'mh-social_login', '=', true ),
							array( 'mh-social_login-google', '=', true )
						)
					),
					//					array(
					//						'id'       => 'mh-social_login-instagram',
					//						'title'    => esc_html__( 'Instagram', 'myhome' ),
					//						'subtitle' => '<a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360003770253#instagram">' .
					//						              esc_html__( 'Click here to read how to configure Instagram Social Login', 'myhome' ) . '</a><br><br>' .
					//						              esc_html__( 'Instagram callback link: ', 'myhome' ) . '<br><br>' .
					//						              '<strong>' . admin_url( 'admin-post.php?action=myhome_social_login_callback_instagram' ) . '</strong>',
					//						'type'     => 'switch',
					//						'default'  => false,
					//						'required' => array(
					//							array( 'mh-social_login', '=', true )
					//						)
					//					),
					//					array(
					//						'id'       => 'mh-social_login-instagram-id',
					//						'title'    => esc_html__( 'Instagram - Client ID', 'myhome' ),
					//						'type'     => 'text',
					//						'default'  => '',
					//						'required' => array(
					//							array( 'mh-social_login', '=', true ),
					//							array( 'mh-social_login-instagram', '=', true ),
					//						)
					//					),
					//					array(
					//						'id'       => 'mh-social_login-instagram-secret',
					//						'title'    => esc_html__( 'Instagram - Client Secret', 'myhome' ),
					//						'type'     => 'text',
					//						'default'  => '',
					//						'required' => array(
					//							array( 'mh-social_login', '=', true ),
					//							array( 'mh-social_login-instagram', '=', true )
					//						)
					//					),
					array(
						'id'       => 'mh-social_login-linkedin',
						'title'    => esc_html__( 'LinkedIn', 'myhome' ),
						'subtitle' => '<a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360003770253#linkedin">' .
						              esc_html__( 'Click here to read how to configure Linkedin Social Login',
							              'myhome' ) . '</a><br><br>' .
						              esc_html__( 'Linkedin callback link: ', 'myhome' ) . '<br><br>' .
						              '<strong>' . site_url() . '/linkedin-callback/' . '</strong>',
						'type'     => 'switch',
						'default'  => false,
						'required' => array(
							array( 'mh-social_login', '=', true )
						)
					),
					array(
						'id'       => 'mh-social_login-linkedin-id',
						'title'    => esc_html__( 'Linkedin - Client ID', 'myhome' ),
						'type'     => 'text',
						'default'  => '',
						'required' => array(
							array( 'mh-social_login', '=', true ),
							array( 'mh-social_login-linkedin', '=', true ),
						)
					),
					array(
						'id'       => 'mh-social_login-linkedin-secret',
						'title'    => esc_html__( 'Linkedin - Client Secret', 'myhome' ),
						'type'     => 'text',
						'default'  => '',
						'required' => array(
							array( 'mh-social_login', '=', true ),
							array( 'mh-social_login-linkedin', '=', true )
						)
					),
					//					array(
					//						'id'       => 'mh-social_login-yahoo',
					//						'title'    => esc_html__( 'Yahoo', 'myhome' ),
					//						'subtitle' => '<a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360003770253#yahoo">' .
					//						              esc_html__( 'Click here to read how to configure Yahoo Social Login', 'myhome' ) . '</a><br><br>' .
					//						              esc_html__( 'Yahoo callback link: ', 'myhome' ) . '<br><br>' .
					//						              '<strong>' . admin_url( 'admin-post.php?action=myhome_social_login_callback_yahoo' ) . '</strong>',
					//						'type'     => 'switch',
					//						'default'  => false,
					//						'required' => array(
					//							array( 'mh-social_login', '=', true )
					//						)
					//					),
					//					array(
					//						'id'       => 'mh-social_login-yahoo-id',
					//						'title'    => esc_html__( 'Yahoo - Client ID (Consumer Key)', 'myhome' ),
					//						'type'     => 'text',
					//						'default'  => '',
					//						'required' => array(
					//							array( 'mh-social_login', '=', true ),
					//							array( 'mh-social_login-yahoo', '=', true ),
					//						)
					//					),
					//					array(
					//						'id'       => 'mh-social_login-yahoo-secret',
					//						'title'    => esc_html__( 'Yahoo - Client Secret (Consumer Secret)', 'myhome' ),
					//						'type'     => 'text',
					//						'default'  => '',
					//						'required' => array(
					//							array( 'mh-social_login', '=', true ),
					//							array( 'mh-social_login-yahoo', '=', true )
					//						)
					//					)
				)
			);
			Redux::setSection( $this->opt_name, $section );


			$section = array(
				'title'      => esc_html__( 'Google reCAPTCHA', 'myhome' ),
				'desc'       => esc_html__( 'Front-end panel must be enabled to run Google reCAPTCHA (MyHome Theme > Users > General > "Enable Frontend user panel")',
					'myhome' ),
				'id'         => 'myhome-google-recaptcha',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'       => 'mh-agent-captcha',
						'type'     => 'switch',
						'title'    => esc_html__( 'Enable Google reCAPTCHA', 'myhome' ),
						'default'  => false,
						'required' => array(
							array( 'mh-agent-panel', '=', true ),
						)
					),
					array(
						'id'       => 'mh-agent_captcha_site-key',
						'title'    => esc_html__( 'Site key', 'myhome' ),
						'type'     => 'text',
						'required' => array(
							array( 'mh-agent-panel', '=', true ),
							array( 'mh-agent-captcha', '=', true )
						)
					),
					array(
						'id'       => 'mh-agent_captcha_secret-key',
						'title'    => esc_html__( 'Secret key', 'myhome' ),
						'type'     => 'text',
						'required' => array(
							array( 'mh-agent-panel', '=', true ),
							array( 'mh-agent-captcha', '=', true )
						)
					),
				)
			);
			Redux::setSection( $this->opt_name, $section );

			$users_list = array();
			foreach ( get_users() as $user ) {
				$users_list[ $user->ID ] = $user->display_name;
			}

			$section = array(
				'title'      => esc_html__( 'Property Form', 'myhome' ),
				'id'         => 'myhome-frontend-opts',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'      => 'mh-frontend-draft_user',
						'title'   => esc_html__( 'Assign temporary drafts of properties (for guest users)', 'myhome' ),
						'type'    => 'select',
						'options' => $users_list
					),
					array(
						'id'      => 'mh-frontend-number_field_validation',
						'title'   => esc_html__( 'Validate number field', 'myhome' ),
						'type'    => 'switch',
						'default' => true
					),
					array(
						'id'      => 'mh-frontend-images_max-number',
						'title'   => esc_html__( 'Max number of images in the gallery', 'myhome' ),
						'type'    => 'text',
						'default' => 25
					),
					array(
						'id'      => 'mh-frontend-images_max-size',
						'title'   => esc_html__( 'Max size of single image (MB)', 'myhome' ),
						'type'    => 'text',
						'default' => 8
					),
					array(
						'id'      => 'mh-frontend-plans_max-number',
						'title'   => esc_html__( 'Max plans number per property', 'myhome' ),
						'type'    => 'text',
						'default' => 10
					),
					array(
						'id'      => 'mh-frontend-plans_max-size',
						'title'   => esc_html__( 'Max size of single plan (MB)', 'myhome' ),
						'type'    => 'text',
						'default' => 8
					),
					array(
						'id'      => 'mh-frontend-attachments_max-number',
						'title'   => esc_html__( 'Max attachments number per property', 'myhome' ),
						'type'    => 'text',
						'default' => 10
					),
					array(
						'id'      => 'mh-frontend-attachments_max-size',
						'title'   => esc_html__( 'Max size of single attachment (MB)', 'myhome' ),
						'type'    => 'text',
						'default' => 8
					),
					array(
						'id'       => 'mh-frontend-properties_expire',
						'title'    => esc_html__( 'Properties expire after X days', 'myhome' ),
						'type'     => 'text',
						'default'  => '0',
						'subtitle' => esc_html__( '0 or empty = never', 'myhome' )
					),
					array(
						'id'      => 'mh-frontend-use-static-values',
						'title'   => esc_html__( 'Use Text Fields "Static Values" options also on the front-end panel (submit property / edit property)', 'myhome' ),
						'type'    => 'switch',
						'default' => 0,
					)
				)
			);
			Redux::setSection( $this->opt_name, $section );

			$section = array(
				'title'      => esc_html__( 'Payments (WooCommerce)', 'myhome' ),
				'id'         => 'myhome-payments-opts',
				'subsection' => true,
				'fields'     => array(
					// Payment module
					array(
						'id'       => 'mh-payment',
						'title'    => esc_html__( 'Payment module', 'myhome' ),
						'type'     => 'switch',
						'default'  => false,
						'required' => array(
							array( 'mh-agent-panel', '=', true ),
						),
					),
					array(
						'id'       => 'mh-payment_pricing-page',
						'title'    => esc_html__( 'Pricing table page', 'myhome' ),
						'type'     => 'select',
						'required' => array(
							array( 'mh-payment', '=', true ),
						),
						'options'  => $all_pages
					),
					array(
						'id'       => 'mh-initial_properties_number',
						'title'    => esc_html__( 'Number of properties that new registered user can add for free',
							'myhome' ),
						'type'     => 'text',
						'default'  => 0,
						'required' => array(
							array( 'mh-payment', '=', true ),
						),
					),
					array(
						'id'       => 'mh-initial_featured_number',
						'title'    => esc_html__( 'Number of featured properties that new registered user can add for free',
							'myhome' ),
						'type'     => 'text',
						'default'  => 0,
						'required' => array(
							array( 'mh-payment', '=', true ),
						),
					),
				),
			);
			Redux::setSection( $this->opt_name, $section );


			$section = array(
				'title'      => esc_html__( 'Email notifications', 'myhome' ),
				'desc'       => esc_html__( 'If you do not get WordPress emails probably your server is blocking sending it. In this case please use SMTP solution - ',
						'myhome' ) . "<a href='https://myhometheme.zendesk.com/hc/en-us/articles/115001343234' target='_blank'>https://myhometheme.zendesk.com/hc/en-us/articles/115001343234</a>",
				'id'         => 'myhome-agents-notify-general',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'      => 'mh-panel-notify_email',
						'title'   => esc_html__( 'Notifications email', 'myhome' ),
						'type'    => 'select',
						'options' => array(
							'wp_mail'      => esc_html__( 'Default WordPress email', 'myhome' ),
							'custom_email' => esc_html__( 'Custom email', 'myhome' ),
						),
						'default' => 'wp_mail'
					),
					array(
						'id'       => 'mh-panel-notify_custom-email',
						'title'    => esc_html__( 'Custom email for notifications', 'myhome' ),
						'type'     => 'text',
						'default'  => '',
						'required' => array(
							array( 'mh-panel-notify_email', '=', 'custom_email' )
						)
					),
					array(
						'id'      => 'mh-panel-notify_new-property',
						'title'   => esc_html__( 'Get notification when new property has been added', 'myhome' ),
						'type'    => 'switch',
						'default' => false
					),
					array(
						'id'      => 'mh-panel-notify_new-property-moderation',
						'title'   => esc_html__( 'Get notification when property has been added and require moderation',
							'myhome' ),
						'type'    => 'switch',
						'default' => false
					),
					array(
						'id'      => 'mh-panel-notify_updated-property',
						'title'   => esc_html__( 'Get notification when property has been updated', 'myhome' ),
						'type'    => 'switch',
						'default' => false
					),
					array(
						'id'      => 'mh-panel-notify_new-user',
						'title'   => esc_html__( 'Get notification when new user has been registered', 'myhome' ),
						'type'    => 'switch',
						'default' => false
					),
					array(
						'id'      => 'mh-panel-notify_property-approved',
						'title'   => esc_html__( 'Notify user when property has been approved', 'myhome' ),
						'type'    => 'switch',
						'default' => false
					),
					array(
						'id'       => 'mh-panel-notify_property-approved-subject',
						'title'    => esc_html__( 'Property approved email - subject', 'myhome' ),
						'subtitle' => esc_html__( 'Allowed: {{property_name}}, {{property_ID}}', 'myhome' ),
						'type'     => 'text',
						'default'  => sprintf( esc_html__( 'Congratulation %s has been approved', 'myhome' ),
							'{{property_name}} ' ),
						'required' => array(
							array( 'mh-panel-notify_property-approved', '=', true )
						)
					),
					array(
						'id'       => 'mh-panel-notify_property-approved-msg',
						'title'    => esc_html__( 'Property approved email - body', 'myhome' ),
						'subtitle' => esc_html__( 'Allowed: {{username}}, {{property_name}}, {{property__ID}}, {{property_link}}',
							'myhome' ),
						'type'     => 'editor',
						'default'  => sprintf( esc_html__( 'You can find your property here: %s', 'myhome' ),
							'{{property_link}} ' ),
						'required' => array(
							array( 'mh-panel-notify_property-approved', '=', true )
						)
					),
					array(
						'id'      => 'mh-panel-notify_property-declined',
						'title'   => esc_html__( 'Notify agent when his property was declined', 'myhome' ),
						'type'    => 'switch',
						'default' => false,
					),
					array(
						'id'       => 'mh-panel-notify_property-declined-subject',
						'title'    => esc_html__( 'Property declined email - subject', 'myhome' ),
						'subtitle' => esc_html__( 'Allowed: {{property_name}}, {{property_ID}}', 'myhome' ),
						'type'     => 'text',
						'default'  => sprintf( esc_html__( 'Unfortunately %s has not been approved', 'myhome' ),
							'{{property_name}} ' ),
						'required' => array(
							array( 'mh-panel-notify_property-declined', '=', true )
						)
					),
					array(
						'id'       => 'mh-panel-notify_property-declined-msg',
						'title'    => esc_html__( 'Property declined email - body', 'myhome' ),
						'subtitle' => esc_html__( 'Allowed: {{username}}, {{property_name}}, {{property__ID}}',
							'myhome' ),
						'type'     => 'editor',
						'default'  => 'Your property submission does not meet the quality standards',
						'required' => array(
							array( 'mh-panel-notify_property-declined', '=', true )
						)
					),
					array(
						'id'      => 'mh-panel-notify_agent-invited',
						'default' => true,
						'title'   => esc_html__( 'Notify agent when agency send invitation', 'myhome' ),
						'type'    => 'switch',
					),
					array(
						'id'       => 'mh-panel-notify_agent-invited-msg',
						'title'    => esc_html__( 'Notify agent when agency send invitation - message', 'myhome' ),
						'default'  => esc_html__( 'Dear {{username}},',
								'myhome' ) . '<br><br>' . esc_html__( '{{agency_name}} invites you to join their agency account. You can accept/decline invitation in your user panel {{panel_link}}',
								'myhome' ) . '<br><br>' . esc_html__( 'Regards,',
								'myhome' ) . '<br>' . esc_html__( 'Administrator', 'myhome' ),
						'subtitle' => esc_html__( 'Allowed: {{username}}, {{agency_name}}, {{panel_link}}', 'myhome' ),
						'type'     => 'editor',
						'required' => array(
							array( 'mh-panel-notify_agent-invited', '=', true )
						)
					),
				)
			);
			Redux::setSection( $this->opt_name, $section );


			$section = array(
				'title'      => esc_html__( 'User fields', 'myhome' ),
				'desc'       => esc_html__( 'This field are available for Admin, Agent and Agency role. It will be not displayed for Buyer.',
					'myhome' ),
				'id'         => 'myhome-agents',
				'subsection' => true,
				'fields'     => array(
					// phone
					array(
						'id'      => 'mh-agent-phone',
						'type'    => 'switch',
						'title'   => esc_html__( 'Phone', 'myhome' ),
						'default' => true,
					),
					// show email
					array(
						'id'      => 'mh-agent-email_show',
						'type'    => 'switch',
						'title'   => esc_html__( 'Email', 'myhome' ),
						'default' => true,
					),
					// facebook
					array(
						'id'      => 'mh-agent-facebook',
						'type'    => 'switch',
						'title'   => esc_html__( 'Facebook', 'myhome' ),
						'default' => true,
					),
					// twitter
					array(
						'id'      => 'mh-agent-twitter',
						'type'    => 'switch',
						'title'   => esc_html__( 'Twitter', 'myhome' ),
						'default' => true,
					),
					// instagram
					array(
						'id'      => 'mh-agent-instagram',
						'type'    => 'switch',
						'title'   => esc_html__( 'Instagram', 'myhome' ),
						'default' => true,
					),
					// linkedin
					array(
						'id'      => 'mh-agent-linkedin',
						'type'    => 'switch',
						'title'   => esc_html__( 'Linkedin', 'myhome' ),
						'default' => true,
					),
				),
			);
			Redux::setSection( $this->opt_name, $section );

		}

		/*
		 * Listing options
		 */
		public function set_listing_options() {
			$search_forms_list = array( 'default' => esc_html__( 'Default', 'myhome' ) );
			$sort_by           = array();

			if ( class_exists( '\MyHomeCore\Components\Listing\Search_Forms\Search_Form' ) ) {
				$search_forms = \MyHomeCore\Components\Listing\Search_Forms\Search_Form::get_all_search_forms();
				foreach ( $search_forms as $search_form ) {
					$search_forms_list[ $search_form->get_key() ] = $search_form->get_label();
				}

				$sort_by = array(
					\MyHomeCore\Estates\Estate_Factory::ORDER_BY_NEWEST            => esc_html__( 'Newest', 'myhome' ),
					\MyHomeCore\Estates\Estate_Factory::ORDER_BY_PRICE_HIGH_TO_LOW => esc_html__( 'Price (high to low)',
						'myhome' ),
					\MyHomeCore\Estates\Estate_Factory::ORDER_BY_PRICE_LOW_TO_HIGH => esc_html__( 'Price (low to high)',
						'myhome' ),
					\MyHomeCore\Estates\Estate_Factory::ORDER_BY_POPULAR           => esc_html__( 'Popular', 'myhome' ),
					\MyHomeCore\Estates\Estate_Factory::ORDER_BY_TITLE_ASC         => esc_html__( 'Alphabetical order',
						'myhome' ),
					\MyHomeCore\Estates\Estate_Factory::ORDER_BY_TITLE_DESC        => esc_html__( 'Reverse alphabetical order',
						'myhome' ),
				);
			}

			$fields = array(
				array(
					'id'      => 'mh-listing-search_form',
					'type'    => 'select',
					'title'   => esc_html__( 'Search form', 'myhome' ),
					'options' => $search_forms_list,
					'default' => 'default'
				),
				array(
					'id'      => 'mh-listing-type',
					'type'    => 'select',
					'title'   => esc_html__( 'Type', 'myhome' ),
					'default' => 'load_more',
					'options' => array(
						'load_more'  => esc_html__( 'Progressive loading', 'myhome' ),
						'pagination' => esc_html__( 'Pagination', 'myhome' )
					)
				),
				// initial card view
				array(
					'id'       => 'mh-listing-default_view',
					'type'     => 'select',
					'title'    => esc_html__( 'Default view', 'myhome' ),
					'subtitle' => esc_html__( 'If you use "left" or "right" search form sidebar, max 2 columns are available',
						'myhome' ),
					'default'  => 'colThree',
					'options'  => array(
						'colThree' => esc_html__( 'Three columns', 'myhome' ),
						'colTwo'   => esc_html__( 'Two columns', 'myhome' ),
						'row'      => esc_html__( 'Row', 'myhome' ),
					),
				),
				// estates per page
				array(
					'id'      => 'mh-listing-estates_limit',
					'type'    => 'text',
					'title'   => esc_html__( 'Properties limit', 'myhome' ),
					'default' => '6',
				),
				array(
					'id'      => 'mh-listing_show-results-number',
					'type'    => 'switch',
					'title'   => esc_html__( 'Display number of results (e.g. 40 Found)', 'myhome' ),
					'default' => true
				),
				// lazy loading
				array(
					'id'       => 'mh-listing-lazy_loading',
					'type'     => 'switch',
					'title'    => esc_html__( 'Lazy loading', 'myhome' ),
					'default'  => true,
					'required' => array(
						array( 'mh-listing-type', '=', 'load_more' )
					)
				),
				// when show load more button
				array(
					'id'       => 'mh-listing-load_more_button_number',
					'type'     => 'text',
					'title'    => esc_html__( 'Show load more button after N loads', 'myhome' ),
					'default'  => '2',
					'required' => array(
						array( 'mh-listing-lazy_loading', '=', 1 ),
						array( 'mh-listing-type', '=', 'load_more' )
					),
				),
				// load more button label
				array(
					'id'       => 'mh-listing-load_more_button_label',
					'type'     => 'text',
					'title'    => esc_html__( 'Load more button label', 'myhome' ),
					'default'  => esc_html__( 'Load more', 'myhome' ),
					'required' => array(
						array( 'mh-listing-type', '=', 'load_more' )
					)
				),
				// search form position
				array(
					'id'      => 'mh-listing-search_form_position',
					'type'    => 'select',
					'title'   => esc_html__( 'Search form position', 'myhome' ),
					'default' => 'left',
					'options' => array(
						'left'  => esc_html__( 'Left', 'myhome' ),
						'right' => esc_html__( 'Right', 'myhome' ),
						'top'   => esc_html__( 'Top', 'myhome' ),
					),
				),
				// listing label
				array(
					'id'       => 'mh-listing-label',
					'type'     => 'text',
					'title'    => esc_html__( 'Label', 'myhome' ),
					'default'  => '',
					'required' => array(
						array( 'mh-listing-search_form_position', '!=', 'left' ),
						array( 'mh-listing-search_form_position', '!=', 'right' ),
					),
				),
				// advanced number
				array(
					'id'       => 'mh-listing-search_form_advanced_number',
					'type'     => 'text',
					'default'  => 3,
					'title'    => esc_html__( 'Number of filters to show before the "Advanced" button', 'myhome' ),
					'required' => array(
						array( 'mh-listing-search_form_position', '!=', 'left' ),
						array( 'mh-listing-search_form_position', '!=', 'right' ),
					),
				),
				// Show advanced
				array(
					'id'       => 'mh-listing-show_advanced',
					'type'     => 'switch',
					'default'  => true,
					'title'    => esc_html__( 'Display "advanced" button', 'myhome' ),
					'required' => array(
						array( 'mh-listing-search_form_position', '!=', 'left' ),
						array( 'mh-listing-search_form_position', '!=', 'right' ),
					),
				),
				// Show advanced
				array(
					'id'       => 'mh-listing-show_clear',
					'type'     => 'switch',
					'default'  => true,
					'title'    => esc_html__( 'Display "clear" button', 'myhome' ),
					'required' => array(
						array( 'mh-listing-search_form_position', '!=', 'left' ),
						array( 'mh-listing-search_form_position', '!=', 'right' ),
					),
				),
				array(
					'id'      => 'mh-listing-hide_save_search',
					'type'    => 'switch',
					'default' => false,
					'title'   => esc_html__( 'Hide save search (if enabled)', 'myhome' )
				),
				// Show sort by
				array(
					'id'      => 'mh-listing-show_sort_by',
					'type'    => 'switch',
					'default' => true,
					'title'   => esc_html__( 'Display "sort by"', 'myhome' ),
				),
				array(
					'id'       => 'mh-listing-show_sort_by_default',
					'type'     => 'select',
					'default'  => 'newest',
					'options'  => $sort_by,
					'title'    => esc_html__( 'Sort by - default', 'myhome' ),
					'required' => array(
						array( 'mh-listing-show_sort_by', '=', true ),
					)
				),
				array(
					'id'       => 'mh-listing-show_sort_by_newest',
					'type'     => 'switch',
					'default'  => true,
					'title'    => esc_html__( 'Show Sort by - newest', 'myhome' ),
					'required' => array(
						array( 'mh-listing-show_sort_by', '=', true ),
					)
				),
				array(
					'id'       => 'mh-listing-show_sort_by_popular',
					'type'     => 'switch',
					'default'  => true,
					'title'    => esc_html__( 'Show Sort by - popular', 'myhome' ),
					'required' => array(
						array( 'mh-listing-show_sort_by', '=', true ),
					)
				),
				array(
					'id'       => 'mh-listing-show_sort_by_price_high_to_low',
					'type'     => 'switch',
					'default'  => true,
					'title'    => esc_html__( 'Show Sort by - price (high to low)', 'myhome' ),
					'required' => array(
						array( 'mh-listing-show_sort_by', '=', true ),
					)
				),
				array(
					'id'       => 'mh-listing-show_sort_by_price_low_to_high',
					'type'     => 'switch',
					'default'  => true,
					'title'    => esc_html__( 'Show Sort by - price (low to high)', 'myhome' ),
					'required' => array(
						array( 'mh-listing-show_sort_by', '=', true ),
					)
				),
				array(
					'id'       => 'mh-listing-show_sort_by_alphabetically',
					'type'     => 'switch',
					'default'  => false,
					'title'    => esc_html__( 'Show Sort by - alphabetical', 'myhome' ),
					'required' => array(
						array( 'mh-listing-show_sort_by', '=', true ),
					)
				),
				// Show view types
				array(
					'id'      => 'mh-listing-show_view_types',
					'type'    => 'switch',
					'default' => true,
					'title'   => esc_html__( 'Display "view types"', 'myhome' ),
				),
			);

			// setup attribute options
			if ( class_exists( '\MyHomeCore\Attributes\Attribute_Factory' ) ) {
				foreach ( \MyHomeCore\Attributes\Attribute_Factory::get_search() as $attribute ) {
					// set if display this attribute on listing search form
					array_push(
						$fields, array(
							'id'      => 'mh-listing-' . $attribute->get_slug() . '_show',
							'type'    => 'switch',
							'title'   => sprintf( esc_html__( 'Show %s filter', 'myhome' ), $attribute->get_name() ),
							'default' => true,
						)
					);
				}
			}

			$section = array(
				'title'      => esc_html__( 'Category pages', 'myhome' ),
				'id'         => 'myhome-listing-opts',
				'subsection' => true,
				'desc'       => esc_html__(
					"Below options will change Single Agent Page and Single Attribute Page (eg. property type, city).
                 It will not influence on any Visual Composer Element eg. Homepages / Maps.", 'myhome'
				),
				'fields'     => $fields,
			);
			Redux::setSection( $this->opt_name, $section );

			$section = array(
				'title'      => esc_html__( 'Other', 'myhome' ),
				'id'         => 'myhome-other-options',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'       => 'mh-agent-panel-order_by',
						'type'     => 'select',
						'title'    => esc_html__( 'Submit property - dropdown list order of values', 'myhome' ),
						'options'  => array(
							'count' => esc_html__( 'Most popular', 'myhome' ),
							'name'  => esc_html__( 'Alphabetically', 'myhome' )
						),
						'default'  => 'name',
						'required' => array(
							array( 'mh-agent-panel', '=', true ),
						)
					),
					// Show estate attachments
					array(
						'id'       => 'mh-estate_plans-first_active',
						'type'     => 'switch',
						'default'  => true,
						'title'    => esc_html__( 'Single property module - plans - first plan opened by default',
							'myhome' ),
						'required' => array(
							array( 'mh-estate_plans', '=', true ),
						)
					),
					array(
						'id'       => 'mh-estate_short-description',
						'type'     => 'text',
						'title'    => esc_html__( 'Property cards - description ', 'myhome' ),
						'subtitle' => esc_html__( 'Length (letters)', 'myhome' ),
						'default'  => '125'
					),
					array(
						'id'      => 'mh-estate_listing-all-currencies',
						'type'    => 'switch',
						'title'   => esc_html__( 'Show all prices / multiple currencies on listing card', 'myhome' ),
						'default' => false
					)
				),
			);
			Redux::setSection( $this->opt_name, $section );

		}

		/*
		 * Footer options
		 */
		public function set_footer_options() {
			$section = array(
				'title'  => esc_html__( 'Footer', 'myhome' ),
				'id'     => 'myhome-footer-opts',
				'icon'   => 'el el-cog',
				'fields' => array(
					// footer style
					array(
						'id'      => 'mh-footer-style',
						'type'    => 'select',
						'title'   => esc_html__( 'Footer Style', 'myhome' ),
						'options' => array(
							'light' => esc_html__( 'Light background', 'myhome' ),
							'dark'  => esc_html__( 'Dark background', 'myhome' ),
							'image' => esc_html__( 'Image background', 'myhome' ),
						),
						'default' => 'dark',
					),
					// Footer image
					array(
						'id'       => 'mh-footer-background-image-url',
						'type'     => 'media',
						'default'  => '',
						'title'    => esc_html__( 'Upload Background Image', 'myhome' ),
						'subtitle' => esc_html__( 'Recommended size 1920x500', 'myhome' ),
						'required' => array(
							'mh-footer-style',
							'=',
							array( 'image' ),
						),
					),
					// Footer image as parallax
					array(
						'id'       => 'mh-footer-background-image-parallax',
						'type'     => 'switch',
						'title'    => esc_html__( 'Background Image Parallax', 'myhome' ),
						'default'  => 1,
						'required' => array(
							'mh-footer-style',
							'=',
							array( 'image' ),
						),
					),
					// Display widget area
					array(
						'id'      => 'mh-footer-widget-area-show',
						'type'    => 'switch',
						'title'   => esc_html__( 'Display Widget Area', 'myhome' ),
						'default' => 1,
					),
					array(
						'id'       => 'mh-footer-widget-area-columns',
						'type'     => 'select',
						'title'    => esc_html__( 'Columns', 'myhome' ),
						'default'  => 'mh-footer__row__column--1of4',
						'options'  => array(
							'mh-footer__row__column--1of2' => esc_html__( '2', 'myhome' ),
							'mh-footer__row__column--1of3' => esc_html__( '3', 'myhome' ),
							'mh-footer__row__column--1of4' => esc_html__( '4', 'myhome' ),
							'mh-footer__row__column--1of5' => esc_html__( '5', 'myhome' ),
						),
						'required' => array(
							'mh-footer-widget-area-show',
							'=',
							true
						),
					),
					// Display Footer information
					array(
						'id'       => 'mh-footer-widget-area-footer-information',
						'type'     => 'switch',
						'title'    => esc_html__( 'Display Footer Widget', 'myhome' ),
						'default'  => 1,
						'required' => array(
							'mh-footer-widget-area-show',
							'=',
							1,
						),
					),
					// Logo
					array(
						'id'       => 'mh-footer-logo',
						'type'     => 'media',
						'default'  => array( 'url' => get_template_directory_uri() . '/assets/images/logo-footer.png' ),
						'title'    => esc_html__( 'Upload Footer Widget logo', 'myhome' ),
						'required' => array(
							'mh-footer-widget-area-footer-information',
							'=',
							1,
						),
					),
					// Information
					array(
						'id'       => 'mh-footer-text',
						'type'     => 'text',
						'title'    => esc_html__( 'Edit Footer Widget Text', 'myhome' ),
						'default'  => esc_html__( 'After a time we drew near the road, and as we did so we heard the clatter of hoofs and saw through the tree stems three cavalry soldiers riding slowly towards Woking.',
							'myhome' ),
						'required' => array(
							'mh-footer-widget-area-footer-information',
							'=',
							1,
						),
					),
					// Phone
					array(
						'id'       => 'mh-footer-phone',
						'type'     => 'text',
						'title'    => esc_html__( 'Edit Footer Widget phone', 'myhome' ),
						'default'  => esc_html__( '(123) 345-6789', 'myhome' ),
						'required' => array(
							'mh-footer-widget-area-footer-information',
							'=',
							1,
						),
					),
					// Email
					array(
						'id'       => 'mh-footer-email',
						'type'     => 'text',
						'title'    => esc_html__( 'Edit Footer Widget email', 'myhome' ),
						'default'  => esc_html__( 'support@tangibledesing.net', 'myhome' ),
						'required' => array(
							'mh-footer-widget-area-footer-information',
							'=',
							1,
						),
					),
					// Address
					array(
						'id'       => 'mh-footer-address',
						'type'     => 'text',
						'title'    => esc_html__( 'Edit Footer Widget address', 'myhome' ),
						'default'  => esc_html__( '518-520 5th Ave, New York, USA', 'myhome' ),
						'required' => array(
							'mh-footer-widget-area-footer-information',
							'=',
							1,
						),
					),
					// Display copyrights
					array(
						'id'      => 'mh-footer-copyright-area-show',
						'type'    => 'switch',
						'title'   => esc_html__( 'Display Copyright Information', 'myhome' ),
						'default' => 1,
					),
					// Copyrights
					array(
						'id'       => 'mh-footer-copyright-text',
						'type'     => 'text',
						'title'    => esc_html__( 'Edit copyright text', 'myhome' ),
						'default'  => esc_html__( '2018 MyHome by TangibleDesign. All rights reserved.', 'myhome' ),
						'required' => array(
							'mh-footer-copyright-area-show',
							'=',
							1,
						),
					),
				),
			);
			Redux::setSection( $this->opt_name, $section );
		}

		public function set_social_options() {
			$section = array(
				'title'  => esc_html__( 'Social', 'myhome' ),
				'id'     => 'myhome-social-opts',
				'fields' => array(
					array(
						'id'      => 'mh-facebook_optimization',
						'type'    => 'switch',
						'title'   => esc_html__( 'Facebook optimization', 'myhome' ),
						'default' => true
					)
				)
			);

			Redux::setSection( $this->opt_name, $section );
		}


		/*
		 * 404 options
		 */
		public function set_more_options() {
			$section = array(
				'title'  => esc_html__( 'Other', 'myhome' ),
				'id'     => 'myhome-more-opts',
				'icon'   => 'el el-th-list',
				'fields' => array(
					array(
						'id'      => 'mh-welcome',
						'type'    => 'switch',
						'default' => true,
						'title'   => esc_html__( 'Display MyHome welcome panel', 'myhome' ),
					),
					array(
						'id'      => 'mh-editor',
						'type'    => 'switch',
						'default' => false,
						'title'   => esc_html__( 'Enable Gutenberg editor', 'myhome' ),
					),
					// Error 404 Heading
					array(
						'id'      => 'mh-404-heading',
						'type'    => 'text',
						'title'   => esc_html__( 'Error 404 page title', 'myhome' ),
						'default' => esc_html__( '404', 'myhome' ),
					),
					// Error 404 Text
					array(
						'id'      => 'mh-404-text',
						'type'    => 'text',
						'title'   => esc_html__( 'Error 404 page subtitle', 'myhome' ),
						'default' => esc_html__( 'Page not found', 'myhome' ),
					),
					// input active color
					array(
						'id'      => 'mh-input_active_color',
						'type'    => 'select',
						'title'   => esc_html__( 'Active inputs style', 'myhome' ),
						'default' => 'mh-active-input-primary',
						'options' => array(
							'mh-active-input-primary' => esc_html__( 'Primary color', 'myhome' ),
							'mh-active-input-dark'    => esc_html__( 'Gray', 'myhome' ),
						),
					),
					array(
						'id'       => 'mh-testimonials',
						'type'     => 'switch',
						'default'  => true,
						'title'    => esc_html__( 'Short testimonials', 'myhome' ),
						'subtitle' => esc_html__( 'This option "ON" makes all testimonials visible only in the carousels. The testimonials have no unique pages.',
							'myhome' )
					),
				),
			);
			Redux::setSection( $this->opt_name, $section );
		}

		/*
		 * redux_disable_ads
		 *
		 * Disable redux ads
		 */
		public function redux_disable_ads( $redux ) {
			$redux->args['dev_mode'] = false;
		}

		public function wpml_strings() {
			$strings = array(
				// Blog
				(object) array(
					'context' => esc_html__( 'Blog read more text', 'myhome' ),
					'name'    => 'mh-blog-more',
				),
				(object) array(
					'context' => esc_html__( 'Load more (button label)', 'myhome' ),
					'name'    => 'mh-listing-load_more_button_label',
				),
				(object) array(
					'context' => esc_html__( 'Archive properties title ', 'myhome' ),
					'name'    => 'mh-estate_archive-name',
				),
				(object) array(
					'context' => esc_html__( 'Footer copyright text', 'myhome' ),
					'name'    => 'mh-footer-copyright-text',
				)
			);

			global $myhome_redux;
			foreach ( $strings as $string ) {
				do_action( 'wpml_register_single_string', 'MyHome - Settings', $string->context,
					$myhome_redux[ $string->name ] );
			}
		}

		public function migrate_design() {
			$sections = array(
				'mh-color__body-bg',
				'mh-color__button-primary-background',
				'mh-color__button-primary-color',
				'mh-color__button-ghost-primary-color',
				'mh-color__button-ghost-primary-color-hover',
				'mh-color__general-separator',
				'mh-color__owl-carousel-dot',
				'mh-color__owl-carousel-dot-active',
				'mh-color__mh-top-header-bg',
				'mh-color__mh-top-header-border',
				'mh-color__mh-top-header-font',
				'mh-color__mh-top-header-separator',
				'mh-color__top-bar-panel-bg',
				'mh-color__top-bar-panel-font',
				'mh-color__top-bar-panel-bg-hover',
				'mh-color__top-bar-panel-font-hover',
				'mh-color-menu-bg-color',
				'mh-color-menu-border-bottom-color',
				'mh-color-menu-first-level-font',
				'mh-color-menu-submit-property-button',
				'mh-color-menu-submenu-background',
				'mh-color-menu-submenu-color-font',
				'mh-color-menu-submenu-background-hover',
				'mh-color-menu-submenu-font-hover',
				'mh-color-menu-flyout-border',
				'mh-color__mh-page-title-bg',
				'mh-color__mh-page-title-heading-color',
				'mh-color__mh-page-title-other-color',
				'mh-color__breadcrumbs-bg',
				'mh-color__breadcrumbs-link-color',
				'mh-color__breadcrumbs-separator-color',
				'mh-color__breadcrumbs-color',
				'mh-color__breadcrumbs-border',
				'mh-color__mh-footer-bg',
				'mh-color__mh-footer-color',
				'mh-color__mh-footer-col-heading-color',
				'mh-color__mh-footer-widgets-border',
				'mh-color__mh-footer-tag-color',
				'mh-color__mh-footer-social-bg',
				'mh-color__mh-footer-social-border',
				'mh-color__mh-footer-social-font',
				'mh-color__mh-footer-social-bg-hover',
				'mh-color__mh-footer-social-border-hover',
				'mh-color__mh-footer-social-font-hover',
				'mh-color__mh-footer-bottom-bg',
				'mh-color__mh-footer-bottom-color',
				'mh-color__single-property-page-bg',
				'mh-color__single-property-page-section-bg',
				'mh-color__single-property-page-heading-color',
				'mh-color__single-property-page-heading-sep',
				'mh-color__single-property-page-dot',
				'mh-color__single-property-price-bg',
				'mh-color__single-property-price-color',
				'mh-color__single-property-details-bg',
				'mh-color__single-property-details-color',
				'mh-color__single-property-sidebar-heading-color',
				'mh-color__single-property-sidebar-heading-sep',
				'mh-color__single-property-contact-send-bg',
				'mh-color__single-property-contact-send-font',
				'mh-color__slider-price-bg',
				'mh-color__slider-price-font',
				'mh-color__slider-bg',
				'mh-color__slider-heading',
				'mh-color__arrows-bg',
				'mh-color__arrows-color',
				'mh-color__arrows-bg-hover',
				'mh-color__arrows-color-hover',
				'mh-color__img-popup-close',
				'mh-color__img-popup-counter',
				'mh-color__search-horizontal-background',
				'mh-color__search-horizontal-border',
				'mh-color__search-horizontal-label',
				'mh-color__search-vertical-label',
				'mh-color__search-horizontal-button-advanced',
				'mh-color__search-horizontal-button-advanced-font',
				'mh-color__search-horizontal-button-clear',
				'mh-color__search-horizontal-button-clear-hover',
				'mh-color__filters-bg',
				'mh-color__filters-sort-by-label',
				'mh-color__filters-sort',
				'mh-color__filters-sort-active',
				'mh-color__filters-grid-icon',
				'mh-color__filters-grid-icon-active',
				'mh-color__search-results',
				'mh-color__search-filters-color',
				'mh-color__search-end-border',
				'mh-color__search-load-more-background',
				'mh-color__search-load-more-color',
				'mh-color__property-card-background',
				'mh-color__property-card-heading-color',
				'mh-color__property-card-address-color',
				'mh-color__property-card-price-color',
				'mh-color__property-card-info-color',
				'mh-color__property-card-date-color',
				'mh-color__details-button-ghost-primary-color',
				'mh-color__details-button-ghost-primary-color-hover',
				'mh-color__property-card-compare-color',
				'mh-color__property-card-compare-active-background',
				'mh-color__property-card-compare-active-color',
				'mh-color__compare-bg',
				'mh-color__compare-border',
				'mh-color__compare-font-one',
				'mh-color__compare-font-two',
				'mh-color__compare-column-bg',
				'mh-color__compare-column-title',
				'mh-color__compare-column-price-bg',
				'mh-color__compare-column-price-font',
				'mh-color__compare-column-address',
				'mh-color__compare-column-other-text',
				'mh-color__compare-column-separators',
				'mh-color__post-card-background',
				'mh-color__post-card-title',
				'mh-color__post-card-decription',
				'mh-color__post-card-date-bg',
				'mh-color__post-card-date-font',
				'mh-color__post-button-ghost-primary-color',
				'mh-color__post-button-ghost-primary-color-hover',
				'mh-color__post-title',
				'mh-color__post-meta',
				'mh-color__post-meta-hover',
				'mh-color__post-meta-separators',
				'mh-color__post-color',
				'mh-color__tag-general-color',
				'mh-color__nav-border-top-color',
				'mh-color__nav-link-hover-box',
				'mh-color__nav-link',
				'mh-color__nav-link-span',
				'mh-color__blog-author-card-bg',
				'mh-color__blog-author-card-label',
				'mh-color__blog-author-card-name',
				'mh-color__blog-author-card-par',
				'mh-color__blog-comment-author',
				'mh-color__blog-comment-date',
				'mh-color__blog-comment-edit-link',
				'mh-color__blog-comment-text',
				'mh-color__blog-comment-reply',
				'mh-color__blog-comment-reply',
				'mh-color__blog-comment-border',
				'mh-color__blog-comment-log-info',
				'mh-color__mh-sidebar-title',
				'mh-color__mh-sidebar-title-separator',
				'mh-color__mh-sidebar-infobox-text',
				'mh-color__mh-sidebar-link-colors',
				'mh-color__mh-sidebar-menu-border',
				'mh-color__mh-sidebar-social-bg',
				'mh-color__mh-sidebar-social-border',
				'mh-color__mh-sidebar-social-font',
				'mh-color__mh-sidebar-social-bg-hover',
				'mh-color__mh-sidebar-social-border-hover',
				'mh-color__mh-sidebar-social-font-hover',
				'mh-color__user-background',
				'mh-color__user-name',
				'mh-color__user-text',
				'mh-color__user-card-info',
				'mh-color__user-social-icons',
				'mh-color__agent-button-ghost-primary-color',
				'mh-color__agent-button-ghost-primary-color-hover',
				'mh-color__map-property-pin',
				'mh-color__map-panel-background',
				'mh-color__map-panel-border',
				'mh-color__map-panel-font',
				'mh-color__map-panel-button-active',
				'mh-color__map-panel-button-active-font',
				'mh-color__map-property-background',
				'mh-color__map-property-name',
				'mh-color__map-property-price',
				'mh-color__input-select-background',
				'mh-color__input-select-font-initial',
				'mh-color__input-select-border',
				'mh-color__input-select-active-bg',
				'mh-color__input-select-active-border',
				'mh-color__input-select-dropdown-bg',
				'mh-color__input-select-dropdown-border',
				'mh-color__input-select-active-font',
				'mh-color__input-select-dropdown-active-font',
				'mh-color__input-select-dropdown-active-bg',
				'mh-color__input-select-dropdown-hover-font',
				'mh-color__input-select-dropdown-hover-bg',
				'mh-color__checkbox-label',
				'mh-color__checkbox-outline',
				'mh-color__checkbox-outline-bg',
			);

			$options = get_option( 'myhome_redux' );
			$design  = array();
			foreach ( $sections as $option ) {
				$design[ $option ] = $options[ $option ];
			}
			update_option( 'myhome_design', $design );
		}

	}

endif;
