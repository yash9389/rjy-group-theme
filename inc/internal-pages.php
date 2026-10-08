<?php
/**
 * Page compositions ported from src/components/page-content.tsx.
 * Page records come from inc/internal-pages.json (exported from src/lib/site-data.ts by scripts/export-wp-pages.mjs).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Image keys used by site-data.ts, mapped to the same files the React app imports. */
function rjy_group_image( $key ) {
	$files = array(
		'bas' => 'service-bas-unique.jpg', 'cyber' => 'service-cybersecurity-ot-ics-unique.jpg', 'generator' => 'generator-maintenance-warm.jpg', 'water' => 'water-treatment-warm.jpg',
		'hvac' => 'hvac-team-warm.jpg', 'staffing' => 'support-staffing-unique.jpg', 'healthcare' => 'industry-healthcare-unique.jpg',
		'publicSafety' => 'industry-public-safety-unique.jpg', 'commercialCampus' => 'industry-commercial-campus-v2.jpg',
		'higherEducation' => 'industry-higher-education-unique.jpg', 'transportUtilities' => 'industry-transport-utilities-unique.jpg',
		'servicesHub' => 'service-bas-unique.jpg', 'industriesHub' => 'industry-commercial-campus-v2.jpg', 'companyTeam' => 'internal-company-team-banner.jpg',
		'healthcareBanner' => 'internal-healthcare-banner.jpg', 'transportBanner' => 'internal-transport-utilities-banner.jpg',
		'support' => 'internal-support-banner.jpg', 'responsive' => 'value-responsive-service-unique.jpg', 'knowledge' => 'internal-knowledge-banner.jpg',
		'federal' => 'internal-federal-banner.jpg', 'compliance' => 'value-compliance-unique.jpg', 'certified' => 'value-certified-team-unique.jpg',
		'standards' => 'value-federal-standards-unique.jpg', 'founder' => 'richard-young-portrait.jpg',
		'basDetail' => 'detail-bas-field.jpg', 'cyberDetail' => 'detail-ot-security.jpg', 'hvacDetail' => 'detail-hvac-chiller.jpg',
		'generatorDetail' => 'detail-generator-test.jpg', 'staffingDetail' => 'detail-staffing-team.jpg', 'waterDetail' => 'detail-water-sampling.jpg',
		'healthcareDetail' => 'detail-healthcare-plant.jpg', 'publicSafetyDetail' => 'detail-public-safety.jpg',
		'commercialDetail' => 'detail-commercial-campus.jpg', 'educationDetail' => 'detail-university-lab.jpg', 'transportDetail' => 'detail-transport-utility.jpg',
		'chiller' => 'detail-hvac-chiller.jpg', 'chillerDetail' => 'bas-control-room.jpg',
	);
	return rjy_group_asset( $files[ $key ] ?? $files['servicesHub'] );
}

function rjy_group_services() {
	return array(
		array( 'title' => 'Building Automation Systems (BAS)', 'path' => '/services/bas', 'description' => 'Optimize controls, reduce energy use, and strengthen building performance.', 'image' => 'bas' ),
		array( 'title' => 'Cyber Security for OT and ICS', 'path' => '/services/cybersecurity-ot-ics', 'description' => 'Protect connected building systems from operational and cyber risk.', 'image' => 'cyber' ),
		array( 'title' => 'HVAC Systems', 'path' => '/services/hvac', 'description' => 'Keep critical heating and cooling assets reliable in every season.', 'image' => 'hvac' ),
		array( 'title' => 'Generators', 'path' => '/services/generators', 'description' => 'Maintain resilient standby power through testing, service, and repair.', 'image' => 'generator' ),
		array( 'title' => 'Support Staffing', 'path' => '/services/support-staffing', 'description' => 'Extend your facilities team with qualified, responsive technical talent.', 'image' => 'staffing' ),
		array( 'title' => 'Water Treatment', 'path' => '/services/water-treatment', 'description' => 'Protect equipment, efficiency, and water quality across closed-loop systems.', 'image' => 'water' ),
	);
}

function rjy_group_industries() {
	return array(
		array( 'title' => 'Healthcare & Hospitals', 'path' => '/industries/healthcare-hospitals', 'description' => 'Critical systems that support safe, uninterrupted patient care.', 'image' => 'healthcare' ),
		array( 'title' => 'Government & Public Safety', 'path' => '/industries/government-public-safety', 'description' => 'Dependable facilities for essential public services and emergency operations.', 'image' => 'publicSafety' ),
		array( 'title' => 'Industrial & Commercial Campuses', 'path' => '/industries/industrial-commercial', 'description' => 'Integrated maintenance and security for complex operating environments.', 'image' => 'commercialCampus' ),
		array( 'title' => 'Higher Education', 'path' => '/industries/higher-education', 'description' => 'Comfort, reliability, and security across academic and residential buildings.', 'image' => 'higherEducation' ),
		array( 'title' => 'Data Center', 'path' => '/industries/data-center', 'description' => 'Reliable infrastructure for critical operations.', 'image' => 'dataCenter' ),
		array( 'title' => 'Chiller', 'path' => '/industries/chiller', 'description' => 'Reliable chiller and central cooling plant performance.', 'image' => 'chiller' ),
		array( 'title' => 'Transportation & Utilities', 'path' => '/industries/transportation-utilities', 'description' => 'Resilient infrastructure that keeps people, power, and services moving.', 'image' => 'transportUtilities' ),
	);
}

function rjy_group_internal_page_records() {
	static $records = null;
	if ( null === $records ) {
		$file = get_template_directory() . '/inc/internal-pages.json';
		$json = file_exists( $file ) ? file_get_contents( $file ) : '';
		$records = $json ? json_decode( $json, true ) : array();
		if ( ! is_array( $records ) ) { $records = array(); }
	}
	return $records;
}

function rjy_group_internal_page_data( $path ) {
	$path = '/' . trim( (string) $path, '/' );
	$records = rjy_group_internal_page_records();
	return isset( $records[ $path ] ) ? $records[ $path ] : null;
}

/** Same as getPage() in site-data.ts: a record, or the not-found record for the path. */
function rjy_group_get_page( $path ) {
	$page = rjy_group_internal_page_data( $path );
	return $page ? $page : array( 'path' => '/' . trim( (string) $path, '/' ), 'title' => 'Page Not Found', 'eyebrow' => '404', 'description' => 'The page you requested is not available.', 'type' => 'not-found', 'image' => 'servicesHub', 'imageAlt' => 'RJY Group facility operations', 'introTitle' => 'This page is not available.', 'intro' => 'The address may have changed or the page may no longer be available.' );
}

function rjy_group_internal_page_link( $path ) { return rjy_group_url( $path ); }


function rjy_group_section_head( $eyebrow, $title, $copy = '' ) {
	?><div class="section-head"><p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p><h2><?php echo esc_html( $title ); ?></h2><?php if ( $copy ) : ?><p><?php echo esc_html( $copy ); ?></p><?php endif; ?></div><?php
}

function rjy_group_page_hero( $page ) {
	$type = $page['type'] ?? '';
	?><section class="inner-hero"><img src="<?php echo esc_url( empty( $page['imageUrl'] ) ? rjy_group_image( $page['image'] ?? 'servicesHub' ) : $page['imageUrl'] ); ?>" alt="<?php echo esc_attr( $page['imageAlt'] ?? '' ); ?>" width="1536" height="864" fetchpriority="high"><div class="inner-hero-shade"></div><div class="site-container inner-hero-content"><p class="eyebrow eyebrow-light"><?php echo esc_html( $page['eyebrow'] ?? '' ); ?></p><h1><?php echo esc_html( $page['title'] ); ?></h1><p><?php echo esc_html( $page['description'] ?? '' ); ?></p><?php if ( 'legal' !== $type && 'not-found' !== $type ) { echo rjy_group_button_link( $page['ctaPath'] ?? '/support/request-quote', $page['ctaLabel'] ?? __( 'Request an Inspection', 'rjy-group' ) ); } ?></div></section><?php
}

function rjy_group_breadcrumbs( $page ) {
	$parts = array_values( array_filter( explode( '/', $page['path'] ) ) );
	$labels = array( 'services' => 'Services', 'industries' => 'Industries', 'about' => 'About Us', 'support' => 'Support', 'case-studies' => 'Case Studies' );
	$chevron = '<li aria-hidden="true">' . rjy_group_icon( 'chevron-right' ) . '</li>';
	?><nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'rjy-group' ); ?>"><ol class="site-container"><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'rjy-group' ); ?></a></li><?php if ( count( $parts ) > 1 ) : echo $chevron; ?><li><a href="<?php echo esc_url( rjy_group_url( '/' . $parts[0] ) ); ?>"><?php echo esc_html( $labels[ $parts[0] ] ?? $parts[0] ); ?></a></li><?php endif; echo $chevron; ?><li><span aria-current="page"><?php echo esc_html( $page['title'] ); ?></span></li></ol></nav><?php
}

function rjy_group_tile_grid( $kind ) {
	$items = 'services' === $kind ? rjy_group_services() : rjy_group_industries();
	?><div class="<?php echo 'services' === $kind ? 'service-grid' : 'industry-grid'; ?>"><?php foreach ( $items as $item ) : ?><article class="image-tile"><div class="image-tile-media"><img src="<?php echo rjy_group_image( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] . ' facility services' ); ?>" loading="lazy" width="1024" height="768"></div><div class="image-tile-copy"><h3><?php echo esc_html( $item['title'] ); ?></h3><span class="accent-rule"></span><p><?php echo esc_html( $item['description'] ); ?></p><a href="<?php echo esc_url( rjy_group_url( $item['path'] ) ); ?>"><?php esc_html_e( 'Learn More', 'rjy-group' ); ?> <?php echo rjy_group_icon( 'arrow-right' ); ?></a></div></article><?php endforeach; ?></div><?php
}

/** ContactForm, wired to the WordPress enquiry handler in inc/form-backend.php. */
function rjy_group_contact_form( $page = array(), $compact = false ) {
	if ( rjy_group_render_cf7_form( $page, $compact ) ) {
		return;
	}
	$status = isset( $_GET['contact'] ) ? sanitize_key( wp_unslash( $_GET['contact'] ) ) : '';
	if ( 'success' === $status ) {
		?><div class="form-confirmation" id="enquiry" role="status"><?php echo rjy_group_icon( 'circle-check' ); ?><h3><?php esc_html_e( 'Request details sent.', 'rjy-group' ); ?></h3><p><?php echo esc_html( sprintf( __( 'Thank you. Your enquiry has been delivered and our team will be in touch shortly. For anything urgent, call RJY Group or email %s.', 'rjy-group' ), rjy_group_contact_mod( 'rjy_email' ) ) ); ?></p><a href="<?php echo esc_url( rjy_group_phone_href( rjy_group_contact_mod( 'rjy_toll_free' ) ) ); ?>"><?php echo esc_html( sprintf( __( 'Toll-Free: %s', 'rjy-group' ), rjy_group_contact_mod( 'rjy_toll_free' ) ) ); ?></a></div><?php
		return;
	}
	$prompt = $page['enquiryPrompt'] ?? __( 'Describe the facility, affected system, current condition, timing, and the outcome you need.', 'rjy-group' );
	?><form id="enquiry" class="contact-form<?php echo $compact ? ' compact' : ''; ?>" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post"><input type="hidden" name="action" value="rjy_submit_enquiry"><input type="hidden" name="redirect_to" value="<?php echo esc_url( is_singular() ? get_permalink() : home_url( add_query_arg( array() ) ) ); ?>"><?php wp_nonce_field( 'rjy_contact_form', 'rjy_contact_nonce' ); ?><p class="form-trap" aria-hidden="true"><label><?php esc_html_e( 'Leave this field empty', 'rjy-group' ); ?><input type="text" name="rjy_website" tabindex="-1" autocomplete="off"></label></p>
	<?php if ( 'error' === $status ) : ?><div class="enquiry-note" role="alert"><?php echo rjy_group_icon( 'triangle-alert' ); ?><p><?php esc_html_e( 'There was a problem sending your enquiry. Please check the required fields and try again, or call the Houston office directly.', 'rjy-group' ); ?></p></div><?php endif; ?>
	<div class="form-grid"><label><?php esc_html_e( 'First Name *', 'rjy-group' ); ?><input required name="firstName" autocomplete="given-name" maxlength="60"></label><label><?php esc_html_e( 'Last Name *', 'rjy-group' ); ?><input required name="lastName" autocomplete="family-name" maxlength="60"></label><label><?php esc_html_e( 'Email *', 'rjy-group' ); ?><input required type="email" name="email" autocomplete="email" maxlength="254"></label><label><?php esc_html_e( 'Company Name', 'rjy-group' ); ?><input name="company" autocomplete="organization" maxlength="120"></label><label><?php esc_html_e( 'Type of Service Needed', 'rjy-group' ); ?><select name="service"><option value="" disabled selected><?php esc_html_e( 'Select a service', 'rjy-group' ); ?></option><?php foreach ( rjy_group_services() as $service ) : ?><option><?php echo esc_html( $service['title'] ); ?></option><?php endforeach; ?></select></label><label><?php esc_html_e( 'Phone *', 'rjy-group' ); ?><input required type="tel" name="phone" autocomplete="tel" maxlength="30"></label></div>
	<label><?php esc_html_e( 'Facility and project details', 'rjy-group' ); ?><textarea name="message" required maxlength="1500" rows="<?php echo $compact ? 3 : 5; ?>" placeholder="<?php echo esc_attr( $prompt ); ?>"></textarea></label><button class="<?php echo esc_attr( rjy_group_button_class( 'default', 'lg' ) ); ?>" type="submit"><?php esc_html_e( 'Prepare Enquiry', 'rjy-group' ); ?> <?php echo rjy_group_icon( 'arrow-right' ); ?></button></form><?php
}

function rjy_group_intro_section( $page ) {
	?><section class="page-section site-container internal-intro"><div><p class="eyebrow"><?php echo esc_html( 'industry' === ( $page['type'] ?? '' ) ? __( 'Your operating environment', 'rjy-group' ) : __( 'The RJY approach', 'rjy-group' ) ); ?></p><h2><?php echo esc_html( $page['introTitle'] ?? '' ); ?></h2></div><p><?php echo esc_html( $page['intro'] ?? '' ); ?></p></section><?php
}


function rjy_group_cards_section( $eyebrow, $title, $cards ) {
	$icons = array( 'gauge', 'shield-check', 'users', 'wrench', 'network', 'clipboard-check' );
	?><section class="page-section section-tint"><div class="site-container"><?php rjy_group_section_head( $eyebrow, $title ); ?><div class="capability-grid"><?php foreach ( $cards as $index => $card ) : ?><article><span class="icon-box"><?php echo rjy_group_icon( $icons[ $index % count( $icons ) ] ); ?></span><small><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></small><h3><?php echo esc_html( $card['title'] ); ?></h3><p><?php echo esc_html( $card['copy'] ?? '' ); ?></p></article><?php endforeach; ?></div></div></section><?php
}

function rjy_group_process_section( $items ) {
	?><section class="page-section site-container process-section"><?php rjy_group_section_head( __( 'How we work', 'rjy-group' ), __( 'A disciplined path from condition to action.', 'rjy-group' ) ); ?><div class="process-grid"><?php foreach ( $items as $index => $item ) : ?><article><strong><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></strong><div><h3><?php echo esc_html( $item['title'] ); ?></h3><p><?php echo esc_html( $item['copy'] ); ?></p></div></article><?php endforeach; ?></div></section><?php
}

function rjy_group_outcomes_band( $outcomes ) {
	?><section class="outcomes-band"><div class="site-container"><p class="eyebrow eyebrow-light"><?php esc_html_e( 'Facility outcomes', 'rjy-group' ); ?></p><div><?php foreach ( $outcomes as $outcome ) : ?><span><?php echo rjy_group_icon( 'circle-check' ); ?><?php echo esc_html( $outcome ); ?></span><?php endforeach; ?></div></div></section><?php
}

function rjy_group_problems_section( $page ) {
	if ( empty( $page['problems'] ) ) { return; }
	?><section class="page-section site-container problems-section"><div class="problems-story"><p class="eyebrow"><?php esc_html_e( 'Problems we solve', 'rjy-group' ); ?></p><h2><?php esc_html_e( 'Remove the conditions that put performance at risk.', 'rjy-group' ); ?></h2><p><?php esc_html_e( 'Technical problems rarely stay isolated. RJY Group connects symptoms, equipment condition, controls, operating practices, records, and system dependencies so the response addresses what is actually driving risk.', 'rjy-group' ); ?></p><?php $support_image = $page['supportImageUrl'] ?? ( empty( $page['supportImage'] ) ? '' : rjy_group_image( $page['supportImage'] ) ); if ( $support_image ) : ?><figure><img src="<?php echo esc_url( $support_image ); ?>" alt="<?php echo esc_attr( $page['supportImageAlt'] ?? 'RJY Group facility specialist at work' ); ?>" loading="lazy" width="1536" height="864"><figcaption><?php echo esc_html( sprintf( __( 'Field-focused insight for %s.', 'rjy-group' ), strtolower( $page['title'] ) ) ); ?></figcaption></figure><?php endif; ?></div><div class="problem-list"><?php foreach ( $page['problems'] as $index => $problem ) : ?><article><span><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span><div><h3><?php echo esc_html( $problem['title'] ); ?></h3><p><?php echo esc_html( $problem['copy'] ); ?></p></div></article><?php endforeach; ?></div></section><?php
}

function rjy_group_faq_section( $page ) {
	if ( empty( $page['faqs'] ) ) { return; }
	?><section class="page-section section-tint"><div class="site-container faq-section"><div><p class="eyebrow"><?php esc_html_e( 'Technical questions', 'rjy-group' ); ?></p><h2><?php esc_html_e( 'Answers for planning the next step.', 'rjy-group' ); ?></h2><p><?php esc_html_e( 'Use these answers to prepare an internal discussion, records review, or first conversation with RJY Group.', 'rjy-group' ); ?></p></div><div><?php foreach ( $page['faqs'] as $index => $item ) : ?><details class="faq"><summary><span><?php echo esc_html( sprintf( '%02d. %s', $index + 1, $item['question'] ) ); ?></span><span aria-hidden="true">+</span></summary><p><?php echo esc_html( $item['answer'] ); ?></p></details><?php endforeach; ?></div></div></section><?php
}

function rjy_group_related_section( $page ) {
	if ( empty( $page['related'] ) ) { return; }
	?><section class="page-section site-container related-section"><?php rjy_group_section_head( __( 'Continue exploring', 'rjy-group' ), __( 'Related expertise for the facility around you.', 'rjy-group' ) ); ?><div class="related-grid"><?php foreach ( $page['related'] as $item ) : $related = rjy_group_get_page( $item['path'] ); ?><a href="<?php echo esc_url( rjy_group_url( $item['path'] ) ); ?>"><span><?php echo esc_html( $related['eyebrow'] ); ?></span><h3><?php echo esc_html( $item['label'] ); ?></h3><p><?php echo esc_html( $item['description'] ); ?></p><strong><?php echo esc_html( sprintf( __( 'Explore %s', 'rjy-group' ), $related['title'] ) ); ?> <?php echo rjy_group_icon( 'arrow-right' ); ?></strong></a><?php endforeach; ?></div></section><?php
}

function rjy_group_enquiry_section( $page ) {
	?><section class="page-section enquiry-section"><div class="site-container form-layout"><div class="support-context"><p class="eyebrow"><?php esc_html_e( 'Start with the operating need', 'rjy-group' ); ?></p><h2><?php echo esc_html( $page['enquiryTitle'] ?? '' ); ?></h2><p class="lead"><?php echo esc_html( $page['enquiryCopy'] ?? '' ); ?></p><div class="enquiry-note"><?php echo rjy_group_icon( 'triangle-alert' ); ?><p><?php esc_html_e( 'For an immediate life-safety or facility emergency, use your established emergency procedure. Website enquiries are for assessment, service, and project planning.', 'rjy-group' ); ?></p></div></div><?php rjy_group_contact_form( $page ); ?></div></section><?php
}

function rjy_group_phone_close( $page ) {
	?><section class="phone-close"><div class="site-container"><div><p class="eyebrow eyebrow-light"><?php esc_html_e( 'Speak directly with RJY Group', 'rjy-group' ); ?></p><h2><?php echo esc_html( $page['phoneCta'] ?? '' ); ?></h2></div><div><a href="<?php echo esc_url( rjy_group_phone_href( rjy_group_contact_mod( 'rjy_phone' ) ) ); ?>"><?php echo rjy_group_icon( 'phone' ); ?><span><?php esc_html_e( 'Houston office', 'rjy-group' ); ?><strong><?php echo esc_html( rjy_group_contact_mod( 'rjy_phone' ) ); ?></strong></span></a><a href="<?php echo esc_url( rjy_group_phone_href( rjy_group_contact_mod( 'rjy_toll_free' ) ) ); ?>"><?php echo rjy_group_icon( 'phone' ); ?><span><?php esc_html_e( 'Toll-Free', 'rjy-group' ); ?><strong><?php echo esc_html( rjy_group_contact_mod( 'rjy_toll_free' ) ); ?></strong></span></a></div></div></section><?php
}

function rjy_group_page_end( $page, $enquiry = false ) {
	if ( $enquiry ) { rjy_group_enquiry_section( $page ); }
	rjy_group_faq_section( $page );
	rjy_group_related_section( $page );
	rjy_group_phone_close( $page );
}

function rjy_group_cta_band() {
	?><section class="cta-band"><div class="site-container"><div><p class="eyebrow eyebrow-light"><?php esc_html_e( 'Your facility. Your priorities.', 'rjy-group' ); ?></p><h2><?php esc_html_e( "Let's scope it together.", 'rjy-group' ); ?></h2></div><?php echo rjy_group_button_link( '/support/request-quote', __( 'Request an Inspection', 'rjy-group' ) ); ?></div></section><?php
}

function rjy_group_case_studies() {
	$cases = array( array( 'Controls modernization planning', 'A phased path toward one operating view', 'gauge' ), array( 'Critical systems risk review', 'Maintenance priorities organized by consequence', 'clipboard-check' ), array( 'Operational resilience planning', 'Dependencies and response roles brought together', 'shield-check' ) );
	?><section class="page-section site-container"><?php rjy_group_section_head( __( 'Project scenarios', 'rjy-group' ), __( 'See how we structure the work.', 'rjy-group' ), __( 'These illustrative scenarios explain the RJY Group approach without presenting unverified client claims.', 'rjy-group' ) ); ?><div class="case-grid"><?php foreach ( $cases as $index => $case ) : ?><a class="case-card" href="<?php echo esc_url( rjy_group_url( '/case-studies/project-' . ( $index + 1 ) ) ); ?>"><div class="case-number">0<?php echo (int) $index + 1; ?></div><?php echo rjy_group_icon( $case[2] ); ?><p class="case-label"><?php esc_html_e( 'Illustrative approach', 'rjy-group' ); ?></p><h3><?php echo esc_html( $case[0] ); ?></h3><p><?php echo esc_html( $case[1] ); ?></p><span><?php esc_html_e( 'View approach', 'rjy-group' ); ?> <?php echo rjy_group_icon( 'arrow-right' ); ?></span></a><?php endforeach; ?></div></section><?php
}

function rjy_group_articles() {
	$items = array( array( 'Building automation', 'Five signs your controls strategy needs a reset' ), array( 'Cybersecurity', 'A practical OT security starting point' ), array( 'Maintenance planning', 'Prepare critical equipment for peak season' ) );
	?><section class="page-section site-container"><?php rjy_group_section_head( __( 'Knowledge Center', 'rjy-group' ), __( 'Ideas for more resilient facilities.', 'rjy-group' ) ); ?><div class="article-grid"><?php foreach ( $items as $index => $item ) : ?><a href="<?php echo esc_url( rjy_group_url( '/support/knowledge-center/article-' . ( $index + 1 ) ) ); ?>"><p><?php echo esc_html( $item[0] ); ?></p><h3><?php echo esc_html( $item[1] ); ?></h3><span><?php esc_html_e( 'Read article', 'rjy-group' ); ?> <?php echo rjy_group_icon( 'arrow-right' ); ?></span></a><?php endforeach; ?></div></section><?php
}

function rjy_group_credentials() {
	$items = array( array( 'SDVOSB', 'Service-Disabled Veteran-Owned Small Business' ), array( 'PMP', 'Project Management Professional leadership credential' ), array( 'CFM', 'Certified Facility Manager leadership credential' ), array( 'SAM.GOV', 'Federal supplier registration information available for review' ) );
	?><section class="page-section site-container"><?php rjy_group_section_head( __( 'Procurement confidence', 'rjy-group' ), __( 'Documented readiness for demanding work.', 'rjy-group' ) ); ?><div class="credential-grid"><?php foreach ( $items as $item ) : ?><article><strong><?php echo esc_html( $item[0] ); ?></strong><p><?php echo esc_html( $item[1] ); ?></p></article><?php endforeach; ?></div></section><?php
}

function rjy_group_resources() {
	$resources = array( array( 'Facility readiness', 'Critical Systems Readiness Checklist', 'Review controls, HVAC, power, water, and OT risk before the next planning cycle.' ), array( 'Cybersecurity', 'OT and ICS Risk Review Guide', 'A practical starting point for identifying connected-system exposure.' ), array( 'Maintenance', 'Peak Season Preparation Guide', 'A focused pre-season review for critical mechanical equipment.' ) );
	?><section class="page-section site-container"><?php rjy_group_section_head( __( 'Field-ready tools', 'rjy-group' ), __( 'Resources for safer, more reliable operations.', 'rjy-group' ) ); ?><div class="resource-grid"><?php foreach ( $resources as $resource ) : ?><article><p class="eyebrow"><?php echo esc_html( $resource[0] ); ?></p><?php echo rjy_group_icon( 'file-check-corner' ); ?><h3><?php echo esc_html( $resource[1] ); ?></h3><p><?php echo esc_html( $resource[2] ); ?></p><form onsubmit="event.preventDefault()"><label><span><?php esc_html_e( 'Email address', 'rjy-group' ); ?></span><input type="email" required placeholder="name@company.com"></label><button class="<?php echo esc_attr( rjy_group_button_class() ); ?>" type="submit"><?php esc_html_e( 'Request the guide', 'rjy-group' ); ?> <?php echo rjy_group_icon( 'arrow-right' ); ?></button></form></article><?php endforeach; ?></div></section><?php
}

function rjy_group_not_found_page( $page ) {
	rjy_group_page_hero( $page );
	?><section class="page-section site-container not-found"><h2><?php echo esc_html( $page['introTitle'] ); ?></h2><p><?php echo esc_html( $page['intro'] ); ?></p><a class="<?php echo esc_attr( rjy_group_button_class() ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return home', 'rjy-group' ); ?> <?php echo rjy_group_icon( 'arrow-right' ); ?></a></section><?php
}

function rjy_group_contact_methods( $phone_label, $phone ) {
	?><div class="contact-methods"><a href="<?php echo esc_url( rjy_group_phone_href( $phone ) ); ?>"><?php echo rjy_group_icon( 'phone' ); ?><?php echo esc_html( $phone_label ); ?></a><a href="mailto:<?php echo esc_attr( antispambot( rjy_group_contact_mod( 'rjy_email' ) ) ); ?>"><?php echo rjy_group_icon( 'headphones' ); ?><?php echo esc_html( antispambot( rjy_group_contact_mod( 'rjy_email' ) ) ); ?></a></div><?php
}

/** GenericPage: picks the page-type composition, exactly as the React router does. */
function rjy_group_render_internal_page( $page, $title = '' ) {
	if ( ! is_array( $page ) ) { return; }
	$type = $page['type'] ?? 'hub';
	$name = $page['layout'] ?? $page['title'];

	if ( 'not-found' === $type ) { rjy_group_not_found_page( $page ); return; }

	rjy_group_page_hero( $page );
	rjy_group_breadcrumbs( $page );

	if ( in_array( $type, array( 'service', 'industry', 'case-study' ), true ) ) {
		rjy_group_intro_section( $page );
		rjy_group_problems_section( $page );
		if ( ! empty( $page['cards'] ) ) { rjy_group_cards_section( 'industry' === $type ? __( 'Where we focus', 'rjy-group' ) : __( 'Core capabilities', 'rjy-group' ), 'industry' === $type ? __( 'Support shaped around the mission.', 'rjy-group' ) : __( 'The right attention at every layer.', 'rjy-group' ), $page['cards'] ); }
		if ( ! empty( $page['process'] ) ) { rjy_group_process_section( $page['process'] ); }
		if ( ! empty( $page['highlights'] ) ) : ?><section class="page-section section-tint"><div class="site-container highlight-layout"><div><p class="eyebrow"><?php esc_html_e( 'Scope priorities', 'rjy-group' ); ?></p><h2><?php esc_html_e( 'Built around the systems that matter.', 'rjy-group' ); ?></h2></div><ul><?php foreach ( $page['highlights'] as $item ) : ?><li><?php echo rjy_group_icon( 'check' ); ?><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul></div></section><?php endif;
		if ( ! empty( $page['outcomes'] ) ) { rjy_group_outcomes_band( $page['outcomes'] ); }
		rjy_group_page_end( $page, 'service' === $type || 'industry' === $type );
		return;
	}

	if ( 'about' === $type ) {
		rjy_group_intro_section( $page );
		if ( ! empty( $page['cards'] ) ) { rjy_group_cards_section( __( 'What defines us', 'rjy-group' ), __( 'Technical depth. Clear responsibility.', 'rjy-group' ), $page['cards'] ); }
		if ( ! empty( $page['outcomes'] ) ) { rjy_group_outcomes_band( $page['outcomes'] ); }
		rjy_group_page_end( $page );
		if ( 'Certifications & Accreditations' === $name ) { rjy_group_credentials(); }
		return;
	}

	if ( 'support' === $type ) {
		if ( 'Frequently Asked Questions' === $name || 'Resources & Downloads' === $name ) {
			rjy_group_intro_section( $page );
			if ( 'Resources & Downloads' === $name ) { rjy_group_resources(); }
		} else {
			?><section class="page-section site-container form-layout"><div class="support-context"><p class="eyebrow"><?php esc_html_e( 'Contact RJY Group', 'rjy-group' ); ?></p><h2><?php echo esc_html( $page['introTitle'] ); ?></h2><p class="lead"><?php echo esc_html( $page['intro'] ); ?></p><?php rjy_group_contact_methods( sprintf( __( 'Toll-Free: %s', 'rjy-group' ), rjy_group_contact_mod( 'rjy_toll_free' ) ), rjy_group_contact_mod( 'rjy_toll_free' ) ); ?></div><?php rjy_group_contact_form( $page ); ?></section><?php
		}
		rjy_group_page_end( $page );
		return;
	}

	if ( 'contact' === $type ) {
		?><section class="page-section site-container form-layout"><div class="support-context"><p class="eyebrow"><?php esc_html_e( 'Houston, Texas', 'rjy-group' ); ?></p><h2><?php echo esc_html( $page['introTitle'] ); ?></h2><p class="lead"><?php echo esc_html( $page['intro'] ); ?></p><div class="office-block"><?php echo rjy_group_icon( 'map-pin' ); ?><div><strong><?php esc_html_e( 'RJY Group Headquarters', 'rjy-group' ); ?></strong><p><?php echo esc_html( rjy_group_contact_mod( 'rjy_address' ) ); ?><br><?php echo esc_html( rjy_group_contact_mod( 'rjy_city' ) ); ?></p></div></div><?php rjy_group_contact_methods( rjy_group_contact_mod( 'rjy_phone' ), rjy_group_contact_mod( 'rjy_phone' ) ); ?></div><?php rjy_group_contact_form( $page ); ?></section><?php
		rjy_group_page_end( $page );
		return;
	}

	if ( 'legal' === $type ) {
		$privacy = 'Privacy & Security' === $name;
		?><article class="page-section site-container legal-layout"><aside><p class="eyebrow">RJY Group</p><p><?php esc_html_e( 'General website information', 'rjy-group' ); ?></p><a href="mailto:<?php echo esc_attr( antispambot( rjy_group_contact_mod( 'rjy_email' ) ) ); ?>"><?php esc_html_e( 'Questions about this notice', 'rjy-group' ); ?></a></aside><div class="prose"><h2><?php echo esc_html( $page['introTitle'] ); ?></h2><p><?php echo esc_html( $page['intro'] ); ?></p><?php if ( $privacy ) : ?><h3>Information you provide</h3><p>When you submit a website form or contact RJY Group, you may provide your name, company, contact details, facility information, and the content of your request.</p><h3>How information is used</h3><p>Information is used to respond to inquiries, understand requested services, maintain business records, and support the security and operation of this website.</p><h3>Sharing and retention</h3><p>RJY Group does not sell information submitted through this website. Information may be handled by service providers that support website and business operations, subject to appropriate obligations.</p><h3>Your choices</h3><p>You may contact RJY Group to ask a question about information you submitted through the website.</p><?php else : ?><h3>Informational purpose</h3><p>Website content is provided for general informational purposes. Service scope, schedules, responsibilities, and commercial terms are established only through an executed agreement.</p><h3>Acceptable use</h3><p>Do not misuse the website, attempt unauthorized access, interfere with its operation, or use its content in a way that violates applicable law or the rights of others.</p><h3>Content and availability</h3><p>RJY Group may update website content and does not promise that every page will remain available or error-free at all times.</p><h3>Questions</h3><p>Contact info@rjygroup.net with questions about these website terms.</p><?php endif; ?></div></article><?php
		rjy_group_page_end( $page );
		return;
	}

	if ( 'article' === $type ) {
		?><article class="page-section site-container article-layout"><aside><?php echo rjy_group_icon( 'book-open' ); ?><p><?php esc_html_e( 'Knowledge Center', 'rjy-group' ); ?></p><span><?php esc_html_e( 'Practical guidance for facility teams', 'rjy-group' ); ?></span></aside><div class="prose"><h2><?php echo esc_html( $page['introTitle'] ); ?></h2><p class="lead"><?php echo esc_html( $page['intro'] ); ?></p><?php foreach ( $page['highlights'] ?? array() as $index => $item ) : ?><section><h3><?php echo esc_html( ( $index + 1 ) . '. ' . $item ); ?></h3><p><?php esc_html_e( 'Review this condition in the context of equipment history, operating priorities, responsible stakeholders, and the consequence of delay. Record what is known, what still needs verification, and the next practical action.', 'rjy-group' ); ?></p></section><?php endforeach; ?><div class="article-note"><?php echo rjy_group_icon( 'shield-check' ); ?><p><?php esc_html_e( 'This article provides general planning guidance. Site conditions and technical requirements should be evaluated by qualified professionals.', 'rjy-group' ); ?></p></div></div></article><?php
		rjy_group_page_end( $page );
		return;
	}

	if ( 'testimonial' === $type ) {
		$values = array( array( 'Responsive communication', 'Prompt, clear updates help facility teams coordinate decisions and reduce uncertainty.' ), array( 'Practical recommendations', 'Technical findings should translate into priorities that operators and leaders can act on.' ), array( 'Disciplined closeout', 'Complete records and visible next steps support the work after the field team leaves.' ) );
		rjy_group_intro_section( $page );
		?><section class="page-section site-container"><div class="expectation-grid"><?php foreach ( $values as $item ) : ?><article><?php echo rjy_group_icon( 'quote' ); ?><h2><?php echo esc_html( $item[0] ); ?></h2><p><?php echo esc_html( $item[1] ); ?></p></article><?php endforeach; ?></div><p class="disclosure"><?php esc_html_e( 'Client quotations will be published only when they are verified and approved for public use.', 'rjy-group' ); ?></p></section><?php
		rjy_group_page_end( $page );
		return;
	}

	// Hub pages.
	rjy_group_intro_section( $page );
	if ( 'Our Services' === $name || 'Industries We Serve' === $name ) {
		$is_services = 'Our Services' === $name;
		?><section class="page-section site-container"><?php rjy_group_section_head( $is_services ? __( 'Complete capabilities', 'rjy-group' ) : __( 'Where we work', 'rjy-group' ), $is_services ? __( 'One partner. Six connected disciplines.', 'rjy-group' ) : __( 'Facilities that cannot afford disruption.', 'rjy-group' ) ); rjy_group_tile_grid( $is_services ? 'services' : 'industries' ); ?></section><?php
	} elseif ( 'Knowledge Center' === $name ) {
		rjy_group_articles();
	} elseif ( 'Case Studies' === $name ) {
		rjy_group_case_studies();
	} elseif ( ! empty( $page['cards'] ) ) {
		// WordPress-only landing pages (/about, /support) that the React app has no route for.
		rjy_group_cards_section( __( 'Where to start', 'rjy-group' ), __( 'Choose the next step for your facility.', 'rjy-group' ), $page['cards'] );
	}
	rjy_group_page_end( $page );
}

/** Hero + breadcrumb wrapper for ordinary WordPress content (posts, archives, editor-only pages). */
function rjy_group_content_hero( $eyebrow, $title, $description = '' ) {
	rjy_group_page_hero( array( 'type' => 'legal', 'image' => 'servicesHub', 'imageAlt' => '', 'eyebrow' => $eyebrow, 'title' => $title, 'description' => $description ) );
}
