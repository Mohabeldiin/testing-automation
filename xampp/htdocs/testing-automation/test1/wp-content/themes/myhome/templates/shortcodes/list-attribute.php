<?php
global $myhome_list_attribute;
/* @var \MyHomeCore\Terms\Term[] $myhome_mosaic */
?>

<div class="mh-list-attribute">
    <?php foreach ( $myhome_list_attribute as $myhome_term ) : ?>
        <div class="mh-list-attribute__item">
            <div class="mh-list-attribute__item__content">
                <a class="mh-list-attribute__image__link"
                   href="<?php echo esc_url( $myhome_term->get_link() ); ?>"
                   title="<?php echo esc_attr( $myhome_term->get_name() ); ?>">
                        <?php
                        if ( $myhome_term->has_image() ) :
                            \MyHomeCore\Common\Image::the_image(
                                $myhome_term->get_image_id(),
                                'standard',
                                $myhome_term->get_name()
                            );
                        endif;
                        ?>
                </a>
                <a class="mh-list-attribute__heading__link"
                   href="<?php echo esc_url( $myhome_term->get_link() ); ?>"
                   title="<?php echo esc_attr( $myhome_term->get_name() ); ?>">
                    <h3>
                        <?php echo esc_attr( $myhome_term->get_name() ); ?>
                    </h3>
                </a>
            </div>
        </div>
    <?php endforeach ?>
</div>