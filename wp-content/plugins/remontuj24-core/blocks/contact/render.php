<?php
/**
 * Contact details and the contact form. Works without JavaScript too:
 * the form posts to admin-post.php and comes back with ?wyslano=1.
 *
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$hid  = r24_uid( 'h1' );
$sent = isset( $_GET['wyslano'] ); // phpcs:ignore WordPress.Security.NonceVerification -- display only.
$err  = isset( $_GET['blad'] ); // phpcs:ignore WordPress.Security.NonceVerification -- display only.
$rows = r24_items( $a['rows'] );
?>
<section<?php echo r24_section_attrs( $a, 'hero contact-hero' ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap contact-grid">
		<div class="contact-info">
			<?php echo r24_crumbs(); ?>
			<?php if ( $a['eyebrow'] ) : ?><span class="eyebrow rv"><?php echo esc_html( $a['eyebrow'] ); ?></span><?php endif; ?>
			<h1 id="<?php echo esc_attr( $hid ); ?>"><?php echo r24_title( $a['title'] ?: get_the_title(), true ); ?></h1>
			<span class="rule rv" aria-hidden="true"></span>
			<div class="firm rv"><b><?php echo esc_html( mb_strtoupper( (string) r24_opt( 'company_short' ) ) ); ?></b><span><?php echo esc_html( r24_opt( 'tagline' ) ); ?></span></div>
			<?php if ( r24_opt( 'phone' ) ) : ?><a class="phone rv" href="<?php echo esc_attr( r24_tel() ); ?>"><?php echo esc_html( r24_phone_display() ); ?></a><?php endif; ?>
			<?php if ( r24_opt( 'email' ) ) : ?><a class="mail rv" href="mailto:<?php echo esc_attr( antispambot( r24_opt( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( r24_opt( 'email' ) ) ); ?></a><?php endif; ?>
			<?php if ( $rows ) : ?>
				<div class="hours rv">
					<?php foreach ( $rows as $row ) : ?><div><span><?php echo esc_html( $row['label'] ?? '' ); ?></span><b><?php echo esc_html( $row['value'] ?? '' ); ?></b></div><?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php echo str_replace( 'class="social"', 'class="social rv"', r24_social() ); ?>
		</div>
		<?php echo r24_contact_form( $a, $sent, $err ); ?>
	</div>
</section>
