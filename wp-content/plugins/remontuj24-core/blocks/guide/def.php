<?php
return [
	'title'       => 'Poradnik: najnowsze artykuły',
	'description' => 'Karty najnowszych wpisów z Poradnika.',
	'icon'        => 'welcome-learn-more',
	'order'       => 7,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text', 'default' => 'Poradnik' ],
		[ 'key' => 'title', 'label' => 'Tytuł', 'type' => 'textarea' ],
		[ 'key' => 'lead', 'label' => 'Wstęp', 'type' => 'textarea' ],
		[ 'key' => 'count', 'label' => 'Liczba artykułów', 'type' => 'number', 'default' => 3 ],
		[ 'key' => 'link_text', 'label' => 'Link do wszystkich: tekst', 'type' => 'text', 'default' => 'Zobacz wszystkie artykuły' ],
	],
];
