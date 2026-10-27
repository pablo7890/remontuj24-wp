<?php
/**
 * Checks that the settings page saves, following WordPress core's own
 * update_option() → add_option() path (sanitize callback runs twice on the
 * first save, and a stored value equal to the registered default is treated
 * as "not saved yet").
 *
 * Usage: php tools/harness/test_settings.php   (exit code 1 on failure)
 */

define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['t'] = [ 'filters' => [], 'db' => [] ];

function add_filter( $tag, $cb, $prio = 10, $n = 1 ) { $GLOBALS['t']['filters'][ $tag ][] = [ $cb, $n ]; }
function add_action( $tag, $cb, $prio = 10, $n = 1 ) { add_filter( $tag, $cb, $prio, $n ); }
function apply_filters( $tag, $value, ...$args ) {
	foreach ( $GLOBALS['t']['filters'][ $tag ] ?? [] as [ $cb, $n ] ) {
		$value = $cb( ...array_slice( array_merge( [ $value ], $args ), 0, max( 1, $n ) ) );
	}
	return $value;
}
function do_action( $tag, ...$args ) { foreach ( $GLOBALS['t']['filters'][ $tag ] ?? [] as [ $cb ] ) { $cb( ...$args ); } }
function sanitize_key( $s ) { return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $s ) ); }
function sanitize_text_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_textarea_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_email( $s ) { return filter_var( trim( (string) $s ), FILTER_VALIDATE_EMAIL ) ?: ''; }
function esc_url_raw( $s ) { return trim( (string) $s ); }
function absint( $v ) { return abs( (int) $v ); }
function current_user_can() { return true; }

/* WordPress core option logic (wp-includes/option.php), reduced to what matters here. */
function register_setting( $group, $name, $args = [] ) {
	if ( ! empty( $args['sanitize_callback'] ) ) {
		add_filter( "sanitize_option_{$name}", $args['sanitize_callback'] );
	}
	if ( array_key_exists( 'default', $args ) ) {
		$default = $args['default'];
		add_filter( "default_option_{$name}", fn( $d, $o = '', $passed = false ) => $passed ? $d : $default, 10, 3 );
	}
}
function get_option( $name, $default = false ) {
	if ( array_key_exists( $name, $GLOBALS['t']['db'] ) ) {
		return $GLOBALS['t']['db'][ $name ];
	}
	return apply_filters( "default_option_{$name}", $default, $name, func_num_args() > 1 );
}
function sanitize_option( $name, $value ) { return apply_filters( "sanitize_option_{$name}", $value, $name, $value ); }
function add_option( $name, $value ) {
	$value = sanitize_option( $name, $value );
	if ( array_key_exists( $name, $GLOBALS['t']['db'] ) ) {
		return false;
	}
	$GLOBALS['t']['db'][ $name ] = $value;
	return true;
}
function update_option( $name, $value ) {
	$old   = get_option( $name );
	$value = sanitize_option( $name, $value );
	if ( $value === $old || serialize( $value ) === serialize( $old ) ) {
		return false;
	}
	if ( apply_filters( "default_option_{$name}", false, $name, false ) === $old ) {
		return add_option( $name, $value );
	}
	$GLOBALS['t']['db'][ $name ] = $value;
	return true;
}

require dirname( __DIR__, 2 ) . '/wp-content/plugins/remontuj24-core/inc/settings.php';
do_action( 'admin_init' );

$fail = 0;
$check = function ( $label, $ok ) use ( &$fail ) {
	echo ( $ok ? 'ok   ' : 'FAIL ' ) . $label . "\n";
	$fail += $ok ? 0 : 1;
};

// 1. First save ever, on a fresh site.
update_option( 'r24_settings', [ '_tab' => 'formularz', 'form_to' => 'jack@example.com', 'form_autoreply' => '0' ] );
$check( 'first save keeps the e-mail', ( get_option( 'r24_settings' )['form_to'] ?? '' ) === 'jack@example.com' );

// 2. Another tab keeps what the first one saved.
update_option( 'r24_settings', [ '_tab' => 'social', 'instagram' => 'https://instagram.com/remontuj24' ] );
$s = get_option( 'r24_settings' );
$check( 'second tab saves', ( $s['instagram'] ?? '' ) === 'https://instagram.com/remontuj24' );
$check( 'second tab keeps first tab', ( $s['form_to'] ?? '' ) === 'jack@example.com' );

// 3. A site broken by the old version (stored empty array) recovers.
$GLOBALS['t']['db']['r24_settings'] = [];
update_option( 'r24_settings', [ '_tab' => 'naglowek', 'header_cta_text' => 'Zadzwoń', 'actionbar_enabled' => '0' ] );
$s = get_option( 'r24_settings' );
$check( 'save works after the old empty value', ( $s['header_cta_text'] ?? '' ) === 'Zadzwoń' );
$check( 'unticked checkbox saves as off', ( $s['actionbar_enabled'] ?? '' ) === '0' );

// 4. Junk keys are dropped, image ids are numbers.
update_option( 'r24_settings', [ '_tab' => 'wyglad', 'og_image' => '42abc', 'evil' => 'x' ] );
$s = get_option( 'r24_settings' );
$check( 'image id cleaned', ( $s['og_image'] ?? '' ) === '42' );
$check( 'unknown keys dropped', ! isset( $s['evil'] ) );

exit( $fail ? 1 : 0 );
