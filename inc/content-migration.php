<?php
/**
 * Seed and migrate core RJY pages and service entries.
 *
 * @package RJY_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function rjy_group_seed_service_posts() {
	$services = array(
		'bas' => array(
			'title' => __( 'Building Automation Systems', 'rjy-group' ),
			'excerpt' => __( 'Design, install, optimize, and maintain connected controls that make facilities easier to operate and more efficient.', 'rjy-group' ),
			'content' => __( "A well-designed building automation system gives facility teams a clear operating view, reliable control, and the information needed to address issues before they interrupt service.\n\nControls integration and upgrades\nSystem commissioning and optimization\nRemote monitoring and diagnostics", 'rjy-group' ),
		),
		'cybersecurity-ot-ics' => array(
			'title' => __( 'Cyber Security for OT and ICS', 'rjy-group' ),
			'excerpt' => __( 'Secure connected building systems that keep facilities safe, productive, and online.', 'rjy-group' ),
			'content' => __( "Operational technology requires a practical security approach that respects uptime, safety, legacy equipment, vendor access, and the realities of facility operations.\n\nOT and ICS risk assessments\nNetwork segmentation guidance\nSecure controls lifecycle support", 'rjy-group' ),
		),
		'hvac' => array(
			'title' => __( 'HVAC Systems', 'rjy-group' ),
			'excerpt' => __( 'Preventive maintenance, repair, and performance services for complex HVAC and chiller systems.', 'rjy-group' ),
			'content' => __( "HVAC reliability starts with disciplined maintenance, informed diagnostics, and work planned around the needs of the occupied facility.\n\nPreventive and predictive maintenance\nChiller and central plant service\nEfficiency and reliability improvements", 'rjy-group' ),
		),
		'generators' => array(
			'title' => __( 'Generator Services', 'rjy-group' ),
			'excerpt' => __( 'Proactive maintenance, testing, and repair programs for mission-critical standby power systems.', 'rjy-group' ),
			'content' => __( "Standby power depends on routine attention to the complete system, from controls and batteries to fuel, transfer equipment, cooling, and documented testing.\n\nLoad-bank testing coordination\nPreventive maintenance\nEmergency diagnostics and repair", 'rjy-group' ),
		),
		'support-staffing' => array(
			'title' => __( 'Support Staffing', 'rjy-group' ),
			'excerpt' => __( 'Flexible facilities staffing that closes capability gaps and protects service levels.', 'rjy-group' ),
			'content' => __( "RJY Group helps facility leaders add focused technical capacity while keeping site expectations, safety practices, and reporting requirements at the center.\n\nSkilled technical personnel\nShort-term and sustained coverage\nSite-specific onboarding", 'rjy-group' ),
		),
		'water-treatment' => array(
			'title' => __( 'Water Treatment', 'rjy-group' ),
			'excerpt' => __( 'Cooling tower and closed-loop programs that reduce corrosion, scale, energy waste, and compliance risk.', 'rjy-group' ),
			'content' => __( "A consistent treatment program supports heat transfer, equipment life, safe operation, and clearer evidence of system condition over time.\n\nClosed-loop water treatment\nCooling tower programs\nTesting, reporting, and optimization", 'rjy-group' ),
		),
	);
	$created = 0;

	foreach ( $services as $slug => $service ) {
		$existing = get_page_by_path( $slug, OBJECT, 'rjy_service' );
		if ( $existing ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type' => 'rjy_service',
				'post_status' => 'publish',
				'post_name' => $slug,
				'post_title' => $service['title'],
				'post_content' => wp_kses_post( $service['content'] ),
				'post_excerpt' => $service['excerpt'],
				'menu_order' => $created,
			),
			true
		);

		if ( ! is_wp_error( $post_id ) ) {
			++$created;
		}
	}
	return $created;
}

function rjy_group_seed_standard_pages() {
	$pages = array(
		'about' => array( __( 'About', 'rjy-group' ), __( 'RJY Group is a Houston-based, veteran-owned facilities and infrastructure partner built around service, discipline, and measurable performance.', 'rjy-group' ) ),
		'about/overview' => array( __( 'Company Overview', 'rjy-group' ), __( 'RJY Group supports the systems behind safe, reliable, and efficient facilities. Our connected approach brings technical expertise and disciplined delivery to building automation, cybersecurity, HVAC, standby power, staffing, and water treatment.', 'rjy-group' ) ),
		'about/why-rjy-group' => array( __( 'Why RJY Group', 'rjy-group' ), __( 'One responsive team connects facilities expertise, operational technology security, and accountable project delivery. Recommendations account for occupants, schedules, risk, and the mission of the facility.', 'rjy-group' ) ),
		'mission-core-values' => array( __( 'Mission & Core Values', 'rjy-group' ), __( 'RJY Group exists to strengthen facility performance through technical discipline, responsive service, and responsible partnership. Service, integrity, discipline, and impact guide every engagement.', 'rjy-group' ) ),
		'about/founder' => array( __( 'About the Founder', 'rjy-group' ), __( 'Richard J. Young founded RJY Group around the belief that critical facilities deserve disciplined technical work, responsive communication, and a partner that treats uptime as a responsibility.', 'rjy-group' ) ),
		'about/leadership' => array( __( 'Leadership Team', 'rjy-group' ), __( 'RJY Group leadership keeps technical quality, responsive service, and responsible execution aligned from the first conversation through project closeout.', 'rjy-group' ) ),
		'about/certifications' => array( __( 'Certifications & Accreditations', 'rjy-group' ), __( 'RJY Group maintains company and leadership credentials relevant to facilities work and public-sector procurement. Current documentation can be discussed during qualification and proposal review.', 'rjy-group' ) ),
		'about/federal-capabilities' => array( __( 'Federal & Regional Capabilities', 'rjy-group' ), __( 'RJY Group brings connected facility capabilities, veteran-led service, and clear documentation to organizations with formal procurement and operational requirements.', 'rjy-group' ) ),
		'about/awards-press' => array( __( 'Awards & Press', 'rjy-group' ), __( 'This is the official location for verified company announcements, earned recognition, and media resources. Contact RJY Group for current information or interview requests.', 'rjy-group' ) ),
		'services' => array( __( 'Our Services', 'rjy-group' ), __( 'One accountable partner across the lifecycle of mechanical, electrical, controls, and operational technology systems.', 'rjy-group' ) ),
		'industries' => array( __( 'Industries We Serve', 'rjy-group' ), __( 'Facilities expertise for organizations where safety, continuity, and accountability cannot be compromised.', 'rjy-group' ) ),
		'industries/healthcare-hospitals' => array( __( 'Healthcare & Hospitals', 'rjy-group' ), __( 'Facilities support designed around patient safety, compliance, and uninterrupted clinical operations.', 'rjy-group' ) ),
		'industries/government-public-safety' => array( __( 'Government & Public Safety', 'rjy-group' ), __( 'Disciplined building support for agencies, civic facilities, and emergency operations.', 'rjy-group' ) ),
		'industries/industrial-commercial' => array( __( 'Industrial & Commercial Campuses', 'rjy-group' ), __( 'Integrated facility programs that align uptime, efficiency, security, and occupant needs across complex properties.', 'rjy-group' ) ),
		'industries/higher-education' => array( __( 'Higher Education', 'rjy-group' ), __( 'Reliable, efficient facilities support across academic, research, athletics, and residential buildings.', 'rjy-group' ) ),
		'industries/transportation-utilities' => array( __( 'Transportation & Utilities', 'rjy-group' ), __( 'Resilient facility operations for transit, logistics, and utility infrastructure.', 'rjy-group' ) ),
		'support' => array( __( 'Support', 'rjy-group' ), __( 'Clear answers and practical support for facility teams, technical leaders, and procurement stakeholders.', 'rjy-group' ) ),
		'support/request-quote' => array( __( 'Request an Inspection', 'rjy-group' ), __( 'Tell us about your facility, system, timing, and current concern. An RJY Group representative can follow up to clarify scope and the most practical next step.', 'rjy-group' ) ),
		'support/contact' => array( __( 'Contact Support', 'rjy-group' ), __( 'Share the facility, affected system, current condition, and preferred contact so we can route your request effectively. Do not use this website form for emergencies.', 'rjy-group' ) ),
		'support/advisor' => array( __( 'AI Service Advisor', 'rjy-group' ), __( 'Describe your facility and service needs to start a conversation with RJY Group. For urgent issues, use your established emergency procedure and call the team.', 'rjy-group' ) ),
		'support/faqs' => array( __( 'Frequently Asked Questions', 'rjy-group' ), __( 'Helpful information about service coverage, response, procurement, and project planning.', 'rjy-group' ) ),
		'support/knowledge-center' => array( __( 'Knowledge Center', 'rjy-group' ), __( 'Practical guidance for protecting uptime, improving efficiency, and managing building risk.', 'rjy-group' ) ),
		'support/resources' => array( __( 'Resources & Downloads', 'rjy-group' ), __( 'Field-ready checklists and guides for planning safer, more reliable building operations.', 'rjy-group' ) ),
		'case-studies' => array( __( 'Case Studies', 'rjy-group' ), __( 'Illustrative project approaches showing how coordinated facility strategies can address operational challenges.', 'rjy-group' ) ),
		'case-studies/project-1' => array( __( 'Controls Modernization Planning', 'rjy-group' ), __( 'An illustrative framework for bringing disconnected facility controls into a clearer operating view through system inventory, operational priorities, and phased planning.', 'rjy-group' ) ),
		'case-studies/project-2' => array( __( 'Critical Systems Risk Review', 'rjy-group' ), __( 'An illustrative approach to prioritizing maintenance and renewal needs by condition, redundancy, access, timing, and operational consequence.', 'rjy-group' ) ),
		'case-studies/project-3' => array( __( 'Operational Resilience Planning', 'rjy-group' ), __( 'An illustrative cross-system review of controls, cooling, standby power, communications, dependencies, and response readiness.', 'rjy-group' ) ),
		'testimonials' => array( __( 'Client Perspectives', 'rjy-group' ), __( 'Clear communication, disciplined execution, and practical recommendations shape the experience RJY Group aims to deliver. Client quotations are published only with approval.', 'rjy-group' ) ),
		'contact' => array( __( 'Contact RJY Group', 'rjy-group' ), __( 'Connect with our Houston team about your facility, infrastructure, or operational technology needs.', 'rjy-group' ) ),
		'privacy-security' => array( __( 'Privacy & Security', 'rjy-group' ), __( 'This notice explains the general information this website may receive when you contact RJY Group and how that information may be used.', 'rjy-group' ) ),
		'terms-of-use' => array( __( 'Terms of Use', 'rjy-group' ), __( 'This website provides general information about RJY Group. Use of the site does not create a professional-services relationship or replace a written agreement.', 'rjy-group' ) ),
		'support/knowledge-center/article-1' => array( __( 'Five Signs Your Controls Strategy Needs a Reset', 'rjy-group' ), __( 'A controls strategy may need attention when operators rely on workarounds, alarms have lost meaning, graphics no longer reflect the facility, trend data is missing, or sequences differ from current needs.', 'rjy-group' ) ),
		'support/knowledge-center/article-2' => array( __( 'A Practical OT Security Starting Point', 'rjy-group' ), __( 'Begin an OT security program by understanding what is connected, who supports it, how access is granted, and which systems carry the greatest operational consequence.', 'rjy-group' ) ),
		'support/knowledge-center/article-3' => array( __( 'Prepare Critical Equipment for Peak Season', 'rjy-group' ), __( 'Peak-season readiness connects equipment condition, controls, operating history, parts, staffing, and response planning before systems face their heaviest load.', 'rjy-group' ) ),
	);
	$created = 0;

	foreach ( $pages as $path => $page ) {
		$segments = explode( '/', $path );
		$parent_id = 0;
		$parent_path = '';
		foreach ( $segments as $index => $segment ) {
			$parent_path = '' === $parent_path ? $segment : $parent_path . '/' . $segment;
			$existing = get_page_by_path( $parent_path, OBJECT, 'page' );
			if ( $existing ) {
				$parent_id = (int) $existing->ID;
				continue;
			}
			$is_leaf = $index === count( $segments ) - 1;
			$title = $is_leaf ? $page[0] : ucwords( str_replace( array( '-', '_' ), ' ', $segment ) );
			$content = $is_leaf ? $page[1] : '';
			$post_id = wp_insert_post(
				array(
					'post_type' => 'page',
					'post_status' => 'publish',
					'post_name' => $segment,
					'post_parent' => $parent_id,
					'post_title' => $title,
					'post_content' => wp_kses_post( $content ),
				),
				true
			);
			if ( is_wp_error( $post_id ) ) {
				break;
			}
			$parent_id = (int) $post_id;
			++$created;
		}
	}
	return $created;
}

function rjy_group_content_migration_run() {
	$pages = rjy_group_seed_standard_pages();
	$services = rjy_group_seed_service_posts();
	if ( function_exists( 'rjy_group_create_default_menu' ) ) {
		rjy_group_create_default_menu();
	}
	flush_rewrite_rules( false );
	return array( 'pages' => $pages, 'services' => $services );
}
add_action( 'after_switch_theme', 'rjy_group_content_migration_run' );

function rjy_group_content_migration_admin_page() {
	add_theme_page(
		__( 'RJY Content Migration', 'rjy-group' ),
		__( 'RJY Content Migration', 'rjy-group' ),
		'edit_pages',
		'rjy-content-migration',
		'rjy_group_content_migration_screen'
	);
}
add_action( 'admin_menu', 'rjy_group_content_migration_admin_page' );

function rjy_group_content_migration_screen() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}

	if ( isset( $_POST['rjy_run_migration'] ) && check_admin_referer( 'rjy_content_migration' ) ) {
		$result = rjy_group_content_migration_run();
		$notice = sprintf( __( 'Migration complete. Created %1$d pages and %2$d service entries. Existing content was left unchanged.', 'rjy-group' ), $result['pages'], $result['services'] );
		echo '<div class="notice notice-success"><p>' . esc_html( $notice ) . '</p></div>';
	}

	echo '<div class="wrap"><h1>' . esc_html__( 'RJY Content Migration', 'rjy-group' ) . '</h1>';
	echo '<p>' . esc_html__( 'This adds the current site page structure, service records, and default editable navigation. Existing page and service content is never overwritten.', 'rjy-group' ) . '</p>';
	echo '<form method="post">';
	wp_nonce_field( 'rjy_content_migration' );
	submit_button( __( 'Run migration', 'rjy-group' ), 'primary', 'rjy_run_migration' );
	echo '</form></div>';
}
