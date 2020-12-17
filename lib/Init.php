<?php

namespace CGP;

use CGP\Admin\Admin;
use CGP\Discount\Discount;
use CGP\Login\Login;


class Init {
	public function __construct()
	{
		//Plugin activation
		register_activation_hook(CGP_PLUGIN_FILE, [$this, 'install']);

		self::actions();
		self::filters();

		/**
		 * Admin settings
		 */

		new Admin();

		/**
		 * Init discount
		 */

		new Discount();

		/**
		 * Init custom login actions
		 */

		Login::init();

	}

	public static function actions()
	{
		//Check if woocommerce is still active
		add_action('admin_init', function() {
			if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				deactivate_plugins('cgp-test/cgp-test.php');
			}
		});

		//Enqueue plugin js
		add_action('wp_enqueue_scripts', [ __CLASS__, 'scripts' ]);
	}

	public static function filters()
	{
		/**
		 * Add settings link to plugin links
		 */

		add_filter('plugin_action_links_' .plugin_basename(CGP_PLUGIN_FILE), function ( $links ) {
			$links[] = sprintf(
				'<a href="%s">%s</a>',
				add_query_arg(['page' => 'wc-settings', 'tab' => 'setting_cgi_test'], admin_url('admin.php')),
				__('Settings')
			);
			return $links;
		});
	}

	/**
	 * Load plugin js
	 */

	public static function scripts()
	{
		wp_register_script(
			'cgp-test/script',
			CGP_PLUGIN_URL . '/dist/main.js',
			['jquery', 'wp-i18n'],
			filemtime(CGP_PLUGIN_DIR . '/dist/main.js'),
			true
		);

		wp_localize_script('cgp-test/script', 'CGP',
			[
				'discount' => get_option('wc_setting_cgi_returned_discount'),
				'homeUrl'   => home_url('/')
			]);
		wp_enqueue_script('jquery');
		wp_enqueue_script('cgp-test/script');
	}

	/**
	 * Handle action on plugin install
	 */

	public function install()
	{
		//Check if woocommerce is active
		if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			wp_die( sprintf(
				"<h1>%s</h1><p>%s <a href='%s'>%s</a></p>",
				__("CGP Error"),
				__("This plugin requires WooCommerce"),
				admin_url('plugins.php'),
				__("Back to plugins")
			), "CGI Error" );
		}
	}
}