<?php

/**
 * Manages Intercom functionality of this plugin.
 *
 * @author 79mplus
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'Mplus_Intercom_Subscription_Handler' ) ) {
	class Mplus_Intercom_Subscription_Handler {
		/**
		 * @var \Intercom\IntercomClient Holds the Intercom client instance.
		 */
		public $client;

		/**
		 * Constructor for the class.
		 *
		 * @param string|null $access_token Access token for Intercom API.
		 *
		 * @return void
		 */
		public function __construct($access_token = null) {
			// Initializes the api with the accesstoken.
			if ( empty( $access_token ) ) {
				$access_token = get_option( 'mplusis_api_key' );
			}
			$this->client = new Intercom\IntercomClient( $access_token, null );
		}

		/**
		 * Creates user with the given info.
		 *
		 * @param array  $submitted_fields Fields to submit.
		 * @param string $user_type        (optional) Either user or lead.
		 *
		 * @return array
		 */
		public function create_user($submitted_fields, $user_type = 'user') {
			$contact_id = null;
			$response   = [
				'success' => 0,
			];
			$client = $this->client;
			$fields = self::get_fields( $submitted_fields );
			$fields = array_merge( ['role' => $user_type ], $fields );
			$email  = isset( $fields['email'] ) ? $fields['email'] : '';

			do_action( 'mplus_intercom_subscription_user_created_before', $fields, $user_type, $this->client );

			// Email validation.
			if ( '' == $email && ! is_email( $email ) ) {
				$response['message'] = __( 'Email required.', 'mplus-intercom-subscription' );

				return $response;
			}

			/* Checking if contact exist or not exist. */
			$search_contacts = $client->contacts->search([
				'pagination' => ['per_page' => 10],
				'query'      => [ 'field' => 'email', 'operator' => '=', 'value' => $email ],
			]);

			$contacts = $search_contacts->data;

			// Validate contact role with user type.
			if ( count( $contacts ) > 0 ) {
				foreach ( $contacts as $contact ) {
					if ( $contact->role == $user_type ) {
						$contact_id = $contact->id;
					}
				}
			}

			// If contact id null create a new contact. If not null update existing contact.
			if ( is_null( $contact_id ) ) {
				try {
					$new_contact = $client->contacts->create( $fields );

					if ( isset( $new_contact->id ) ) {
						$response['message']   = __( 'Contact has been successfully registered.', 'mplus-intercom-subscription' );
						$response['success']   = 1;
						$response['type']      = $user_type;
						$response['user_info'] = $new_contact;
					}

					return $response;
				} catch ( Exception $e ) {
					$response['message'] = __( 'An error occurred while registering the contact.', 'mplus-intercom-subscription' );
					$response['code']    = $e->getCode();

					if ( 409 == $e->getCode() && 'lead' == $user_type ) {
						$response['message'] = __( 'This email already registered as an user contact. Please used another email.', 'mplus-intercom-subscription' );
					}

					return $response;
				}
			} else {
				/* Update existing contact */
				try {
					$fields['role']        = $user_type;
					$new_contact           = $client->contacts->update( $contact_id, $fields );
					$response['message']   = __( 'Contact has been successfully updated.', 'mplus-intercom-subscription' );
					$response['success']   = 1;
					$response['type']      = $user_type;
					$response['user_info'] = $new_contact;
				} catch ( Exception $e ) {
					$response['message'] = __( 'An error occurred while updating the contact.', 'mplus-intercom-subscription' );

					return $response;
				}
			}

			do_action( 'mplus_intercom_subscription_user_created_after', $new_contact, $submitted_fields, $this->client );

			return $response;
		}

		/**
		 * Gets fields.
		 *
		 * @param object $fields Fields to submit.
		 *
		 * @return array
		 */
		public static function get_fields($fields) {
			$basic  = [];
			$custom = [];
			/*default value for unsubscribed_from_emails*/
			$basic['unsubscribed_from_emails'] = true;
			foreach ( $fields as $field ) {
				if ( array_key_exists( 'intercom_attribute', $field ) ) {
					if ( 'unsubscribed_from_emails' == $field['intercom_attribute'] ) {
						$field['value'] = false;
					}

					if ( 'basic' == $field['attribute_type'] ) {
						$basic[ $field['intercom_attribute'] ] = $field['value'];
					} elseif ( 'custom' == $field['attribute_type'] ) {
						$custom[ $field['intercom_attribute'] ] = $field['value'];
					} else {
					}
				}
			}

			if ( ! empty( $custom ) ) {
				$basic['custom_attributes'] = $custom;
			}

			return $basic;
		}
	}
}
