<?php
/**
 * Newsletter sign-up: the form, and delivery to the provider chosen in
 * Remontuj24 → Ustawienia → Newsletter (local list, MailerLite, Brevo or Mailchimp).
 */

defined( 'ABSPATH' ) || exit;

function r24_newsletter_enabled(): bool {
	return '1' === r24_opt( 'news_enabled' );
}

/** The sign-up form. $id is unique per page because the form can appear twice. */
function r24_newsletter_form( string $id = 'news', string $class = '' ): string {
	if ( ! r24_newsletter_enabled() ) {
		return '';
	}
	ob_start();
	?>
	<form class="news <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $id ); ?>" data-news method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
		<input type="hidden" name="action" value="r24_newsletter">
		<input type="hidden" name="r24_back" value="<?php echo esc_url( home_url( add_query_arg( [] ) ) ); ?>">
		<?php echo r24_spam_fields(); ?>
		<div class="field"><input type="email" name="email" id="<?php echo esc_attr( $id ); ?>-email" placeholder="<?php esc_attr_e( 'Twój e-mail', 'remontuj24' ); ?>" autocomplete="email" aria-label="<?php esc_attr_e( 'Adres e-mail', 'remontuj24' ); ?>" required><button type="submit" aria-label="<?php esc_attr_e( 'Zapisz się', 'remontuj24' ); ?>"><?php echo r24_arrow(); ?></button></div>
		<label class="consent"><input type="checkbox" name="consent" value="1" required> <?php echo esc_html( r24_opt( 'news_consent' ) ); ?></label>
		<span class="msg" role="status"><?php echo isset( $_GET['zapisano'] ) ? esc_html( r24_opt( 'news_success' ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification ?></span>
	</form>
	<?php
	return (string) ob_get_clean();
}

function r24_handle_newsletter(): void {
	// phpcs:disable WordPress.Security.NonceVerification -- cached pages; see forms.php.
	$back    = esc_url_raw( wp_unslash( $_POST['r24_back'] ?? home_url( '/' ) ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$consent = ! empty( $_POST['consent'] );
	$bot     = ! empty( $_POST['website'] ) || ! r24_form_token_ok( (string) wp_unslash( $_POST['r24_t'] ?? '' ) );
	// phpcs:enable
	$reply = function ( bool $ok, string $msg, int $status = 200 ) use ( $back ) {
		if ( str_contains( (string) ( $_SERVER['HTTP_ACCEPT'] ?? '' ), 'application/json' ) ) {
			wp_send_json( [ 'ok' => $ok, 'message' => $msg ], $status );
		}
		wp_safe_redirect( add_query_arg( $ok ? 'zapisano' : 'blad-zapisu', '1', wp_validate_redirect( $back, home_url( '/' ) ) ) . '#news' );
		exit;
	};
	if ( $bot ) {
		$reply( true, (string) r24_opt( 'news_success' ) );
	}
	if ( ! is_email( $email ) ) {
		$reply( false, __( 'Wpisz poprawny adres e-mail.', 'remontuj24' ), 422 );
	}
	if ( ! $consent ) {
		$reply( false, __( 'Zaznacz zgodę, aby się zapisać.', 'remontuj24' ), 422 );
	}
	if ( r24_rate_limited( 'news', 5 ) ) {
		$reply( false, __( 'Zbyt wiele prób. Spróbuj za godzinę.', 'remontuj24' ), 429 );
	}
	$result = r24_newsletter_subscribe( $email );
	if ( is_wp_error( $result ) ) {
		error_log( 'Remontuj24 newsletter: ' . $result->get_error_message() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
		$reply( false, __( 'Nie udało się zapisać. Spróbuj ponownie za chwilę.', 'remontuj24' ), 502 );
	}
	$reply( true, (string) r24_opt( 'news_success' ) );
}
add_action( 'admin_post_nopriv_r24_newsletter', 'r24_handle_newsletter' );
add_action( 'admin_post_r24_newsletter', 'r24_handle_newsletter' );

/**
 * Adds the address to the configured list. Providers send their own
 * confirmation e-mail (double opt-in) where it is enabled in their panel.
 *
 * @return true|WP_Error
 */
function r24_newsletter_subscribe( string $email ) {
	$provider = (string) r24_opt( 'news_provider' );
	$key      = trim( (string) r24_opt( 'news_api_key' ) );
	$list     = trim( (string) r24_opt( 'news_list' ) );

	// Every address is also kept locally with the consent date (proof of consent under GDPR).
	r24_newsletter_store_local( $email, $provider );
	if ( 'local' === $provider ) {
		return true;
	}
	if ( ! $key ) {
		return new WP_Error( 'r24_no_key', 'Brak klucza API newslettera.' );
	}

	switch ( $provider ) {
		case 'mailerlite':
			$res = wp_remote_post( 'https://connect.mailerlite.com/api/subscribers', [
				'timeout' => 12,
				'headers' => [ 'Authorization' => 'Bearer ' . $key, 'Content-Type' => 'application/json', 'Accept' => 'application/json' ],
				'body'    => wp_json_encode( array_filter( [ 'email' => $email, 'groups' => $list ? [ $list ] : null ] ) ),
			] );
			break;
		case 'brevo':
			$template = (int) r24_opt( 'news_doi_template' );
			$url      = $template ? 'https://api.brevo.com/v3/contacts/doubleOptinConfirmation' : 'https://api.brevo.com/v3/contacts';
			$body     = $template
				? [ 'email' => $email, 'includeListIds' => [ (int) $list ], 'templateId' => $template, 'redirectionUrl' => home_url( '/?newsletter=potwierdzony' ) ]
				: [ 'email' => $email, 'listIds' => [ (int) $list ], 'updateEnabled' => true ];
			$res = wp_remote_post( $url, [
				'timeout' => 12,
				'headers' => [ 'api-key' => $key, 'Content-Type' => 'application/json', 'Accept' => 'application/json' ],
				'body'    => wp_json_encode( $body ),
			] );
			break;
		case 'mailchimp':
			$dc  = substr( strrchr( $key, '-' ) ?: '', 1 );
			$res = wp_remote_request( sprintf( 'https://%s.api.mailchimp.com/3.0/lists/%s/members/%s', rawurlencode( $dc ), rawurlencode( $list ), md5( strtolower( $email ) ) ), [
				'method'  => 'PUT',
				'timeout' => 12,
				'headers' => [ 'Authorization' => 'Basic ' . base64_encode( 'r24:' . $key ), 'Content-Type' => 'application/json' ],
				'body'    => wp_json_encode( [ 'email_address' => $email, 'status_if_new' => 'pending' ] ),
			] );
			break;
		default:
			return true;
	}
	if ( is_wp_error( $res ) ) {
		return $res;
	}
	$code = (int) wp_remote_retrieve_response_code( $res );
	// Brevo answers 400 "duplicate_parameter" for an address already on the list: that is fine.
	if ( $code >= 200 && $code < 300 || ( 'brevo' === $provider && str_contains( (string) wp_remote_retrieve_body( $res ), 'duplicate' ) ) ) {
		return true;
	}
	return new WP_Error( 'r24_provider', $provider . ' HTTP ' . $code . ': ' . wp_remote_retrieve_body( $res ) );
}

function r24_newsletter_store_local( string $email, string $provider ): void {
	$existing = get_posts( [ 'post_type' => 'r24_subskrybent', 'post_status' => 'any', 'title' => $email, 'numberposts' => 1, 'fields' => 'ids' ] );
	if ( $existing ) {
		return;
	}
	$id = wp_insert_post( [ 'post_type' => 'r24_subskrybent', 'post_status' => 'private', 'post_title' => $email ] );
	if ( $id && ! is_wp_error( $id ) ) {
		update_post_meta( $id, 'r24_consent_text', (string) r24_opt( 'news_consent' ) );
		update_post_meta( $id, 'r24_provider', $provider );
	}
}

/** "Pobierz CSV" on the subscribers list. */
add_action( 'manage_posts_extra_tablenav', function ( $which ) {
	if ( 'top' === $which && 'r24_subskrybent' === get_current_screen()->post_type ) {
		printf( '<a class="button" href="%s">Pobierz listę (CSV)</a>', esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=r24_news_csv' ), 'r24_news_csv' ) ) );
	}
} );
add_action( 'admin_post_r24_news_csv', function () {
	if ( ! current_user_can( 'edit_posts' ) || ! check_admin_referer( 'r24_news_csv' ) ) {
		wp_die( 'Brak uprawnień.' );
	}
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=subskrybenci-' . gmdate( 'Y-m-d' ) . '.csv' );
	$out = fopen( 'php://output', 'w' );
	fputcsv( $out, [ 'email', 'data_zapisu', 'zgoda' ] );
	foreach ( get_posts( [ 'post_type' => 'r24_subskrybent', 'post_status' => 'any', 'numberposts' => -1 ] ) as $p ) {
		fputcsv( $out, [ $p->post_title, $p->post_date, get_post_meta( $p->ID, 'r24_consent_text', true ) ] );
	}
	fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	exit;
} );
