<?php
/** Single post template. */
get_header();
while ( have_posts() ) {
	the_post();
	rjy_group_content_hero( get_post_type_object( get_post_type() )->labels->singular_name, get_the_title(), get_the_date() );
	?><article <?php post_class( 'page-section site-container' ); ?>><div class="prose wp-editor-content"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'rjy-hero' ); } the_content(); wp_link_pages(); ?></div><?php the_post_navigation(); ?></article><?php
	if ( comments_open() || get_comments_number() ) { comments_template(); }
}
get_footer();
