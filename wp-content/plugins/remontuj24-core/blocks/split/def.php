<?php
return [
	'title'       => 'Tekst ze zdjęciem',
	'description' => 'Tytuł, tekst i przycisk obok zdjęcia; strony można zamienić.',
	'icon'        => 'align-pull-right',
	'order'       => 10,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text' ],
		[ 'key' => 'title', 'label' => 'Tytuł', 'type' => 'textarea' ],
		[ 'key' => 'lead', 'label' => 'Wstęp', 'type' => 'textarea' ],
		[ 'key' => 'text', 'label' => 'Tekst (można używać <strong>)', 'type' => 'textarea' ],
		[ 'key' => 'btn_text', 'label' => 'Przycisk lub link: tekst', 'type' => 'text' ],
		[ 'key' => 'btn_url', 'label' => 'Przycisk lub link: adres', 'type' => 'url' ],
		[ 'key' => 'btn_style', 'label' => 'Wygląd odnośnika', 'type' => 'select', 'default' => 'link', 'options' => [ 'link' => 'Link ze strzałką', 'btn' => 'Czarny przycisk' ] ],
		[ 'key' => 'image', 'label' => 'Zdjęcie', 'type' => 'image' ],
		[ 'key' => 'flip', 'label' => 'Zdjęcie po lewej', 'type' => 'toggle' ],
		[ 'key' => 'cream', 'label' => 'Kremowe tło', 'type' => 'toggle' ],
		[ 'key' => 'tight', 'label' => 'Mniejszy odstęp od góry', 'type' => 'toggle' ],
	],
];
