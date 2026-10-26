<?php
/**
 * Small helpers shared by blocks, templates and the theme.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Reads one value from the "Remontuj24" settings page, falling back to the default.
 */
function r24_opt( string $key, $fallback = null ) {
	static $opts = null;
	if ( null === $opts ) {
		$opts = wp_parse_args( (array) get_option( 'r24_settings', [] ), r24_setting_defaults() );
	}
	$value = $opts[ $key ] ?? null;
	if ( ( null === $value || '' === $value ) && null !== $fallback ) {
		return $fallback;
	}
	return $value;
}

/** Phone number as a tel: link target, e.g. +48512640044. */
function r24_tel( string $phone = '' ): string {
	$phone  = $phone ?: (string) r24_opt( 'phone' );
	$digits = preg_replace( '/\D+/', '', $phone );
	if ( 9 === strlen( $digits ) ) {
		$digits = '48' . $digits;
	}
	return $digits ? 'tel:+' . $digits : '';
}

/** Phone number for display that never breaks across lines. */
function r24_phone_display( string $phone = '' ): string {
	$phone = $phone ?: (string) r24_opt( 'phone' );
	return str_replace( ' ', "\u{00A0}", trim( $phone ) );
}

/** The animated two-arrow icon used on buttons and links. */
function r24_arrow(): string {
	return '<span class="arrow" aria-hidden="true"><i><svg><use href="#arr"/></svg></i><i><svg><use href="#arr"/></svg></i></span>';
}

/** Inline SVG icon from the sprite printed in the footer. */
function r24_icon( string $name ): string {
	return '<svg aria-hidden="true"><use href="#ico-' . esc_attr( $name ) . '"/></svg>';
}

/** The door-swing sygnet, drawn when it scrolls into view. */
function r24_sygnet( string $class = '' ): string {
	return '<svg class="door draw ' . esc_attr( $class ) . '" viewBox="0 0 48 48" aria-hidden="true">'
		. '<path class="w" pathLength="1" d="M12 44 H4 V4 H44 V44 H28"/>'
		. '<path class="w" pathLength="1" d="M12 44 V28" style="stroke-width:1.2"/>'
		. '<path class="a" pathLength="1" d="M12 28 A16 16 0 0 1 28 44"/></svg>';
}

/**
 * Inline HTML allowed in text fields: emphasis, links and line breaks.
 */
function r24_text( ?string $text ): string {
	$allowed = [
		'strong' => [],
		'b'      => [],
		'em'     => [],
		'i'      => [],
		'br'     => [],
		'a'      => [ 'href' => true, 'target' => true, 'rel' => true ],
		'span'   => [ 'class' => true ],
	];
	return wp_kses( trim( (string) $text ), $allowed );
}

/**
 * Heading text: closing full stop or question mark is painted gold,
 * and with $lines each line of the field becomes a separately animated line.
 */
function r24_title( ?string $text, bool $lines = false ): string {
	$text = trim( (string) $text );
	if ( '' === $text ) {
		return '';
	}
	$parts = array_values( array_filter( array_map( 'trim', preg_split( '/\R/u', $text ) ), 'strlen' ) );
	$last  = count( $parts ) - 1;
	foreach ( $parts as $i => $part ) {
		$part = r24_text( $part );
		if ( $i === $last ) {
			$part = preg_replace( '/([.?!])$/u', '<span class="dot">$1</span>', $part );
		}
		$parts[ $i ] = $lines ? '<span class="line"><span>' . $part . '</span></span>' : $part;
	}
	return implode( $lines ? '' : '<br>', $parts );
}

/** Paragraphs from a multi-line text field. */
function r24_paragraphs( ?string $text, string $class = '' ): string {
	$out = '';
	foreach ( preg_split( '/\R+/u', trim( (string) $text ) ) as $p ) {
		if ( '' !== trim( $p ) ) {
			$out .= '<p' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '>' . r24_text( $p ) . '</p>';
		}
	}
	return $out;
}

/**
 * Responsive image from the media library. WordPress adds srcset and sizes,
 * so every device downloads the width it needs.
 */
function r24_img( $id, string $sizes = '100vw', array $attr = [], string $size = 'large' ): string {
	$id = (int) $id;
	if ( ! $id || ! wp_attachment_is_image( $id ) ) {
		return '';
	}
	$attr = array_merge( [ 'sizes' => $sizes, 'decoding' => 'async' ], $attr );
	if ( ! empty( $attr['fetchpriority'] ) ) {
		$attr['loading'] = false;
	}
	return wp_get_attachment_image( $id, $size, false, $attr );
}

/** Link target for a page chosen by path or full URL. */
function r24_url( ?string $url ): string {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	if ( str_starts_with( $url, '/' ) && ! str_starts_with( $url, '//' ) ) {
		return home_url( $url );
	}
	return $url;
}

/** Button or text link with the arrow. */
function r24_button( ?string $text, ?string $url, string $class = 'btn' ): string {
	if ( ! $text || ! $url ) {
		return '';
	}
	return sprintf( '<a class="%s" href="%s">%s %s</a>', esc_attr( $class ), esc_url( r24_url( $url ) ), r24_text( $text ), r24_arrow() );
}

/** Social profile links set in Remontuj24 → Ustawienia. */
function r24_social(): string {
	$out = '';
	foreach ( [ 'instagram' => [ 'ig', 'Instagram' ], 'facebook' => [ 'fb', 'Facebook' ], 'tiktok' => [ 'tt', 'TikTok' ] ] as $key => [ $icon, $label ] ) {
		$url = r24_opt( $key );
		if ( $url ) {
			$out .= sprintf( '<a href="%s" aria-label="%s" target="_blank" rel="noopener">%s</a>', esc_url( $url ), esc_attr( $label ), r24_icon( $icon ) );
		}
	}
	return $out ? '<div class="social">' . $out . '</div>' : '';
}

/** Section heading: eyebrow, title and lead, side by side on wide screens. */
function r24_section_head( array $a, string $id, bool $split = true ): string {
	if ( empty( $a['eyebrow'] ) && empty( $a['title'] ) && empty( $a['lead'] ) ) {
		return '';
	}
	$left = '';
	if ( ! empty( $a['eyebrow'] ) ) {
		$left .= '<span class="eyebrow rv">' . esc_html( $a['eyebrow'] ) . '</span>';
	}
	if ( ! empty( $a['title'] ) ) {
		$left .= '<h2 id="' . esc_attr( $id ) . '" class="rv">' . r24_title( $a['title'] ) . '</h2>';
	}
	$lead = ! empty( $a['lead'] ) ? '<p class="lead rv">' . r24_text( $a['lead'] ) . '</p>' : '';
	return '<div class="section-head' . ( $split ? ' split' : '' ) . '"><div class="head-main">' . $left . '</div>' . $lead . '</div>';
}

/** A unique, stable id for headings inside a block. */
function r24_uid( string $prefix ): string {
	static $n = [];
	$n[ $prefix ] = ( $n[ $prefix ] ?? 0 ) + 1;
	return $prefix . ( $n[ $prefix ] > 1 ? '-' . $n[ $prefix ] : '' );
}

/** Breadcrumbs for the current page: Home / parents / title. */
function r24_crumbs(): string {
	if ( is_front_page() ) {
		return '';
	}
	$items = [ '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Strona główna', 'remontuj24' ) . '</a>' ];
	$post  = get_queried_object();
	if ( $post instanceof WP_Post ) {
		foreach ( array_reverse( get_post_ancestors( $post ) ) as $parent ) {
			$items[] = '<a href="' . esc_url( get_permalink( $parent ) ) . '">' . esc_html( get_the_title( $parent ) ) . '</a>';
		}
		$items[] = '<span>' . esc_html( get_the_title( $post ) ) . '</span>';
	} elseif ( is_home() ) {
		$items[] = '<span>' . esc_html( get_the_title( (int) get_option( 'page_for_posts' ) ) ) . '</span>';
	}
	return '<nav class="crumbs rv" aria-label="' . esc_attr__( 'Okruszki', 'remontuj24' ) . '">' . implode( '<span aria-hidden="true">/</span>', $items ) . '</nav>';
}

/** The page that hosts the Realizacje list (slug "realizacje"). */
function r24_realizacje_page_id(): int {
	$page = get_page_by_path( 'realizacje' );
	return $page ? (int) $page->ID : 0;
}

/**
 * Before/after slider. On phones it can use separate portrait crops.
 *
 * @param array $o before, after, before_m, after_m (ids), labels, start, sizes, priority, label.
 */
function r24_ba( array $o ): string {
	$o = wp_parse_args( $o, [ 'before_m' => 0, 'after_m' => 0, 'labels' => [ 'Przed', 'Po' ], 'start' => 50, 'sizes' => '100vw', 'priority' => false, 'label' => '', 'class' => '' ] );
	$pic = function ( $id, $mobile, $class, $first ) use ( $o ) {
		$alt  = trim( (string) get_post_meta( (int) $id, '_wp_attachment_image_alt', true ) );
		$attr = [ 'alt' => $alt ];
		if ( $first && $o['priority'] ) {
			$attr['fetchpriority'] = 'high';
		} else {
			$attr['loading'] = $o['priority'] ? 'eager' : 'lazy';
		}
		if ( ! $mobile ) {
			$img = r24_img( $id, $o['sizes'], $attr );
			return $class ? '<picture class="' . $class . '">' . $img . '</picture>' : $img;
		}
		$source = sprintf(
			'<source media="(min-width:700px)" srcset="%s" sizes="%s">',
			esc_attr( (string) wp_get_attachment_image_srcset( (int) $id, 'large' ) ),
			esc_attr( $o['sizes'] )
		);
		return '<picture' . ( $class ? ' class="' . $class . '"' : '' ) . '>' . $source . r24_img( $mobile, '100vw', $attr ) . '</picture>';
	};
	$labels = (array) $o['labels'];
	$label  = $o['label'] ?: __( 'Porównaj: przed i po', 'remontuj24' );
	return sprintf(
		'<div class="ba %7$s" data-start="%1$d">%2$s%3$s<span class="tag before">%4$s</span><span class="tag after-t">%5$s</span>'
		. '<span class="handle" aria-hidden="true"><span class="knob"><svg viewBox="0 0 26 12"><path d="M6 1 1 6l5 5M20 1l5 5-5 5M1 6h24" fill="none" stroke="currentColor" stroke-width="1.3"/></svg></span></span>'
		. '<input type="range" min="0" max="100" value="%1$d" aria-label="%6$s"></div>',
		max( 5, min( 95, (int) $o['start'] ) ),
		$pic( $o['before'], $o['before_m'], '', true ),
		$pic( $o['after'], $o['after_m'], 'after', false ),
		esc_html( $labels[0] ?? '' ),
		esc_html( $labels[1] ?? '' ),
		esc_attr( $label ),
		esc_attr( $o['class'] )
	);
}

/** "Jeden kontakt. Jeden proces. Pełna koordynacja." with the last sentence in gold. */
function r24_motto( ?string $text, string $class = 'motto rv' ): string {
	$text = trim( (string) $text );
	if ( '' === $text ) {
		return '';
	}
	$sentences = preg_split( '/(?<=[.!?])\s+/u', $text );
	$last      = array_pop( $sentences );
	$html      = esc_html( implode( ' ', $sentences ) );
	$html     .= ( $html ? ' ' : '' ) . '<em>' . esc_html( $last ) . '</em>';
	return '<p class="' . esc_attr( $class ) . '">' . $html . '</p>';
}

/** Estimated reading time in minutes (180 words per minute). */
function r24_reading_minutes( $post = null ): int {
	$words = str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content', $post ) ), 0, 'ąćęłńóśźżĄĆĘŁŃÓŚŹŻ' );
	return max( 1, (int) round( $words / 180 ) );
}

/** Main category of a post (Poradnik section). */
function r24_post_category( $post = null ): ?WP_Term {
	$cats = get_the_category( $post ? get_post( $post )->ID : 0 );
	$cats = array_filter( $cats, fn( $c ) => 'uncategorized' !== $c->slug && (int) get_option( 'default_category' ) !== (int) $c->term_id );
	return $cats ? reset( $cats ) : null;
}

/** Article teaser card; $featured gives the large horizontal variant. */
function r24_post_card( $post, bool $featured = false ): string {
	$post    = get_post( $post );
	$cat     = r24_post_category( $post );
	$meta    = '<div class="meta"><span>' . esc_html( $cat ? $cat->name : __( 'Poradnik', 'remontuj24' ) ) . '</span><i></i><span>' . esc_html( sprintf( _n( '%d min czytania', '%d min czytania', r24_reading_minutes( $post ), 'remontuj24' ), r24_reading_minutes( $post ) ) ) . '</span></div>';
	$img     = get_post_thumbnail_id( $post ) ? '<div class="ph">' . r24_img( get_post_thumbnail_id( $post ), $featured ? '(min-width:960px) 56vw, 100vw' : '(min-width:760px) 30vw, 100vw', [ 'loading' => 'lazy', 'class' => 'lz' ] ) . '</div>' : '';
	$title   = esc_html( get_the_title( $post ) );
	$excerpt = esc_html( wp_strip_all_tags( get_the_excerpt( $post ) ) );
	$link    = '<span class="link">' . esc_html__( 'Czytaj', 'remontuj24' ) . ' ' . r24_arrow() . '</span>';
	$cat_attr = esc_attr( $cat ? $cat->slug : '' );
	if ( $featured ) {
		return sprintf( '<a class="featured rv" href="%s" data-cat-item="%s">%s<div class="copy card">%s<h2>%s</h2><p>%s</p>%s</div></a>', esc_url( get_permalink( $post ) ), $cat_attr, $img, $meta, $title, $excerpt, $link );
	}
	return sprintf( '<a class="card rv" href="%s" data-cat-item="%s">%s%s<h3>%s</h3><p>%s</p>%s</a>', esc_url( get_permalink( $post ) ), $cat_attr, $img, $meta, $title, $excerpt, $link );
}
