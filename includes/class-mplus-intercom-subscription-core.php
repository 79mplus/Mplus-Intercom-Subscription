<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link https://www.79mplus.com/
 * @since 1.0.0
 *
 * @package Mplus_Intercom_Core
 * @subpackage Mplus_Intercom_Core/includes
 */
class Mplus_Intercom_Subscription_Core {

	/**
	 * The loader that's responsible for maintaining and registering all hooks
	 * that power the plugin.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var \Mplus_Intercom_Core_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Defines the core functionality of the plugin.
	 *
	 * Sets the plugin name and the plugin version that can be used throughout
	 * the plugin.
	 * Loads the dependencies, defines the locale, and sets the hooks for the
	 * admin area and the public-facing side of the site.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {

		$this->plugin_name = MPLUSIS_NAME;
		$this->version = MPLUSISVERSION;

		$this->mplus_load_dependencies();
		$this->mplus_set_locale();
		$this->mplus_admin_hooks_define();
		$this->mplus_public_hooks_define();
	}


	/**
	 * Loads the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mplus_Intercom_Core_Loader. Orchestrates the hooks of the plugin.
	 * - Mplus_Intercom_Core_i18n. Defines internationalization functionality.
	 * - Mplus_Intercom_Core_Admin. Defines all hooks for the admin area.
	 * - Mplus_Intercom_Core_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function mplus_load_dependencies() {

		/**
		 * Autoload.
		 */
		require_once MPLUSIS_PLUGINS_DIR . 'vendor/autoload.php';

		/**
		 * Helper functions.
		 */
		require_once MPLUSIS_PLUGINS_DIR . 'includes/helper-function.php';

		/**
		 * Intercom Settings.
		 */
		require_once MPLUSIS_PLUGINS_DIR . 'includes/class-mplus-intercom-subscription-settings.php';

		/**
		 * The is class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once MPLUSIS_PLUGINS_DIR . 'includes/class-mplus-intercom-subscription-core-loader.php';

		/**
		 * The is class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once MPLUSIS_PLUGINS_DIR . 'includes/class-mplus-intercom-subscription-i18n.php';

		/**
		 * The is class responsible for defining all actions that occur in the admin area.
		 */
		require_once MPLUSIS_PLUGINS_DIR . 'includes/class-mplus-intercom-subscription-admin.php';

		/**
		 * The is class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once MPLUSIS_PLUGINS_DIR . 'includes/class-mplus-intercom-subscription-public.php';

		/**
		 * The is class responsible for defining shortcode functionality
		 * of the plugin.
		 */
		require_once MPLUSIS_PLUGINS_DIR . 'includes/class-mplus-intercom-subscription-shortcode.php';

		$this->loader = new Mplus_Intercom_Subscription_Core_Loader();
	}

	/**
	 * Defines the locale for this plugin for internationalization.
	 *
	 * Uses the Mplus_Intercom_Core_i18n class in order to set the domain and
	 * to register the hook with WordPress.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function mplus_set_locale() {

		$plugin_i18n = new Mplus_Intercom_Subscription_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'mplus_load_plugin_textdomain' );
	}

	/**
	 * Registers all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function mplus_admin_hooks_define() {

		$plugin_admin = new Mplus_Intercom_Subscription_Admin( $this->get_plugin_name(), $this->get_version() );

		$mplus_intercom_settings = new Mplus_Intercom_Subscription_Settings();
		$this->loader->add_action( 'admin_menu', $mplus_intercom_settings, 'admin_menu', 999 );
		$this->loader->add_action( 'admin_init', $mplus_intercom_settings, 'mplus_intercom_settings_fields' );
		$this->loader->add_action( 'admin_notices', $mplus_intercom_settings, 'mplus_admin_notices' );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin ,'mplus_plugin_row_meta', 10, 2 );
		$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'mplus_add_action_links', 10, 5 );

		$mplus_intercom_shortcode = new Mplus_Intercom_Subscription_Shortcode();
		$this->loader->add_shortcode( 'mplus_intercom_subscription', $mplus_intercom_shortcode, 'mplus_intercom_subscription' );

		$subscription_form = new Mplus_Intercom_Subscription_Form();
		$this->loader->add_action( 'wp_ajax_intercom_form_submit', $subscription_form, 'submit_handler' );

	}

	/**
	 * Registers all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function mplus_public_hooks_define(){

		$plugin_public = new Mplus_Intercom_Subscription_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'mplus_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'mplus_enqueue_scripts' );

		$subscription_form = new Mplus_intercom_Subscription_Form();
		$this->loader->add_action( 'wp_ajax_nopriv_intercom_form_submit', $subscription_form, 'submit_handler' );
	}


	/**
	 * Runs the loader to execute all of the hooks with WordPress.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run() {

		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context
	 * of WordPress and to define internationalization functionality.
	 *
	 * @since 1.0.0
	 *
	 * @return string The name of the plugin.
	 */
	public function get_plugin_name() {

		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return \Mplus_Intercom_Core_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {

		return $this->loader;
	}

	/**
	 * Retrieves the version number of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return string The version number of the plugin.
	 */
	public function get_version() {

		return $this->version;
	}
}
