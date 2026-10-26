<?php
/**
 * Images: uploaded JPG and PNG photos get WebP sizes (smaller files, same look),
 * very large originals are scaled down, and srcset covers phones to wide screens.
 */

defined( 'ABSPATH' ) || exit;

/** JPG/PNG uploads get WebP sizes; WordPress keeps the original format if the server cannot write WebP. */
add_filter( 'image_editor_output_format', function ( $formats ) {
	$formats['image/jpeg'] = 'image/webp';
	$formats['image/png']  = 'image/webp';
	return $formats;
} );

add_filter( 'wp_editor_set_quality', fn( $q, $mime ) => 'image/webp' === $mime ? 80 : $q, 10, 2 );

/** Originals above 2400 px are scaled down; nothing on the site needs more. */
add_filter( 'big_image_size_threshold', fn() => 2400 );

/** Extra widths so phones (≈400–480 px wide, 2–3× density) get a close match. */
add_action( 'after_setup_theme', function () {
	add_image_size( 'r24-480', 480, 0 );
	add_image_size( 'r24-1280', 1280, 0 );
} );

add_filter( 'max_srcset_image_width', fn() => 2400 );
