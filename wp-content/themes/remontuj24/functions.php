<?php
/**
 * Remontuj24 theme. The page sections, settings and forms come from the
 * "Remontuj24 — bloki i ustawienia" plugin; the theme draws the frame around
 * them (header, footer, article and document layouts) and holds the styles.
 */

defined( 'ABSPATH' ) || exit;

const R24T_VERSION = '1.1.0';

/** Without the plugin the templates have nothing to draw with, so visitors get a simple holding page. */
if ( ! function_exists( 'r24_opt' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="notice notice-error"><p><b>Remontuj24:</b> włącz wtyczkę „Remontuj24 — bloki i ustawienia” (Wtyczki), bez niej motyw nie wyświetli strony.</p></div>';
	} );
	add_filter( 'template_include', fn() => get_template_directory() . '/inc/no-plugin.php', 99 );
	return;
}

require get_template_directory() . '/inc/template.php';

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'remontuj24', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', [ 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ] );
	add_theme_support( 'custom-logo', [ 'height' => 72, 'width' => 252, 'flex-width' => true, 'flex-height' => true ] );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'align-wide' );
	remove_theme_support( 'core-block-patterns' );
	add_editor_style( [ 'assets/css/site.css', 'assets/css/editor.css' ] );
	register_nav_menus( [
		'primary' => __( 'Menu główne', 'remontuj24' ),
		'footer'  => __( 'Menu w stopce', 'remontuj24' ),
		'legal'   => __( 'Dokumenty (dół stopki)', 'remontuj24' ),
	] );
} );

/** Only the CSS of core blocks actually used on a page. */
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

add_action( 'wp_enqueue_scripts', function () {
	$dir = get_template_directory_uri() . '/assets/';
	wp_enqueue_style( 'r24-site', $dir . 'css/site.css', [], R24T_VERSION );
	wp_enqueue_script( 'r24-site', $dir . 'js/site.js', [], R24T_VERSION, [ 'strategy' => 'defer', 'in_footer' => true ] );
	// The theme's own styles cover the button and file blocks this adds.
	wp_dequeue_style( 'classic-theme-styles' );
} );

/** The two fonts seen above the fold arrive before the CSS asks for them. */
add_action( 'wp_head', function () {
	$dir = get_template_directory_uri() . '/assets/fonts/';
	foreach ( [ 'inter-latin', 'cormorant-garamond-latin' ] as $font ) {
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( $dir . $font . '.woff2' ) );
	}
	if ( ! has_site_icon() ) {
		printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( get_template_directory_uri() . '/assets/img/favicon.svg' ) );
		printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( get_template_directory_uri() . '/assets/img/ikona-aplikacji.svg' ) );
	}
	echo '<meta name="theme-color" content="#F7F5F1">' . "\n";
}, 2 );

/** No emoji script: the site uses none and it costs a request on every page. */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );

add_filter( 'excerpt_length', fn() => 32 );
add_filter( 'excerpt_more', fn() => '…' );

/** Pages built from sections fill the width; text-only pages get the document layout. */
add_filter( 'body_class', function ( $classes ) {
	if ( is_singular() && r24t_has_sections() ) {
		$classes[] = 'has-sections';
	}
	return $classes;
} );

/** Titles: "Inspiracje · Remontuj24", and the home page shows the tagline. */
add_filter( 'document_title_separator', fn() => '·' );
