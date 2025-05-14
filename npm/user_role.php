<?php 
// Add a custom user role
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
  		show_admin_bar(false);
	}
}
//add_action( 'init', 'blockusers_init' );
function blockusers_init() {
	if ( is_admin() && ! current_user_can( 'administrator' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		wp_redirect( home_url().'/account' );
		exit;
	}
}
$result = add_role( 'listing', __('Listing' ),
	array(
	'read' => true, // true allows this capability
	'edit_posts' => true, // Allows user to edit their own posts
	'edit_pages' => true, // Allows user to edit pages
	'edit_others_posts' => true, // Allows user to edit others posts not just their own
	'create_posts' => true, // Allows user to create new posts
	'manage_categories' => true, // Allows user to manage post categories
	'publish_posts' => true, // Allows the user to publish, otherwise posts stays in draft mode
	'edit_themes' => true, // false denies this capability. User can’t edit your theme
	'install_plugins' => true, // User cant add new plugins
	'update_plugin' => true, // User can’t update any plugins
	'update_core' => true // user cant perform core updates
	)
);
// Limit media library access
  
add_filter( 'ajax_query_attachments_args', 'wpb_show_current_user_attachments' );
function wpb_show_current_user_attachments( $query ) {
    $user_id = get_current_user_id();
    if ( $user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts') ) {
        $query['author'] = $user_id;
    }
    return $query;
} 