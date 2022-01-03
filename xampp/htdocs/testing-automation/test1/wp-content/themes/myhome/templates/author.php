<?php
$myhome_author_description = get_the_author_meta( 'description' );
if ( ! empty( $myhome_author_description ) ) :
	?>
	<article class="mh-author">
		<div class="position-relative">

			<div class="mh-author__avatar">
				<?php
				$myhome_user_image = get_the_author_meta( 'agent_image' );
				if ( ! empty( $myhome_user_image ) ) :
					?>
					<img
						src="<?php echo esc_url( wp_get_attachment_image_url( $myhome_user_image, 'myhome-square-xs' ) ); ?>"
						alt="<?php echo esc_attr( get_the_author_meta( 'display_name' ) ) ?>"
						class="mh-author__avatar__image"
					>
				<?php
				else :
					echo get_avatar( get_the_author_meta( 'ID' ), 125 );
				endif;
				?>
			</div>

			<div class="mh-author__content">
				<div class="mh-author__content__inner">

					<div class="mh-author__label">
						<?php esc_html_e( 'Post author', 'myhome' ); ?>
					</div>

					<h3 class="mh-author__name">
						<?php the_author(); ?>
					</h3>

					<p>
						<?php the_author_meta( 'description' ); ?>
					</p>

				</div>
			</div>

		</div>
	</article>
<?php
endif;