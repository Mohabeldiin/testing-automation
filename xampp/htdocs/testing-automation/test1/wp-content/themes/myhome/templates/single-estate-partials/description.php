<?php
/* @var \MyHomeCore\Estates\Estate_Writer $myhome_estate */
global $myhome_estate_element;

if ( ! empty( get_the_content() ) ) :?>
    <div class="mh-estate__section mh-estate__section--description">

		<?php if ( $myhome_estate_element->has_label() ) : ?>
            <h3 class="mh-estate__section__heading"><?php echo esc_html( $myhome_estate_element->get_label() ); ?></h3>
		<?php endif; ?>

		<?php the_content(); ?>

    </div>
<?php
endif;