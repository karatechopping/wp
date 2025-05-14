<?php
	/* Searching a listing from home page searchbar section.*/
	// add the ajax fetch js
	add_action( 'wp_footer', 'listing_ajax_fetch' );
	function listing_ajax_fetch() {
?>
		<script type="text/javascript">
			var sseen = {};
			jQuery('#single_listing_form .listing_address,#home_search .listing_address').each(function() {
    			var txt = jQuery(this).text();
    			if (sseen[txt])
        			jQuery(this).remove();
    			else
        			sseen[txt] = true;
			});
			jQuery("#listing_category").change(function(){
        		var scattext = jQuery(this).children("option:selected").val();
       			scattext1 = encodeURIComponent(scattext);
				jQuery(this).parent().parent().find('#s_listing_category').val(scattext1);	
	    		jQuery(this).parent().parent().find('button.btn').text(scattext);	
    		});	
			jQuery('#single_listing_form button.reset-btn,#home_search button.reset-btn').click(function(){
				jQuery('.listing_keyword').val("");
				jQuery('#listing_category').prop('selectedIndex',0);
				jQuery('#s_listing_category').val("");	
				jQuery('#single_listing_form #listing_address,#home_search #listing_address').text("Search by: Address");
				jQuery('#listing_address').val("");
			});
			jQuery(window).bind('load', function() {  
    			jQuery('#single_listing_form .dropdown-address').click(function(){
    				var saddresstext = jQuery(this).text(); 
    				jQuery(this).parent().parent().find('#s_listing_address').val(saddresstext);
    				jQuery(this).parent().parent().find('button.btn').text(saddresstext);
    			});	
			});
			jQuery("#single_listing_form").submit(function(event) { 
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
        			data: { action: 'listing_fetch', s_listing_category : listing_category, s_listing_keyword : my_listing, s_listing_address : listing_address  },
        			success: function(data) {
            			jQuery('#listing_ajax').html( data ); 
						var divs = jQuery("#listing_ajax .col-lg-4");
						for(var i = 0; i < divs.length; i+=3) {
			  				divs.slice(i, i+3).wrapAll("<div class='row'></div>");
						}	            
        			},
					complete: function(){
						jQuery('#loading-icon,button .s-spinner').hide();
					}
    			});
			});
			//end form_listing
			//Start Business suggestion list
			jQuery(function () {
				var keys = [];
				var dataList = jQuery('.srldiv input').each(function(){
				var dataSrc=jQuery(this).attr("data-businame");
					keys.push(dataSrc.replace("&#8217;",""));
				});
				jQuery("#listing_keyword").autocomplete({
        			source: keys
    			});
			});
			//End Business suggestion list
			jQuery(function () {
				var addkeys = [];
				var dataList = jQuery('.sbaddress input').each(function(){
					var dataSrc=jQuery(this).attr("data-address");
					addkeys.push(dataSrc);
				});
				jQuery("#listing_address").autocomplete({
        			source:addkeys
    			});
			});
			jQuery(function () {
				var addkeys = [];
				var dataList = jQuery('.sbaddress1 input').each(function(){
					var dataSrc=jQuery(this).attr("data-address");
					addkeys.push(dataSrc);		
				});
				jQuery("#listing_address").autocomplete({
        			source:addkeys
    			});
			});
		</script>
<?php
	} 
	function theme_autocomplete_js() {
		$args = array(
			'post_type' => 'listings',
			'post_status' => 'publish',
			'posts_per_page'   => -1 // all posts
		);
		$posts = get_posts( $args );
		if( $posts ) :
			foreach( $posts as $k => $post ) {
				$source[$k]['ID'] = $post->ID;
				$source[$k]['label'] = $post->post_title; // The name of the post
				$source[$k]['permalink'] = get_permalink( $post->ID );
			}
?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					var posts = <?php echo json_encode( array_values( $source ) ); ?>;
					jQuery('#s_listing_keyword').autocomplete({
		        		source: posts,
		        		minLength: 2,
		        		select: function(event, ui) {
		            		var permalink = ui.item.permalink; // Get permalink from the datasource
		            		window.location.replace(permalink);
		        		}
		    		});
				});    
			</script>
<?php
		endif;
	}
	add_action( 'wp_footer', 'theme_autocomplete_js' );

	// the ajax function
	add_action('wp_ajax_listing_fetch' , 'listing_fetch');
	add_action('wp_ajax_nopriv_listing_fetch','listing_fetch');
	function listing_fetch(){
		global $choices_array1;
		$main_args = array( 
    		'posts_per_page' => -1, 
    		's' => sanitize_text_field(esc_attr($_POST['s_listing_keyword'])), 
    		'post_type' => 'listings',
			'post_status' => 'publish',
        	'taxonomy'=>'listing-categories',
    		'term' => sanitize_text_field(esc_attr($_POST['s_listing_category']))
		);		
		if(!empty($_POST['s_listing_category'])){
	    	$main_args['tax_query'] = array(
                array( 
                    'taxonomy' => 'listing-categories',
                    'terms' => sanitize_text_field(esc_attr($_POST['s_listing_category'])),
                    'field' => 'slug',
                )
            );
		}
		if(!empty($_POST['s_listing_address'])){
	        $main_args['meta_query'] = array(
                'relation'		=> 'AND',
                array(
                	'key' => 'address',
                    'value' => sanitize_text_field(esc_attr($_POST['s_listing_address'])),
                    'compare' => 'LIKE'
                )
            );
		}
		$the_query = new WP_Query( $main_args);
    	if( $the_query->have_posts() ) :
        	while( $the_query->have_posts() ): $the_query->the_post(); 
				$dtsimg=get_field('s_default_featured_image','option');
				$ftdimg=get_the_post_thumbnail_url();
				$post_img = get_field('featured_image');        
				if (is_numeric($post_img)) {        
					$post_img = wp_get_attachment_url($post_img);
				} else {
					$post_img = get_field('featured_image');
				}
?>
				<div class="col-lg-4">
					<div class="featured-listing">
						<div class="fl-img">
					 		<div class="zioea <?php echo get_field('listing_type');?>_5b28570780cc1">
								<a class="5b28570780cc1" href="<?php the_permalink(); ?>"><img src="<?php if(!empty($ftdimg)) { echo $ftdimg; } else if(!empty($post_img)) { echo $post_img; } else if(!empty($dtsimg)) { echo $dtsimg; } else{ echo bloginfo("template_url").'/images/Listing-Placeholder.png'; }?>" alt="<?php the_title(); ?>" class="img-fluid" /></a>
							</div>
							<div class="zioea <?php echo get_field('listing_type');?>_default_listing_image">
								<a class="5b28570780cc1" href="<?php the_permalink(); ?>"><img src="<?php if(!empty($dtsimg)) { echo $dtsimg; } else { echo bloginfo("template_url").'/images/Listing-Placeholder.png'; } ?>" alt="<?php the_title(); ?>" class="img-fluid" /></a>
							</div> <!-- zioea -->
                        	<h3><a href="<?php the_permalink(); ?>" class="list-title-click"><?php the_title(); ?></a></h3>
                         	<!-- category shortcode-->
                         	<div class="col-md-12 category_display" style="padding : 0;">
                        		<?php echo do_shortcode( '[displ_cat]' ); ?>
                        	</div>
							<?php if(get_field('listing_type') != $choices_array1[0] ) : ?>
								<img src="<?php echo get_template_directory_uri(); ?>/images/star.png" class="premium-star" />
							<?php endif; ?>						
						</div><!--fl-img-->
					</div>
				</div><!--col-->        
<?php
	    	endwhile;
    	    wp_reset_postdata();  
    	else : 
?>    
	        <p>No Listing Found.</p>	
<?php    
	    endif; 
	    /*pricing page field selected by admin in backend Screenshort : https://prnt.sc/1ubl3uw */
		$page_ids = get_all_page_ids();
		global $pricing_page,$my_field;
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
		?>
		<script type="text/javascript">
			/*pricing page option*/
			var price_option = [];
			<?php foreach($json_price_option as $key => $val): ?>
					price_option['<?php echo $key; ?>'] = <?php echo $val; ?>;
			<?php endforeach; ?>
			jQuery.fn.myFunction = function(key) {
				var new_key = key; 
				var arrayFromPHP = price_option[key];
				var exist_field = ['5a5567c297a42','5a5567c297187','5a4df4a8y3r17','5a2fb4cc6eddf','direction-on-map','5a4df4y3er02w','5a2fb4f96ede0','5a0552cd48d5f','5a556a21dc86b','5a2fb4ff6ede1','5a2fb51a6ede2','5a2fb52e6ede3','5a2fb53e6ede4', '5aa8eb5906999','5aa8ec230plm4','5ba9ec231plh8','5ba9fc231poh2','5ba3gc231pod4','5ba3gc234pjl7','5ba3gc23dfvx','5a430c5235231','5b28570780cc1'];
				var difference1 = jQuery(exist_field).not(arrayFromPHP).get();
				jQuery.each(difference1, function(index, value){
					jQuery("."+ key + '_' +value).css({"display": "none"});
					if(value == '5b28570780cc1'){
						jQuery('.'+ key+'_default_listing_image').css({"display": "block"});
					}
				});
				var difference = jQuery(exist_field).not(difference1).get();
				jQuery.each(difference, function(index, value){
					jQuery("."+key+'_'+ value).css({"display": "block"});
					if(value == '5b28570780cc1'){
						jQuery('.'+ key+'_default_listing_image').css({"display": "none"});
					}
				});
			}
			<?php foreach($choices_array1 as $key => $val): ?>
				<?php $listing_ty = $val; ?>
				var def_val = '<?php echo $listing_ty; ?>';
				jQuery.fn.myFunction(def_val);
			<?php endforeach; ?>
		</script>
		<?php
		die();
	}