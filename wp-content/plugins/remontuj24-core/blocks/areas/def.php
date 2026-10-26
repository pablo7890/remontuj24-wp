<?php
return [
	'title'       => 'Obszary (lista ze zdjęciem)',
	'description' => 'Lista obszarów z opisami i przyklejonym zdjęciem obok.',
	'icon'        => 'list-view',
	'order'       => 11,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text' ],
		[ 'key' => 'title', 'label' => 'Tytuł', 'type' => 'textarea' ],
		[ 'key' => 'image', 'label' => 'Zdjęcie (pionowe, widoczne na komputerze)', 'type' => 'image' ],
		[ 'key' => 'items', 'label' => 'Obszary', 'type' => 'repeater', 'itemLabel' => 'title', 'fields' => [
			[ 'key' => 'title', 'label' => 'Nazwa', 'type' => 'text' ],
			[ 'key' => 'text', 'label' => 'Opis', 'type' => 'textarea' ],
		] ],
	],
];
