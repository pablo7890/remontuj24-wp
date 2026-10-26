<?php
/**
 * Dark call to action. Phone and e-mail come from Remontuj24 → Ustawienia.
 *
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$hid = r24_uid( 'h-close' );
?>
<section<?php echo r24_section_attrs( $a, 'closing' ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<svg class="plan-art" viewBox="0 0 300 300" aria-hidden="true"><path class="w" pathLength="1" d="M300 0 H260 V260"/><path class="a" pathLength="1" d="M260 260 A260 260 0 0 1 0 0"/></svg>
	<div class="wrap">
		<div class="closing-copy">
			<?php if ( $a['eyebrow'] ) : ?><span class="eyebrow rv"><?php echo esc_html( $a['eyebrow'] ); ?></span><?php endif; ?>
			<h2 id="<?php echo esc_attr( $hid ); ?>" class="rv"><?php echo r24_title( $a['title'] ); ?></h2>
			<?php if ( $a['sub'] ) : ?><p class="sub rv"><?php echo r24_text( $a['sub'] ); ?></p><?php endif; ?>
			<?php echo r24_paragraphs( $a['text'], 'rv' ); ?>
			<?php if ( $a['btn_text'] ) : ?><div class="ctas rv"><?php echo r24_button( $a['btn_text'], $a['btn_url'] ); ?></div><?php endif; ?>
		</div>
		<div class="phone rv">
			<?php if ( $a['phone_label'] ) : ?><small><?php echo esc_html( $a['phone_label'] ); ?></small><?php endif; ?>
			<?php if ( r24_opt( 'phone' ) ) : ?><a class="tel" href="<?php echo esc_attr( r24_tel() ); ?>"><?php echo esc_html( r24_phone_display() ); ?></a><?php endif; ?>
			<?php if ( r24_opt( 'email' ) ) : ?><a href="mailto:<?php echo esc_attr( antispambot( r24_opt( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( r24_opt( 'email' ) ) ); ?></a><?php endif; ?>
		</div>
	</div>
</section>
