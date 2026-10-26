<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$hid   = r24_uid( 'h-split' );
$class = 'section split' . ( $a['flip'] ? ' flip' : '' ) . ( $a['cream'] ? ' band' : '' ) . ( $a['tight'] ? ' tight' : '' );
?>
<section<?php echo r24_section_attrs( $a, $class ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<div class="copy">
			<?php if ( $a['eyebrow'] ) : ?><span class="eyebrow rv"><?php echo esc_html( $a['eyebrow'] ); ?></span><?php endif; ?>
			<h2 id="<?php echo esc_attr( $hid ); ?>" class="rv"><?php echo r24_title( $a['title'] ); ?></h2>
			<?php if ( $a['lead'] ) : ?><p class="lead rv"><?php echo r24_text( $a['lead'] ); ?></p><?php endif; ?>
			<?php echo r24_paragraphs( $a['text'], 'rv more' ); ?>
			<?php if ( $a['btn_text'] ) : ?><div class="ctas rv"><?php echo r24_button( $a['btn_text'], $a['btn_url'], 'btn' === $a['btn_style'] ? 'btn' : 'link' ); ?></div><?php endif; ?>
		</div>
		<?php if ( $a['image'] ) : ?><div class="ph mask"><?php echo r24_img( $a['image'], '(min-width:960px) 46vw, 100vw', [ 'loading' => 'lazy', 'class' => 'para' ] ); ?></div><?php endif; ?>
	</div>
</section>
