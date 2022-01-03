<?php
global $myhome_agent;
$myhome_agent = \MyHomeCore\Users\User::get_user_by_id();

if ( $myhome_agent->is_buyer() || ! $myhome_agent->is_accepted() ) {
	status_header( 404 );
	get_template_part( 404 );
	exit();
}

get_header();
get_template_part( 'templates/top-title' );

if ( My_Home_Theme()->layout->show_agents_on_agency_page() && $myhome_agent->is_agency() ) :
	$myhome_agency_agents = $myhome_agent->get_agents();
	if ( count( $myhome_agency_agents ) ) :
		?>
        <div class="mh-agency-agents-wrapper">
            <div class="mh-agency-agents">
                <h2><?php echo esc_html__( 'List of agents', 'myhome' ); ?></h2>
                <div class="mh-grid">
					<?php foreach ( $myhome_agency_agents as $myhome_agency_agent ) : ?>
                        <div class="mh-grid__1of4 mh-agency-agents__single">
                            <a class="mh-agency-agents__single__img-link"
                               href="<?php echo esc_url( $myhome_agency_agent->get_link() ); ?>">
                                <div class="mh-agency-agents__single__img-wrapper">
									<?php $myhome_agency_agent->image(); ?>
                                </div>
                                <h4 class="mh-agency-agents__heading"><?php echo esc_html( $myhome_agency_agent->get_name() ); ?></h4>
                            </a>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
        </div>
	<?php
	endif;
endif;

?>
<?php if ( $myhome_agent->get_listing_number() > 0 ) : ?>
    <div class="mh-layout mh-top-title-offset">
		<?php $myhome_agent->listing(); ?>
    </div>
<?php endif; ?>
<?php
get_footer();