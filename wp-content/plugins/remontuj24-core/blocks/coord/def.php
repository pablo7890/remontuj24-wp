<?php
return [
	'title'       => 'Koordynacja na odległość',
	'description' => 'Dwa nakładające się zdjęcia, tytuł i lista krótkich faktów.',
	'icon'        => 'location-alt',
	'order'       => 6,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text' ],
		[ 'key' => 'title', 'label' => 'Tytuł', 'type' => 'textarea' ],
		[ 'key' => 'lead', 'label' => 'Wstęp', 'type' => 'textarea' ],
		[ 'key' => 'facts', 'label' => 'Fakty (każdy w osobnej linii)', 'type' => 'textarea' ],
		[ 'key' => 'link_text', 'label' => 'Link: tekst', 'type' => 'text' ],
		[ 'key' => 'link_url', 'label' => 'Link: adres', 'type' => 'url' ],
		[ 'key' => 'image', 'label' => 'Zdjęcie główne', 'type' => 'image' ],
		[ 'key' => 'inset', 'label' => 'Zdjęcie nakładane', 'type' => 'image' ],
	],
];
