<?php

/**
 * Manages Intercom functionality of this plugin.
 *
 * @package Mplus_Intercom_Subscription
 * @subpackage Mplus_Intercom_Subscription/classes
 * @author 79mplus
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'Mplus_Intercom_Subscription_Handler' ) ) {
	class Mplus_Intercom_Subscription_Handler {

		/**
		 * @var \Intercom\IntercomClient $client Holds the Intercom client instance.
		 */
		public $client;

		/**
		 * Constructor for the class.
		 *
		 * @param string|null $access_token Access token for Intercom API.
		 * @return void
		 */
		public function __construct( $access_token = null ) {

			// Initializes the api with the accesstoken.
			if ( empty( $access_token ) ) {
				$access_token = get_option( 'mplusis_api_key' );
			}
			$this->client = new Intercom\IntercomClient( $access_token, null );

		}

		/**
		 * Creates user with the given info.
		 *
		 * @param array $submitted_fields Fields to submit.
		 * @param string $user_type (optional) Either user or lead.
		 * @return array
		 */
		public function create_user( $submitted_fields, $user_type = 'user' ) {

			$client = $this->client;

			$fields = self::get_fields( $submitted_fields );

			do_action( 'mplus_intercom_subscription_user_created_before', $fields, $user_type );

			$response = array();
			if ( $user_type == 'lead' ) {
				try {
					$new_user = $client->leads->create( $fields );
					if ( ! empty( $new_user->id ) ) :
						$response['massage'] =  __( 'Added New User.', 'mplus-intercom-subscription' );
						$response['success'] = 1;
						$response['user_info'] = $new_user;
					else :
						$response['massage'] =  __( 'Something Wrong.', 'mplus-intercom-subscription' );
						$response['success'] = 0;
					endif;
				} catch ( Exception $e ) {
					$response['success'] = 0;
					if ( $e->getCode() == 409 ) {
						/*
						There are multiple users with this email.
						in this case creates the user using a custom user_id and saves that in wp.
						But at first checks if it has already been done this for this email.
						*/
						$user_id = get_option( 'mplus_intercom_subscription' . $fields->email );
						$user_found = $user_id;
						if ( ! $user_found ) {
							$user_id = 'mplus-intercom-subscription-' . time();
						}
						$fields['user_id'] = "$user_id";
						$new_user = $client->leads->create( $fields );
						if ( $new_user && ! $user_found ) {
							update_option( 'mplus_intercom_subscription' . $fields->email, $user_id );
						}

					} else {
						$response['message'] = __( 'An error occurred while registering the user.', 'mplus-intercom-subscription' );
						return $response;
					}
				}
			} else {
				try {
					$new_user = $client->users->create( $fields );
					if ( ! empty( $new_user->id ) ) :
						$response['massage'] =  __( 'Added New User.', 'mplus-intercom-subscription' );
						$response['success'] = 1;
						$response['user_info'] = $new_user;
					else :
						$response['massage'] =  __( 'Something Wrong.', 'mplus-intercom-subscription' );
						$response['success'] = 0;
					endif;
				} catch ( Exception $e ) {
					$response['success'] = 0;
					if ( $e->getCode() == 409 ) {
						/*
						There are multiple users with this email.
						In this case it creates the user using a custom user_id and save that in wp.
						But at first checks if it has already been done this for this email.
						*/
						$user_id = get_option( 'mplus_intercom_subscription' . $fields->email );
						$user_found = $user_id;
						if ( ! $user_found ) {
							$user_id = 'mplus-intercom-subscription-' . time();
						}
						$fields['user_id'] = "$user_id";
						$new_user = $client->users->create( $fields );
						if ( $new_user && ! $user_found ) {
							update_option( 'mplus_intercom_subscription' . $fields->email, $user_id );
						}

					} else {
						$response['message'] = __( 'An error occurred while registering the user.', 'mplus-intercom-subscription' );
						return $response;
					}
				}
			}
			
			do_action( 'mplus_intercom_subscription_user_created_after', $new_user, $submitted_fields );

			return $response;

		}

		/**
		 * Gets fields.
		 *
		 * @param object $fields Fields to submit.
		 * @return array
		 */
		public function get_fields( $fields ) {

			$basic = array();
			$custom = array();
			/*default value for unsubscribed_from_emails*/
			$basic['unsubscribed_from_emails'] = true;
			foreach ( $fields as $field ) {
				if ( $field['intercom_attribute'] == 'unsubscribed_from_emails' ) {
					$field['value'] = false;
				}
				if ( $field['attribute_type'] == 'basic' ) {
					$basic[ $field['intercom_attribute'] ] = $field['value'];
				} elseif ( $field['attribute_type'] == 'custom' ) {
					$custom[ $field['intercom_attribute'] ] = $field['value'];
				} else {

				}
			}
			$basic['custom_attributes'] = $custom;
			return $basic;

		}
	}
}
