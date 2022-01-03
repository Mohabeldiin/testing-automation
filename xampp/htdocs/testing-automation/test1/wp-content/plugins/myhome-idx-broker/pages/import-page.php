<?php

$myhome_idx_broker_api_key = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get('api_key');
$myhome_idx_broker_api = new \MyHomeIDXBroker\Api();
if ($myhome_idx_broker_api->has_key()) {
    $new_active = count($myhome_idx_broker_api->get_new_active_properties());
    $sold = count($myhome_idx_broker_api->get_sold_pending_properties());
}

?>
<div class="wrap">
    <h1>Import listings assigned to your account</h1>

    <?php
    $limit = get_option('myhome_idx_broker_api_limit');
    if (!empty($limit)) :
        ?>
        <div class="mh-api-used">
            <h2>Unfortunately this page cannot be displayed now because your IDX Broker account has exceeded the hourly
                access limit for your API key</h2>
            <div>
                You can find more information about <strong>API Key</strong> limits and how to reset it
                <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360009233893">here</a>.
            </div>
            <div class="clearfix"></div>
        </div>
    <?php endif; ?>

    <?php
    $limit = get_option('myhome_idx_broker_api_limit');
    if (empty($limit)) :
        ?>
        <div class="mh-idx-info-start" style="width:100%">
            <div class="mh-idx-info-start__inner">
                <!--                    <h2 style="margin-top:0;"><span class="dashicons dashicons-admin-home"-->
                <!--                                                    style="position:relative; top:4px;"></span> --><?php //esc_html_e('Import listings assigned to your account', 'myhome-idx-broker');
                ?>
                <!--                    </h2>-->
                <?php
                $myhome_idx_broker_api = new \MyHomeIDXBroker\Api();
                if ($myhome_idx_broker_api->has_key()) :
                    ?>
                <?php endif; ?>
                <?php esc_html_e('When you click the "Import Listings" button below, MyHome will connect to your IDX Broker account and download all Active/Sold/Pending listings that are associated with you or your office. It is not possible to import into database all MLS properties, because it is illegal in the most cases and IDX Broker API does not have this function available in the current version.',
                    'myhome-idx-broker'); ?>
                <br><br><strong><?php esc_html_e('Available active listings:', 'myhome-idx-broker'); ?></strong>
                <?php echo esc_html($new_active); ?><br>
                <strong><?php esc_html_e('Available sold / pending listings:', 'myhome-idx-broker'); ?></strong>
                <?php echo esc_html($sold); ?>

                <?php
                $max_execution_time = ini_get('max_execution_time');
                if ($max_execution_time > 1 && $max_execution_time < 179):?>
                    <br>
                    <strong>Your server max_execution_time is:</strong>
                    <?php echo esc_html($max_execution_time); ?> (seconds). If there is a lot of images assigned to one property and not all images import correctly, please increase max_execution_time in your PHP settings to 600. Your server will have more time to download all images and create thumbnails.
                <?php endif; ?>
                <br>

                <?php if ($new_active !== 50 || $sold !== 50) : ?>
                    <div id="myhome-idx-broker-import">
                        <idx-broker-import></idx-broker-import>
                    </div>
                <?php endif; ?>

                <div class="faq-box-import">
                    <h2><i class="fas fa-life-ring" style="margin-right:6px;"></i> Frequently asked questions about
                        Importing Listings</h2>
                    <div class="faq-box-import__content">
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360001486153">How
                            can I change Agent/Office IDs used for importing listings to the WordPress database?</a>
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360009280833">Why I
                            cannot download all MLS properties into database?</a>
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360009286413">How
                            can I stop importing to my database Sold/Pending Listings?</a>
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360003963633">When
                            I import listings, images are not imported correctly - how to fix it?</a>
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360009158914">Why I
                            cannot import all information about my listings (e.g. Year Build)?</a>
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360009158214">Why
                            my imported listings are not displayed in the featured sections on the page?</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mh-idx-info-start" style="width:100%">
            <div class="mh-idx-info-start__inner">
                <form
                        action="<?php echo esc_url(admin_url('admin-post.php?action=myhome_idx_broker_save_fields')); ?>"
                        method="post"
                >
                    <h2>Fields configuration</h2>
                    <div><?php esc_html_e('Below you can find list of fields that are available via IDX Broker API to import -',
                            'myhome-idx-broker'); ?>
                        <a target="_blank"
                           href="https://myhometheme.zendesk.com/hc/en-us/articles/360009158914-Why-I-cannot-import-all-information-about-my-listings-e-g-Year-Build-">click
                            here to read more</a>
                    </div>
                    <br>
                    <div class="form-table">
                        <?php $myhome_attributes = \MyHomeIDXBroker\Fields::get_attributes(); ?>
                        <div style="margin-bottom: 12px; float: left;">
                            <div style="width:120px; float:left;">
                                <strong>IDX Broker Field</strong>
                            </div>
                            <div style="width:300px; float:left;">
                                <strong>MyHome Fields</strong>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <?php foreach (\MyHomeIDXBroker\Fields::get() as $myhome_field) : ?>
                            <div style="margin-bottom: 12px; float: left;">
                                <div style="width:120px; float:left; line-height:36px;">
                                    <?php echo esc_html($myhome_field->get_display_name()); ?>
                                </div>
                                <div style="width:300px; float:left;">
                                    <select
                                            name="fields[<?php echo esc_attr($myhome_field->get_name()); ?>][]"
                                            multiple="multiple"
                                            class="selectize-idx"
                                    >
                                        <?php foreach ($myhome_attributes as $myhome_key => $myhome_label) : ?>
                                            <option
                                                    value="<?php echo esc_attr($myhome_key); ?>"
                                                <?php if ($myhome_field->has_value($myhome_key)) : ?>
                                                    selected="selected"
                                                <?php endif; ?>
                                            >
                                                <?php echo esc_html($myhome_label); ?>
                                            </option>
                                        <?php endforeach;; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        <?php endforeach; ?>
                    </div>
                    <div>
                        <button class="button button-primary">
                            <?php esc_html_e('Save', 'myhome-idx-broker'); ?>
                        </button>
                    </div>

                </form>

            </div>
        </div>

        <div class="mh-idx-info-start" style="width:100%">
            <div class="mh-idx-info-start__inner">

                <div class="mh-idx-agents-import">
                    <h2>
                        <span class="dashicons dashicons-admin-users"
                              style="position:relative; top:4px;"></span> <?php esc_html_e('Users',
                            'myhome-idx-broker'); ?>
                    </h2>

                    <div>
                        MyHome import automatically users from - <a href="https://middleware.idxbroker.com/mgmt/"
                                                                    target="_blank">https://middleware.idxbroker.com/mgmt/</a>
                        >> Users >> Agents >> Manage
                    </div>
                    <br>
                    <div class="faq-box-import">
                        <h2><i class="fas fa-life-ring" style="margin-right:6px;"></i> Frequently asked questions about
                            IDX Users</h2>
                        <div class="faq-box-import__content">
                            <a target="_blank"
                               href="https://myhometheme.zendesk.com/hc/en-us/articles/360009313933-How-can-I-import-IDX-Broker-users-into-WordPress-database-">How
                                can I import IDX Broker users into WordPress database?</a>
                            <a target="_blank"
                               href="https://myhometheme.zendesk.com/hc/en-us/articles/360009338473-How-can-I-migrate-my-WordPress-IDX-Broker-Dynamic-wrappers-to-the-new-domain-I-move-my-WordPress-">How
                                can I merge 2 IDX Broker users into one WordPress
                                account?</a>
                        </div>
                    </div>
                    <div>
                        <a
                                class="button button-primary"
                                href="<?php echo esc_url(admin_url('admin-post.php?action=myhome_idx_broker_import_agents')); ?>"
                        >
                            <?php esc_html_e('Manual Import', 'myhome-idx-broker'); ?>
                        </a>
                        <br>
                        <br>
                    </div>
                </div>

                <table class="wp-list-table widefat fixed striped posts">

                    <tr>
                        <th>
                            <?php esc_html_e('IDX Broker ID', 'myhome-idx-broker'); ?>
                        </th>
                        <th class="manage-column">
                            <?php esc_html_e('Name', 'myhome-idx-broker'); ?>
                        </th>
                        <th>
                            <?php esc_html_e('E-mail', 'myhome-idx-broker'); ?>
                        </th>
                        <th></th>
                    </tr>

                    <?php foreach (\MyHomeIDXBroker\Agents::get() as $myhome_agent) : ?>
                        <tr>
                            <td>
                                #<?php echo esc_html($myhome_agent->get_idx_broker_id()); ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url($myhome_agent->get_link()); ?>">
                                    <?php echo esc_html($myhome_agent->get_name()); ?>
                                </a>
                            </td>
                            <td>
                                <?php if ($myhome_agent->has_email()) : ?>
                                    <a href="mailto:<?php echo esc_attr($myhome_agent->get_email()); ?>">
                                        <?php echo esc_html($myhome_agent->get_email()); ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url(admin_url('user-edit.php?user_id='
                                    . $myhome_agent->get_ID())); ?>">
                                    <span class="dashicons dashicons-edit"></span> Edit User WordPress Profile
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            </div>
        </div>

        <div class="mh-idx-info-start" style="width:100%">
            <div class="mh-idx-info-start__inner">

                <form
                        action="
    <?php echo esc_url(admin_url('admin-post.php?action=myhome_idx_broker_save_mls_ids')); ?>"
                        method="post"
                >

                    <?php wp_nonce_field('myhome_idx_broker_update_mls', 'check_sec'); ?>

                    <strong>
                        <?php esc_html_e('MLS IDs (one per line)', 'myhome-idx-broker'); ?>
                    </strong>
                    <div>This field should be filled automatically. <a target="_blank"
                                                                       href="https://myhometheme.zendesk.com/hc/en-us/articles/360009310273">Click
                            here to read where to find MLS IDs</a></div>
                    <p>
                        <textarea name="mls_ids" id="mls-ids" cols="30" rows="5"><?php
                            foreach (\MyHomeIDXBroker\MLS::get() as $myhome_mls_id) :
                                echo esc_html($myhome_mls_id) . "\n";
                            endforeach;
                            ?></textarea>
                    </p>

                    <p>
                        <input
                                type="submit"
                                class="button button-primary"
                                value="<?php esc_attr_e('Save', 'myhome-idx-broker'); ?>"
                        >
                    </p>

                </form>

            </div>
        </div>

        <div class="mh-idx-info-start" style="width:100%;">
            <div class="mh-idx-info-start__inner">

                <h2 style="margin-top:0;"><span class="dashicons dashicons-clock"
                                                style="position:relative; top:4px;"></span> <?php esc_html_e('Automatically "Import Listings"',
                        'myhome-idx-broker'); ?>
                </h2>
                <div>If you wish, you can use your server <strong>Cron Jobs</strong> to import yours listings
                    automatically
                    e.g. once per day at midnight. Every server is little different so we recommend to firstly
                    contact
                    your
                    hosting provider and ask how to schedule this 2 commands on your type of server.
                </div>
                <br>
                <div>
                    <div class="faq-box-import">
                        <div class="faq-box-import__content">
                            <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360009346793">How
                                can I automatically import listings assigned to my account
                                via cron jobs?</a>
                        </div>
                    </div>
                </div>
                <br>
                <div style="border-left:4px solid #387f75; padding:12px;">
                    <?php $myhome_idx_broker_hash = \MyHomeIDXBroker\IDX::cron_get_hash(); ?>
                    <div>
                        <strong style="color:#387f75;"><?php esc_html_e('Schedule it how often you wish to import e.g. once per day at midnight:',
                                'myhome-idx-broker'); ?></strong>
                    </div>
                    <div style="font-size:12px">
                        <?php echo esc_url(admin_url('admin-post.php?action=myhome_idx_broker_cron_init&myhome_idx_broker_hash='
                            . $myhome_idx_broker_hash)); ?>

                    </div>
                    <div>
                        <br>
                        <strong style="color:#387f75;"><?php esc_html_e('Schedule it every 60 seconds:',
                                'myhome-idx-broker'); ?></strong>
                    </div>
                    <div style="font-size:12px">
                        <?php echo esc_url(admin_url('admin-post.php?action=myhome_idx_broker_cron_job&myhome_idx_broker_hash='
                            . $myhome_idx_broker_hash)); ?>
                    </div>
                </div>
                <br>
                <a href="<?php echo esc_url(admin_url('admin-post.php?action=myhome_idx_broker_hash')); ?>"
                   class="button button-primary">
                    <?php esc_html_e('Regenerate hash', 'myhome-idx-broker'); ?>
                </a>
                <br>
                <?php
                $cron_job_info = get_option('mh_cron_job');
                $cron_init_info = get_option('mh_cron_init');
                if (!empty($cron_job_info) && is_array($cron_job_info)
                    && isset($cron_job_info['type'], $cron_job_info['date'])
                ) :?>
                    <h3>Cron job</h3>
                    Last execution: <?php echo esc_html($cron_job_info['date']); ?><br>
                <?php
                endif;

                if (!empty($cron_init_info)) : ?>
                    <h3>Cron init</h3>
                    Last execution: <?php echo esc_html($cron_init_info); ?>
                <?php endif; ?>
                <br>
            </div>
        </div>
    <?php endif; ?>
</div>