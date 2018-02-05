<?php

/**
* class to submit data to intercom
*/
class Mplus_Intercom_Submitter
{
  private $client;

  public function __construct(){
    /* initialize the api with the accesstoken*/
    $access_token = get_option('mplus_ic_api_key');
    $this->client = new Intercom\IntercomClient($access_token, null);
  }

  /**
  * @param object $fields fields to submit
  * @param string $user_type either user or lead
  */
  public function create_user($fields, $user_type = 'user' ){

    $client = $this->client;

    $response = array();
    if($user_type == 'lead'){
      try{
        $new_user = $client->leads->create([
          "email" => "$fields->email",
          "name" => "$fields->name",
          "unsubscribed_from_emails" => $fields->unsubscribed_from_emails,
          "custom_attributes" => $fields->custom_attributes,
        ]);
      }catch (Exception $e){
        $response['success'] = 0;
        if ( $e->getCode() == 409 ){
          /*there are multiple users with this email.
          in this case we create the user using a custom user_id and save that in wp*/
          /*but at first check if we have already done this for this email*/
          $user_id = get_option('intercom_' . $fields->email);
          $user_found = $user_id;
          if(!$user_found){
            $user_id = 'nanit-' . time();
          }
          $new_user = $client->leads->create([
            "user_id" => "$fields->user_id",
            "email" => "$fields->email",
            "name" => "$fields->name",
            "unsubscribed_from_emails" => $fields->unsubscrihisbed_from_emails,
            "custom_attributes" => $fields->custom_attributes,
          ]);
          if($new_user && !$user_found){
            update_option('intercom_' . $fields->email, $user_id);
          }

        }else{
          $response['message'] = "An error occurred while registering the user.";
          wp_send_json( $response );
          die();
        }
      }
    }else{
      try{
        $new_user = $client->users->create([
          "email" => "$fields->email",
          "name" => "$fields->name",
          "unsubscribed_from_emails" => $fields->unsubscribed_from_emails,
          "custom_attributes" => $fields->custom_attributes,
        ]);
      }catch (Exception $e){
        $response['success'] = 0;
        if ( $e->getCode() == 409 ){
          /*there are multiple users with this email.
          in this case we create the user using a custom user_id and save that in wp*/
          /*but at first check if we have already done this for this email*/
          $user_id = get_option('intercom_' . $fields->email);
          $user_found = $user_id;
          if(!$user_found){
            $user_id = 'nanit-' . time();
          }
          $new_user = $client->users->create([
            "user_id" => "$fields->user_id",
            "email" => "$fields->email",
            "name" => "$fields->name",
            "unsubscribed_from_emails" => $fields->unsubscribed_from_emails,
            "custom_attributes" => $fields->custom_attributes,
          ]);
          if($new_user && !$user_found){
            update_option('intercom_' . $fields->email, $user_id);
          }

        }else{
          $response['message'] = "An error occurred while registering the user.";
          wp_send_json( $response );
          die();
        }
      }
    }

    return $new_user;
  }
}
