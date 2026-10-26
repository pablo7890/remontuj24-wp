<?php
return [
	'title'       => 'Kierunki wnętrz (karuzela)',
	'description' => 'Pionowe karty stylów; na telefonie przewijane palcem.',
	'icon'        => 'images-alt2',
	'order'       => 5,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text' ],
		[ 'key' => 'title', 'label' => 'Tytuł', 'type' => 'textarea' ],
		[ 'key' => 'lead', 'label' => 'Wstęp', 'type' => 'textarea' ],
		[ 'key' => 'items', 'label' => 'Style', 'type' => 'repeater', 'itemLabel' => 'name', 'fields' => [
			[ 'key' => 'name', 'label' => 'Nazwa', 'type' => 'text' ],
			[ 'key' => 'text', 'label' => 'Opis', 'type' => 'text' ],
			[ 'key' => 'image', 'label' => 'Zdjęcie (pionowe)', 'type' => 'image' ],
			[ 'key' => 'url', 'label' => 'Link', 'type' => 'url' ],
		] ],
		[ 'key' => 'link_text', 'label' => 'Link pod kartami: tekst', 'type' => 'text' ],
		[ 'key' => 'link_url', 'label' => 'Link pod kartami: adres', 'type' => 'url' ],
	],
];
