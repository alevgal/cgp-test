<?php

use CGP\Init;

/**
 * Plugin Name: CGP Test
 * Version: 0.0.1
 * Author: Aleksey Galkevych
 * Text Domain: cgp-test
 *
 * @package CGP
 */

defined('ABSPATH') || exit;

define('CGP_PLUGIN_DIR', untrailingslashit(plugin_dir_path(__FILE__)));
define('CGP_PLUGIN_FILE', __FILE__);
define('CGP_PLUGIN_URL', untrailingslashit(plugin_dir_url(CGP_PLUGIN_FILE)));

if (!file_exists($composer = CGP_PLUGIN_DIR . '/vendor/autoload.php')) {
	wp_die( sprintf(
		"<h1>%s</h1><p>%s</p>",
		__("CGP Error"),
		__('You must run <code>composer install</code> from plugin directory')
	), "CGI Error" );
}
require_once $composer;

new Init();