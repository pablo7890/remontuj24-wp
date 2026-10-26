<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$items = r24_items( $a['items'] );
if ( ! $items ) {
	return;
}
?>
<section<?php echo r24_section_attrs( $a, 'pillars' ); ?> aria-label="<?php esc_attr_e( 'Najważniejsze w skrócie', 'remontuj24' ); ?>">
	<div class="wrap" style="--cols:<?php echo (int) min( 4, count( $items ) ); ?>">
		<?php foreach ( $items as $i => $item ) : ?>
			<div class="pillar rv"><span class="num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><h3><?php echo esc_html( $item['title'] ?? '' ); ?></h3><p><?php echo r24_text( $item['text'] ?? '' ); ?></p></div>
		<?php endforeach; ?>
	</div>
</section>
