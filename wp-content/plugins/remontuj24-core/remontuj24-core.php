<?php
/**
 * Plugin Name:       Remontuj24 — bloki i ustawienia
 * Description:       Bloki sekcji strony, Realizacje, formularz kontaktowy, newsletter, baner cookies i dane firmy dla motywu Remontuj24.
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      8.1
 * Author:            Remontuj24
 * Text Domain:       remontuj24
 * License:           GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

const R24_VERSION = '1.0.0';
define( 'R24_DIR', plugin_dir_path( __FILE__ ) );
define( 'R24_URL', plugin_dir_url( __FILE__ ) );

require R24_DIR . 'inc/helpers.php';
require R24_DIR . 'inc/settings.php';
require R24_DIR . 'inc/typography.php';
require R24_DIR . 'inc/realizacje.php';
require R24_DIR . 'inc/blocks.php';
require R24_DIR . 'inc/forms.php';
require R24_DIR . 'inc/newsletter.php';
require R24_DIR . 'inc/cookies.php';
require R24_DIR . 'inc/seo.php';
require R24_DIR . 'inc/shortcodes.php';
require R24_DIR . 'inc/images.php';
require R24_DIR . 'inc/starter.php';

register_activation_hook( __FILE__, function () {
	r24_register_realizacje();
	r24_register_private_types();
	flush_rewrite_rules();
} );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
