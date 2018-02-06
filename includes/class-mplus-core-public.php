<?php
/**
 * The public-facing functionality of the plugin.
 *
 *
 * @package    Mplus_Intercom_Core
 * @subpackage Mplus_Intercom_Core/public
 * @author     79mplus
 */

class Mplus_Intercom_Core_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function mplus_enqueue_styles()
    {

        wp_enqueue_style($this->plugin_name, MPLUSI_PLUGINS_DIR_URI . 'assets/css/mplus-core-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function mplus_enqueue_scripts()
    {

        wp_enqueue_script($this->plugin_name, MPLUSI_PLUGINS_DIR_URI . 'assets/js/mplus-core-public.js', array('jquery'), $this->version, false);

        wp_localize_script( $this->plugin_name, 'wp', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ) ,
        ));
    }

}
