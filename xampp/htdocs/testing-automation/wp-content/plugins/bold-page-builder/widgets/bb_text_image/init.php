<?php

if ( ! class_exists( 'BB_Text_Image' ) ) {

	// TEXT IMAGE

	class BB_Text_Image extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_bb_text_image', // Base ID
				esc_html__( 'BB Text Image', 'bold-builder' ), // Name
				array( 'description' => esc_html__( 'Text with image.', 'bold-builder' ) ) // Args
			);
			
			add_action( 'admin_head', [ $this, 'bb_admin_head' ] );
			add_action( 'customize_controls_head', [ $this, 'bb_admin_head' ] );
		}
		
		public function bb_admin_head() {
			$cs = get_current_screen();
			if ( $cs->base != 'widgets' && $cs->base != 'customize' ) return;
			?>
			<script>
				(function( $ ) {
					
					var bt_bb_text_image_init = function() {

						$( '.bt_bb_text_image_form' ).each(function() {
							
							$( this ).find( '.bt_bb_dialog_select_images_button' ).off( 'click' );
							$( this ).find( '.bt_bb_dialog_select_images_button' ).on( 'click', function( e ) {

								var key = bt_bb_get_key();
								
								var this_container = $( this ).closest( '.bt_bb_text_image_form' ).find( '.bt_bb_dialog_image_container' );
								
								var multiple_option = 'add';
								
								var insertImage = wp.media.controller.Library.extend({
									defaults: _.defaults({
										id: key,
										title: window.bt_bb_text.select_images,
										allowLocalEdits: false,
										displaySettings: false,
										displayUserSettings: false,
										multiple: multiple_option,
										type: 'image'
									}, wp.media.controller.Library.prototype.defaults )
								});

								var frame = wp.media({
									button: { text: window.bt_bb_text.select },
									state: key,
									states: [
										new insertImage()
									]
								});

								// close
								frame.on( 'close', function() {
								
									var selection = frame.state( key ).get( 'selection' );
									
									var content = '';
									var ids = ''
									selection.each(function( img ) {
										if ( img.attributes.sizes !== undefined ) {
											var img_url = '';
											if ( img.attributes.sizes.thumbnail !== undefined ) {
												img_url = img.attributes.sizes.thumbnail.url;
											} else {
												img_url = img.attributes.sizes.full.url;
											}
											ids += img.id + ',';
											window.bt_bb.cache[ img.id ] = {};
											window.bt_bb.cache[ img.id ].url = img_url;
											window.bt_bb.cache[ img.id ].title = img.attributes.title;
											content += '<div class="bt_bb_sortable_item" data-id="' + img.id + '" style="background-image:url(\'' + img_url + '\');"><i class="fa fa-times"></i></div>';

											this_container.html( content );
										}
									});

									ids = ids.slice( 0, -1 );

									this_container.closest( '.bt_bb_text_image_form' ).find( '.bt_bb_text_image_ids' ).val( ids );
									
									this_container.closest( '.bt_bb_text_image_form' ).find( 'input[type="text"]' ).trigger( 'change' );

								});
								
								// open
								frame.on( 'open', function() {
									var selection = frame.state( key ).get( 'selection' );
									
									this_container.find( '.bt_bb_sortable_item' ).each(function() {
										var attachment = wp.media.attachment( $( this ).data( 'id' ) );
										selection.add( attachment );
									});
								});

								frame.open();
							});

							$( this ).find( '.bt_bb_sortable_item' ).each(function() {
								setTimeout( window.bt_bb_sortable_background, 60, $( this ).data( 'id' ) );
							});
							
							$( this ).find( '.bt_bb_dialog_image_container' ).sortable({
								cursor: 'move',
								update: function( event, ui ) {

									var ids = '';
									$( this ).find( '.bt_bb_sortable_item' ).each(function() {
										ids += $( this ).data( 'id' ) + ',';
									});
									ids = ids.slice( 0, -1 );

									$( this ).closest( '.bt_bb_text_image_form' ).find( '.bt_bb_text_image_ids' ).val( ids );
									
									$( this ).closest( '.bt_bb_text_image_form' ).find( 'input[type="text"]' ).trigger( 'change' );
								}
							});
							
							$( this ).find( '.bt_bb_dialog_image_container' ).off( 'click' );
							$( this ).find( '.bt_bb_dialog_image_container' ).on( 'click', '.fa-times', function( e ) {

								var this_container = $( this ).closest( '.bt_bb_dialog_image_container' );
								$( this ).parent().remove();
								var ids = '';
								this_container.find( '.bt_bb_sortable_item' ).each(function() {
									ids += $( this ).data( 'id' ) + ',';
								});
								ids = ids.slice( 0, -1 );

								this_container.closest( '.bt_bb_text_image_form' ).find( '.bt_bb_text_image_ids' ).val( ids );
								
								this_container.closest( '.bt_bb_text_image_form' ).find( 'input[type="text"]' ).trigger( 'change' );
								
							});
							
							$( this ).find( '.bt_bb_dialog_image_container' ).disableSelection();
						
						});
					}
					
					$(function() { // ready - As of jQuery 3.0, only this syntax is recommended
						bt_bb_text_image_init();
					});
					
					$( document ).on( 'widget-added widget-updated', bt_bb_text_image_init );

				}( jQuery ));
			</script>
		<?php }

		public function widget( $args, $instance ) {

			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
				
			if ( $instance['ids'] != '' ) {
				echo do_shortcode( '[bt_bb_slider images="' . $instance['ids'] . '" show_dots="hide" height="auto" auto_play="3000"]' );
			}
			echo '<div class="widget_sp_image-description">' . wpautop( $instance['text'] ) . '</div>';
			
			echo $args['after_widget'];
		}

		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$ids = ! empty( $instance['ids'] ) ? $instance['ids'] : '';
			$text = ! empty( $instance['text'] ) ? $instance['text'] : '';
			?>
			<div class="bt_bb_text_image_form">
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bold-builder' ); ?></label> 
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'ids' ) ); ?>"><?php _e( 'Image IDs:', 'bold-builder' ); ?></label>
					<input class="widefat bt_bb_text_image_ids" id="<?php echo esc_attr( $this->get_field_id( 'ids' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ids' ) ); ?>" type="hidden" value="<?php echo esc_attr( $ids ); ?>">
					<div class="bt_bb_dialog_image_container">
						<?php
							$img_arr = explode( ',', $ids );
							if ( $img_arr[0] != '' ) {
								for ( $j = 0; $j < count( $img_arr ); $j++ ) {
									echo '<div class="bt_bb_sortable_item" data-id="' . $img_arr[ $j ] . '"><i class="fa fa-times"></i></div>';
								}
							}
						?>
					</div>
					<div class="bt_bb_dialog_inline_buttons bt_bb_left">
						<input type="button" class="bt_bb_dialog_select_images_button button button-small" value="<?php _e( 'Select', 'bold-builder' ); ?>">
					</div>
				</p>			
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Text:', 'bold-builder' ); ?></label> 
					<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_attr( $text ); ?></textarea>
				</p>
			</div>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['ids'] = ( ! empty( $new_instance['ids'] ) ) ? strip_tags( $new_instance['ids'] ) : '';
			$instance['text'] = ( ! empty( $new_instance['text'] ) ) ? strip_tags( $new_instance['text'] ) : '';

			return $instance;
		}
	}	
}