<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$posts = get_posts( [ 'numberposts' => max( 1, min( 9, (int) $a['count'] ) ), 'post_status' => 'publish', 'ignore_sticky_posts' => true ] );
if ( ! $posts ) {
	return;
}
$hid     = r24_uid( 'h-guide' );
$archive = get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/' );
?>
<section<?php echo r24_section_attrs( $a, 'section guide' ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<?php echo r24_section_head( $a, $hid ); ?>
		<div class="guide-grid<?php echo 2 === count( $posts ) ? ' two' : ''; ?>" data-stagger>
			<?php foreach ( $posts as $post ) : ?>
				<?php echo r24_post_card( $post ); ?>
			<?php endforeach; ?>
		</div>
		<?php if ( $a['link_text'] ) : ?><div class="guide-foot rv"><?php echo r24_button( $a['link_text'], $archive, 'link' ); ?></div><?php endif; ?>
	</div>
</section>
