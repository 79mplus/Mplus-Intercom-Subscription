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
	 * 1. /themes/theme/mplus-intercom-core/templates/$template_name
	 * 2. /plugins/mplus-intercom-core/templates/$template_name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $template_name Template to load.
	 * @param string $template_path Path to templates.
	 * @param string $default_path Default path to template files.
	 * @return string Path to the template file.
	 */
	function mplus_intercom_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		// Set variable to search in templates folder of theme.
		if ( ! $template_path ) :
			$template_path = get_template_directory() . '/' . MPLUSI_NAME . '/templates/';
		endif;
		// Set default plugin templates path.
		if ( ! $default_path ) :
			$default_path = MPLUSI_PLUGINS_DIR . 'templates/';
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
		return apply_filters( 'mplus_intercom_locate_template', $template, $template_name, $template_path, $default_path );
	}


	/**
	 * Gets template.
	 *
	 * Search for the template and include the file.
	 *
	 * @since 1.0.0
	 *
	 * @see mplus_intercom_locate_template()
	 *
	 * @param string $template_name Template to load.
	 * @param array $args Args passed for the template file.
	 * @param string $string $template_path	Path to templates.
	 * @param string $default_path Default path to template files.
	 * @return null|void
	 */
	function mplus_intercom_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
		if ( is_array( $args ) && isset( $args ) ) :
			extract( $args );
		endif;
		$template_file = mplus_intercom_locate_template( $template_name, $tempate_path, $default_path );
		if ( ! file_exists( $template_file ) ) :
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
			return;
		endif;
		include $template_file;
	}