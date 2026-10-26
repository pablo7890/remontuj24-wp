<?php
/**
 * Realizacje (projects): a post type the client fills in like posts, with
 * project facts, before/after photos and a gallery edited in the sidebar.
 * Also the private lists for contact messages and newsletter subscribers.
 */

defined( 'ABSPATH' ) || exit;

/** Project facts shown on the card and on the project page. */
function r24_realizacja_fields(): array {
	return [
		[ 'key' => 'miasto', 'label' => 'Miasto (służy też do filtrowania listy)', 'type' => 'text' ],
		[ 'key' => 'metraz', 'label' => 'Metraż, np. 58 m²', 'type' => 'text' ],
		[ 'key' => 'zakres', 'label' => 'Zakres, np. Pod klucz', 'type' => 'text' ],
		[ 'key' => 'czas', 'label' => 'Czas realizacji, np. 14 tygodni', 'type' => 'text' ],
		[ 'key' => 'lead', 'label' => 'Krótki opis pod tytułem', 'type' => 'textarea' ],
		[ 'key' => 'przed', 'label' => 'Zdjęcie „przed”', 'type' => 'image' ],
		[ 'key' => 'po', 'label' => 'Zdjęcie „po” (z tego samego miejsca)', 'type' => 'image' ],
		[ 'key' => 'galeria', 'label' => 'Galeria gotowego wnętrza', 'type' => 'gallery' ],
		[ 'key' => 'opinia', 'label' => 'Opinia właściciela (opcjonalnie)', 'type' => 'textarea' ],
		[ 'key' => 'opinia_autor', 'label' => 'Podpis opinii', 'type' => 'text' ],
		[ 'key' => 'przyklad', 'label' => 'Oznacz jako przykład (plakietka „Przykład”)', 'type' => 'toggle' ],
	];
}

function r24_register_realizacje(): void {
	register_post_type( 'realizacja', [
		'labels'        => [
			'name'               => 'Realizacje',
			'singular_name'      => 'Realizacja',
			'add_new'            => 'Dodaj realizację',
			'add_new_item'       => 'Dodaj realizację',
			'edit_item'          => 'Edytuj realizację',
			'new_item'           => 'Nowa realizacja',
			'view_item'          => 'Zobacz realizację',
			'search_items'       => 'Szukaj realizacji',
			'not_found'          => 'Nie ma jeszcze realizacji',
			'all_items'          => 'Wszystkie realizacje',
			'featured_image'     => 'Zdjęcie na liście',
			'set_featured_image' => 'Ustaw zdjęcie na liście',
		],
		'public'        => true,
		'has_archive'   => false,
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-format-gallery',
		'menu_position' => 6,
		'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ],
		'rewrite'       => [ 'slug' => 'realizacje', 'with_front' => false ],
		'template'      => [
			[ 'core/heading', [ 'content' => 'Punkt wyjścia', 'level' => 2 ] ],
			[ 'core/paragraph', [ 'placeholder' => 'W jakim stanie było mieszkanie i czego oczekiwał właściciel?' ] ],
			[ 'core/heading', [ 'content' => 'Co zrobiliśmy', 'level' => 2 ] ],
			[ 'core/list', [ 'className' => 'done-list' ] ],
			[ 'core/heading', [ 'content' => 'Efekt', 'level' => 2 ] ],
			[ 'core/paragraph', [ 'placeholder' => 'Krótkie podsumowanie realizacji.' ] ],
		],
	] );

	foreach ( r24_realizacja_fields() as $field ) {
		$type = match ( $field['type'] ) {
			'image' => 'integer',
			'gallery' => 'array',
			'toggle' => 'boolean',
			default => 'string',
		};
		$args = [
			'type'          => $type,
			'single'        => true,
			'show_in_rest'  => 'array' === $type ? [ 'schema' => [ 'type' => 'array', 'items' => [ 'type' => 'integer' ] ] ] : true,
			'auth_callback' => fn() => current_user_can( 'edit_posts' ),
		];
		if ( 'string' === $type ) {
			$args['sanitize_callback'] = 'textarea' === $field['type'] ? 'sanitize_textarea_field' : 'sanitize_text_field';
		}
		register_post_meta( 'realizacja', 'r24_' . $field['key'], $args );
	}
}
add_action( 'init', 'r24_register_realizacje' );

function r24_register_private_types(): void {
	register_post_type( 'r24_wiadomosc', [
		'labels'          => [ 'name' => 'Wiadomości', 'singular_name' => 'Wiadomość', 'edit_item' => 'Wiadomość', 'not_found' => 'Brak wiadomości' ],
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => 'remontuj24',
		'supports'        => [ 'title' ],
		'capability_type' => 'post',
		'capabilities'    => [ 'create_posts' => 'do_not_allow' ],
		'map_meta_cap'    => true,
	] );
	register_post_type( 'r24_subskrybent', [
		'labels'          => [ 'name' => 'Subskrybenci', 'singular_name' => 'Subskrybent', 'not_found' => 'Brak subskrybentów' ],
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => 'remontuj24',
		'supports'        => [ 'title' ],
		'capabilities'    => [ 'create_posts' => 'do_not_allow' ],
		'map_meta_cap'    => true,
	] );
}
add_action( 'init', 'r24_register_private_types' );

/** One project's facts, ready for templates. */
function r24_realizacja( $post = null ): array {
	$post = get_post( $post );
	$data = [];
	foreach ( r24_realizacja_fields() as $field ) {
		$data[ $field['key'] ] = get_post_meta( $post->ID, 'r24_' . $field['key'], true );
	}
	$data['galeria'] = array_filter( array_map( 'intval', (array) $data['galeria'] ) );
	return $data;
}

/**
 * Hides the Realizacje menu item until at least one project is published,
 * so the menu never leads to an empty page.
 */
add_filter( 'wp_nav_menu_objects', function ( $items ) {
	$page_id = r24_realizacje_page_id();
	if ( ! $page_id || wp_count_posts( 'realizacja' )->publish > 0 ) {
		return $items;
	}
	return array_filter( $items, fn( $item ) => ! ( 'page' === $item->object && (int) $item->object_id === $page_id ) );
} );

/** Messages list: who and when, with the message shown on the edit screen. */
add_filter( 'manage_r24_wiadomosc_posts_columns', fn( $cols ) => [ 'cb' => $cols['cb'], 'title' => 'Od', 'r24_contact' => 'Kontakt', 'r24_excerpt' => 'Wiadomość', 'date' => 'Data' ] );
add_action( 'manage_r24_wiadomosc_posts_custom_column', function ( $col, $id ) {
	if ( 'r24_contact' === $col ) {
		echo esc_html( get_post_meta( $id, 'r24_contact', true ) );
	} elseif ( 'r24_excerpt' === $col ) {
		echo esc_html( wp_trim_words( get_post_meta( $id, 'r24_message', true ), 18 ) );
	}
}, 10, 2 );
add_action( 'add_meta_boxes_r24_wiadomosc', function () {
	add_meta_box( 'r24-message', 'Treść wiadomości', function ( $post ) {
		printf(
			'<p><strong>Kontakt:</strong> %s</p><p><strong>Strona:</strong> %s</p><div style="white-space:pre-wrap;font-size:14px;line-height:1.6">%s</div>',
			esc_html( get_post_meta( $post->ID, 'r24_contact', true ) ),
			esc_html( get_post_meta( $post->ID, 'r24_page', true ) ),
			esc_html( get_post_meta( $post->ID, 'r24_message', true ) )
		);
	}, null, 'normal', 'high' );
} );

/** Project card for the Realizacje list: hover reveals the "before" photo. */
function r24_project_card( $post ): string {
	$post  = get_post( $post );
	$d     = r24_realizacja( $post );
	$main  = get_post_thumbnail_id( $post ) ?: (int) $d['po'];
	$hover = (int) $d['przed'];
	$sizes = '(min-width:860px) 46vw, 100vw';
	$img   = r24_img( $main, $sizes, [ 'loading' => 'lazy', 'class' => 'lz' ] );
	if ( $hover && $hover !== $main ) {
		$img .= r24_img( $hover, $sizes, [ 'loading' => 'lazy', 'alt' => '' ] );
	}
	$tags  = $d['przyklad'] ? '<span class="tag">' . esc_html__( 'Przykład', 'remontuj24' ) . '</span>' : '';
	$tags .= $hover ? '<span class="tag when">' . esc_html__( 'Przed', 'remontuj24' ) . '</span>' : '';
	$meta  = implode( '', array_map( fn( $v ) => '<span>' . esc_html( $v ) . '</span>', array_filter( [ $d['miasto'], $d['metraz'], $d['zakres'] ] ) ) );
	return sprintf(
		'<a class="proj rv" href="%s" data-cat-item="%s"><div class="ph">%s%s</div><div class="meta">%s</div><h3>%s</h3><span class="link">%s %s</span></a>',
		esc_url( get_permalink( $post ) ),
		esc_attr( sanitize_title( (string) $d['miasto'] ) ),
		$tags,
		$img,
		$meta,
		esc_html( get_the_title( $post ) ),
		esc_html__( 'Zobacz realizację', 'remontuj24' ),
		r24_arrow()
	);
}
