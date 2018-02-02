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
 * @link       https://www.79mplus.com/
 * @since      1.0.0
 *
 * @package    Mplus_Intercom_Core
 * @subpackage Mplus_Intercom_Core/includes
 */
class Mplus_Intercom_Core
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Mplus_Intercom_Core_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.1.0
     */
    public function __construct()
    {

        $this->plugin_name = 'mplus-intercom-core';
        $this->version = '1.0.0';

        $this->mplus_load_dependencies();
        $this->mplus_set_locale();
        $this->mplus_admin_hooks_define();
        $this->mplus_public_hooks_define();

    }


    /**
     * Load the required dependencies for this plugin.
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
     * @since    1.0.0
     * @access   private
     */
    private function mplus_load_dependencies()
    {


        // Autoload
        require_once plugin_dir_path(dirname(__FILE__)) . '/vendor/autoload.php';

        // Intercom Settings
        require_once plugin_dir_path(dirname(__FILE__)) . '/includes/helper-function.php';

        // Intercom Settings
        require_once plugin_dir_path(dirname(__FILE__)) . '/includes/class-mplus-intercom-settings.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mplus-core-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mplus-core-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mplus-core-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mplus-core-public.php'; 

        $this->loader = new Mplus_Intercom_Core_Loader();
    } 

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Mplus_Intercom_Core_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function mplus_set_locale()
    {

        $plugin_i18n = new Mplus_Intercom_Core_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'mplus_load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function mplus_admin_hooks_define()
    {

        $plugin_admin = new Mplus_Intercom_Core_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'mplus_enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'mplus_enqueue_scripts');

        $mplus_intercom_settings = new Mplus_Intercom_Settings();

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */ 
    private function mplus_public_hooks_define(){

        $plugin_public = new Mplus_Intercom_Core_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'mplus_enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'mplus_enqueue_scripts');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Mplus_Intercom_Core_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}

