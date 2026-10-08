<?php
/**
 * "Page content" panel for pages built from inc/internal-pages.json.
 * The panel is pre-filled with the exported React content; once saved, the page renders the saved values.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Site path of the record behind a page or Services entry, e.g. "services/bas". */
function rjy_group_data_path( $post ) {
	$post = get_post( $post );
	if ( ! $post ) { return ''; }
	if ( 'rjy_service' === $post->post_type ) { return 'services/' . $post->post_name; }
	return 'page' === $post->post_type ? get_page_uri( $post ) : '';
}

/** Page record for a page or Services entry: exported defaults, overridden by anything saved in the panel. */
function rjy_group_page_record_for_post( $post_id ) {
	$path = rjy_group_data_path( $post_id );
	$page = $path ? rjy_group_internal_page_data( $path ) : null;
	if ( ! $page ) { return null; }
	$page['layout'] = $page['title'];
	$saved = get_post_meta( $post_id, '_rjy_page', true );
	if ( is_array( $saved ) ) { $page = array_merge( $page, $saved ); }
	$title = get_the_title( $post_id );
	if ( $title ) { $page['title'] = $title; }
	return $page;
}

/** Which panel sections a page type actually renders (see rjy_group_render_internal_page). */
function rjy_group_page_editor_sections( $type ) {
	$detail = in_array( $type, array( 'service', 'industry', 'case-study' ), true );
	return array(
		'cta' => 'legal' !== $type,
		'problems' => $detail,
		'cards' => $detail || in_array( $type, array( 'about', 'hub' ), true ),
		'process' => $detail,
		'highlights' => $detail || 'article' === $type,
		'outcomes' => $detail || 'about' === $type,
		'enquiry' => in_array( $type, array( 'service', 'industry' ), true ),
	);
}

function rjy_group_is_data_page( $post ) {
	$post = get_post( $post );
	$path = rjy_group_data_path( $post );
	return $path && (bool) rjy_group_internal_page_data( $path );
}

/** Data pages use the classic screen with the panel instead of the block editor, since the editor body is not displayed. */
function rjy_group_data_page_classic_screen( $use_block_editor, $post ) {
	return rjy_group_is_data_page( $post ) ? false : $use_block_editor;
}
add_filter( 'use_block_editor_for_post', 'rjy_group_data_page_classic_screen', 10, 2 );

function rjy_group_data_page_hide_editor() {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	if ( $post_id && rjy_group_is_data_page( $post_id ) ) { remove_post_type_support( get_post_type( $post_id ), 'editor' ); }
}
add_action( 'load-post.php', 'rjy_group_data_page_hide_editor' );

function rjy_group_page_editor_register( $post_type, $post ) {
	if ( ! in_array( $post_type, array( 'page', 'rjy_service' ), true ) || ! rjy_group_is_data_page( $post ) ) { return; }
	add_meta_box( 'rjy-page-content', __( 'Page content', 'rjy-group' ), 'rjy_group_page_editor_box', $post_type, 'normal', 'high' );
	wp_enqueue_media();
	wp_enqueue_script( 'rjy-group-page-editor', get_template_directory_uri() . '/assets/js/page-editor.js', array(), RJY_GROUP_VERSION, true );
}
add_action( 'add_meta_boxes', 'rjy_group_page_editor_register', 10, 2 );

function rjy_group_page_editor_text( $key, $label, $value, $multiline = false, $help = '' ) {
	$name = 'rjy_page[' . $key . ']';
	echo '<p class="rjy-field"><label for="rjy-' . esc_attr( $key ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';
	if ( $multiline ) {
		printf( '<textarea id="rjy-%1$s" name="%2$s" rows="3" class="large-text">%3$s</textarea>', esc_attr( $key ), esc_attr( $name ), esc_textarea( $value ) );
	} else {
		printf( '<input id="rjy-%1$s" type="text" name="%2$s" value="%3$s" class="large-text">', esc_attr( $key ), esc_attr( $name ), esc_attr( $value ) );
	}
	if ( $help ) { echo '<span class="description">' . esc_html( $help ) . '</span>'; }
	echo '</p>';
}

function rjy_group_page_editor_image( $key, $label, $value ) {
	echo '<p class="rjy-field rjy-image-field"><strong>' . esc_html( $label ) . '</strong><br>';
	printf( '<img src="%1$s" alt="" style="display:%2$s;max-width:320px;height:auto;margin:6px 0;border-radius:4px">', esc_url( $value ), $value ? 'block' : 'none' );
	printf( '<input type="hidden" name="rjy_page[%1$s]" value="%2$s">', esc_attr( $key ), esc_attr( $value ) );
	echo '<button type="button" class="button rjy-image-choose">' . esc_html__( 'Choose image', 'rjy-group' ) . '</button> <button type="button" class="button-link rjy-image-clear">' . esc_html__( 'Remove', 'rjy-group' ) . '</button></p>';
}

function rjy_group_page_editor_lines( $key, $label, $items, $help ) {
	rjy_group_page_editor_text( $key, $label, implode( "\n", (array) $items ), true, $help );
}

/** Repeatable rows, e.g. cards (title + copy) or FAQs (question + answer). */
function rjy_group_page_editor_rows( $key, $label, $rows, $columns ) {
	$render_row = static function ( $index, $row ) use ( $key, $columns ) {
		$html = '<div class="rjy-row">';
		foreach ( $columns as $column => $column_label ) {
			$name = sprintf( 'rjy_page[%s][%s][%s]', $key, $index, $column );
			$value = $row[ $column ] ?? '';
			$long = in_array( $column, array( 'copy', 'answer', 'description' ), true );
			$html .= '<label>' . esc_html( $column_label ) . ( $long ? '<textarea rows="2" name="' . esc_attr( $name ) . '">' . esc_textarea( $value ) . '</textarea>' : '<input type="text" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">' ) . '</label>';
		}
		return $html . '<button type="button" class="button-link button-link-delete rjy-row-remove">' . esc_html__( 'Remove', 'rjy-group' ) . '</button></div>';
	};
	echo '<div class="rjy-field rjy-rows" data-key="' . esc_attr( $key ) . '"><strong>' . esc_html( $label ) . '</strong>';
	echo '<input type="hidden" name="rjy_page[' . esc_attr( $key ) . '][__present]" value="1">';
	echo '<div class="rjy-row-list">';
	foreach ( array_values( (array) $rows ) as $index => $row ) { echo $render_row( $index, $row ); }
	echo '</div><template>' . $render_row( '__i__', array() ) . '</template>';
	echo '<button type="button" class="button rjy-row-add">' . esc_html__( 'Add item', 'rjy-group' ) . '</button></div>';
}

function rjy_group_page_editor_box( $post ) {
	$page = rjy_group_page_record_for_post( $post->ID );
	$sections = rjy_group_page_editor_sections( $page['type'] ?? '' );
	$image = $page['imageUrl'] ?? rjy_group_image( $page['image'] ?? 'servicesHub' );
	$support_image = $page['supportImageUrl'] ?? ( empty( $page['supportImage'] ) ? '' : rjy_group_image( $page['supportImage'] ) );
	wp_nonce_field( 'rjy_page_content', 'rjy_page_nonce' );
	?>
	<style>
		#rjy-page-content h3{margin:28px 0 8px;padding-top:16px;border-top:1px solid #dcdcde;font-size:14px}
		#rjy-page-content h3:first-of-type{border-top:0;padding-top:0;margin-top:8px}
		.rjy-field{margin:12px 0}
		.rjy-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:8px 12px;align-items:start;padding:10px 12px;margin:8px 0;background:#f6f7f7;border:1px solid #dcdcde;border-radius:4px}
		.rjy-row label{display:block;font-size:12px;color:#50575e}
		.rjy-row input,.rjy-row textarea{display:block;width:100%;margin-top:2px}
		.rjy-row .rjy-row-remove{justify-self:start}
	</style>
	<p class="description"><?php esc_html_e( 'Everything shown on this page is edited here. The page title above is the large heading in the banner.', 'rjy-group' ); ?></p>

	<h3><?php esc_html_e( 'Banner', 'rjy-group' ); ?></h3>
	<?php
	rjy_group_page_editor_text( 'eyebrow', __( 'Small label above the title', 'rjy-group' ), $page['eyebrow'] ?? '' );
	rjy_group_page_editor_text( 'description', __( 'Banner text', 'rjy-group' ), $page['description'] ?? '', true );
	rjy_group_page_editor_image( 'imageUrl', __( 'Banner image', 'rjy-group' ), $image );
	rjy_group_page_editor_text( 'imageAlt', __( 'Banner image description (alt text)', 'rjy-group' ), $page['imageAlt'] ?? '' );
	if ( $sections['cta'] ) {
		rjy_group_page_editor_text( 'ctaLabel', __( 'Button text', 'rjy-group' ), $page['ctaLabel'] ?? 'Request an Inspection' );
		rjy_group_page_editor_text( 'ctaPath', __( 'Button link', 'rjy-group' ), $page['ctaPath'] ?? '/support/request-quote', false, __( 'A site path such as /support/request-quote', 'rjy-group' ) );
	}
	?>
	<h3><?php esc_html_e( 'Introduction', 'rjy-group' ); ?></h3>
	<?php
	rjy_group_page_editor_text( 'introTitle', __( 'Heading', 'rjy-group' ), $page['introTitle'] ?? '' );
	rjy_group_page_editor_text( 'intro', __( 'Text', 'rjy-group' ), $page['intro'] ?? '', true );

	if ( $sections['problems'] ) {
		echo '<h3>' . esc_html__( 'Problems we solve', 'rjy-group' ) . '</h3>';
		rjy_group_page_editor_image( 'supportImageUrl', __( 'Section image', 'rjy-group' ), $support_image );
		rjy_group_page_editor_text( 'supportImageAlt', __( 'Section image description (alt text)', 'rjy-group' ), $page['supportImageAlt'] ?? '' );
		rjy_group_page_editor_rows( 'problems', __( 'Problems', 'rjy-group' ), $page['problems'] ?? array(), array( 'title' => __( 'Title', 'rjy-group' ), 'copy' => __( 'Text', 'rjy-group' ) ) );
	}
	if ( $sections['cards'] ) {
		echo '<h3>' . esc_html__( 'Capability cards', 'rjy-group' ) . '</h3>';
		rjy_group_page_editor_rows( 'cards', __( 'Cards', 'rjy-group' ), $page['cards'] ?? array(), array( 'title' => __( 'Title', 'rjy-group' ), 'copy' => __( 'Text', 'rjy-group' ) ) );
	}
	if ( $sections['process'] ) {
		echo '<h3>' . esc_html__( 'How we work', 'rjy-group' ) . '</h3>';
		rjy_group_page_editor_rows( 'process', __( 'Steps', 'rjy-group' ), $page['process'] ?? array(), array( 'title' => __( 'Title', 'rjy-group' ), 'copy' => __( 'Text', 'rjy-group' ) ) );
	}
	if ( $sections['highlights'] ) {
		echo '<h3>' . esc_html( 'article' === ( $page['type'] ?? '' ) ? __( 'Article sections', 'rjy-group' ) : __( 'Scope priorities', 'rjy-group' ) ) . '</h3>';
		rjy_group_page_editor_lines( 'highlights', __( 'Items', 'rjy-group' ), $page['highlights'] ?? array(), __( 'One item per line.', 'rjy-group' ) );
	}
	if ( $sections['outcomes'] ) {
		echo '<h3>' . esc_html__( 'Facility outcomes', 'rjy-group' ) . '</h3>';
		rjy_group_page_editor_lines( 'outcomes', __( 'Outcomes', 'rjy-group' ), $page['outcomes'] ?? array(), __( 'One outcome per line.', 'rjy-group' ) );
	}
	if ( $sections['enquiry'] ) {
		echo '<h3>' . esc_html__( 'Enquiry form', 'rjy-group' ) . '</h3>';
		rjy_group_page_editor_text( 'enquiryTitle', __( 'Heading', 'rjy-group' ), $page['enquiryTitle'] ?? '' );
		rjy_group_page_editor_text( 'enquiryCopy', __( 'Text', 'rjy-group' ), $page['enquiryCopy'] ?? '', true );
		rjy_group_page_editor_text( 'enquiryPrompt', __( 'Message box placeholder', 'rjy-group' ), $page['enquiryPrompt'] ?? '', true );
	}
	echo '<h3>' . esc_html__( 'Technical questions (FAQs)', 'rjy-group' ) . '</h3>';
	rjy_group_page_editor_rows( 'faqs', __( 'Questions', 'rjy-group' ), $page['faqs'] ?? array(), array( 'question' => __( 'Question', 'rjy-group' ), 'answer' => __( 'Answer', 'rjy-group' ) ) );
	echo '<h3>' . esc_html__( 'Continue exploring (related pages)', 'rjy-group' ) . '</h3>';
	rjy_group_page_editor_rows( 'related', __( 'Links', 'rjy-group' ), $page['related'] ?? array(), array( 'path' => __( 'Link (e.g. /services/hvac)', 'rjy-group' ), 'label' => __( 'Title', 'rjy-group' ), 'description' => __( 'Text', 'rjy-group' ) ) );
	echo '<h3>' . esc_html__( 'Call band', 'rjy-group' ) . '</h3>';
	rjy_group_page_editor_text( 'phoneCta', __( 'Heading next to the phone numbers', 'rjy-group' ), $page['phoneCta'] ?? '' );
}

function rjy_group_page_editor_save( $post_id ) {
	if ( ! isset( $_POST['rjy_page_nonce'], $_POST['rjy_page'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rjy_page_nonce'] ) ), 'rjy_page_content' ) ) { return; }
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) { return; }
	$input = wp_unslash( $_POST['rjy_page'] );
	$data = array();
	foreach ( array( 'eyebrow', 'imageAlt', 'ctaLabel', 'ctaPath', 'introTitle', 'supportImageAlt', 'enquiryTitle', 'phoneCta' ) as $key ) {
		if ( isset( $input[ $key ] ) ) { $data[ $key ] = sanitize_text_field( $input[ $key ] ); }
	}
	foreach ( array( 'description', 'intro', 'enquiryCopy', 'enquiryPrompt' ) as $key ) {
		if ( isset( $input[ $key ] ) ) { $data[ $key ] = sanitize_textarea_field( $input[ $key ] ); }
	}
	foreach ( array( 'imageUrl', 'supportImageUrl' ) as $key ) {
		if ( isset( $input[ $key ] ) ) { $data[ $key ] = esc_url_raw( $input[ $key ] ); }
	}
	foreach ( array( 'highlights', 'outcomes' ) as $key ) {
		if ( isset( $input[ $key ] ) ) { $data[ $key ] = array_values( array_filter( array_map( 'sanitize_text_field', preg_split( '/\r\n|\r|\n/', $input[ $key ] ) ) ) ); }
	}
	$row_fields = array( 'problems' => array( 'title', 'copy' ), 'cards' => array( 'title', 'copy' ), 'process' => array( 'title', 'copy' ), 'faqs' => array( 'question', 'answer' ), 'related' => array( 'path', 'label', 'description' ) );
	foreach ( $row_fields as $key => $fields ) {
		if ( ! isset( $input[ $key ] ) || ! is_array( $input[ $key ] ) ) { continue; }
		$rows = array();
		foreach ( $input[ $key ] as $index => $row ) {
			if ( '__present' === $index || '__i__' === $index || ! is_array( $row ) ) { continue; }
			$clean = array();
			foreach ( $fields as $field ) { $clean[ $field ] = sanitize_textarea_field( $row[ $field ] ?? '' ); }
			if ( implode( '', $clean ) !== '' ) { $rows[] = $clean; }
		}
		$data[ $key ] = $rows;
	}
	update_post_meta( $post_id, '_rjy_page', $data );
}
add_action( 'save_post_page', 'rjy_group_page_editor_save' );
add_action( 'save_post_rjy_service', 'rjy_group_page_editor_save' );
