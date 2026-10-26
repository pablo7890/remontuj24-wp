<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
$hid = r24_uid( 'h-statement' );
?>
<section<?php echo r24_section_attrs( $a, 'statement' . ( $a['cream'] ? ' band' : '' ) ); ?> aria-labelledby="<?php echo esc_attr( $hid ); ?>">
	<div class="wrap">
		<?php echo $a['sygnet'] ? r24_sygnet() : ''; ?>
		<?php if ( $a['eyebrow'] ) : ?><span class="eyebrow rv"<?php echo $a['title'] ? '' : ' id="' . esc_attr( $hid ) . '"'; ?>><?php echo esc_html( $a['eyebrow'] ); ?></span><?php endif; ?>
		<?php if ( $a['title'] ) : ?><h2 id="<?php echo esc_attr( $hid ); ?>" class="rv"><?php echo r24_title( $a['title'] ); ?></h2><?php endif; ?>
		<?php if ( $a['lead'] ) : ?><p class="lead rv"><?php echo r24_text( $a['lead'] ); ?></p><?php endif; ?>
		<?php if ( $a['lead'] && $a['quote'] ) : ?><span class="rule rv" aria-hidden="true"></span><?php endif; ?>
		<?php if ( $a['quote'] ) : ?><blockquote class="rv"><?php echo r24_text( $a['quote'] ); ?></blockquote><?php endif; ?>
		<?php if ( $a['motto'] ) : ?><span class="rule rv" aria-hidden="true"></span><?php echo r24_motto( $a['motto'] ); ?><?php endif; ?>
	</div>
</section>
