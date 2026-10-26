<?php
return [
	'title'       => 'Realizacje: lista',
	'description' => 'Karty opublikowanych realizacji z filtrem miast. Realizacje dodaje się w menu Realizacje.',
	'icon'        => 'format-gallery',
	'order'       => 18,
	'multiple'    => false,
	'fields'      => [
		[ 'key' => 'filter', 'label' => 'Pokaż filtr miast', 'type' => 'toggle', 'default' => true ],
		[ 'key' => 'empty', 'label' => 'Tekst, gdy nie ma jeszcze realizacji', 'type' => 'text', 'default' => 'Pierwsze realizacje pojawią się tu wkrótce.' ],
	],
];
