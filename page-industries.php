<?php
/**
 * Template Name: RJY Industries
 */

get_header();
while ( have_posts() ) {
	the_post();
	$page_data = rjy_group_page_record_for_post( get_the_ID() );
	if ( $page_data ) { rjy_group_render_internal_page( $page_data, get_the_title() ); }
}
get_footer();
