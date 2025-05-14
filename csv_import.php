<?php
/*-------------------------------------------------------------------------------
   Import Listing
-------------------------------------------------------------------------------*/
add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>';
  echo '.listings_page_csv-import .listing_imp_div{margin : 50px}';
  echo '.margin_lr{margin : 0px 50px;}';
  echo 'input[type=file] {font-size: 15px;margin-bottom: 15px;}';
  echo '.imp_btn {color: #fff;
  background-color: #3073aa;
  border-color: #3073aa;
  user-select: none;
  border: 1px solid transparent;
  padding: .5rem .75rem;
  font-size: 1rem;
  line-height: 1.25;
  border-radius: .25rem;
  transition: all .15s ease-in-out;}';
  echo '</style>';
}

function example_insert_category() {

	$term = term_exists( 'Uncategorized', 'listing-categories' );
	if ( $term !== 0 && $term !== null ) {
		$new_cat = print_r($term['term_id'],true);
	} else {
		$term = wp_insert_term('Uncategorized','listing-categories',array('description' => '','slug'    => 'uncategorized') );
		if ( $term !== 0 && $term !== null ) {
			$new_cat = print_r($term['term_id'],true);
		}
	}
}

function import_csv_form($new_cat) {

	global $choices_array1;

	echo '<div class="listing_imp_div">';
	_e( '<h1>Import</h1>', 'directorytheme');
	_e( '<p>Howdy! Upload your Listing CSV file and we’ll import the Listing into this site.</p>', 'directorytheme');
	_e( '<p>Choose a CSV (.csv) file to upload, then click Upload button.</p>', 'directorytheme');
	_e( '<p><strong>Note : </strong>You can only upload the CSV file which are exported through export CSV option & CSV format should be same which are created by export option.</p>', 'directorytheme');
	echo '<form action="" method="post" enctype="multipart/form-data">';
	echo '<div class="form-group">';
	echo '<input type="file" name="csv_file" class="imp_file">';
	echo '</div>';
	echo '<input type="submit" name="submit" value="Upload" class="imp_btn">';
	echo '</form>';
	echo '</div>';

	if (isset($_POST['submit'])){

		if (isset($_FILES['csv_file']['size'])) {

			if ($_FILES['csv_file']['size'] > 0) {

				$filename = $_FILES['csv_file']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);

				if($ext == 'csv') {

					global $new_cat;
					$csv_file = $_FILES['csv_file'];
					$csv_to_array = array_map('str_getcsv', file($csv_file['tmp_name']));

					foreach ($csv_to_array as $key => $value) {

						if ($key == 0) {
							$b = $value;
							continue;
						}
						$a = array_combine($b, $value);
						if (!array_key_exists("listing_type",$a)) {
							echo "<h2 class='listing_imp_div' style='color : red;margin-top: 15px;'>Please Upload Valid CSV File.</h2><br/>";
							exit();
						}
						else {
							/* taxonomy category*/
							$cat = $a['post_category'];

							$arr = array();
							if(!empty($cat)) {
								$post_cat = explode(",",$cat);
								if(is_array($post_cat)):
									foreach($post_cat as $new_cate) :

										$term = term_exists($new_cate, 'listing-categories');
										if ($term !== 0 && $term !== null) {
											$new_cat = print_r($term['term_id'], true);
											array_push($arr,$new_cat);
										} else {
											$term = wp_insert_term($new_cate, 'listing-categories', array(
												'description' => '',
												'slug' => $new_cate
											));

											if ($term !== 0 && $term !== null) {
												$new_cat = print_r($term['term_id'], true);
												array_push($arr,$new_cat);
											}
										}
									endforeach;
								endif;
							} else {
							$term = term_exists('Uncategorized', 'listing-categories');
							if ($term !== 0 && $term !== null) {
								$new_cat = print_r($term['term_id'], true);
							} else {
								$term = wp_insert_term('Uncategorized', 'listing-categories', array(
									'description' => '',
									'slug' => 'uncategorized'
								));
								if ($term !== 0 && $term !== null) {
									$new_cat = print_r($term['term_id'], true);
								}
							}
						}

						$address = $a['address'];
						$prepAddr = str_replace(' ','+',$address);
						$apiKey = GooglePlacesImporter::get_plugin_option('api_key');
						$fetch_plugin_api_key = get_option( 'gpi_plugin_global_settings' );
						$plugin_api_key =  $fetch_plugin_api_key["api_key"];

						if(!empty($plugin_api_key)) $apiKey=$plugin_api_key;
						else {
							$cmnapikey=get_field('googlemapcommonapikey','option');
							$apiKey = $cmnapikey;
						}

						if(empty($a['lat']) && empty($a['lng']) && !empty($address)){
							$geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&key='.$apiKey);
							$output= json_decode($geocode);
							$latitude = $output->results[0]->geometry->location->lat;
							$longitude = $output->results[0]->geometry->location->lng;
						}else{
							$latitude = $a['lat'];
							$longitude = $a['lng'];
						}
						$merge_add = array(
							'address' => $a['address'],
							'lat' => $latitude,
							'lng' => $longitude
						);
						$user_id = get_current_user_id();

						if (!is_admin()) require_once (ABSPATH . 'wp-admin/includes/post.php');

						if ($a['post_title'] != '') {

							$fount_post = post_exists($a['post_title'], '', '', 'listings');
							echo $fount_post ? "<p class='margin_lr'>Already Listing exists at id : $fount_post</p>" : "<p class='margin_lr'></p>";
							if ($fount_post == '' || $fount_post == 0){
								$user_id = get_current_user_id();
								$post_id = wp_insert_post(array(
									'post_type' => 'listings',
									'post_content'  => '',
									'post_title' => $a['post_title'],
									'post_status' => 'publish',
									'comment_status' => 'open',
									'ping_status' => 'closed',
									'post_author' => $user_id
								));
							} else {
								$post_id = $fount_post;
							}

							$website = '';
							$phone = '';
							$email = '';
							$video = '';
							$facebook_link = '';
							$twitter_link = '';
							$instagram_link = '';
							$linkedin_link = '';

							if(isset($a['website'])){	$website = $a['website'];	}
							if(isset($a['phone'])){  $phone = $a['phone'];	}
							if(isset($a['email_address'])){  $email = $a['email_address'];		}
							if(isset($a['video'])){  $video = $a['video'];		}
							if(isset($a['facebook_link'])){  $facebook_link = $a['facebook_link'];	}
							if(isset($a['twitter_link'])){  $twitter_link = $a['twitter_link'];	}
							if(isset($a['instagram_link'])){  $instagram_link = $a['instagram_link'];	}
							if(isset($a['linkedin_link'])){  $linkedin_link = $a['linkedin_link'];		}

							if(in_array($a['listing_type'],$choices_array1)){
								$str = $a['listing_type'];	
							}else{
								$str = $choices_array1[0];
							}
							//$str = $a['listing_type'];
							$a = explode(",", $str);
							//create new category
							$tag = array( $new_cat ); // Correct. This will add the tag with the id 5.
							$taxonomy = 'listing-categories';

							if(!empty($arr)){
								wp_set_post_terms($post_id, $arr, $taxonomy);
								$term_obj_list = get_the_terms( $post_id, 'listing-categories' );
								if(!empty($term_obj_list)){
									$categories = wp_list_pluck( $term_obj_list, 'term_id' );
									$data = serialize($categories);
									update_post_meta( $post_id, 'category', $data);
								}
							}
							else {
								wp_set_post_terms($post_id, $tag, $taxonomy);
								update_post_meta($post_id, 'category', $new_cat);
								update_post_meta($post_id, '_category', 'field_5a2fb5a20bb13');                      }
								update_post_meta($post_id, 'listing_type', $str);
								update_post_meta($post_id, 'website', $website);
								update_post_meta($post_id, 'phone', $phone);
								update_post_meta($post_id, 'address',$merge_add);
								update_post_meta($post_id, 'email_address',$email);

								if($video != ''):
									update_post_meta($post_id, '_video','field_5a2fb52e6ede3');
									update_post_meta($post_id, 'video',$video);
								endif;
								if($facebook_link != ''):
									update_post_meta($post_id, '_facebook_link','facebook_link');
									update_post_meta($post_id, 'facebook_link',$facebook_link);
								endif;
								if($twitter_link != ''):
									update_post_meta($post_id, '_twitter_link','twitter_link');
									update_post_meta($post_id, 'twitter_link', $twitter_link);
								endif;
								if($instagram_link != ''):
									update_post_meta($post_id, '_instagram_link','instagram_link');
									update_post_meta($post_id, 'instagram_link',$instagram_link);
								endif;
								if($linkedin_link != ''):
									update_post_meta($post_id, '_linkedin_link','linkedin_link');
									update_post_meta($post_id, 'linkedin_link',$linkedin_link);
								endif;
							}
						} //key exists
					}//END foreach
				echo "<h2 class='listing_imp_div' style='color : green;margin-top: 15px;'>Success! Upload Listing Done.</h2>";
			}
			else {
				echo "<h2 class='listing_imp_div' style='color : red;margin-top: 15px;'>Sorry, only CSV file allowed.</h2><br/>";
			}
			}
			else {
				echo "<h2 class='listing_imp_div' style='color : red;margin-top: 15px;'>No file was found for the upload.</h2><br/>";
			}
		} //$_FILES['csv_file']['size'])

	}// END $_POST['submit']

}// END import_csv_form function

add_shortcode('import_csv_form', 'import_csv_form');

/*add submenu in cpt*/

add_action('admin_menu', 'listing_import');

function listing_import() {
    add_submenu_page('edit.php?post_type=listings', 'CSV Import', 'CSV Import', 'manage_options', 'csv-import', 'listing_import_main');
}

function listing_import_main() {
    echo do_shortcode('[import_csv_form]');
}

