<?php
/**
 * Contact form processing and enquiry storage.
 *
 * @package RJY_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function rjy_group_register_enquiry_post_type() {
	register_post_type(
		'rjy_enquiry',
		array(
			'labels' => array(
				'name' => __( 'Enquiries', 'rjy-group' ),
				'singular_name' => __( 'Enquiry', 'rjy-group' ),
				'menu_name' => __( 'Enquiries', 'rjy-group' ),
			),
			'public' => false,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_rest' => false,
			'menu_icon' => 'dashicons-email-alt',
			'supports' => array( 'title', 'editor' ),
			'capability_type' => 'post',
			'capabilities' => array(
				'edit_posts' => 'manage_options',
				'edit_others_posts' => 'manage_options',
				'edit_published_posts' => 'manage_options',
				'edit_private_posts' => 'manage_options',
				'publish_posts' => 'manage_options',
				'read_private_posts' => 'manage_options',
				'delete_posts' => 'manage_options',
				'delete_others_posts' => 'manage_options',
				'delete_published_posts' => 'manage_options',
				'delete_private_posts' => 'manage_options',
				'create_posts' => 'manage_options',
			),
			'map_meta_cap' => true,
		)
	);
}
add_action( 'init', 'rjy_group_register_enquiry_post_type' );

function rjy_group_contact_form_status_banner() {
	if ( ! isset( $_GET['contact'] ) ) {
		return '';
	}

	$status = sanitize_key( wp_unslash( $_GET['contact'] ) );
	if ( 'success' === $status ) {
		return '<div class="form-status form-status-success" role="status">' . esc_html__( 'Thank you. Your enquiry has been sent and our team will be in touch shortly.', 'rjy-group' ) . '</div>';
	}

	return '<div class="form-status form-status-error" role="alert">' . esc_html__( 'There was a problem sending your enquiry. Please try again or call the Houston office directly.', 'rjy-group' ) . '</div>';
}

function rjy_group_contact_form_submission() {
	if ( ! isset( $_POST['rjy_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rjy_contact_nonce'] ) ), 'rjy_contact_form' ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', home_url( '/contact/' ) ) );
		exit;
	}
	if ( ! empty( $_POST['rjy_website'] ) ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	if ( ! $name && isset( $_POST['firstName'] ) ) {
		$name = trim( sanitize_text_field( wp_unslash( $_POST['firstName'] ) ) . ' ' . sanitize_text_field( wp_unslash( $_POST['lastName'] ?? '' ) ) );
	}
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$company = isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '';
	$service = isset( $_POST['service'] ) ? sanitize_text_field( wp_unslash( $_POST['service'] ) ) : '';
	$facility = isset( $_POST['facility'] ) ? sanitize_text_field( wp_unslash( $_POST['facility'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ! $phone || ! $message ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', rjy_group_enquiry_return_url() ) );
		exit;
	}

	$to = sanitize_email( rjy_group_get_mod( 'rjy_email', get_option( 'admin_email' ) ) );
	$subject = sprintf( __( 'New RJY Group enquiry from %s', 'rjy-group' ), $name );
	$body = '<html><body>';
	$body .= '<h2>' . esc_html__( 'New enquiry', 'rjy-group' ) . '</h2>';
	$body .= '<p><strong>' . esc_html__( 'Name', 'rjy-group' ) . ':</strong> ' . esc_html( $name ) . '</p>';
	$body .= '<p><strong>' . esc_html__( 'Email', 'rjy-group' ) . ':</strong> ' . esc_html( $email ) . '</p>';
	$body .= '<p><strong>' . esc_html__( 'Phone', 'rjy-group' ) . ':</strong> ' . esc_html( $phone ) . '</p>';
	if ( $company ) {
		$body .= '<p><strong>' . esc_html__( 'Company', 'rjy-group' ) . ':</strong> ' . esc_html( $company ) . '</p>';
	}
	if ( $service ) {
		$body .= '<p><strong>' . esc_html__( 'Service interest', 'rjy-group' ) . ':</strong> ' . esc_html( $service ) . '</p>';
	}
	if ( $facility ) {
		$body .= '<p><strong>' . esc_html__( 'Facility', 'rjy-group' ) . ':</strong> ' . esc_html( $facility ) . '</p>';
	}
	$body .= '<p><strong>' . esc_html__( 'Enquiry details', 'rjy-group' ) . ':</strong><br>' . nl2br( esc_html( $message ), false ) . '</p>';
	$body .= '</body></html>';

	$stored_id = wp_insert_post(
		array(
			'post_type' => 'rjy_enquiry',
			'post_status' => 'publish',
			'post_title' => $name,
			'post_content' => $message,
			'menu_order' => 0,
		),
		true
	);
	if ( is_wp_error( $stored_id ) || ! $stored_id ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', rjy_group_enquiry_return_url() ) );
		exit;
	}

	update_post_meta( $stored_id, '_rjy_contact_name', $name );
	update_post_meta( $stored_id, '_rjy_contact_email', $email );
	update_post_meta( $stored_id, '_rjy_contact_phone', $phone );
	update_post_meta( $stored_id, '_rjy_contact_company', $company );
	update_post_meta( $stored_id, '_rjy_contact_service', $service );
	update_post_meta( $stored_id, '_rjy_contact_facility', $facility );

	$site_email = sanitize_email( get_option( 'admin_email' ) );
	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	if ( $site_email ) {
		$headers[] = 'From: ' . sanitize_text_field( get_bloginfo( 'name' ) ) . ' <' . $site_email . '>';
	}
	$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';

	// The enquiry is already saved under Enquiries in wp-admin, so a failed notification email does not fail the submission.
	if ( ! wp_mail( $to, $subject, $body, $headers ) ) { update_post_meta( $stored_id, '_rjy_mail_failed', 1 ); }
	wp_safe_redirect( add_query_arg( 'contact', 'success', rjy_group_enquiry_return_url() ) );
	exit;
}
add_action( 'admin_post_nopriv_rjy_submit_enquiry', 'rjy_group_contact_form_submission' );
add_action( 'admin_post_rjy_submit_enquiry', 'rjy_group_contact_form_submission' );

function rjy_group_contact_form_markup( $args = array() ) {
	$defaults = array(
		'action' => admin_url( 'admin-post.php' ),
		'method' => 'post',
		'submit_text' => __( 'Prepare Enquiry', 'rjy-group' ),
		'show_service' => true,
	);
	$args = wp_parse_args( $args, $defaults );
	$current_url = is_singular() ? get_permalink() : home_url( '/' );
	ob_start();
	?>
	<form class="contact-form reveal" action="<?php echo esc_url( $args['action'] ); ?>" method="<?php echo esc_attr( $args['method'] ); ?>">
		<input type="hidden" name="action" value="rjy_submit_enquiry">
		<input type="hidden" name="redirect_to" value="<?php echo esc_url( $current_url ); ?>">
		<?php wp_nonce_field( 'rjy_contact_form', 'rjy_contact_nonce' ); ?>
		<p class="form-trap" aria-hidden="true"><label><?php esc_html_e( 'Leave this field empty', 'rjy-group' ); ?><input type="text" name="rjy_website" tabindex="-1" autocomplete="off"></label></p>
		<div class="form-grid">
			<label><?php esc_html_e( 'Name', 'rjy-group' ); ?> *
				<input name="name" type="text" maxlength="100" autocomplete="name" required>
			</label>
			<label><?php esc_html_e( 'Business Email', 'rjy-group' ); ?> *
				<input name="email" type="email" maxlength="254" autocomplete="email" required>
			</label>
			<label><?php esc_html_e( 'Phone', 'rjy-group' ); ?> *
				<input name="phone" type="tel" maxlength="30" autocomplete="tel" required>
			</label>
			<label><?php esc_html_e( 'Company', 'rjy-group' ); ?>
				<input name="company" type="text" maxlength="120" autocomplete="organization">
			</label>
			<?php if ( $args['show_service'] ) : ?>
			<label><?php esc_html_e( 'Service of interest', 'rjy-group' ); ?>
				<select name="service">
					<option value=""><?php esc_html_e( 'Select a service', 'rjy-group' ); ?></option>
					<option value="<?php esc_attr_e( 'Building Automation Systems', 'rjy-group' ); ?>"><?php esc_html_e( 'Building Automation Systems', 'rjy-group' ); ?></option>
					<option value="<?php esc_attr_e( 'Cyber Security for OT and ICS', 'rjy-group' ); ?>"><?php esc_html_e( 'Cyber Security for OT and ICS', 'rjy-group' ); ?></option>
					<option value="<?php esc_attr_e( 'HVAC Systems', 'rjy-group' ); ?>"><?php esc_html_e( 'HVAC Systems', 'rjy-group' ); ?></option>
					<option value="<?php esc_attr_e( 'Generator Services', 'rjy-group' ); ?>"><?php esc_html_e( 'Generator Services', 'rjy-group' ); ?></option>
					<option value="<?php esc_attr_e( 'Support Staffing', 'rjy-group' ); ?>"><?php esc_html_e( 'Support Staffing', 'rjy-group' ); ?></option>
					<option value="<?php esc_attr_e( 'Water Treatment', 'rjy-group' ); ?>"><?php esc_html_e( 'Water Treatment', 'rjy-group' ); ?></option>
				</select>
			</label>
			<?php endif; ?>
			<label><?php esc_html_e( 'Facility or site', 'rjy-group' ); ?>
				<input name="facility" type="text" maxlength="200">
			</label>
			<label class="full"><?php esc_html_e( 'Facility and project details', 'rjy-group' ); ?> *
				<textarea name="message" maxlength="1500" required></textarea>
			</label>
		</div>
		<button class="button" type="submit"><?php echo esc_html( $args['submit_text'] ); ?></button>
		<p class="form-notice"><?php esc_html_e( 'This form is processed by WordPress and delivered to the configured RJY Group email address.', 'rjy-group' ); ?></p>
	</form>
	<?php
	return ob_get_clean();
}

/** Page the enquiry was submitted from, so status messages appear beside the form. */
function rjy_group_enquiry_return_url() {
	$return_to = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : home_url( '/contact/' );
	return remove_query_arg( 'contact', wp_validate_redirect( $return_to, home_url( '/contact/' ) ) ) . '#enquiry';
}
