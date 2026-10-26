<?php
/**
 * @var array $a Block attributes.
 */
defined( 'ABSPATH' ) || exit;
if ( ! $a['image'] ) {
	return;
}
?>
<div<?php echo r24_section_attrs( $a, 'tall' === $a['height'] ? 'pano' : 'band-photo' ); ?> aria-hidden="true"><?php echo r24_img( $a['image'], '100vw', [ 'alt' => '', 'loading' => 'lazy', 'class' => 'para' ], 'full' ); ?></div>
