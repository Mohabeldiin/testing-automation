<?php

namespace MyHomeCore;


use Elementor\Plugin;
use MyHomeCore\Admin\Init;
use MyHomeCore\Api\Estates_Api;
use MyHomeCore\Attributes\Attribute_Admin_Ajax;
use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Attributes_Settings;
use MyHomeCore\Cache\Cache;
use MyHomeCore\Common\Images;
use MyHomeCore\Components\Listing\Search_Forms\Search_Forms_Admin_Ajax;
use MyHomeCore\Frontend_Panel\Panel_Controller;
use MyHomeCore\Integrations\ESSB\ESSB_Init;
use MyHomeCore\Estates\Elements\Estate_Elements_Settings;
use MyHomeCore\Estates\Estate_Settings;
use MyHomeCore\Panel\Panel;
use MyHomeCore\Shortcodes\Shortcodes;
use MyHomeCore\Social_Auth\Auth;
use MyHomeCore\Users\User_Settings;

class Core
{

    const VERSION = '3.1.52';

    private static $instance = false;

    /**
     * @var Settings
     */
    public $settings;

    /**
     * @var Attribute_Factory
     */
    public $attributes;

    /**
     * @var User_Settings
     */
    public $user_settings;

    /**
     * @var Attributes_Settings
     */
    public $attributes_settings;

    /**
     * @var Attribute_Admin_Ajax
     */
    public $attribute_admin_ajax;

    /**
     * @var Estate_Settings
     */
    public $estates_settings;

    /**
     * @var Estate_Elements_Settings
     */
    public $estate_elements_settings;

    /**
     * @var Search_Forms_Admin_Ajax
     */
    public $search_form_settings;

    /**
     * @var Shortcodes
     */
    public $shortcodes;

    /**
     * @var Rewrite
     */
    public $rewrite;

    /**
     * @var Estates_Api
     */
    public $api;

    /**
     * @var Panel
     */
    public $panel;

    /**
     * @var string
     */
    public $currency = 'any';

    /**
     * @var string
     */
    public $current_language;

    /**
     * @var \string
     */
    public $default_language;

    /**
     * @var array
     */
    public $languages;

    /**
     * @var Post_Types
     */
    public $post_types;

    /**
     * @var Essb_Init()
     */
    public $essb;

    /**
     * @var Cache
     */
    public $cache;

    /**
     * @var bool
     */
    public $development_mode;

    /**
     * @var Images
     */
    public $images;

    /**
     * @var Auth
     */
    public $social_networks;

    /**
     * @return Core
     */
    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function php_version_error()
    {
        ?>
        <div class="myhome-big-error">
            <?php esc_html_e('Your server PHP version (' . PHP_VERSION . ') is too low to run MyHome (or any other modern theme). PHP 5.6 or higher is required.', 'myhome-core'); ?>
            <a href="https://myhometheme.zendesk.com/hc/en-us/articles/360001522353-"
               target="_blank"><?php esc_html_e('Click here to read how to solve this problem', 'myhome-core'); ?></a>
        </div>
        <?php
    }

    public function myhome_version_mismatch()
    {
        return;
        ?>
        <div class="myhome-big-error">
            <?php esc_html_e('Please update MyHome Core Plugin - it is required to make sure theme works fully correctly. Visit "Plugins" and click "Update Required" link next to it.', 'myhome-core'); ?>
        </div>
        <?php
    }

    public function init()
    {
        if (version_compare(PHP_VERSION, '5.6.0', '<')) {
            add_action('admin_notices', array($this, 'php_version_error'));
        }

        if (wp_get_theme(get_template())->version != Core::VERSION) {
            add_action('admin_notices', array($this, 'myhome_version_mismatch'));
        }


        $this->settings = new Settings();
        $this->cache = new Cache();
        $this->images = new Images();
        $this->development_mode = !empty($this->settings->props['mh-development']) || is_admin();
        $this->languages = apply_filters('wpml_active_languages', $this->languages);
        $this->current_language = apply_filters('wpml_current_language', $this->current_language);
        $this->default_language = apply_filters('wpml_default_language', $this->default_language);

        $this->attributes_settings = new Attributes_Settings();
        $this->post_types = new Post_Types();
        $this->estates_settings = new Estate_Settings();
        $this->rewrite = new Rewrite();
        $this->user_settings = new User_Settings();
        $this->shortcodes = new Shortcodes();
        $this->api = new Estates_Api();
        $this->panel = new Panel_Controller();
        $this->essb = new ESSB_Init();
        $this->social_networks = new Auth();

        if (isset($_COOKIE['myhome_currency']) && !empty($_COOKIE['myhome_currency']) && $_COOKIE['myhome_currency'] !== 'undefined') {
            $this->currency = $_COOKIE['myhome_currency'];
        } elseif (!empty($this->settings->props['mh-currency_switcher-default']) && !empty($this->settings->props['mh-currency_switcher'])) {
            $this->currency = $this->settings->props['mh-currency_switcher-default'];
        }

        if (is_user_logged_in() && is_admin()) {
            new Init();
            $this->attribute_admin_ajax = new Attribute_Admin_Ajax();
            $this->estate_elements_settings = new Estate_Elements_Settings();
            $this->search_form_settings = new Search_Forms_Admin_Ajax();
        }

        add_action(
            'wp_ajax_nopriv_myhome_contact_form_send',
            array('MyHomeCore\Components\Contact_Form\Contact_Form_Single_Property', 'mail')
        );
        add_action(
            'wp_ajax_myhome_contact_form_send',
            array('MyHomeCore\Components\Contact_Form\Contact_Form_Single_Property', 'mail')
        );

        add_action('widgets_init', array($this, 'register_widgets'));
        add_action('init', array($this, 'load_text_domain'));

        // disable redux notices
        add_action('init', array($this, 'disable_redux_notices'));


        add_action('admin_bar_menu', array($this, 'admin_links'), 41);

        add_action('in_admin_header', array($this, 'admin_notices'));

        add_filter('wp_fatal_error_handler_enabled', '__return_false');

        add_action('admin_init', static function () {
            if (wp_doing_ajax() || !class_exists('My_Home_Importer')) {
                return;
            }

            $redirect = get_option('myhome_welcome') === 'yes';
            if ($redirect && !isset($_GET['welcome'])) {
                wp_redirect(admin_url('admin.php?page=myhome_importer&welcome=1'));
                exit;
            }

            if (isset($_GET['welcome'])) {
                update_option('myhome_welcome', 'no');
            }
        });
    }

    public function admin_notices()
    {
        global $wp_filter;

        $forbidden_message_strings = [
            'bsf',
            'Ultimate Addons for WPBakery Page Builder'
        ];


//        if (!isset($wp_filter['admin_notices']) || !is_array($wp_filter['admin_notices'])) {
        if (!isset($wp_filter['admin_notices'])) {
            return;
        }

        foreach ($wp_filter['admin_notices'] as $weight => $callbacks) {
            foreach ($callbacks as $name => $details) {
                ob_start();
                call_user_func($details['function']);
                $message = ob_get_clean();
                foreach ($forbidden_message_strings as $forbidden_string) {
                    if (strpos($message, $forbidden_string) !== FALSE) {
                        $wp_filter['admin_notices']->remove_filter('admin_notices', $details['function'], $weight);
                    }
                }

            }

        }
    }

    public function load_text_domain()
    {
        load_plugin_textdomain('myhome-core', false, MYHOME_CORE_PATH . '/languages');
    }

    public function register_widgets()
    {
        register_widget('MyHomeCore\Widgets\Facebook_Widget');
        register_widget('MyHomeCore\Widgets\Twitter_Widget');
        register_widget('MyHomeCore\Widgets\Social_Icons_Widget');
        register_widget('MyHomeCore\Widgets\Infobox_Widget');
    }

    public function activation()
    {
        User_Settings::create_roles();
        Attributes_Settings::create_table();
        Estate_Settings::create_table();

        if (get_option('myhome_welcome') !== 'no') {
            update_option('myhome_welcome', 'yes');
        }
    }

    public function disable_redux_notices()
    {
        if (class_exists('\ReduxFrameworkPlugin')) {
            remove_filter('plugin_row_meta', array(
                \ReduxFrameworkPlugin::get_instance(),
                'plugin_metalinks'
            ), 2);
            remove_action('admin_notices', array(\ReduxFrameworkPlugin::get_instance(), 'admin_notices'));
        }
    }


    public function admin_links($wp_admin_bar)
    {
        if (class_exists('MyHomeCore\Core')) {
            $theme = wp_get_theme();
            /* @var $wp_admin_bar WP_Admin_Bar */
            $wp_admin_bar->add_node(array(
                'id' => 'myhome',
                'title' => esc_html__('MyHome', 'myhome-core'),
            ));
            $wp_admin_bar->add_node(array(
                'id' => 'myhome-theme',
                'title' => esc_html__('MyHome Theme', 'myhome-core'),
                'parent' => 'myhome',
                'href' => admin_url('admin.php?page=' . str_replace(' ', '', $theme->get('Name')) . '&tab=1')
            ));
            $wp_admin_bar->add_node(array(
                'id' => 'myhome-panel',
                'title' => esc_html__('MyHome Panel', 'myhome-core'),
                'parent' => 'myhome',
                'href' => admin_url('admin.php?page=myhome_attributes')
            ));
            $wp_admin_bar->add_node(array(
                'id' => 'myhome-design',
                'title' => esc_html__('MyHome Design', 'myhome-core'),
                'parent' => 'myhome',
                'href' => admin_url('admin.php?page=MyHomeDesign&tab=1')
            ));

        }
    }

}