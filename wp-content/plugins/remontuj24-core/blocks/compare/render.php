<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$items = array_filter( r24_items( $a['items'] ), fn( $i ) => ! empty( $i['before'] ) && ! empty( $i['after'] ) );
if ( ! $items ) {
	return;
}
$hid  = r24_uid( 'h-ba' );
$wide = 1 === count( $items );
?>
<section<?php echo r24_section_attrs( $a, 'section' . ( $a['cream'] ? ' band' : '' ) ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<?php echo r24_section_head( $a, $hid ); ?>
		<div class="compare-grid<?php echo $wide ? ' one' : ''; ?>">
			<?php foreach ( $items as $item ) : ?>
				<figure class="rv">
					<?php
					echo r24_ba( [
						'before' => $item['before'],
						'after'  => $item['after'],
						'start'  => 50,
						'sizes'  => $wide ? '(min-width:1320px) 1240px, 100vw' : '(min-width:860px) 46vw, 100vw',
						'label'  => sprintf( __( 'Porównaj: %s przed i po', 'remontuj24' ), $item['title'] ?? '' ),
					] );
					?>
					<?php if ( ! empty( $item['title'] ) || ! empty( $item['caption'] ) ) : ?>
						<figcaption><b><?php echo esc_html( $item['title'] ?? '' ); ?></b><span><?php echo esc_html( $item['caption'] ?? '' ); ?></span></figcaption>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
