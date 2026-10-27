<?php
/**
 * Page head, icon sprite, header with menu and the phone menu panel.
 */
defined( 'ABSPATH' ) || exit;
$cta_text = r24_opt( 'header_cta_text' );
$cta_url  = r24_url( r24_opt( 'header_cta_url', '/kontakt/' ) );
$cta      = $cta_text ? '<a class="btn" href="' . esc_url( $cta_url ) . '">' . esc_html( str_replace( ' o ', " o\u{00A0}", $cta_text ) ) . ' ' . r24_arrow() . '</a>' : '';
?><!doctype html>
<html <?php language_attributes(); ?><?php echo '1' === r24_opt( 'animations' ) ? '' : ' class="no-anim"'; ?>>
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
	<symbol id="ico-yt" viewBox="0 0 24 24"><rect x="2.5" y="5.5" width="19" height="13" rx="4" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M10 9.2v5.6l4.8-2.8z" fill="currentColor"/></symbol>
	<symbol id="ico-in" viewBox="0 0 24 24"><rect x="3.5" y="3.5" width="17" height="17" rx="2.5" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M8 10.5V16M8 7.6v.4M11.5 16v-5.5M11.5 13c0-1.6 1-2.6 2.3-2.6s2.2.9 2.2 2.6V16" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></symbol>
	<symbol id="ico-pin" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M11.2 9.5c1.5-1.4 4.3-.6 4.1 1.7-.2 2.2-2.4 3.2-3.6 2.2M12 10.5 9.8 19.5" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></symbol>
	<symbol id="ico-wa" viewBox="0 0 24 24"><path d="M4.5 19.5l1.1-3.6A8 8 0 1 1 8.4 18.6z" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M9.3 8.6c.3-.6.9-.5 1.1 0l.6 1.5c.1.3 0 .6-.3.9l-.4.4a5.5 5.5 0 0 0 2.4 2.4l.4-.4c.3-.3.6-.4.9-.3l1.5.6c.5.2.6.8 0 1.1-1 .7-2.2.8-3.5.1A8 8 0 0 1 9 10.9c-.5-.9-.3-1.7.3-2.3z" fill="currentColor"/></symbol>
	<symbol id="ico-tt" viewBox="0 0 24 24"><path d="M14 3.5v11.2a3.3 3.3 0 1 1-3.3-3.3M14 3.5c.4 2.7 2.2 4.5 5 4.7" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></symbol>
</svg>
<a class="skip" href="#main"><?php esc_html_e( 'Przejdź do treści', 'remontuj24' ); ?></a>
<?php if ( '1' === r24_opt( 'announce_enabled' ) && r24_opt( 'announce_text' ) ) : ?>
	<div class="announce" id="announce" data-key="<?php echo esc_attr( substr( md5( (string) r24_opt( 'announce_text' ) ), 0, 8 ) ); ?>">
		<div class="wrap">
			<p><?php echo esc_html( r24_opt( 'announce_text' ) ); ?>
				<?php if ( r24_opt( 'announce_url' ) ) : ?><a href="<?php echo esc_url( r24_url( r24_opt( 'announce_url' ) ) ); ?>"><?php echo esc_html( r24_opt( 'announce_link_text', __( 'Sprawdź', 'remontuj24' ) ) ); ?></a><?php endif; ?>
			</p>
			<button type="button" class="announce-close" aria-label="<?php esc_attr_e( 'Zamknij ogłoszenie', 'remontuj24' ); ?>"><svg viewBox="0 0 12 12" aria-hidden="true"><path d="M1 1l10 10M11 1 1 11" stroke="currentColor" stroke-width="1.3"/></svg></button>
		</div>
	</div>
<?php endif; ?>

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
		<?php if ( r24_opt( 'mmenu_text' ) ) : ?><span><?php echo esc_html( r24_opt( 'mmenu_text' ) ); ?></span><?php endif; ?>
		<?php if ( r24_opt( 'phone' ) ) : ?><a href="<?php echo esc_attr( r24_tel() ); ?>"><strong><?php echo esc_html( r24_phone_display() ); ?></strong></a><?php endif; ?>
		<?php if ( r24_opt( 'email' ) ) : ?><a href="mailto:<?php echo esc_attr( antispambot( r24_opt( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( r24_opt( 'email' ) ) ); ?></a><?php endif; ?>
		<?php if ( r24_opt( 'hours' ) ) : ?><span><?php echo esc_html( r24_opt( 'hours' ) ); ?></span><?php endif; ?>
		<?php echo $cta; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php echo r24_social(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</div>

<main id="main">
