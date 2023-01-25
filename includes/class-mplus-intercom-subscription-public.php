<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package Mplus_Intercom_Subscription
 * @subpackage Mplus_Intercom_Subscription/includes
 * @author 79mplus
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'Mplus_Intercom_Subscription_Public' ) ) {
	class Mplus_Intercom_Subscription_Public {

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
		 * Initializes the class and set its properties.
		 *
		 * @since 1.0.0
		 *
		 * @param string $plugin_name The name of the plugin.
		 * @param string $version The version of this plugin.
		 * @return void
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version = $version;

		}

		/**
		 * Registers the stylesheets for the public-facing side of the site.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function mplus_enqueue_styles() {

			wp_enqueue_style( $this->plugin_name, MPLUSIS_PLUGINS_DIR_URI . 'assets/css/mplus-intercom-subscription-public.css', array(), $this->version, 'all' );

		}

		/**
		 * Registers the stylesheets for the public-facing side of the site.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function mplus_enqueue_scripts() {

			wp_enqueue_script( $this->plugin_name, MPLUSIS_PLUGINS_DIR_URI . 'assets/js/mplus-intercom-subscription-public.js', array( 'jquery' ), $this->version, false );

			wp_localize_script( $this->plugin_name, 'wp', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ) ,
			) );

		}

		/**
		 * Handles submission of the Company Registration form.
		 *
		 * @return void
		 */
		public function company_submit_handler() {
			$submitted_fields = array();
			$response         = array();
			$intercom         = Mplus_Intercom_Subscription_Core::get_client();
			$honeypot         = false;
			$fields           = wp_unslash( $_POST['fields'] );

			foreach ( $fields as $field ) {
				if( $field['name'] == 'honeypot' ) {
					if( $field['value'] != '' ) {
						$honeypot = true;
					}
					continue;
				}

				$submitted_fields[ $field['name'] ] =  $field['value'];
			}

			$spam_protection = get_option( 'mplusis_subscription_spam_protection' );

			if( $spam_protection == 1 && $honeypot ) {
				$response['success'] = 0;
				$response['message'] = __( 'Something Wrong.', 'mplus-intercom-subscription' );
				wp_send_json( $response );
				die();
			}

			try {
				// Check in conpany exists or not. If exists update company informations.
				$company = $intercom->companies->getCompanies( [
					'name' => esc_attr( trim( $submitted_fields['name'] ) ),
				] );

				// Conpany fields new data.
				$company_fields = [
					'id'         => $company->id,
					'company_id' => $company->company_id,
					'plan'       => esc_attr( $submitted_fields['plan'] ),
					'created_at' => strtotime( $submitted_fields['created_at'] ),
					'size'       => esc_attr( $submitted_fields['size'] ),
					'website'    => esc_url( $submitted_fields['website'] ),
					'industry'   => esc_attr( $submitted_fields['industry'] ),
				];

				// Update Company information
				$company = $intercom->companies->update( $company_fields );

				$response['company_info'] = $company;
				$response['message']      = __( 'Company already exists. Company Information updated.', 'mplus-intercom-subscription' );
				$response['success']      = 0;
				wp_send_json( $response );
				die();
			} catch (Exception $e) {
				// Conpany fields data.
				$company_fields = [
					'name'			=> esc_attr( trim( $submitted_fields['name'] ) ),
					'company_id' 	=> mt_rand( 10,999999 ),
					'plan'			=> esc_attr( $submitted_fields['plan'] ),
					'created_at'	=> strtotime( $submitted_fields['created_at'] ),
					'size'			=> esc_attr( $submitted_fields['size'] ),
					'website'		=> esc_url( $submitted_fields['website'] ),
					'industry'		=> esc_attr( $submitted_fields['industry'] ),
				];

				try {
					// Create A New company.
					$company = $intercom->companies->create($company_fields);

					try {
						// Create a new user using email address. And assign as a company user.
						$creator_user = $intercom->contacts->create([
							'name'      => ucwords( trim( $submitted_fields['name'] ) ) . ' Creator',
							'email' 	=> $submitted_fields['email'],
							'companies' => [
								//[ 'company_id' => $company->company_id ]
								[ 'id'  => $company->id ]
							],
							'type' 		=> 'user',
						]);
						/**
						 * Add companies to a contact with IDs
						 */
						$intercom->companies->attachContact( $creator_user->id, $company->id );

						$response['company_info'] = $creator_user;
						$response['success']      = 1;
						$response['message']      = __( 'Company Registration Completed.', 'mplus-intercom-subscription' );
						wp_send_json( $response );
						die();
					} catch (Exception $e) {
						//If use exists for submitted email address. Update user's company information.
						try {
							/** Search for contacts */
							$query = [ 'field' => 'email', 'operator' => '=', 'value' => $submitted_fields['email'] ];
							$query_users = $intercom->contacts->search([
								'pagination' => ['per_page' => 1],
								'query'      => $query,
							]);

							$exists_users = $query_users->data;
							$exists_user  = $exists_users[0];

							// Update user company information.
							$creator_user = $intercom->contacts->update( $exists_user->id, [
								'email' 	=> $submitted_fields['email'],
								'companies' => [
									[ 'id' => $company->id ]
								],
							]);
							/**
							 * Add companies to a contact with IDs
							 */
							$intercom->companies->attachContact( $creator_user->id, $company->id );

							$response['company_info'] = $creator_user;
							$response['success']      = 1;
							$response['message']      = __( 'Company Registration Completed.', 'mplus-intercom-subscription' );
							wp_send_json( $response );
							die();
						} catch (Exception $e) {
							$response['success'] = 0;
							$response['message'] = __( $e->getMessage(), 'mplus-intercom-subscription' );
							wp_send_json( $response );
							die();
						}
					}
				} catch (Exception $e) {
					// If company not created properly send error message.
					$response['success'] = 0;
					$response['message'] = __( $e->getMessage(), 'mplus-intercom-subscription' );
					wp_send_json( $response );
					die();
				}
			}
		}

		/**
		 * Handles User assign to the Registered Company.
		 *
		 * @return void
		 */
		public function user_assign_to_company_handler( $new_user, $submitted_fields, $client ) {

			if ( is_object( $new_user ) && isset( $new_user->email ) ) {
				foreach ( $submitted_fields as $field ) {
					if ( array_key_exists( 'name', $field ) && $field['name'] == 'company_id' ) :
						$company_id = $field['value'];
						break;
					endif;
				}

				if ( isset( $company_id ) ) :
					$client->companies->attachContact( $new_user->id, $company_id );
				endif;

			}
		}
		/**
		 * print the chat bubble
		 *
		 * @return void
		 */
		public function chat_bubble(){
			if( get_option( 'mplusis_enable_chat' ) ){
				$app_id = get_option( 'mplusis_app_id' );
				if($app_id){
					echo <<<EOT
					<script>
						window.intercomSettings = {
						api_base: "https://api-iam.intercom.io",
						app_id: "{$app_id}"
						};
					</script>
					
					<script>
					(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/{$app_id}';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);};if(document.readyState==='complete'){l();}else if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
					</script>
	EOT;
				}
			}
		}
	}
}
