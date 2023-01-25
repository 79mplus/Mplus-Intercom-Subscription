<?php

/**
 * Manages Admin settings
 *
 * @package Mplus_Intercom_Subscription
 * @subpackage Mplus_Intercom_Subscription/includes
 * @author 79mplus
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'Mplus_Intercom_Subscription_Settings' ) ) {
	class Mplus_Intercom_Subscription_Settings {

		/**
		 * To hold manu page ID.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @var string $menupage Hold Menu page Id.
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
		function mplusis_admin_menu() {

			$this->menupage = add_menu_page( 'Intercom Subscription', 'Intercom Subscription', 'manage_options', 'mplusis-settings', array( $this, 'mplusis_personal_token_settings' ), plugins_url( MPLUSIS_NAME . '/assets/images/admin-icon.png' ), 27 );
			$this->license_menu = add_submenu_page( 'mplusis-settings', 'License Activation', 'License Activation', 'manage_options', 'mplusis-license-activation', array( $this, 'mplusis_licence_activation_submenu' ) );
			add_action( "load-{$this->menupage}", array( $this, 'mplusis_settings_help' ) );

		}

		/**
		 * Shows Intercom Personal Access Token Fields.
		 *
		 * @return void
		 */
		public function mplusis_personal_token_settings() {

			if(isset($_GET['disconnect'])){
				delete_option('mplusis_api_key');
			}
			?>
				<div class="wrap">
					<form method="post" action="options.php">

						<?php settings_fields( 'mplusis-section' ); ?>

						<?php do_settings_sections( 'mplusis-options' ); ?>

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
		public function mplusis_settings_fields() {

			add_settings_section( 'mplusis-section', __( 'Intercom Subscription General Settings', 'mplus-intercom-subscription' ), [ $this, 'intercom_connect_section' ], 'mplusis-options' );
			//add_settings_field( 'mplusis_api_key', __( 'Access Token', 'mplus-intercom-subscription' ), array( $this, 'mplusis_display_api_key' ), 'mplusis-options', 'mplusis-section' );
			add_settings_field( 'mplusis_subscription_type', __( 'Subscription Type', 'mplus-intercom-subscription' ), array( $this, 'mplusis_display_subscription_type' ), 'mplusis-options', 'mplusis-section' );
			add_settings_field( 'mplusis_subscribe_to_intercom', 'Enable Consent Checkbox', array( $this, 'mplusis_display_subscribe_to_intercom' ), 'mplusis-options', 'mplusis-section' );
			add_settings_field( 'mplusis_subscribe_company_field', 'Enable Company Field', array( $this, 'mplusis_display_company_field' ), 'mplusis-options', 'mplusis-section' );
			add_settings_field( 'mplusis_subscribe_company_register_page', 'Company Registration Page', array( $this, 'mplusis_display_company_register_page' ), 'mplusis-options', 'mplusis-section' );
			add_settings_field( 'mplusis_subscription_spam_protection', 'Enable Spam Protection', array( $this, 'mplusis_display_spam_protection' ), 'mplusis-options', 'mplusis-section' );
			add_settings_field( 'mplusis_enable_chat', 'Enable Live Chat', array( $this, 'mplusis_display_chat_option' ), 'mplusis-options', 'mplusis-section' );

			//register_setting( 'mplusis-section', 'mplusis_api_key' );
			register_setting( 'mplusis-section', 'mplusis_subscription_type' );
			register_setting( 'mplusis-section', 'mplusis_subscribe_to_intercom' );
			register_setting( 'mplusis-section', 'mplusis_subscribe_company_field' );
			register_setting( 'mplusis-section', 'mplusis_subscribe_company_register_page' );
			register_setting( 'mplusis-section', 'mplusis_subscription_spam_protection' );
			register_setting( 'mplusis-section', 'mplusis_enable_chat' );

		}

		/**
		 * Intercom connect section.
		 *
		 * @return void
		 */
		public function intercom_connect_section() {

			$access_token = get_option( 'mplusis_api_key' );

			if( $access_token ){
				$disconnect_url = site_url('wp-admin/admin.php?page=mplusis-settings&disconnect=1');
				echo __( 'You are connected with Intercom.', 'mplus-intercom-subscription') .
				 ' ' .
				 sprintf( "<a href='%s'>" . __('Disconnect', 'mplus-intercom-subscription') . "</a>", $disconnect_url );
			}else{
				$connect_url = Mplus_Intercom_Subscription_OAuth::connect_url();
				printf("<a href='%s' class='intercom-connect'><img src='%s'></a>", $connect_url, MPLUSIS_PLUGINS_DIR_URI. 'assets/images/intercom-connect.png');
			}
			echo "<style>a.intercom-connect:active, a.intercom-connect:focus {
					outline: 0;
					border: none;
					box-shadow: none;
					-moz-outline-style: none;
				}</style>";

		}

		/**
		 * Shows Intercom Subscriptons Type Field.
		 *
		 * @return void
		 */
		public function mplusis_display_subscription_type() {

			echo '<select name="mplusis_subscription_type">';
				echo '<option value="user" ' . selected( get_option( 'mplusis_subscription_type' ), "user" ) . '>' . __( 'User', 'mplus-intercom-subscription' ) . '</option>';
				echo '<option value="lead" ' . selected( get_option( 'mplusis_subscription_type' ), "lead" ) .'>' . __( 'Lead', 'mplus-intercom-subscription' ) . '</option>';
			echo '</select>';
			echo sprintf( '<p class="description">%s</p>', __( 'Please select Intercom Subscription Type.', 'mplus-intercom-subscription' ) );

		}

		/**
		 * Shows Consent Checkbox for Subscription to Intercom field.
		 *
		 * @return void
		 */
		public function mplusis_display_chat_option() {

			$enable_chat = get_option( 'mplusis_enable_chat' );

			$html = '<input type="checkbox" id="mplusis_enable_chat" name="mplusis_enable_chat" value="1"' . checked( 1, $enable_chat, false ) . '/>';
			$html .= '<label for="mplusis_enable_chat">' . __( 'Show the chat bubble at the bottom.', 'mplus-intercom-subscription' ) . '</label>';

			echo $html;

		}

		/**
		 * Shows Company Select Field.
		 *
		 * @return void
		 */
		public function mplusis_display_company_field() {

			$intercom_company_field = get_option( 'mplusis_subscribe_company_field' );

			$html = '<input type="checkbox" id="mplusis_subscribe_company_field" name="mplusis_subscribe_company_field" value="1"' . checked( 1, $intercom_company_field, false ) . '/>';
			$html .= '<label for="mplusis_subscribe_company_field">' . __( 'Check to show company select field on the form', 'mplus-intercom-subscription' ) . '</label>';

			echo $html;

		}

		/**
		 * Shows Intercom Subscriptons Type Field.
		 *
		 * @return void
		 */
		public function mplusis_display_company_register_page() {

			$html = '<select name="mplusis_subscribe_company_register_page">';
				$html .= self::mplusis_get_all_page_select_options();
			$html .= '</select>';
			$html .= sprintf( '<p class="description">%s</p>', __( 'Please select Intercom Company Registration Page.', 'mplus-intercom-subscription' ) );

			echo $html;

		}

		/**
		 * Shows Company Select Field.
		 *
		 * @return void
		 */
		public function mplusis_display_spam_protection() {

			$intercom_spam_protection = get_option( 'mplusis_subscription_spam_protection' );

			$html = '<input type="checkbox" id="mplusis_subscription_spam_protection" name="mplusis_subscription_spam_protection" value="1"' . checked( 1, $intercom_spam_protection, false ) . '/>';
			$html .= '<label for="mplusis_subscription_spam_protection">' . __( 'Check to enable honeypot spam protection for forms.', 'mplus-intercom-subscription' ) . '</label>';

			echo $html;

		}

		/**
		 * Displays Help page.
		 *
		 * @since 1.0
		 * @return null|void
		 */
		function mplusis_settings_help() {

			$screen = get_current_screen();

			if ( $screen->id != $this->menupage )
				return;

			$screen->add_help_tab( array(
				'id'      => 'mplusis_settings_overview',
				'title'   => __( 'Overview', 'mplus-intercom-subscription' ),
				'content' => sprintf( 
					/* translators: %s: link location */
					__( "<h3>Intercom Subscription Plugin</h3><p>The easiest and most extendable WordPress plugin for Intercom. This lets you offer a subscription form for Intercom and offers a wide range of extensions to grow your user base with the power of Intercom.<br/>Please <a target='_blank' href='%s'>click here</a> to get more information.</p>", 'mplus-intercom-subscription' ),
					esc_url( 'https://www.79mplus.com/' ) ),
			));

			$screen->add_help_tab( array(
				'id'      => 'mplusis_settings_info',
				'title'   => __( 'Settings', 'mplus-intercom-subscription' ),
				'content' => self::mplusis_settings_connect(),
			) );

			/* Set Help Sidebar */
			$screen->set_help_sidebar(
				'<p><strong>' . __( 'For more information:', 'mplus-intercom-subscription' ) . '</strong></p>' .
				'<p><a href="https://wordpress.org/plugins/mplus-intercom-subscription/#faq" target="_blank">' . __( 'FAQ', 'mplus-intercom-subscription' ) . '</a></p>' .
				'<p><a href="https://wordpress.org/support/plugin/mplus-intercom-subscription" target="_blank">' . __( 'Support Forum', 'mplus-intercom-subscription' ) . '</a></p>'
			);

		}

		/**
		 * Returns Help page content.
		 *
		 * @since 1.0
		 *
		 * @return string
		 */
		public static function mplusis_settings_connect() {

			return sprintf( 
				/* translators: 1: link location 2: link location */
				__( '
			<p><strong>Where is Intercom Access Token?</strong></p>
			<ol>
				<li>Please visit <a target="_blank" href="%1$s">Intercom Application</a> to get more about Intercom Access Token.</li>
			</ol>

			<p><strong>I am new. How do I get access token?</strong> Please follow the instruction below to create a Intercom Access Token:</p>
			<ol>
				<li>To create your Access Token, go to the dashboard in the Intercom Developer Hub by <a target="_blank" href="%2$s">clicking here</a> or by clicking on Dashboard at the top of the page and click <strong>"Get an Access Token"</strong></li>
				<li>When you setup your Token, you will be asked to choose between two levels of scopes. Select Your Scopes.</li>
				<li>Once you have created your Access Token you will see it in the same section in your Dashboard. You can edit or delete the token from <a target="_blank" href="%3$s">here</a>.</li>
			</ol>
			', 'mplus-intercom-subscription' ), 'https://developers.intercom.com/docs/personal-access-tokens', 'https://app.intercom.com/a/developer-signup', 'https://app.intercom.com/a/developer-signup' );

		}

		/**
		 * Shows Consent Checkbox to enable chat.
		 *
		 * @return void
		 */
		public function mplusis_display_subscribe_to_intercom() {

			$sub_to_intercom = get_option( 'mplusis_subscribe_to_intercom' );

			$html = '<input type="checkbox" id="mplusis_subscribe_to_intercom" name="mplusis_subscribe_to_intercom" value="1"' . checked( 1, $sub_to_intercom, false ) . '/>';
			$html .= '<label for="mplusis_subscribe_to_intercom">' . __( 'Check to show a consent checkbox on the form', 'mplus-intercom-subscription' ) . '</label>';

			echo $html;

		}

		/**
		 * Displays admin notice.
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		function mplusis_admin_notices() {

			/* Get the options */
			$access_token = get_option( 'mplusis_api_key' );

			$page = ( isset( $_GET['page'] ) ? $_GET['page'] : null );

			if ( empty( $access_token ) && $page != 'mplusis-settings' && current_user_can( 'manage_options' ) ) :
				echo '<div class="error fade">';
					echo '<p>' . sprintf( 
						/* translators: 1: anchor tag start 2: anchor tag end */
						__( 'Intercom Subscription Plugin is almost ready. Please %1$sconnect to Intercom%2$s to use the plugin.', 'mplus-intercom-subscription' ),
						'<a href="admin.php?page=mplusis-settings">', '</a>' 
						) . '</p>';
				echo '</div>';
			endif;

			$phpversion = phpversion();

			if ( $phpversion < 7.1 ) :
				echo '<div class="error fade">';
					echo '<p>' . sprintf( 
						/* translators: 1: anchor tag start 2: anchor tag end */
						__( 'Intercom Subscription plugin uses %1$sofficial PHP bindings to the Intercom API%2$s. This library supports PHP 7.1 and later. Your web server has PHP version %3$s, which doesn\'t meet the requirement for this to work as expected.', 'mplus-intercom-subscription' ), '<a href="https://github.com/intercom/intercom-php" target="_blank">', '</a>', $phpversion ) . '</p>';
				echo '</div>';
			endif;

		}

		/**
		 * Renders License Activation page contents
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function mplusis_licence_activation_submenu() {

			$page = $_GET['page'];
			$addons = apply_filters( 'mplus_intercom_subscription_addon_license_tabs', array() );
			if ( ! empty( $addons ) ) {
				$active_addon = isset( $_GET['addon'] ) ? $_GET['addon'] : key( $addons );
				?>
				<h2 class="nav-tab-wrapper">
				<?php
				foreach ( $addons as $addon => $label ) {
					?>
					<a href="?page=<?php echo $page; ?>&addon=<?php echo $addon; ?>" class="nav-tab <?php echo $active_addon == $addon ? 'nav-tab-active' : ''; ?>"><?php echo $label; ?></a>
					<?php
				}
				?>
				</h2>
				<?php
				do_action( 'mplus_intercom_subscription_addon_licence_activation_form', $active_addon );
				?>
				<?php
			} else {
				echo '<h2>' . __( 'No Premium Addon Found', 'mplus-intercom-subscription' ) . '</h2>';
			}
		}

		/**
		 * Gets all page select options
		 *
		 * @since 1.0
		 *
		 * @return string
		 */
		static public function mplusis_get_all_page_select_options() {

			$pages = get_pages();
			$pages_options = '<option value="">' . __( 'Select Page', 'mplus-intercom-subscription' ) . '</option>';

			foreach ( $pages as $page ) :
				if ( get_page_link( $page->ID ) == get_option( 'mplusis_subscribe_company_register_page' ) ) :
					$selected = 'selected="selected"';
				else :
					$selected = '';
				endif;
				$pages_options .= '<option value="' . get_page_link( $page->ID ) . '" ' . $selected . '>' . __( ucwords( $page->post_title ), 'mplus-intercom-subscription' ) . '</option>';
			endforeach;

			return $pages_options;
		}
	}
}
