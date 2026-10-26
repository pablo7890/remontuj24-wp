<?php
return [
	'title'       => 'Poradnik: lista artykułów',
	'description' => 'Wszystkie artykuły z filtrem kategorii: najnowszy duży, pozostałe w kartach.',
	'icon'        => 'grid-view',
	'order'       => 19,
	'multiple'    => false,
	'fields'      => [
		[ 'key' => 'filter', 'label' => 'Pokaż filtr kategorii', 'type' => 'toggle', 'default' => true ],
		[ 'key' => 'featured', 'label' => 'Najnowszy artykuł w dużej karcie', 'type' => 'toggle', 'default' => true ],
		[ 'key' => 'per_page', 'label' => 'Artykułów na stronę', 'type' => 'number', 'default' => 13 ],
	],
];
