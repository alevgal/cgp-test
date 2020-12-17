<?php

namespace CGP\Admin;


class Admin {

	public function __construct() {
		self::filters();
		self::actions();
	}

	public static function actions()
	{
		add_action( 'woocommerce_settings_tabs_setting_cgi_test', [ __CLASS__, 'settingsTab' ]);
		add_action( 'woocommerce_update_options_setting_cgi_test', [ __CLASS__, 'updateSettings' ]);
	}

	public static function filters()
	{
		/**
		 * Add plugin setting tab to WC
		 */
		add_filter('woocommerce_settings_tabs_array', [ __CLASS__, 'addSettingTab']);
	}

	/**
	 * Add setting tab to WC settings page
	 *
	 * @param array $settingTabs - array of WC setting tabs
	 *
	 * @return array
	 */

	public static function addSettingTab($settingTabs)
	{
		$settingTabs['setting_cgi_test'] = __('CGI Test settings');
		return $settingTabs;
	}

	/**
	 * Creates Setting Tab via WC settings API, oprions degined in self fetSettings
	 *
	 * @uses woocommerce_admin_fields()
	 * @uses self::get_settings()
	 */
	public static function settingsTab() {
		woocommerce_admin_fields( self::getSettings() );
	}


	/**
	 * Update Options
	 *
	 * @uses woocommerce_update_options()
	 * @uses self::getSettings()
	 */
	public static function updateSettings() {
		woocommerce_update_options( self::getSettings() );
	}


	/**
	 * Get plugin settings
	 *
	 * @return array Array of settings for  woocommerce_admin_fields() function.
	 */
	public static function getSettings() {
		$settings = [
			'section_title' => [
				'name'     => __( 'CGI Discounts' ),
				'type'     => 'title',
				'desc'     => '',
				'id'       => 'wc_settings_cgi_section'
			],
			'registered_discount' => [
				'name' => __( 'Registered users discount, %' ),
				'type' => 'number',
				'desc' => __( 'Set the discount for registered users' ),
				'id'   => 'wc_setting_cgi_registered_discount',
				'custom_attributes' => [
					'min'  => 1,
					'max'   => 100,
					'step'  => 1
				]
			],
			'returned_discount' => [
				'name' => __( 'Returned users discount, %' ),
				'type' => 'number',
				'desc' => __( 'Set the discount for returned users' ),
				'id'   => 'wc_setting_cgi_returned_discount',
				'custom_attributes' => [
					'min'  => 1,
					'max'   => 100,
					'step'  => 1
				]
			],
			'section_end' => [
				'type' => 'sectionend',
				'id' => 'wc_setting_cgi_section_end'
			]
		];
		return $settings;
	}
}