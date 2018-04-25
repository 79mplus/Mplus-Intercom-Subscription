<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package Mplus_Intercom_Core
 * @subpackage Mplus_Intercom_Core/admin
 * @author 79mplus
 */
class Mplus_Intercom_Subscription_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initializes the class and sets its properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 * @return void
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Registers the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function mplus_enqueue_styles() {

	}

	/**
	 * Registers the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function mplus_enqueue_scripts() {

	}

	/**
	 * Prepares plugin row meta.
	 *
	 * @since 1.0
	 *
	 * @param array $links Links sent to function.
	 * @param string $file Filename sent to function.
	 * @return array
	 */
	public function mplus_plugin_row_meta( $links, $file ) {

		if ( strpos( $file, $this->plugin_name . '.php' ) !== false  ) :
			$links[] = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'http://docs.79mplus.com/intercom-subscription-base-plugin/' ), __( 'Docs', 'mplus-intercom-core' )  );
		endif;

		return $links;
	}

	/**
	 * Prepares plugin action links.
	 *
	 * @since 1.0
	 *
	 * @param array $actions Actions sent to function.
	 * @param string $plugin_file Plugin filename sent to function.
	 * @return array
	 */
	public function mplus_add_action_links( $actions, $plugin_file ) {

		if ( strpos( $plugin_file, $this->plugin_name . '.php' ) !== false  ) :

			$actions['settings'] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( "admin.php?page=mi-settings" ) ), __( 'Settings', 'mplus-intercom-core' )  );
		endif;

		return $actions;
	}
}
