<?php
/*
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages Shortcodes
 * @author 79mplus
 */
class Mplus_Intercom_Shortcode {

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
		// Default values for shortcode
		$a = shortcode_atts( array(
			'test' => 'testvalue',
		), $atts );

		// Generates shortcode output.
		$html = mplus_intercom_get_template( 'mplus-shortcode.php' );

		return $html;
	}
}
