<?php 
if( have_rows('call_to_action', 'option') ): 
	while ( have_rows('call_to_action', 'option') ) : the_row(); 
		if( get_sub_field('bg_image') ) : ?>
			<style>
				.cp-get-started:before{display: none;}
				.ui-helper-hidden-accessible {display: none;}
			</style>
		<?php 
		endif; ?>
		<section class="cp-section cp-get-started" style="background-color: <?php the_sub_field('bg_color'); ?>; <?php if(get_sub_field('bg_image')) : ?>background: url('<?php the_sub_field('bg_image'); ?>') no-repeat scroll top center / cover transparent;<?php endif; ?>">
			<div class="inner">
				<div class="row justify-content-between">
					<div class="col-lg-9">
						<div class="get-started-content">
							<h2><?php if(get_sub_field('title')) : ?><?php the_sub_field('title'); ?><?php else:?>OfflineSharks Directorty Theme Review!<?php endif; ?></h2>
							<?php if(get_sub_field('content')) : ?><?php the_sub_field('content'); ?><?php else:?><p>Erat eget vitae malesuada, tortor tincidunt porta lorem lectus unde omnis iste natus.</p><?php endif; ?>
						</div>	
					</div>
					<div class="col-lg-auto align-self-center">
						<div class="get-started-btn">
							<a href="<?php the_sub_field('url'); ?>" class="global-btn"><?php if(get_sub_field('content')) : ?><?php the_sub_field('button_text'); ?><?php else:?><?php echo __('Contact Us', 'directorytheme'); ?><?php endif; ?></a>	
						</div>
					</div>
				</div>			  	
			</div>
		</section>
	<?php  
	endwhile; 
endif; 
	
$gtopn 		= get_field('activate_custom_color','option');
$footer_txt = get_field('footer_txt_color','option');
		
if($gtopn == 1){
	echo '<style>';
	echo '#footer h3,#footer p, .footer-widget li, .footer-widget li a,#footer .footer-txt p {color : '.$footer_txt.';}';
	echo '.footer_hr {background : '.$footer_txt.';}';
	echo '#footer .footer-txt p{font-weight : 600;}';
	echo '.footer-widget li a:hover {color : '.$footer_txt.';text-decoration : underline;}';
	echo '</style>';
} ?>
	<footer>     
		<div id="footer" style="background : <?php if($gtopn == 1){ if(get_field('footer_bg_color','option')){the_field('footer_bg_color','option');} } ?>;">
			<div class="inner">
				<div class="row justify-content-lg-center">        
					<div class="col-lg">
						<div class="footer-widget">
							<h3><?php the_field('col1title', 'option') ?></h3>
							<?php the_field('about', 'option') ?>
							<ul class="footer-info">
								<?php 
								$mail_icon = get_field('email', 'option'); 
								$tel_icon = get_field('phone', 'option');
								$map_icon = get_field('address', 'option');
								if($tel_icon): ?>
									<li><a href="tel:<?php the_field('phone', 'option') ?>"><i class="fa fa-phone"></i> <?php the_field('phone', 'option') ?></a></li>
								<?php 
								endif; 
								if($mail_icon):
								?>
								<li><a href="mailto:<?php the_field('email', 'option') ?>"><i class="fa fa-envelope"></i> <?php the_field('email', 'option') ?></a></li>
								<?php 
								endif; 
								if($map_icon): ?>
								<li><i class="fa fa-map-marker"></i> <?php the_field('address', 'option') ?></li>
								<?php 
								endif; ?>
							</ul>
						</div><!--footer-widget-->
					</div><!--col-->	
					<div class="col-lg-auto">
						<div class="footer-widget">
							<h3><?php the_field('col2title', 'option') ?></h3>
							<?php if( have_rows('company_info', 'option') ): ?>
								<ul>
									<?php 
										while ( have_rows('company_info', 'option') ) : the_row(); ?>
											<?php $get_url_array = get_sub_field('url'); ?>
											<li><a href="<?php if(!empty($get_url_array)){ echo $get_url_array;}else{ echo '#!'; } ?>"><?php the_sub_field('title', 'option'); ?></a></li>
											
									<?php endwhile; ?>
								</ul>
							<?php endif; ?>
						</div>
					</div>
					<div class="col-lg-auto">
						<div class="footer-widget">
							<h3><?php the_field('col3title', 'option') ?></h3>
							<?php if( have_rows('footer_links', 'option') ): ?>
								<ul>
									<?php while ( have_rows('footer_links', 'option') ) : the_row(); ?>
										<?php $get_url_array = get_sub_field('url'); ?>
										<li><a href="<?php if(!empty($get_url_array)){ echo $get_url_array;}else{ echo '#!'; } ?>"><?php the_sub_field('title', 'option'); ?></a></li>
									<?php endwhile; ?>
								</ul>
							<?php endif; ?>
						</div>
					</div>				      	  
					<div class="col-lg-auto">
						<div class="footer-widget footer-copyright">
							<a href="<?php echo home_url(); ?>"><img src="<?php the_field('footer_logo', 'option') ?>" class="img-fluid" /></a>
							<?php get_template_part( 'template-parts/footer/footer', 'social' ); ?>	
						</div>
					</div>      	  
				</div>
			</div>
			<?php 
				$copy_btn  = get_field('copy_txt','options');
				$copyright = get_field('copyright','options');
				if($copy_btn == 1){ ?>
					<div class="footer-txt" style="display:block;">
						<hr class="footer_hr">
						<div class="row justify-content-lg-center">
							<div class="col-md-12 text-center">
								<p><?php echo __('Copyright ', 'directorytheme'); ?><? echo date('Y').'.'; ?>
									<span class="copyright-inner">
										<?php if($copyright != '') { echo ucfirst($copyright); } else { echo $blog_title = get_bloginfo( 'name' ); } ?>
									</span>
								</p>
							</div>
						</div>
					</div>
			<?php 
				} ?>
		</div><!--footer-->
	</footer>
</div><!--wrapper-->  

<a class="scroll backtotop" href="#cp-wrapper"><i class="fa fa-angle-up"></i></a>
<?php 
	global $zoom_level, $wp;
	$get_zoom_level = get_option( 'options_cm_zoom_level');
	$zoom_level 	= $get_zoom_level;
	if($zoom_level == '') $zoom_level= 10;
	$c_url  	= home_url($wp->request);
	$url_part 	= explode("/",$c_url);
	
	if(in_array("dt_listing",$url_part)){
		$zoom_level = get_field('default_zoom_map','option');
		if($zoom_level == '') $zoom_level= 10;
	}else {
    	$lsiting_cls = get_body_class();
    	if(in_array("single-listings",$lsiting_cls)):
        	$zoom_level = get_field('default_zoom_map','option');
        	if($zoom_level == '') $zoom_level= 10;
    	endif;
	} ?>

<script type="text/javascript">
	(function($) {
		function new_map( $el ) {
			var $markers = $el.find('.marker');
			var styles = [{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#747474"},{"lightness":"23"}]},{"featureType":"poi.attraction","elementType":"geometry.fill","stylers":[{"color":"#f38eb0"}]},{"featureType":"poi.government","elementType":"geometry.fill","stylers":[{"color":"#ced7db"}]},{"featureType":"poi.medical","elementType":"geometry.fill","stylers":[{"color":"#ffa5a8"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#c7e5c8"}]},{"featureType":"poi.place_of_worship","elementType":"geometry.fill","stylers":[{"color":"#d6cbc7"}]},{"featureType":"poi.school","elementType":"geometry.fill","stylers":[{"color":"#c4c9e8"}]},{"featureType":"poi.sports_complex","elementType":"geometry.fill","stylers":[{"color":"#b1eaf1"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":"100"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"},{"lightness":"100"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffd4a5"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffe9d2"}]},{"featureType":"road.local","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"weight":"3.00"}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"weight":"0.30"}]},{"featureType":"road.local","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#747474"},{"lightness":"36"}]},{"featureType":"road.local","elementType":"labels.text.stroke","stylers":[{"color":"#e9e5dc"},{"lightness":"30"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"visibility":"on"},{"lightness":"100"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#d2e7f7"}]}];

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
	
		//extra varaible
		function add_marker( $marker, map ) {
			// var
			var allInfowWindows=[];
			var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
			var mylat = $marker.attr('data-lat');
			var mylng = $marker.attr('data-lng');
		
			if(mylat !== '' && mylng !== ''){
				var icon = $marker.attr('data-icon');
				var marker = new google.maps.Marker({
					position	: latlng,
					map			: map
				});
				
				map.markers.push( marker );
				if( $marker.html() ){
					var maptitle = $marker.attr('data-title');
	  				var mapimg = $marker.attr('data-img');
	  				var maplink = $marker.attr('data-link');
	  				var mapurl = $marker.attr('data-url');
	  				var content = '<div id="map-container">' +
	                    '<div class="map-content">' +
	                      '<a href="'+maplink+'"><img data-src="'+mapimg+'" class="img-fluid"></a>' +
	                      '<div class="map-title">'+maptitle+' <span>'+mapurl+'</span><a href="'+maplink+'" class="map-btn">View Listing <i class="fa fa-caret-right"></i></a></div>' +
	                    '</div>' +
	                  '</div>';

	  				var infowindow = new google.maps.InfoWindow({
	    				content: content,
	    				maxWidth: 250
					});
					allInfowWindows.push(infowindow);
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
				}
	  		} 
	  		// check lan & long not empty if condition
		}

		function center_map( map ) {
			var bounds = new google.maps.LatLngBounds();
			$.each( map.markers, function( i, marker ){
				var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
				bounds.extend(marker.position);
				map.panTo(marker.position);
			});

			if( map.markers.length == 1 ){
				map.setCenter( bounds.getCenter() );
		    	map.setZoom( <?= $zoom_level; ?> );
			}else{
				<?php  if(empty($get_zoom_level)){ ?>
					map.fitBounds(bounds);
				<?php  } ?>
	       	}
		}
		var map = null;

		$(window).bind('load', function() {
			$('.acf-map').each(function(){
				// create map
				map = new_map( $(this) );
				// Set zoom level if added from banner settings
				<?php  if(!empty($get_zoom_level)){ ?>
					google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
	                	this.setZoom(<?= $zoom_level; ?>);
	        		});
				<?php  } ?>
	 		});
	        if (jQuery(window).width() < 768){
	        	jQuery(".category_display .row .col-md-12").addClass("my-cls-9");
	        	jQuery(".category_display .row .col-md-12").removeClass("col-md-12");
	        	jQuery(".featured-listing .category_display .row .col-md-12").addClass("my-cls-9");
	        	jQuery(".featured-listing .category_display .row .col-md-12").removeClass("col-md-12");
	              
	        	jQuery('#keyword-search').click(function(){
	                setInterval(function(){
	                    jQuery("#listing_ajax .featured-listing .category_display .row .col-md-12").addClass("my-cls-9");
	                    jQuery("#listing_ajax .featured-listing .category_display .row .col-md-12").removeClass("col-md-12");
	                }, 2000);
	        	});
	    	}
		});
	})(jQuery);
</script>
<?php wp_footer(); ?>
</body>
</html>