<?php
/**
 * Shown to visitors while the Remontuj24 plugin is switched off.
 */
defined( 'ABSPATH' ) || exit;
status_header( 503 );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title><?php bloginfo( 'name' ); ?></title>
<style>body{margin:0;min-height:100vh;display:grid;place-items:center;background:#F7F5F1;color:#171717;font:17px/1.6 system-ui,sans-serif;text-align:center;padding:24px}</style>
</head>
<body>
<p><?php bloginfo( 'name' ); ?> · <?php esc_html_e( 'Strona jest chwilowo niedostępna. Zapraszamy za chwilę.', 'remontuj24' ); ?></p>
</body>
</html>
