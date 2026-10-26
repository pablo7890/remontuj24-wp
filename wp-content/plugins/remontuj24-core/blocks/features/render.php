<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$items = r24_items( $a['items'] );
if ( ! $items ) {
	return;
}
$hid = r24_uid( 'h-styles' );
$ids = [];
foreach ( $items as $i => $item ) {
	$ids[ $i ] = sanitize_title( $item['name'] ?? '' ) ?: 'styl-' . ( $i + 1 );
}
?>
<section<?php echo r24_section_attrs( $a, 'section tight' ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<?php if ( $a['eyebrow'] || $a['title'] ) : ?>
			<div class="section-head split">
				<div class="head-main">
					<?php if ( $a['eyebrow'] ) : ?><span class="eyebrow rv"><?php echo esc_html( $a['eyebrow'] ); ?></span><?php endif; ?>
					<h2 id="<?php echo esc_attr( $hid ); ?>" class="rv"><?php echo r24_title( $a['title'] ); ?></h2>
				</div>
				<?php if ( $a['index'] ) : ?>
					<nav class="style-index rv" aria-label="<?php esc_attr_e( 'Spis stylów', 'remontuj24' ); ?>">
						<?php foreach ( $items as $i => $item ) : ?><a href="#<?php echo esc_attr( $ids[ $i ] ); ?>"><b><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></b><?php echo esc_html( $item['name'] ?? '' ); ?></a><?php endforeach; ?>
					</nav>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="features">
			<?php foreach ( $items as $i => $item ) : ?>
				<?php
				$fid    = $hid . '-' . ( $i + 1 );
				$colors = array_filter( array_map( 'trim', explode( ',', (string) ( $item['colors'] ?? '' ) ) ), fn( $c ) => (bool) sanitize_hex_color( $c ) );
				?>
				<article class="feature" id="<?php echo esc_attr( $ids[ $i ] ); ?>" aria-labelledby="<?php echo esc_attr( $fid ); ?>">
					<div class="ph mask"><?php echo r24_img( $item['image'] ?? 0, '(min-width:960px) 56vw, 100vw', [ 'loading' => 'lazy' ] ); ?></div>
					<div class="copy">
						<span class="num rv"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<?php if ( ! empty( $item['name'] ) ) : ?><span class="eyebrow rv"><?php echo esc_html( $item['name'] ); ?></span><?php endif; ?>
						<h3 id="<?php echo esc_attr( $fid ); ?>" class="rv"><?php echo r24_title( $item['tagline'] ?? '' ); ?></h3>
						<p class="rv"><?php echo r24_text( $item['text'] ?? '' ); ?></p>
						<?php if ( $colors ) : ?>
							<div class="palette rv" aria-label="<?php echo esc_attr( sprintf( __( 'Paleta: %s', 'remontuj24' ), $item['colors_label'] ?? '' ) ); ?>">
								<?php foreach ( $colors as $c ) : ?><i style="background:<?php echo esc_attr( sanitize_hex_color( $c ) ); ?>"></i><?php endforeach; ?>
								<?php if ( ! empty( $item['colors_label'] ) ) : ?><span><?php echo esc_html( $item['colors_label'] ); ?></span><?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
