<?php
/** 404 — mirrors NotFoundPage in src/components/page-content.tsx. */
get_header();
rjy_group_not_found_page( rjy_group_get_page( wp_parse_url( add_query_arg( array() ), PHP_URL_PATH ) ) );
get_footer();
