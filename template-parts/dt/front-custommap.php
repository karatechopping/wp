<?php
	/* If admin select custom map option of banner for home page from backend.
		Screenshort : https://prnt.sc/1voe060 */
	$cusmapadd=get_field('cm_address','option');
	if(!empty($cusmapadd)):
		$cusmapzoom=get_field('cm_zoom_level','option');
		$cmadd=$cusmapadd['address'];
		$lat = $cusmapadd['lat'];
		$lng = $cusmapadd['lng'];
		$all_listings = get_posts( array('numberposts' => 10, 'post_type'   => 'listings', 'post_status' => 'publish') );
	else : 
		$cusmapzoom='6';
		$cmadd='USA';
		$lat = '37.090240';
		$lng = '-95.712891';
	endif;
?>
	<div id="datafetch" style="position: relative;">
		<div id="map" style="width: 100%; height: 400px;"></div>
		<script>
			function initMap() {
				var locations = [
					<?php
	    			$single_lat =$lat;
	    			$single_lng =$lng;
	     		?>      
  			];

  			var map = new google.maps.Map(document.getElementById('map'), {
    			zoom: <?php if($cusmapzoom != ''){echo $cusmapzoom;}else{echo '15';}; ?>,
    			center: new google.maps.LatLng(<?= $lat; ?>, <?= $lng; ?>),
    			mapTypeId: google.maps.MapTypeId.ROADMAP
  			});

  			var infowindow = new google.maps.InfoWindow();
  			var marker, i;

	      marker = new google.maps.Marker({
	        position: new google.maps.LatLng(<?= $lat; ?>, <?= $lng; ?>),
	        map: map
	      });

	      google.maps.event.addListener(marker, 'click', (function(marker, i) {
	        return function() {
	          infowindow.setContent(locations[i][0]);
	          infowindow.open(map, marker);
	        }
	      })(marker, i));
			}
		</script>
		<?php 
     	$fetch_plugin_api_key = get_option( 'gpi_plugin_global_settings' );
      if(!empty($fetch_plugin_api_key )) $plugin_api_key =  $fetch_plugin_api_key["api_key"];
      $cmnapikey=get_field('googlemapcommonapikey','option');
	
			if (isset($fetch_plugin_api_key["api_key_front_end"]) && !empty($fetch_plugin_api_key["api_key_front_end"])) $plugin_api_key = $fetch_plugin_api_key["api_key_front_end"];
			$cmnapikey_front_end=get_field('googlemapcommonapikey_frontend','option');
			if (!empty($cmnapikey_front_end)) $cmnapikey = $cmnapikey_front_end;
    ?>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php if(!empty($plugin_api_key)){ echo $plugin_api_key; } else { echo $cmnapikey; } ?>&callback=initMap"></script>
	</div>	