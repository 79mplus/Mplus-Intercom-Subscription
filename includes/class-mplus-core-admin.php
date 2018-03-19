<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Mplus_Intercom_Core
 * @subpackage Mplus_Intercom_Core/admin
 * @author     79mplus
 */
class Mplus_Intercom_Core_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of this plugin.
	 * @param    string $version The version of this plugin.
	 * @return   void
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	public function mplus_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, MPLUSI_PLUGINS_DIR_URI . 'assets/css/mplus-core-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	public function mplus_enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, MPLUSI_PLUGINS_DIR_URI . 'assets/js/mplus-core-admin.js', array( 'jquery' ), $this->version, false );
	}

    /**
     * Plugin row meta
     * @since 1.0
     */
    public function mplus_plugin_row_meta( $links, $file ) {

        if ( strpos( $file, $this->plugin_name . '.php' ) !== false  ) :
            $links[] = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'http://docs.79mplus.com/intercom-subscription-base-plugin/' ), __( 'Docs', 'mplus-intercom-core' )  );
            $links[] = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( '#' ), __( 'Premium support', 'mplus-intercom-core' )  );
        endif;

        return $links;
    }

    /**
     * Plugin action links
     *
     * @since 1.0
     */
    public function mplus_add_action_links( $actions, $plugin_file ) {

        if ( strpos( $plugin_file, $this->plugin_name . '.php' ) !== false  ) :

            $actions['settings'] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( "admin.php?page=mi-settings" ) ), __( 'Settings', 'mplus-intercom-core' )  );
        endif;

        return $actions;
    }
}
