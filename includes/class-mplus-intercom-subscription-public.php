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
			$response = array();
			$intercom = Mplus_Intercom_Subscription_Core::get_client();

			$fields = $_POST['fields'];

			foreach ( $fields as $field ) :
				$submitted_fields[ $field['name'] ] =  $field['value'];
			endforeach;

			try {

				$company = $intercom->companies->getCompanies( [
					'name' => $submitted_fields['name']
				] );
				$company_fields = [
					'id'         => $company->id,
					'plan'       => esc_attr( $submitted_fields['plan'] ),
					'created_at' => strtotime( $submitted_fields['created_at'] ),
					'size'       => esc_attr( $submitted_fields['size'] ),
					'website'    => esc_url( $submitted_fields['website'] ),
					'industry'   => esc_attr( $submitted_fields['industry'] ),
				];
				$company = $intercom->companies->create( $company_fields );
				$response['company_info'] = $company;
				$response['message'] = __( 'Company already exists. Company Information updated.', 'mplus-intercom-subscription' );
				$response['success'] = 0;

			} catch ( Exception $e ) {

				$company_fields = [
					'name'			=> esc_attr( $submitted_fields['name'] ),
					'company_id' 	=> mt_rand( 10,999999 ),
					'plan'			=> esc_attr( $submitted_fields['plan'] ),
					'created_at'	=> strtotime( $submitted_fields['created_at'] ),
					'size'			=> esc_attr( $submitted_fields['size'] ),
					'website'		=> esc_url( $submitted_fields['website'] ),
					'industry'		=> esc_attr( $submitted_fields['industry'] ),
				];

				// Assign company creator as a company user
				$creator_user = $intercom->users->create( [
					'email'     => $submitted_fields['email'],
					'name'      => ucwords( $company->name ) . ' Creator',
					'companies' => [ $company_fields ]
				] );

				$response['company_info'] = $creator_user->companies;
				$response['success'] = 1;
				$response['message'] = __( 'Company Registration Completed.', 'mplus-intercom-subscription' );
			}

			wp_send_json( $response );

			die();

		}

		/**
		 * Handles User assign to the Registered Company.
		 *
		 * @return void
		 */
		public function user_assign_to_company_handler( $new_user, $submitted_fields ) {

			if ( is_object( $new_user ) && isset( $new_user->email ) ) {

				$intercom = Mplus_Intercom_Subscription_Core::get_client();

				foreach ( $submitted_fields as $field ) {
					if ( array_key_exists( 'name', $field ) && $field['name'] == 'company_id' ) :
						$company_id = $field['value'];
						break;
					endif;
				}
				if ( isset( $company_id ) ) :
					$intercom->users->update( [
						'email'     => $new_user->email,
						'companies' => [
							[
								'company_id' => $company_id
							]
						]
					] );
				endif;

			}
		}
	}
}
