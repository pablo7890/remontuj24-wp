<?php
return [
	'title'       => 'Nagłówek podstrony',
	'description' => 'Tytuł strony, wstęp i zdjęcie w ramie.',
	'icon'        => 'align-pull-left',
	'order'       => 2,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text' ],
		[ 'key' => 'title', 'label' => 'Tytuł (każda linia osobno)', 'type' => 'textarea' ],
		[ 'key' => 'tagline', 'label' => 'Podtytuł kursywą (opcjonalnie)', 'type' => 'text' ],
		[ 'key' => 'lead', 'label' => 'Wstęp', 'type' => 'textarea' ],
		[ 'key' => 'text', 'label' => 'Dalszy tekst', 'type' => 'textarea' ],
		[ 'key' => 'motto', 'label' => 'Motto wersalikami (ostatnie zdanie na złoto)', 'type' => 'text' ],
		[ 'key' => 'image', 'label' => 'Zdjęcie', 'type' => 'image' ],
		[ 'key' => 'crumbs', 'label' => 'Pokaż okruszki nawigacji', 'type' => 'toggle', 'default' => true ],
	],
];
