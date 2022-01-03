<?php
$url_widgets = Thim_For_Developer::get_url_download( 'widgets' );





?>

<div class="col-md-4">
	<div class="tc-box">
		<div class="tc-box-header">
			<h2 class="box-title"><?php esc_html_e( 'Export widget_data.json', 'thim-core' ); ?></h2>
		</div>
		<div class="tc-box-body text-center">
			<a type="button" class="button button-secondary tc-button" href="<?php echo esc_url( $url_widgets ); ?>"
			   download="<?php echo esc_url( $url_widgets ); ?>"><?php esc_html_e( 'Download', 'thim-core' ); ?></a>
		</div>
	</div>
</div>
