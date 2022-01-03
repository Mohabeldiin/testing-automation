<?php
global $myhome_estate;

if (!$myhome_estate->has_gallery()) {
    return;
}
?>
<div class="myhome-single-property-slider swiper-container">
    <div class="swiper-wrapper mh-popup-group">
        <?php foreach ($myhome_estate->get_gallery() as $myhome_image) : ?>
            <div class="swiper-slide">
                <a href="<?php echo esc_url($myhome_image['url']); ?>" class="mh-popup-group__element">
                    <img
                            src="<?php echo esc_url($myhome_image['url']); ?>"
                            alt="<?php echo esc_attr($myhome_estate->get_name()); ?>"
                    >
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="swiper-pagination"></div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
