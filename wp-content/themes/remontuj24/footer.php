<?php
/**
 * Footer: about, menu, contact, newsletter, documents; phone action bar.
 */
defined( 'ABSPATH' ) || exit;
$news = r24_newsletter_form( 'news' );
?>
</main>

<footer class="footer">
	<div class="wrap">
		<div class="top<?php echo $news ? '' : ' no-news'; ?>">
			<div class="about">
				<?php echo r24t_logo( 'poziome', 300 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php if ( r24_opt( 'footer_about' ) ) : ?><p><?php echo esc_html( r24_opt( 'footer_about' ) ); ?></p><?php endif; ?>
				<?php echo r24_social(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
			<nav class="fcol" aria-label="<?php esc_attr_e( 'Nawigacja w stopce', 'remontuj24' ); ?>">
				<h2 class="fh"><?php echo esc_html( r24_opt( 'footer_nav_title' ) ); ?></h2>
				<?php echo r24t_menu_links( 'footer' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</nav>
			<div class="fcol">
				<h2 class="fh"><?php echo esc_html( r24_opt( 'footer_contact_title' ) ); ?></h2>
				<?php if ( r24_opt( 'phone' ) ) : ?><a href="<?php echo esc_attr( r24_tel() ); ?>"><?php echo esc_html( r24_phone_display() ); ?></a><?php endif; ?>
				<?php if ( r24_opt( 'email' ) ) : ?><a href="mailto:<?php echo esc_attr( antispambot( r24_opt( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( r24_opt( 'email' ) ) ); ?></a><?php endif; ?>
				<?php if ( r24_opt( 'hours' ) ) : ?><span class="muted"><?php echo esc_html( r24_opt( 'hours' ) ); ?></span><?php endif; ?>
				<?php if ( r24_opt( 'tagline' ) ) : ?><span class="muted"><?php echo esc_html( r24_opt( 'tagline' ) ); ?></span><?php endif; ?>
			</div>
			<?php if ( $news ) : ?>
				<div class="fcol news-col">
					<h2 class="fh"><?php echo esc_html( r24_opt( 'news_title' ) ); ?></h2>
					<?php if ( r24_opt( 'news_text' ) ) : ?><span class="muted"><?php echo esc_html( r24_opt( 'news_text' ) ); ?></span><?php endif; ?>
					<?php echo $news; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="bottom">
			<span><?php echo esc_html( str_replace( '{rok}', wp_date( 'Y' ), (string) r24_opt( 'footer_copyright' ) ) ); ?></span>
			<button class="totop" id="totop" type="button"><?php esc_html_e( 'Do góry', 'remontuj24' ); ?> <svg aria-hidden="true"><use href="#arr"/></svg></button>
			<nav aria-label="<?php esc_attr_e( 'Dokumenty', 'remontuj24' ); ?>">
				<?php
				$legal = r24t_menu_links( 'legal' );
				if ( ! $legal ) {
					$privacy = get_privacy_policy_url();
					$legal   = ( $privacy ? '<a href="' . esc_url( $privacy ) . '">' . esc_html__( 'Polityka prywatności', 'remontuj24' ) . '</a>' : '' )
						. '<a href="#cookie-settings">' . esc_html__( 'Cookies', 'remontuj24' ) . '</a>';
				}
				echo $legal; // phpcs:ignore WordPress.Security.EscapeOutput
				?>
			</nav>
		</div>
	</div>
</footer>

<div class="cursor" id="cursor" aria-hidden="true"><?php echo esc_html( r24_opt( 'cursor_label', __( 'Przeciągnij', 'remontuj24' ) ) ); ?></div>
<?php if ( '1' === r24_opt( 'actionbar_enabled' ) ) : ?>
	<?php $wa = r24_whatsapp_url(); ?>
	<div class="actionbar<?php echo $wa ? ' three' : ''; ?>" id="actionbar">
		<?php if ( r24_opt( 'phone' ) ) : ?><a href="<?php echo esc_attr( r24_tel() ); ?>"><svg aria-hidden="true"><use href="#ico-phone"/></svg><?php echo esc_html( r24_opt( 'actionbar_call' ) ); ?></a><?php endif; ?>
		<?php if ( $wa ) : ?><a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener"><svg aria-hidden="true"><use href="#ico-wa"/></svg>WhatsApp</a><?php endif; ?>
		<a href="<?php echo esc_url( r24_url( r24_opt( 'contact_url', '/kontakt/' ) ) ); ?>"><svg aria-hidden="true"><use href="#ico-mail"/></svg><?php echo esc_html( r24_opt( 'actionbar_write' ) ); ?></a>
	</div>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
