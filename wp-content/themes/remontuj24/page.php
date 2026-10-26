<?php
/**
 * Pages. A page built from Remontuj24 sections is printed as is; a text page
 * (policy, terms) gets the document layout with numbered headings and contents.
 */
defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) :
	the_post();
	if ( r24t_has_sections() ) {
		the_content();
		continue;
	}
	$is_policy         = (int) get_option( 'wp_page_for_privacy_policy' ) === get_the_ID();
	$raw               = r24t_content();
	// Longer documents number their sections (01, 02…) like the policy in the design.
	[ $content, $toc ] = r24t_prose( $raw, preg_match_all( '#<h2[\s>]#', $raw ) >= 3 );
	?>
	<article <?php post_class(); ?>>
		<header class="article-head hero">
			<div class="wrap">
				<?php echo r24_crumbs(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php if ( $is_policy ) : ?><span class="eyebrow rv"><?php esc_html_e( 'Dokumenty', 'remontuj24' ); ?></span><?php endif; ?>
				<h1 class="rv"><?php echo r24_title( get_the_title() ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h1>
				<p class="doc-meta rv"><?php echo esc_html( sprintf( __( 'Ostatnia zmiana: %s', 'remontuj24' ), get_the_modified_date( 'j F Y' ) ) ); ?></p>
			</div>
		</header>
		<div class="wrap doc-body<?php echo $toc && count( $toc ) >= 3 ? '' : ' no-toc'; ?>">
			<?php echo r24t_toc( $toc, __( 'Spis treści', 'remontuj24' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="prose" data-article>
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput -- filtered post content. ?>
			</div>
		</div>
	</article>
	<?php
endwhile;
get_footer();
