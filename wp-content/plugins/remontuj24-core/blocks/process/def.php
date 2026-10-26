<?php
return [
	'title'       => 'Proces krok po kroku',
	'description' => 'Etapy z przewijanym zdjęciem i złotą linią postępu.',
	'icon'        => 'editor-ol',
	'order'       => 4,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text' ],
		[ 'key' => 'title', 'label' => 'Tytuł', 'type' => 'textarea' ],
		[ 'key' => 'lead', 'label' => 'Wstęp', 'type' => 'textarea' ],
		[ 'key' => 'steps', 'label' => 'Etapy', 'type' => 'repeater', 'itemLabel' => 'title', 'fields' => [
			[ 'key' => 'title', 'label' => 'Nazwa etapu', 'type' => 'text' ],
			[ 'key' => 'text', 'label' => 'Opis', 'type' => 'textarea' ],
			[ 'key' => 'note', 'label' => 'Wyróżnione zdanie (opcjonalnie)', 'type' => 'text' ],
			[ 'key' => 'image', 'label' => 'Zdjęcie etapu', 'type' => 'image' ],
		] ],
		[ 'key' => 'link_text', 'label' => 'Link pod etapami: tekst', 'type' => 'text' ],
		[ 'key' => 'link_url', 'label' => 'Link pod etapami: adres', 'type' => 'url' ],
	],
];
