<?php
/**
 * Template helpers: logo, menus, section detection and the article/document
 * layout (numbered headings with a table of contents).
 */

defined( 'ABSPATH' ) || exit;

/** True when the page is built from Remontuj24 sections rather than plain text. */
function r24t_has_sections( $post = null ): bool {
	$post = get_post( $post );
	return $post && str_contains( (string) $post->post_content, '<!-- wp:r24/' );
}

/** Logo: the one set in Customizer, otherwise the theme's SVG ("poziome" or "poziome-bez-hasla"). */
function r24t_logo( string $variant = 'poziome-bez-hasla', int $width = 252 ): string {
	$custom = get_theme_mod( 'custom_logo' );
	if ( $custom && 'poziome-bez-hasla' === $variant ) {
		return wp_get_attachment_image( $custom, 'medium', false, [ 'alt' => get_bloginfo( 'name' ), 'class' => 'custom-logo', 'fetchpriority' => 'high' ] );
	}
	$alt = 'poziome' === $variant ? sprintf( '%s, %s', get_bloginfo( 'name' ), __( 'od kluczy do gotowego wnętrza', 'remontuj24' ) ) : get_bloginfo( 'name' );
	return sprintf(
		'<img src="%s" alt="%s" width="%d" height="72">',
		esc_url( get_template_directory_uri() . '/assets/img/logo-' . $variant . '.svg' ),
		esc_attr( $alt ),
		$width
	);
}

/**
 * Links of a menu location as flat <a> tags (or <li><a>), with the current page marked.
 * Before menus are set up, the starter pages are linked by their addresses.
 */
function r24t_menu_links( string $location, bool $li = false ): string {
	$items     = [];
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations[ $location ] ) ) {
		$menu_items = wp_get_nav_menu_items( $locations[ $location ] ) ?: [];
		_wp_menu_item_classes_by_context( $menu_items );
		$menu_items = apply_filters( 'wp_nav_menu_objects', $menu_items, (object) [ 'theme_location' => $location ] );
		foreach ( $menu_items as $item ) {
			if ( (int) $item->menu_item_parent ) {
				continue; // The design has one level; sub-items would only clutter it.
			}
			$current = $item->current || ( 'page' === $item->object && is_singular( 'realizacja' ) && (int) $item->object_id === r24_realizacje_page_id() )
				|| ( 'page' === $item->object && is_singular( 'post' ) && 'poradnik' === get_post_field( 'post_name', (int) $item->object_id ) );
			$items[] = [ $item->url, $item->title, $current, $item->target ];
		}
	} elseif ( 'legal' !== $location ) {
		foreach ( [ 'jak-dzialamy', 'zakres-obslugi', 'inspiracje', 'realizacje', 'poradnik', 'o-nas', 'kontakt' ] as $slug ) {
			$page = get_page_by_path( $slug );
			if ( $page && 'publish' === $page->post_status ) {
				$items[] = [ get_permalink( $page ), get_the_title( $page ), is_page( $page->ID ), '' ];
			}
		}
	}
	$out = '';
	foreach ( $items as [ $url, $title, $current, $target ] ) {
		$a    = sprintf(
			'<a href="%s"%s%s>%s</a>',
			esc_url( $url ),
			$current ? ' class="active" aria-current="page"' : '',
			$target ? ' target="' . esc_attr( $target ) . '" rel="noopener"' : '',
			r24_polish_typography( esc_html( $title ) )
		);
		$out .= $li ? '<li>' . $a . '</li>' : $a;
	}
	return $out;
}

/**
 * Prepares post content for the reading layout: every h2 gets an id, and headings
 * that start with "1." (or every heading in a numbered document) get the gold
 * number. Returns the content and the table of contents entries.
 *
 * @return array{0:string,1:array<int,array{id:string,num:string,text:string}>}
 */
function r24t_prose( string $html, bool $number_all = false ): array {
	$toc  = [];
	$n    = 0;
	$used = [];
	$html = preg_replace_callback( '#<h2([^>]*)>(.*?)</h2>#su', function ( $m ) use ( &$toc, &$n, &$used, $number_all ) {
		$attrs = $m[1];
		$inner = trim( $m[2] );
		$text  = trim( html_entity_decode( wp_strip_all_tags( $inner ), ENT_QUOTES ) );
		if ( '' === $text ) {
			return $m[0];
		}
		++$n;
		$num = '';
		if ( preg_match( '/^(\d+)[.)]\s*(.+)$/su', $inner, $parts ) ) {
			$num   = sprintf( '%02d', (int) $parts[1] );
			$inner = $parts[2];
			$text  = preg_replace( '/^\d+[.)]\s*/u', '', $text );
		} elseif ( $number_all ) {
			$num = sprintf( '%02d', $n );
		}
		if ( preg_match( '/\sid="([^"]+)"/', $attrs, $id ) ) {
			$id = $id[1];
		} else {
			$id     = sanitize_title( $text ) ?: 's' . $n;
			$id     = isset( $used[ $id ] ) ? $id . '-' . $n : $id;
			$attrs .= ' id="' . esc_attr( $id ) . '"';
		}
		$used[ $id ] = true;
		$toc[] = [ 'id' => $id, 'num' => $num, 'text' => $text ];
		if ( '' === $num ) {
			$attrs = str_contains( $attrs, 'class="' ) ? preg_replace( '/class="/', 'class="plain ', $attrs, 1 ) : $attrs . ' class="plain"';
			return '<h2' . $attrs . '>' . $inner . '</h2>';
		}
		return '<h2' . $attrs . '><span class="num">' . $num . '</span><span>' . $inner . '</span></h2>';
	}, $html );
	return [ (string) $html, $toc ];
}

/** Table of contents beside an article or document. */
function r24t_toc( array $toc, string $title ): string {
	if ( count( $toc ) < 3 ) {
		return '';
	}
	$links = '';
	foreach ( $toc as $item ) {
		$links .= sprintf( '<a href="#%s"><b>%s</b><span>%s</span></a>', esc_attr( $item['id'] ), $item['num'] ? esc_html( $item['num'] ) : '·', esc_html( $item['text'] ) );
	}
	return '<nav class="toc" aria-label="' . esc_attr__( 'Spis treści', 'remontuj24' ) . '"><h2>' . esc_html( $title ) . '</h2>' . $links . '</nav>';
}

/** The filtered content of the current post, as a string. */
function r24t_content(): string {
	$content = apply_filters( 'the_content', get_the_content() );
	return str_replace( ']]>', ']]&gt;', (string) $content );
}

/** Breadcrumb trail for posts and projects: Home / list page / last item. */
function r24t_crumbs( array $middle, string $last ): string {
	$items = [ '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Strona główna', 'remontuj24' ) . '</a>' ];
	foreach ( $middle as [ $url, $label ] ) {
		$items[] = '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
	}
	$items[] = '<span>' . esc_html( $last ) . '</span>';
	return '<nav class="crumbs rv" aria-label="' . esc_attr__( 'Okruszki', 'remontuj24' ) . '">' . implode( '<span aria-hidden="true">/</span>', $items ) . '</nav>';
}

/** The page that lists the Poradnik articles (slug "poradnik"), or the posts page. */
function r24t_guide_url(): string {
	$page = get_page_by_path( 'poradnik' );
	if ( $page ) {
		return get_permalink( $page );
	}
	$posts_page = (int) get_option( 'page_for_posts' );
	return $posts_page ? get_permalink( $posts_page ) : home_url( '/' );
}

/** Closing invitation (dark band) used under projects and on the 404 page. */
function r24t_closing(): string {
	return render_block( [
		'blockName'    => 'r24/closing',
		'attrs'        => [
			'eyebrow' => __( 'Twoje mieszkanie', 'remontuj24' ),
			'title'   => __( 'Wszystko zaczyna się od rozmowy.', 'remontuj24' ),
			'sub'     => __( 'Nie trzeba mieć gotowego projektu, wybranych materiałów ani szczegółowego planu.', 'remontuj24' ),
			'btn_url' => r24_opt( 'contact_url', '/kontakt/' ),
		],
		'innerBlocks'  => [],
		'innerHTML'    => '',
		'innerContent' => [],
	] );
}

/** Square logo for search engines (structured data in the plugin). */
function r24_theme_logo_url(): string {
	return get_template_directory_uri() . '/assets/img/logo-512.png';
}
