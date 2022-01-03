<?php
/*
Plugin Name: MyHome Updater
Description: This plugin is a part of MyHome Theme. The minimum PHP version required is 5.6.0.
Version: 1.3
Plugin URI: https://myhometheme.net
*/

if (version_compare(PHP_VERSION, '5.6.0', '<')) {
    return;
}

add_action('admin_menu', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    add_menu_page(
        esc_html__('MyHome Updater', 'myhome-updater'),
        esc_html__('MyHome Updater', 'myhome-updater'),
        'manage_options',
        'myhome-updater',
        function () {
            $purchase_code = myhome_get_purchase_code();

            ?>
            <style>
                #update-nag, .update-nag, .notice {
                    display: none !important;
                }
            </style>
            <h1>MyHome Updater</h1>
            <div style="
                    padding:24px;
                    background:#fff;
                    color:#222;">
                <div style="
                        border: 1px solid #d4d4d4!important;
                        padding: 24px;
                        background: #f4f4f4;
                    ">

                    <form
                            action="<?php echo esc_url(admin_url('admin-post.php?action=myhome_updater_purchase_code')); ?>"
                            method="post"
                    >
                        <p>
                        <h2 for="mh-purchase-code">
                            <?php esc_html_e('Your Purchase code', 'myhome-updater'); ?>
                            <div>
                                <input
                                        id="mh-purchase-code"
                                        name="mh-purchase-code"
                                        placeholder="Add your purchase code here"
                                        type="text"
                                        value="<?php echo esc_attr($purchase_code); ?>"
                                        style="max-width: 400px;
                                                       margin: 12px 0 0 0;
                                                       width: 100%;
                                                       padding: 8px;"
                                >
                            </div>
                        </h2>

                        <?php if (myhome_show_invalid_purchase_code_notice()) : ?>
                            <span style="color:red;">Purchase code is invalid.</span>
                        <?php endif; ?>
                        </p>
                        <p>
                        <ul>
                            <li><a target="_blank"
                                   href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">Where
                                    to find purchase code?</a></li>
                            <li><a target="_blank"
                                   href="https://themeforest.net/item/myhome-real-estate-wordpress-theme/19508653&ref=tangibledesign">Where
                                    to buy legal MyHome theme?</a></li>
                        </ul>
                        <button class="button-primary">
                            <?php esc_html_e('Save', 'myhome-updater'); ?>
                        </button>
                        </p>
                    </form>
                </div>
            </div>

            <div style="
    padding: 0 24px 36px;
    background: #fff;
    font-size: 16px;
    line-height: 1.5;
    margin-bottom: 12px;">
                <h2 style="font-size:24px; margin-top:0!important; margin-bottom:12px;">Important information</h2>
                <div>
                    <div style="max-width:500px; margin-bottom:24px">
                        When you click the "update" button your theme and all plugins files will be overwritten.
                        Please make sure all modified theme files are stored in the
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360001119614-How-to-use-Child-Theme-">
                            MyHome Child Theme</a><br>
                    </div>

                    <?php
                    $myhomeCountLangTheme = count(scandir(get_template_directory() . '/languages')) - 2;
                    $myhomeCountLangCore = count(scandir(ABSPATH . 'wp-content/plugins/myhome-core/languages')) - 2;
                    ?>

                    <?php if ($myhomeCountLangTheme > 1 || $myhomeCountLangCore > 1) : ?>
                        <div style="margin-bottom:24px;">
                            <div style="color:#ca4a1f">
                                <i class="fa fa-warning"></i> Warning! Our system found translation files in your:<br>
                                <ul style="list-style: disc; margin-left: 40px;">
                                    <?php if ($myhomeCountLangTheme > 1) : ?>
                                        <li>wp-content/themes/myhome/languages/</li>
                                    <?php endif; ?>

                                    <?php if ($myhomeCountLangCore > 1) : ?>
                                        <li>wp-content/plugins/myhome-core/languages/</li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <strong>This is not the correct way of doing it. Files will be removed during update! The
                                correct location of files and names should be:</strong>
                            <ul style="list-style: disc; margin-left: 40px;">
                                <?php if ($myhomeCountLangTheme > 1) : ?>
                                    <li>wp-content/languages/themes/myhome-LANGUAGE-CODE.po</li>
                                    <li>wp-content/languages/themes/myhome-LANGUAGE-CODE.mo</li>
                                <?php endif; ?>

                                <?php if ($myhomeCountLangCore > 1) : ?>
                                    <li>wp-content/languages/plugins/myhome-core-LANGUAGE-CODE.po</li>
                                    <li>wp-content/languages/plugins/myhome-core-LANGUAGE-CODE.mo</li>
                                <?php endif; ?>
                            </ul>
                            Keeping these files in the <strong>/wp-content/languages</strong> not in the theme or plugin
                            folders will keep your translation safe.
                        </div>
                    <?php endif; ?>

                    <?php if (!myhome_show_invalid_purchase_code_notice() && !empty(myhome_get_purchase_code())) : ?>
                        <div>
                            <form action="<?php echo esc_url(admin_url('admin-post.php?action=myhome_check_updates')); ?>"
                                  method="post">
                                <button class="button">Check for new updates</button>

                                <?php if (isset($_GET['show_updates_info'])) : ?>
                                    <?php if (myhome_require_update()) : ?>
                                        <div style="color: orange;
                                        font-weight: 700;
                                        margin-top: 12px;
                                        font-size: 21px;">
                                            New updates available
                                        </div>
                                    <?php else : ?>
                                        <div style="color: green;
                                    font-weight: 700;
                                    margin-top: 12px;
                                    font-size: 21px;">You have latest MyHome version
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </form>
                        </div>
                    <?php endif; ?>

                    <?php if (myhome_require_update()) : ?>
                        <div style="padding: 6px 24px 24px 24px; background: #f4f4f4;">
                            <div id="myhome-updater-app">
                                <myhome-updater
                                        :plugins="<?php echo htmlspecialchars(json_encode(myhome_updater_get_plugins())); ?>"
                                        theme-status="<?php echo esc_attr(myhome_updater_get_theme_status()); ?>"
                                        query-url="<?php echo esc_url(admin_url('admin-post.php?action=')); ?>"
                                >
                                    <div slot-scope="myhomeUpdater">
                                        <template>
                                            <div>
                                                <ul style="
                                            line-height: 24px!important;
                                            list-style: none;
                                            font-size: 16px!important;">
                                                    <li>
                                                    <span v-if="myhomeUpdater.themeStatus === 'ok'">MyHome Theme Files - <span
                                                                style="color:green; font-weight:700;">OK</span></span>
                                                        <span v-if="myhomeUpdater.themeStatus === 'need_update'">MyHome Theme Files - <span
                                                                    style="color:orange; font-weight:700;">Need update</span></span>
                                                        <span v-if="myhomeUpdater.themeStatus === 'updating'">MyHome Theme Files <i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </li>
                                                    <li v-for="plugin in myhomeUpdater.plugins" :key="plugin.key">
                                                    <span v-if="plugin.status === 'ok'">{{ plugin.name }} - <span
                                                                style="color:green; font-weight:700;">OK</span></span>
                                                        <span v-if="plugin.status === 'need_update'">{{ plugin.name }} - <span
                                                                    style="color:orange; font-weight:700;">Need update</span></span>
                                                        <span v-if="plugin.status === 'updating'">{{ plugin.name }} <i
                                                                    class="fa fa-spinner fa-spin"></i></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </template>
                                        <button :disabled="myhomeUpdater.updateInProgress"
                                                @click="myhomeUpdater.onStart"
                                                class="button-primary"
                                                style="padding: 24px 18px!important;
                                                    line-height: 1px!important;
                                                    font-size: 21px!important;
                                                    font-weight: 700!important;
                                                    text-transform: uppercase;
                                                    border-radius: 3px!important;">
                                            <?php esc_html_e('Update', 'myhome-updater'); ?>
                                        </button>
                                    </div>
                                </myhome-updater>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <?php
        },
        '',
        4
    );
});

add_action('admin_post_myhome_updater_purchase_code', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (!isset($_POST['mh-purchase-code'])) {
        wp_redirect(admin_url('?page=myhome-updater'));
        exit;
    }

    $purchase_code = str_replace(' ', '', $_POST['mh-purchase-code']);
    update_option('myhome_purchase_code', $purchase_code);

    myhome_check_updates();

    wp_redirect(admin_url('?page=myhome-updater'));
    exit;
});

add_action('admin_init', function () {
    if (!wp_next_scheduled('myhome_check_updates')) {
        wp_schedule_event(time(), 'daily', 'myhome_check_updates');
    }
});

function myhome_check_updates()
{
    $purchase_code = myhome_get_purchase_code();
    if (empty($purchase_code)) {
        return;
    }

    $params = [
        'purchaseCode' => $purchase_code,
        'projectKey' => 'myhome',
        'site' => site_url()
    ];

    $response = wp_remote_post('https://updater.tangiblewp.com/api/updates/check', [
        'body' => $params
    ]);

    if ($response instanceof WP_Error || !isset($response['body'])) {
        return;
    }

    $data = json_decode($response['body']);
    if (!$data instanceof stdClass) {
        return;
    }

    if (!property_exists($data, 'success')) {
        return;
    }

    if (property_exists($data, 'data')) {
        update_option('myhome_updates', $data->data);
    } else {
        update_option('myhome_updates', $data);
    }
}

add_action('myhome_check_updates', 'myhome_check_updates');

add_action('admin_post_myhome_updater_plugin', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (!isset($_POST['pluginKey']) || empty($_POST['pluginKey'])) {
        return;
    }

    $purchase_code = myhome_get_purchase_code();
    if (empty($purchase_code)) {
        return;
    }

    $plugin_key = trim($_POST['pluginKey']);

    require_once ABSPATH . 'wp-admin/includes/misc.php';

    if (!function_exists('request_filesystem_credentials')) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }

    if (!class_exists('Plugin_Upgrader')) {
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    }

    $updates_info = get_option('myhome_updates');
    $plugin_upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());

    foreach ($updates_info->plugins as $plugin) {
        if ($plugin->key !== $plugin_key) {
            continue;
        }

        $pluginPath = $plugin->directory . '/' . $plugin->main_file;
        if (is_plugin_active($pluginPath)) {
            deactivate_plugins(WP_PLUGIN_DIR . '/' . $pluginPath, true);
        }
        $delete_plugins_return = delete_plugins([$pluginPath]);

        if (is_wp_error($delete_plugins_return)) {
            echo $delete_plugins_return->get_error_messages();
        }

        $install_plugin_return = $plugin_upgrader->install(
            'https://updater.tangiblewp.com/api/updates/file?fileId=' . $plugin->file
            . '&purchaseCode=' . $purchase_code
            . '&projectKey=myhome'
        );

        if (is_wp_error($install_plugin_return)) {
            echo $install_plugin_return->get_error_messages();
        }

        activate_plugin($pluginPath);
        break;
    }
});

add_action('admin_post_myhome_updater_theme', function () {
    $updates = get_option('myhome_updates');
    if (!$updates instanceof stdClass || !property_exists($updates, 'version')) {
        return;
    }

    $purchase_code = myhome_get_purchase_code();
    if (empty($purchase_code)) {
        return;
    }


    if (!class_exists('Theme_Upgrader')) {
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    }

    require 'class-myhome-theme-upgrader.php';

    require_once ABSPATH . 'wp-admin/includes/misc.php';

    if (!function_exists('request_filesystem_credentials')) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }

    $theme_upgrader = new My_Home_Theme_Upgrader(new Automatic_Upgrader_Skin());

    $return = $theme_upgrader->upgrade(
        'myhome',
        [],
        'https://updater.tangiblewp.com/api/updates/file?fileId='
        . $updates->file
        . '&purchaseCode=' . $purchase_code
        . '&projectKey=myhome'
    );

    if (is_wp_error($return)) {
        echo $return->get_error_messages();
    }
});

add_action('admin_enqueue_scripts', function () {
    if (isset($_GET['page']) && $_GET['page'] === 'myhome-updater') {
        wp_enqueue_script('myhome-updater', plugins_url() . '/myhome-updater/assets/js/updater.js', ['jquery'], '1.0.0', true);
    }
});

function myhome_require_update()
{
    foreach (myhome_updater_get_plugins() as $plugin) {
        if ($plugin['status'] === 'need_update') {
            return true;
        }
    }

    return myhome_updater_get_theme_status() === 'need_update';
}

function myhome_updater_get_theme_status()
{
    $updates = get_option('myhome_updates');

    if (!$updates instanceof stdClass || !property_exists($updates, 'version')) {
        return 'ok';
    }

    return My_Home_Theme()->version < $updates->version ? 'need_update' : 'ok';
}

function myhome_updater_get_plugins()
{
    $updates = get_option('myhome_updates');
    if (!$updates instanceof stdClass || !property_exists($updates, 'plugins')) {
        return [];
    }

    $plugins = [];
    foreach ($updates->plugins as $plugin) {
        $pluginPath = $plugin->directory . '/' . $plugin->main_file;
        if (is_plugin_active($pluginPath)) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $pluginPath);
            $current_version = $plugin_data['Version'];
        } else {
            continue;
        }

        $plugins[] = [
            'name' => $plugin->name,
            'key' => $plugin->key,
            'version_current' => $current_version,
            'version_new' => $plugin->version,
            'status' => version_compare($current_version, $plugin->version) === -1 ? 'need_update' : 'ok'
        ];
    }

    return $plugins;
}

function myhome_get_purchase_code()
{
    return str_replace(' ', '', get_option('myhome_purchase_code'));
}

function myhome_show_invalid_purchase_code_notice()
{
    $purchase_code = myhome_get_purchase_code();
    if (empty($purchase_code)) {
        return false;
    }

    $updates_info = get_option('myhome_updates');
    if (empty($updates_info)) {
        return false;
    }

    if (
        !$updates_info instanceof stdClass
        || !property_exists($updates_info, 'success')
        || $updates_info->success
        || !property_exists($updates_info, 'error')
        || $updates_info->error !== 'invalid_purchase_code'
    ) {
        return false;
    }

    return true;
}

add_action('admin_post_myhome_check_updates', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    myhome_check_updates();

    wp_redirect(admin_url('?page=myhome-updater&show_updates_info=1'));
    exit;
});