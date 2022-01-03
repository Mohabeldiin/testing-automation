<?php
/* @var \MyHomeCore\Users\User $myhome_agent */

global $myhome_agent;
?>
<section class="mh-estate__agent">
    <div class="mh-widget-title">
        <h3 class="mh-widget-title__text">
            <?php if ($myhome_agent->is_buyer()) : ?>
                <span><?php echo esc_html($myhome_agent->get_name()); ?></span>
            <?php else : ?>
                <a
                        href="<?php echo esc_url($myhome_agent->get_link()); ?>"
                        title="<?php echo esc_attr($myhome_agent->get_name()); ?>"
                >
                    <span><?php echo esc_html($myhome_agent->get_name()); ?></span>
                </a>
            <?php endif; ?>
        </h3>
    </div>

    <div class="mh-estate__agent__content">

        <?php if ($myhome_agent->has_image())  : ?>
            <?php if ($myhome_agent->is_buyer()) : ?>
                <div class="mh-estate__agent__thumbnail-wrapper">
                    <img
                            src="<?php echo esc_url(wp_get_attachment_image_url($myhome_agent->get_image_id(),
                                'myhome-square-s')) ?>"
                            alt="<?php echo esc_attr($myhome_agent->get_name()); ?>"
                    >
                </div>
            <?php else : ?>
                <a
                        class="mh-estate__agent__thumbnail-wrapper"
                        href="<?php echo esc_url($myhome_agent->get_link()); ?>"
                        title="<?php echo esc_attr($myhome_agent->get_name()); ?>"
                >
                    <img
                            src="<?php echo esc_url(wp_get_attachment_image_url($myhome_agent->get_image_id(),
                                'myhome-square-s')) ?>"
                            alt="<?php echo esc_attr($myhome_agent->get_name()); ?>"
                    >
                </a>
            <?php endif; ?>
        <?php endif; ?>

        <div class="position-relative">

            <?php if ($myhome_agent->has_phone()) : ?>
                <div class="mh-estate__agent__phone">
                    <a href="tel:<?php echo esc_attr($myhome_agent->get_phone_href()); ?>">
                        <i class="flaticon-phone"></i>
                        <span><?php echo esc_html($myhome_agent->get_phone()); ?></span>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($myhome_agent->has_email()) : ?>
                <div class="mh-estate__agent__email">
                    <a href="mailto:<?php echo esc_attr($myhome_agent->get_email()); ?>">
                        <i class="flaticon-mail-2"></i><?php echo esc_html($myhome_agent->get_email()); ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php foreach ($myhome_agent->get_fields() as $myhome_agent_field) : ?>
                <?php
                if ($myhome_agent_field->get_value() == '') {
                    continue;
                }
                ?>
                <div class="mh-estate__agent__more">
                    <strong>
                        <?php echo esc_html($myhome_agent_field->get_name()); ?>:
                    </strong>
                    <?php if ($myhome_agent_field->is_link()) : ?>
                        <a href="<?php echo esc_url($myhome_agent_field->get_link()); ?>">
                            <?php echo esc_html($myhome_agent_field->get_value()); ?>
                        </a>
                    <?php else :
                        echo esc_html($myhome_agent_field->get_value());
                    endif; ?>
                </div>
            <?php endforeach; ?>

            <?php if ($myhome_agent->has_social_icons()) : ?>
                <div class="mh-estate__agent__social-icons">
                    <?php foreach ($myhome_agent->get_social_icons() as $myhome_social_icon) :
                        $myhome_social_icon->display();
                    endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ( ! $myhome_agent->is_buyer()) : ?>
                <a
                        href="<?php echo esc_url($myhome_agent->get_link()); ?>"
                        title="<?php echo esc_attr($myhome_agent->get_name()); ?>"
                        class="mdl-button mdl-js-button mdl-button--raised mdl-button--primary mdl-button--full-width"
                >
                    <?php printf(esc_html__('All by %s', 'myhome'), $myhome_agent->get_name()); ?>
                </a>
            <?php endif; ?>

        </div>
    </div>
</section>