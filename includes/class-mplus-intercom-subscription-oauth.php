<?php

/**
 * Manages oAuth.
 *
 * @package Mplus_Intercom_Subscription
 * @subpackage Mplus_Intercom_Subscription/includes
 * @author 79mplus
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'Mplus_Intercom_Subscription_OAuth' ) ) {

	class Mplus_Intercom_Subscription_OAuth {

		static $namespace = 'mplus-internet-subscription'; 
		static $version = 'v1';

		static $OAuth_URL = 'https://www.79mplus.com/intercom/';

		/**
		 * Constructs the class.
		 *
		 * @return void
		 */
		function __construct() {

		}

		/**
		 * regiser rest route.
		 */
		public function rest_route() {

			register_rest_route( static::$namespace . '/' . static::$version , '/access-token', array(
				'methods' => 'POST',
				'callback' => [ $this, 'save_token' ],
				'permission_callback' => '__return_true'
			) ); 

		}

		/**
		 * the URL to connect to Intercom
		 */
		public static function connect_url(){
			$nonce = md5(time().rand(100,999));
			set_transient( 'intercom_oauth_nonce', $nonce, HOUR_IN_SECONDS );
			$connect_url = add_query_arg([
				'nonce' => $nonce,
				'site' 	=> site_url(),
			], static::$OAuth_URL);
			
			return $connect_url;
		}

		public function save_token(){
			try {

				$data = json_decode(file_get_contents('php://input'), true);
	
				if (empty($data)) {

					$response = new WP_HTTP_Response('Payload is empty.', 400 );

				}
	
				if (isset($data['nonce']) && isset($data['access_token'])) {

					if ($data['nonce'] == get_transient('intercom_oauth_nonce')) {

						delete_transient('intercom_oauth_nonce');

						update_option( 'mplusis_api_key', $data['access_token'] );
						
						update_option( 'mplusis_app_id', $data['app_id'] );

						$response = new WP_HTTP_Response('ok.', 200 );

	
					} else {

						$response = new WP_HTTP_Response('Invalid request.', 401 );

					}
				}
	
			} catch (\Exception $e) {
	
				error_log($e->getMessage());

				$response = new WP_HTTP_Response($e->getMessage(), 500 );
			}
	
			return rest_ensure_response( $response );
		}
	}
}
