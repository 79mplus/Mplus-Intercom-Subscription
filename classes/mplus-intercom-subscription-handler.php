<?php

/**
 * class to submit data to intercom
 */
class Mplus_Intercom_Subscription_Handler {
	/**
	 * @var \Intercom\IntercomClient $client Holds the Intercom client instance.
	 */
	private $client;

	/**
	 * Constructor for the class.
	 *
	 * @param string|null $access_token Access token for Intercom API.
	 * @return void
	 */
	public function __construct( $access_token = null ) {
		// Initializes the api with the accesstoken.
		if ( empty( $access_token ) ) {
			$access_token = get_option( 'mplus_ic_api_key' );
		}
		$this->client = new Intercom\IntercomClient( $access_token, null );
	}

	/**
	 * Creates user with the given info.
	 *
	 * @param array $fields Fields to submit.
	 * @param string $user_type (optional) Either user or lead.
	 * @return array
	 */
	public function create_user( $fields, $user_type = 'user' ) {

		$client = $this->client;

		$fields = self::get_fields( $fields );

		$response = array();
		if ( $user_type == 'lead' ) {
			try {
				$new_user = $client->leads->create( $fields );
				if ( ! empty( $new_user->id ) ) :
					$response['massage'] =  __( 'Added New User.', 'mplus-intercom-core' );
					$response['success'] = 1;
				else :
					$response['massage'] =  __( 'Something Wrong.', 'mplus-intercom-core' );
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
					$user_id = get_option( 'intercom_' . $fields->email );
					$user_found = $user_id;
					if ( ! $user_found ) {
						$user_id = 'nanit-' . time();
					}
					$fields['user_id'] = "$user_id";
					$new_user = $client->leads->create( $fields );
					if ( $new_user && ! $user_found ) {
						update_option( 'intercom_' . $fields->email, $user_id );
					}

				} else {
					$response['message'] = __( 'An error occurred while registering the user.', 'mplus-intercom-core' );
					return $response;
				}
			}
		} else {
			try {
				$new_user = $client->users->create( $fields );
				if ( ! empty( $new_user->id ) ) :
					$response['massage'] =  __( 'Added New User.', 'mplus-intercom-core' );
					$response['success'] = 1;
				else :
					$response['massage'] =  __( 'Something Wrong.', 'mplus-intercom-core' );
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
					$user_id = get_option( 'intercom_' . $fields->email );
					$user_found = $user_id;
					if ( ! $user_found ) {
						$user_id = 'nanit-' . time();
					}
					$fields['user_id'] = "$user_id";
					$new_user = $client->users->create( $fields );
					if ( $new_user && ! $user_found ) {
						update_option( 'intercom_' . $fields->email, $user_id );
					}

				} else {
					$response['message'] = __( 'An error occurred while registering the user.', 'mplus-intercom-core' );
					return $response;
				}
			}
		}

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
		foreach ( $fields as $field ) {
			if( $field['intercom_attribute'] == 'unsubscribed_from_emails' ){
				$field['value'] = true;
			}
			if ( $field['attribute_type'] == 'basic' ) {
				$basic[ $field['intercom_attribute'] ] = $field['value'];
			} else {
				$custom[ $field['intercom_attribute'] ] = $field['value'];
			}
		}
		$basic['custom_attributes'] = $custom;
		return $basic;
	}
}
