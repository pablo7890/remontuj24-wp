<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$hid = r24_uid( 'h-band' );
?>
<section<?php echo r24_section_attrs( $a, 'news-band' ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<div class="band-copy">
			<?php if ( $a['eyebrow'] ) : ?><span class="eyebrow rv"><?php echo esc_html( $a['eyebrow'] ); ?></span><?php endif; ?>
			<h2 id="<?php echo esc_attr( $hid ); ?>" class="rv"><?php echo r24_title( $a['title'] ); ?></h2>
			<?php echo r24_paragraphs( $a['text'], 'rv more' ); ?>
		</div>
		<?php if ( 'newsletter' === $a['mode'] ) : ?>
			<?php echo r24_newsletter_form( r24_uid( 'news-band' ), 'rv' ); ?>
		<?php else : ?>
			<div class="ctas rv">
				<?php echo r24_button( $a['btn_text'], $a['btn_url'] ); ?>
				<?php if ( r24_opt( 'phone' ) ) : ?><a class="link" href="<?php echo esc_attr( r24_tel() ); ?>"><?php echo esc_html( r24_phone_display() ); ?></a><?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
