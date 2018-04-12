<?php

/**
 * The subscription form class.
 */
class Mplus_Intercom_Subscription_Form{
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
				'type' => 'text',
				'label' => 'Name',
				'name' => 'name',
				'intercom_attribute' => 'name',
				'attribute_type' => 'basic',
				'required' => true,
				'sanitize' => 'sanitize_text',
			),
			array(
				'type' => 'email',
				'label' => 'Email',
				'name' => 'email',
				'intercom_attribute' => 'email',
				'attribute_type' => 'basic',
				'required' => true,
				'sanitize' => 'sanitize_email',
			),
			array(
				'type' => 'submit',
				'label' => 'Submit',
				'name' => 'submit',
			),
		);

		$this->fields = apply_filters( 'mplus_intercom_form_fields', $fields );
	}

	/**
	 * Returns or renders the form html.
	 *
	 * @return string
	 */
	public function render_form() {

		$html = '';

		$html .= '<form class="mpss_intercom" method="post">';
			foreach ( $this->fields as $field ) :
				$html .= $this->render_form_input( $field );
			endforeach;
		$html .= '</form>';
		$html .= '<div class="message">' . __( 'Thank You!', 'mplus-intercom-core' ) . '</div>';
		return $html;
	}


	/**
	 * Returns or renders the form single input field.
	 *
	 * @return string
	 */
	public function render_form_input( $field ) {
		$default = array(
				'type' => 'text',
				'label' => '',
				'name' => '',
				'intercom_attribute' => '',
				'attribute_type' => 'basic',
				'required' => true,
				'onclick'   => '',
				'sanitize' => '',
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
				$input = '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $intercom_attribute ) . '" ' . $required . ' value="" />';
				break;
			case 'textarea' :
				$input = '<textarea name="' . esc_attr( $name ) . '" id="' . esc_attr( $intercom_attribute ) . '" ' . $required .' /></textarea>';
				break;
			case 'button' :
			case 'submit' :
				$input = '<input type="' . esc_attr( $type ) . '" id="' . esc_attr( $name ) . '" value="' . esc_attr( $label ) . '" ' . $onclick . ' />';
				break;
			default :
				break;
		endswitch;

		$html ='';
		$html .= '<p class="input-group">';
			if ( $label != '' && $type != 'button' && $type != 'submit' ) :
				$html .= '<label for="' . esc_attr( $name ) . '">' . esc_attr( $label ) . '</label>';
			endif;
			$html .= $input;
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

		$sub_type = get_option( 'mplus_ic_sub_type' );

		$intercom_submitter = new Mplus_Intercom_Submitter();

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
			default:
				break;
		endswitch;
		
		return $field_value;

	}

}