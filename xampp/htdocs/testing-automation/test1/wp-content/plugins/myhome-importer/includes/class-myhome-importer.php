<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors', 'on' );
//ini_set( 'memory_limit', '96M' );
/*
 * My_Home_Importer
 *
 * Demo importer for MyHome theme
 */

if ( ! defined('ABSPATH')) {
    die('Access denied.');
}

if ( ! class_exists('My_Home_Importer')) :

    class My_Home_Importer
    {

        private $demos;
        private $plugin_url;
        private $search = array(
            'https://myhometheme.net/default',
            'http://myhometheme.net/default',
            'https://myhometheme.net/main',
            'http://myhometheme.net/main',
            'https://myhometheme.net/agent',
            'http://myhometheme.net/agent',
            'https://myhometheme.net/investments',
            'http://myhometheme.net/investments',
            'https://myhometheme.net/marketplace',
            'http://myhometheme.net/marketplace',
            'https://myhometheme.net/agency',
            'http://myhometheme.net/agency',
            'http://myhometheme.net/agents',
            'https://myhometheme.net/directory',
            'http://myhometheme.net/directory',
            'https://myhometheme.net/invest',
            'http://myhometheme.net/invest',
            'https://myhometheme.net/market',
            'http://myhometheme.net/market',
            'https://myhometheme.net/agents',
            'https://myhometheme.net/test-user',
            'http://myhometheme.net/test-user',
        );

        public function __construct()
        {
            $this->plugin_url = plugins_url('/myhome-importer/');
            $this->set_demos();
            add_action('admin_menu', array($this, 'init'));
            if (isset($_GET['page']) && $_GET['page'] == 'myhome_importer') {
                add_action('admin_enqueue_scripts', array($this, 'load_scripts'));
            }

            add_action('admin_enqueue_scripts', array($this, 'load_dashboard_scripts'));

            // set admin_post callbacks
            add_action('admin_post_myhome_importer_init', array($this, 'prepare'));
            add_action('admin_post_myhome_importer_add_posts', array($this, 'add_posts'));
            add_action('admin_post_myhome_importer_add_comments', array($this, 'add_comments'));
            add_action('admin_post_myhome_importer_add_options', array($this, 'add_options'));
            add_action('admin_post_myhome_importer_add_locations', array($this, 'add_locations'));
            add_action('admin_post_myhome_importer_add_users', array($this, 'add_users'));
            add_action('admin_post_myhome_importer_add_media', array($this, 'add_media'));
            add_action('admin_post_myhome_importer_add_terms', array($this, 'add_terms'));
            add_action('admin_post_myhome_importer_add_term_taxonomy', array($this, 'add_term_taxonomy'));
            add_action('admin_post_myhome_importer_add_term_relationships', array($this, 'add_term_relationships'));
            add_action('admin_post_myhome_importer_add_term_meta', array($this, 'add_term_meta'));
            add_action('admin_post_myhome_importer_add_attributes', array($this, 'add_attributes'));
            add_action('admin_post_myhome_importer_add_redux', array($this, 'add_redux'));
            add_action('admin_post_myhome_importer_add_sliders', array($this, 'add_sliders'));
            add_action('admin_post_myhome_importer_clear_cache', array($this, 'clear_cache'));

            // load textdomain
            add_action('init', array($this, 'load_textdomain'));
        }

        /*
         * load_textdomain
         *
         * Load textdomain for translations
         */
        public function load_textdomain()
        {
            load_plugin_textdomain('myhome-importer', false, dirname(plugin_basename(__FILE__)).'/languages');
        }

        /*
         * set_demos
         *
         * Here we setup all data related to every demo
         */
        private function set_demos()
        {
            $demo_path   = $this->plugin_url.'demos/';
            $images_path = $this->plugin_url.'assets/images/';
            $this->demos = array(
                'default'   => (object)array(
                    'key'      => 'default',
                    'name'     => esc_html__('Default', 'myhome-importer'),
                    'image'    => $images_path.'1.jpg',
                    'meta'     => $demo_path.'default/meta.json',
                    'features' => array(),
                    'url'      => 'https://myhometheme.net/default/',
                    'cdn'      => 'default'
                ),
                'market'    => (object)array(
                    'key'      => 'market',
                    'name'     => esc_html__('Marketplace', 'myhome-importer'),
                    'image'    => $images_path.'5.jpg',
                    'meta'     => $demo_path.'market/meta.json',
                    'features' => array(),
                    'url'      => 'https://myhometheme.net/market/',
                    'cdn'      => 'market'
                ),
                'agents'    => (object)array(
                    'key'      => 'agents',
                    'name'     => esc_html__('Agent', 'myhome-importer'),
                    'image'    => $images_path.'6.jpg',
                    'meta'     => $demo_path.'agents/meta.json',
                    'features' => array(),
                    'url'      => 'https://myhometheme.net/agents/',
                    'cdn'      => 'agents'
                ),
                'agency'    => (object)array(
                    'key'      => 'agency',
                    'name'     => esc_html__('Agency', 'myhome-importer'),
                    'image'    => $images_path.'2.jpg',
                    'meta'     => $demo_path.'agency/meta.json',
                    'features' => array(),
                    'url'      => 'https://myhometheme.net/agency/',
                    'cdn'      => 'agency'
                ),
                'invest'    => (object)array(
                    'key'      => 'invest',
                    'name'     => esc_html__('Investments', 'myhome-importer'),
                    'image'    => $images_path.'7.jpg',
                    'meta'     => $demo_path.'invest/meta.json',
                    'features' => array(),
                    'url'      => 'https://myhometheme.net/invest/',
                    'cdn'      => 'invest'
                ),
                'directory' => (object)array(
                    'key'      => 'directory',
                    'name'     => esc_html__('Directory', 'myhome-importer'),
                    'image'    => $images_path.'4.jpg',
                    'meta'     => $demo_path.'directory/meta.json',
                    'features' => array(),
                    'url'      => 'https://myhometheme.net/directory/',
                    'cdn'      => 'directory'
                ),
            );
        }

        /*
         * init
         *
         * Create admin sub page and attach it to tools menu
         */
        public function init()
        {
            add_menu_page(
                esc_html__('MyHome Demo Importer', 'myhome-importer'),
                esc_html__('MyHome Demo Importer', 'myhome-importer'),
                'administrator',
                'myhome_importer',
                array($this, 'page'),
                '',
                4
            );
        }

        /*
         * page
         *
         * Admin page html output
         */
        public function page()
        {
            if (
                ! is_plugin_active('myhome-core/myhome-core.php')
                || ! is_plugin_active('js_composer/js_composer.php')
                || ! is_plugin_active('revslider/revslider.php')
                || ! is_plugin_active('advanced-custom-fields-pro/acf.php')
                || ! is_plugin_active('mega_main_menu/mega_main_menu.php')
                || ! is_plugin_active('redux-framework/redux-framework.php')
            ) {
                return;
            }

            $memory_limit       = ini_get('memory_limit');
            $maX_execution_time = ini_get('max_execution_time');
            $max_input_vars     = intval(ini_get('max_input_vars'));
            $php_gd             = extension_loaded('gd') && function_exists('gd_info');
            $mb_string          = extension_loaded('mbstring');

            ob_start();
            ?>
            <h1 style="margin-bottom: 24px;">MyHome Demo Importer</h1>
            <div class="mh-server">
                <h2 class="mh-server__heading">Server requirements</h2>
                <div class="mh-server__intro">
                    If you have any problem with demo import please contact our support team via
                    <a href="mailto:support@tangibledesign.net">support@tangibledesign.net</a>. We will do our best to
                    help you. If the problem is related to the below server settings please contact your hosting
                    provider first.
                </div>

                <?php
                $min_php_version       = 5.6;
                $min_input_vars        = 1000;
                $min_memory_start      = 128;
                $min_memory_end        = 196;
                $min_execution_time    = 60;
                $mb_string_requirement = 1;
                $php_gd_requirement    = 1;
                ?>

                <div class="mh-server__row
                    <?php if (phpversion() >= $min_php_version) : ?>mh-server__row--good<?php endif ?>
                    <?php if (phpversion() < $min_php_version) : ?>mh-server__row--bad<?php endif ?>
                ">
                    <div class="mh-server__title">
                        PHP version
                    </div>
                    <div class="mh-server__icon">
                        <?php if (phpversion() >= $min_php_version) : ?>
                            <span class="dashicons dashicons-yes"></span><?php endif ?>
                        <?php if (phpversion() < $min_php_version) : ?>
                            <span class="dashicons dashicons-warning"></span><?php endif ?>
                    </div>
                    <div class="mh-server__your-version">
                        <?php echo esc_html(phpversion()); ?>
                    </div>
                    <?php if (phpversion() < $min_php_version) : ?>
                        <div class="mh-server__problem">
                            <div class="mh-server__problem__text">
                                PHP version installed on your server is outdated. PHP version 5.6 or higher is required
                                to make sure MyHome and all required plugins work correctly.
                            </div>
                            <div class="mh-server__problem__solve">
                                <a href="https://myhometheme.zendesk.com/hc/en-us/articles/360001522353"
                                   target="_blank">
                                    <span class="dashicons dashicons-arrow-right-alt2"></span>Click here to read how to
                                    solve it
                                </a>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
                <div class="mh-server__row
                    <?php if ($max_input_vars >= $min_input_vars) : ?>mh-server__row--good<?php endif ?>
                    <?php if ($max_input_vars < $min_input_vars) : ?>mh-server__row--bad<?php endif ?>
                ">
                    <div class="mh-server__title">
                        Max Input Vars
                    </div>
                    <div class="mh-server__icon">
                        <?php if ($max_input_vars >= $min_input_vars) : ?>
                            <span class="dashicons dashicons-yes"></span><?php endif ?>
                        <?php if ($max_input_vars < $min_input_vars) : ?>
                            <span class="dashicons dashicons-warning"></span><?php endif ?>
                    </div>
                    <div class="mh-server__your-version">
                        <?php echo esc_html($max_input_vars); ?>
                    </div>
                    <?php if ($max_input_vars < $min_input_vars) : ?>
                        <div class="mh-server__problem">
                            <div class="mh-server__problem__text">
                                Please increase it to 1000 or more. Lower number can create some unexpected problems,
                                because it is responsible for how many GET/POST/COOKIE input variables may be accepted.
                            </div>
                        </div>
                    <?php endif ?>
                </div>
                <div class="mh-server__row
                    <?php if ($memory_limit >= $min_memory_end || $memory_limit == 0) : ?>mh-server__row--good<?php endif ?>
                    <?php if ($memory_limit >= $min_memory_start && $memory_limit < $min_memory_end) : ?>mh-server__row--medium<?php endif ?>
                    <?php if ($memory_limit < $min_memory_start && $memory_limit != 0) : ?>mh-server__row--bad<?php endif ?>
                ">
                    <div class="mh-server__title">
                        Memory limit
                    </div>
                    <div class="mh-server__icon">
                        <?php if ($memory_limit >= $min_memory_end) : ?>
                            <span class="dashicons dashicons-yes"></span><?php endif ?>
                        <?php if ($memory_limit >= $min_memory_start && $memory_limit < $min_memory_end) : ?>
                            <span class="dashicons dashicons-info"></span><?php endif ?>
                        <?php if ($memory_limit < $min_memory_start) : ?>
                            <span class="dashicons dashicons-warning"></span><?php endif ?>
                    </div>
                    <div class="mh-server__your-version">
                        <?php echo esc_html($memory_limit); ?>
                    </div>
                    <?php if ($memory_limit < $min_memory_end && $memory_limit != 0) : ?>
                        <div class="mh-server__problem">
                            <div class="mh-server__problem__text">
                                Your server has very low memory_limit. Please increase it to 196M or more to make sure
                                MyHome and all required plugins work correctly
                            </div>
                            <div class="mh-server__problem__solve">
                                <a href="https://myhometheme.zendesk.com/hc/en-us/articles/360001522393"
                                   target="_blank">
                                    <span class="dashicons dashicons-arrow-right-alt2"></span>Click here to read how to
                                    solve it
                                </a>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
                <div class="mh-server__row
                        <?php if ($maX_execution_time >= $min_execution_time || ! $maX_execution_time) : ?>mh-server__row--good<?php endif ?>
                        <?php if ($maX_execution_time < $min_execution_time && $maX_execution_time != 0) : ?>mh-server__row--medium<?php endif ?>
">
                    <div class="mh-server__title">
                        Max execution time
                    </div>
                    <div class="mh-server__icon">
                        <?php if ($maX_execution_time >= $min_execution_time || ! $maX_execution_time) : ?>
                            <span class="dashicons dashicons-yes"></span><?php endif ?>
                        <?php if ($maX_execution_time < $min_execution_time && $maX_execution_time != 0) : ?>
                            <span class="dashicons dashicons-info"></span><?php endif ?>
                    </div>
                    <div class="mh-server__your-version">
                        <?php if ($maX_execution_time) : ?>
                            <?php echo esc_html($maX_execution_time); ?> (seconds)
                        <?php else : ?>
                            unlimited
                        <?php endif; ?>
                    </div>

                    <?php if ($maX_execution_time < $min_execution_time && $maX_execution_time != 0) : ?>
                        <div class="mh-server__problem">
                            <div class="mh-server__problem__text">
                                Your server has very low max_execution_time. We recommend to increase it to 60 (seconds)
                                or more to make sure importer will have enough time to load all sample photos and videos
                            </div>
                        </div>
                    <?php endif ?>
                </div>
                <div class="mh-server__row
                    <?php if ($php_gd == $php_gd_requirement) : ?>mh-server__row--good<?php endif ?>
                    <?php if ($php_gd != $php_gd_requirement) : ?>mh-server__row--bad<?php endif ?>
                ">
                    <div class="mh-server__title">
                        PHP GD
                    </div>
                    <div class="mh-server__icon">
                        <?php if ($php_gd == $php_gd_requirement) : ?>
                            <span class="dashicons dashicons-yes"></span><?php endif ?>
                        <?php if ($php_gd != $php_gd_requirement) : ?>
                            <span class="dashicons dashicons-warning"></span><?php endif ?>
                    </div>
                    <div class="mh-server__your-version">
                        <?php echo $php_gd ? 'installed' : 'not installed'; ?>
                    </div>
                    <?php if ($php_gd != $php_gd_requirement) : ?>
                        <div class="mh-server__problem">
                            <div class="mh-server__problem__text">
                                PHP GD is not installed on your server. It is required to generate image thumbnails by
                                any WordPress Theme. Please contact your server provider how to turn it "on"
                            </div>
                        </div>
                    <?php endif ?>
                </div>
                <div class="mh-server__row
                    <?php if ($mb_string == $mb_string_requirement) : ?>mh-server__row--good<?php endif ?>
                    <?php if ($mb_string != $mb_string_requirement) : ?>mh-server__row--bad<?php endif ?>
                ">
                    <div class="mh-server__title">
                        PHP mbstring
                    </div>
                    <div class="mh-server__icon">
                        <?php if ($mb_string == $mb_string_requirement) : ?>
                            <span class="dashicons dashicons-yes"></span><?php endif ?>
                        <?php if ($mb_string != $mb_string_requirement) : ?>
                            <span class="dashicons dashicons-warning"></span><?php endif ?>
                    </div>
                    <div class="mh-server__your-version">
                        <?php echo $mb_string ? 'installed' : 'not installed'; ?>
                    </div>
                    <?php if ($mb_string != $mb_string_requirement) : ?>
                        <div class="mh-server__problem">
                            <div class="mh-server__problem__text">
                                PHP mbstring is not installed on your server. It is required to run almost any modern
                                WordPress theme or plugin. Please contact your server provider how to turn it "on"
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </div>
            <div id="myhome-importer">
                <div class="mh-info-database">
                    <span class="dashicons dashicons-warning"></span>
                    IMPORTANT - Loading Demo will remove all of your database content. Before you start importing demo
                    make sure you activated all required plugins.
                </div>
                <demo-importer url="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                               :demos='<?php echo esc_attr(json_encode($this->demos)); ?>'
                               :translations='<?php echo esc_attr($this->get_strings()); ?>'></demo-importer>
            </div>
            <?php
            echo ob_get_clean();
        }

        /*
         * load_scripts
         *
         * Load required css and js files
         */
        public function load_scripts()
        {
            // load js
            wp_enqueue_script('myhome-importer', plugins_url('/myhome-importer/assets/js/build.js'), array(), true,
                true);
            // load styles
            wp_enqueue_style('myhome-importer', plugins_url('/myhome-importer/assets/css/style.css'));
        }

        public function load_dashboard_scripts()
        {
            wp_enqueue_style('myhome-dashboard', plugins_url('/myhome-importer/assets/css/dashboard.css'));
        }

        /*
         * Callbacks for admin-post.php actions
         */

        // add post
        public function add_posts()
        {
            if (isset($_POST['demoKey']) && isset($_POST['start']) && isset($_POST['limit'])) {
                $start    = intval($_POST['start']);
                $end      = intval($_POST['limit']);
                $demo_key = sanitize_text_field($_POST['demoKey']);
                $file     = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/posts.json';
                $posts    = json_decode(file_get_contents($file), true);

                global $wpdb;
                for ($i = $start; $i < $end; $i++) {
                    $post      = $posts[$i]['post'];
                    $post_meta = $posts[$i]['post_meta'];

                    $wpdb->insert($wpdb->posts, $post);

                    if (is_array($post_meta)) {
                        foreach ($post_meta as $key => $meta) {
                            if ($meta['meta_key'] == 'estate_video_plan' || $meta['meta_key'] == 'estate_video') {
                                $meta['meta_value'] = 'https://youtu.be/MGZEKMZw9ZY';
                            }
                            $wpdb->insert(
                                $wpdb->postmeta,
                                $meta
                            );
                        }
                    }
                }
            }
        }

        // add terms
        public function add_terms()
        {
            if (isset($_POST['demoKey']) && isset($_POST['start']) && isset($_POST['limit'])) {
                $start    = intval($_POST['start']);
                $end      = intval($_POST['limit']);
                $demo_key = sanitize_text_field($_POST['demoKey']);
                $file     = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/terms.json';
                $terms    = json_decode(file_get_contents($file), true);

                global $wpdb;
                for ($i = $start; $i < $end; $i++) {
                    $wpdb->insert($wpdb->terms, $terms[$i]);
                }
            }
        }

        // add term_taxonomies
        public function add_term_taxonomy()
        {
            if (isset($_POST['demoKey']) && isset($_POST['start']) && isset($_POST['limit'])) {
                $start         = intval($_POST['start']);
                $end           = intval($_POST['limit']);
                $demo_key      = sanitize_text_field($_POST['demoKey']);
                $file          = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/term_taxonomy.json';
                $term_taxonomy = json_decode(file_get_contents($file), true);

                global $wpdb;
                for ($i = $start; $i < $end; $i++) {
                    $wpdb->insert($wpdb->term_taxonomy, $term_taxonomy[$i]);
                }
            }
        }

        // add term_relationships
        public function add_term_relationships()
        {
            if (isset($_POST['demoKey']) && isset($_POST['start']) && isset($_POST['limit'])) {
                $start             = intval($_POST['start']);
                $end               = intval($_POST['limit']);
                $demo_key          = sanitize_text_field($_POST['demoKey']);
                $file              = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/term_relationships.json';
                $term_relationship = json_decode(file_get_contents($file), true);

                global $wpdb;
                for ($i = $start; $i < $end; $i++) {
                    $wpdb->insert($wpdb->term_relationships, $term_relationship[$i]);
                }
            }
        }

        // add term_meta
        public function add_term_meta()
        {
            if (isset($_POST['demoKey']) && isset($_POST['start']) && isset($_POST['limit'])) {
                $start     = intval($_POST['start']);
                $end       = intval($_POST['limit']);
                $demo_key  = sanitize_text_field($_POST['demoKey']);
                $file      = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/term_meta.json';
                $term_meta = json_decode(file_get_contents($file), true);

                global $wpdb;
                for ($i = $start; $i < $end; $i++) {
                    $wpdb->insert($wpdb->termmeta, $term_meta[$i]);
                }
            }
        }

        // add locations
        public function add_locations()
        {
            if (isset($_POST['demoKey']) && isset($_POST['start']) && isset($_POST['limit'])) {
                $start     = intval($_POST['start']);
                $end       = intval($_POST['limit']);
                $demo_key  = sanitize_text_field($_POST['demoKey']);
                $file      = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/locations.json';
                $locations = json_decode(file_get_contents($file), true);

                global $wpdb;
                $table = $wpdb->prefix.'myhome_locations';

                for ($i = $start; $i < $end; $i++) {
                    $wpdb->insert($table, $locations[$i]);
                }
            }
        }

        // add comment
        public function add_comments()
        {
            if (isset($_POST['demoKey']) && isset($_POST['start']) && isset($_POST['limit'])) {
                $start    = intval($_POST['start']);
                $end      = intval($_POST['limit']);
                $demo_key = sanitize_text_field($_POST['demoKey']);
                $file     = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/comments.json';
                $comments = json_decode(file_get_contents($file), true);

                global $wpdb;
                for ($i = $start; $i < $end; $i++) {
                    $comment      = $comments[$i]['comment'];
                    $comment_meta = $comments[$i]['comment_meta'];
                    $wpdb->insert($wpdb->comments, $comment);
                    if (is_array($comment_meta)) {
                        foreach ($comment_meta as $meta) {
                            $wpdb->insert($wpdb->commentmeta, $meta);
                        }
                    }
                }
            }
        }

        // add options
        public function add_options()
        {
            if (isset($_POST['demoKey']) && isset($_POST['start']) && isset($_POST['limit'])) {
                $start    = intval($_POST['start']);
                $end      = intval($_POST['limit']);
                $demo_key = sanitize_text_field($_POST['demoKey']);
                $file     = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/options.json';
                $options  = json_decode(file_get_contents($file), true);

                global $wpdb;
                for ($i = $start; $i < $end; $i++) {
                    $option = $options[$i];
                    $wpdb->query("
                    DELETE FROM {$wpdb->options}
                    WHERE option_name = '".$option['option_name']."'
                ");

                    if ($option['option_name'] == 'mega_main_menu_options') {
                        $value                  = unserialize($option['option_value']);
                        $value['last_modified'] = time();
                        $option['option_value'] = serialize($value);
                    }

                    if ($option['option_name'] == 'widget_myhome-image-widget') {
                        $values = unserialize($option['option_value']);
                        if (is_array($values)) {
                            foreach ($values as $key => $value) {
                                if ( ! empty($values[$key]['image_url'])) {
                                    $values[$key]['image_url'] = str_replace($this->search, site_url(),
                                        $values[$key]['image_url']);
                                }
                            }
                        }
                        $option['option_value'] = serialize($values);
                    }

                    $wpdb->insert(
                        $wpdb->options,
                        array(
                            'option_name'  => $option['option_name'],
                            'option_value' => $option['option_value'],
                            'autoload'     => $option['autoload']
                        )
                    );
                }
            }
        }

        // add user
        public function add_users()
        {
            if (isset($_POST['demoKey']) && isset($_POST['start']) && isset($_POST['limit'])) {
                $start        = intval($_POST['start']);
                $end          = intval($_POST['limit']);
                $demo_key     = sanitize_text_field($_POST['demoKey']);
                $file         = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/users.json';
                $users        = json_decode(file_get_contents($file), true);
                $current_user = wp_get_current_user();

                global $wpdb;
                for ($i = $start; $i < $end; $i++) {
                    $user = $users[$i]['user'];
                    if ($user['ID']  === 1 || $user['ID']  === '1'
                        || $user['ID'] == get_current_user_id() || $user['user_login'] == 'admin') {
                        continue;
                    }

                    $user['user_pass'] = $current_user->data->user_pass;
                    $user_meta         = $users[$i]['user_meta'];
                    $wpdb->insert($wpdb->users, $user);
                    foreach ($user_meta as $meta) {
                        $test = get_metadata_by_mid('user', $meta['umeta_id']);
                        if ($test != false) {
                            continue;
                        }

                        $wpdb->insert($wpdb->usermeta, $meta);
                        if ($meta['meta_key'] == 'wp_capabilities') {
                            if (strpos($meta['meta_value'], 'agent') !== false) {
                                $role = 'agent';
                            } elseif (strpos($meta['meta_value'], 'agency') !== false) {
                                $role = 'agency';
                            } elseif (strpos($meta['meta_value'], 'editor') !== false) {
                                $role = 'editor';
                            } elseif (strpos($meta['meta_value'], 'buyer') !== false) {
                                $role = 'buyer';
                            }

                            if (isset($role) && ! is_null($role)) {
                                wp_update_user(['ID' => $user['ID'], 'role' => $role]);
                            }
                        }
                    }
                }
            }
        }

        // add attachment
        public function add_media()
        {
            $path       = 'https://myhometheme.net/main/wp-content/uploads/';
            $upload_dir = wp_upload_dir();
            $save_path  = $upload_dir['basedir'].'/';

            if (isset($_POST['demoKey']) && isset($_POST['start']) && isset($_POST['limit'])) {
                $start    = intval($_POST['start']);
                $end      = intval($_POST['limit']);
                $demo_key = sanitize_text_field($_POST['demoKey']);
                $file     = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/media.json';
                $media    = json_decode(file_get_contents($file), true);

                global $wpdb;
                for ($i = $start; $i < $end; $i++) {
	                if ($media[$i]['attachment']['ID'] == '3154' || $media[$i]['attachment']['ID'] == '1794') {
                        continue;
                    }

                    $attachment      = $media[$i]['attachment'];
                    $attachment_meta = $media[$i]['attachment_meta'];
                    $wpdb->insert($wpdb->posts, $attachment);

                    foreach ($attachment_meta as $meta) {
                        if ($meta['meta_key'] == '_wp_attached_file') {
                            $name   = $save_path.$meta['meta_value'];
                            $source = $path.$meta['meta_value'];

                            $dir = dirname($name);
                            if ( ! is_dir($dir)) {
                                mkdir($dir, 0777, true);
                            }

                            $response = wp_remote_get($source, array(
                                'timeout' => 60
                            ));

                            if (is_wp_error($response)) {
                                echo 'fail';
                                echo $response->get_error_message();
                            }

                            $file = $response['body'];
                            file_put_contents($name, $file);

                            $metadata = wp_generate_attachment_metadata($attachment['ID'], $name);
                            if ( ! empty($metadata)) {
                                wp_update_attachment_metadata($attachment['ID'], $metadata);
                            }

                            $wpdb->insert($wpdb->postmeta, $meta);
                        }
                    }
                }
            }
        }

        // add attributes
        public function add_attributes()
        {
            if (isset($_POST['demoKey']) && isset($_POST['start']) && isset($_POST['limit'])) {
                $start      = intval($_POST['start']);
                $end        = intval($_POST['limit']);
                $demo_key   = sanitize_text_field($_POST['demoKey']);
                $file       = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/attributes.json';
                $attributes = json_decode(file_get_contents($file), true);

                global $wpdb;
                $table = $wpdb->prefix.'myhome_attributes';

                for ($i = $start; $i < $end; $i++) {
                    $wpdb->insert($table, $attributes[$i]);
                }
            }
        }

        // add sliders
        public function add_sliders()
        {
            if (isset($_POST['demoKey']) && isset($_POST['start']) && isset($_POST['limit'])) {
                $start         = intval($_POST['start']);
                $end           = intval($_POST['limit']);
                $demo_key      = sanitize_text_field($_POST['demoKey']);
                $file          = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/sliders.json';
                $sliders       = json_decode(file_get_contents($file), true);
                $sliders_array = array();

                foreach ($sliders as $key => $data) {
                    array_push($sliders_array, array(
                        'key'  => $key,
                        'data' => $data
                    ));
                }

                global $wpdb;
                for ($i = $start; $i < $end; $i++) {
                    $key  = $sliders_array[$i]['key'];
                    $data = $sliders_array[$i]['data'];

                    $table = $wpdb->prefix.$key;
                    $wpdb->query("DELETE FROM $table ");
                    foreach ($data as $row) {
                        if ($key == 'revslider_slides') {
                            $params = json_decode($row['params'], true);
                            foreach ($params as $k => $param) {
                                $params[$k] = str_replace($this->search, site_url(), $param);
                            }
                        }
                        $wpdb->insert($table, $row);
                    }
                }
            }
        }

        // add redux options
        public function add_redux()
        {
            if (isset($_POST['demoKey'])) {
                $demo_key = sanitize_text_field($_POST['demoKey']);
                $file     = WP_PLUGIN_DIR.'/myhome-importer/demos/'.$demo_key.'/redux.json';
                $redux    = json_decode(file_get_contents($file), true);
                $images   = array(
                    'mh-logo',
                    'mh-logo-dark',
                    'mh-logo-top-bar',
                    'mh-top-title-background-image-url',
                    'mh-footer-background-image-url',
                    'mh-footer-logo'
                );

                foreach ($images as $image) {
                    if (empty($redux[$image])) {
                        continue;
                    }
                    $redux[$image]['url']       = str_replace($this->search, site_url(), $redux[$image]['url']);
                    $redux[$image]['thumbnail'] = str_replace($this->search, site_url(), $redux[$image]['thumbnail']);
                }

                if ( ! empty($redux['mh-agent-panel_link'])) {
                    $redux['mh-agent-panel_link'] = str_replace($this->search, site_url(),
                        $redux['mh-agent-panel_link']);
                }

                $redux['mh-development'] = '1';

                update_option('myhome_redux', $redux);

                $users = get_users();
                foreach ($users as $user) {
                    /* @var $user WP_User */
                    if (empty($user->roles)) {
                        $user->add_role('agent');
                    }
                }
            }
        }

        // clear all existing transients
        public function clear_cache()
        {
            global $wp_rewrite;
            $wp_rewrite->set_permalink_structure('/%postname%/');
            update_option('rewrite_rules', false);
            $wp_rewrite->flush_rules(true);

            global $wpdb;
            $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%transient_%' ");
            wp_load_alloptions();
            wp_cache_delete('alloptions', 'options');
            do_action('myhome_reload_cache');

            $options                       = get_option('theme_mods_myhome');
            $options['nav_menu_locations'] = array('mh-primary' => 41);
            update_option('theme_mods_myhome', $options);
        }

        // IMPORTANT: Almost all data existing before demo import will be deleted.
        // Remove all existing data which may conflict with demo data.
        public function prepare()
        {
            global $wpdb;
            $wpdb->query("DELETE FROM {$wpdb->prefix}myhome_attributes ");
            $wpdb->query("DELETE FROM {$wpdb->prefix}myhome_locations ");
            $wpdb->query("DELETE FROM {$wpdb->posts} ");
            $wpdb->query("DELETE FROM {$wpdb->postmeta} ");
            $wpdb->query("DELETE FROM {$wpdb->commentmeta} ");
            $wpdb->query("DELETE FROM {$wpdb->comments} ");
            $wpdb->query("DELETE FROM {$wpdb->terms} ");
            $wpdb->query("DELETE FROM {$wpdb->term_taxonomy} ");
            $wpdb->query("DELETE FROM {$wpdb->term_relationships} ");
            $wpdb->query("DELETE FROM {$wpdb->termmeta} ");
            $wpdb->query("DELETE FROM {$wpdb->users} WHERE ID != 1 AND ID != ".get_current_user_id());
            $wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE user_id != 1 AND user_id != ".get_current_user_id());
        }

        // strings for translation
        public function get_strings()
        {
            return json_encode(array(
                'demo_loaded'        => __('You have successfully loaded MyHome Demo', 'myhome-importer'),
                'demo_online'        => __('Demo online', 'myhome-importer'),
                'plugin_name'        => __('MyHome Demo Importer', 'myhome-importer'),
                'load_demo'          => __('Import demo', 'myhome-importer'),
                'posts'              => __('Posts', 'myhome-importer'),
                'comments'           => __('Comments', 'myhome-importer'),
                'users'              => __('Users', 'myhome-importer'),
                'media'              => __('Media', 'myhome-importer'),
                'terms'              => __('Terms', 'myhome-importer'),
                'term_taxonomy'      => __('Term taxonomy', 'myhome-importer'),
                'term_relationships' => __('Term relationships', 'myhome-importer'),
                'term_meta'          => __('Term meta', 'myhome-importer'),
                'options'            => __('Options', 'myhome-importer'),
                'locations'          => __('Locations', 'myhome-importer'),
                'attributes'         => __('Attributes', 'myhome-importer'),
                'sliders'            => __('Sliders', 'myhome-importer'),
                'clear_cache'        => __('Clear cache', 'myhome-importer'),
                'available_demos'    => __('Demos', 'myhome-importer'),
                'redux'              => __('MyHome Settings', 'myhome-importer'),
                'time_left_resume'   => __('Next attempt to resume in', 'myhome-importer'),
                'error_message'      => __('Ops, something went wrong', 'myhome-importer'),
                'description'        => wp_kses_post(__('IMPORTANT - Loading Demo will remove all of your database content.
Before you start importing demo make sure you activated all required plugins.
            '))
            ));
        }

    }

endif;
