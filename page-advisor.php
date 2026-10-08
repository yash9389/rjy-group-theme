<?php
/**
 * AI Service Advisor — mirrors src/routes/support.advisor.tsx.
 * Recommendations are produced in the browser by assets/js/advisor.js.
 */
get_header();
$buildings = array( 'Hospital or medical campus', 'Government or public safety facility', 'Industrial plant', 'Commercial office campus', 'University or school campus', 'Transit or utility facility', 'Data center' );
$advisor_data = array(
	'services' => array_map( static function ( $item ) { return array( 'title' => $item['title'], 'url' => rjy_group_url( $item['path'] ), 'description' => $item['description'] ); }, rjy_group_services() ),
	'industries' => array_map( static function ( $item ) { return array( 'title' => $item['title'], 'url' => rjy_group_url( $item['path'] ), 'description' => $item['description'] ); }, rjy_group_industries() ),
	'quoteUrl' => rjy_group_url( '/support/request-quote' ),
	'resourcesUrl' => rjy_group_url( '/support/resources' ),
	'knowledgeUrl' => rjy_group_url( '/support/knowledge-center' ),
	'tollFree' => rjy_group_contact_mod( 'rjy_toll_free' ),
	'tollFreeHref' => rjy_group_phone_href( rjy_group_contact_mod( 'rjy_toll_free' ) ),
	'icons' => array( 'arrow' => rjy_group_icon( 'arrow-right', 'h-4 w-4 transition group-hover:translate-x-1' ), 'phone' => rjy_group_icon( 'phone', 'h-4 w-4' ) ),
);
?>
<main class="bg-muted/40"><section class="bg-primary text-primary-foreground"><div class="site-container py-16 md:py-20"><nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'rjy-group' ); ?>" class="mb-6 text-sm opacity-80"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'rjy-group' ); ?></a> / <span><?php esc_html_e( 'Support', 'rjy-group' ); ?></span> / <span aria-current="page"><?php esc_html_e( 'AI Service Advisor', 'rjy-group' ); ?></span></nav><p class="mb-3 flex items-center gap-2 text-sm font-extrabold uppercase tracking-widest"><?php echo rjy_group_icon( 'sparkles', 'h-4 w-4' ); ?> <?php esc_html_e( 'Guided matching', 'rjy-group' ); ?></p><h1 class="max-w-3xl text-4xl font-black leading-tight md:text-5xl"><?php esc_html_e( 'Find the right services for your facility', 'rjy-group' ); ?></h1><p class="mt-4 max-w-2xl text-lg opacity-90"><?php esc_html_e( 'Tell us about your building and what keeps your team up at night. Our advisor recommends the RJY Group services and resources that fit.', 'rjy-group' ); ?></p></div></section>
<section class="site-container grid gap-8 py-12 lg:grid-cols-[minmax(0,420px)_1fr]"><form id="rjy-advisor" class="h-fit space-y-5 rounded-2xl border bg-background p-6 shadow-sm"><div><label for="bt" class="mb-2 block text-sm font-extrabold"><?php esc_html_e( 'Building type', 'rjy-group' ); ?></label><input id="bt" list="bt-list" required minlength="2" maxlength="120" placeholder="<?php esc_attr_e( 'e.g. 400-bed hospital', 'rjy-group' ); ?>" class="w-full rounded-lg border bg-background px-4 py-3"><datalist id="bt-list"><?php foreach ( $buildings as $building ) : ?><option value="<?php echo esc_attr( $building ); ?>"></option><?php endforeach; ?></datalist></div><div><label for="needs" class="mb-2 block text-sm font-extrabold"><?php esc_html_e( 'Operational needs and challenges', 'rjy-group' ); ?></label><textarea id="needs" required minlength="10" maxlength="1500" rows="7" placeholder="<?php esc_attr_e( 'e.g. Aging chillers, frequent BAS alarms, vendors need remote access, and we must pass generator inspections.', 'rjy-group' ); ?>" class="w-full rounded-lg border bg-background px-4 py-3"></textarea></div><button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-6 py-4 font-extrabold text-primary-foreground disabled:opacity-60"><?php esc_html_e( 'Get recommendations', 'rjy-group' ); ?> <?php echo rjy_group_icon( 'arrow-right', 'h-5 w-5' ); ?></button><p class="text-xs text-muted-foreground"><?php esc_html_e( 'Recommendations are automated guidance. An RJY specialist will confirm scope during an inspection.', 'rjy-group' ); ?></p></form><div id="rjy-advisor-result" aria-live="polite"><div class="rounded-2xl border border-dashed bg-background p-10 text-center text-muted-foreground"><?php esc_html_e( 'Your tailored recommendations will appear here.', 'rjy-group' ); ?></div></div></section></main>
<script>window.rjyAdvisor = <?php echo wp_json_encode( $advisor_data ); ?>;</script>
<?php get_footer();
