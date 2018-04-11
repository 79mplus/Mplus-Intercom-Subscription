<?php
/**
 * Plugin Name:       Mplus Intercom Subscription
 * Plugin URI:        https://www.79mplus.com/
 * Description:       Intercom integration with WordPress by 79mplus
 * Version:           1.0.0
 * Author:            79mplus
 * Author URI:        https://www.79mplus.com/
 * License:           GNU General Public License v2 or later
 * Text Domain:       mplus-intercom-core
 * Domain Path:       /languages
 */

// If this file is called directly, aborts execution.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Stores plugin file path.
 */
define( 'MPLUSI', __FILE__ );
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
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require MPLUSI_PLUGINS_DIR . 'includes/class-mplus-intercom-subscription-core.php';

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 * @return void
 */
function run_Mplus_Intercom_Subscription_Core() {

	$plugin = new Mplus_Intercom_Subscription_Core();
	$plugin->run();
	do_action( MPLUSI_NAME . '_loaded' );

}
run_Mplus_Intercom_Subscription_Core();
