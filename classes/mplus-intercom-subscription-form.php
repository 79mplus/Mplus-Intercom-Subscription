<?php

/**
 * The subscription form
 */
class Mplus_Intercom_Subscription_Form
{
    /**
     * the form fields
     */
    private $fields = array();

    public function __construct(){

        $fields = array(
            array(
                'type' => 'text',
                'label' => 'Name',
                'name' => 'name',
                'intercom_attribute' => 'name',
                'attribute_type' => 'basic',
                'required' => true
            ),
            array(
                'type' => 'email',
                'label' => 'Email',
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
    public function render_form(){

        $html = '';

        $html .= '<form class="mpss_intercom" method="post">';
            foreach ($this->fields as $field) :
                $html .= $this->render_form_input($field);
            endforeach;
        $html .= '</form>';
        return $html;
    }


    /**
     * render the form single input field
     */
    public function render_form_input($field){
        $default = array(
                'type' => 'text',
                'label' => '',
                'name' => '',
                'intercom_attribute' => '',
                'attribute_type' => 'basic',
                'required' => true,
                'onclick'   => '',
            );

        // Merge default field with user submitted field.
        $field = array_replace_recursive( $default, $field );
        extract($field);

        if($required) :
            $required = 'required="required"';
        else:
            $required = '';
        endif;

        if($onclick != ''):
            $onclick = 'onclick="'.$onclick.'()">';
        else:
            $onclick = '';
        endif;
        switch ($type) :
            case 'text':
            case 'tel':
            case 'email':
                $input = '<input type="'.esc_attr($type).'" name="'.esc_attr($name).'" id="'.esc_attr($intercom_attribute).'" '. $required .' value="" />';
                break;
            case 'textarea':
                $input = '<textarea name="'.esc_attr($name).'" id="'.esc_attr($intercom_attribute).'" '. $required .' /></textarea>';
                break;
            case 'button':
            case 'submit':
                $input = '<input type="'.esc_attr($type).'" id="'.esc_attr($intercom_attribute).'" value="'.esc_attr($label).'" '.$onclick.' />';
                break;
            default:
                # code...
                break;
        endswitch;

        $html ='';
        $html .= '<div class="input-group">';
            if($label !='' && $type !='button' && $type != 'submit') :
                $html .= '<label for="'.esc_attr($name).'">'.esc_attr($label).'</label>';
            endif;
            $html .= $input;
        $html .= '</div>';

        return $html;
    }

    /**
     * handling submission of the form
     */
    public function submit_handler(){

        $html = '';
        foreach ($this->fields as $field) :
            $key = $field['intercom_attribute'];
            $html .= "data[$key] = $( 'form.mpss_intercom #$key' ).val();";
        endforeach;

        $script = "jQuery(function( $ ) {

            $( 'form.mpss_intercom' ).submit(function(e){
                e.preventDefault();

                var data;

                ".$html."

                $.ajax({
                    url : wp.ajaxurl,
                    data : data,
                    type: 'POST',
                    action: 'intercom_form_submit',
                    
                    beforeSend: function() {

                    },

                    success: function(data, textStatus, jqXHR) {
 
                    },

                    error: function(jqXHR, textStatus, errorThrown) {

                        console.log('The following error occured: ' + textStatus, errorThrown);
                    }
                })

            });
            
        });";

        return $script;

    }


}