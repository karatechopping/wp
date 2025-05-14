<?php
	/* If admin select map option of banner of home page from backend.
		Screenshort : https://prnt.sc/1vodnma */
	$cache_time = DAY_IN_SECONDS;
	$cache_path = get_stylesheet_directory() . '/cache/';
	/* check theme update*/
	$cache_filepath = $cache_path . 'front-banner-map-v2.cache';
	if (file_exists($cache_filepath) && filemtime($cache_filepath)) {
		$my_theme 	   = wp_get_theme();
		$theme_version = $my_theme->Version;
		add_option( 'my_theme_version', $theme_version, '', 'yes');

		$old_version = get_option( 'my_theme_version' );
		$addNew 	 = true;
		if($theme_version >= $old_version && $addNew){
			if (file_exists($cache_filepath) && is_file($cache_filepath)) unlink($cache_filepath);
			$addNew = false;
		}
	}
	/* end code theme update */

	if (!file_exists($cache_path) && !is_dir($cache_path)) {
		$mask = @umask(0);
		@mkdir($cache_path, 0644);
		@umask($mask);

		/* check client change API key or not */
		$old_api_key = get_option( 'old_api_key' );
		if(empty($old_api_key)) {
			if (file_exists($cache_filepath) && is_file($cache_filepath)) unlink($cache_filepath);
		}else{
			$fetch_plugin_api_key  = get_option( 'gpi_plugin_global_settings' );
			$plugin_api_key 	   =  $fetch_plugin_api_key["api_key"];
			if(!empty($plugin_api_key)) $api_key=$plugin_api_key;
			else{
				$cmnapikey=get_field('googlemapcommonapikey','option');
				$api_key = $cmnapikey;
			}
			if($old_api_key != $api_key){
				if (file_exists($cache_filepath) && is_file($cache_filepath)) unlink($cache_filepath);
			}
		}
		/* end code check client change API key */
	}
	$cache_filepath = $cache_path . 'front-banner-map-v2.cache';
	if (file_exists($cache_filepath) && filemtime($cache_filepath) > time() - $cache_time) {
		$html = file_get_contents($cache_filepath);
		echo $html;
		echo '<!--- FROM CACHE ---->';
	} else {
		ob_start();

		$fetch_plugin_api_key = get_option( 'gpi_plugin_global_settings' );
		if(!empty($fetch_plugin_api_key )) $plugin_api_key =  $fetch_plugin_api_key["api_key"];

		if(!empty($plugin_api_key)){
			$api_key=$plugin_api_key;
		}
		else{
			$cmnapikey = get_field('googlemapcommonapikey','option');
			$api_key   = $cmnapikey;
		}
		add_option( 'old_api_key', $api_key, '', 'yes' );

	if (isset($fetch_plugin_api_key["api_key_front_end"]) && !empty($fetch_plugin_api_key["api_key_front_end"])) $plugin_api_key = $api_key = $fetch_plugin_api_key["api_key_front_end"];
		$cmnapikey_front_end = get_field('googlemapcommonapikey_frontend','option');
	if (!empty($cmnapikey_front_end)) $cmnapikey = $api_key = $cmnapikey_front_end;
?>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php if(!empty($plugin_api_key)){ echo $plugin_api_key; } else { echo $cmnapikey; } ?>"></script> <!-- &libraries=places -->
<?php
	$the_query_map = new WP_Query( array(
		'post_type' => 'listings',
		'posts_per_page' => -1,
		'order' => 'ASC',
		'orderby' => 'menu_order'
	));
	if($the_query_map->have_posts()) :
		while($the_query_map->have_posts()):
			$the_query_map->the_post();
			$the_ID = get_the_ID();
			$get_google_map = get_field('address');
			$title = get_the_title();
			$icon = get_field('category');
			$hideadd = get_field('hide_add_swtchr');
			$dpath = get_template_directory_uri();
			if(!empty($icon) && is_array($icon) || is_object($icon)):
				foreach($icon as $icons):
					if(!empty($icons->name) && $icons->name == "Restaurant"){
						$icon = ''.$dpath.'/images/icon-marker-3.png';
					}
					elseif(!empty($icons->name) && $icons->name == "Hotel"){
						$icon = ''.$dpath.'/images/icon-marker-2.png';
					}
					elseif(!empty($icons->name) && $icons->name == "Coffee"){
						$icon = ''.$dpath.'/images/icon-marker-1.png';
					}
				endforeach;
			endif;

			$mapimg = get_the_post_thumbnail_url(get_the_ID(),'img_1000x600');
			if(empty($mapimg)):
				$mapimg = get_field('s_default_featured_image','option');
			endif;

			$permalink = get_the_permalink();
			$mapwebsite = get_field('website');
			if(!empty($get_google_map)){
				if($hideadd != '1'){
					$output_map[$the_ID]['address'] = '<div class="marker" data-lat="'.$get_google_map['lat'].'" data-lng="'.$get_google_map['lng'].'" data-title="'.$title.'" data-img="'.$mapimg.'" data-link="'.$permalink.'" data-url="'.$mapwebsite.'">&nbsp;</div>';
				}
			}
		endwhile;
	endif;
	wp_reset_postdata();

	if(!empty($output_map)) {
		echo '<div id="datafetch" style="position: relative;">';
			echo '<div class="acf-map">';
				foreach( $output_map as $key => $map_marker ):
					echo $map_marker['address'];
				endforeach;
			echo '</div><!--acf-map-banner-->';
			echo '<div id="loading-icon"></div><!--loading-->';
		echo '</div><!--datafetch-->';
	}
	else {
		include 'front-custommap.php';
	}
?>

<section class="listing_search">
	<div class="inner">
		<form id="listing_form">
			<?php get_template_part( 'template-parts/dt/front', 'search-listing' ); ?>
		</form>
	</div><!--inner-->
</section>
<!--- strat here code of searching with section---->
<script>
jQuery( "form#listing_form .row .no_res_found p").hide();
</script>
<?php
	$html = ob_get_contents();
	$a 	  = file_put_contents ( $cache_filepath, $html );
	chmod( $cache_filepath, 0644 );
	echo '<!--- NO CACHE ---->';
}
?>
