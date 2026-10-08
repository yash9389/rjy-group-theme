<?php
/** Main template. */
get_header();
rjy_group_content_hero( __( 'RJY Group', 'rjy-group' ), is_home() ? single_post_title( '', false ) : __( 'Latest Updates', 'rjy-group' ) );
?><section class="page-section site-container"><div class="article-grid"><?php if ( have_posts() ) { while ( have_posts() ) { the_post(); get_template_part( 'template-parts/content', get_post_type() ); } } else { get_template_part( 'template-parts/content', 'none' ); } ?></div><?php the_posts_pagination(); ?></section><?php
get_footer();
