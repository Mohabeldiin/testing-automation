<?php
$myhome_idx_broker_api_key = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'api_key' );
?>
<div class="wrap">
    <style>
        #footer-thankyou,
        #footer-upgrade {
            display: none !important;
        }
    </style>
    <h1>Settings</h1>


	<?php
	$myhome_idx_broker_load_style = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'load_style' );
	?>
	<?php
	if ( ! empty( $myhome_idx_broker_api_key ) ) :
		$myhome_idx_broker_account_info = get_option( 'myhome_idx_account' );
		?>
        <div class="mh-idx-info-start" style="margin-top:12px;">
            <div class="mh-idx-info-start__inner">
				<?php if ( isset( $myhome_idx_broker_account_info['clientName'] ) ) : ?>
                    <strong>
					<span class="dashicons dashicons-admin-plugins" style="
     margin-right: 3px; position: relative;top: 4px;"></span>
						<?php esc_html_e( 'Connected to IDX Broker', 'myhome-idx-broker' ); ?>
                    </strong>
                    <br>
                    <p>
                        <strong><?php esc_html_e( 'Name:', 'myhome-idx-broker' ); ?></strong>
						<?php echo esc_html( $myhome_idx_broker_account_info['clientName'] ) ?>
                    </p>
				<?php endif; ?>

				<?php if ( isset( $myhome_idx_broker_account_info['primaryEmail'] ) ) : ?>
                    <p><strong><?php esc_html_e( 'Primary E-mail:', 'myhome-idx-broker' ); ?></strong>
                        <a href="mailto:<?php echo esc_url( $myhome_idx_broker_account_info['primaryEmail'] ) ?>">
							<?php echo esc_html( $myhome_idx_broker_account_info['primaryEmail'] ) ?></a>
                    </p>
				<?php endif; ?>

				<?php if ( isset( $myhome_idx_broker_account_info['website'] ) ) : ?>
                    <p>
                        <strong><?php esc_html_e( 'Website:', 'myhome-idx-broker' ); ?></strong>
                        <a target="_blank"
                           href="<?php echo esc_url( $myhome_idx_broker_account_info['website'] ) ?>">
							<?php echo esc_html( $myhome_idx_broker_account_info['website'] ) ?>
                        </a>
                    </p>
				<?php endif; ?>

				<?php if ( isset( $myhome_idx_broker_account_info['maxAgentLevel'] ) && ! empty( $myhome_idx_broker_account_info['maxAgentLevel'] ) ) : ?>
                    <p style="margin-bottom:0;">
                        <strong><?php esc_html_e( 'Max Agents Level:', 'myhome-idx-broker' ); ?></strong>
						<?php echo esc_html( $myhome_idx_broker_account_info['maxAgentLevel'] ) ?>
                    </p>
				<?php endif; ?>
            </div>
        </div>
	<?php endif; ?>

    <div class="mh-idx-info-start" style="width:100%!important;">
        <div class="mh-idx-info-start__inner">
            <form action="<?php echo esc_url( admin_url( 'admin-post.php?action=myhome_idx_broker_save_options' ) ); ?>"
                  method="post">

				<?php wp_nonce_field( 'myhome_idx_broker_update_options', 'check_sec' ); ?>
                <Strong><span class="dashicons dashicons-admin-network" style="
     margin-right: 3px; position:relative; top:4px;"></span>
                    Your IDX Broker API Key (<a target="_blank"
                                                href="https://myhometheme.zendesk.com/hc/en-us/articles/360009178814">click
                        here to read where to find it</a>)</Strong>
                <div>
                    <input
                            id="api-key"
                            type="text"
                            name="options[api_key]"
                            value="<?php echo esc_attr( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'api_key' ) ); ?>">
                </div>
                <br>
                <button class="button button-primary">
					<?php esc_html_e( 'SAVE', 'myhome-idx-broker' ); ?>
                </button>

                <br><br>
                <div style="margin-bottom:12px">
                    <strong>
                        <span class="dashicons dashicons-admin-settings" style="
     margin-right: 3px; position:relative; top:4px;"></span><?php esc_html_e( 'Import listings assigned to your account - basic setting', 'myhome-idx-broker' ); ?>
                    </strong>
					<?php
					$myhome_idx_broker_load_style = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'load_style' );
					?>
                </div>

                <table class="form-table mh-basic-settings-table">
                    <tr>
                        <th>
                            <label for="init-status" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Initial property status', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
                            <select name="options[init_status]" id="init-status">
                                <option
                                        value="publish"
									<?php if ( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'init_status' ) == 'publish' ) : ?>
                                        selected="selected"
									<?php endif; ?>
                                >
									<?php esc_html_e( 'Publish', 'myhome-idx-broker' ); ?>
                                </option>
                                <option
                                        value="pending"
									<?php if ( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'init_status' ) == 'pending' ) : ?>
                                        selected="selected"
									<?php endif; ?>
                                >
									<?php esc_html_e( 'Pending', 'myhome-idx-broker' ); ?>
                                </option>
                                <option
                                        value="draft"
									<?php if ( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'init_status' ) == 'draft' ) : ?>
                                        selected="selected"
									<?php endif; ?>
                                >
									<?php esc_html_e( 'Draft', 'myhome-idx-broker' ); ?>
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="user" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Default user', 'myhome-idx-broker' ); ?>
                            </label>
                            It is important to set it. If listing has not agent assigned it will assign it to this user.
                        </th>
                        <td>
							<?php
							$myhome_users        = get_users();
							$myhome_default_user = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'user' );
							if ( $myhome_default_user == '' ) {
								$myhome_default_user = get_current_user_id();
							}
							?>
                            <select name="options[user]" id="user">
                                <option value="0"><?php esc_html_e( 'Not set', 'myhome-idx-broker' ); ?></option>
								<?php foreach ( $myhome_users as $myhome_user ) :
									/* @var $myhome_user \WP_User */
									?>
                                    <option
                                            value="<?php echo esc_attr( $myhome_user->ID ); ?>"
										<?php if ( $myhome_default_user == $myhome_user->ID ) : ?>
                                            selected
										<?php endif; ?>
                                    >
										<?php echo esc_html( $myhome_user->display_name ); ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="offer-type" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Offer type for "Active" listings', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
							<?php
							$myhome_idx_broker_offer_type = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'offer_type' );
							if ( $myhome_idx_broker_offer_type == '' ) {
								$myhome_idx_broker_offer_type = 149;
							}
							?>
                            <select name="options[offer_type]" id="offer-type">
                                <option value="0">
									<?php esc_html_e( 'Not set', 'myhome-idx-broker' ) ?>
                                </option>
								<?php foreach ( \MyHomeCore\Terms\Term_Factory::get_offer_types() as $offer_type ) : ?>
                                    <option
										<?php if ( $myhome_idx_broker_offer_type == $offer_type->get_ID() ) : ?>
                                            selected="selected"
										<?php endif; ?>
                                            value="<?php echo esc_attr( $offer_type->get_ID() ); ?>"
                                    >
										<?php echo esc_html( $offer_type->get_name() ); ?>
                                    </option>
								<?php endforeach; ?>

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="offer-type-sold" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Offer type for "Sold" listings', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
							<?php
							$myhome_idx_broker_offer_type_sold = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'offer_type_sold' );
							if ( $myhome_idx_broker_offer_type_sold == '' ) {
								$myhome_idx_broker_offer_type_sold = 132;
							}
							?>
                            <select name="options[offer_type_sold]" id="offer-type-sold">
                                <option value="0">
									<?php esc_html_e( 'Not set', 'myhome-idx-broker' ) ?>
                                </option>
								<?php foreach ( \MyHomeCore\Terms\Term_Factory::get_offer_types() as $offer_type ) : ?>
                                    <option
										<?php if ( $myhome_idx_broker_offer_type_sold == $offer_type->get_ID() ) : ?>
                                            selected="selected"
										<?php endif; ?>
                                            value="<?php echo esc_attr( $offer_type->get_ID() ); ?>"
                                    >
										<?php echo esc_html( $offer_type->get_name() ); ?>
                                    </option>
								<?php endforeach; ?>

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="offer-type-pending" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Offer type for "Pending" listings', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
							<?php
							$myhome_idx_broker_offer_type_pending = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'offer_type_pending' );
							if ( $myhome_idx_broker_offer_type_pending == '' ) {
								$myhome_idx_broker_offer_type_pending = 148;
							}
							?>
                            <select name="options[offer_type_pending]" id="offer-type-pending">
                                <option value="0">
									<?php esc_html_e( 'Not set', 'myhome-idx-broker' ) ?>
                                </option>
								<?php foreach ( \MyHomeCore\Terms\Term_Factory::get_offer_types() as $offer_type ) : ?>
                                    <option
										<?php if ( $myhome_idx_broker_offer_type_pending == $offer_type->get_ID() ) : ?>
                                            selected="selected"
										<?php endif; ?>
                                            value="<?php echo esc_attr( $offer_type->get_ID() ); ?>"
                                    >
										<?php echo esc_html( $offer_type->get_name() ); ?>
                                    </option>
								<?php endforeach; ?>

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="offer-type-rent" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Offer type for "Rent" listings', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
							<?php
							$myhome_idx_broker_offer_type_rent = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'offer_type_rent' );
							if ( $myhome_idx_broker_offer_type_rent == '' ) {
								$myhome_idx_broker_offer_type_rent = 3;
							}
							?>
                            <select name="options[offer_type_rent]" id="offer-type-rent">
                                <option value="0">
									<?php esc_html_e( 'Not set', 'myhome-idx-broker' ) ?>
                                </option>
								<?php foreach ( \MyHomeCore\Terms\Term_Factory::get_offer_types() as $offer_type ) : ?>
                                    <option
										<?php if ( $myhome_idx_broker_offer_type_rent == $offer_type->get_ID() ) : ?>
                                            selected="selected"
										<?php endif; ?>
                                            value="<?php echo esc_attr( $offer_type->get_ID() ); ?>"
                                    >
										<?php echo esc_html( $offer_type->get_name() ); ?>
                                    </option>
								<?php endforeach; ?>

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="offer-type-rent" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Offer type for "Open House" listings', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
							<?php
							$myhome_idx_broker_offer_type_open_house = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'offer_type_open_house' );
							if ( $myhome_idx_broker_offer_type_open_house == '' ) {
								$myhome_idx_broker_offer_type_open_house = 116;
							}
							?>
                            <select name="options[offer_type_open_house]" id="offer-type-rent">
                                <option value="0">
									<?php esc_html_e( 'Not set', 'myhome-idx-broker' ) ?>
                                </option>

								<?php foreach ( \MyHomeCore\Terms\Term_Factory::get_offer_types() as $offer_type ) : ?>
                                    <option
										<?php if ( $myhome_idx_broker_offer_type_open_house == $offer_type->get_ID() ) : ?>
                                            selected="selected"
										<?php endif; ?>
                                            value="<?php echo esc_attr( $offer_type->get_ID() ); ?>"
                                    >
										<?php echo esc_html( $offer_type->get_name() ); ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="images-limit" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Limit number of imported images', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
							<?php
							$myhome_idx_broker_images_limit = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'images_limit' );

							if ( $myhome_idx_broker_images_limit == '' ) {
								$myhome_idx_broker_images_limit = 25;
							} else {
								$myhome_idx_broker_images_limit = intval( $myhome_idx_broker_images_limit );
							}
							?>
                            <input name="options[images_limit]" id="images-limit" type="text"
                                   value="<?php echo esc_attr( $myhome_idx_broker_images_limit ); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="update_type" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Update all values during synchronization', 'myhome-idx-broker' ); ?>
                            </label>
                            <div class="mh-idx-form-basic__subheading">
								<?php esc_html_e( 'If this option is checked, every synchronization will overwrite content in the WordPress database. If it is unchecked the "Price" and "Offer Type" will be updated.',
									'myhome-idx-broker' ); ?>
                            </div>
                        </th>
                        <td>
							<?php
							if ( ! \MyHomeIDXBroker\My_Home_IDX_Broker()->options->exists( 'update_all_data' ) ) :
								$myhome_idx_broker_update_all_data = 1;
							else :
								$myhome_idx_broker_update_all_data = intval( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'update_all_data' ) );
							endif;
							?>
                            <input
                                    type="checkbox"
                                    value="1"
                                    name="options[update_all_data]"
								<?php if ( ! empty( $myhome_idx_broker_update_all_data ) ) : ?>
                                    checked="checked"
								<?php endif; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Set "featured" for all imported listings', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                    type="checkbox"
                                    value="1"
                                    name="options[import_featured]"
								<?php if ( ! empty( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'import_featured' ) ) ) : ?>
                                    checked="checked"
								<?php endif; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="mh-disable_sold_import" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Do not import sold listings', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                    id="mh-disable_sold_import"
                                    type="checkbox"
                                    value="1"
                                    name="options[disable_sold_import]"
								<?php if ( ! empty( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'disable_sold_import' ) ) ) : ?>
                                    checked="checked"
								<?php endif; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="mh-disable_sold_import" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Check this box if you wish to keep the off market listings as public', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                    id="mh-leave_off_market_as_public"
                                    type="checkbox"
                                    value="1"
                                    name="options[leave_off_market_as_public]"
								<?php if ( ! empty( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'leave_off_market_as_public' ) ) ) : ?>
                                    checked="checked"
								<?php endif; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="mh-idx-broker-listing-links" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Disable WordPress Listing Pages and use IDX Broker Feed Listing Page only', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                    id="mh-idx-broker-listing-links"
                                    type="checkbox"
                                    value="1"
                                    name="options[idx_broker_listing_links]"
								<?php if ( ! empty( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'idx_broker_listing_links' ) ) ) : ?>
                                    checked="checked"
								<?php endif; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="mh-idx-broker-agents-links" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Disable WordPress User Pages and use IDX Broker Feed User Pages only', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                    id="mh-idx-broker-agents-links"
                                    type="checkbox"
                                    value="1"
                                    name="options[idx_broker_agent_links]"
								<?php if ( ! empty( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'idx_broker_agent_links' ) ) ) : ?>
                                    checked="checked"
								<?php endif; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="mh-idx-broker-website-url" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'IDX Broker URL', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                    id="mh-idx-broker-website-url"
                                    type="text"
                                    name="options[idx_url]"
								<?php if ( ! empty( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'idx_url' ) ) ) : ?>
                                    value="<?php echo esc_attr( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'idx_url' ) ); ?>"
								<?php endif; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="mh-disable_sold_import" class="mh-idx-form-basic__heading">
								<?php esc_html_e( 'Change offer type when listing change status to "Off Market"', 'myhome-idx-broker' ); ?>
                            </label>
                        </th>
                        <td>
							<?php
							$myhome_idx_broker_offer_type_off_market = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'offer_type_off_market' );
							if ( empty( $myhome_idx_broker_offer_type_off_market ) ) {
								$myhome_idx_broker_offer_type_off_market = '0';
							}
							?>
                            <select name="options[offer_type_off_market]" id="offer-type-pending">
                                <option value="0">
									<?php esc_html_e( 'Don\'t change', 'myhome-idx-broker' ) ?>
                                </option>
								<?php foreach ( \MyHomeCore\Terms\Term_Factory::get_offer_types() as $offer_type ) : ?>
                                    <option
										<?php if ( $myhome_idx_broker_offer_type_off_market == $offer_type->get_ID() ) : ?>
                                            selected="selected"
										<?php endif; ?>
                                            value="<?php echo esc_attr( $offer_type->get_ID() ); ?>"
                                    >
										<?php echo esc_html( $offer_type->get_name() ); ?>
                                    </option>
								<?php endforeach; ?>

                            </select>
                        </td>
                    </tr>
                </table>

				<?php
				$myhome_breadcrumb_attributes = \MyHomeCore\Common\Breadcrumbs\Breadcrumbs::get_attributes();

				if ( count( $myhome_breadcrumb_attributes ) ) :
					?>
                    <br>
                    <strong><?php esc_html_e( 'Breadcrumbs fields - default values', 'myhome-idx-broker' ); ?></strong>
                    <div>
						<?php
						echo wp_kses_post( __( 'Default values are required for all fields that are used in the breadcrumbs. If you wish you can visit MyHome Theme >> Breadcrumbs to remove fields from its structure and default values will be not required.',
							'myhome-idx-broker' ) );
						?>
                    </div>
                    <br>
				<?php
				endif;
				?>

                <table class="form-table mh-basic-settings-table">
					<?php foreach ( $myhome_breadcrumb_attributes as $myhome_attribute ) : ?>
                        <tr>
                            <th>
                                <label class="mh-idx-form-basic__heading"
                                       for="attr-<?php echo esc_attr( $myhome_attribute->get_slug() ); ?>">
									<?php echo esc_html( $myhome_attribute->get_name() ); ?>
                                </label>
                            </th>
                            <td>
                                <select
                                        name="options[attributes][<?php echo esc_attr( $myhome_attribute->get_ID() ); ?>]"
                                        id="attr-<?php echo esc_attr( $myhome_attribute->get_slug() ); ?>"
                                >
									<?php foreach ( $myhome_attribute->get_terms() as $myhome_term ) : ?>
                                        <option
                                                value="<?php echo esc_attr( $myhome_term->get_ID() ); ?>"
											<?php
											$myhome_current_term_id = intval( \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get( 'attributes',
												$myhome_attribute->get_ID() ) );
											if ( $myhome_term->get_ID() == $myhome_current_term_id ) : ?>
                                                selected="selected"
											<?php endif; ?>
                                        >
											<?php echo esc_html( $myhome_term->get_name() ); ?>
                                        </option>
									<?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
					<?php endforeach; ?>
                </table>
                <br>
                <button class="button button-primary">
					<?php esc_html_e( 'SAVE', 'myhome-idx-broker' ); ?>
                </button>
            </form>
        </div>
    </div>

</div>

</div>