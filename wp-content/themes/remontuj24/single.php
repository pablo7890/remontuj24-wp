<?php
/**
 * Poradnik article: heading, cover photo, contents that follows the reader,
 * a call to action under the text and two more articles to read.
 */
defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) :
	the_post();
	$cat               = r24_post_category();
	$minutes           = r24_reading_minutes();
	[ $content, $toc ] = r24t_prose( r24t_content() );
	$contact           = r24_url( r24_opt( 'contact_url', '/kontakt/' ) );
	$howto             = get_page_by_path( 'jak-dzialamy' );
	?>
	<article <?php post_class(); ?>>
		<header class="article-head hero">
			<div class="wrap">
				<?php
				$middle = [ [ r24t_guide_url(), __( 'Poradnik', 'remontuj24' ) ] ];
				echo r24t_crumbs( $middle, $cat ? $cat->name : get_the_title() ); // phpcs:ignore WordPress.Security.EscapeOutput
				?>
				<div class="meta rv">
					<?php if ( $cat ) : ?><span><?php echo esc_html( $cat->name ); ?></span><i></i><?php endif; ?>
					<span><?php echo esc_html( sprintf( __( '%d min czytania', 'remontuj24' ), $minutes ) ); ?></span><i></i>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>
				</div>
				<h1 class="rv"><?php the_title(); ?></h1>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="wrap"><figure class="article-cover mask"><?php echo r24_img( get_post_thumbnail_id(), '(min-width:1320px) 1240px, 100vw', [ 'fetchpriority' => 'high' ], 'full' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></figure></div>
		<?php endif; ?>

		<div class="wrap article-body">
			<?php echo r24t_toc( $toc, __( 'W artykule', 'remontuj24' ) ) ?: '<span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="prose" data-article>
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput -- filtered post content. ?>
				<?php if ( r24_opt( 'article_cta_title' ) ) : ?>
					<div class="cta-box">
						<span class="eyebrow"><?php echo esc_html( r24_opt( 'company_short', 'Remontuj24' ) . ' · ' . __( 'Trójmiasto', 'remontuj24' ) ); ?></span>
						<h3><?php echo esc_html( r24_opt( 'article_cta_title' ) ); ?></h3>
						<?php if ( r24_opt( 'article_cta_text' ) ) : ?><p><?php echo esc_html( r24_opt( 'article_cta_text' ) ); ?></p><?php endif; ?>
						<?php echo r24_button( r24_opt( 'article_cta_btn' ), $contact ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				<?php endif; ?>
				<div class="share"><span><?php esc_html_e( 'Udostępnij', 'remontuj24' ); ?></span><button type="button" data-copy><?php esc_html_e( 'Kopiuj link', 'remontuj24' ); ?></button></div>
			</div>
			<?php if ( r24_opt( 'article_aside' ) ) : ?>
				<aside class="aside-note">
					<b><?php esc_html_e( 'Jeden kontakt. Jeden proces.', 'remontuj24' ); ?></b>
					<span><?php echo esc_html( r24_opt( 'article_aside' ) ); ?></span>
					<?php if ( $howto ) : ?><a class="link" href="<?php echo esc_url( get_permalink( $howto ) ); ?>"><?php esc_html_e( 'Zobacz, jak działamy', 'remontuj24' ); ?> <?php echo r24_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a><?php endif; ?>
				</aside>
			<?php endif; ?>
		</div>
	</article>
	<?php
	// Two more articles: same category first, then the newest.
	$related = get_posts( [ 'post__not_in' => [ get_the_ID() ], 'posts_per_page' => 2, 'category__in' => $cat ? [ $cat->term_id ] : [], 'ignore_sticky_posts' => true ] );
	if ( count( $related ) < 2 ) {
		$related = array_merge( $related, get_posts( [ 'post__not_in' => array_merge( [ get_the_ID() ], wp_list_pluck( $related, 'ID' ) ), 'posts_per_page' => 2 - count( $related ), 'ignore_sticky_posts' => true ] ) );
	}
	if ( $related ) :
		?>
		<section class="section related" aria-labelledby="h-rel">
			<div class="wrap">
				<div class="section-head split">
					<div class="head-main">
						<span class="eyebrow rv"><?php esc_html_e( 'Poradnik', 'remontuj24' ); ?></span>
						<h2 id="h-rel" class="rv"><?php echo r24_title( __( 'Czytaj dalej.', 'remontuj24' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h2>
					</div>
					<a class="link rv" href="<?php echo esc_url( r24t_guide_url() ); ?>"><?php esc_html_e( 'Wszystkie artykuły', 'remontuj24' ); ?> <?php echo r24_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
				</div>
				<div class="guide-grid two" data-stagger>
					<?php foreach ( $related as $post_item ) { echo r24_post_card( $post_item ); } // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
			</div>
		</section>
		<?php
	endif;
endwhile;
get_footer();
