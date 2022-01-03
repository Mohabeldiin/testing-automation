<?php
/* @var \MyHomeCore\Estates\Estate_Writer $myhome_estate */
global $myhome_estate;

if ($myhome_estate->is_slider_type()) {
    return;
}

if ($myhome_estate->has_gallery()) : ?>

    <div
        <?php if ($myhome_estate->is_gallery_auto_height()) : ?>
            class="swiper-container swiper-container--single swiper-container--auto_height"
        <?php else : ?>
            class="swiper-container swiper-container--single swiper-container--regular"
        <?php endif; ?>
    >
        <div class="swiper-wrapper mh-popup-group">
            <?php foreach ($myhome_estate->get_gallery() as $myhome_image) : ?>
                <div class="swiper-slide">
                    <a href="<?php echo esc_url($myhome_image['url']); ?>" class="mh-popup-group__element">
                        <?php
                        $myhome_gallery_image = wp_get_attachment_image_url($myhome_image['ID'], 'large');
                        if (empty($myhome_gallery_image)) {
                            $myhome_gallery_image = $myhome_image['url'];
                        }
                        ?>
                        <img src="<?php echo esc_url($myhome_gallery_image); ?>"
                             alt="<?php echo esc_attr($myhome_image['alt']); ?>">
                    </a>
                </div>
            <?php endforeach; ?>

        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <div class="swiper-container swiper-container--single-thumbs">
        <div class="swiper-wrapper">
            <?php foreach ($myhome_estate->get_gallery() as $myhome_image) : ?>
                <div class="swiper-slide">
                    <div class="swiper-slide__inner"
                         style="background-image:url(<?php echo esc_url(wp_get_attachment_image_url($myhome_image['ID'], 'myhome-standard-xs')); ?>);"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php elseif ($myhome_estate->has_image()) : ?>
    <div class="mh-estate__main-image">
        <a class="mh-popup" href="<?php echo wp_get_attachment_image_url($myhome_estate->get_image_id(), 'full'); ?>"
           title="<?php the_title_attribute(); ?>"
        >
            <img src="<?php echo wp_get_attachment_image_url($myhome_estate->get_image_id(), 'full'); ?>"
                 alt="<?php the_title_attribute(); ?>">
        </a>
    </div>
<?php
endif;
