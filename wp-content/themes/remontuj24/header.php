<?php
/**
 * Page head, icon sprite, header with menu and the phone menu panel.
 */
defined( 'ABSPATH' ) || exit;
$cta_text = r24_opt( 'header_cta_text' );
$cta_url  = r24_url( r24_opt( 'header_cta_url', '/kontakt/' ) );
$cta      = $cta_text ? '<a class="btn" href="' . esc_url( $cta_url ) . '">' . esc_html( str_replace( ' o ', " o\u{00A0}", $cta_text ) ) . ' ' . r24_arrow() . '</a>' : '';
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
	<symbol id="arr" viewBox="0 0 22 10"><path d="M0 5h20M16 1l4 4-4 4" fill="none" stroke="currentColor" stroke-width="1.3"/></symbol>
	<symbol id="ico-phone" viewBox="0 0 24 24"><path d="M5 3h4l2 5-2.5 1.5a11 11 0 0 0 6 6L16 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 5a2 2 0 0 1 2-2" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></symbol>
	<symbol id="ico-mail" viewBox="0 0 24 24"><path d="M3 6h18v12H3zM3 7l9 6 9-6" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></symbol>
	<symbol id="ico-ig" viewBox="0 0 24 24"><rect x="3.5" y="3.5" width="17" height="17" rx="5" fill="none" stroke="currentColor" stroke-width="1.4"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.4"/><circle cx="17.3" cy="6.7" r="1" fill="currentColor"/></symbol>
	<symbol id="ico-fb" viewBox="0 0 24 24"><path d="M14 8h3V4.5h-3a4 4 0 0 0-4 4V11H7.5v3.5H10V21h3.5v-6.5H16.5l.5-3.5h-3.5V8.8c0-.5.3-.8.8-.8z" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></symbol>
	<symbol id="ico-tt" viewBox="0 0 24 24"><path d="M14 3.5v11.2a3.3 3.3 0 1 1-3.3-3.3M14 3.5c.4 2.7 2.2 4.5 5 4.7" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></symbol>
</svg>
<a class="skip" href="#main"><?php esc_html_e( 'Przejdź do treści', 'remontuj24' ); ?></a>

<header class="header" id="top">
	<div class="wrap">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '%s, strona główna', 'remontuj24' ), get_bloginfo( 'name' ) ) ); ?>"><?php echo r24t_logo(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		<nav class="nav" aria-label="<?php esc_attr_e( 'Główna', 'remontuj24' ); ?>"><?php echo r24t_menu_links( 'primary' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></nav>
		<?php echo $cta; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<span class="progress" id="progress" aria-hidden="true"></span>
		<button class="burger" id="burger" type="button" aria-label="<?php esc_attr_e( 'Otwórz menu', 'remontuj24' ); ?>" aria-expanded="false" aria-controls="mmenu"><span></span></button>
	</div>
</header>

<div class="mmenu" id="mmenu" aria-label="<?php esc_attr_e( 'Menu', 'remontuj24' ); ?>">
	<ol><?php echo r24t_menu_links( 'primary', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?></ol>
	<div class="contact">
		<span><?php esc_html_e( 'Pierwszy krok to rozmowa', 'remontuj24' ); ?></span>
		<?php if ( r24_opt( 'phone' ) ) : ?><a href="<?php echo esc_attr( r24_tel() ); ?>"><strong><?php echo esc_html( r24_phone_display() ); ?></strong></a><?php endif; ?>
		<?php if ( r24_opt( 'email' ) ) : ?><a href="mailto:<?php echo esc_attr( antispambot( r24_opt( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( r24_opt( 'email' ) ) ); ?></a><?php endif; ?>
		<?php echo $cta; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php echo r24_social(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</div>

<main id="main">
