<?php
return [
	'title'       => 'Myśl przewodnia',
	'description' => 'Wyśrodkowany cytat z sygnetem, linią i mottem.',
	'icon'        => 'format-quote',
	'order'       => 15,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text' ],
		[ 'key' => 'title', 'label' => 'Tytuł (opcjonalnie)', 'type' => 'textarea' ],
		[ 'key' => 'lead', 'label' => 'Tekst pod tytułem', 'type' => 'textarea' ],
		[ 'key' => 'quote', 'label' => 'Cytat', 'type' => 'textarea' ],
		[ 'key' => 'motto', 'label' => 'Motto wersalikami (ostatnie zdanie na złoto)', 'type' => 'text' ],
		[ 'key' => 'cream', 'label' => 'Kremowe tło', 'type' => 'toggle' ],
		[ 'key' => 'sygnet', 'label' => 'Pokaż sygnet (rysowane drzwi)', 'type' => 'toggle', 'default' => true ],
	],
];
