<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$items = r24_items( $a['items'] );
if ( ! $items ) {
	return;
}
$hid  = r24_uid( 'h-styles' );
$line = r24_uid( 'sx' );
?>
<section<?php echo r24_section_attrs( $a, 'section styles-band' ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<?php echo r24_section_head( $a, $hid ); ?>
		<div class="styles" data-carousel="<?php echo esc_attr( $line ); ?>" data-stagger>
			<?php foreach ( $items as $i => $item ) : ?>
				<?php $tag = ! empty( $item['url'] ) ? 'a' : 'div'; ?>
				<<?php echo $tag; ?> class="style rv"<?php echo 'a' === $tag ? ' href="' . esc_url( r24_url( $item['url'] ) ) . '"' : ''; ?>>
					<?php echo r24_img( $item['image'] ?? 0, '(min-width:1000px) 28vw, 78vw', [ 'loading' => 'lazy', 'class' => 'lz' ], 'large' ); ?>
					<span class="meta"><small><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></small><strong><?php echo esc_html( $item['name'] ?? '' ); ?></strong><span><?php echo r24_text( $item['text'] ?? '' ); ?></span></span>
				</<?php echo $tag; ?>>
			<?php endforeach; ?>
		</div>
		<div class="styles-progress" aria-hidden="true"><i id="<?php echo esc_attr( $line ); ?>"></i></div>
		<div class="styles-foot">
			<span class="swipe-hint"><?php esc_html_e( 'Przesuń, aby zobaczyć kolejne →', 'remontuj24' ); ?></span>
			<?php echo r24_button( $a['link_text'], $a['link_url'], 'link' ); ?>
		</div>
	</div>
</section>
