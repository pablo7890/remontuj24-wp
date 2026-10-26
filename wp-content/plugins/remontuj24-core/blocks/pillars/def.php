<?php
return [
	'title'       => 'Trzy filary',
	'description' => 'Pas z numerowanymi hasłami na kremowym tle.',
	'icon'        => 'columns',
	'order'       => 3,
	'fields'      => [
		[ 'key' => 'items', 'label' => 'Hasła', 'type' => 'repeater', 'itemLabel' => 'title', 'fields' => [
			[ 'key' => 'title', 'label' => 'Hasło', 'type' => 'text' ],
			[ 'key' => 'text', 'label' => 'Opis', 'type' => 'text' ],
		] ],
	],
];
