<?php
/**
 * Plugin Name:       Mplus Intercom Subscription Plugin
 * Plugin URI:        https://www.79mplus.com/
 * Description:       Intercom integration with WordPress by 79mplus
 * Version:           1.0.0
 * Author:            79mplus
 * Author URI:        https://www.79mplus.com/
 * License:           GNU General Public License v2 or later
 * Text Domain:       mplus-intercom-core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Stores plugin file path.
 */
define( 'MPLUSI', __FILE__ );
/**
 * Contains the language identifier for the plugin.
 * 
 * This is deprecated. Using this may break translation functionality.
 * DO NOT USE.
 */
define( 'MPLUSILANGUAGE', 'mplus-intercom-core' );
/**
 * Plugin name in slug.
 */
define( 'MPLUSI_NAME', 'mplus-intercom-core' );
/**
 * Plugin version.
 */
define( 'MPLUSIVERSION', '1.0.0' );
/**
 * Plugin directory.
 */
define( 'MPLUSI_PLUGINS_DIR', trailingslashit( plugin_dir_path( MPLUSI ) ) );
/**
 * Plugin directory url.
 */
define( 'MPLUSI_PLUGINS_DIR_URI', trailingslashit( plugin_dir_url( MPLUSI ) ) );

/**
 * Function that runs during plugin activation.
 * 
 * @return void
 */
function mplus_core_activate() {
	require_once MPLUSI_PLUGINS_DIR . 'includes/class-mplus-core-activator.php';
	Mplus_Intercom_Core_Activator::activate();
}

/**
 * Function that runs during plugin deactivation.
 * 
 * @return void
 */
function mplus_core_deactivate() {
	require_once MPLUSI_PLUGINS_DIR . 'includes/class-mplus-core-deactivator.php';
	Mplus_Intercom_Core_Deactivator::deactivate();
}

register_activation_hook( MPLUSI , 'mplus_core_activate' );
register_deactivation_hook( MPLUSI , 'mplus_core_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require MPLUSI_PLUGINS_DIR . 'includes/class-mplus-intercom-core.php';

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 * @return void
 */
function run_Mplus_Intercom_Core() {

	$plugin = new Mplus_Intercom_Core();
	$plugin->run();
	do_action( MPLUSI_NAME.'_loaded' );
	
}
run_Mplus_Intercom_Core();
