<?php

/**
 * class to submit data to intercom
 */
class Mplus_Intercom_Submitter
{
    private $api;

    public function __construct(){
        /* initialize the api with the accesstoken*/
    }

    /**
     * @param array $fields fields to submit
     * @param string $user_type either user or lead
     */
    public function create_user( $fields, $user_type = 'user'){

    }
}