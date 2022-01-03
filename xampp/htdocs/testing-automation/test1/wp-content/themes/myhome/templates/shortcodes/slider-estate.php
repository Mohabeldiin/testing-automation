<?php
/* @var array $myhome_slider_estate */
global $myhome_slider_estate;
/* @var \MyHomeCore\Estates\Estates $myhome_estates */
global $myhome_estates;

?>

    <div
        <?php if ($myhome_slider_estate['estates_slider_style'] === 'estate_slider_card') : ?>
            class="myhome-property-slider myhome-property-slider--default swiper-container" style="opacity: 0;"
        <?php elseif ($myhome_slider_estate['estates_slider_style'] === 'estate_slider_transparent') : ?>
            class="myhome-property-slider myhome-property-slider--transparent swiper-container" style="opacity: 0;"
        <?php elseif ($myhome_slider_estate['estates_slider_style'] === 'estate_slider_card_short') : ?>
            class="myhome-property-slider myhome-property-slider--short swiper-container" style="opacity: 0;"
        <?php endif; ?>
    >

        <div class="swiper-wrapper">
            <?php foreach ($myhome_estates as $myhome_key => $myhome_estate) : ?>
                <a
                        href="<?php echo esc_url($myhome_estate->get_link()); ?>"
                        class="swiper-slide"
                >
                    <?php if ($myhome_estate->has_image()) : ?>
                        <img
                                src="<?php echo esc_url(wp_get_attachment_image_url($myhome_estate->get_image_id(), 'full')); ?>"
                                alt="<?php echo esc_attr($myhome_estate->get_name()); ?>"
                        >
                    <?php endif; ?>

                    <?php if ($myhome_slider_estate['estates_slider_style'] === 'estate_slider_card') : ?>
                        <div class="mh-slider__card-default-wrapper">
                            <div class="mh-layout">
                                <div class="mh-slider__card-default">
                                    <h3 class="mh-slider__card-default__heading">
                                        <?php echo esc_html($myhome_estate->get_name()); ?>
                                    </h3>

                                    <div class="position-relative">
                                        <?php if (!empty($myhome_estate->get_address())) : ?>
                                            <address class="mh-slider__card-default__address">
                                                <i class="flaticon-pin"></i>
                                                <span><?php echo esc_html($myhome_estate->get_address()); ?></span>
                                            </address>
                                        <?php endif; ?>

                                        <?php if ($myhome_estate->has_price()) : ?>
                                            <?php $myhome_prices = $myhome_estate->get_prices(); ?>

                                            <div class="mh-slider__card-default__price">
                                                <?php foreach ($myhome_prices as $myhome_price) : ?>
                                                    <div
                                                        <?php if ($myhome_price->is_range()) : ?>
                                                            class="mh-price__range"
                                                        <?php endif; ?>
                                                    >
                                                        <?php echo esc_html($myhome_price->get_formatted()); ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($myhome_slider_estate['estates_slider_style'] === 'estate_slider_card_short') : ?>
                        <div class="mh-slider__card-default-wrapper">
                            <div class="mh-layout">
                                <div class="mh-slider__card-short">
                                    <h3 class="mh-slider__card-short__heading">
                                        <?php echo esc_html($myhome_estate->get_name()); ?>
                                    </h3>

                                    <?php if (!empty($myhome_estate->get_address())) : ?>
                                        <address class="mh-slider__card-short__address">
                                            <i class="flaticon-pin"></i>
                                            <span><?php echo esc_html($myhome_estate->get_address()); ?></span>
                                        </address>
                                    <?php endif; ?>

                                    <?php if ($myhome_estate->has_price()) : ?>
                                        <div class="mh-slider__card-short__price">
                                            <?php $myhome_price = $myhome_estate->get_price(); ?>
                                            <div
                                                <?php if ($myhome_price->is_range()) : ?>
                                                    class="mh-price__range"
                                                <?php endif; ?>
                                            >
                                                <?php echo esc_html($myhome_price->get_formatted()); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($myhome_slider_estate['estates_slider_style'] === 'estate_slider_transparent') : ?>
                        <div class="mh-slider__transparent">
                            <h3 class="mh-slider__transparent__title">
                                <span class="mh-slider__transparent__title__inner"><?php echo esc_html($myhome_estate->get_name()); ?></span>
                            </h3>
                            <div class="clearfix"></div>
                            <?php if (!empty($myhome_estate->get_address())) : ?>
                                <address class="mh-slider__transparent__address">
                                    <i class="flaticon flaticon-pin"></i> <?php echo esc_html($myhome_estate->get_address()); ?>
                                </address>
                            <?php endif; ?>

                            <?php if ($myhome_estate->has_price()) : ?>
                                <div class="mh-slider__transparent__price">
                                    <?php $myhome_price = $myhome_estate->get_price(); ?>
                                    <div
                                        <?php if ($myhome_price->is_range()) : ?>
                                            class="mh-price__range"
                                        <?php endif; ?>
                                    >
                                        <?php echo esc_html($myhome_price->get_formatted()); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="swiper-pagination"></div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

<?php if (!empty($myhome_slider_estate['content']) && function_exists('myhome_filter')) : ?>
    <div class="mh-slider__extra-content">
        <?php echo myhome_filter($myhome_slider_estate['content']); ?>
    </div>
<?php endif;
