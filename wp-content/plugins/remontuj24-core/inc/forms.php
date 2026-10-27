<?php
/**
 * Contact form: markup, spam protection and delivery.
 *
 * No nonce on purpose: pages are often cached and a stale nonce would block
 * real visitors. Spam is stopped by a hidden field, a signed time stamp
 * (a form filled in under 3 seconds is a bot) and a per-IP limit.
 */

defined( 'ABSPATH' ) || exit;

/** Signed time stamp put in the form when the page is rendered. */
function r24_form_token(): string {
	$time = time();
	return $time . '.' . substr( hash_hmac( 'sha256', (string) $time, wp_salt( 'nonce' ) ), 0, 16 );
}

/** True when the token is genuine and the form was not filled in by a bot. */
function r24_form_token_ok( string $token ): bool {
	[ $time, $sig ] = array_pad( explode( '.', $token, 2 ), 2, '' );
	$valid          = hash_equals( substr( hash_hmac( 'sha256', $time, wp_salt( 'nonce' ) ), 0, 16 ), $sig );
	$age            = time() - (int) $time;
	return $valid && $age >= 3 && $age < DAY_IN_SECONDS * 2;
}

/** Hidden anti-spam fields shared by the contact and newsletter forms. */
function r24_spam_fields(): string {
	return '<input type="hidden" name="r24_t" value="' . esc_attr( r24_form_token() ) . '">'
		. '<div class="hp" aria-hidden="true"><label>Strona www <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>';
}

/** Stops repeated submissions from one address: $max per hour. */
function r24_rate_limited( string $what, int $max ): bool {
	$ip  = preg_replace( '/[^0-9a-f:.]/i', '', (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	$key = 'r24_rl_' . $what . '_' . md5( $ip );
	$n   = (int) get_transient( $key );
	if ( $n >= $max ) {
		return true;
	}
	set_transient( $key, $n + 1, HOUR_IN_SECONDS );
	return false;
}

function r24_contact_form( array $a, bool $sent = false, bool $error = false ): string {
	$hid = r24_uid( 'h-form' );
	ob_start();
	?>
	<form class="form rv<?php echo $sent ? ' sent' : ''; ?>" id="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate aria-labelledby="<?php echo esc_attr( $hid ); ?>">
		<h2 id="<?php echo esc_attr( $hid ); ?>"><?php echo esc_html( $a['form_title'] ?? __( 'Formularz kontaktowy', 'remontuj24' ) ); ?></h2>
		<input type="hidden" name="action" value="r24_contact">
		<input type="hidden" name="r24_back" value="<?php echo esc_url( get_permalink() ?: home_url( '/' ) ); ?>">
		<?php echo r24_spam_fields(); ?>
		<div class="ff" data-req>
			<label for="f-name"><?php esc_html_e( 'Imię', 'remontuj24' ); ?></label>
			<input id="f-name" name="name" autocomplete="given-name" maxlength="80" aria-describedby="e-name">
			<span class="err" id="e-name" hidden><?php esc_html_e( 'Podaj imię, abyśmy wiedzieli, jak się zwracać.', 'remontuj24' ); ?></span>
		</div>
		<div class="ff" data-req>
			<label for="f-contact"><?php esc_html_e( 'Telefon lub e-mail', 'remontuj24' ); ?></label>
			<input id="f-contact" name="contact" autocomplete="email" maxlength="120" aria-describedby="e-contact">
			<span class="err" id="e-contact" hidden><?php esc_html_e( 'Podaj telefon lub e-mail, żebyśmy mogli odpowiedzieć.', 'remontuj24' ); ?></span>
		</div>
		<div class="ff" data-req>
			<label for="f-msg"><?php esc_html_e( 'Wiadomość', 'remontuj24' ); ?></label>
			<textarea id="f-msg" name="message" rows="4" maxlength="5000" placeholder="<?php echo esc_attr( $a['placeholder'] ?? '' ); ?>" aria-describedby="e-msg"></textarea>
			<span class="err" id="e-msg" hidden><?php esc_html_e( 'Napisz kilka słów o mieszkaniu.', 'remontuj24' ); ?></span>
		</div>
		<div class="ff check" data-req>
			<input type="checkbox" id="f-rodo" name="rodo" value="1" aria-describedby="e-rodo">
			<label for="f-rodo"><?php echo r24_with_policy_link( (string) r24_opt( 'form_consent' ) ); ?></label>
			<span class="err" id="e-rodo" hidden><?php esc_html_e( 'Zaznacz zgodę, abyśmy mogli odpowiedzieć.', 'remontuj24' ); ?></span>
		</div>
		<p class="form-error" role="alert"<?php echo $error ? '' : ' hidden'; ?>><?php echo esc_html( sprintf( __( 'Nie udało się wysłać wiadomości. Spróbuj ponownie albo zadzwoń: %s.', 'remontuj24' ), r24_phone_display() ) ); ?></p>
		<button class="btn" type="submit"><?php echo esc_html( $a['button'] ?? __( 'Wyślij wiadomość', 'remontuj24' ) ); ?> <?php echo r24_arrow(); ?></button>
		<div class="done" id="form-done" tabindex="-1"<?php echo $sent ? '' : ' hidden'; ?>>
			<?php echo r24_sygnet(); ?>
			<h2><?php echo esc_html( $a['done_title'] ?? __( 'Dziękujemy za wiadomość.', 'remontuj24' ) ); ?></h2>
			<p><?php echo esc_html( r24_opt( 'form_success' ) ); ?>
				<?php if ( r24_opt( 'phone' ) ) : ?>
					<?php esc_html_e( 'Jeśli sprawa jest pilna, zadzwoń:', 'remontuj24' ); ?> <a href="<?php echo esc_attr( r24_tel() ); ?>" class="nowrap"><?php echo esc_html( r24_phone_display() ); ?></a>.
				<?php endif; ?>
			</p>
		</div>
	</form>
	<?php
	return (string) ob_get_clean();
}

/** Sends a JSON answer for fetch() or redirects back for a plain form post. */
function r24_form_reply( bool $ok, string $back, string $message = '', int $status = 200 ): void {
	$wants_json = str_contains( (string) ( $_SERVER['HTTP_ACCEPT'] ?? '' ), 'application/json' );
	if ( $wants_json ) {
		wp_send_json( [ 'ok' => $ok, 'message' => $message ], $ok ? 200 : $status );
	}
	$back = wp_validate_redirect( $back, home_url( '/' ) );
	wp_safe_redirect( add_query_arg( $ok ? 'wyslano' : 'blad', '1', $back ) . ( $ok ? '#form-done' : '#contact-form' ) );
	exit;
}

function r24_handle_contact(): void {
	// phpcs:disable WordPress.Security.NonceVerification -- see file comment.
	$back    = esc_url_raw( wp_unslash( $_POST['r24_back'] ?? home_url( '/' ) ) );
	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$contact = sanitize_text_field( wp_unslash( $_POST['contact'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
	$consent = ! empty( $_POST['rodo'] );
	$bot     = ! empty( $_POST['website'] ) || ! r24_form_token_ok( (string) wp_unslash( $_POST['r24_t'] ?? '' ) );
	// phpcs:enable

	if ( $bot ) {
		// Bots get a polite yes and nothing is sent.
		r24_form_reply( true, $back );
	}
	$is_email = (bool) is_email( $contact );
	$is_phone = strlen( preg_replace( '/\D/', '', $contact ) ) >= 9;
	if ( mb_strlen( $name ) < 2 || ! ( $is_email || $is_phone ) || mb_strlen( $message ) < 2 || ! $consent ) {
		r24_form_reply( false, $back, __( 'Uzupełnij wszystkie pola formularza.', 'remontuj24' ), 422 );
	}
	if ( r24_rate_limited( 'contact', 8 ) ) {
		r24_form_reply( false, $back, __( 'Zbyt wiele wiadomości w krótkim czasie. Spróbuj za godzinę albo zadzwoń.', 'remontuj24' ), 429 );
	}

	$page = wp_parse_url( $back, PHP_URL_PATH ) ?: '/';
	$id   = wp_insert_post( [
		'post_type'   => 'r24_wiadomosc',
		'post_status' => 'private',
		'post_title'  => $name,
	] );
	if ( $id && ! is_wp_error( $id ) ) {
		update_post_meta( $id, 'r24_contact', $contact );
		update_post_meta( $id, 'r24_message', $message );
		update_post_meta( $id, 'r24_page', $page );
		update_post_meta( $id, 'r24_consent', current_time( 'mysql' ) );
	}

	$to      = r24_opt( 'form_to' ) ?: r24_opt( 'email' ) ?: get_option( 'admin_email' );
	$body    = sprintf( "Imię: %s\nKontakt: %s\nStrona: %s\n\n%s\n\n— \nWiadomość zapisana też w panelu: %s", $name, $contact, $page, $message, admin_url( 'edit.php?post_type=r24_wiadomosc' ) );
	$headers = [ 'Content-Type: text/plain; charset=UTF-8' ];
	if ( $is_email ) {
		$headers[] = 'Reply-To: ' . str_replace( [ "\r", "\n", '<', '>' ], '', $name ) . ' <' . $contact . '>';
	}
	if ( is_email( (string) r24_opt( 'form_cc' ) ) ) {
		$headers[] = 'Cc: ' . r24_opt( 'form_cc' );
	}
	$sent = wp_mail( $to, (string) r24_opt( 'form_subject' ), $body, $headers );

	if ( $sent && $is_email && '1' === r24_opt( 'form_autoreply' ) ) {
		wp_mail(
			$contact,
			(string) r24_opt( 'form_autoreply_subject' ),
			trim( (string) r24_opt( 'form_autoreply_text' ) ) . "\n" . implode( ' · ', array_filter( [ r24_opt( 'phone' ), r24_opt( 'email' ) ] ) ),
			[ 'Content-Type: text/plain; charset=UTF-8' ]
		);
	}
	// The message is stored even when the mail server fails, so nothing is lost.
	r24_form_reply( (bool) $id, $back, '', 500 );
}
add_action( 'admin_post_nopriv_r24_contact', 'r24_handle_contact' );
add_action( 'admin_post_r24_contact', 'r24_handle_contact' );
