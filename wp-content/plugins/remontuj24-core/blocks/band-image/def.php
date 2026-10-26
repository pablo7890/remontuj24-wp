<?php
return [
	'title'       => 'Zdjęcie panoramiczne',
	'description' => 'Pas zdjęcia na całą szerokość z delikatną paralaksą.',
	'icon'        => 'format-image',
	'order'       => 20,
	'fields'      => [
		[ 'key' => 'image', 'label' => 'Zdjęcie (szerokie)', 'type' => 'image' ],
		[ 'key' => 'height', 'label' => 'Wysokość', 'type' => 'select', 'default' => 'tall', 'options' => [ 'tall' => 'Wysoki pas', 'low' => 'Niski pas' ] ],
	],
];
