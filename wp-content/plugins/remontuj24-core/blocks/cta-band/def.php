<?php
return [
	'title'       => 'Pas z zaproszeniem lub newsletterem',
	'description' => 'Jasny pas: tytuł i tekst po lewej, po prawej przycisk albo formularz newslettera.',
	'icon'        => 'megaphone',
	'order'       => 17,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text' ],
		[ 'key' => 'title', 'label' => 'Tytuł', 'type' => 'textarea' ],
		[ 'key' => 'text', 'label' => 'Tekst', 'type' => 'textarea' ],
		[ 'key' => 'mode', 'label' => 'Po prawej', 'type' => 'select', 'default' => 'button', 'options' => [ 'button' => 'Przycisk i telefon', 'newsletter' => 'Formularz newslettera' ] ],
		[ 'key' => 'btn_text', 'label' => 'Przycisk: tekst', 'type' => 'text', 'default' => 'Porozmawiajmy o mieszkaniu' ],
		[ 'key' => 'btn_url', 'label' => 'Przycisk: adres', 'type' => 'url', 'default' => '/kontakt/' ],
	],
];
