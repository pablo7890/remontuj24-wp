<?php
return [
	'title'       => 'Style wnętrz (lista)',
	'description' => 'Duże zdjęcia stylów naprzemiennie z opisem, paletą kolorów i spisem na górze.',
	'icon'        => 'art',
	'order'       => 14,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text' ],
		[ 'key' => 'title', 'label' => 'Tytuł', 'type' => 'textarea' ],
		[ 'key' => 'index', 'label' => 'Pokaż spis stylów', 'type' => 'toggle', 'default' => true ],
		[ 'key' => 'items', 'label' => 'Style', 'type' => 'repeater', 'itemLabel' => 'name', 'fields' => [
			[ 'key' => 'name', 'label' => 'Nazwa stylu', 'type' => 'text' ],
			[ 'key' => 'tagline', 'label' => 'Hasło', 'type' => 'text' ],
			[ 'key' => 'text', 'label' => 'Opis', 'type' => 'textarea' ],
			[ 'key' => 'image', 'label' => 'Zdjęcie', 'type' => 'image' ],
			[ 'key' => 'colors', 'label' => 'Paleta: kolory HEX po przecinku', 'type' => 'text', 'help' => 'Np. #D8C3A0, #ECE6DA, #B39B80, #3D3A36' ],
			[ 'key' => 'colors_label', 'label' => 'Paleta: podpis', 'type' => 'text' ],
		] ],
	],
];
