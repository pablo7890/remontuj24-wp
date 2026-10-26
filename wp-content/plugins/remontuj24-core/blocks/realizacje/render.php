<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$projects = get_posts( [ 'post_type' => 'realizacja', 'numberposts' => -1, 'orderby' => [ 'menu_order' => 'ASC', 'date' => 'DESC' ] ] );
$cities   = array_values( array_unique( array_filter( array_map( fn( $p ) => trim( (string) get_post_meta( $p->ID, 'r24_miasto', true ) ), $projects ) ) ) );
?>
<section<?php echo r24_section_attrs( $a, 'section flush' ); ?> aria-label="<?php esc_attr_e( 'Lista realizacji', 'remontuj24' ); ?>">
	<div class="wrap">
		<?php if ( ! $projects ) : ?>
			<p class="lead"><?php echo esc_html( $a['empty'] ); ?></p>
		<?php else : ?>
			<?php if ( $a['filter'] && count( $cities ) > 1 ) : ?>
				<div class="chips rv" role="group" aria-label="<?php esc_attr_e( 'Filtruj według miasta', 'remontuj24' ); ?>">
					<button type="button" data-cat="all" aria-pressed="true"><?php esc_html_e( 'Wszystkie', 'remontuj24' ); ?></button>
					<?php foreach ( $cities as $city ) : ?><button type="button" data-cat="<?php echo esc_attr( sanitize_title( $city ) ); ?>" aria-pressed="false"><?php echo esc_html( $city ); ?></button><?php endforeach; ?>
				</div>
			<?php endif; ?>
			<div class="proj-grid" data-stagger>
				<?php foreach ( $projects as $p ) : ?>
					<?php echo r24_project_card( $p ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
