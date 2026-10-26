<?php
/**
 * Settings page "Remontuj24": company data, contact, social profiles,
 * contact form, newsletter, cookies and analytics. Stored in one option, r24_settings.
 */

defined( 'ABSPATH' ) || exit;

/** Every field on the settings page, grouped into tabs. */
function r24_settings_schema(): array {
	return [
		'firma'      => [
			'label'  => 'Firma i kontakt',
			'intro'  => 'Dane pojawiają się w stopce, w sekcjach kontaktowych, w polityce prywatności i w danych dla Google.',
			'fields' => [
				'company_short' => [ 'Nazwa marki', 'text', 'Remontuj24' ],
				'tagline'       => [ 'Hasło pod nazwą', 'text', 'Mieszkania pod klucz · Trójmiasto' ],
				'company_full'  => [ 'Pełna nazwa firmy (do dokumentów)', 'text', '' ],
				'address'       => [ 'Adres siedziby', 'text', '' ],
				'nip'           => [ 'NIP', 'text', '' ],
				'regon'         => [ 'REGON', 'text', '' ],
				'phone'         => [ 'Telefon', 'text', '512 64 00 44' ],
				'email'         => [ 'E-mail', 'email', 'biuro@remontuj24.pl' ],
				'area'          => [ 'Obszar działania', 'text', 'Gdańsk · Gdynia · Sopot' ],
				'remote_note'   => [ 'Informacja dla właścicieli spoza regionu', 'text', 'Ustalenia zdalnie' ],
				'footer_about'  => [ 'Opis w stopce', 'textarea', 'Kompleksowa organizacja i koordynacja przygotowania mieszkań — od pierwszych ustaleń do gotowego wnętrza.' ],
			],
		],
		'naglowek'   => [
			'label'  => 'Nagłówek i social media',
			'intro'  => 'Puste pole profilu ukrywa ikonę w stopce i menu.',
			'fields' => [
				'header_cta_text' => [ 'Przycisk w nagłówku: tekst', 'text', 'Porozmawiajmy o mieszkaniu' ],
				'header_cta_url'  => [ 'Przycisk w nagłówku: adres', 'text', '/kontakt/' ],
				'instagram'       => [ 'Instagram (adres profilu)', 'url', '' ],
				'facebook'        => [ 'Facebook (adres profilu)', 'url', '' ],
				'tiktok'          => [ 'TikTok (adres profilu)', 'url', '' ],
			],
		],
		'formularz'  => [
			'label'  => 'Formularz kontaktowy',
			'intro'  => 'Wiadomości trafiają na podany adres i zapisują się w menu „Wiadomości”. Na hostingu warto skonfigurować wysyłkę przez SMTP (np. wtyczką WP Mail SMTP), żeby poczta nie trafiała do spamu.',
			'fields' => [
				'form_to'       => [ 'Adres, na który przychodzą wiadomości', 'email', '' ],
				'form_subject'  => [ 'Temat e-maila', 'text', 'Nowa wiadomość ze strony remontuj24.pl' ],
				'form_consent'  => [ 'Treść zgody pod formularzem', 'textarea', 'Wyrażam zgodę na przetwarzanie moich danych w celu odpowiedzi na wiadomość. Szczegóły w {polityka}.' ],
				'form_success'  => [ 'Komunikat po wysłaniu', 'textarea', 'Odezwiemy się, aby umówić krótką rozmowę o mieszkaniu.' ],
				'form_autoreply' => [ 'Wysyłaj nadawcy potwierdzenie e-mailem', 'checkbox', '1' ],
			],
		],
		'newsletter' => [
			'label'  => 'Newsletter',
			'intro'  => 'Wybierz usługę, w której prowadzona jest lista. Przy „Lista w WordPressie” adresy zapisują się w menu „Subskrybenci” i można je pobrać jako CSV.',
			'fields' => [
				'news_enabled'  => [ 'Pokazuj formularz newslettera', 'checkbox', '1' ],
				'news_provider' => [ 'Usługa', 'select', 'local', [
					'local'      => 'Lista w WordPressie',
					'mailerlite' => 'MailerLite',
					'brevo'      => 'Brevo (Sendinblue)',
					'mailchimp'  => 'Mailchimp',
				] ],
				'news_api_key'  => [ 'Klucz API', 'password', '' ],
				'news_list'     => [ 'ID listy / grupy', 'text', '' ],
				'news_doi_template' => [ 'Brevo: ID szablonu e-maila potwierdzającego (double opt-in)', 'text', '' ],
				'news_title'    => [ 'Nagłówek w stopce', 'text', 'Poradnik na e-mail' ],
				'news_text'     => [ 'Opis w stopce', 'text', 'Nowe artykuły raz na jakiś czas. Bez spamu.' ],
				'news_consent'  => [ 'Treść zgody', 'textarea', 'Zgadzam się na otrzymywanie newslettera. Mogę zrezygnować w każdej chwili.' ],
				'news_success'  => [ 'Komunikat po zapisie', 'text', 'Dziękujemy. Potwierdzenie zapisu przyjdzie na podany adres.' ],
			],
		],
		'tresci'     => [
			'label'  => 'Poradnik i sekcje',
			'intro'  => 'Teksty powtarzane na wielu stronach.',
			'fields' => [
				'article_cta_title' => [ 'Ramka pod artykułem: nagłówek', 'text', 'Planujesz wykończenie mieszkania?' ],
				'article_cta_text'  => [ 'Ramka pod artykułem: tekst', 'textarea', 'Krótka rozmowa pozwala ustalić, czego wymaga mieszkanie i w jaki sposób najlepiej poprowadzić kolejne etapy.' ],
				'article_cta_btn'   => [ 'Ramka pod artykułem: przycisk', 'text', 'Porozmawiajmy o mieszkaniu' ],
				'article_aside'     => [ 'Notka obok artykułu', 'textarea', 'Organizujemy i koordynujemy wykończenie mieszkań w Gdańsku, Gdyni i Sopocie, również dla właścicieli spoza regionu.' ],
				'contact_url'       => [ 'Adres strony kontaktu', 'text', '/kontakt/' ],
				'privacy_url'       => [ 'Adres polityki prywatności', 'text', '/polityka-prywatnosci/' ],
			],
		],
		'cookies'    => [
			'label'  => 'Cookies i analityka',
			'intro'  => 'Skrypty analityczne i marketingowe ładują się dopiero po zgodzie odwiedzającego. Link „Cookies” w stopce pozwala zmienić decyzję.',
			'fields' => [
				'cookie_banner' => [ 'Pokazuj baner cookies', 'checkbox', '1' ],
				'cookie_text'   => [ 'Treść banera', 'textarea', 'Używamy plików cookies, aby strona działała poprawnie, a za Twoją zgodą także do statystyk odwiedzin. Szczegóły w {polityka}.' ],
				'ga4_id'        => [ 'Google Analytics 4: identyfikator (G-…)', 'text', '' ],
				'meta_pixel'    => [ 'Meta Pixel: identyfikator', 'text', '' ],
				'head_code'     => [ 'Dodatkowy kod analityczny (ładowany po zgodzie)', 'code', '' ],
			],
		],
	];
}

/** Defaults taken from the schema. */
function r24_setting_defaults(): array {
	$defaults = [];
	foreach ( r24_settings_schema() as $tab ) {
		foreach ( $tab['fields'] as $key => $field ) {
			$defaults[ $key ] = $field[2];
		}
	}
	return $defaults;
}

add_action( 'admin_menu', function () {
	add_menu_page( 'Remontuj24', 'Remontuj24', 'manage_options', 'remontuj24', 'r24_settings_page', 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="none" stroke="#a7aaad" stroke-width="4" d="M12 44H4V4h40v40H28M12 44V28"/><path fill="none" stroke="#a7aaad" stroke-width="3" d="M12 28a16 16 0 0 1 16 16"/></svg>' ), 59 );
	add_submenu_page( 'remontuj24', 'Ustawienia', 'Ustawienia', 'manage_options', 'remontuj24', 'r24_settings_page' );
} );

add_action( 'admin_init', function () {
	register_setting( 'r24_settings', 'r24_settings', [
		'type'              => 'array',
		'sanitize_callback' => 'r24_sanitize_settings',
		'default'           => [],
	] );
} );

/** Cleans submitted values by field type; fields of other tabs are kept. */
function r24_sanitize_settings( $input ): array {
	$input   = is_array( $input ) ? $input : [];
	$current = (array) get_option( 'r24_settings', [] );
	$tab     = sanitize_key( $input['_tab'] ?? '' );
	$schema  = r24_settings_schema();
	if ( ! isset( $schema[ $tab ] ) ) {
		return $current;
	}
	foreach ( $schema[ $tab ]['fields'] as $key => $field ) {
		$raw = $input[ $key ] ?? '';
		switch ( $field[1] ) {
			case 'email':
				$current[ $key ] = sanitize_email( $raw );
				break;
			case 'url':
				$current[ $key ] = esc_url_raw( trim( $raw ) );
				break;
			case 'textarea':
				$current[ $key ] = sanitize_textarea_field( $raw );
				break;
			case 'checkbox':
				$current[ $key ] = empty( $raw ) ? '0' : '1';
				break;
			case 'select':
				$current[ $key ] = array_key_exists( $raw, $field[3] ) ? $raw : $field[2];
				break;
			case 'code':
				$current[ $key ] = current_user_can( 'unfiltered_html' ) ? trim( (string) $raw ) : ( $current[ $key ] ?? '' );
				break;
			default:
				$current[ $key ] = sanitize_text_field( $raw );
		}
	}
	return $current;
}

function r24_settings_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$schema = r24_settings_schema();
	$tab    = sanitize_key( $_GET['tab'] ?? 'firma' );
	$tab    = isset( $schema[ $tab ] ) ? $tab : 'firma';
	$values = wp_parse_args( (array) get_option( 'r24_settings', [] ), r24_setting_defaults() );
	echo '<div class="wrap r24-settings"><h1>Remontuj24 · Ustawienia</h1><nav class="nav-tab-wrapper">';
	foreach ( $schema as $key => $t ) {
		printf( '<a class="nav-tab%s" href="%s">%s</a>', $key === $tab ? ' nav-tab-active' : '', esc_url( admin_url( 'admin.php?page=remontuj24&tab=' . $key ) ), esc_html( $t['label'] ) );
	}
	echo '</nav>';
	settings_errors();
	echo '<form method="post" action="options.php">';
	settings_fields( 'r24_settings' );
	printf( '<input type="hidden" name="r24_settings[_tab]" value="%s">', esc_attr( $tab ) );
	printf( '<p class="description" style="max-width:720px;margin:16px 0">%s</p><table class="form-table" role="presentation">', esc_html( $schema[ $tab ]['intro'] ) );
	foreach ( $schema[ $tab ]['fields'] as $key => $field ) {
		[ $label, $type ] = $field;
		$name  = 'r24_settings[' . $key . ']';
		$value = (string) ( $values[ $key ] ?? '' );
		echo '<tr><th scope="row"><label for="r24-' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th><td>';
		switch ( $type ) {
			case 'textarea':
				printf( '<textarea id="r24-%s" name="%s" rows="3" class="large-text">%s</textarea>', esc_attr( $key ), esc_attr( $name ), esc_textarea( $value ) );
				if ( str_contains( $field[2], '{polityka}' ) ) {
					echo '<p class="description">{polityka} zamienia się w link do polityki prywatności.</p>';
				}
				break;
			case 'code':
				printf( '<textarea id="r24-%s" name="%s" rows="6" class="large-text code">%s</textarea><p class="description">Np. kod Hotjar lub Google Tag Manager. Wklejany przed &lt;/head&gt; dopiero po zgodzie na cookies analityczne.</p>', esc_attr( $key ), esc_attr( $name ), esc_textarea( $value ) );
				break;
			case 'checkbox':
				printf( '<label><input type="checkbox" id="r24-%s" name="%s" value="1"%s> Tak</label>', esc_attr( $key ), esc_attr( $name ), checked( $value, '1', false ) );
				break;
			case 'select':
				printf( '<select id="r24-%s" name="%s">', esc_attr( $key ), esc_attr( $name ) );
				foreach ( $field[3] as $opt => $opt_label ) {
					printf( '<option value="%s"%s>%s</option>', esc_attr( $opt ), selected( $value, $opt, false ), esc_html( $opt_label ) );
				}
				echo '</select>';
				break;
			default:
				printf( '<input type="%s" id="r24-%s" name="%s" value="%s" class="regular-text" autocomplete="off">', esc_attr( 'password' === $type ? 'password' : ( 'email' === $type ? 'email' : 'text' ) ), esc_attr( $key ), esc_attr( $name ), esc_attr( $value ) );
		}
		echo '</td></tr>';
	}
	echo '</table>';
	if ( 'newsletter' === $tab ) {
		echo '<p class="description" style="max-width:720px">MailerLite: klucz z Integrations → API, ID grupy z Subscribers → Groups. Brevo: klucz API v3 i ID listy; podanie ID szablonu włącza potwierdzenie zapisu (double opt-in). Mailchimp: klucz API (z końcówką -us21 itp.) i Audience ID; Mailchimp sam wysyła e-mail potwierdzający.</p>';
	}
	submit_button( 'Zapisz zmiany' );
	echo '</form></div>';
}

/** Replaces {polityka} with a link to the privacy policy. */
function r24_with_policy_link( string $text ): string {
	$link = '<a href="' . esc_url( r24_url( r24_opt( 'privacy_url' ) ) ) . '">' . esc_html__( 'polityce prywatności', 'remontuj24' ) . '</a>';
	return str_replace( '{polityka}', $link, esc_html( $text ) );
}
