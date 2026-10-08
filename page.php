<?php
/** Pages: React page records render through the shared compositions; other pages show editor content. */
get_header();
while ( have_posts() ) {
	the_post();
	$page_data = rjy_group_page_record_for_post( get_the_ID() );
	if ( $page_data ) {
		rjy_group_render_internal_page( $page_data, get_the_title() );
	} else {
		rjy_group_content_hero( get_bloginfo( 'name' ), get_the_title(), get_the_excerpt() );
		?><article <?php post_class( 'page-section site-container' ); ?>><div class="prose wp-editor-content"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'rjy-hero' ); } the_content(); wp_link_pages(); ?></div></article><?php
		if ( comments_open() || get_comments_number() ) { comments_template(); }
	}
}
get_footer();
