<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$items = r24_items( $a['items'] );
if ( ! $items ) {
	return;
}
$hid   = r24_uid( 'h-factors' );
$icons = [
	'plan'   => '<path d="M5 29V5h24v24H19"/><path d="M11 29v-8"/><path d="M11 21a8 8 0 0 1 8 8" stroke-width="1"/>',
	'person' => '<circle cx="17" cy="13" r="6"/><path d="M6 30c1.5-6 6-9 11-9s9.5 3 11 9"/>',
	'bars'   => '<path d="M4 28h26M8 28V16M14 28V10M20 28V13M26 28V6"/>',
	'check'  => '<rect x="5" y="5" width="24" height="24"/><path d="M11 17l4 4 8-9"/>',
	'house'  => '<path d="M3 31h28M6 31V12l11-8 11 8v19"/><path d="M13 31v-9h8v9"/>',
	'target' => '<circle cx="17" cy="17" r="13"/><path d="M17 4v26M4 17h26"/><circle cx="17" cy="17" r="3" fill="currentColor" stroke="none"/>',
	'screen' => '<rect x="3" y="7" width="22" height="15"/><path d="M1 26h26"/><rect x="26" y="12" width="7" height="14" rx="1"/>',
];
?>
<section<?php echo r24_section_attrs( $a, 'section' . ( $a['tight'] ? ' flush' : '' ) ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<?php echo r24_section_head( $a, $hid ); ?>
		<div class="factors cols-<?php echo (int) min( 4, count( $items ) ); ?>" data-stagger>
			<?php foreach ( $items as $i => $item ) : ?>
				<div class="factor rv">
					<?php
					$icon = $item['icon'] ?? 'number';
					if ( 'number' === $icon ) {
						echo '<span class="num">' . esc_html( sprintf( '%02d', $i + 1 ) ) . '</span>';
					} elseif ( isset( $icons[ $icon ] ) ) {
						echo '<svg viewBox="0 0 34 34" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true">' . $icons[ $icon ] . '</svg>'; // phpcs:ignore -- fixed markup above.
					}
					?>
					<h3><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
					<p><?php echo r24_text( $item['text'] ?? '' ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( $a['closing'] ) : ?><p class="closing-line rv"><?php echo r24_title( $a['closing'] ); ?></p><?php endif; ?>
	</div>
</section>
