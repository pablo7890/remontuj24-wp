<?php
return [
	'title'       => 'Kontakt z formularzem',
	'description' => 'Dane kontaktowe (z ustawień) i formularz. Wiadomości trafiają na e-mail i do menu Remontuj24 → Wiadomości.',
	'icon'        => 'email-alt',
	'order'       => 16,
	'multiple'    => false,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text', 'default' => 'Kontakt' ],
		[ 'key' => 'title', 'label' => 'Tytuł (każda linia osobno)', 'type' => 'textarea' ],
		[ 'key' => 'rows', 'label' => 'Dodatkowe informacje', 'type' => 'repeater', 'itemLabel' => 'label', 'fields' => [
			[ 'key' => 'label', 'label' => 'Etykieta', 'type' => 'text' ],
			[ 'key' => 'value', 'label' => 'Wartość', 'type' => 'text' ],
		] ],
		[ 'key' => 'form_title', 'label' => 'Nagłówek formularza', 'type' => 'text', 'default' => 'Formularz kontaktowy' ],
		[ 'key' => 'placeholder', 'label' => 'Podpowiedź w polu wiadomości', 'type' => 'text', 'default' => 'Np. lokalizacja i metraż mieszkania, termin odbioru kluczy, czego potrzebujesz' ],
		[ 'key' => 'button', 'label' => 'Przycisk', 'type' => 'text', 'default' => 'Wyślij wiadomość' ],
		[ 'key' => 'done_title', 'label' => 'Nagłówek po wysłaniu', 'type' => 'text', 'default' => 'Dziękujemy za wiadomość.' ],
	],
];
