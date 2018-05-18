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

			?>
				<div class="wrap">
					<form method="post" action="options.php">

						<?php settings_fields( "mplusis-section" ); ?>

						<?php do_settings_sections( "mplusis-options" ); ?>

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

			add_settings_section( 'mplusis-section', __( 'Intercom Subscription General Settings', 'mplus-intercom-subscription' ), null, 'mplusis-options' );
			add_settings_field( 'mplusis_api_key', __( 'Access Token', 'mplus-intercom-subscription' ), array( $this, 'mplusis_display_api_key' ), 'mplusis-options', 'mplusis-section' );
			add_settings_field( 'mplusis_subscription_type', __( 'Subscription Type', 'mplus-intercom-subscription' ), array( $this, 'mplusis_display_subscription_type' ), 'mplusis-options', 'mplusis-section' );
			add_settings_field( 'mplusis_subscribe_to_intercom', 'Enable Consent Checkbox', array($this, 'mplusis_display_subscribe_to_intercom'), 'mplusis-options', 'mplusis-section' );

			register_setting( 'mplusis-section', 'mplusis_api_key' );
			register_setting( 'mplusis-section', 'mplusis_subscription_type' );
			register_setting( 'mplusis-section', 'mplusis_subscribe_to_intercom' );

		}

		/**
		 * Shows Intercom API Access Token fields.
		 *
		 * @return void
		 */
		public function mplusis_display_api_key() {

			echo '<textarea name="mplusis_api_key" id="mplusis_api_key" class="regular-text mpss-settings-apikey" style="height:70px">' . get_option( 'mplusis_api_key' ) . '</textarea>';
			echo sprintf( '<p class="description">%s</p>', __( 'Please enter Intercom API Access Token.', 'mplus-intercom-subscription' ) );
			echo sprintf(
					'<p class="description">%s</p>',
					sprintf(
						__( 'To create your Access Token, go to %1$s and then click &quot;Get an Access Token&quot;. %2$s', 'mplus-intercom-subscription' ),
						'<a href="https://app.intercom.com/developers/_" target="_blank">https://app.intercom.com/developers/_</a>',
						sprintf( '<a href="https://developers.intercom.com/docs/personal-access-tokens#section-creating-your-access-token" target="_blank">%s</a>', __( 'more info', 'mplus-intercom-subscription' ) )
					)
				);

		}

		/**
		 * Shows Intercom API Access Token fields.
		 *
		 * @return void
		 */
		public function mplusis_display_subscription_type() {

			echo '<select name="mplusis_subscription_type">';
				echo '<option value="user" ' . selected( get_option( 'mplusis_subscription_type' ), "user" ) .'>' . __( 'User', 'mplus-intercom-subscription' ) . '</option>';
				echo '<option value="lead" ' . selected( get_option( 'mplusis_subscription_type' ), "lead" ) .'>' . __( 'Lead', 'mplus-intercom-subscription' ) . '</option>';
			echo '</select>';
			echo sprintf( '<p class="description">%s</p>', __( 'Please select Intercom Subscription Type.', 'mplus-intercom-subscription' ) );

		}

		/**
		 * Shows Consent Checkbox for Subscription to Intercom fields.
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
				'content' => sprintf( __( "<h3>Intercom Subscription Plugin</h3><p>The easiest and most extendable WordPress plugin for Intercom. This lets you offer a subscription form for Intercom and offers a wide range of extensions to grow your user base with the power of Intercom.<br/>Please <a target='_blank' href='%s'>click here</a> to get more information.</p>", 'mplus-intercom-subscription' ),
					esc_url( 'https://www.79mplus.com/' ) ),
			));

			$screen->add_help_tab( array(
				'id'      => 'mplusis_settings_info',
				'title'   => __( 'Settings', 'mplus-intercom-subscription' ),
				'content' => self::mplusis_settings_connect(),
			));

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

			return sprintf( __( '
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
			', 'mplus-intercom-subscription' ), 'https://developers.intercom.com/docs/personal-access-tokens', 'https://app.intercom.com/developers/_', 'https://app.intercom.com/developers/_' );

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
					echo sprintf( __( '<p><strong>Intercom Subscription Plugin is almost ready.</strong> Please %1$sAdd Access Token%2$s to use the plugin.</p>', 'mplus-intercom-subscription' ), '<a href="admin.php?page=mplusis-settings">', '</a>' );
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
				$active_addon = isset( $_GET['addon'] ) ? $_GET['addon'] : key(  $addons  );
				?>
				<h2 class="nav-tab-wrapper">
				<?php
				foreach ( $addons as $addon => $label ) {
					?>
					<a href="?page=<?php echo $page;?>&addon=<?php echo $addon;?>" class="nav-tab <?php echo $active_addon == $addon ? 'nav-tab-active' : ''; ?>"><?php echo $label; ?></a>
					<?php
				}
				?>
				</h2>
				<?php
				do_action( 'mplus_intercom_subscription_addon_licence_activation_form', $active_addon );
				?>
				<?php
			} else {
				echo '<h2>' . __( 'No Premium Addon Found', 'mplus-intercom-subscription') . '</h2>';
			}
		}
	}
}
