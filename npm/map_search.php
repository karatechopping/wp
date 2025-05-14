<?php
/* Search listing using map on home page. */
// add the ajax fetch js
add_action( 'wp_footer', 'ajax_fetch' );
function ajax_fetch() {
	global $zoom_level, $wp;
	$get_zoom_level = get_option( 'options_cm_zoom_level');
	$zoom_level = $get_zoom_level;
	if($zoom_level == ''){
    	$zoom_level= 10;
	}
	$c_url  = home_url($wp->request);
	$url_part = explode("/",$c_url);
	if(in_array("dt_listing",$url_part)){
		$zoom_level = get_field('default_zoom_map','option');
		if($zoom_level == ''){
        	$zoom_level= 10;
    	}
	}else{
    	$lsiting_cls = get_body_class();
    	if(in_array("single-listings",$lsiting_cls)):
        	$zoom_level = get_field('default_zoom_map','option');
        	if($zoom_level == ''){
            	$zoom_level= 10;
        	}
    	endif;
	}
?>
	<script type="text/javascript">
		var window_size = jQuery(window).width();
		if(window_size <= 991){
			jQuery('form.listing_form').attr('id','home_search');
			var url = "<?php echo get_site_url();?>/listings/";
			jQuery('form.listing_form').attr('action',url);
		}
		jQuery(window).resize(function() {
			var window_size = window.innerWidth;
			if(window_size <= 991){
				jQuery('form.listing_form').attr('id','home_search');
				var url = "<?php echo get_site_url();?>/listings/";
				jQuery('form.listing_form').attr('action',url);
			}else{
				jQuery('form.listing_form').attr('id','listing_form');
				jQuery('form.listing_form').removeAttr('action');
			}
		});

		
		var seen = {};
		jQuery('.dropdown-address').each(function() {
    		var txt = jQuery(this).text();
    		if (seen[txt])
        		jQuery(this).remove();
    		else
        		seen[txt] = true;
		});
		jQuery("select#listing_category").change(function(){
        	var scattext = jQuery(this).children("option:selected").val();
       		jQuery(this).parent().parent().find('#listing_category').val(scattext);	
	    	jQuery(this).parent().parent().find('button.btn').text(scattext);        		
		});
		jQuery('.dropdown-address').click(function(){
			var addresstext = jQuery(this).text();
			jQuery(this).parent().parent().find('#listing_address').val(addresstext);
			jQuery(this).parent().parent().find('button.btn').text(addresstext);
		});
		jQuery('#listing_form button.reset-btn').click(function(){
			jQuery('#listing_form .form-control').val("");
			jQuery('#listing_form #listing_category').val("Search by: Category");
			jQuery('#listing_category').val("").change();
			jQuery('#listing_form #dropdown_search').text("Search by: Address");
			jQuery('#listing_address').val("");
		});
		jQuery("#listing_form").submit(function(event) { 
			// stop the form from submitting the normal way and refreshing the page
    		event.preventDefault();
    		var my_listing = jQuery('#listing_keyword').val();
    		var listing_address = jQuery('#listing_address').val();
    		var listing_category = jQuery('#listing_category').val();
    		my_listing = my_listing.replace(/[^a-z0-9\s]/gi, ' ').replace(/[_\s]/g, ' ');
    		jQuery('#loading-icon,button .s-spinner').show();	    
    		jQuery.ajax({
        		url: '<?php echo admin_url('admin-ajax.php'); ?>',
        		type: 'post',
        		data: { action: 'data_fetch', listing_category : listing_category, listing_keyword : my_listing, listing_address : listing_address },
        		success: function(data) {
            		jQuery('#datafetch').html( data );
            		(function($) {
						function new_map( $el ) {
							// var
							var $markers = $el.find('.marker');
							//style
							var styles = [{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#747474"},{"lightness":"23"}]},{"featureType":"poi.attraction","elementType":"geometry.fill","stylers":[{"color":"#f38eb0"}]},{"featureType":"poi.government","elementType":"geometry.fill","stylers":[{"color":"#ced7db"}]},{"featureType":"poi.medical","elementType":"geometry.fill","stylers":[{"color":"#ffa5a8"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#c7e5c8"}]},{"featureType":"poi.place_of_worship","elementType":"geometry.fill","stylers":[{"color":"#d6cbc7"}]},{"featureType":"poi.school","elementType":"geometry.fill","stylers":[{"color":"#c4c9e8"}]},{"featureType":"poi.sports_complex","elementType":"geometry.fill","stylers":[{"color":"#b1eaf1"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":"100"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"},{"lightness":"100"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffd4a5"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffe9d2"}]},{"featureType":"road.local","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"weight":"3.00"}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"weight":"0.30"}]},{"featureType":"road.local","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#747474"},{"lightness":"36"}]},{"featureType":"road.local","elementType":"labels.text.stroke","stylers":[{"color":"#e9e5dc"},{"lightness":"30"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"visibility":"on"},{"lightness":"100"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#d2e7f7"}]}];
							//end style	
							// vars
							var args = {
								zoom		: <?= $zoom_level; ?>,
								center		: new google.maps.LatLng(0, 0),
								mapTypeId	: google.maps.MapTypeId.ROADMAP,
								styles: styles
							};
							// create map	        	
							var map = new google.maps.Map( $el[0], args);
							// add a markers reference
							map.markers = [];
							// add markers
							$markers.each(function(){
								add_marker( $(this), map );
							});
							// center map
							center_map( map );
							// return
							return map;
						}
						function add_marker( $marker, map ) {
							// var
							var allInfowWindows=[];
							var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
							var icon = $marker.attr('data-icon');
							// create marker
							var marker = new google.maps.Marker({
								position	: latlng,
								map			: map
						  	});
							// add to array
							map.markers.push( marker );
							// if marker contains HTML, add it to an infoWindow
							if( $marker.html() ){
								var maptitle = $marker.attr('data-title');
			  					var mapimg = $marker.attr('data-img');
			  					var maplink = $marker.attr('data-link');
			  					var mapurl = $marker.attr('data-url');
			  					var content = '<div id="map-container">' + '<div class="map-content">' + '<a href="'+maplink+'"><img data-src="'+mapimg+'" class="img-fluid"></a>' + '<div class="map-title">'+maptitle+' <span>'+mapurl+'</span><a href="'+maplink+'" class="map-btn">View Listing <i class="fa fa-angle-right"></i></a></div>' + '</div>' + '</div>';
					  			var infowindow = new google.maps.InfoWindow({
					    			content: content,
					    			maxWidth: 250
					  			});
					  			//allInfowWindows = [];
								allInfowWindows.push(infowindow);
								// show info window when marker is clicked
								var window_size = jQuery(window).width();
								if(window_size >= 1024){
									<?php 
										if(!empty(get_field('map_pin_popup_switcher','option'))):
										?>
											google.maps.event.addListener(marker, 'click', function() {
												for (var i=0;i<allInfowWindows.length;i++) {
							    					allInfowWindows[i].close();
							  					}
												infowindow.setContent( infowindow.content.replace("data-src", "src") );
												infowindow.open( map, marker );
											});
										<?php 
										else:
										?>
										google.maps.event.addListener(marker, 'mouseover', function() {
											for (var i=0;i<allInfowWindows.length;i++) {
						    					allInfowWindows[i].close();
						  					}
											infowindow.setContent( infowindow.content.replace("data-src", "src") );
											infowindow.open( map, marker );
										});		
										<?php
										endif;
									?>
									jQuery(document).on('mouseleave','.gm-style-iw',function(){
										if(jQuery(".map-content").length>0) infowindow.close();
									});
								}else{
									google.maps.event.addListener(marker, 'click', function() {
										for (var i=0;i<allInfowWindows.length;i++) {
					    					allInfowWindows[i].close();
					  					}
										infowindow.setContent( infowindow.content.replace("data-src", "src") );
										infowindow.open( map, marker );
									});
									jQuery(document).on('mouseleave','.gm-style-iw',function(){
										if(jQuery(".map-content").length>0) infowindow.close();
									});
								}
								//custom map
								google.maps.event.addListener(infowindow, 'domready', function() {			
					    			var iwOuter = $('.gm-style-iw');
								    var iwBackground = iwOuter.prev();
									iwOuter.css({'top':'27px'});
								    iwBackground.children(':nth-child(2)').css({'display' : 'none'});
								    iwBackground.children(':nth-child(4)').css({'display' : 'none'});
								    iwOuter.parent().parent().css({left: '65px'});
								    iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'left: 76px !important;'});
								    iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'left: 76px !important;'});
								    iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'none', 'z-index' : '1'});
								    var iwCloseBtn = iwOuter.next();
								    iwCloseBtn.css({opacity: '1', right: '55px', top: '45px', border: '7px solid #fff', 'border-radius': '50%', 'box-shadow': '0 1px 2px rgba(0,0,0,.3)','width': '27px','height': '27px','cursor':'pointer'});
								    iwCloseBtn.mouseout(function(){
					      				$(this).css({opacity: '1'});
					    			});
									iwCloseBtn.click(function(){
						  				$(this).prev().parent().fadeOut();
					      			});
					  			});
								//custom map
							}
						}
						/*
						*  center_map
						*
						*  This function will center the map, showing all markers attached to this map
						*
						*  @type	function
						*  @date	8/11/2013
						*  @since	4.3.0
						*
						*  @param	map (Google Map object)
						*  @return	n/a
						*/
						function center_map( map ) {
							// vars
							var bounds = new google.maps.LatLngBounds();
							// loop through all markers and create bounds
							$.each( map.markers, function( i, marker ){
								var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
								bounds.extend( latlng );
								map.panTo(marker.position);
							});
							// only 1 marker?
							if( map.markers.length == 1 ){
								// set center of map
						    	map.setCenter( bounds.getCenter() );
						    	map.setZoom( <?= $zoom_level; ?> );
							}else{
								<?php  if(empty($get_zoom_level)){ ?>
									map.fitBounds(bounds);
								<?php  } ?>
							}
						}
						/*
						*  document ready
						*
						*  This function will render each map when the document is ready (page has loaded)
						*
						*  @type	function
						*  @date	8/11/2013
						*  @since	5.0.0
						*
						*  @param	n/a
						*  @return	n/a
						*/
						// global var
						var map = null;
						$(document).ready(function(){
							$('.acf-map').each(function(){
								// create map
								map = new_map( $(this) );
								<?php  if(!empty($get_zoom_level)){ ?>
									google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
					                	this.setZoom(<?= $zoom_level; ?>);
					        		});
								<?php  } ?>
							});
						});
					})(jQuery);             
        		},
				complete: function(){
					jQuery('#loading-icon,button .s-spinner').hide();
				}
    		});
		});
		//end form_listing
	</script>
<?php
}
// the ajax function
add_action('wp_ajax_data_fetch' , 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch','data_fetch');
function data_fetch(){
	$nctitle=str_replace('\\','',sanitize_text_field($_POST['listing_keyword']));
    $new_query = new WP_Query( array( 
    	'posts_per_page' => -1, 
    	's' => $nctitle, 
    	'post_type' => 'listings',
		'post_status' => 'publish',
    	'taxonomy'=>'listing-categories',
    	'term' => sanitize_text_field(esc_attr($_POST['listing_category'])),
		'meta_query' => array(
			'relation'		=> 'AND',
			array(
				'key' => 'address',
				'value' => sanitize_text_field(esc_attr($_POST['listing_address'])),
				'compare' => 'LIKE'
			)
		)
	));
	if( $new_query->have_posts() ) :
		$the_query = $new_query;
?>
		<script>jQuery( "form#listing_form .row .no_res_found p").hide();</script>
<?php 		
	else :
		/* no result found query */
		$new_query = new WP_Query( array( 
			'post_type' => 'listings', 
			'posts_per_page' => -1, 
			'order' => 'ASC', 
			'orderby' => 'menu_order',
			'post_status' => 'publish',
			'taxonomy'=>'listing-categories',
			'term' => sanitize_text_field(esc_attr($_POST['listing_category'])),
		));
		/* over query */
		$the_query = $new_query;
?>
		<script>jQuery( "form#listing_form .row .no_res_found p").show();</script>
<?php		
	endif;
    if( $the_query->have_posts() ) :
        while( $the_query->have_posts() ): $the_query->the_post();
			$the_ID = get_the_ID();
			$get_google_map = get_field('address', $value);
			$title = get_the_title();
			$icon = get_field('category');
			$dpath = get_template_directory_uri();
			if($icon->name == "Restaurant"){
				$icon = ''.$dpath.'/images/icon-marker-3.png';
			}
			if($icon->name == "Hotel"){
				$icon = ''.$dpath.'/images/icon-marker-2.png';
			}
			if($icon->name == "Coffee"){
				$icon = ''.$dpath.'/images/icon-marker-1.png';
			}
			$mapimg = get_the_post_thumbnail_url(get_the_ID(),'img_1000x600');
			if(empty($mapimg)):
					$mapimg = get_field('s_default_featured_image','option');
			endif;
			$permalink = get_the_permalink();
			$mapwebsite = get_field('website');
			$output_map[$the_ID]['address'] = '<div class="marker" data-lat="'.$get_google_map['lat'].'" data-lng="'.$get_google_map['lng'].'" data-title="'.$title.'" data-img="'.$mapimg.'" data-link="'.$permalink.'" data-url="'.$mapwebsite.'">&nbsp;</div>';		
    	endwhile;
    	wp_reset_postdata();  
    endif; 
	if(!empty($output_map)) {		
		echo '<div class="acf-map">';
			foreach( $output_map as $key => $map_marker ):
				echo $map_marker['address'];
			endforeach;
		echo '</div><!--acf-map-banner-->';				
	}
	else { 		
		include dirname(__DIR__).'/template-parts/dt/front-custommap.php';			
	}	
    die();
}