<?php
$myhome_idx_broker_api_key = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get('api_key');
$myhome_idx_api = new \MyHomeIDXBroker\Api();
$myhome_idx_pages = array_merge($myhome_idx_api->get_system_links(), $myhome_idx_api->get_saved_links());
?>

<div class="wrap">

    <h1><?php esc_html_e('Live MLS feed', 'myhome-idx-broker'); ?></h1>

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

        <div class="box-mls-install">
            <h2>
                <span class="dashicons dashicons-admin-generic" style="position:relative; top:1px;"></span>
                Configuration of Live MLS Feed
            </h2>
            <p>This instruction explains how to configure all yours IDX Broker Pages (<a href="#your-idx-pages">list
                    below</a>) to look the same as MyHome IDX
                Broker Templates (<a target="_blank"
                                     href="https://myhometheme.zendesk.com/hc/en-us/articles/360001420793">click here to
                    see list of styled templates</a>)</p>


            <div style="
            padding: 1px 12px;
    border-left: 4px solid #387f75;
    margin-bottom: 6px;
">
                <p>
                    <strong>Step 1. Disable Coming Soon Mode / Password Protect</strong> - please temporary disable it
                    if you use it. IDX Broker system can only read public sites
                </p>
                <p>
                    <strong>Step 2. Importing Wrappers</strong> - click below button once - it will add 29 pages to your
                    /wp-admin/ >> Pages. Theses
                    pages will be used as source for your dynamic wrappers.
                </p>
                <p>
                    <a href="<?php echo esc_url(admin_url('admin-post.php?action=myhome_idx_broker_auto_setup')); ?>"
                       class="button button-primary">
                        <?php esc_html_e('Import IDX Broker Dynamic Wrappers', 'myhome-idx-broker'); ?>
                    </a>
                    <br>
                    <br>
                </p>
                <p><strong>Step 3. Templates Setup</strong> - please make sure all
                    <a target="_blank"
                       href="https://myhometheme.zendesk.com/hc/en-us/articles/360001420793-IDX-Broker-MyHome-style-templates-list"
                       target="_blank">recommended templates</a> are chosen in your IDX Broker account
                    - <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360008165534">
                        <?php esc_html_e('click here to read how to change page template', 'myhome-idx-broker'); ?>
                    </a>
                </p>
                <p><strong>Step 4. Dynamic Wrapper Setup</strong> - please make sure all dynamic wrappers point to the
                    correct page WordPress URLs
                    - <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360008316113">
                        <?php esc_html_e('click here to read how to change page wrapper', 'myhome-idx-broker'); ?>
                    </a>
                </p>
                <p>
                    <strong>Step 5. Test Your Live MLS Feed</strong> - Below you can see list of your IDX Broker Pages.
                    Visit links to check if design was implemented correctly
                </p>
            </div>
        </div>

        <div class="mh-idx-half-wrapper">


            <div class="mh-idx-half">


                <div style="background:#fff; padding:12px;">
                    <h2 id="your-idx-pages" style="margin-top:-40px; padding-top:40px;">
                        <span class="dashicons dashicons-networking"></span> <?php esc_html_e('Your IDX Broker Pages', 'myhome-idx-broker'); ?>
                    </h2>

                    <?php
                    $myhome_idx_api = new \MyHomeIDXBroker\Api();
                    if (!empty($myhome_idx_pages)) :
                        ?>

                        <table class="mh-idx-new-table">
                            <tr>
                                <th><?php esc_html_e('Page name', 'myhome-idx-broker'); ?></th>
                                <th><?php esc_html_e('Your Link', 'myhome-idx-broker'); ?></th>
                            </tr>

                            <?php foreach ($myhome_idx_pages as $idx_page) : ?>
                                <tr>
                                    <td>
                                        <?php
                                        if (isset($idx_page['name'])) :
                                            echo esc_html($idx_page['name']);
                                        else :
                                            esc_html_e('Custom Page', 'myhome-idx-broker');
                                        endif;
                                        ?>
                                    </td>

                                    <td>
                                        <a
                                                href="<?php echo esc_url($idx_page['url']); ?>"
                                                target="_blank"
                                        >
                                            <?php echo esc_url($idx_page['url']); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td><?php esc_html_e('Photo Gallery', 'myhome-idx-broker'); ?></td>
                                <td><?php esc_html_e('Dynamic url', 'myhome-idx-broker'); ?></td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e('Schedule Showing', 'myhome-idx-broker'); ?></td>
                                <td><?php esc_html_e('Dynamic url', 'myhome-idx-broker'); ?></td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e('More Info', 'myhome-idx-broker'); ?></td>
                                <td><?php esc_html_e('Dynamic url', 'myhome-idx-broker'); ?></td>
                            </tr>
                            <tr>
                                <td>Agent Bio & Listings</td>
                                <td><?php esc_html_e('Dynamic url', 'myhome-idx-broker'); ?></td>
                            </tr>

                        </table>
                    <?php
                    endif;
                    ?>
                </div>
            </div>

            <div class="mh-idx-half">
                <?php if (!empty($myhome_idx_broker_api_key)) : ?>
                <div class="box-mls-cache">
                    <h2>Important! How IDX Broker cache works</h2>
                    Dynamic wrapper information is cached. This ensures a speedier experience for your web visitors, but
                    you
                    may notice that changes to your website are not immediately available on the IDX
                    Broker pages. Click the "Clear IDX Wrapper
                    Cache" button below to immediately clear the cache and update your IDX Broker Pages.
                    <strong>Please disable any Coming Soon Mode / Password Protect on your server, before you click it,
                        because system can only read public sites</strong>
                    <br><br>
                    <a
                            href="<?php echo esc_url(admin_url('admin-post.php?action=clear_cache_button')); ?>"
                            class="button button-primary"
                    >
                        <?php esc_html_e('Clear IDX Wrapper Cache', 'myhome-idx-broker'); ?>
                    </a>
                    <br>
                </div>
                <div class="faq-box-import">
                    <h2><i class="fas fa-life-ring" style="margin-right:6px;"></i> Frequently asked questions about Live MLS feed</h2>
                    <div class="faq-box-import__content">
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360009285993">Why I
                            always see dark box "IDX Wrapper Placeholder" not Live MLS Feed?</a>
                        <a href="https://myhometheme.zendesk.com/hc/en-us/articles/360010129614" target="_blank">How
                            to edit sidebar on the IDX Broker Pages

                        </a>
                        <a href="https://myhometheme.zendesk.com/hc/en-us/articles/360009339574-How-to-add-IDX-Broker-Omnibar-that-will-Search-All-MLS-Listings-"
                           target="_blank">How to add IDX Broker Omnibar that will Search All MLS Listings?
                        </a>
                        <a href="https://myhometheme.zendesk.com/hc/en-us/articles/360009469613-How-to-add-IDX-Broker-Search-Form-that-will-Search-All-MLS-Listings-"
                           target="_blank">How to add IDX Broker Search Form that will Search All MLS Listings?

                        </a>
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360003726173">How
                            can I create IDX Broker saved search pages?</a>
                        <a target="_blank" href="">
                            How can I migrate my WordPress IDX Broker Dynamic wrappers to the new domain (I move my
                            WordPress)?
                        </a>
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360004220634">How
                            can I add IDX Broker widgets via Page Builder e.g. search forms / cards / carousels (for all
                            MLS Listings)</a>
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360009281353">Why
                            my IDX Broker pages layout / styles do not works correctly?</a>
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360009156774">Can I
                            use IDX Broker templates that are not styled by MyHome?</a>
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360009280833">Why I
                            cannot download all MLS properties into database? Why I cannot see it the /wp-admin/ >
                            Properties?</a>
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360004113853">How
                            to enable HTTPS on my IDX Broker Pages?</a>
                        <a target="_blank" href="https://myhometheme.zendesk.com/hc/en-us/articles/360001744373">Why
                            Icons on the IDX Broker Pages do not display correctly and how to fix it?</a>
                    </div>
                </div>
                <a href="https://myhometheme.zendesk.com/hc/en-us/sections/360000202133-IDX-Broker-Integration"
                   class="button button-primary idx-button-big"
                   target="_blank">
                    MyHome IDX Broker Knowledge Base
                </a>
            </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>