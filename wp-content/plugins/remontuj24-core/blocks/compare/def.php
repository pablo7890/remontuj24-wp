<?php
return [
	'title'       => 'Przed i po',
	'description' => 'Jeden lub dwa suwaki porównujące to samo pomieszczenie przed i po.',
	'icon'        => 'image-flip-horizontal',
	'order'       => 12,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text' ],
		[ 'key' => 'title', 'label' => 'Tytuł', 'type' => 'textarea' ],
		[ 'key' => 'lead', 'label' => 'Wstęp', 'type' => 'textarea' ],
		[ 'key' => 'items', 'label' => 'Porównania', 'type' => 'repeater', 'itemLabel' => 'title', 'fields' => [
			[ 'key' => 'title', 'label' => 'Pomieszczenie', 'type' => 'text' ],
			[ 'key' => 'caption', 'label' => 'Zakres prac', 'type' => 'text' ],
			[ 'key' => 'before', 'label' => 'Zdjęcie „przed”', 'type' => 'image' ],
			[ 'key' => 'after', 'label' => 'Zdjęcie „po”', 'type' => 'image' ],
		] ],
		[ 'key' => 'cream', 'label' => 'Kremowe tło', 'type' => 'toggle', 'default' => true ],
	],
];
