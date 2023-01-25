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
 * @package Mplus_Intercom_Subscription
 * @subpackage Mplus_Intercom_Subscription/includes
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'Mplus_Intercom_Subscription_Core' ) ) {
	class Mplus_Intercom_Subscription_Core {

		/**
		 * The loader that's responsible for maintaining and registering all hooks
		 * that power the plugin.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @var \Mplus_Intercom_Subscription_Loader $loader Maintains and registers all hooks for the plugin.
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
		 * The api client for intercom.
		 *
		 * @since 1.0.18
		 * @access static
		 * @var string $_client The api client for intercom.
		 */
		private static $_client = null;

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

			spl_autoload_register( array( $this, 'autoload' ) );

			$this->mplus_load_dependencies();
			$this->mplus_set_locale();
			$this->mplus_admin_hooks_define();
			if( get_option( 'mplusis_api_key' ) ){
				$this->mplus_public_hooks_define();
			}

			new Mplus_Intercom_Subscription_OAuth();

		}


		/**
		 * Loads the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - Mplus_Intercom_Subscription_Loader. Orchestrates the hooks of the plugin.
		 * - Mplus_Intercom_Subscription_i18n. Defines internationalization functionality.
		 * - Mplus_Intercom_Subscription_Admin. Defines all hooks for the admin area.
		 * - Mplus_Intercom_Subscription_Public. Defines all hooks for the public side of the site.
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

			/**
			 * The class is responsible for receiving and saving access token
			 * 
			 */
			require_once MPLUSIS_PLUGINS_DIR . 'includes/class-mplus-intercom-subscription-oauth.php';

			$this->loader = new Mplus_Intercom_Subscription_Core_Loader();

		}

		/**
		 * Defines the locale for this plugin for internationalization.
		 *
		 * Uses the Mplus_Intercom_Subscription_i18n class in order to set the domain and
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

			$mplusis_settings = new Mplus_Intercom_Subscription_Settings();
			$this->loader->add_action( 'admin_menu', $mplusis_settings, 'mplusis_admin_menu');
			$this->loader->add_action( 'admin_init', $mplusis_settings, 'mplusis_settings_fields' );
			$this->loader->add_action( 'admin_notices', $mplusis_settings, 'mplusis_admin_notices' );
			$this->loader->add_filter( 'plugin_row_meta', $plugin_admin ,'mplus_plugin_row_meta', 10, 2 );
			$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'mplus_add_action_links', 10, 5 );

			$mplusis_shortcode = new Mplus_Intercom_Subscription_Shortcode();
			$this->loader->add_shortcode( 'mplus_intercom_subscription', $mplusis_shortcode, 'mplus_intercom_subscription' );
			$this->loader->add_shortcode( 'mplus_intercom_subscription_company', $mplusis_shortcode, 'mplus_intercom_subscription_company' );

			$access_token = new Mplus_Intercom_Subscription_OAuth();
			$this->loader->add_action( 'rest_api_init', $access_token, 'rest_route' );

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
		private function mplus_public_hooks_define() {

			$mplusis_subscription_form = new Mplus_intercom_Subscription_Form();
			$this->loader->add_action( 'wp_ajax_mplus_intercom_subscription_form_submit', $mplusis_subscription_form, 'submit_handler' );
			$this->loader->add_action( 'wp_ajax_nopriv_mplus_intercom_subscription_form_submit', $mplusis_subscription_form, 'submit_handler' );

			$plugin_public = new Mplus_Intercom_Subscription_Public( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'mplus_enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'mplus_enqueue_scripts' );
			$this->loader->add_action( 'wp_ajax_mplus_intercom_subscription_company_form_submit', $plugin_public, 'company_submit_handler' );
			$this->loader->add_action( 'wp_ajax_nopriv_mplus_intercom_subscription_company_form_submit', $plugin_public, 'company_submit_handler' );
			$this->loader->add_action( 'mplus_intercom_subscription_user_created_after', $plugin_public, 'user_assign_to_company_handler', 10, 3 );
			$this->loader->add_action( 'wp_footer', $plugin_public, 'chat_bubble' );
		}

		/**
		 * Autoloads class files on demand.
		 *
		 * @since 1.0.0
		 *
		 * @param string $class Requested class name.
		 * @return void
		 */
		public function autoload( $class ) {

			if ( stripos( $class, 'Mplus_Intercom_' ) !== false ) :
				$class_name = str_replace( '_', '-', $class );
				$file_path = MPLUSIS_PLUGINS_DIR . 'classes/' . strtolower( $class_name ) . '.php';
				if ( file_exists( $file_path ) ) :
					require_once $file_path;
				endif;
			endif;

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
		 * @return \Mplus_Intercom_Subscription_Loader Orchestrates the hooks of the plugin.
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

		/**
		 * Create the api client if not created before and return.
		 *
		 * @since 1.0.18
		 *
		 * @param void.
		 * @return obj
		 */
		public static function get_client(){

			if ( is_null( self::$_client ) && class_exists( 'Intercom\IntercomClient' ) ) {
				try {
					// Access token
					$access_token = get_option( 'mplusis_api_key' );
					self::$_client = new Intercom\IntercomClient( $access_token, null );
				} catch (Exception $e) {
				}
			}

			return self::$_client;
		}
	}
}
