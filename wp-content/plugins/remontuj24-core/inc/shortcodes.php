<?php
/**
 * [r24_firma pole="nip"] prints company data from the settings, so documents
 * such as the privacy policy stay up to date. A missing value shows a highlighted
 * placeholder that tells the client what to fill in.
 */

defined( 'ABSPATH' ) || exit;

add_shortcode( 'r24_firma', function ( $atts ) {
	$atts  = shortcode_atts( [ 'pole' => 'company_full' ], $atts );
	$map   = [
		'nazwa'   => 'company_full',
		'marka'   => 'company_short',
		'adres'   => 'address',
		'nip'     => 'nip',
		'regon'   => 'regon',
		'telefon' => 'phone',
		'email'   => 'email',
	];
	$key   = $map[ $atts['pole'] ] ?? $atts['pole'];
	$value = r24_opt( $key );
	if ( $value ) {
		return 'email' === $key ? '<a href="mailto:' . esc_attr( antispambot( $value ) ) . '">' . esc_html( antispambot( $value ) ) . '</a>' : esc_html( $value );
	}
	$labels = [ 'company_full' => 'pełna nazwa firmy', 'address' => 'adres siedziby', 'nip' => 'NIP', 'regon' => 'REGON' ];
	// Placeholder only for logged-in editors; visitors see nothing rather than a bracket.
	return is_user_logged_in() ? '<span class="ph-var" title="Uzupełnij w Remontuj24 → Ustawienia">[' . esc_html( $labels[ $key ] ?? $key ) . ']</span>' : '';
} );

/** [r24_data] — date of the last change of the current page, e.g. in a policy. */
add_shortcode( 'r24_data', fn() => esc_html( get_the_modified_date( 'j F Y' ) ) );
