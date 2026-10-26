<?php
/**
 * Section blocks. Each block lives in blocks/<name>/: def.php describes its fields
 * (one source for block attributes and the editor sidebar), render.php prints it.
 * The editor shows a live server-side preview, so what you see is the real page.
 */

defined( 'ABSPATH' ) || exit;

/** All block definitions, keyed by short name. */
function r24_block_defs(): array {
	static $defs = null;
	if ( null !== $defs ) {
		return $defs;
	}
	$defs = [];
	foreach ( glob( R24_DIR . 'blocks/*/def.php' ) as $file ) {
		$name          = basename( dirname( $file ) );
		$defs[ $name ] = require $file;
	}
	uasort( $defs, fn( $a, $b ) => ( $a['order'] ?? 50 ) <=> ( $b['order'] ?? 50 ) );
	return $defs;
}

/** Field type → block attribute type. */
function r24_attr_type( string $type ): string {
	return match ( $type ) {
		'image' => 'number',
		'number' => 'number',
		'toggle' => 'boolean',
		'repeater', 'gallery' => 'array',
		default => 'string',
	};
}

function r24_field_default( array $field ) {
	if ( array_key_exists( 'default', $field ) ) {
		return $field['default'];
	}
	return match ( r24_attr_type( $field['type'] ) ) {
		'number' => 0,
		'boolean' => false,
		'array' => [],
		default => '',
	};
}

add_filter( 'block_categories_all', function ( $cats ) {
	array_unshift( $cats, [ 'slug' => 'remontuj24', 'title' => 'Remontuj24 · sekcje' ] );
	return $cats;
} );

add_action( 'init', function () {
	wp_register_script(
		'r24-blocks',
		R24_URL . 'assets/editor.js',
		[ 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-server-side-render', 'wp-data', 'wp-core-data', 'wp-plugins', 'wp-editor', 'wp-i18n' ],
		R24_VERSION,
		true
	);
	wp_register_style( 'r24-editor', R24_URL . 'assets/editor.css', [ 'wp-components' ], R24_VERSION );

	foreach ( r24_block_defs() as $name => $def ) {
		$attributes = [
			'anchor'    => [ 'type' => 'string', 'default' => '' ],
			'className' => [ 'type' => 'string', 'default' => '' ],
		];
		foreach ( $def['fields'] as $field ) {
			if ( 'panel' === $field['type'] ) {
				continue;
			}
			$attributes[ $field['key'] ] = [
				'type'    => r24_attr_type( $field['type'] ),
				'default' => r24_field_default( $field ),
			];
		}
		register_block_type( 'r24/' . $name, [
			'api_version'     => 3,
			'title'           => $def['title'],
			'description'     => $def['description'] ?? '',
			'category'        => 'remontuj24',
			'icon'            => $def['icon'] ?? 'layout',
			'keywords'        => $def['keywords'] ?? [],
			'attributes'      => $attributes,
			'supports'        => [ 'html' => false, 'anchor' => true, 'multiple' => $def['multiple'] ?? true ],
			'editor_script_handles' => [ 'r24-blocks' ],
			'editor_style_handles'  => [ 'r24-editor' ],
			'render_callback' => fn( $attrs ) => r24_render_block( $name, (array) $attrs ),
		] );
	}
} );

/** Hands the field definitions to the editor script. */
add_action( 'enqueue_block_editor_assets', function () {
	$defs = [];
	foreach ( r24_block_defs() as $name => $def ) {
		$defs[ 'r24/' . $name ] = [
			'fields' => $def['fields'],
			'hint'   => $def['hint'] ?? '',
		];
	}
	wp_add_inline_script( 'r24-blocks', 'window.r24Blocks = ' . wp_json_encode( $defs ) . ';', 'before' );
	wp_add_inline_script( 'r24-blocks', 'window.r24Realizacja = ' . wp_json_encode( r24_realizacja_fields() ) . ';', 'before' );
	wp_enqueue_script( 'r24-blocks' );
	wp_enqueue_style( 'r24-editor' );
} );

/** Renders one block; empty blocks print nothing on the site. */
function r24_render_block( string $name, array $a ): string {
	$file = R24_DIR . 'blocks/' . $name . '/render.php';
	if ( ! is_readable( $file ) ) {
		return '';
	}
	ob_start();
	include $file;
	return trim( (string) ob_get_clean() );
}

/** id and class attributes for a section, honouring the block's anchor and extra classes. */
function r24_section_attrs( array $a, string $class ): string {
	$class .= ! empty( $a['className'] ) ? ' ' . $a['className'] : '';
	$out    = ' class="' . esc_attr( trim( $class ) ) . '"';
	if ( ! empty( $a['anchor'] ) ) {
		$out .= ' id="' . esc_attr( $a['anchor'] ) . '"';
	}
	return $out;
}

/** Only rows of a repeater that have some content. */
function r24_items( $items ): array {
	return array_values( array_filter( (array) $items, fn( $item ) => is_array( $item ) && array_filter( $item ) ) );
}
