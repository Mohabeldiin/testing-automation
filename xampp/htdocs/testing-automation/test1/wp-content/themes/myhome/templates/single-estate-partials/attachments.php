<?php
/* @var \MyHomeCore\Estates\Elements\Attachments_Estate_Element $myhome_estate_element */
global $myhome_estate_element;

if ( $myhome_estate_element->has_attachments() ) :
	?>
	<section class="mh-estate__section mh-estate__section--attachments">

		<?php if ( $myhome_estate_element->has_label() ) : ?>
			<h3 class="mh-estate__section__heading"><?php echo esc_html( $myhome_estate_element->get_label() ); ?></h3>
		<?php endif; ?>

		<div class="mh-estate__list">
			<div class="mh-estate__list__inner">
				<?php foreach ( $myhome_estate_element->get_attachments() as $myhome_attachment ) : ?>
                    <?php
                        $myhome_attachment_ext = pathinfo( esc_url( $myhome_attachment->get_file() ), PATHINFO_EXTENSION);
                    ?>
					<li
						class="mh-estate__list__element mh-estate__list__element--attachment mh-attachment__type-<?php echo esc_attr( $myhome_attachment->get_type() ); ?>"
					>

						<a
							href="<?php echo esc_url( $myhome_attachment->get_file() ); ?>"
							title="<?php echo esc_attr( $myhome_attachment->get_label() ); ?>"
							target="_blank"
						>
                            <?php if ( $myhome_attachment_ext == 'pdf' ): ?>
                                <i class="far fa-file-pdf"></i>
                            <?php endif; ?>

                            <?php if ( $myhome_attachment_ext == 'docx' ||  $myhome_attachment_ext == 'doc' ): ?>
                                <i class="far fa-file-word"></i>
                            <?php endif; ?>

							<?php echo esc_attr( $myhome_attachment->get_label() ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php
endif;