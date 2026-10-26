<?php
/**
 * Subpage heading with framed photo.
 *
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$hid = r24_uid( 'h1' );
?>
<section<?php echo r24_section_attrs( $a, 'page-hero hero' . ( $a['image'] ? '' : ' no-media' ) ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<div class="copy">
			<?php echo $a['crumbs'] ? r24_crumbs() : ''; ?>
			<?php if ( $a['eyebrow'] ) : ?><span class="eyebrow rv"><?php echo esc_html( $a['eyebrow'] ); ?></span><?php endif; ?>
			<h1 id="<?php echo esc_attr( $hid ); ?>"><?php echo r24_title( $a['title'] ?: get_the_title(), true ); ?></h1>
			<?php if ( $a['tagline'] ) : ?><p class="tagline rv"><?php echo r24_text( $a['tagline'] ); ?></p><?php endif; ?>
			<?php if ( $a['lead'] ) : ?><p class="lead rv"><?php echo r24_text( $a['lead'] ); ?></p><?php endif; ?>
			<?php echo r24_paragraphs( $a['text'], 'rv more' ); ?>
			<?php echo r24_motto( $a['motto'] ); ?>
		</div>
		<?php if ( $a['image'] ) : ?>
			<div class="media">
				<span class="frame-line" aria-hidden="true"></span>
				<div class="ph mask"><?php echo r24_img( $a['image'], '(min-width:1000px) 42vw, 100vw', [ 'fetchpriority' => 'high' ] ); ?></div>
			</div>
		<?php endif; ?>
	</div>
</section>
