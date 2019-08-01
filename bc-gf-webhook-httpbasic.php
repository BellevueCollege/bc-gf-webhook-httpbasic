<?php
/**
Plugin Name: Gravity Forms Webhook HTTP Basic Auth
Plugin URI: http://github.com/bellevuecollege/bc-gf-webhook-httpbasic
Description: Adds HTTP Basic Auth to Gravity Forms Webhooks
Version: 0.0.0.1
Author: Bellevue College
Author URI: https://www.bellevuecollege.edu
License: GPL-2.0+

**/

/**
 * Add Basic Auth request headers if needed
 */
add_filter( 'gform_webhooks_request_headers', function ( $request_headers, $feed, $entry, $form ) {

	$this_form = GFAPI::get_form( rgar( $entry, 'form_id' ) );
	$settings  = rgar( $this_form, 'gfwhba' );
	$enabled   = rgar( $settings, 'enable' ) === 'enabled' ? true : false;
	$userid    = rgar( $settings, 'userid' );
	$userkey   = rgar( $settings, 'userkey' );

	if ( $enabled && ( ! empty( $userid ) && ! empty( $userkey ) ) ) {
		$request_headers['Authorization'] = 'Basic ' . base64_encode( $userid . ':' . $userkey );
	}

	return $request_headers;
}, 10, 4 );


// add a custom menu item to the Form Settings page menu
function gfwhba_add_custom_form_settings_menu_item( $menu_items ) {

	$menu_items[] = array(
		'name'  => 'gfwhba_custom_form_settings_page',
		'label' => __( 'Webhook Basic Auth' ),
	);

	return $menu_items;
}

// handle displaying content for our custom menu when selected
function gfwhba_custom_form_settings_page() {

	// Only provide our menu item if person has appropriate permissions
	if ( current_user_can( 'manage_options' ) ) {
		GFFormSettings::page_header();
		require_once dirname( __FILE__ ) . '/admin/settings-page.php';
		GFFormSettings::page_footer();
	} else {
		echo __( 'You do not have the correct permissions to update this setting.' );
	}
}

//add settings submenu page to form settings
add_filter( 'gform_form_settings_menu', 'gfwhba_add_custom_form_settings_menu_item' );

//set content for custom settings menu page
add_action( 'gform_form_settings_page_gfwhba_custom_form_settings_page', 'gfwhba_custom_form_settings_page' );
