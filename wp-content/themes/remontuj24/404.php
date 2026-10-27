<?php
/**
 * Page not found: "Te drzwi prowadzą donikąd." with the door drawing.
 */
defined( 'ABSPATH' ) || exit;
get_header();
$links = array_filter( array_map( 'get_page_by_path', [ 'jak-dzialamy', 'poradnik', 'kontakt' ] ) );
?>
<section class="e404 hero" aria-labelledby="h-404">
	<div class="wrap">
		<div class="copy">
			<span class="big rv" aria-hidden="true">404</span>
			<span class="eyebrow rv"><?php esc_html_e( 'Nie znaleziono strony', 'remontuj24' ); ?></span>
			<h1 id="h-404"><?php echo r24_title( (string) r24_opt( 'e404_title' ), true ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h1>
			<p class="lead rv"><?php echo esc_html( r24_opt( 'e404_text' ) ); ?></p>
			<div class="ctas rv"><?php echo r24_button( __( 'Wróć na stronę główną', 'remontuj24' ), home_url( '/' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<?php if ( $links ) : ?>
				<nav class="links rv" aria-label="<?php esc_attr_e( 'Przydatne strony', 'remontuj24' ); ?>">
					<?php foreach ( $links as $page ) : ?><a class="link" href="<?php echo esc_url( get_permalink( $page ) ); ?>"><?php echo esc_html( get_the_title( $page ) ); ?></a><?php endforeach; ?>
				</nav>
			<?php endif; ?>
		</div>
		<div class="art" aria-hidden="true"><?php echo r24_sygnet(); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
	</div>
</section>
<?php
get_footer();
