<?php
return [
	'title'       => 'Zaproszenie do rozmowy (ciemne)',
	'description' => 'Ciemna sekcja z przyciskiem i telefonem. Najlepiej raz na stronę, na końcu.',
	'icon'        => 'phone',
	'order'       => 30,
	'fields'      => [
		[ 'key' => 'eyebrow', 'label' => 'Nadtytuł', 'type' => 'text' ],
		[ 'key' => 'title', 'label' => 'Tytuł', 'type' => 'textarea' ],
		[ 'key' => 'sub', 'label' => 'Podtytuł (jaśniejszy)', 'type' => 'textarea' ],
		[ 'key' => 'text', 'label' => 'Tekst', 'type' => 'textarea' ],
		[ 'key' => 'btn_text', 'label' => 'Przycisk: tekst', 'type' => 'text', 'default' => 'Porozmawiajmy o mieszkaniu' ],
		[ 'key' => 'btn_url', 'label' => 'Przycisk: adres', 'type' => 'url', 'default' => '/kontakt/' ],
		[ 'key' => 'phone_label', 'label' => 'Podpis nad telefonem', 'type' => 'text', 'default' => 'Zadzwoń lub napisz' ],
	],
];
