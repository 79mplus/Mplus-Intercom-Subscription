<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link https://www.79mplus.com/
 * @since 1.0.0
 *
 * @package Mplus_Intercom_Core
 * @subpackage Mplus_Intercom_Core/includes
 */
class Mplus_Intercom_Subscription_i18n {

	/**
	 * The domain specified for this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $domain The domain identifier for this plugin.
	 */
	private $domain;

	/**
	 * Loads the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function mplus_load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

	/**
	 * Set the domain equal to that of the specified domain.
	 *
	 * @since 1.0.0
	 * @param string $domain The domain that represents the locale of this plugin.
	 * @return void
	 */
	public function set_domain( $domain ) {
		$this->domain = $domain;
	}

}
