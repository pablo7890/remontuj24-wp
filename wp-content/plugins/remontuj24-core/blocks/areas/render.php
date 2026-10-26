<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$items = r24_items( $a['items'] );
$hid   = r24_uid( 'h-areas' );
?>
<section<?php echo r24_section_attrs( $a, 'section tight' ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<div class="areas-grid">
			<div class="areas-media mask" aria-hidden="true"><?php echo r24_img( $a['image'], '34vw', [ 'alt' => '', 'loading' => 'lazy' ] ); ?></div>
			<div>
				<?php echo r24_section_head( [ 'eyebrow' => $a['eyebrow'], 'title' => $a['title'] ], $hid, false ); ?>
				<ul class="areas">
					<?php foreach ( $items as $item ) : ?>
						<li class="area rv"><h3><?php echo esc_html( $item['title'] ?? '' ); ?></h3><p><?php echo r24_text( $item['text'] ?? '' ); ?></p></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</section>
