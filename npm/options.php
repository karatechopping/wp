<?php
//acf options panel
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Theme Options',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> true
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'General Settings',
		'menu_title'	=> 'General Settings',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Header Settings',
		'menu_title'	=> 'Header Settings',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Footer Settings',
		'menu_title'	=> 'Footer Settings',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Listing Settings',
		'menu_title'	=> 'Listing Settings',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Featured Listing Settings',
		'menu_title'	=> 'Featured Listing',
		'parent_slug'	=> 'theme-general-settings',
	));	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Paypal Settings',
		'menu_title'	=> 'Paypal Settings',
		'parent_slug'	=> 'theme-general-settings',
	));	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Stripe Setting',
		'menu_title'	=> 'Stripe Setting',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Banner Settings',
		'menu_title'	=> 'Banner Settings',
		'parent_slug'	=> 'theme-general-settings',
	));	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Contact Settings',
		'menu_title'	=> 'Contact Settings',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Social Media',
		'menu_title'	=> 'Social Media',
		'parent_slug'	=> 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Contact Form',
		'menu_title'	=> 'Contact Form',
		'parent_slug'	=> 'theme-general-settings',
	));	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Color Settings',
		'menu_title'	=> 'Color Settings',
		'parent_slug'	=> 'theme-general-settings',
	));
    acf_add_options_sub_page(array(
		'page_title' 	=> 'Header & Footer Setting',
		'menu_title'	=> 'Header & Footer Setting',
		'parent_slug'	=> 'theme-general-settings',
	));    
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Ads Settings',
		'menu_title'	=> 'Ads Settings',
		'menu_slug'     => 'ads-settings',
		'capability'    => 'edit_posts',
		'parent_slug'	=> 'edit.php?post_type=ads',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Claim Listing Settings',
		'menu_title'	=> 'Claim Listing Settings',
		'menu_slug'     => 'claim-email-settings',
		'capability'    => 'edit_posts',
		'parent_slug'	=> 'edit.php?post_type=claim_listing',
	));
}
function my_acf_init() {
    $fetch_plugin_api_key = get_option( 'gpi_plugin_global_settings' );
    if(is_array($fetch_plugin_api_key) ) {
        $plugin_api_key =  $fetch_plugin_api_key["api_key"];
		if (isset($fetch_plugin_api_key["api_key_front_end"]) && !empty($fetch_plugin_api_key["api_key_front_end"])) $plugin_api_key = $fetch_plugin_api_key["api_key_front_end"];
    }
    $cmnapikey=get_field('googlemapcommonapikey','option');
   	if(!empty($plugin_api_key)){
        acf_update_setting('google_api_key', $plugin_api_key);
    } else {
        acf_update_setting('google_api_key', $cmnapikey);
    }
}
add_action('acf/init', 'my_acf_init');