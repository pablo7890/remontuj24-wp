<?php
/**
 * Home hero: headline and before/after slider.
 *
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$hid = r24_uid( 'h-hero' );
?>
<section<?php echo r24_section_attrs( $a, 'hero' ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<div class="hero-copy">
			<?php if ( $a['eyebrow'] ) : ?><span class="eyebrow rv"><?php echo esc_html( $a['eyebrow'] ); ?></span><?php endif; ?>
			<h1 id="<?php echo esc_attr( $hid ); ?>"><?php echo r24_title( $a['title'], true ); ?></h1>
			<?php if ( $a['lead'] ) : ?><p class="lead rv"><?php echo r24_text( $a['lead'] ); ?></p><?php endif; ?>
			<?php if ( $a['note'] ) : ?><p class="note rv"><?php echo r24_text( $a['note'] ); ?></p><?php endif; ?>
			<div class="hero-ctas rv">
				<?php echo r24_button( $a['btn_text'], $a['btn_url'] ); ?>
				<?php echo r24_button( $a['link_text'], $a['link_url'], 'link' ); ?>
			</div>
		</div>
		<?php if ( $a['before'] && $a['after'] ) : ?>
			<div class="ba-wrap rv">
				<span class="frame-line" aria-hidden="true"></span>
				<?php
				echo r24_ba( [
					'before'   => $a['before'],
					'after'    => $a['after'],
					'before_m' => $a['before_m'],
					'after_m'  => $a['after_m'],
					'labels'   => [ $a['before_label'], $a['after_label'] ],
					'start'    => $a['start'],
					'sizes'    => '(min-width:1000px) 50vw, 100vw',
					'priority' => true,
				] );
				?>
				<?php if ( $a['caption_left'] || $a['caption_right'] ) : ?>
					<div class="ba-caption"><span><?php echo esc_html( $a['caption_left'] ); ?></span><span><?php echo esc_html( $a['caption_right'] ); ?></span></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
