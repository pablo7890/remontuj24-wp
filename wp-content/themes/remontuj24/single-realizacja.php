<?php
/**
 * One project (Realizacje): facts, before/after slider, the story, gallery,
 * a link to the next project and the closing invitation.
 */
defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) :
	the_post();
	$r     = r24_realizacja();
	$list  = r24_realizacje_page_id();
	$facts = array_filter( [
		__( 'Lokalizacja', 'remontuj24' )     => $r['miasto'],
		__( 'Metraż', 'remontuj24' )          => $r['metraz'],
		__( 'Zakres', 'remontuj24' )          => $r['zakres'],
		__( 'Czas realizacji', 'remontuj24' ) => $r['czas'],
	] );
	// Photos in groups of four (wide, tall, two square); the remainder still fills whole rows.
	$gallery = $r['galeria'];
	$shapes  = [];
	$full    = intdiv( count( $gallery ), 4 ) * 4;
	foreach ( $gallery as $i => $id ) {
		$left     = count( $gallery ) - $full;
		$shapes[] = $i < $full ? [ 'wide', 'tall', '', '' ][ $i % 4 ] : ( 1 === $left ? 'wide full' : ( 2 === $left && $i === $full + 1 ? 'wide' : '' ) );
	}
	$next = get_adjacent_post( false, '', false ) ?: get_adjacent_post( false, '', true );
	?>
	<article <?php post_class(); ?>>
		<header class="article-head hero">
			<div class="wrap">
				<?php echo r24t_crumbs( $list ? [ [ get_permalink( $list ), get_the_title( $list ) ] ] : [], $r['miasto'] ?: get_the_title() ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<div class="meta rv"><span><?php esc_html_e( 'Realizacja', 'remontuj24' ); ?></span><?php if ( $r['przyklad'] ) : ?><i></i><span><?php esc_html_e( 'Przykład', 'remontuj24' ); ?></span><?php endif; ?></div>
				<h1 class="rv"><?php echo r24_title( get_the_title() . ( preg_match( '/[.!?]$/u', get_the_title() ) ? '' : '.' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h1>
				<?php if ( $r['lead'] ) : ?><p class="lead rv"><?php echo esc_html( $r['lead'] ); ?></p><?php endif; ?>
			</div>
		</header>

		<?php if ( $facts ) : ?>
			<div class="wrap">
				<dl class="facts-list rv">
					<?php foreach ( $facts as $label => $value ) : ?><div><dt><?php echo esc_html( $label ); ?></dt><dd><?php echo esc_html( $value ); ?></dd></div><?php endforeach; ?>
				</dl>
			</div>
		<?php endif; ?>

		<?php if ( $r['przed'] && $r['po'] ) : ?>
			<section class="section proj-ba" aria-labelledby="h-ba">
				<div class="wrap">
					<h2 id="h-ba" class="visually-hidden"><?php esc_html_e( 'Przed i po', 'remontuj24' ); ?></h2>
					<?php
					echo r24_ba( [ // phpcs:ignore WordPress.Security.EscapeOutput
						'before'   => $r['przed'],
						'after'    => $r['po'],
						'sizes'    => '(min-width:1320px) 1240px, 100vw',
						'class'    => 'ba-wide mask',
						'priority' => true,
						'label'    => sprintf( __( 'Porównaj: %s przed i po', 'remontuj24' ), get_the_title() ),
					] );
					?>
				</div>
			</section>
		<?php elseif ( $r['po'] || has_post_thumbnail() ) : ?>
			<div class="wrap proj-cover"><figure class="article-cover mask"><?php echo r24_img( $r['po'] ?: get_post_thumbnail_id(), '(min-width:1320px) 1240px, 100vw', [ 'fetchpriority' => 'high' ], 'full' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></figure></div>
		<?php endif; ?>

		<div class="wrap proj-story">
			<div class="prose">
				<?php echo r24t_prose( r24t_content() )[0]; // phpcs:ignore WordPress.Security.EscapeOutput -- filtered post content. ?>
				<?php if ( $r['opinia'] ) : ?>
					<blockquote class="quote">„<?php echo esc_html( trim( $r['opinia'], '„”"' ) ); ?>”<?php if ( $r['opinia_autor'] ) : ?><cite><?php echo esc_html( $r['opinia_autor'] ); ?></cite><?php endif; ?></blockquote>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $gallery ) : ?>
			<section class="section proj-gallery" aria-labelledby="h-gal">
				<div class="wrap">
					<div class="section-head split">
						<div class="head-main">
							<span class="eyebrow rv"><?php esc_html_e( 'Galeria', 'remontuj24' ); ?></span>
							<h2 id="h-gal" class="rv"><?php echo r24_title( __( 'Gotowe wnętrze.', 'remontuj24' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h2>
						</div>
					</div>
					<div class="gallery" data-stagger>
						<?php foreach ( $gallery as $i => $id ) : ?>
							<?php $sizes = str_contains( $shapes[ $i ], 'wide' ) ? '(min-width:860px) 66vw, 100vw' : '(min-width:860px) 33vw, 50vw'; ?>
							<div class="ph rv <?php echo esc_attr( $shapes[ $i ] ); ?>"><?php echo r24_img( $id, $sizes, [ 'loading' => 'lazy', 'class' => 'lz' ] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<nav class="proj-next" aria-label="<?php esc_attr_e( 'Następna realizacja', 'remontuj24' ); ?>">
			<div class="wrap">
				<?php if ( $list ) : ?><a href="<?php echo esc_url( get_permalink( $list ) ); ?>" class="link"><?php echo r24_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php esc_html_e( 'Wszystkie realizacje', 'remontuj24' ); ?></a><?php endif; ?>
				<?php if ( $next ) : ?>
					<a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="next"><small><?php esc_html_e( 'Następna realizacja', 'remontuj24' ); ?></small><span><?php echo esc_html( get_the_title( $next ) ); ?></span><?php echo r24_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
				<?php endif; ?>
			</div>
		</nav>
	</article>
	<?php
	echo r24t_closing(); // phpcs:ignore WordPress.Security.EscapeOutput
endwhile;
get_footer();
