<?php
/* @var array $myhome_slider */
global $myhome_slider;
global $myhome_search;
?>

<?php if (function_exists('vc_is_inline') && vc_is_inline()) : ?>
    <div class="mh-rs-search mh-rs-search--middle <?php echo esc_attr($myhome_search['class']); ?>">
        <style>
            #myhome-search-form-submit .mh-search--button {
                top: <?php echo esc_html( $myhome_search['search_offset'] ); ?>px !important;
            }

            @media (max-width: 778px) {
                #myhome-search-form-submit .mh-search--button {
                    top: <?php echo esc_html( $myhome_search['search_offset_mobile'] ); ?>px !important;
                }
            }
        </style>

        <div class="mc-rs-slider-front-end-editor">
            <div class="mc-rs-slider-front-end-editor__text"><?php esc_html_e( 'Slider Placeholder', 'myhome' ) ?></div>
        </div>
        <?php

        if (!empty($myhome_slider['content']) && function_exists('myhome_filter')) : ?>
            <div>
                <?php echo myhome_filter($myhome_slider['content']); ?>
            </div>
        <?php endif; ?>
    </div>
<?php else : ?>
    <div class="mh-rs-search mh-rs-search--middle <?php echo esc_attr($myhome_search['class']); ?>">
        <style>
            #myhome-search-form-submit .mh-search--button {
                top: <?php echo esc_html( $myhome_search['search_offset'] ); ?>px !important;
            }

            @media (max-width: 778px) {
                #myhome-search-form-submit .mh-search--button {
                    top: <?php echo esc_html( $myhome_search['search_offset_mobile'] ); ?>px !important;
                }
            }
        </style>
        <?php
        if (!empty($myhome_slider['slider']) && function_exists('putRevSlider')) :
            putRevSlider($myhome_slider['slider']);
        endif;

        if (!empty($myhome_slider['content']) && function_exists('myhome_filter')) : ?>
            <div>
                <?php echo myhome_filter($myhome_slider['content']); ?>
            </div>
        <?php endif; ?>
    </div>
<?php
endif;