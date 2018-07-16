<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) :
	die;
endif;

	$fields = array(
		array(
			'type' => 'text',
			'label' => __( 'Company Name', 'mplus-intercom-subscription' ),
			'name' => 'name',
			'intercom_attribute' => 'name',
			'attribute_type' => 'basic',
			'required' => true,
			'sanitize' => 'sanitize_text',
			'description' => __( 'The name of the company.', 'mplus-intercom-subscription' ),
		),
		array(
			'type' => 'email',
			'label' => __( 'Company Email', 'mplus-intercom-subscription' ),
			'name' => 'email',
			'intercom_attribute' => 'email',
			'attribute_type' => 'basic',
			'required' => true,
			'sanitize' => 'sanitize_email',
			'description' => __( 'The email of the company.', 'mplus-intercom-subscription' ),
		),
		array(
			'type' => 'date',
			'label' => __( 'Company Created At', 'mplus-intercom-subscription' ),
			'name' => 'created_at',
			'intercom_attribute' => 'created_at',
			'attribute_type' => 'basic',
			'required' => true,
			//'sanitize' => 'sanitize_text',
			'description' => __( 'The date at which the company was started. In mm/dd/yyyy format.', 'mplus-intercom-subscription' ),
		),
		array(
			'type' => 'text',
			'label' => __( 'Plan', 'mplus-intercom-subscription' ),
			'name' => 'plan',
			'intercom_attribute' => 'plan',
			'attribute_type' => 'basic',
			'required' => true,
			'sanitize' => 'sanitize_text',
			'description' => __( 'The plan or package the company is under. This is useful if you are selling any package product to the company.', 'mplus-intercom-subscription' ),
		),
		array(
			'type' => 'text',
			'label' => __( 'Company Industry', 'mplus-intercom-subscription' ),
			'name' => 'industry',
			'intercom_attribute' => 'industry',
			'attribute_type' => 'basic',
			'required' => true,
			'sanitize' => 'sanitize_text',
			'description' => __( 'The industry in which the company works on. E.g. Computer Accessories, Food Home Delivery.', 'mplus-intercom-subscription' ),
		),
		array(
			'type' => 'number',
			'label' => __( 'People', 'mplus-intercom-subscription' ),
			'name' => 'size',
			'intercom_attribute' => 'size',
			'attribute_type' => 'basic',
			'required' => true,
			'sanitize' => 'sanitize_text',
			'description' => __( 'How many employees or people are related to the company.', 'mplus-intercom-subscription' ),
		),
		array(
			'type' => 'text',
			'label' => __( 'Company Website', 'mplus-intercom-subscription' ),
			'name' => 'website',
			'intercom_attribute' => 'website',
			'attribute_type' => 'basic',
			'required' => true,
			'sanitize' => 'esc_url',
			'description' => __( 'Website of the company.', 'mplus-intercom-subscription' ),
		),
	);
	$fields = apply_filters( 'mplus_intercom_subscription_company_form_before_submit', $fields );

	$fields [] = array(
			'type' => 'submit',
			'label' => __( 'Register Company', 'mplus-intercom-subscription' ),
			'name' => 'submit_company',
		);

	$fields = apply_filters( 'mplus_intercom_subscription_company_form_after_submit', $fields );
?>
<div class="mplus-intercom-subscription-wrap">
	<div class="mplus-intercom-subscription-company-form">
	<?php
		$mplus_intercom_subscription_company_form = new Mplus_Intercom_Subscription_Form();
		echo $mplus_intercom_subscription_company_form->render_form( $fields );
	?>
	</div>
</div>
