<?php
/**
 * Remontuj24 → Treści startowe: one click fills a fresh WordPress with the pages,
 * guide articles, privacy policy, menus and photos from the prototype. Safe to run
 * again: anything that already exists (same slug, same photo) is left untouched.
 */

defined( 'ABSPATH' ) || exit;

/** Alt texts for the starter photos, by file name without extension. */
function r24_starter_alts(): array {
	return [
		'hero-przed-1024'             => 'Salon w stanie deweloperskim, tuż po odbiorze kluczy',
		'hero-po-1024'                => 'Ten sam salon po wykończeniu, gotowy do zamieszkania',
		'hero-przed-m-640'            => 'Salon w stanie deweloperskim, tuż po odbiorze kluczy',
		'hero-po-m-640'               => 'Ten sam salon po wykończeniu, gotowy do zamieszkania',
		'lazienka-przed-1024'         => 'Łazienka w stanie deweloperskim',
		'lazienka-po-1024'            => 'Wykończona łazienka z kamienną okładziną',
		'proces-1-1600'               => 'Rozmowa o mieszkaniu przy stole z próbkami materiałów',
		'proces-2-1600'               => 'Plan mieszkania i harmonogram prac',
		'proces-3-1600'               => 'Próbki materiałów wykończeniowych',
		'proces-4-1600'               => 'Prace wykończeniowe w mieszkaniu',
		'proces-5-1600'               => 'Meble i oświetlenie w wykończonym wnętrzu',
		'proces-6-1600'               => 'Gotowe mieszkanie przygotowane do przekazania',
		'styl-japandi-800'            => 'Wnętrze w stylu Japandi',
		'styl-japandi-l-1600'         => 'Wnętrze w stylu Japandi',
		'styl-modern-classic-800'     => 'Wnętrze w stylu Modern Classic',
		'styl-modern-classic-l-1600'  => 'Wnętrze w stylu Modern Classic',
		'styl-soft-minimal-800'       => 'Wnętrze w stylu Soft Minimal',
		'styl-soft-minimal-l-1600'    => 'Wnętrze w stylu Soft Minimal',
		'styl-elegant-natural-800'    => 'Wnętrze w stylu Elegant Natural',
		'styl-elegant-natural-l-1600' => 'Wnętrze w stylu Elegant Natural',
		'styl-contemporary-l-1600'    => 'Wnętrze w stylu Contemporary',
		'styl-soft-glamour-l-1600'    => 'Wnętrze w stylu Soft Glamour',
		'gdansk-1600'                 => 'Gdańsk, widok na Motławę',
		'zdalnie-1600'                => 'Warszawa, widok na centrum miasta',
		'insp-top-1600'               => 'Jasny salon z naturalnymi materiałami',
		'martwa-natura-1600'          => 'Detal wnętrza: ceramika i len',
		'salon-l-1600'                => 'Przestronny salon z dużym oknem',
		'kuchnia-p-720'               => 'Kuchnia z zabudową na wymiar',
		'panorama-1940'               => 'Panorama wykończonego mieszkania',
	];
}

add_action( 'admin_menu', function () {
	add_submenu_page( 'remontuj24', 'Treści startowe', 'Treści startowe', 'manage_options', 'r24-starter', 'r24_starter_page' );
}, 20 );

function r24_starter_page(): void {
	$done = get_transient( 'r24_starter_log' );
	delete_transient( 'r24_starter_log' );
	$theme_ok = 'remontuj24' === get_stylesheet() || 'remontuj24' === get_template();
	?>
	<div class="wrap">
		<h1>Treści startowe</h1>
		<p style="max-width:680px">Tworzy strony, wpisy Poradnika, politykę prywatności, menu i zdjęcia z projektu strony. Istniejące strony i wpisy o tym samym adresie zostają bez zmian, więc import można bezpiecznie uruchomić ponownie.</p>
		<?php if ( ! $theme_ok ) : ?>
			<div class="notice notice-warning inline"><p>Najpierw włącz motyw <b>Remontuj24</b> (Wygląd → Motywy), aby menu trafiły na właściwe miejsca.</p></div>
		<?php endif; ?>
		<?php if ( $done ) : ?>
			<div class="notice notice-success"><p><b>Gotowe.</b></p><ul style="list-style:disc;padding-left:20px">
				<?php foreach ( $done as $line ) : ?><li><?php echo esc_html( $line ); ?></li><?php endforeach; ?>
			</ul></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="r24_starter">
			<?php wp_nonce_field( 'r24_starter' ); ?>
			<fieldset style="display:grid;gap:10px;margin:20px 0">
				<?php
				$opts = [
					'pages'    => 'Strony (Strona główna, Jak działamy, Zakres obsługi, Inspiracje, Realizacje, Poradnik, O nas, Kontakt)',
					'posts'    => 'Trzy artykuły Poradnika',
					'policy'   => 'Polityka prywatności i plików cookies (wzór do uzupełnienia)',
					'menus'    => 'Menu główne, menu w stopce i dokumenty',
					'settings' => 'Strona główna, bezpośrednie odnośniki (/nazwa-strony/), polityka prywatności w ustawieniach',
					'example'  => 'Przykładowa realizacja jako szkic (do podglądu, nie pojawi się na stronie)',
				];
				foreach ( $opts as $key => $label ) {
					printf( '<label><input type="checkbox" name="r24_do[]" value="%s" checked> %s</label>', esc_attr( $key ), esc_html( $label ) );
				}
				?>
			</fieldset>
			<?php submit_button( 'Zaimportuj treści startowe' ); ?>
		</form>
	</div>
	<?php
}

add_action( 'admin_post_r24_starter', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Brak uprawnień.' );
	}
	check_admin_referer( 'r24_starter' );
	@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors -- some hosts forbid it
	$do  = array_map( 'sanitize_key', (array) ( $_POST['r24_do'] ?? [] ) );
	$log = r24_starter_run( $do );
	set_transient( 'r24_starter_log', $log, 300 );
	wp_safe_redirect( admin_url( 'admin.php?page=r24-starter' ) );
	exit;
} );

/** Runs the chosen import steps and returns a human-readable log. */
function r24_starter_run( array $do ): array {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$log = [];

	if ( in_array( 'pages', $do, true ) ) {
		$made = 0;
		foreach ( require R24_DIR . 'demo/pages.php' as $page ) {
			if ( get_page_by_path( $page['slug'] ) ) {
				continue;
			}
			$blocks = array_map( fn( $b ) => [
				'blockName'    => 'r24/' . $b[0],
				'attrs'        => r24_starter_images( $b[1] ),
				'innerBlocks'  => [],
				'innerHTML'    => '',
				'innerContent' => [],
			], $page['blocks'] );
			$id = wp_insert_post( wp_slash( [
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => $page['slug'],
				'post_title'   => $page['title'],
				'post_content' => serialize_blocks( $blocks ),
				'meta_input'   => [ 'r24_seo_desc' => $page['seo'] ?? '' ],
			] ) );
			if ( $id && ! is_wp_error( $id ) ) {
				++$made;
				if ( ! empty( $page['front'] ) ) {
					update_option( 'r24_starter_front', $id );
				}
			}
		}
		$log[] = sprintf( 'Strony: utworzono %d.', $made );
	}

	if ( in_array( 'posts', $do, true ) ) {
		$made = 0;
		foreach ( require R24_DIR . 'demo/articles.php' as $post ) {
			if ( get_page_by_path( $post['slug'], OBJECT, 'post' ) ) {
				continue;
			}
			$term = term_exists( $post['category'], 'category' ) ?: wp_insert_term( $post['category'], 'category' );
			$id   = wp_insert_post( wp_slash( [
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_name'     => $post['slug'],
				'post_title'    => $post['title'],
				'post_date'     => $post['date'],
				'post_excerpt'  => $post['excerpt'],
				'post_content'  => $post['content'],
				'post_category' => is_array( $term ) ? [ (int) $term['term_id'] ] : [],
				'meta_input'    => [ 'r24_seo_title' => $post['seo_title'], 'r24_seo_desc' => $post['seo_desc'] ],
			] ) );
			if ( $id && ! is_wp_error( $id ) ) {
				++$made;
				set_post_thumbnail( $id, r24_starter_media( $post['image'] ) );
			}
		}
		// The default "Bez kategorii" post is not part of the guide.
		$hello = get_page_by_path( 'witaj-swiecie', OBJECT, 'post' ) ?: get_page_by_path( 'hello-world', OBJECT, 'post' );
		if ( $hello && 'publish' === $hello->post_status ) {
			wp_trash_post( $hello->ID );
		}
		$log[] = sprintf( 'Poradnik: utworzono %d artykuły.', $made );
	}

	if ( in_array( 'policy', $do, true ) ) {
		// WordPress creates its own draft policy on install (in Polish under the same address);
		// it would only confuse, so it gives way to the template.
		foreach ( [ 'polityka-prywatnosci', 'privacy-policy' ] as $slug ) {
			$core = get_page_by_path( $slug );
			if ( $core && 'draft' === $core->post_status && ! str_contains( $core->post_content, '[r24_firma' ) ) {
				wp_delete_post( $core->ID, true );
			}
		}
		$policy = get_page_by_path( 'polityka-prywatnosci' );
		if ( ! $policy ) {
			$id = wp_insert_post( wp_slash( [
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => 'polityka-prywatnosci',
				'post_title'   => 'Polityka prywatności i plików cookies',
				'post_content' => require R24_DIR . 'demo/policy.php',
				'meta_input'   => [
					'r24_seo_title' => 'Polityka prywatności | ' . r24_opt( 'company_short', 'Remontuj24' ),
					'r24_seo_desc'  => 'Jak przetwarzamy dane osób, które kontaktują się z nami, zapisują się do newslettera lub odwiedzają stronę.',
				],
			] ) );
			$policy = $id && ! is_wp_error( $id ) ? get_post( $id ) : null;
			$log[]  = 'Polityka prywatności: utworzona. Uzupełnij dane firmy w Remontuj24 → Ustawienia i fragmenty wyróżnione w edytorze.';
		}
		if ( $policy ) {
			update_option( 'wp_page_for_privacy_policy', $policy->ID );
		}
	}

	if ( in_array( 'example', $do, true ) && ! get_page_by_path( 'mieszkanie-z-salonem-na-dwie-strony', OBJECT, 'realizacja' ) ) {
		$id = wp_insert_post( wp_slash( [
			'post_type'    => 'realizacja',
			'post_status'  => 'draft',
			'post_name'    => 'mieszkanie-z-salonem-na-dwie-strony',
			'post_title'   => 'Mieszkanie z salonem na dwie strony',
			'post_content' => r24_starter_example_content(),
			'meta_input'   => [
				'r24_miasto'       => 'Gdańsk',
				'r24_metraz'       => '58 m²',
				'r24_zakres'       => 'Pod klucz',
				'r24_czas'         => '14 tygodni',
				'r24_lead'         => 'Stan deweloperski, właściciel na co dzień poza Trójmiastem i jeden cel: gotowe wnętrze bez osobnych ustaleń z kilkoma ekipami.',
				'r24_przed'        => r24_starter_media( 'hero-przed-1024' ),
				'r24_po'           => r24_starter_media( 'hero-po-1024' ),
				'r24_galeria'      => array_map( 'r24_starter_media', [ 'proces-6-1600', 'kuchnia-p-720', 'lazienka-po-1024', 'proces-5-1600' ] ),
				'r24_opinia'       => 'Najbardziej ceniłem to, że miałem jeden kontakt i zawsze wiedziałem, na jakim etapie są prace.',
				'r24_opinia_autor' => 'Przykładowa opinia właściciela',
				'r24_przyklad'     => true,
			],
		] ) );
		if ( $id && ! is_wp_error( $id ) ) {
			set_post_thumbnail( $id, r24_starter_media( 'hero-po-1024' ) );
			$log[] = 'Przykładowa realizacja: zapisana jako szkic w Realizacje. Zobacz ją w podglądzie i usuń, gdy dodasz pierwszą prawdziwą.';
		}
	}

	if ( in_array( 'menus', $do, true ) ) {
		$log[] = r24_starter_menus();
	}

	if ( in_array( 'settings', $do, true ) ) {
		$front = (int) get_option( 'r24_starter_front' ) ?: ( get_page_by_path( 'strona-glowna' )?->ID ?? 0 );
		if ( $front ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front );
		}
		if ( ! get_option( 'permalink_structure' ) ) {
			update_option( 'permalink_structure', '/%postname%/' );
		}
		update_option( 'blogdescription', 'Mieszkania pod klucz · Trójmiasto' );
		flush_rewrite_rules();
		$log[] = 'Ustawienia: strona główna i bezpośrednie odnośniki ustawione.';
	}

	$map   = get_option( 'r24_media_map', [] );
	$log[] = sprintf( 'Biblioteka mediów: %d zdjęć projektu.', count( $map ) );
	return $log;
}

/** Swaps every "img:<file>" value for the media library ID, importing the photo once. */
function r24_starter_images( array $attrs ): array {
	array_walk_recursive( $attrs, function ( &$value ) {
		if ( is_string( $value ) && str_starts_with( $value, 'img:' ) ) {
			$value = r24_starter_media( substr( $value, 4 ) );
		}
	} );
	return $attrs;
}

/** Adds demo/img/<name>.webp to the media library (once) and returns its ID. */
function r24_starter_media( string $name ): int {
	$map = get_option( 'r24_media_map', [] );
	if ( ! empty( $map[ $name ] ) && get_post( $map[ $name ] ) ) {
		return (int) $map[ $name ];
	}
	$file = R24_DIR . 'demo/img/' . $name . '.webp';
	if ( ! is_readable( $file ) ) {
		return 0;
	}
	$upload = wp_upload_bits( basename( $file ), null, file_get_contents( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$alt = r24_starter_alts()[ $name ] ?? '';
	$id  = wp_insert_attachment( [
		'post_mime_type' => 'image/webp',
		'post_title'     => $alt ?: $name,
		'post_status'    => 'inherit',
	], $upload['file'] );
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	$map[ $name ] = $id;
	update_option( 'r24_media_map', $map, false );
	return $id;
}

function r24_starter_example_content(): string {
	$h = fn( $t ) => "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">$t</h2>\n<!-- /wp:heading -->\n\n";
	$p = fn( $t ) => "<!-- wp:paragraph -->\n<p>$t</p>\n<!-- /wp:paragraph -->\n\n";
	$items = [
		'Ustalenie zakresu, standardu i harmonogramu prac',
		'Prace wykończeniowe: podłogi, ściany, łazienka',
		'Zamówienia i dostawy materiałów zgodnie z przebiegiem prac',
		'Kuchnia i zabudowy na wymiar, oświetlenie, AGD',
		'Odbiór prac i przygotowanie mieszkania do przekazania',
	];
	$li = implode( "\n", array_map( fn( $i ) => "<!-- wp:list-item -->\n<li>$i</li>\n<!-- /wp:list-item -->", $items ) );
	return $h( 'Punkt wyjścia' )
		. $p( 'Mieszkanie odebrane od dewelopera w stanie deweloperskim. Właściciel na stałe mieszka poza Trójmiastem, miał gotowy układ funkcjonalny i kilka inspiracji w stylu soft minimal. Zależało mu na terminie i na tym, aby nie koordynować samodzielnie kilku ekip.' )
		. $h( 'Co zrobiliśmy' )
		. "<!-- wp:list {\"className\":\"done-list\"} -->\n<ul class=\"wp-block-list done-list\">$li</ul>\n<!-- /wp:list -->\n\n"
		. $h( 'Efekt' )
		. $p( 'Mieszkanie zostało przekazane gotowe do zamieszkania. Najważniejsze decyzje zapadały zdalnie, na podstawie zdjęć i krótkich raportów z kolejnych etapów, a na miejscu całość prowadził jeden koordynator.' );
}

/** Creates the three menus from the prototype and assigns them to the theme's locations. */
function r24_starter_menus(): string {
	$main  = [ 'jak-dzialamy', 'zakres-obslugi', 'inspiracje', 'realizacje', 'poradnik', 'o-nas', 'kontakt' ];
	$menus = [
		'primary' => [ 'Menu główne', $main ],
		'footer'  => [ 'Stopka', $main ],
		'legal'   => [ 'Dokumenty', [ 'polityka-prywatnosci', [ 'Cookies', '#cookie-settings' ] ] ],
	];
	$locations = [];
	foreach ( $menus as $location => [ $name, $items ] ) {
		$menu = wp_get_nav_menu_object( $name );
		if ( $menu ) {
			$locations[ $location ] = $menu->term_id;
			continue;
		}
		$menu_id = wp_create_nav_menu( $name );
		if ( is_wp_error( $menu_id ) ) {
			continue;
		}
		foreach ( $items as $i => $item ) {
			if ( is_array( $item ) ) {
				wp_update_nav_menu_item( $menu_id, 0, [
					'menu-item-title'    => $item[0],
					'menu-item-url'      => $item[1],
					'menu-item-type'     => 'custom',
					'menu-item-status'   => 'publish',
					'menu-item-position' => $i + 1,
				] );
				continue;
			}
			$page = get_page_by_path( $item );
			if ( ! $page ) {
				continue;
			}
			wp_update_nav_menu_item( $menu_id, 0, [
				'menu-item-title'     => 'polityka-prywatnosci' === $item ? 'Polityka prywatności' : $page->post_title,
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $i + 1,
			] );
		}
		$locations[ $location ] = $menu_id;
	}
	// Written straight into the theme's mods, so it works even before the theme is switched on.
	$mods                       = get_option( 'theme_mods_remontuj24', [] );
	$mods                       = is_array( $mods ) ? $mods : [];
	$mods['nav_menu_locations'] = array_merge( $mods['nav_menu_locations'] ?? [], $locations );
	update_option( 'theme_mods_remontuj24', $mods );
	return 'Menu: główne, w stopce i dokumenty przypisane do motywu.';
}
