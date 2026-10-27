<?php
/**
 * Minimal stand-ins for the WordPress functions the theme and plugin call, so
 * templates and blocks can be rendered to static HTML without a WordPress
 * install (used for visual checks against the prototype). Not a WordPress
 * emulator: only what the templates need, with plain behaviour.
 */

define( 'ABSPATH', __DIR__ . '/' );
define( 'OBJECT', 'OBJECT' );
define( 'R24H_HOME', getenv( 'R24H_HOME' ) ?: 'http://localhost:8765' );

class WP_Post {
	public $ID, $post_title = '', $post_name = '', $post_type = 'page', $post_content = '', $post_excerpt = '';
	public $post_date = '2026-10-20 10:00:00', $post_modified = '2026-10-26 10:00:00', $post_status = 'publish', $post_parent = 0, $menu_order = 0;
	public function __construct( array $data ) {
		foreach ( $data as $k => $v ) {
			$this->$k = $v;
		}
	}
}
class WP_Term {
	public $term_id, $name, $slug, $taxonomy = 'category', $description = '';
	public function __construct( array $data ) {
		foreach ( $data as $k => $v ) {
			$this->$k = $v;
		}
	}
}
class WP_Error {
	public function __construct( public $code = '', public $message = '' ) {}
	public function get_error_message() { return $this->message; }
}
class WP_Query {
	public array $posts = [];
	public int $max_num_pages = 1;
	public int $current = -1;
	public function __construct( array $args = [] ) {
		$all   = r24h_find_posts( $args );
		$per   = (int) ( $args['posts_per_page'] ?? 10 );
		$paged = max( 1, (int) ( $args['paged'] ?? 1 ) );
		$this->max_num_pages = $per > 0 ? (int) max( 1, ceil( count( $all ) / $per ) ) : 1;
		$this->posts = $per > 0 ? array_slice( $all, ( $paged - 1 ) * $per, $per ) : $all;
	}
	public function have_posts() { return $this->current + 1 < count( $this->posts ); }
	public function the_post() { $GLOBALS['post'] = $this->posts[ ++$this->current ]; }
}

$GLOBALS['r24h'] = [ 'filters' => [], 'options' => [], 'posts' => [], 'meta' => [], 'att' => [], 'terms' => [], 'shortcodes' => [], 'blocks' => [], 'ctx' => [], 'styles' => [], 'scripts' => [], 'inline' => [] ];

/* ---------- hooks ---------- */
function add_filter( $tag, $cb, $prio = 10, $n = 1 ) { $GLOBALS['r24h']['filters'][ $tag ][ $prio ][] = [ $cb, $n ]; return true; }
function add_action( $tag, $cb, $prio = 10, $n = 1 ) { return add_filter( $tag, $cb, $prio, $n ); }
function remove_action() { return true; }
function apply_filters( $tag, $value, ...$args ) {
	$f = $GLOBALS['r24h']['filters'][ $tag ] ?? [];
	ksort( $f );
	foreach ( $f as $cbs ) {
		foreach ( $cbs as [ $cb, $n ] ) {
			$value = $cb( ...array_slice( array_merge( [ $value ], $args ), 0, max( 1, $n ) ) );
		}
	}
	return $value;
}
function do_action( $tag, ...$args ) {
	$f = $GLOBALS['r24h']['filters'][ $tag ] ?? [];
	ksort( $f );
	foreach ( $f as $cbs ) {
		foreach ( $cbs as [ $cb, $n ] ) {
			$cb( ...array_slice( $args, 0, $n ) );
		}
	}
}
function register_activation_hook() {}
function register_deactivation_hook() {}

/* ---------- i18n and escaping ---------- */
function __( $s ) { return $s; }
function _x( $s ) { return $s; }
function _n( $one, $many, $n ) { return 1 === (int) $n ? $one : $many; }
function esc_html__( $s ) { return esc_html( $s ); }
function esc_attr__( $s ) { return esc_attr( $s ); }
function esc_html_e( $s ) { echo esc_html( $s ); }
function esc_attr_e( $s ) { echo esc_attr( $s ); }
function _e( $s ) { echo $s; }
function esc_html( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8', false ); }
function esc_attr( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8', false ); }
function esc_textarea( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' ); }
function esc_url( $u ) { $u = trim( (string) $u ); return preg_match( '/^\s*javascript:/i', $u ) ? '' : str_replace( [ ' ', '"' ], [ '%20', '%22' ], $u ); }
function esc_url_raw( $u ) { return esc_url( $u ); }
function wp_kses( $s, $allowed ) { return strip_tags( (string) $s, array_keys( (array) $allowed ) ); }
function wp_kses_post( $s ) { return (string) $s; }
function wp_strip_all_tags( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_key( $s ) { return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $s ) ); }
function sanitize_text_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_textarea_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_email( $s ) { return trim( (string) $s ); }
function sanitize_hex_color( $s ) { return $s; }
function sanitize_title( $s ) {
	$s = strtr( mb_strtolower( wp_strip_all_tags( (string) $s ) ), [ 'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z', 'ż' => 'z', "\u{00A0}" => ' ' ] );
	return trim( preg_replace( '/[^a-z0-9]+/', '-', $s ), '-' );
}
function antispambot( $s ) { return $s; }
function is_email( $s ) { return (bool) filter_var( $s, FILTER_VALIDATE_EMAIL ); }
function wp_json_encode( $d, $f = 0 ) { return json_encode( $d, $f ); }
function wp_parse_args( $args, $defaults = [] ) { return array_merge( (array) $defaults, is_object( $args ) ? get_object_vars( $args ) : (array) $args ); }
function wp_list_pluck( $list, $field ) { return array_map( fn( $i ) => is_object( $i ) ? $i->$field : $i[ $field ], $list ); }
function wp_trim_words( $text, $n = 55 ) { $w = preg_split( '/\s+/u', trim( (string) $text ) ); return count( $w ) > $n ? implode( ' ', array_slice( $w, 0, $n ) ) . '…' : implode( ' ', $w ); }
function wp_slash( $v ) { return $v; }
function wp_unslash( $v ) { return $v; }
function is_wp_error( $v ) { return $v instanceof WP_Error; }
function wp_date( $f, $ts = null ) { return date( $f, $ts ?? time() ); }
function current_time() { return date( 'Y-m-d H:i:s' ); }
function wp_salt() { return 'harness'; }
function checked( $a, $b = true, $echo = true ) { $r = $a == $b ? ' checked' : ''; if ( $echo ) { echo $r; } return $r; }
function selected( $a, $b = true, $echo = true ) { $r = $a == $b ? ' selected' : ''; if ( $echo ) { echo $r; } return $r; }
function shortcode_atts( $pairs, $atts ) { return array_merge( $pairs, array_intersect_key( (array) $atts, $pairs ) ); }
function status_header() {}

/* ---------- options, transients, theme ---------- */
function get_option( $k, $d = false ) { return $GLOBALS['r24h']['options'][ $k ] ?? $d; }
function update_option( $k, $v ) { $GLOBALS['r24h']['options'][ $k ] = $v; return true; }
function get_transient() { return false; }
function set_transient() { return true; }
function delete_transient() { return true; }
function get_theme_mod( $k, $d = false ) { return $d; }
function get_nav_menu_locations() { return []; }
function wp_get_nav_menu_items() { return []; }
function has_site_icon() { return false; }
function get_bloginfo( $k = 'name' ) { return [ 'name' => 'Remontuj24', 'charset' => 'UTF-8', 'description' => 'Mieszkania pod klucz · Trójmiasto' ][ $k ] ?? ''; }
function bloginfo( $k = 'name' ) { echo esc_html( get_bloginfo( $k ) ); }
function language_attributes() { echo 'lang="pl-PL"'; }
function home_url( $path = '' ) { return R24H_HOME . '/' . ltrim( (string) $path, '/' ); }
function admin_url( $path = '' ) { return R24H_HOME . '/wp-admin/' . ltrim( (string) $path, '/' ); }
function get_template_directory() { return dirname( __DIR__, 2 ) . '/wp-content/themes/remontuj24'; }
function get_template_directory_uri() { return R24H_HOME . '/wp-content/themes/remontuj24'; }
function get_stylesheet() { return 'remontuj24'; }
function get_template() { return 'remontuj24'; }
function plugin_dir_path( $f ) { return dirname( $f ) . '/'; }
function plugin_dir_url( $f ) { return R24H_HOME . '/wp-content/plugins/' . basename( dirname( $f ) ) . '/'; }
function add_query_arg( ...$a ) {
	if ( is_array( $a[0] ?? null ) ) { $args = $a[0]; $url = $a[1] ?? ( $_SERVER['REQUEST_URI'] ?? '/' ); } else { $args = [ $a[0] => $a[1] ]; $url = $a[2] ?? ( $_SERVER['REQUEST_URI'] ?? '/' ); }
	if ( ! $args ) { return $url; }
	return $url . ( str_contains( $url, '?' ) ? '&' : '?' ) . http_build_query( $args );
}
function get_privacy_policy_url() { $id = (int) get_option( 'wp_page_for_privacy_policy' ); return $id ? get_permalink( $id ) : ''; }
function is_admin() { return false; }
function is_user_logged_in() { return ! empty( $GLOBALS['r24h']['ctx']['logged_in'] ); }
function current_user_can() { return false; }
function wp_nonce_field() {}

/* registration calls that do nothing here */
foreach ( [ 'add_theme_support', 'remove_theme_support', 'add_editor_style', 'register_nav_menus', 'load_theme_textdomain', 'add_image_size', 'register_post_type', 'register_post_meta', 'register_setting', 'add_menu_page', 'add_submenu_page', 'add_meta_box', 'wp_register_script', 'wp_register_style', 'wp_dequeue_style', 'wp_add_inline_script', 'flush_rewrite_rules' ] as $fn ) {
	eval( "function $fn() { return true; }" );
}
function wp_enqueue_style( $h, $src = '' ) { if ( $src ) { $GLOBALS['r24h']['styles'][ $h ] = $src; } }
function wp_enqueue_script( $h, $src = '' ) { if ( $src ) { $GLOBALS['r24h']['scripts'][ $h ] = $src; } }
function register_block_type( $name, $args ) { $GLOBALS['r24h']['blocks'][ $name ] = $args; }

function wp_head() {
	do_action( 'wp_enqueue_scripts' );
	echo '<title>' . esc_html( $GLOBALS['r24h']['ctx']['title'] ?? 'Remontuj24' ) . "</title>\n";
	do_action( 'wp_head' );
	foreach ( $GLOBALS['r24h']['styles'] as $h => $src ) {
		echo '<link rel="stylesheet" id="' . esc_attr( $h ) . '-css" href="' . esc_url( $src ) . '">' . "\n";
	}
}
function wp_footer() {
	do_action( 'wp_footer' );
	foreach ( $GLOBALS['r24h']['scripts'] as $h => $src ) {
		echo '<script src="' . esc_url( $src ) . '" defer></script>' . "\n";
	}
}
function wp_body_open() { do_action( 'wp_body_open' ); }
function body_class() { echo 'class="' . esc_attr( implode( ' ', apply_filters( 'body_class', $GLOBALS['r24h']['ctx']['body'] ?? [] ) ) ) . '"'; }
function post_class() { echo 'class="' . esc_attr( get_post()->post_type ) . '"'; }
function get_header() { require get_template_directory() . '/header.php'; }
function get_footer() { require get_template_directory() . '/footer.php'; }

/* ---------- posts ---------- */
function r24h_add_post( array $data ): WP_Post {
	$data['ID'] = $data['ID'] ?? count( $GLOBALS['r24h']['posts'] ) + 100;
	$post       = new WP_Post( $data );
	$GLOBALS['r24h']['posts'][ $post->ID ] = $post;
	return $post;
}
function r24h_find_posts( array $args ): array {
	$type = $args['post_type'] ?? 'post';
	$out  = array_filter( $GLOBALS['r24h']['posts'], fn( $p ) => $p->post_type === $type && 'publish' === $p->post_status );
	if ( ! empty( $args['post__not_in'] ) ) {
		$out = array_filter( $out, fn( $p ) => ! in_array( $p->ID, $args['post__not_in'], true ) );
	}
	if ( ! empty( $args['category__in'] ) ) {
		$out = array_filter( $out, fn( $p ) => array_intersect( $GLOBALS['r24h']['meta'][ $p->ID ]['_cats'] ?? [], $args['category__in'] ) );
	}
	usort( $out, fn( $a, $b ) => 'realizacja' === $type ? $a->menu_order <=> $b->menu_order : strcmp( $b->post_date, $a->post_date ) );
	return array_values( $out );
}
function get_posts( $args = [] ) { $q = new WP_Query( $args + [ 'posts_per_page' => 5 ] ); return $q->posts; }
function get_post( $p = null ) {
	if ( null === $p ) { return $GLOBALS['post'] ?? null; }
	if ( $p instanceof WP_Post ) { return $p; }
	return $GLOBALS['r24h']['posts'][ (int) $p ] ?? null;
}
function get_page_by_path( $slug, $output = OBJECT, $type = 'page' ) {
	foreach ( $GLOBALS['r24h']['posts'] as $p ) {
		if ( $p->post_name === $slug && $p->post_type === $type ) { return $p; }
	}
	return null;
}
function get_permalink( $p = null ) {
	$p = get_post( $p );
	if ( ! $p ) { return ''; }
	if ( (int) get_option( 'page_on_front' ) === $p->ID ) { return home_url( '/' ); }
	return home_url( ( 'realizacja' === $p->post_type ? '/realizacje/' : '/' ) . $p->post_name . '/' );
}
function get_the_ID() { return get_post()->ID; }
function get_the_title( $p = null ) { $p = get_post( $p ); return $p ? apply_filters( 'the_title', $p->post_title, $p->ID ) : ''; }
function the_title() { echo get_the_title(); }
function get_the_content() { return get_post()->post_content; }
function the_content() { echo apply_filters( 'the_content', get_the_content() ); }
function has_excerpt( $p = null ) { return '' !== get_post( $p )->post_excerpt; }
function get_the_excerpt( $p = null ) { $p = get_post( $p ); return $p->post_excerpt ?: wp_trim_words( wp_strip_all_tags( $p->post_content ), 32 ); }
function get_post_field( $f, $p = null ) { $p = get_post( $p ); return $p ? $p->$f : ''; }
function get_post_type( $p = null ) { return get_post( $p )->post_type; }
function get_post_type_object( $t ) { return (object) [ 'labels' => (object) [ 'singular_name' => [ 'page' => 'Strona', 'post' => 'Artykuł', 'realizacja' => 'Realizacja' ][ $t ] ?? $t ] ]; }
function get_post_ancestors() { return []; }
function get_the_date( $f = 'j F Y', $p = null ) { return r24h_date( $f, get_post( $p )->post_date ); }
function get_the_modified_date( $f = 'j F Y', $p = null ) { return r24h_date( $f, get_post( $p )->post_modified ); }
function r24h_date( $f, $d ) {
	$months = [ 1 => 'stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia' ];
	$ts = strtotime( $d );
	return 'c' === $f ? date( 'c', $ts ) : str_replace( date( 'F', $ts ), $months[ (int) date( 'n', $ts ) ], date( $f, $ts ) );
}
function get_adjacent_post() { return null; }
function wp_reset_postdata() {}
function have_posts() { return $GLOBALS['r24h']['main']->have_posts(); }
function the_post() { $GLOBALS['r24h']['main']->the_post(); }
function get_post_meta( $id, $key = '', $single = false ) { return $GLOBALS['r24h']['meta'][ $id ][ $key ] ?? ( $single ? '' : [] ); }
function update_post_meta( $id, $key, $v ) { $GLOBALS['r24h']['meta'][ $id ][ $key ] = $v; }
function get_post_thumbnail_id( $p = null ) { $p = get_post( $p ); return (int) ( $GLOBALS['r24h']['meta'][ $p->ID ]['_thumbnail_id'] ?? 0 ); }
function has_post_thumbnail( $p = null ) { return (bool) get_post_thumbnail_id( $p ); }
function wp_count_posts( $type ) { return (object) [ 'publish' => count( r24h_find_posts( [ 'post_type' => $type ] ) ) ]; }
function get_queried_object() { return $GLOBALS['r24h']['ctx']['object'] ?? null; }
function get_queried_object_id() { return get_queried_object()->ID ?? 0; }
function get_search_query() { return $GLOBALS['r24h']['ctx']['search'] ?? ''; }

/* ---------- terms ---------- */
function get_the_category( $id = 0 ) {
	$id = $id ?: get_the_ID();
	return array_values( array_map( fn( $t ) => $GLOBALS['r24h']['terms'][ $t ], $GLOBALS['r24h']['meta'][ $id ]['_cats'] ?? [] ) );
}
function get_categories() {
	$used = [];
	foreach ( r24h_find_posts( [ 'post_type' => 'post' ] ) as $p ) {
		foreach ( $GLOBALS['r24h']['meta'][ $p->ID ]['_cats'] ?? [] as $t ) { $used[ $t ] = $GLOBALS['r24h']['terms'][ $t ]; }
	}
	return array_values( $used );
}
function term_description() { return ''; }
function single_term_title() { return get_queried_object()->name ?? ''; }
function get_the_archive_title() { return single_term_title(); }
function category_description() { return ''; }

/* ---------- conditionals ---------- */
function r24h_is( $k ) { return ! empty( $GLOBALS['r24h']['ctx']['is'][ $k ] ); }
function is_front_page() { return r24h_is( 'front' ); }
function is_home() { return r24h_is( 'home' ); }
function is_page( $id = null ) { return r24h_is( 'page' ) && ( null === $id || get_queried_object_id() === (int) $id ); }
function is_single() { return r24h_is( 'single' ); }
function is_singular( $type = null ) { $o = get_queried_object(); return $o instanceof WP_Post && ( null === $type || in_array( $o->post_type, (array) $type, true ) ); }
function is_archive() { return r24h_is( 'archive' ); }
function is_category() { return r24h_is( 'category' ); }
function is_tag() { return false; }
function is_search() { return r24h_is( 'search' ); }
function is_404() { return r24h_is( '404' ); }

/* ---------- attachments ---------- */
function r24h_add_image( string $file, string $alt = '' ): int {
	$id = 1000 + count( $GLOBALS['r24h']['att'] );
	[ $w, $h ] = getimagesize( $file );
	$GLOBALS['r24h']['att'][ $id ] = [ 'file' => $file, 'w' => $w, 'h' => $h ];
	$GLOBALS['r24h']['meta'][ $id ]['_wp_attachment_image_alt'] = $alt;
	return $id;
}
function r24h_image_url( $id ) { $a = $GLOBALS['r24h']['att'][ $id ]; return R24H_HOME . '/media/' . basename( $a['file'] ); }
function wp_attachment_is_image( $id ) { return isset( $GLOBALS['r24h']['att'][ (int) $id ] ); }
function wp_get_attachment_image_url( $id ) { return wp_attachment_is_image( $id ) ? r24h_image_url( $id ) : false; }
function wp_get_attachment_image_srcset( $id ) { $a = $GLOBALS['r24h']['att'][ $id ] ?? null; return $a ? r24h_image_url( $id ) . ' ' . $a['w'] . 'w' : false; }
function wp_get_attachment_image( $id, $size = 'large', $icon = false, $attr = [] ) {
	$a = $GLOBALS['r24h']['att'][ $id ] ?? null;
	if ( ! $a ) { return ''; }
	$attr = array_merge( [ 'src' => r24h_image_url( $id ), 'width' => $a['w'], 'height' => $a['h'], 'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ), 'srcset' => wp_get_attachment_image_srcset( $id ), 'loading' => 'lazy' ], $attr );
	$out = '<img';
	foreach ( $attr as $k => $v ) {
		if ( false === $v || null === $v || ( '' === $v && 'alt' !== $k ) ) { continue; }
		$out .= ' ' . $k . '="' . esc_attr( $v ) . '"';
	}
	return $out . '>';
}

/* ---------- shortcodes and blocks ---------- */
function add_shortcode( $tag, $cb ) { $GLOBALS['r24h']['shortcodes'][ $tag ] = $cb; }
function do_shortcode( $html ) {
	return preg_replace_callback( '/\[(\w+)((?:\s+\w+="[^"]*")*)\s*\]/u', function ( $m ) {
		$cb = $GLOBALS['r24h']['shortcodes'][ $m[1] ] ?? null;
		if ( ! $cb ) { return $m[0]; }
		preg_match_all( '/(\w+)="([^"]*)"/', $m[2], $pairs, PREG_SET_ORDER );
		$atts = [];
		foreach ( $pairs as $p ) { $atts[ $p[1] ] = $p[2]; }
		return $cb( $atts );
	}, (string) $html );
}
function serialize_block_attributes( $attrs ) {
	$json = json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	return strtr( $json, [ '--' => '\\u002d\\u002d', '<' => '\\u003c', '>' => '\\u003e', '&' => '\\u0026', '\\"' => '\\u0022' ] );
}
function serialize_blocks( $blocks ) {
	return implode( "\n\n", array_map( fn( $b ) => '<!-- wp:' . $b['blockName'] . ( $b['attrs'] ? ' ' . serialize_block_attributes( $b['attrs'] ) : '' ) . ' /-->', $blocks ) );
}
function render_block( $block ) {
	$name = $block['blockName'];
	$def  = $GLOBALS['r24h']['blocks'][ $name ] ?? null;
	if ( ! $def ) { return ''; }
	$attrs = [];
	foreach ( $def['attributes'] as $k => $spec ) { $attrs[ $k ] = $spec['default'] ?? null; }
	$attrs = array_merge( $attrs, (array) $block['attrs'] );
	return apply_filters( 'render_block', ( $def['render_callback'] )( $attrs ), $block );
}
function do_blocks( $html ) {
	$html = preg_replace_callback( '#<!-- wp:(r24/[\w-]+)(?: (\{.*?\}))? /-->#s', fn( $m ) => render_block( [ 'blockName' => $m[1], 'attrs' => isset( $m[2] ) ? json_decode( $m[2], true ) : [] ] ), (string) $html );
	return preg_replace( '#<!-- /?wp:[^>]*-->\n?#', '', $html );
}
function paginate_links() { return ''; }
function _wp_menu_item_classes_by_context() {}
function wp_get_nav_menu_object() { return null; }
function wp_get_document_title() { return $GLOBALS['r24h']['ctx']['title'] ?? 'Remontuj24'; }
function wp_get_attachment_image_src( $id ) { $a = $GLOBALS['r24h']['att'][ $id ] ?? null; return $a ? [ r24h_image_url( $id ), $a['w'], $a['h'] ] : false; }
function wp_get_attachment_url( $id ) { return wp_get_attachment_image_url( $id ); }
function get_site_icon_url() { return ''; }
function get_locale() { return 'pl_PL'; }
function absint( $v ) { return abs( (int) $v ); }
