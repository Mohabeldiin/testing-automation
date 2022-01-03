<?php
global $myhome_idx_omnibar_show_fields;
?>
<div class="myhome-idx-omnibar <?php if ( $myhome_idx_omnibar_show_fields ) : ?>myhome-idx-omnibar--additional_fields<?php endif; ?>">
	<?php echo do_shortcode( '[idx-omnibar styles="1" extra="0" min_price="0" ]' ); ?>
</div>