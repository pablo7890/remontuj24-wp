<?php
return [
	'title'       => 'Czynniki / zasady',
	'description' => 'Kolumny z ikoną lub numerem, nagłówkiem i krótkim opisem.',
	'icon'        => 'screenoptions',
	'order'       => 13,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text' ],
		[ 'key' => 'title', 'label' => 'Tytuł', 'type' => 'textarea' ],
		[ 'key' => 'lead', 'label' => 'Wstęp', 'type' => 'textarea' ],
		[ 'key' => 'items', 'label' => 'Kolumny', 'type' => 'repeater', 'itemLabel' => 'title', 'fields' => [
			[ 'key' => 'icon', 'label' => 'Ikona', 'type' => 'select', 'default' => 'number', 'options' => [
				'number' => 'Numer (01, 02…)',
				'plan'   => 'Rzut z drzwiami',
				'person' => 'Osoba',
				'bars'   => 'Słupki (standard)',
				'check'  => 'Odhaczone',
				'house'  => 'Dom',
				'target' => 'Cel',
				'screen' => 'Laptop i telefon',
				'none'   => 'Bez ikony',
			] ],
			[ 'key' => 'title', 'label' => 'Nagłówek', 'type' => 'text' ],
			[ 'key' => 'text', 'label' => 'Opis', 'type' => 'textarea' ],
		] ],
		[ 'key' => 'closing', 'label' => 'Zdanie podsumowania pod kolumnami', 'type' => 'text' ],
		[ 'key' => 'tight', 'label' => 'Bez odstępu od góry', 'type' => 'toggle' ],
	],
];
