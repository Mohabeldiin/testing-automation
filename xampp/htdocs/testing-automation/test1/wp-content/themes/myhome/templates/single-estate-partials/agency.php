<?php
/* @var \MyHomeCore\Users\User $myhome_agency */
global $myhome_agency;
if ( ! $myhome_agency instanceof \MyHomeCore\Users\User ) {
	return;
}
?>
<section class="mh-estate__agent">
	<div class="mh-widget-title">
		<h3 class="mh-widget-title__text">
			<a
				href="<?php echo esc_url( $myhome_agency->get_link() ); ?>"
				title="<?php echo esc_attr( $myhome_agency->get_name() ); ?>"
			>
				<span><?php echo esc_html( $myhome_agency->get_name() ); ?></span>
			</a>
		</h3>
	</div>

	<div class="mh-estate__agent__content">

		<?php if ( $myhome_agency->has_image() )  : ?>
			<a class="mh-estate__agent__thumbnail-wrapper"
			   href="<?php echo esc_url( $myhome_agency->get_link() ); ?>"
			   title="<?php echo esc_attr( $myhome_agency->get_name() ); ?>">
				<img
					src="<?php echo esc_url( wp_get_attachment_image_url( $myhome_agency->get_image_id(), 'myhome-square-s' ) ) ?>"
					alt="<?php echo esc_attr( $myhome_agency->get_name() ); ?>"
				>
			</a>
		<?php endif; ?>

		<div class="position-relative">

			<?php if ( $myhome_agency->has_phone() ) : ?>
				<div class="mh-estate__agent__phone">
					<a href="tel:<?php echo esc_attr( $myhome_agency->get_phone_href() ); ?>">
						<i class="flaticon-phone"></i>
						<span><?php echo esc_html( $myhome_agency->get_phone() ); ?></span>
					</a>
				</div>
			<?php endif; ?>

			<?php if ( $myhome_agency->has_email() ) : ?>
				<div class="mh-estate__agent__email">
					<a href="mailto:<?php echo esc_attr( $myhome_agency->get_email() ); ?>">
						<i class="flaticon-mail-2"></i><?php echo esc_html( $myhome_agency->get_email() ); ?>
					</a>
				</div>
			<?php endif; ?>

			<?php foreach ( $myhome_agency->get_fields() as $myhome_agency_field ) : ?>
				<?php
				if ( $myhome_agency_field->get_value() == '' ) {
					continue;
				}
				?>
				<div class="mh-estate__agent__more">
					<strong>
						<?php echo esc_html( $myhome_agency_field->get_name() ); ?>:
					</strong>
					<?php if ( $myhome_agency_field->is_link() ) : ?>
						<a href="<?php echo esc_url( $myhome_agency_field->get_link() ); ?>">
							<?php echo esc_html( $myhome_agency_field->get_value() ); ?>
						</a>
					<?php else :
						echo esc_html( $myhome_agency_field->get_value() );
					endif; ?>
				</div>
			<?php endforeach; ?>

			<?php if ( $myhome_agency->has_social_icons() ) : ?>
				<div class="mh-estate__agent__social-icons">
					<?php foreach ( $myhome_agency->get_social_icons() as $myhome_social_icon ) :
						$myhome_social_icon->display();
					endforeach; ?>
				</div>
			<?php endif; ?>

			<a
				href="<?php echo esc_url( $myhome_agency->get_link() ); ?>"
				title="<?php echo esc_attr( $myhome_agency->get_name() ); ?>"
				class="mdl-button mdl-js-button mdl-button--raised mdl-button--primary mdl-button--full-width"
			>
				<?php printf( esc_html__( 'All by %s', 'myhome' ), $myhome_agency->get_name() ); ?>
			</a>

		</div>
	</div>
</section>