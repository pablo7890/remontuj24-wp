<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$hid   = r24_uid( 'h-coord' );
$facts = array_filter( array_map( 'trim', preg_split( '/\R/u', (string) $a['facts'] ) ) );
?>
<section<?php echo r24_section_attrs( $a, 'section coord' ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<div class="coord-media">
			<?php if ( $a['image'] ) : ?><div class="main mask"><?php echo r24_img( $a['image'], '(min-width:960px) 52vw, 100vw', [ 'loading' => 'lazy', 'class' => 'para' ] ); ?></div><?php endif; ?>
			<?php if ( $a['inset'] ) : ?><div class="inset mask"><?php echo r24_img( $a['inset'], '(min-width:960px) 32vw, 62vw', [ 'loading' => 'lazy' ] ); ?></div><?php endif; ?>
		</div>
		<div class="coord-copy">
			<?php if ( $a['eyebrow'] ) : ?><span class="eyebrow rv"><?php echo esc_html( $a['eyebrow'] ); ?></span><?php endif; ?>
			<h2 id="<?php echo esc_attr( $hid ); ?>" class="rv"><?php echo r24_title( $a['title'] ); ?></h2>
			<?php if ( $a['lead'] ) : ?><p class="lead rv"><?php echo r24_text( $a['lead'] ); ?></p><?php endif; ?>
			<?php if ( $facts ) : ?>
				<div class="facts rv">
					<?php foreach ( $facts as $fact ) : ?><div><b aria-hidden="true">—</b><span><?php echo r24_text( $fact ); ?></span></div><?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php echo r24_button( $a['link_text'], $a['link_url'], 'link rv' ); ?>
		</div>
	</div>
</section>
