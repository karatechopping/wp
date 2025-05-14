<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

define( 'ACF_LITE', true);
include_once('npm/advanced-custom-fields-pro/acf.php');
//include_once('npm/acf-paypal-field/acf-paypal.php');
require get_template_directory() . '/npm/options.php';
require get_template_directory() . '/npm/acf_fields.php';


/**
* required plugins
*/
require get_template_directory() . '/lib/reg_plugin.php';
require get_template_directory() . '/npm/acf-star-rating-master/acf-star_rating.php';
require get_template_directory() . '/npm/advanced-custom-fields-font-awesome/acf-font-awesome.php';


/**
* default functions
*/
require get_template_directory() . '/npm/wp-pagenavi/wp-pagenavi.php';
require get_template_directory() . '/default-functions.php';
require get_template_directory() . '/npm/registration-form.php';
require get_template_directory() . '/npm/login-form.php';
require get_template_directory() . '/npm/user_role.php';
require get_template_directory() . '/npm/post_types.php';
require get_template_directory() . '/npm/map_search.php';
require get_template_directory() . '/npm/listing_search.php';
require get_template_directory() . '/npm/comment_rating.php';
require get_template_directory() . '/csv_import.php';
require get_template_directory() . '/sendio.php';
require get_template_directory() . '/listing_claim.php';
require get_template_directory() . '/category_shortcode.php';



/**
 * Create Coupon Functionality
*/
require get_template_directory() . '/coupon/coupon.php';
require get_template_directory() . '/coupon/coupon_fields.php';


add_action('admin_menu', 'remove_built_in_roles');
 function remove_built_in_roles() {
    global $wp_roles;
    $wp_roles->remove_role('listing');
}
//$data = str_replace(array('<![CDATA[', ']]>'), array('', ''), $data);



add_action( 'save_post', 'set_featured_image_from_gallery' );

function set_featured_image_from_gallery() {
if (!empty($post_id)) :
   if ( has_post_thumbnail( $post->ID ) ) :
      $has_thumbnail = get_the_post_thumbnail($post->ID);
      if ( !$has_thumbnail ) {

        $images = get_field('images', false, false);
        $image_id = $images[0];

        if ( $image_id ) {
          set_post_thumbnail( $post->ID, $image_id );
        }
      }
    endif;
	endif;
}
//set gallery featured



add_action( 'save_post', 'set_featured_image_from_fa' );

function set_featured_image_from_fa() {
 if (!empty($post_id)) :
  if ( has_post_thumbnail( $post->ID ) ) :
      $fa_thumbnail = get_the_post_thumbnail($post->ID);
      if ( !$fa_thumbnail ) {
    
        $faimages = get_field('featured_image', false, false);
    
       set_post_thumbnail( $post->ID, $faimages );
      }
 	endif;
   endif;
}
//set gallery featured

require get_template_directory() . '/npm/listing_notify.php';
require get_template_directory() . '/npm/listing_claim.php';



//update api

require 'npm/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
  'http://www.themepluginupdate.sharkdevserver.com/api/directorytheme.json',
  __FILE__,
  'directorytheme'
);

//end update api


/**
 * Remove Admin Menu Link to Theme Customizer
 */
add_action( 'admin_menu', function () {
    global $submenu;

    if ( isset( $submenu[ 'themes.php' ] ) ) {
        foreach ( $submenu[ 'themes.php' ] as $index => $menu_item ) {
            if ( in_array( 'Customize', $menu_item ) ) {
                unset( $submenu[ 'themes.php' ][ $index ] );
            }
        }
    }
});


function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}


add_filter('wp_list_categories', 'cat_count_inline');
function cat_count_inline($links) {
$links = str_replace('</a> (', '</a><span class="count">(', $links);
$links = str_replace(')', ')</span>', $links);
return $links;
}


function crunchify_disable_comment_url($fields) { 
    unset($fields['url']);
    return $fields;
}
add_filter('comment_form_default_fields','crunchify_disable_comment_url');


/**
 * Add automatic image sizes
 */
if ( function_exists( 'add_image_size' ) ) { 
  add_image_size( 'img_1000x600', 1000, 600, true ); //(cropped)
  add_image_size( 'img_1920x1100', 1920, 1100, true ); //(scaled)
  add_image_size( 'img_1000x1000', 1000, 1000, true ); //(scaled)
}

//custom style
function generate_options_css() {
    $ss_dir = get_template_directory();
  $ss_dir_php = get_template_directory();
    ob_start(); // Capture all output into buffer
    require($ss_dir_php . '/npm/style-color.php'); // Grab the custom-style.php file
    //require get_template_directory() . '/npm/style-color.php';
    $css = ob_get_clean(); // Store output in a variable, then flush the buffer
    file_put_contents($ss_dir . '/style-color.css?'.time(), $css, LOCK_EX); // Save it as a css file
}
add_action( 'acf/save_post', 'generate_options_css', 20 ); 



// listing badges
add_filter( 'add_menu_classes', 'show_pending_number');
function show_pending_number( $menu ) {
    $type = "listings";
    $status = "draft";
    $num_posts = wp_count_posts( $type, 'readable' );
    $pending_count = 0;
    if ( !empty($num_posts->$status) )
        $pending_count = $num_posts->$status;

    // build string to match in $menu array
    if ($type == 'post') {
        $menu_str = 'edit.php';
    // support custom post types
    } else {
        $menu_str = 'edit.php?post_type=' . $type;
    }

    // loop through $menu items, find match, add indicator
    foreach( $menu as $menu_key => $menu_data ) {
        if( $menu_str != $menu_data[2] )
            continue;
        $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
    }
    return $menu;
}


//add listing type column admin
function my_edit_listing_columns($column) {
  
    $column['listing_type'] = 'Listing Type';
     return $column;

}
add_filter( 'manage_edit-listings_columns', 'my_edit_listing_columns' ) ;


function listing_column_show_value($column_name) {
    if ($column_name == 'listing_type') {
     echo get_field('listing_type');  
    }
}

add_action('manage_posts_custom_column', 'listing_column_show_value', 10, 2);

function listing_column_admin_columns_make_sortable($columns)
{
    $columns['listing_type'] = 'listing_type';

    return $columns;
}

add_filter("manage_edit-post_sortable_columns", "listing_column_admin_columns_make_sortable" );



//end listing type column admin



//listing column admin

function my_edit_ads_columns($column) {
  
    $column['ads_order'] = 'order';
     return $column;
	 
}
add_filter( 'manage_edit-ads_columns', 'my_edit_ads_columns' ) ;


function ads_column_show_value($column_name) {
    if ($column_name == 'ads_order') {
     echo get_field('ads_order');  
    }
}

add_action('manage_posts_custom_column', 'ads_column_show_value', 10, 2);

function ads_column_admin_columns_make_sortable($columns)
{
    $columns['ads_order'] = 'order';

    return $columns;
}

add_filter("manage_edit-post_sortable_columns", "ads_column_admin_columns_make_sortable" );

//end listing column admin


//add claim permission column admin
function my_edit_claim_columns($column) {
  
    $column['claim_status'] = 'Claim Status';
     return $column;

}
add_filter( 'manage_edit-claim_listing_columns', 'my_edit_claim_columns' ) ;


function claim_column_show_value($column_name) {
    if ($column_name == 'claim_status') {
		$st = get_field('claim_status');
    if($st == 2){
      echo '<div class="pending">pending</div>';
    }else if($st == 1){
			echo '<div class="approv">approved</div>';
		}
		else{
			echo '<div class="decline">declined</div>';
		}
     
    }
}

add_action('manage_posts_custom_column', 'claim_column_show_value', 10, 2);

function claim_column_admin_columns_make_sortable($columns)
{
    $columns['claim_status'] = 'claim_status';

    return $columns;
}

add_filter("manage_edit-post_sortable_columns", "claim_column_admin_columns_make_sortable" );



//end claim permission column admin

function my_edit_lexpire_columns($column) {
  
    $column['exp_date'] = 'Expiry Date';
     return $column;

}
add_filter( 'manage_edit-listings_columns', 'my_edit_lexpire_columns' ) ;


function lexpire_column_show_value($column_name) {
    if ($column_name == 'exp_date') {
     echo get_field('exp_date');  
    }
}

add_action('manage_posts_custom_column', 'lexpire_column_show_value', 10, 2);

function lexpire_column_admin_columns_make_sortable($columns)
{
    $columns['exp_date'] = 'Expiry Date';

    return $columns;
}

add_filter("manage_edit-post_sortable_columns", "lexpire_column_admin_columns_make_sortable" );

//end expiry listing column admin





function custom_admin_css() {
  echo '<style type="text/css">#wp-ultimate-csv-importer-update {display: none!important; } tr[data-slug="wp-ultimate-csv-importer"] .plugin-version-author-uri,.post-type-listings #listing-categoriesdiv{display: none!important;}';
  echo 'th#category_order,.category_order.column-category_order {text-align: center;}';
  echo '.acf-repeater .acf-actions {text-align: left;}';
  echo '.acf-field-5a56fda9782ec,.acf-field-5a74fda9882ac,.acf-field-6a74fda9872ac{display : none}';
  echo '.approv,.decline,.pending {max-width: 20%;padding: 5px;color: #fff;text-align: center;text-transform: capitalize;}';
  echo '.approv{background: rgb(0 128 0 / 0.8);}';
  echo '.decline{background: rgb(255 0 0 / 0.8);}';
  echo '.pending{background: rgb(255 131 0 / 80%);}';
  echo '</style>';
}

add_action('admin_head','custom_admin_css');


$page_ids = get_all_page_ids();
global $pricing_page, $choices_array1;
$choices_array1 = array();
$ex_optin = array('draft'); 
foreach($page_ids as $page_id){
    $pricing_page = get_the_title($page_id);
    if(get_page_template_slug($page_id) == "pricing.php"){   
    $premium_field = array();
    $pricing_page = $page_id;
    if(!empty(get_post_meta($page_id, 'pricing',true))): 
            $my_field = array();
            $pricing_tbl_cnt = get_post_meta($page_id,'pricing',true);
            if(!empty($pricing_tbl_cnt)):
              for ($i=0; $i < $pricing_tbl_cnt; $i++) { 
                  $title_slug = 'pricing_'.$i.'_title';
                  $price_title = get_post_meta($page_id,$title_slug,true);
                  $a = strtolower($price_title);
                  $choices_array1[] = sanitize_title($a);
                  array_push($ex_optin,$a);
              }
            endif;
        endif;
    }
}

if(empty($choices_array1)){
  array_push($choices_array1,'free','premium');
}


foreach($page_ids as $page_id){
    if(get_page_template_slug($page_id) == "pricing.php"){
        $get_option = get_post_meta($page_id,'pricing_0_avail_opt5',true);
        if(empty($get_option)){
            $option_array = array('company_name', 'business_description','additional_detail','address','phone','website','email_add','feature_img','cmp_logo','schedules','video','image_slideshow','extra_links','shortcode','social_media');
            update_post_meta($page_id,'pricing_0_avail_opt5',$option_array);
            update_post_meta($page_id,'pricing_1_avail_opt5',$option_array);
        }
    }
}


function Premium_to_draft(){
    global $wpdb, $choices_array1;
    $posts = get_posts(array(
        'post_type'   => 'listings',
        'post_status' => 'publish',
        'posts_per_page' => -1
        )
    );
  //echo 'hello';
  
  foreach ($posts as $key => $value) {
      $post_id = $value->ID;
      $get_date = get_post_meta($post_id,'exp_date',true);
      if(!empty($get_date)){
          $exp_date = date('Y-m-d',strtotime(get_post_meta($post_id,'exp_date',true)));         
          $expire_lstatus = get_post_meta($post_id,'expire_lstatus',true);
          $list_type= get_post_meta($post_id,'listing_type',true);
          $current_date = date('Y-m-d');
          if($exp_date < $current_date):
              if($expire_lstatus == 0){
                  $my_post = array(
                      'ID' => $post_id,
                      'post_status' => 'draft',
                  );
                  wp_update_post( $my_post );
              }else{
                  update_post_meta($post_id, 'listing_type', $choices_array1[0] );
              } /*else if($expire_lstatus == 1){
                  if($list_type == 'premium' || $list_type == 'free'){
                      update_post_meta($post_id, 'listing_type', 'free' );
                  }
              }else{
                  if($list_type == 'premium' || $list_type == 'free'){
                      update_post_meta($post_id, 'listing_type', 'premium' );
                  }
              }*/
          endif;
      }
  }
  wp_reset_postdata();
  //exit();
}
add_action('init','Premium_to_draft');




/* Custom email name */
add_filter( 'wp_mail_from_name', 'my_mail_from_name' );
function my_mail_from_name( $name ) {
  $sitename = get_bloginfo();
    return $sitename;
}

function send_fp_mail() {
  $fpemail = sanitize_email($_POST['email_id']);
  $cme=email_exists($fpemail);
  if($cme) {

    $headers = "Content-Type: text/html; charset=UTF-8\r\n";
    $reseturl=get_site_url()."/account/?reset=1&uid=".md5($cme).$cme;
    $message="Please click below link for reset your password <br/> <a target='_blank' href='$reseturl'>$reseturl</a>";
    wp_mail($fpemail,'Reset password',$message, $headers);
    echo "<span class='valid_user'>Please check your mail, we have send a link to reset your password.</span>";
  }
  else {
    echo "User doesn't exist. You need to register first.";
  }
    die();
}
add_action('wp_ajax_send_fp_mail','send_fp_mail');
add_action('wp_ajax_nopriv_send_fp_mail','send_fp_mail');


class WPImporterUpdate {
  protected $existing_post;
  function __construct() {
    add_filter( 'wp_import_existing_post', [ $this, 'wp_import_existing_post' ], 10, 2 );
    add_filter( 'wp_import_post_data_processed', [ $this, 'wp_import_post_data_processed' ], 10, 2 );
  }
  function wp_import_existing_post( $post_id, $post ) {
    if ( $this->existing_post = $post_id ) {
      $post_id = 0; // force the post to be imported
    }
    return $post_id;
  }
  function wp_import_post_data_processed( $postdata, $post ) {
    if ( $this->existing_post ) {
      // update the existing post
      $postdata['ID'] = $this->existing_post;
    }
    return $postdata;
  }
}
new WPImporterUpdate;


//add_action( 'upgrader_process_complete', 'pricing_repeater' );
function pricing_repeater(){
global $wpdb,$poid;
  $tableprefix = $wpdb->prefix;
  $post_id = $wpdb->get_results("SELECT post_id FROM ".$tableprefix."postmeta WHERE (meta_key = '_wp_page_template' AND meta_value = 'pricing.php')");
if (!empty($post_id)) {

  $poid=$post_id[0]->post_id; 
}
  $postprice=get_post_meta($poid,'pricing',true); 
  $staticplan=array("Company Name","Business Description","Address","Phone","Website","Email Address","Company Logo","Video","Image Slideshow");
//$staticplan=array();

    $postdata=array();
    $pl=get_option('plan_loaded');

    
     $plan_exist = $wpdb->get_results("SELECT post_id FROM ".$tableprefix."postmeta WHERE (meta_key = '_pricing_0_availableoptionsrepeater_0_select_choice_switcher')");

    if(empty($plan_exist)){

    
    for($i=0; $i<$postprice; $i++){
      $postdata=get_post_meta($poid,'pricing_'.$i.'_available_options',true);   
      $compareplan=array_intersect($staticplan,$postdata);
      
      $exist_array = array();
      $full_array = array();
      foreach ($postdata as $value) {
        if (in_array($value, $staticplan))
          {
            array_push($exist_array, $value);
          } 
      }
      foreach ($postdata as $value1) {
        if(!in_array($value1, $exist_array)){
          array_push($exist_array, $value1);
        }
      }
      
      $full_array = array_unique(array_merge($staticplan,$exist_array));
      $full_array = array_values($full_array);
      if($postprice != 0) { 
        for($j=0; $j < count($full_array); $j++){

          $current_switch_key = 'pricing_'.$i.'_availableoptionsrepeater_'.$j.'_select_choice_switcher';
          $current_switch_key_cf = '_pricing_'.$i.'_availableoptionsrepeater_'.$j.'_select_choice_switcher';

          $current_key = 'pricing_'.$i.'_availableoptionsrepeater_'.$j.'_avopn_choice';
          $current_key_cf = '_pricing_'.$i.'_availableoptionsrepeater_'.$j.'_avopn_choice';


          if(in_array($full_array[$j], $exist_array)){
            update_post_meta($poid, $current_switch_key, 1);

          } else {
            update_post_meta($poid, $current_switch_key, 0);
          }
          update_post_meta($poid, $current_switch_key_cf,'field_5a4dfioky7617');

          update_post_meta($poid, $current_key, $full_array[$j]);
          update_post_meta($poid, $current_key_cf, 'field_5aahtre20plka');


        }

        $row_key = 'pricing_'.$i.'_availableoptionsrepeater'; 
        $row_key_cf = '_pricing_'.$i.'_availableoptionsrepeater';

        update_post_meta($poid, $row_key, count($full_array));
        update_post_meta($poid, $row_key_cf, 'field_5aa8ytr506739');
                
        
      }
      else {
        for($k=0; $k < count($staticplan); $k++){
          $current_switch_key = 'pricing_'.$i.'_availableoptionsrepeater_'.$k.'_select_choice_switcher';
          $current_switch_key_cf = '_pricing_'.$i.'_availableoptionsrepeater_'.$k.'_select_choice_switcher';

          $current_key = 'pricing_'.$i.'_availableoptionsrepeater_'.$k.'_avopn_choice';
          $current_key_cf = '_pricing_'.$i.'_availableoptionsrepeater_'.$k.'_avopn_choice';

          $row_key = 'pricing_'.$i.'_availableoptionsrepeater'; 
          $row_key_cf = '_pricing_'.$i.'_availableoptionsrepeater';
          
          update_post_meta($poid, $current_switch_key, 0);          
          update_post_meta($poid, $current_switch_key_cf,'field_5a4dfioky7617');

          update_post_meta($poid, $current_key, $full_array[$k]);
          update_post_meta($poid, $current_key_cf, 'field_5aahtre20plka');

          update_post_meta($poid, $row_key, count($full_array));
          update_post_meta($poid, $row_key_cf, 'field_5aa8ytr506739');
        }
      }

    
    }
    update_option('plan_loaded','1');
}
    
    
}
add_action( 'after_setup_theme', 'pricing_repeater' );
function my_custom_admin_head() {
  if(get_option('plan_loaded',true) == 1){
    echo '<style>';
        echo '.acf-field.acf-field-checkbox.acf-field-5aac8814ec509 { display: none; }';
        echo '</style>';
  }
}


add_action( 'admin_head', 'my_custom_admin_head' );




// disable for posts
add_filter('use_block_editor_for_post', '__return_false', 10);

// disable for post types
add_filter('use_block_editor_for_post_type', '__return_false', 10);


function my_acf_prepare_field( $field ) {
    $field['label'] = "Listing Name";
    return $field;
}
add_filter('acf/prepare_field/name=_post_title', 'my_acf_prepare_field');

add_action( 'admin_enqueue_scripts', 'slimline_remove_divi_date_scripts', 100 );

function slimline_remove_divi_date_scripts() {

	wp_deregister_script( 'et_pb_admin_date_addon_js' );

	wp_deregister_script( 'et_pb_admin_date_js' );
}


//listing order column admin
    

function my_edit_listings_columns($column) {
  
    $column['listing_order'] = 'order';
     return $column;

}
add_filter( 'manage_edit-listings_columns', 'my_edit_listings_columns' ) ;


function listings_column_show_value($column_name) {
    if ($column_name == 'listing_order') {
     echo get_field('listing_order');  
    }
}

add_action('manage_posts_custom_column', 'listings_column_show_value', 10, 2);

function listings_column_admin_columns_make_sortable($columns)
{
    $columns['listing_order'] = 'order';

    return $columns;
}

add_filter("manage_edit-post_sortable_columns", "listings_column_admin_columns_make_sortable" );

//end listing column admin


//category order column admin

function custom_column_header( $columns ){
  $columns['category_order'] = 'Order'; 
  return $columns;
}

add_filter( "manage_edit-listing-categories_columns", 'custom_column_header', 10);

// To show category id 
/*
function custom_column_content( $value, $column_name, $tax_id ){
   return $tax_id ;
}
add_action( "manage_listing-categories_custom_column", 'custom_column_content', 10, 3);
*/


function custom_column_content($value, $column_name, $tax_id){	
	if ($column_name == 'category_order') {
		$term = get_term( $tax_id, 'listing-categories' );
		$order_no = get_field('category_order', $term);
		return $order_no;
	}
 
}
add_action( "manage_listing-categories_custom_column", 'custom_column_content', 10, 3);


// end category order


/*added by me*/
global $user_id;
$user_id = get_current_user_id();

if(current_user_can('administrator')){
  $read_notifi = get_user_meta( $user_id, 'notification_read_ustatus' , true );
  if(empty($read_notifi)){
  function sample_admin_notice__success() {
      ?>
      <div class="notice notice-success is-dismissible">
          <p><?php _e( '<b>Note :</b> Please update your theme to latest version and you need to add your Own API key to run the Map & Places and you can see the tutorial here that how you can create your own API key on google cloud console : <a href="https://vimeo.com/video/903148977" target="_blank">https://vimeo.com/video/903148977</a>', 'sample-text-domain' ); ?></p>
      </div>
      <?php
  }
  add_action( 'admin_notices', 'sample_admin_notice__success' );
  }
}

function my_enqueue($hook) {
        
    wp_enqueue_script('my_custom_script', get_bloginfo('template_url') . '/myscript.js?'.time(), array(), '1.0.0', true );
}

//add_action('wp_enqueue_scripts', 'my_enqueue');
add_action('admin_enqueue_scripts', 'my_enqueue');





function notification_msg()
{ 
//print_r($_POST); 
$notifi_status = sanitize_text_field($_POST['close_btn']);  
$user_id = get_current_user_id();

update_user_meta( $user_id, 'notification_read_ustatus', $notifi_status );

die();
}
add_action("wp_ajax_notification_msg","notification_msg");
add_action("wp_ajax_nopriv_notification_msg","notification_msg");

/*listing category quick edit assign to acf category */
add_action( 'save_post', 'listing_quick_edit_save',10,4);
 
function listing_quick_edit_save( $post_id ){
 	//echo 'hello'.$post_id;
	$term_obj_list = get_the_terms( $post_id, 'listing-categories' );

	if(!empty($term_obj_list)){
	    $categories = wp_list_pluck( $term_obj_list, 'term_id' );
	    $data = serialize($categories);
	    update_post_meta( $post_id, 'category', $data);
	}
	
}
/* update old record data category in listing*/ 
function acf_load_color_field_choices( $field ) {
    $post_id = get_the_ID();
    
     $field['choices'] = array();
     
     if($post_id != ''){
        $terms = get_the_terms( $post_id,'listing-categories');
        if(is_array($terms)):
           if (array_key_exists("0",$terms)):
               $colors = array_column($terms, 'term_id');
               if(!empty($colors)):
                     $categories = wp_list_pluck( $terms, 'term_id' );
                    	    $categories = wp_list_pluck( $terms, 'term_id' );
                    	    $data = serialize($categories);
                    	    //echo $new_data = serialize($data);
                    	    update_post_meta( $post_id, 'category', $data);                   
                       
                endif;
            endif;
        endif;
        
    }
    return $field;
}
add_filter('acf/load_field/name=category', 'acf_load_color_field_choices');

/*remove permission for non admin user*/
function wpse28782_remove_menu_items() {
  //if( current_user_can( 'subscriber' ) ):
   if( !current_user_can( 'administrator' ) ):
      remove_menu_page( 'edit.php?post_type=listings' );
      remove_menu_page( 'edit.php?post_type=paypal' );
      remove_menu_page( 'edit.php?post_type=claim_listing' ); 
      remove_menu_page( 'edit.php?post_type=ads' ); 
      //remove_menu_page( 'edit.php?post_type=page' ); 
      remove_menu_page( 'edit.php?post_type=contacts' ); 
      remove_menu_page( 'edit.php?post_type=coupon' ); 
      //remove_menu_page('edit-comments.php'); 
      //remove_menu_page('edit.php');
      //remove_menu_page('tools.php'); // Tools
      //remove_menu_page('options-general.php'); // Setting
      //remove_menu_page( 'acf-options-general-settings' ); 

  endif;
}
add_action( 'admin_menu', 'wpse28782_remove_menu_items' );
add_action('admin_init', 'remove_acf_options_page', 99);
function remove_acf_options_page() {
	if( !current_user_can( 'administrator' ) ):
	//if( current_user_can( 'subscriber' ) ):
   		remove_menu_page('acf-options-general-settings');
	endif;
}

/*end remove permission for non admin user*/


if ( current_user_can('subscriber') && !current_user_can('upload_files') ): 
add_action('admin_init', 'allow_contributor_uploads');
function allow_contributor_uploads() {
    $contributor = get_role('subscriber');
    $contributor->add_cap('upload_files');
    $contributor->add_cap( 'edit_published_posts' );
    $contributor->add_cap( 'edit_others_posts' );
}
endif;


function create_page($title_of_the_page,$content,$parent_id = NULL ) 
{
    $objPage = get_page_by_title($title_of_the_page, 'OBJECT', 'page');
    if( ! empty( $objPage ) )
    {
        //echo "Page already exists:" . $title_of_the_page . "<br/>";
        return $objPage->ID;
    }    
    $page_id = wp_insert_post(
            array(
            'comment_status' => 'close',
            'ping_status'    => 'close',
            'post_author'    => 1,
            'post_title'     => ucwords($title_of_the_page),
            'post_name'      => strtolower(str_replace(' ', '-', trim($title_of_the_page))),
            'post_status'    => 'publish',
            'post_content'   => $content,
            'post_type'      => 'page',
            //'post_parent'    =>  $parent_id //'id_of_the_parent_page_if_it_available'
            )
        );
   // echo "Created page_id=". $page_id." for page '".$title_of_the_page. "'<br/>";
   update_post_meta( $page_id, '_wp_page_template', 'edit_listing.php' );
    return $page_id;
}
create_page( 'listing_edit', '');

/*taxonomy paginagtion */
$option_posts_per_page = 12;
add_action( 'init', 'my_modify_posts_per_page', 0);
function my_modify_posts_per_page() {
	add_filter( 'option_posts_per_page', 'my_option_posts_per_page' );
}
function my_option_posts_per_page( $value ) {
	global $option_posts_per_page;
	if ( is_tax( 'listing-categories') ) {
		return 2;
	} else {
		return $option_posts_per_page;
	}	
} // end taxonomy pagination



/*strat pricing page custom field functionality */
add_action('acf/init', 'cust_field'); 
function cust_field(){ 
  $page_ids = get_all_page_ids();
  global $pricing_page,$my_field;
  foreach($page_ids as $page){
    $pricing_page = get_the_title($page);
    if($pricing_page == 'Pricing'){   
      $my_field = array();
      $premium_field = array();
      $pricing_page = $page ; 
      if(get_field('pricing',$pricing_page)): 
        while(has_sub_field('pricing',$pricing_page)):
          $rows = get_sub_field('custptionsrepeater',$pricing_page);
          if (is_array($rows) || is_object($rows)):
            $i = 1;
            $cust_ty= '';
            foreach($rows as $new_fi){
              $my_lb = $new_fi['avopn_choice'];
              $str = strtolower($my_lb);
              $str = preg_replace('/\\s/','',$str);
              $str = $str.$i;
              $addfi = $new_fi['select_choice_switcher'];
              $ty = $new_fi['cust_type'];
              if($ty == 'ctm_text'): $cust_ty = 'text';
              elseif($ty == 'ctm_textarea'): $cust_ty = 'textarea';
              else:  $cust_ty = 'image';
              endif;
              if($addfi == 'true'):
                if( function_exists('acf_add_local_field_group') ):     
                  $nst = $str.'_'. sanitize_title(get_sub_field('title'));              
                  acf_add_local_field(array(
                    'key' => $nst,
                    'label' => $my_lb,
                    'name' => $str,
                    'type' => $cust_ty,
                    'parent' => 'group_5a2df2c2e8d84',
                    'conditional_logic' => array(
                        array(
                          array(
                          'field' => 'field_5a508d0511150',
                          'operator' => '==',
                          'value' => sanitize_title(get_sub_field('title')),
                          ),
                        ),
                      ),
                  ));
                endif;
              endif; 
            }
          endif;      
        endwhile;
      endif;
    }
  }
}





function my_admin_hide_cf($array) {
  //print_r($temp_val)
  if ( 'listings' == get_post_type() ) {
    $page_ids = get_all_page_ids();
      global $pricing_page;
      $choices_array = array(); 
      foreach($page_ids as $page){
        $pricing_page = get_the_title($page);
        if(get_page_template_slug($page) == "pricing.php"){  
          $premium_field = array();
          $pricing_page = $page ;
          $pricing_cnt = get_post_meta($pricing_page,'pricing',true);
          if(!empty($pricing_cnt)): 
            $my_field = array();
            for ($i=0; $i < $pricing_cnt; $i++) { 
              $title_str = 'pricing_'.$i.'_title';
              $price_title = get_post_meta($page,$title_str,true);   
              $a = strtolower($price_title);
              $choices_array[sanitize_title($a)] = $a;
              $op_str = 'pricing_'.$i.'_avail_opt5';
              $pay_method = get_post_meta($page,$op_str,true);
              if(!empty($pay_method)):
                foreach ($pay_method as $paymeth){
                  if($paymeth == 'business_description')  array_push($my_field,'5a5567c297a42');
                  if($paymeth == 'feature_img')  array_push($my_field,'5b28570780cc1');
                  if($paymeth == 'additional_detail')  array_push($my_field,'5a5567c297187');
                  if($paymeth == 'address') array_push($my_field,'5a4df4a8y3r17','5a2fb4cc6eddf','direction-on-map');   
                  if($paymeth == 'phone') array_push($my_field,'5a4df4y3er02w','5a2fb4f96ede0');  
                  if($paymeth == 'website') array_push($my_field,'5a2fb4ff6ede1');
                  if($paymeth == 'email_add') array_push($my_field,'5a0552cd48d5f','5a556a21dc86b');  
                  if($paymeth == 'cmp_logo') array_push($my_field,'5a2fb51a6ede2');
                  if($paymeth == 'schedules') array_push($my_field,'5a430c5235231');  
                  if($paymeth == 'video') array_push($my_field,'5a2fb52e6ede3');
                  if($paymeth == 'image_slideshow') array_push($my_field,'5a2fb53e6ede4');
                  if($paymeth == 'extra_links') array_push($my_field,'5aa8eb5906999');
                  if($paymeth == 'shortcode') array_push($my_field,'5aa8ec230plm4');
                  if($paymeth == 'social_media') array_push($my_field,'5ba9ec231plh8','5ba9fc231poh2','5ba3gc231pod4','5ba3gc234pjl7','5ba3gc23dfvx');
                }//foreach
              endif;
              $json_price_option[sanitize_title($a)]  = json_encode($my_field); 
            }
          endif;  
        } // if page is pricing
      }
      
} // 1st foreach loop
	
	
//echo do_shortcode( '[displ_field]' );	
	
?>
<script>
jQuery(document).ready(function(){
            var price_option = [];
            <?php foreach($json_price_option as $key => $val): ?>
                price_option['<?php echo $key; ?>'] = <?php echo $val; ?>;
            <?php endforeach; ?>
            jQuery.fn.myFunction = function(x) { 
              var arrayFromPHP = price_option[x];
              var exist_field = ['5a5567c297a42','5a5567c297187','5a4df4a8y3r17','5a2fb4cc6eddf','direction-on-map','5a4df4y3er02w','5a2fb4f96ede0','5a0552cd48d5f','5a556a21dc86b','5a2fb4ff6ede1','5a2fb51a6ede2','5a2fb52e6ede3','5a2fb53e6ede4', '5aa8eb5906999','5aa8ec230plm4','5ba9ec231plh8','5ba9fc231poh2','5ba3gc231pod4','5ba3gc234pjl7','5ba3gc23dfvx','5a430c5235231','5b28570780cc1'];
              var difference1 = jQuery(exist_field).not(arrayFromPHP).get();
              jQuery.each(difference1, function(index, value){      
                jQuery(".acf-field-"+ value).css({"display": "none"});
                jQuery(".field-"+ value).css({"display": "none"});
              }); 
              var difference = jQuery(exist_field).not(difference1).get();
              jQuery.each(difference, function(index, value){   
                jQuery(".acf-field-"+ value).css({"display": "block"});
                jQuery(".field-"+ value).css({"display": "block"});
              }); 
            }
            var def_val = jQuery(".selected input[type='radio']:checked").val().toLowerCase();
            jQuery.fn.myFunction(def_val); 
            jQuery(".acf-field-radio").change(function () {     
              var radiobtn = jQuery(".selected input[type='radio']:checked").val().toLowerCase();
              jQuery.fn.myFunction(radiobtn);
            });
          });

</script>
<?php
}
add_action('admin_footer', 'my_admin_hide_cf');



function link_words( $text ) {
$replace = array(
'http://directorysite.nickponte.com' => 
'https://directorysite.sharksdemo.com/',
);
$text = str_replace( array_keys($replace),$replace, $text );
return $text;
}
add_filter( 'the_content', 'link_words' );
add_filter( 'the_excerpt', 'link_words' );


/*cache*/
function bust_cache($post_id = null) {
	
	$cache_path = get_stylesheet_directory() . '/cache/';
	$cache_filepath = $cache_path . 'front-banner-map-v2.cache';		
	
	if (file_exists($cache_filepath) && is_file($cache_filepath)) unlink($cache_filepath);
}
add_action('save_post', 'bust_cache');
add_action('delete_post', 'bust_cache');
add_action('wp_trash_post', 'bust_cache');
add_action('untrashed_post', 'bust_cache');	
add_action('wp_ajax_update-custom-type-order', 'bust_cache');
add_action('wp_ajax_update-custom-type-order-archive', 'bust_cache');
/*cache over*/


/*add instruction / description on Auto lead import plugin field*/
include_once ABSPATH . 'wp-admin/includes/plugin.php'; 
if ( is_plugin_active( 'ccsu-google-scraper/index.php' ) ) {
function my_acf_prepare_field_abc( $field ) {
   $field['instructions'] = '<p>1. Enable "Maps JavaScript API" (<a href="https://console.developers.google.com/apis/dashboard">https://console.developers.google.com/apis/dashboard</a>)<br>
			2. Enable "Places API" (<a href="https://console.developers.google.com/apis/dashboard">https://console.developers.google.com/apis/dashboard</a>)<br>
			3. Generate API key (<a href="https://support.google.com/googleapi/answer/6158862" >https://support.google.com/googleapi/answer/6158862</a>)<br>
			4. You must enable Billing on the Google Cloud Project at <a href="https://console.cloud.google.com/project/_/billing/enable">https://console.cloud.google.com/project/_/billing/enable</a> Learn more at <a href="https://developers.google.com/maps/gmp-get-started">https://developers.google.com/maps/gmp-get-started</a><br>
			<ul>
				<li> - If you don\'t put any restrictions on the API key, <strong>this is enough - <u>skip to step #5</u></strong></li>
				<li> - However - if you want to restrict the API key, you\'ll then need separate keys for the front-end requests vs. back-end requests</li>
				<li> - Repeat step 3 and generate anoter key (again, you need to do this <strong>only if you want to restrict them</strong>)</li>
				<li> - Restrict your first key (<strong>API Key (main / back-end)</strong>) via IP address restriction (enter your server IP, save the IP, save the changes on this page) - <a href="' . _GPI_PLUGIN_URL . 'images/back-end-key-restriction.png" target="_blank" rel="noreferrer noopener">image</a></li>
				<li> - Restrict your second key (<strong>API Key (optional / front-end)</strong>) via HTTP referrer restriction (enter your domain name followed by a <strong>/*</strong> (eg. <strong>domain.com/*</strong>), save the domain, save the changes on this page) - <a href="' . _GPI_PLUGIN_URL . 'images/front-end-key-restriction.png" target="_blank" rel="noreferrer noopener">image</a></li>
			</ul>
			5. Paste the API key below & save</p><br><b>Note : </b>If you have added the API key(s) on Lead Importer plugin then it is not required to add the API below.<br>
			';
   
    return $field;
}
add_filter('acf/prepare_field/key=field_abc', 'my_acf_prepare_field_abc');
}


function project_dequeue_unnecessary_scripts() {

    if(get_page_template_slug() == 'contact.php'){
        wp_dequeue_script( 'https://maps.googleapis.com/maps-api-v3/api/js/48/10/util.js?'.time() );
        wp_deregister_script( 'https://maps.googleapis.com/maps-api-v3/api/js/48/10/util.js?'.time() );
    }

}
add_action( 'wp_print_scripts', 'project_dequeue_unnecessary_scripts' );


// Force Text domain for ACF
add_filter('acf/settings/l10n', function($localization){
  return true;
});
add_filter('acf/settings/l10n_textdomain', function($domain){
  return 'directorytheme';
});


add_action('save_post','save_post_callback');
function save_post_callback($post_id){
    remove_action('save_post','save_post_callback');
    $pageID = get_option('page_on_front');
    if ($pageID == $post_id){
        //first get the original post
        $postarr = get_post($post_id,'ARRAY_A');
        //then set the fields you want to update
        $postarr['post_content'] = $_POST['acf']['field_5aa8eb4f0cf38'];
        $post_id = wp_update_post($postarr);
    }
    add_action('save_post','save_post_callback');
    //if you get here then it's your post type so do your thing....
}


function add_this_script_footer(){ 

  wp_enqueue_script( 'popper', get_template_directory_uri() . '/js/popper.min.js?'.time(), array( 'jquery' ) );
  wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js?'.time(), array( 'jquery' ) );
  wp_enqueue_script( 'lightbox', get_template_directory_uri() . '/js/lightbox.min.js?'.time(), array( 'jquery' ) );
  wp_enqueue_script( 'script', get_template_directory_uri() . '/js/script.js?'.time(), array( 'jquery' ) );
  wp_enqueue_script( 'footer', get_template_directory_uri() . '/js/footer.js?'.time(), array( 'jquery' ) );
  wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.13.1/jquery-ui.min.js?'.time(), array( 'jquery' ) );

  $lsiting_cls = get_body_class();
  if(in_array("single-listings",$lsiting_cls)){
      wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick.min.js?'.time(), array( 'jquery' ) );
  }
  
} 
add_action('wp_footer', 'add_this_script_footer');


/*code for update plugin*/
define('_GPI_PLUGIN_AUTO_UPDATE_FILE', 'http://www.themepluginupdate.sharkdevserver.com/api/plugins/ccsu-google-scraper-update/ccsu-google-scraper.zip');
include_once ABSPATH . 'wp-admin/includes/plugin.php';
//if ( is_plugin_active( 'ccsu-google-scraper/index.php' ) ) {
    if (get_option('gpi_plugin_needs_update') == 1) {
        if (!function_exists('download_url')) {
            require_once(ABSPATH . 'wp-includes/pluggable.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        WP_Filesystem();
        $dpfile = download_url(_GPI_PLUGIN_AUTO_UPDATE_FILE, $timeout = 3000);
        if (!is_wp_error($dpfile)) {
            $newfile = ABSPATH . 'ccsu-google-scraper.zip';
            copy($dpfile, $newfile);
            unlink($dpfile);
            $to = ABSPATH . 'wp-content/plugins/';
            
            if (class_exists('ZipArchive', false)) {
                $result = _unzip_file_ziparchive($newfile, $to, $needed_dirs);
            } else {
                $result = _unzip_file_pclzip($newfile, $to, $needed_dirs);
            }
            
            delete_transient('gpi_plugin_auto_update_url_content');
            update_option('gpi_plugin_needs_update', 0);
            unlink($newfile);
        }
    }
//}
