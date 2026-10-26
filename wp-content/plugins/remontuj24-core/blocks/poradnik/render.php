<?php
/**
 * Article list with category chips and pagination (?strona=2).
 *
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$paged = max( 1, (int) ( $_GET['strona'] ?? 1 ) ); // phpcs:ignore WordPress.Security.NonceVerification
$query = new WP_Query( [ 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => max( 1, (int) $a['per_page'] ), 'paged' => $paged, 'ignore_sticky_posts' => false ] );
if ( ! $query->have_posts() ) {
	return;
}
$cats = get_categories( [ 'hide_empty' => true, 'exclude' => [ (int) get_option( 'default_category' ) ] ] );
$posts = $query->posts;
?>
<section<?php echo r24_section_attrs( $a, 'section flush' ); ?> id="artykuly" aria-label="<?php esc_attr_e( 'Artykuły', 'remontuj24' ); ?>">
	<div class="wrap">
		<?php if ( $a['filter'] && count( $cats ) > 1 ) : ?>
			<div class="chips rv" role="group" aria-label="<?php esc_attr_e( 'Filtruj według kategorii', 'remontuj24' ); ?>">
				<button type="button" data-cat="all" aria-pressed="true"><?php esc_html_e( 'Wszystkie', 'remontuj24' ); ?></button>
				<?php foreach ( $cats as $cat ) : ?><button type="button" data-cat="<?php echo esc_attr( $cat->slug ); ?>" aria-pressed="false"><?php echo esc_html( $cat->name ); ?></button><?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( $a['featured'] && 1 === $paged ) : ?>
			<?php echo r24_post_card( array_shift( $posts ), true ); ?>
		<?php endif; ?>
		<?php if ( $posts ) : ?>
			<div class="guide-grid<?php echo 2 === count( $posts ) || 4 === count( $posts ) ? ' two' : ''; ?>" data-stagger>
				<?php foreach ( $posts as $post ) : ?><?php echo r24_post_card( $post ); ?><?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( $query->max_num_pages > 1 ) : ?>
			<nav class="pager" aria-label="<?php esc_attr_e( 'Kolejne strony', 'remontuj24' ); ?>">
				<?php
				echo paginate_links( [ // phpcs:ignore WordPress.Security.EscapeOutput -- core markup.
					'base'      => esc_url_raw( add_query_arg( 'strona', '%#%' ) ),
					'format'    => '',
					'current'   => $paged,
					'total'     => $query->max_num_pages,
					'prev_text' => __( 'Nowsze', 'remontuj24' ),
					'next_text' => __( 'Starsze', 'remontuj24' ),
				] );
				?>
			</nav>
		<?php endif; ?>
	</div>
</section>
<?php
wp_reset_postdata();
