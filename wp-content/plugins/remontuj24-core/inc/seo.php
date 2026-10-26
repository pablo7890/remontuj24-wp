<?php
/**
 * Light SEO: SEO title and description per page (sidebar panel "SEO" in the editor),
 * Open Graph tags and company data for Google. Switches itself off when
 * Yoast SEO, Rank Math or SEOPress is active.
 */

defined( 'ABSPATH' ) || exit;

function r24_seo_active(): bool {
	return ! defined( 'WPSEO_VERSION' ) && ! defined( 'RANK_MATH_VERSION' ) && ! defined( 'SEOPRESS_VERSION' );
}

add_action( 'init', function () {
	foreach ( [ 'post', 'page', 'realizacja' ] as $type ) {
		foreach ( [ 'r24_seo_title', 'r24_seo_desc' ] as $key ) {
			register_post_meta( $type, $key, [
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => fn() => current_user_can( 'edit_posts' ),
			] );
		}
	}
} );

/** Description for the current view: own field, then excerpt, then the site tagline. */
function r24_meta_description(): string {
	if ( is_singular() ) {
		$id   = get_queried_object_id();
		$desc = (string) get_post_meta( $id, 'r24_seo_desc', true );
		if ( ! $desc && has_excerpt( $id ) ) {
			$desc = get_the_excerpt( $id );
		}
		if ( $desc ) {
			return wp_strip_all_tags( $desc );
		}
	}
	if ( is_category() && category_description() ) {
		return wp_strip_all_tags( category_description() );
	}
	return (string) get_bloginfo( 'description' );
}

add_filter( 'pre_get_document_title', function ( $title ) {
	if ( r24_seo_active() && is_singular() ) {
		$own = (string) get_post_meta( get_queried_object_id(), 'r24_seo_title', true );
		if ( $own ) {
			return $own;
		}
	}
	return $title;
} );
add_filter( 'document_title_separator', fn() => '·' );

add_action( 'wp_head', function () {
	if ( ! r24_seo_active() ) {
		return;
	}
	$desc  = r24_meta_description();
	$title = wp_get_document_title();
	$url   = is_singular() ? get_permalink() : home_url( add_query_arg( [] ) );
	$image = is_singular() && has_post_thumbnail() ? wp_get_attachment_image_url( get_post_thumbnail_id(), 'large' ) : '';
	if ( $desc ) {
		printf( "<meta name=\"description\" content=\"%s\">\n", esc_attr( $desc ) );
	}
	printf( "<meta property=\"og:type\" content=\"%s\">\n", is_single() ? 'article' : 'website' );
	printf( "<meta property=\"og:title\" content=\"%s\">\n", esc_attr( $title ) );
	printf( "<meta property=\"og:url\" content=\"%s\">\n", esc_url( $url ) );
	printf( "<meta property=\"og:site_name\" content=\"%s\">\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( "<meta property=\"og:locale\" content=\"pl_PL\">\n" );
	if ( $desc ) {
		printf( "<meta property=\"og:description\" content=\"%s\">\n", esc_attr( $desc ) );
	}
	if ( $image ) {
		printf( "<meta property=\"og:image\" content=\"%s\">\n<meta name=\"twitter:card\" content=\"summary_large_image\">\n", esc_url( $image ) );
	}

	if ( is_front_page() ) {
		$org = array_filter( [
			'@context'   => 'https://schema.org',
			'@type'      => 'HomeAndConstructionBusiness',
			'name'       => r24_opt( 'company_full' ) ?: r24_opt( 'company_short' ),
			'url'        => home_url( '/' ),
			'telephone'  => r24_opt( 'phone' ),
			'email'      => r24_opt( 'email' ),
			'address'    => r24_opt( 'address' ) ?: null,
			'areaServed' => [ 'Gdańsk', 'Gdynia', 'Sopot' ],
			'sameAs'     => array_values( array_filter( [ r24_opt( 'instagram' ), r24_opt( 'facebook' ), r24_opt( 'tiktok' ) ] ) ),
			'logo'       => function_exists( 'r24_theme_logo_url' ) ? r24_theme_logo_url() : null,
		] );
		echo '<script type="application/ld+json">' . wp_json_encode( $org, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . "</script>\n";
	} elseif ( is_singular( 'post' ) ) {
		$article = [
			'@context'      => 'https://schema.org',
			'@type'         => 'Article',
			'headline'      => get_the_title(),
			'datePublished' => get_the_date( 'c' ),
			'dateModified'  => get_the_modified_date( 'c' ),
			'image'         => $image ?: null,
			'author'        => [ '@type' => 'Organization', 'name' => r24_opt( 'company_short' ) ],
			'publisher'     => [ '@type' => 'Organization', 'name' => r24_opt( 'company_short' ) ],
		];
		echo '<script type="application/ld+json">' . wp_json_encode( array_filter( $article ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . "</script>\n";
	}
}, 2 );
