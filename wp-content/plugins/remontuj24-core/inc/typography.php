<?php
/**
 * Polish typesetting: one-letter words (a, i, o, u, w, z) stay with the next word
 * and a dash never starts a line. Applied to page content, titles and our blocks.
 */

defined( 'ABSPATH' ) || exit;

function r24_polish_typography( $html ): string {
	$html = (string) $html;
	if ( '' === $html ) {
		return $html;
	}
	$parts = preg_split( '/(<[^>]+>)/u', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	if ( false === $parts ) {
		return $html;
	}
	$skip = 0;
	foreach ( $parts as $i => $part ) {
		if ( '' === $part ) {
			continue;
		}
		if ( '<' === $part[0] ) {
			if ( preg_match( '#^<(script|style|textarea|pre|code)\b#i', $part ) ) {
				++$skip;
			} elseif ( preg_match( '#^</(script|style|textarea|pre|code)\b#i', $part ) ) {
				$skip = max( 0, $skip - 1 );
			}
			continue;
		}
		if ( $skip || '' === trim( $part ) ) {
			continue;
		}
		// Twice, so chains like "i w domu" are fully bound.
		for ( $pass = 0; $pass < 2; $pass++ ) {
			$part = preg_replace( '/(^|[\s(„"])([aiouwzAIOUWZ]) +(?=\S)/u', "\$1\$2\u{00A0}", $part );
		}
		$part        = preg_replace( '/(?<=\S) +(—|–)/u', "\u{00A0}\$1", $part );
		$parts[ $i ] = $part;
	}
	return implode( '', $parts );
}

add_filter( 'the_content', 'r24_polish_typography', 99 );
add_filter( 'the_excerpt', 'r24_polish_typography', 99 );
add_filter( 'the_title', function ( $title ) {
	return is_admin() ? $title : r24_polish_typography( (string) $title );
}, 99 );
add_filter( 'render_block', function ( $html, $block ) {
	return str_starts_with( (string) ( $block['blockName'] ?? '' ), 'r24/' ) ? r24_polish_typography( (string) $html ) : $html;
}, 99, 2 );
