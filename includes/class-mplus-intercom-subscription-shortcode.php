<?php

/**
 * Manages Shortcodes functionality of this plugin.
 *
 * @package Mplus_Intercom_Subscription
 * @subpackage Mplus_Intercom_Subscription/includes
 * @author 79mplus
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'Mplus_Intercom_Subscription_Shortcode' ) ) {
	class Mplus_Intercom_Subscription_Shortcode {

		/**
		 * Constructs the class.
		 *
		 * @return void
		 */
		function __construct() {

		}

		/**
		 * Handles the [mplus_intercom_subscription] shortcode.
		 *
		 * @param array $atts Holds the shortcode parameters.
		 * @return string Returns html for the ouput.
		 */
		public function mplus_intercom_subscription( $atts ) {

			if ( ! is_admin() ) {
				// Generates shortcode output.
				$html = mplus_intercom_subscription_get_template( 'mplus-intercom-subscription-shortcode.php' );
				return $html;
			} else {
				return '';
			}

		}

		/**
		 * Handles the [mplus_intercom_subscription_company] shortcode.
		 *
		 * @param array $atts Holds the shortcode parameters.
		 * @return string Returns html for the ouput.
		 */
		public function mplus_intercom_subscription_company( $atts ) {

			if ( ! is_admin() ) {
				// Generates shortcode output.
				$html = mplus_intercom_subscription_get_template( 'mplus-intercom-subscription-company-shortcode.php' );
				return $html;
			} else {
				return '';
			}

		}
	}
}
