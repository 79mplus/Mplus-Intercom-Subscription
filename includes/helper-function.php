<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

/**
 * Locates template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/mplus-intercom-subscription/templates/$template_name
 * 2. /plugins/mplus-intercom-subscription/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param string $template_name Template to load.
 * @param string $template_path (optional) Path to templates.
 * @param string $default_path (optional) Default path to template files.
 * @return string Path to the template file.
 */
function mplus_intercom_subscription_locate_template( $template_name, $template_path = '', $default_path = '' ) {

	// Set variable to search in templates folder of theme.
	if ( ! $template_path ) :
		$template_path = get_template_directory() . '/' . MPLUSIS_NAME . '/templates/';
	endif;
	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = MPLUSIS_PLUGINS_DIR . 'templates/';
	endif;
	// Search template file in theme folder.
	$template = locate_template( array(
		$template_path . $template_name,
		$template_name
	) );
	// Get plugins template file.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;
	return apply_filters( 'mplus_intercom_subscription_locate_template', $template, $template_name, $template_path, $default_path );

}


/**
 * Gets template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @see mplus_intercom_subscription_locate_template()
 *
 * @param string $template_name Template to load.
 * @param array $args Args passed for the template file.
 * @param string $template_path (optional) Path to templates.
 * @param string $default_path (optional) Default path to template files.
 * @return null|void
 */
function mplus_intercom_subscription_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {

	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;
	$template_file = mplus_intercom_subscription_locate_template( $template_name, $tempate_path, $default_path );
	if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
		return;
	endif;
	// Gets the content from the template.
	ob_start();
	require_once $template_file;
	$html = ob_get_clean();
	return $html;

}


/**
 * Gets all intercom company select options.
 *
 * @since 1.0.0
 *
 * @return array
 */
function get_all_company_list() {
	$intercom = Mplus_Intercom_Subscription_Core::get_client();
	$companies_options = array();

	try {
		$company = $intercom->companies->getCompanies( [] );

		$companies = $company->companies;
		$companies_options[] = 'Select Company';
		foreach ( $companies as $company ) :
			if ( isset( $company->name ) ) :
				$companies_options[ $company->company_id ] = $company->name;
			endif;
		endforeach;
	} catch ( Exception $e ) {
		$companies_options[] = 'Select Company';
	}

	return $companies_options;
}


/**
 * Gets company information.
 *
 * @param int $company_id Company id to be used for function.
 * @return mixed
 */
function get_company_information( $company_id ) {

	$intercom = Mplus_Intercom_Subscription_Core::get_client();
	return $company = $intercom->companies->getCompanies( [ 'company_id' => $company_id ] );
}
