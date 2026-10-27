<?php
/**
 * Cookie banner and consent-gated analytics. Nothing tracking is loaded
 * until the visitor agrees; "Cookies" in the footer reopens the choice.
 */

defined( 'ABSPATH' ) || exit;

function r24_cookie_banner_needed(): bool {
	return '1' === r24_opt( 'cookie_banner' );
}

add_action( 'wp_enqueue_scripts', function () {
	if ( ! r24_cookie_banner_needed() ) {
		return;
	}
	wp_enqueue_script( 'r24-cookies', R24_URL . 'assets/cookies.js', [], R24_VERSION, [ 'strategy' => 'defer', 'in_footer' => true ] );
	wp_add_inline_script( 'r24-cookies', 'window.r24Consent = ' . wp_json_encode( [
		'ga4'   => preg_match( '/^G-[A-Z0-9]+$/', (string) r24_opt( 'ga4_id' ) ) ? r24_opt( 'ga4_id' ) : '',
		'pixel' => preg_match( '/^\d+$/', (string) r24_opt( 'meta_pixel' ) ) ? r24_opt( 'meta_pixel' ) : '',
	] ) . ';', 'before' );
} );

add_action( 'wp_footer', function () {
	if ( ! r24_cookie_banner_needed() ) {
		return;
	}
	$has_marketing = (bool) r24_opt( 'meta_pixel' );
	?>
	<div class="cookie-bar" id="cookie-bar" role="dialog" aria-modal="false" aria-labelledby="cookie-title" hidden>
		<div class="cookie-in">
			<p class="cookie-title" id="cookie-title"><?php echo esc_html( r24_opt( 'cookie_title', __( 'Pliki cookies', 'remontuj24' ) ) ); ?></p>
			<p class="cookie-text"><?php echo r24_with_policy_link( (string) r24_opt( 'cookie_text' ) ); ?></p>
			<div class="cookie-options" hidden>
				<label><input type="checkbox" checked disabled> <?php esc_html_e( 'Niezbędne — zawsze włączone', 'remontuj24' ); ?></label>
				<label><input type="checkbox" name="analytics"> <?php esc_html_e( 'Statystyki odwiedzin', 'remontuj24' ); ?></label>
				<?php if ( $has_marketing ) : ?><label><input type="checkbox" name="marketing"> <?php esc_html_e( 'Marketing', 'remontuj24' ); ?></label><?php endif; ?>
			</div>
			<div class="cookie-actions">
				<button type="button" class="btn" data-consent="all"><?php esc_html_e( 'Akceptuję', 'remontuj24' ); ?></button>
				<button type="button" class="cookie-link" data-consent="necessary"><?php esc_html_e( 'Tylko niezbędne', 'remontuj24' ); ?></button>
				<button type="button" class="cookie-link" data-consent="choose"><?php esc_html_e( 'Wybieram', 'remontuj24' ); ?></button>
				<button type="button" class="cookie-link" data-consent="save" hidden><?php esc_html_e( 'Zapisz wybór', 'remontuj24' ); ?></button>
			</div>
		</div>
	</div>
	<?php
	$code = (string) r24_opt( 'head_code' );
	if ( $code ) {
		// Kept inert inside <template> until analytics consent is given.
		echo '<template id="r24-consent-code">' . $code . '</template>'; // phpcs:ignore WordPress.Security.EscapeOutput -- admin-only field, unfiltered_html.
	}
}, 5 );
