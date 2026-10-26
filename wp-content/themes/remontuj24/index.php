<?php
/**
 * Lists of articles: a category, a tag, an author, a month, or search results.
 * The main Poradnik page is a normal page with the article list section.
 */
defined( 'ABSPATH' ) || exit;
get_header();
if ( is_search() ) {
	$eyebrow = __( 'Wyszukiwanie', 'remontuj24' );
	$title   = sprintf( __( 'Wyniki dla „%s”.', 'remontuj24' ), get_search_query() );
} elseif ( is_category() || is_tag() ) {
	$eyebrow = __( 'Poradnik', 'remontuj24' );
	$title   = single_term_title( '', false ) . '.';
} elseif ( is_archive() ) {
	$eyebrow = __( 'Poradnik', 'remontuj24' );
	$title   = wp_strip_all_tags( get_the_archive_title() ) . '.';
} else {
	$eyebrow = '';
	$title   = __( 'Poradnik.', 'remontuj24' );
}
$description = is_category() || is_tag() ? term_description() : '';
?>
<section class="page-hero hero no-media" aria-labelledby="h-list">
	<div class="wrap">
		<div class="copy">
			<?php echo r24t_crumbs( is_search() ? [] : [ [ r24t_guide_url(), __( 'Poradnik', 'remontuj24' ) ] ], wp_strip_all_tags( rtrim( $title, '.' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php if ( $eyebrow ) : ?><span class="eyebrow rv"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
			<h1 id="h-list" class="rv"><?php echo r24_title( $title ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h1>
			<?php if ( $description ) : ?><div class="lead rv"><?php echo wp_kses_post( $description ); ?></div><?php endif; ?>
		</div>
	</div>
</section>

<section class="section flush" aria-labelledby="h-list">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<div class="guide-grid" data-stagger>
				<?php
				while ( have_posts() ) {
					the_post();
					if ( 'post' === get_post_type() ) {
						echo r24_post_card( get_post() ); // phpcs:ignore WordPress.Security.EscapeOutput
					} else {
						printf(
							'<a class="card rv" href="%s"><div class="meta"><span>%s</span></div><h3>%s</h3><p>%s</p><span class="link">%s %s</span></a>',
							esc_url( get_permalink() ),
							esc_html( get_post_type_object( get_post_type() )->labels->singular_name ),
							esc_html( get_the_title() ),
							esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 24 ) ),
							esc_html__( 'Otwórz', 'remontuj24' ),
							r24_arrow() // phpcs:ignore WordPress.Security.EscapeOutput
						);
					}
				}
				?>
			</div>
			<nav class="pager" aria-label="<?php esc_attr_e( 'Kolejne strony', 'remontuj24' ); ?>">
				<?php echo paginate_links( [ 'prev_text' => __( 'Nowsze', 'remontuj24' ), 'next_text' => __( 'Starsze', 'remontuj24' ) ] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</nav>
		<?php else : ?>
			<div class="empty-note rv">
				<p class="lead"><?php is_search() ? esc_html_e( 'Nic nie znaleźliśmy. Spróbuj innego słowa albo zajrzyj do Poradnika.', 'remontuj24' ) : esc_html_e( 'Nie ma tu jeszcze artykułów.', 'remontuj24' ); ?></p>
				<?php echo r24_button( __( 'Przejdź do Poradnika', 'remontuj24' ), r24t_guide_url(), 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
