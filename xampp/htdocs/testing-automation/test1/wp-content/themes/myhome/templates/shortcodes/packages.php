<?php
global $myhome_packages;
?>

<div class="mh-pricing-table mh-pricing-table--<?php echo esc_attr( $myhome_packages['number'] ); ?>-col">
	<div class="mh-pricing-table__inner">
		<?php foreach ( $myhome_packages['products'] as $product ) : ?>
		<?php /* @var $product \MyHomeCore\Payments\WC_Product_Property_Package */ ?>
		<div class="mh-pricing-table__column">
			<div class="mh-pricing-table__column__inner">
				<div class="mh-pricing-table__row mh-pricing-table__row--name">
					<?php echo esc_html( $product->get_name() ); ?>

                    <?php if ($product->is_one_time()) : ?>
                        <div class="mh-pricing-table__row__one-time-heading">
                            <div class="mh-pricing-table__row__one-time-heading__inner">
                                <?php esc_html_e( 'One time offer', 'myhome' );?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
				<div class="mh-pricing-table__row mh-pricing-table__row--price">
					<?php if ( empty( $product->get_price() ) ) : ?>
						<?php esc_html_e( 'Free', 'myhome' ); ?>
					<?php else : ?>
						<?php echo wp_kses_post( $product->get_price_html() ); ?>
					<?php endif; ?>
					<!--<span class="mh-pricing-table__price-period"></span>!-->
				</div>
				<div class="mh-pricing-table__row">
					<?php esc_html_e( 'Properties number:', 'myhome' ); ?><?php echo esc_html( $product->get_properties_number() ); ?>
				</div>
				<div class="mh-pricing-table__row">
					<?php esc_html_e( 'Featured number:', 'myhome' ); ?><?php echo esc_html( $product->get_featured_number() ); ?>
				</div>
				<?php $product->is_virtual() ?>

                <?php if ( $product->can_current_user_buy() ) : ?>
                    <div class="mh-pricing-table__row mh-pricing-table__row--button">
                        <a
                            href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
                            class="mdl-button mdl-button--lg mdl-js-button mdl-button--raised mdl-button--primary"
                        >
                            <i class="fas fa-shopping-cart"></i> <?php esc_html_e( 'Buy now', 'myhome' ); ?>
                        </a>
                    </div>
				<?php else: ?>
                    <div class="mh-pricing-table__row mh-pricing-table__row--sold">
                        <?php esc_html_e( 'It is one time offer.', 'myhome' );?>
                        <br>
                        <?php esc_html_e( 'You have already used it.', 'myhome' );?>
                    </div>
                <?php endif; ?>

			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>