<?php
/**
 * Plugin Name:       Mplus Intercom Subscription
 * Plugin URI:        https://www.79mplus.com/intercom-subscription/
 * Description:       The easiest and most extendable WordPress plugin for Intercom. This lets you offer a subscription form for Intercom and offers a wide range of extensions to grow your user base with the power of Intercom.
 * Version:           1.0.18
 * Author:            79mplus
 * Author URI:        https://www.79mplus.com/
 * License:           GNU General Public License v2 or later
 * Text Domain:       mplus-intercom-subscription
 * Domain Path:       /languages
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

/**
 * Stores plugin file path.
 */
define( 'MPLUSIS', __FILE__ );
/**
 * Plugin name in slug.
 */
define( 'MPLUSIS_NAME', 'mplus-intercom-subscription' );
/**
 * Plugin version.
 */
define( 'MPLUSISVERSION', '1.0.18' );
/**
 * Plugin directory.
 */
define( 'MPLUSIS_PLUGINS_DIR', trailingslashit( plugin_dir_path( MPLUSIS ) ) );
/**
 * Plugin directory url.
 */
define( 'MPLUSIS_PLUGINS_DIR_URI', trailingslashit( plugin_dir_url( MPLUSIS ) ) );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require MPLUSIS_PLUGINS_DIR . 'includes/class-mplus-intercom-subscription-core.php';

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 * @return void
 */
function run_mplus_intercom_subscription_core() {

	$mplusis_plugin = new Mplus_Intercom_Subscription_Core();
	$mplusis_plugin->run();
	do_action( MPLUSIS_NAME . '_loaded' );

}
run_mplus_intercom_subscription_core();
