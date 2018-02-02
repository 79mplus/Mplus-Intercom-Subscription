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
	 * Constructs the class.
	 * 
	 * @return void
	 */
	function __construct() {

		add_action( 'admin_menu', array($this, 'admin_menu'), 999 );
		add_action( 'admin_init', array($this, 'mplus_intercom_settings_fields') );

	}

	/**
	 * Creates the admin menu for API settings.
	 * 
	 * @return void
	 */
	function admin_menu() {

		add_menu_page( 'Mplus Intercom', 'Mplus Intercom', 'manage_options', 'mi-settings', array( $this, 'mplus_personal_token_settings' ), 'dashicons-admin-settings', 27 );

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
		
		register_setting( "mplusi-section", "mplus_ic_api_key" );
	}

	/**
	 * Shows Intercom API Access Token fields.
	 * 
	 * @return void
	 */
	public function mplus_display_ic_api_key() {
		echo '<textarea name="mplus_ic_api_key" id="mplus_ic_api_key" class="regular-text mpss-settings-apikey" style="height:70px">'.get_option( 'mplus_ic_api_key' ).'</textarea>';
		echo '<p class="description">Input Intercom API Access Token.</p>';
	}
}
