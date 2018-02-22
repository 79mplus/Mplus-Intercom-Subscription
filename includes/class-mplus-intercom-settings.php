<?php
/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages Admin settings
 * @author 79mplus
 */
class Mplus_Intercom_Settings {

    /**
     * The menupage hold manu page ID
     *
     * @since    1.0.0
     * @access   protected
     * @var      Mplus_Intercom_Settings    $menupage    Hold Menu page Id.
     */
    
	protected $menupage;

	/**
	 * Constructs the class.
	 * 
	 * @return void
	 */
	function __construct() {

	}

	/**
	 * Creates the admin menu for API settings.
	 * 
	 * @return void
	 */
	function admin_menu() { 

		$this->menupage = add_menu_page( 'Mplus Intercom', 'Mplus Intercom', 'manage_options', 'mi-settings', array( $this, 'mplus_personal_token_settings' ), 'dashicons-admin-settings', 27 );
		add_action( "load-{$this->menupage}", array( $this, 'mplus_intercom_settings_help' ) );
	}

	/**
	 * Shows Intercom Personal Access Token Fields.
	 * 
	 * @return void
	 */
	public function mplus_personal_token_settings() {

		?>
		   <div class="wrap">
			<!-- <h1>JobAdder Settings</h1> -->
				<form method="post" action="options.php">
				
					<?php settings_fields( "mplusi-section" ); ?>

					<?php do_settings_sections( "mplusi-options" ); ?>

					<?php submit_button(); ?>
		  
				</form>
			</div>
		<?php
	}

	/**
	 * Creates Intercom API settings fields.
	 * 
	 * @return void
	 */
	public function mplus_intercom_settings_fields() {
		add_settings_section( 'mplusi-section', 'Mplus Intercom General Settings', null, 'mplusi-options' );
		add_settings_field( "mplus_ic_api_key", "Access Token", array( $this, 'mplus_display_ic_api_key' ), "mplusi-options", "mplusi-section" );
        add_settings_field( "mplus_ic_sub_type", "Subscription Type", array( $this, 'mplus_display_ic_sub_type' ), "mplusi-options", "mplusi-section" );
		
		register_setting( "mplusi-section", "mplus_ic_api_key" );
        register_setting( "mplusi-section", "mplus_ic_sub_type" );
	}

	/**
	 * Shows Intercom API Access Token fields.
	 * 
	 * @return void
	 */
	public function mplus_display_ic_api_key() {
		echo '<textarea name="mplus_ic_api_key" id="mplus_ic_api_key" class="regular-text mpss-settings-apikey" style="height:70px">'.get_option( 'mplus_ic_api_key' ).'</textarea>';
        echo '<p class="description">Input Intercom API Access Token.</p>';
		echo '<p class="description">To create your Access Token, go to <a href="https://app.intercom.com/developers/_" target="_blank">https://app.intercom.com/developers/_</a> and then click &quot;Get an Access Token&quot;. <a href="https://developers.intercom.com/docs/personal-access-tokens#section-creating-your-access-token" target="_blank">more info</a></p>';
	}

    /**
     * Shows Intercom API Access Token fields.
     * 
     * @return void
     */
    public function mplus_display_ic_sub_type() {
        echo '<select name="mplus_ic_sub_type">';
            echo '<option value="user" ' . selected(get_option('mplus_ic_sub_type'), "user") .'>User</option>';
            echo '<option value="lead" ' . selected(get_option('mplus_ic_sub_type'), "lead") .'>Lead</option>';
        echo '</select>';
        echo '<p class="description">Select Intercom Subscription Type.</p>';
    }
    
    /**
     * Display help page
     *
     * @since 1.0
     */
    function mplus_intercom_settings_help() {

        $screen = get_current_screen();

        if ( $screen->id != $this->menupage )
            return;

        $screen->add_help_tab( array(
            'id'      => 'mplus_intercom_settings_overview',
            'title'   => __( 'Overview', MPLUSILANGUAGE ),
            'content' => __( sprintf( "<h3>Mplus Intercom Subscription Plugin</h3><p>Modern messaging for sales, marketing and support – all on the first platform made with customers in mind.
                Please <a target='_blank' href='%s'>click here</a> to get more information.</p>",
                esc_url( 'http://www.79mplus.com/' ) ) , MPLUSILANGUAGE ),
        ));

        $screen->add_help_tab( array(
            'id'      => 'mplus_intercom_settings_info',
            'title'   => __( 'Settings', MPLUSILANGUAGE ),
            'content' => __( self::mplus_intercom_settings_connect(), MPLUSILANGUAGE ),
        ));

        /* Set Help Sidebar */
        $screen->set_help_sidebar(
            '<p><strong>' . __( 'For more information:', MPLUSILANGUAGE ) . '</strong></p>' .
            '<p><a href="#" target="_blank">'     . __( 'FAQ',     MPLUSILANGUAGE ) . '</a></p>' .
            '<p><a href="#" target="_blank">' . __( 'Support Forum', MPLUSILANGUAGE ) . '</a></p>'
        );
    }
    /**
     * Help page content
     *
     * @since 1.0
     */
    public static function mplus_intercom_settings_connect() {
        return sprintf( "
        <p><strong>Where is Intercom Access Token?</strong></p>
        <ol>
            <li>Please visit <a target='_blank' href='https://developers.intercom.com/docs/personal-access-tokens'>Intercom Application</a> to get more about Intercom Access Token.</li>
        </ol>

        <p><strong>I am new. How do I get access token?</strong> Please follow the instruction below to create a Intercom Access Token:</p>
        <ol>
            <li>To create your Access Token, go to the dashboard in the Intercom Developer Hub by <a target='_blank' href='https://app.intercom.com/developers/_'>clicking here</a> or by clicking on Dashboard at the top of the page and click <strong>'Get an Access Token'</strong></li>
            <li>When you setup your Token, you will be asked to choose between two levels of scopes. Select Your Scopes.</li>
            <li>Once you have created your Access Token you will see it in the same section in your Dashboard. You can edit or delete the token from here..</li>
        </ol>
        ", home_url('/') );
    }
    /**
     * Display admin notice - Facebook application settings
     *
     * @since 1.0
     */
    function mplus_admin_notices() {

        /* Get the options */
        $access_token = get_option('mplus_ic_api_key');

        $page = (isset($_GET['page']) ? $_GET['page'] : null);

        //echo "$page";

        if ( empty( $access_token ) && $page != 'mi-settings' && current_user_can( 'manage_options' ) ) :
	        echo '<div class="error fade">';
	            echo sprintf( __('<p><strong>Mplus Intercom Plugin is almost ready.</strong> Please %sAdd Access Token%s to use the plugin.</p>', MPLUSILANGUAGE ), '<a href="admin.php?page=mi-settings">', '</a>' );
	        echo '</div>';
        endif;
    }
}
