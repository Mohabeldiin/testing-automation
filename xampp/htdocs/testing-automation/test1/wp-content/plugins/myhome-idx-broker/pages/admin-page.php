<?php

use MyHomeIDXBroker\Importer;

$myhome_idx_broker_api_key = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'api_key' );

?>
<div class="wrap">

    <h1><?php esc_html_e( 'MyHome IDX Broker', 'myhome-idx-broker' ); ?></h1>

    <div style="padding: 24px; background: #fff; font-size: 16px; line-height: 24px; margin-top: 12px; position: relative;">
        Using this plugin is fully optional. You can disable it if you do not use it. IDX is limited to licensed agents
        from the United States, Canada, Bahamas, Mexico and Jamaica.
		<?php esc_html_e( 'You can read more about IDX integration in the MyHome Knowledge Base:',
			'myhome-idx-broker' ); ?>
        <a target="_blank"
           href="https://myhometheme.zendesk.com/hc/en-us/articles/115004872273-About-IDX-Broker-Integration-MLS-"><?php esc_html_E( 'About IDX', 'myhome-idx-broker' ); ?></a>
        <br><br>
        <h2 style="margin-top:0!important;"><?php esc_html_e( 'IDX Broker Registration', 'myhome-idx-broker' ); ?></h2>
        <div>

            If you do not have IDX Broker account yet we recommend to use below button to register.

			<?php esc_html_e( 'This way you do not have to pay IDX Broker Setup Fee ($100.00) and',
				'myhome-idx-broker' ); ?>
            <a href="https://themeforest.net/user/tangibledesign"><?php esc_html_e( 'TangibleDesign',
					'myhome-idx-broker' ); ?></a>
			<?php esc_html_e( '(MyHome developers team) will be assigned to your IDX Broker account. It can be useful for you if you need any help with integration.',
				'myhome-idx-broker' ); ?>
        </div>
        <p>
            <a href="https://signup.idxbroker.com/d/myhome" class="button button-primary idx-button-big"
               target="_blank">
				<?php esc_html_e( 'REGISTER', 'myhome-idx-broker' ); ?>
            </a>
        </p>
    </div>
    <div style="padding: 24px;
    background: #fff;
    font-size: 16px;
    line-height: 24px;
    margin-top: 12px;
    position: relative;
">
        <div>
            <h2 style="margin-top:0;">Plugin Overview</h2>
            <div>
                MyHome has two types of MLS integration. You can use either one integration or both at the same time.
            </div>
            <br>
            <table class="mh-basic-settings-table">
                <tr>
                    <th>

                    </th>
                    <th>
                        Import listings assigned to your account
                    </th>
                    <th>
                        Live MLS feed
                    </th>
                </tr>
                <tr>
                    <td>General Information</td>
                    <td>
                        This integration allows you to import all Active/Sold/Pending listings that are associated with
                        you or your office.
                        <br><br>
                        <strong>Important information:</strong> It is not possible to import all MLS listings into a
                        database; in most cases this is not legal, and the current version of the API does not support
                        this function.
                    </td>
                    <td>
                        You can display all MLS listings on your website.
                        <br><br>
                        <strong>Important information:</strong> Listings will be not imported to the WordPress database.
                    </td>
                </tr>
                <tr>
                    <td>Technology</td>
                    <td><a href="https://middleware.idxbroker.com/docs/api/overview.php" target="_blank">IDX Broker
                            API</a></td>
                    <td>
                        <a
                                href="https://support.idxbroker.com/customer/en/portal/articles/1995773-how-does-idx-broker-work"
                                target="_blank">Dynamic Wrappers</a>
                    </td>
                </tr>
                <tr>
                    <td>Search Engines</td>
                    <td><a href="http://myhometheme.net/default" target="_blank">Dynamic searching</a>,
                        <a href="http://myhometheme.net/marketplace" target="_blank">Classic searching</a>,
                        <a href="http://myhometheme.net/agency" target="_blank">Google Map Search</a>
                    </td>
                    <td>
                        <a href="http://myhometheme.net/idx/" target="_blank">
                            Omnibar</a>,
                        <a href="http://myhometheme.net/idx/" target="_blank">
                            Quick Search Horizontal (scroll to the middle of the linked page to see an example)</a>, <a
                                href="https://myhometheme.net/idx/blog/" target="_blank">Quick Search Vertical (right
                            sidebar example)</a>, <a href="http://myhometheme.idxbroker.com/idx/search/basic"
                                                     target="_blank">Basic Search
                            Page</a>, <a href="http://myhometheme.idxbroker.com/idx/search/address" target="_blank">Address
                            Page</a>,
                        <a href="http://myhometheme.idxbroker.com/idx/search/advanced" target="_blank">Advanced Search
                            Page</a>, <a href="http://myhometheme.idxbroker.com/idx/map/mapsearch" target="_blank">Map
                            Search
                            Page</a>
                    </td>
                </tr>
                <tr>
                    <td>Sample Listing</td>
                    <td>
                        MyHome Property Page - 
                        <a target="_blank"
                           href="https://myhometheme.net/main/properties/apartment/chicago/modern-apartment-in-the-city-center/">sample
                            listing</a>
                        <br><br>
                        It can import: Gallery, Listing Price, Address, Property Type, Description (Remarks), Acres,
                        Bedrooms, Total Baths, Full Baths, Partial Baths, SqFt, City, County, State, Street Direction,
                        Street Name, Street Number, Postal Code.
                        <br><br>
                        You will see all listings in your /wp-admin/ &gt; Properties and you can add more information
                        manually there as required.

                    </td>
                    <td>
                        IDX Broker Page - <a target="_blank"
                                             href="https://idx.myhometheme.net/idx/details/listing/a000/M1381320/5861-NW-194-TE-Miami-FL-33015">sample
                            listing</a>
                    </td>
                </tr>
                <tr>
                    <td>Custom Pages</td>
                    <td>All MyHome theme options, searches, maps, demos, property fields, offer types,
                        property cards and single property pages work perfectly with imported listings.
                    </td>
                    <td>
                        <span style="font-weight: 400;">You can add your IDX Broker account</span> - <a
                                href="/hc/en-us/articles/360003726173-How-to-create-saved-search-pages" target="_blank"
                        >Saved Searches Page</a>. Here you can see example of Saved Searched Page <a
                                href="https://idx.myhometheme.net/idx/results/listings?idxID=a000&amp;pt=7&amp;aw_address=Miami&amp;srt=newest"
                                target="_blank">Miami</a>. <br/><br/> You can also build your own WordPress pages
                        with custom <a
                                href="/hc/en-us/articles/360004220634-IDX-Broker-How-to-add-widgets-via-Page-Builder"
                                target="_blank">IDX Broker Widgets</a>.
                    </td>
                </tr>
                <tr>
                    <td>User Panel</td>
                    <td>
                        MyHome WordPress: <a href="https://myhometheme.net/test-user/panel/#/"
                                             target="_blank">Login</a>, <a
                                href="https://myhometheme.net/test-user/panel/#/register" target="_blank"
                        >Register</a>, <a href="https://myhometheme.net/test-user/panel/#/submit-property"
                                          target="_blank">Submit Property</a>.
                    </td>
                    <td>
                        MyHome WordPress: <a href="https://myhometheme.net/test-user/panel/#/" target="_blank"
                        >Login</a>, <a
                                href="https://myhometheme.net/test-user/panel/#/register" target="_blank"
                        >Register</a>, <a href="https://myhometheme.net/test-user/panel/#/submit-property"
                                          target="_blank">Submit Property</a> <br/><br/><span
                                style="font-weight: 400;">Includes an</span> independent IDX Broker panel - <a
                                href="http://myhometheme.idxbroker.com/idx/userlogin" target="_blank">Login</a>, <a
                                href="http://myhometheme.idxbroker.com/idx/usersignup" target="_blank">Register</a>.
                    </td>
                </tr>
                <tr>
                    <td>Page link</td>
                    <td>100% WordPress based</td>
                    <td>IDX Broker subdomain (e.g. xxxxxx.idxbroker.com)<br/><br/><span
                                style="font-weight: 400;">Alternatively,</span> you can use your custom subdomain e.g.
                        search.youraddress.com - <a
                                href="https://support.idxbroker.com/customer/portal/articles/1910166-setting-up-a-custom-subdomain">click
                            here to see how to configure your own subdomain</a>.
                    </td>
                </tr>
                <tr>
                    <td>Synchronization</td>
                    <td>
                        Manually sync by clicking "Import Listings" or automatically sync via your server Cron Jobs.
                    </td>
                    <td>Automatically sync</td>
                </tr>

                <tr>
                    <td>Maps</td>
                    <td>
                        Google Maps.
                    </td>
                    <td>
                        MapQuest - it is not possible to change it to Google Maps.
                    </td>
                </tr>
                <tr>
                    <td>HTTPS</td>
                    <td>Yes</td>
                    <td>
                        Yes - <a href="/hc/en-us/articles/360004113853-IDX-Broker-Enable-HTTPS"
                                 target="_blank">click here to see how to configure https</a>.
                    </td>
                </tr>
            </table>
            <div>
                <br>
                Using IDX integration is not free and requires a monthly subscription for IDX Broker. Some boards may
                require you to
                pay additional fees. The IDX Broker Lite option allows you to have all the MyHome theme options
                described here. Please remember that IDX Broker Lite will only allow you to import one agent listings to
                your database; for
                more agency properties, there is an additional payment to be made. It is not possible to import all MLS
                listings
                into a WordPress database; in most cases this is not legal, and the current version of the API does not
                support this
                function.
            </div>
            <p>
                <a href="https://myhometheme.zendesk.com/hc/en-us/sections/360000202133-IDX-Broker-Integration"
                   class="button button-primary idx-button-big"
                   target="_blank">
                    Click here to visit MyHome IDX Broker Knowledge Base
                </a>
            </p>
        </div>
    </div>
</div>

<div>
    Last job: <?php echo esc_html( get_option( Importer::LAST_CHECK ) ); ?><br>
    Last cron start: <?php echo esc_html( get_option( 'myhome_idx_cron_date_start' ) ) ?><br>
    Last cron end: <?php echo esc_html( get_option( 'myhome_idx_cron_date_end' ) ) ?><br>
</div>