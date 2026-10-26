<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$steps = r24_items( $a['steps'] );
if ( ! $steps ) {
	return;
}
$hid  = r24_uid( 'h-proces' );
$long = (bool) array_filter( array_column( $steps, 'note' ) ) || max( array_map( fn( $s ) => mb_strlen( $s['text'] ?? '' ), $steps ) ) > 140;
?>
<section<?php echo r24_section_attrs( $a, 'section' ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<?php echo r24_section_head( $a, $hid ); ?>
		<div class="process-grid" data-process>
			<div class="process-media mask" aria-hidden="true">
				<?php foreach ( $steps as $i => $step ) : ?>
					<?php echo r24_img( $step['image'] ?? 0, '(min-width:1000px) 40vw, 1px', [ 'alt' => '', 'class' => 0 === $i ? 'on' : '', 'loading' => 'lazy' ] ); ?>
				<?php endforeach; ?>
				<span class="count"><span><?php esc_html_e( 'Etap', 'remontuj24' ); ?> <b class="step-no">01</b> / <?php echo esc_html( sprintf( '%02d', count( $steps ) ) ); ?></span><em class="step-name"><?php echo esc_html( $steps[0]['title'] ?? '' ); ?></em></span>
			</div>
			<div>
				<ol class="steps idle<?php echo $long ? ' long' : ''; ?>">
					<span class="rail" aria-hidden="true"></span><span class="rail-fill" aria-hidden="true"></span>
					<?php foreach ( $steps as $i => $step ) : ?>
						<li class="step<?php echo 0 === $i ? ' on' : ''; ?>">
							<div class="row"><span class="num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><h3><?php echo esc_html( $step['title'] ?? '' ); ?></h3></div>
							<p><?php echo r24_text( $step['text'] ?? '' ); ?></p>
							<?php if ( ! empty( $step['note'] ) ) : ?><p class="step-note"><?php echo r24_text( $step['note'] ); ?></p><?php endif; ?>
							<?php if ( ! empty( $step['image'] ) ) : ?><div class="ph"><?php echo r24_img( $step['image'], '(min-width:700px) 60vw, 100vw', [ 'loading' => 'lazy', 'class' => 'lz' ], 'medium_large' ); ?></div><?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ol>
				<?php if ( $a['link_text'] && $a['link_url'] ) : ?><div class="process-foot rv"><?php echo r24_button( $a['link_text'], $a['link_url'], 'link' ); ?></div><?php endif; ?>
			</div>
		</div>
	</div>
</section>
