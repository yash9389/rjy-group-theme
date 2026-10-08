<?php
/** Contact Form 7 integration for the Request an Inspection / Get a Quote page. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Page paths whose enquiry form is rendered by Contact Form 7 instead of the theme handler. */
function rjy_group_cf7_form_paths() {
	return array( '/support/request-quote' );
}

/** ID of the CF7 form saved in the rjy_quote_cf7_form_id option, or 0 when CF7 or the form is unavailable. */
function rjy_group_cf7_quote_form_id() {
	if ( ! function_exists( 'wpcf7_contact_form' ) ) {
		return 0;
	}
	$form_id = (int) get_option( 'rjy_quote_cf7_form_id', 0 );
	return $form_id && wpcf7_contact_form( $form_id ) ? $form_id : 0;
}

/** Output the CF7 quote form when it applies to $page; returns false so the caller falls back to the theme form. */
function rjy_group_render_cf7_form( $page ) {
	$form_id = rjy_group_cf7_quote_form_id();
	if ( ! $form_id || ! in_array( $page['path'] ?? '', rjy_group_cf7_form_paths(), true ) ) {
		return false;
	}
	echo do_shortcode( sprintf( '[contact-form-7 id="%d" html_id="enquiry" html_class="contact-form"]', $form_id ) );
	return true;
}

// The form template carries the theme's own grid markup, so CF7 must not add <p>/<br> tags.
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/** [rjy_submit "Label"] renders the theme's primary button with its arrow icon. */
function rjy_group_cf7_submit_tag( $tag ) {
	$label = $tag->values ? $tag->values[0] : __( 'Prepare Enquiry', 'rjy-group' );
	return '<button class="wpcf7-submit ' . esc_attr( rjy_group_button_class( 'default', 'lg' ) ) . '" type="submit">' . esc_html( $label ) . ' ' . rjy_group_icon( 'arrow-right' ) . '</button>';
}
add_action( 'wpcf7_init', static function () {
	wpcf7_add_form_tag( 'rjy_submit', 'rjy_group_cf7_submit_tag' );
} );

/** Keep the inline arrow icon when CF7 filters the rendered form through wp_kses. */
add_filter( 'wpcf7_kses_allowed_html', static function ( $allowed ) {
	$allowed['svg'] = array( 'xmlns' => true, 'width' => true, 'height' => true, 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'class' => true, 'aria-hidden' => true );
	$allowed['path'] = array( 'd' => true );
	$allowed['button'] = array_merge( $allowed['button'] ?? array(), array( 'class' => true, 'type' => true ) );
	return $allowed;
} );

/** Save CF7 quote submissions under Enquiries in wp-admin, like the theme's own form does. */
function rjy_group_cf7_store_enquiry( $contact_form ) {
	$submission = WPCF7_Submission::get_instance();
	if ( ! $submission || (int) $contact_form->id() !== rjy_group_cf7_quote_form_id() ) {
		return;
	}
	$data = $submission->get_posted_data();
	$field = static function ( $key ) use ( $data ) {
		$value = $data[ $key ] ?? '';
		return is_array( $value ) ? implode( ', ', $value ) : (string) $value;
	};
	$name = trim( sanitize_text_field( $field( 'firstName' ) ) . ' ' . sanitize_text_field( $field( 'lastName' ) ) );
	$stored_id = wp_insert_post(
		array(
			'post_type' => 'rjy_enquiry',
			'post_status' => 'publish',
			'post_title' => $name,
			'post_content' => sanitize_textarea_field( $field( 'message' ) ),
		)
	);
	if ( ! $stored_id ) {
		return;
	}
	update_post_meta( $stored_id, '_rjy_contact_name', $name );
	update_post_meta( $stored_id, '_rjy_contact_email', sanitize_email( $field( 'email' ) ) );
	update_post_meta( $stored_id, '_rjy_contact_phone', sanitize_text_field( $field( 'phone' ) ) );
	update_post_meta( $stored_id, '_rjy_contact_company', sanitize_text_field( $field( 'company' ) ) );
	update_post_meta( $stored_id, '_rjy_contact_service', sanitize_text_field( $field( 'service' ) ) );
}
add_action( 'wpcf7_before_send_mail', 'rjy_group_cf7_store_enquiry' );
