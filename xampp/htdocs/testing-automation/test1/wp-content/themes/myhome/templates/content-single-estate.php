<?php
/* @var \MyHomeCore\Estates\Estate_Writer $myhome_estate */
global $myhome_estate;
global $myhome_agent;
$myhome_agent = $myhome_estate->agent;

if ( $myhome_estate->get_slider_name() == \MyHomeCore\Estates\Estate_Writer::SLIDER_PHOTOS ) :

	if ( $myhome_estate->has_gallery() ) :
		get_template_part( 'templates/single-estate-partials/slider' );
	else :
		if ( $myhome_estate->has_image() ):?>
            <div class="mh-estate__huge-image">
                <a class="mh-popup"
                   href="<?php echo esc_url( wp_get_attachment_image_url( $myhome_estate->get_image_id(), 'full' ) ); ?>"
                   title="<?php the_title_attribute(); ?>">
                    <div
                            class="mh-estate__huge-image__single mh-background-cover"
                            style="background-image: url('<?php echo esc_url( wp_get_attachment_image_url( $myhome_estate->get_image_id(), 'full' ) ); ?>');"
                    >
                    </div>
                </a>
            </div>
		<?php else: ?>
            <div class="mh-estate__huge-no-image">
                <br>
            </div>
		<?php endif ?>

	<?php endif;

endif; ?>
<?php if ( $myhome_estate->is_gallery_type() ) : ?>

    <div
		<?php if ( $myhome_estate->has_background_image() ) : ?>
            class="mh-top-title mh-top-title--single-estate mh-top-title--image-background"
            style="background-image: url('<?php echo esc_url( wp_get_attachment_image_url( $myhome_estate->get_image_id(), 'full' ) ); ?>');"
		<?php else : ?>
            class="mh-top-title mh-top-title--single-estate"
		<?php endif; ?>
    >
        <div class="mh-layout">
            <h1 class="mh-top-title__heading">
				<?php echo esc_html( get_the_title() ); ?>
            </h1>

			<?php if ( $myhome_estate->has_address() ) : ?>
                <div class="small-text">
                    <a href="#myhome-estate-map"><i class="flaticon-pin"></i></a>
                    <span>
						<?php echo esc_html( $myhome_estate->get_address() ); ?>
					</span>
                </div>
			<?php endif; ?>

        </div>
    </div>

<?php elseif ( $myhome_estate->is_slider_type() ): ?>

    <div class="position-relative">
        <div class="mh-slider-single">
            <div class="mh-slider-single__content">
                <div class="mh-layout">
                    <div class="mh-slider-single__top">
                        <div class="mh-slider-single__name-price">
                            <h1 class="mh-slider-single__name">
								<?php echo esc_html( get_the_title() ); ?>
                            </h1>


                            <div class="mh-slider-single__price">
                                <div>
									<?php if ( $myhome_estate->has_price() ) : ?>
										<?php foreach ( $myhome_estate->get_prices() as $myhome_price ) : ?>
                                            <div
												<?php if ( $myhome_price->is_range() ) : ?>class="mh-price__range" <?php endif; ?>>
												<?php echo esc_html( $myhome_price->get_formatted() ); ?>
                                            </div>
										<?php endforeach; ?>
									<?php else : ?>
                                        <div>
											<?php echo esc_html( \MyHomeCore\Attributes\Price_Attribute_Options_Page::get_default_value() ); ?>
                                        </div>
									<?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="mh-slider-single__bottom">

						<?php if ( $myhome_estate->has_address() ) : ?>
                            <div class="mh-slider-single__address">
                                <i class="flaticon-pin"></i>
                                <span>
									<?php echo esc_html( $myhome_estate->get_address() ); ?>
								</span>
                            </div>
						<?php endif; ?>

						<?php if ( $myhome_estate->agent->has_phone() ) : ?>
                            <div class="mh-slider-single__phone">
                                <a href="tel:<?php echo esc_attr( $myhome_estate->agent->get_phone_href() ); ?>">
                                    <i class="flaticon-phone"></i>
                                    <span><?php echo esc_html( $myhome_estate->agent->get_phone() ); ?></span>
                                </a>
                            </div>
						<?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php
if ( $myhome_estate->has_breadcrumbs() || $myhome_estate->has_back_to_results_button() ) :
	global $myhome_breadcrumbs;
	$myhome_breadcrumbs = new \MyHomeCore\Common\Breadcrumbs\Breadcrumbs();
	get_template_part( 'templates/breadcrumbs' );
endif;
?>

    <div class="mh-layout position-relative <?php echo esc_attr( $myhome_estate->get_attribute_classes() ); ?>">
        <div class="<?php echo esc_attr( $myhome_estate->get_container_class() ); ?>">

            <div class="mh-display-mobile">
                <div class="position-relative">
                    <div class="mh-estate__details">

						<?php if ( ( $myhome_estate->has_gallery() || $myhome_estate->has_image() ) && $myhome_estate->is_gallery_type() ) : ?>

                            <div class="mh-estate__details__price">
                                <div>
									<?php if ( $myhome_estate->has_price() ) : ?>

										<?php foreach ( $myhome_estate->get_prices() as $myhome_price ) : ?>
                                            <div><?php echo esc_html( $myhome_price->get_formatted() ); ?></div>
										<?php endforeach; ?>
									<?php else : ?>
                                        <div><?php echo esc_html( \MyHomeCore\Attributes\Price_Attribute_Options_Page::get_default_value() ); ?></div>
									<?php endif; ?>
                                </div>
                            </div>

							<?php if ( $myhome_estate->agent->has_phone() ) : ?>
                                <div class="mh-estate__details__phone">
                                    <a href="tel:<?php echo esc_attr( $myhome_estate->agent->get_phone_href() ); ?>">
                                        <i class="flaticon-phone"></i> <?php echo esc_html( $myhome_estate->agent->get_phone() ); ?>
                                    </a>
                                </div>
							<?php endif; ?>

						<?php endif; ?>

						<?php if ( $myhome_estate->has_map(true) ) : ?>
                            <div class="mh-estate__details__map">
                                <a href="#map" class="smooth">
                                    <i class="flaticon-pin"></i> <?php esc_html_e( 'See Map', 'myhome' ); ?>
                                </a>
                            </div>
						<?php endif; ?>

						<?php if ( $myhome_estate->is_favorite_enabled() || $myhome_estate->is_compare_enabled() ) : ?>
                            <div class="mh-estate__add-to">
								<?php if ( $myhome_estate->is_favorite_enabled() ) : ?>
                                    <add-to-favorite-single
                                            class="myhome-add-to-favorite-single"
                                            :property-id="<?php echo esc_attr( $myhome_estate->get_ID() ); ?>"
                                    ></add-to-favorite-single>
								<?php endif; ?>

								<?php if ( $myhome_estate->is_compare_enabled() ) : ?>
                                    <compare-button-single
                                            class="myhome-compare-button-single"
                                            :estate="<?php echo esc_attr( json_encode( $myhome_estate->get_data() ) ); ?>"
                                    ></compare-button-single>
								<?php endif; ?>
                            </div>
						<?php endif; ?>

						<?php if ( $myhome_estate->has_sidebar_elements() ) : ?>
							<?php foreach ( $myhome_estate->get_sidebar_elements() as $myhome_sidebar_element ) : ?>
                                <div class="mh-estate__cta">

									<?php if ( $myhome_sidebar_element->has_link() ) : ?>
                                    <a
                                            href="<?php echo esc_url( $myhome_sidebar_element->get_link() ); ?>"
                                            title="<?php echo esc_attr( $myhome_sidebar_element->get_text() ); ?>"
                                            target="_blank"
                                    >
										<?php endif; ?>

										<?php if ( $myhome_sidebar_element->has_image() ) : ?>
                                            <img src="<?php echo esc_url( $myhome_sidebar_element->get_image() ); ?>);">
										<?php endif; ?>

										<?php if ( $myhome_sidebar_element->has_text() ) : ?>
                                            <div class="mh-estate__cta__text">
												<?php echo esc_html( $myhome_sidebar_element->get_text() ); ?>
                                            </div>
										<?php endif; ?>

										<?php if ( $myhome_sidebar_element->has_link() ) : ?>
                                    </a>
								<?php endif; ?>
                                </div>
							<?php endforeach; ?>
						<?php endif; ?>

                    </div>
                </div>
            </div>

			<?php
			/* @var \MyHomeCore\Estates\Elements\Estate_Element $myhome_estate_element */
			global $myhome_estate_element;
			foreach ( $myhome_estate->get_elements() as $element ) :
				$myhome_estate_element = $element;
				get_template_part( $element->get_template() );
			endforeach;
			?>

			<?php comments_template(); ?>

        </div>

		<?php if ( $myhome_estate->has_sidebar() ) : ?>
			<?php get_template_part( 'templates/single-estate-partials/sidebar' ); ?>
		<?php endif; ?>
    </div>

<?php if ( $myhome_estate->has_map( true ) ) : ?>
	<?php $myhome_estate->map(); ?>
<?php endif; ?>