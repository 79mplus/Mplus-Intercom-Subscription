<?php

/**
 * The subscription form
 */
class Mplus_Intercom_Subscription_Form
{
    /**
     * the form fields
     */
    private $fields;

    public function __construct(){

        $fields = array(
            array(
                'type' => 'text',
                'name' => 'name',
                'intercom_attribute' => 'name',
                'attribute_type' => 'basic',
                'required' => true
            ),
            array(
                'type' => 'email',
                'name' => 'email',
                'intercom_attribute' => 'email',
                'attribute_type' => 'basic',
                'required' => true
            ),
        );

        $this->fields = apply_filters( 'mplus_intercom_form_fields', $fields );
    }

    /**
     * render the form
     */
    public function render(){
        
    }


}