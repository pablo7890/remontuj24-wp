<?php
/**
 * Renders every template of the theme with the starter content into static HTML.
 *
 * Usage: php tools/harness/render.php <out-dir>
 * Then serve the repo root and <out-dir> together (see tools/harness/serve.py).
 */

require __DIR__ . '/stubs.php';

$root = dirname( __DIR__, 2 );
$out  = rtrim( $argv[1] ?? sys_get_temp_dir() . '/r24-site', '/' );

update_option( 'default_category', 1 );
update_option( 'show_on_front', 'page' );
update_option( 'r24_settings', getenv( 'R24H_SETTINGS' ) ? json_decode( getenv( 'R24H_SETTINGS' ), true ) : [ 'news_enabled' => '1', 'cookie_banner' => '1' ] );

require $root . '/wp-content/plugins/remontuj24-core/remontuj24-core.php';
require $root . '/wp-content/themes/remontuj24/functions.php';
add_filter( 'the_content', 'do_blocks', 9 );
add_filter( 'the_content', 'do_shortcode', 11 );
do_action( 'after_setup_theme' );
do_action( 'init' );

/* media: the starter photos with their alt texts */
$media = [];
foreach ( glob( R24_DIR . 'demo/img/*.webp' ) as $file ) {
	$name           = basename( $file, '.webp' );
	$media[ $name ] = r24h_add_image( $file, r24_starter_alts()[ $name ] ?? '' );
}
$img = function ( $attrs ) use ( &$img, $media ) {
	array_walk_recursive( $attrs, function ( &$v ) use ( $media ) {
		if ( is_string( $v ) && str_starts_with( $v, 'img:' ) ) {
			$v = $media[ substr( $v, 4 ) ] ?? 0;
		}
	} );
	return $attrs;
};

/* pages, articles, policy, one project */
foreach ( require R24_DIR . 'demo/pages.php' as $page ) {
	$blocks = array_map( fn( $b ) => [ 'blockName' => 'r24/' . $b[0], 'attrs' => $img( $b[1] ) ], $page['blocks'] );
	$p      = r24h_add_post( [ 'post_name' => $page['slug'], 'post_title' => $page['title'], 'post_content' => serialize_blocks( $blocks ) ] );
	if ( ! empty( $page['front'] ) ) {
		update_option( 'page_on_front', $p->ID );
	}
}
$terms = [];
foreach ( require R24_DIR . 'demo/articles.php' as $a ) {
	$slug = sanitize_title( $a['category'] );
	if ( ! isset( $terms[ $slug ] ) ) {
		$terms[ $slug ] = 10 + count( $terms );
		$GLOBALS['r24h']['terms'][ $terms[ $slug ] ] = new WP_Term( [ 'term_id' => $terms[ $slug ], 'name' => $a['category'], 'slug' => $slug ] );
	}
	$p = r24h_add_post( [ 'post_type' => 'post', 'post_name' => $a['slug'], 'post_title' => $a['title'], 'post_date' => $a['date'], 'post_excerpt' => $a['excerpt'], 'post_content' => $a['content'] ] );
	update_post_meta( $p->ID, '_cats', [ $terms[ $slug ] ] );
	update_post_meta( $p->ID, '_thumbnail_id', $media[ $a['image'] ] );
}
$policy = r24h_add_post( [ 'post_name' => 'polityka-prywatnosci', 'post_title' => 'Polityka prywatności i plików cookies', 'post_content' => require R24_DIR . 'demo/policy.php' ] );
update_option( 'wp_page_for_privacy_policy', $policy->ID );
$projects = getenv( 'R24H_PROJECTS' ) === '0' ? 0 : 1;
for ( $i = 0; $i < $projects; $i++ ) {
	$p = r24h_add_post( [ 'post_type' => 'realizacja', 'post_name' => 'mieszkanie-z-salonem-na-dwie-strony', 'post_title' => 'Mieszkanie z salonem na dwie strony', 'post_content' => r24_starter_example_content() ] );
	foreach ( [ 'miasto' => 'Gdańsk', 'metraz' => '58 m²', 'zakres' => 'Pod klucz', 'czas' => '14 tygodni', 'lead' => 'Stan deweloperski, właściciel na co dzień poza Trójmiastem i jeden cel: gotowe wnętrze bez osobnych ustaleń z kilkoma ekipami.', 'przed' => $media['hero-przed-1024'], 'po' => $media['hero-po-1024'], 'galeria' => [ $media['proces-6-1600'], $media['kuchnia-p-720'], $media['lazienka-po-1024'], $media['proces-5-1600'] ], 'opinia' => 'Najbardziej ceniłem to, że miałem jeden kontakt i zawsze wiedziałem, na jakim etapie są prace.', 'opinia_autor' => 'Przykładowa opinia właściciela', 'przyklad' => true ] as $k => $v ) {
		update_post_meta( $p->ID, 'r24_' . $k, $v );
	}
	update_post_meta( $p->ID, '_thumbnail_id', $media['hero-po-1024'] );
}

/* render one route */
function r24h_render( string $template, array $ctx, string $file ): void {
	$GLOBALS['r24h']['ctx']    = $ctx;
	$GLOBALS['r24h']['styles'] = $GLOBALS['r24h']['scripts'] = [];
	$q        = new WP_Query( [ 'posts_per_page' => 0 ] );
	$q->posts = $ctx['posts'] ?? ( isset( $ctx['object'] ) && $ctx['object'] instanceof WP_Post ? [ $ctx['object'] ] : [] );
	$GLOBALS['r24h']['main'] = $q;
	$GLOBALS['post']         = $q->posts[0] ?? null;
	$_SERVER['REQUEST_URI']  = $ctx['uri'] ?? '/';
	ob_start();
	require get_template_directory() . '/' . $template;
	$html = ob_get_clean();
	@mkdir( dirname( $file ), 0777, true );
	file_put_contents( $file, $html );
	echo basename( dirname( $file ) ) . '/' . basename( $file ) . ' ' . strlen( $html ) . "\n";
}

foreach ( $GLOBALS['r24h']['posts'] as $p ) {
	$path = parse_url( get_permalink( $p ), PHP_URL_PATH );
	$type = $p->post_type;
	$is   = 'page' === $type ? [ 'page' => true, 'front' => (int) get_option( 'page_on_front' ) === $p->ID ] : [ 'single' => true ];
	$tpl  = [ 'page' => 'page.php', 'post' => 'single.php', 'realizacja' => 'single-realizacja.php' ][ $type ];
	r24h_render( $tpl, [ 'object' => $p, 'is' => $is, 'uri' => $path, 'title' => $p->post_title . ' · Remontuj24', 'body' => [ $type ] ], $out . $path . 'index.html' );
}
$cat = reset( $GLOBALS['r24h']['terms'] );
r24h_render( 'index.php', [ 'object' => $cat, 'is' => [ 'archive' => true, 'category' => true ], 'posts' => get_posts( [ 'category__in' => [ $cat->term_id ] ] ), 'uri' => '/kategoria/' . $cat->slug . '/' ], $out . '/kategoria/' . $cat->slug . '/index.html' );
r24h_render( 'index.php', [ 'is' => [ 'search' => true ], 'search' => 'łazienka', 'posts' => [], 'uri' => '/?s=x' ], $out . '/szukaj/index.html' );
r24h_render( '404.php', [ 'is' => [ '404' => true ] ], $out . '/404/index.html' );
