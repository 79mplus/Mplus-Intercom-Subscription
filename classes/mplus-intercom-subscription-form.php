<?php

/**
 * Manages Form functionality of this plugin.
 *
 * @package Mplus_Intercom_Subscription
 * @subpackage Mplus_Intercom_Subscription/classes
 * @author 79mplus
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'Mplus_Intercom_Subscription_Form' ) ) {
	class Mplus_Intercom_Subscription_Form {

		/**
		 * @var array $fields Contains the form fields.
		 */
		private $fields = array();

		/**
		 * Constructor for the class.
		 *
		 * @return void
		 */
		public function __construct() {

			$fields = array(
				array(
					'type'               => 'text',
					'label'              => __( 'Name', 'mplus-intercom-subscription' ),
					'name'               => 'name',
					'intercom_attribute' => 'name',
					'attribute_type'     => 'basic',
					'required'           => true,
					'sanitize'           => 'sanitize_text',
				),
				array(
					'type'               => 'email',
					'label'              => __( 'Email', 'mplus-intercom-subscription' ),
					'name'               => 'email',
					'intercom_attribute' => 'email',
					'attribute_type'     => 'basic',
					'required'           => true,
					'sanitize'           => 'sanitize_email',
				),
			);

			$intercom_company = get_option( 'mplusis_subscribe_company_field' );
			$page_link = get_option( 'mplusis_subscribe_company_register_page' );
			if ( ! empty( $page_link ) ) :
				$description = sprintf(
					__( 'Your company not listed? %sCreate it%s.', 'mplus-intercom-subscription' ),
					'<a href="' . $page_link . '" target="_blank" >',
					'</a>' );
				$description = '<span class="mpis-company-create-text">' . $description . '</span>';
			else :
				$description = '';
			endif;

			if ( ! empty( $intercom_company ) && $intercom_company == 1 ) :
				$fields [] = apply_filters( 'mplus_intercom_subscription_form_fields_company', array(
						'type'               => 'select',
						'label'              => __( 'Company', 'mplus-intercom-subscription' ),
						'name'               => 'company_id',
						'intercom_attribute' => 'company_id',
						'required'           => true,
						'attribute_type'     => '',
						'options'            => get_all_company_list(),
						'description'        => $description,
					));
			endif;

			$fields = apply_filters( 'mplus_intercom_subscription_form_fields_before_consent', $fields );

			$sub_to_intercom = get_option( 'mplusis_subscribe_to_intercom' );
			$sub_to_intercom = apply_filters( 'mplus_intercom_subscription_consent_enable', $sub_to_intercom );

			if ( ! empty( $sub_to_intercom ) && $sub_to_intercom == 1 ) :
				$fields [] = apply_filters( 'mplus_intercom_subscription_form_fields_consent', array(
						'type'               => 'checkbox',
						'label'              => __( 'Subscribe to email', 'mplus-intercom-subscription' ),
						'name'               => 'unsubscribed_from_emails',
						'intercom_attribute' => 'unsubscribed_from_emails',
						'attribute_type'     => 'basic'
					));
			endif;

			$fields = apply_filters( 'mplus_intercom_subscription_form_fields_after_consent', $fields );

			$fields [] = array(
					'type'  => 'submit',
					'label' => __( 'Submit', 'mplus-intercom-subscription' ),
					'name'  => 'submit',
				);

			$this->fields = apply_filters( 'mplus_intercom_subscription_form_fields', $fields );

		}

		/**
		 * Returns or renders the form html.
		 *
		 * @return string
		 */
		public function render_form( $fields = array() ) {

			$html = '';

			if ( ! empty( $fields ) ) :
				$this->fields = $fields;
			endif;

			$html .= '<form class="mplus_intercom_subscription" method="post" autocomplete="off">';
				foreach ( $this->fields as $field ) :
					$html .= $this->render_form_input( $field );
				endforeach;
			$html .= '</form>';
			$html .= '<div class="message">' . __( 'Thank You!', 'mplus-intercom-subscription' ) . '</div>';
			return $html;

		}

		/**
		 * Returns or renders the form single input field.
		 *
		 * @return string
		 */
		public function render_form_input( $field ) {

			$default = array(
					'type'               => 'text',
					'label'              => '',
					'name'               => '',
					'intercom_attribute' => '',
					'attribute_type'     => 'basic',
					'required'           => true,
					'onclick'            => '',
					'sanitize'           => '',
					'options'            => array(),
					'description'        => '',
				);

			// Merge default field with user submitted field.
			$field = array_replace_recursive( $default, $field );
			extract( $field );

			if ( $required ) :
				$required = 'required="required"';
			else:
				$required = '';
			endif;

			if ( $onclick != '' ) :
				$onclick = 'onclick="' . $onclick . '()">';
			else:
				$onclick = '';
			endif;
			switch ( $type ) :
				case 'text' :
				case 'tel' :
				case 'email' :
				case 'number' :
					$input = '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $intercom_attribute ) . '" ' . $required . ' value="" />';
					break;
				case 'textarea' :
					$input = '<textarea name="' . esc_attr( $name ) . '" id="' . esc_attr( $intercom_attribute ) . '" ' . $required .' /></textarea>';
					break;
				case 'checkbox':
					$input = '<input type="checkbox" name="' . esc_attr( $name ) . '" id="' . esc_attr( $intercom_attribute ) . '" value="true"> ';
					break;
				case 'select':
					$input = '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $intercom_attribute ) . '" '. $required .' >';
					$opt_val = '';
					foreach ( $options as $key => $opt ) :
						if ( $key == 0 ) {
							$key = '';
						}
						$opt_val .= '<option value="' . $key . '">' . $opt . '</option>';
					endforeach;
					$input .= $opt_val;
					$input .= '</select>';
					break;
				case 'date' :
					$input = '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $intercom_attribute ) . '" ' . $required . ' value="" />';
					break;
				case 'button' :
				case 'submit' :
					$input = '<input type="' . esc_attr( $type ) . '" id="' . esc_attr( $name ) . '" value="' . esc_attr( $label ) . '" ' . $onclick . ' />';
					break;
				default :
					$input = '';
					break;
			endswitch;

			$html ='';
			$htmlclass = 'input-group';
			$htmlclass .= ' type-' . $type;
			$html .= '<p class="' . $htmlclass . '">';
				if ( $label != '' && $type != 'button' && $type != 'submit' ) :
					$html .= '<label for="' . esc_attr( $name ) . '">' . esc_attr( $label ) . '</label>';
				endif;
				$html .= $input;
				$html .= $description;
			$html .= '</p>';

			return $html;

		}

		/**
		 * Handles submission of the form.
		 *
		 * @return void
		 */
		public function submit_handler() {

			$sub_type = '';
			$submitted_fields = array();

			foreach ( $this->fields as $field ) {
				foreach ( $_POST['fields'] as $f ) {
					if ( $f['name'] == $field['name'] ) :

						$field['value'] = array_key_exists('sanitize', $field ) ? self::field_value_sanitize( $f['value'], $field['sanitize'] ) : $f['value'];
						$submitted_fields[] = $field;
					endif;
				}
			}

			$sub_type = get_option( 'mplusis_subscription_type' );

			$intercom_submitter = new Mplus_Intercom_Subscription_Handler();

			$intercom_res = $intercom_submitter->create_user( $submitted_fields, $sub_type );

			wp_send_json( $intercom_res );

			die();

		}

		/**
		 * Handles Sanitizing: Cleaning User Input when form submited.
		 *
		 * @param string $field_value Value of the field.
		 * @param string $sanitize_type (optional) Type of the sanitization.
		 * @return void
		 */
		public static function field_value_sanitize( $field_value, $sanitize_type = '' ) {

			switch ( $sanitize_type ) :
				case 'sanitize_text':
					$field_value = sanitize_text_field( $field_value );
					break;
				case 'sanitize_textarea':
					$field_value = sanitize_textarea_field( $field_value );
					break;
				case 'sanitize_email':
					$field_value = sanitize_email( $field_value );
					break;
				case 'esc_textarea':
					$field_value = esc_textarea( $field_value );
					break;
				case 'esc_url':
					$field_value = esc_url( $field_value );
					break;
				default:
					break;
			endswitch;

			return $field_value;

		}
	}
}
