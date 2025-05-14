<?php acf_form_head(); ?>
<?php
/**
 * template name: Listing
 */

get_header(); ?>

<!-- banner section -->
<div id="preloader"><div id="status">&nbsp;</div></div>
<div id="page-banner" class="<?php if(get_field('banner')) : ?>has-banner<?php endif; ?>" style="background: <?php if(get_field('banner')) : ?>url('<?php the_field('banner'); ?>') no-repeat <?php the_field('bg_position'); ?>  transparent;<?php endif; ?>;">
  <div class="inner">
    <h1><?php the_title(); ?></h1>
  </div><!--inner-->
</div><!--page-banner-->

<!-- This code is used for serach listing.
	Screenshort :https://prnt.sc/1uh5imm
-->
<section class="listing_search single-listing-search">
	<div class="inner">
<?php 
	global $choices_array1;
    global $business_nm,$add_nm,$listing_category;
    if($_GET){
    	$business_nm = $_GET['listing_keyword'];
    	//echo $business_nm.'hello';
        $add_nm = $_GET['add_listing_keyword1'];
        //echo $add_nm.': add';
        $listing_category = $_GET['s_listing_category'];
        //echo $listing_category.': cat';
    }
?>


<form id="single_listing_form">
	<?php get_template_part( 'template-parts/dt/front', 'search-listing' ); ?>
</form>
		
	</div>
</section>

<!-- This code is used for display all listing on page.
	Screenshort : https://prnt.sc/1umeb7r -->
<section class="cp-section single-listing-wrap" style="padding-top: 0;">
	<div class="inner">
		<div id="listing_ajax">
		
<?php
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;		
		if(!empty($_GET['listing_keyword'])){
				$main_args = array( 
    			'posts_per_page' => '30',
    			'paged' => $paged,	 
    			's' => sanitize_text_field(esc_attr($_GET['listing_keyword'])), 
    			'post_type' => 'listings',
					'post_status' => 'publish',
				);		
		}else{
				$main_args = array( 
					'post_type' => 'listings', 
					'posts_per_page' => '30',
					'paged' => $paged,	
					'post_status' => 'publish',
				);
		}
		if(!empty($_GET['s_listing_category'])){
	    	$main_args['tax_query'] = array(
                array( 
                    'taxonomy' => 'listing-categories',
                    'terms' => sanitize_text_field(esc_attr($_GET['s_listing_category'])),
                    'field' => 'slug',
                )
            );
		}
		if(!empty($_GET['add_listing_keyword1'])){
	        $main_args['meta_query'] = array(
                'relation'		=> 'AND',
                array(
                	'key' => 'address',
                    'value' => sanitize_text_field(esc_attr($_GET['add_listing_keyword1'])),
                    'compare' => 'LIKE'
                )
            );
		}
		$fl = new WP_Query( $main_args );
	
	
if ( $fl->have_posts() ) : ?>

<div class="row">
	<?php while ( $fl->have_posts() ) : $fl->the_post(); ?>
	
	<div class="col-lg-4">
		<div class="featured-listing">
			<div class="fl-img">
				<?php
				 $post_img = get_field('featured_image');        
		        if (is_numeric($post_img)) {        
		       		$post_img = wp_get_attachment_url($post_img);
		       
			    } else {
			        $post_img = get_field('featured_image');
			    }

				$dtsimg=get_field('s_default_featured_image','option');					
				$ftdimg=get_the_post_thumbnail_url();					
				$post_id = get_the_ID();
					
				?>
				<div class="zioea <?php echo get_field('listing_type');?>_5b28570780cc1">
					<a href="<?php the_permalink(); ?>">
						<img  src="<?php if(!empty($ftdimg)) { echo $ftdimg; } else if(!empty($post_img)) { echo $post_img; } else if(!empty($dtsimg)) { echo $dtsimg; } else{ echo bloginfo("template_url").'/images/Listing-Placeholder.png'; } ?>" alt="<?php the_title(); ?>" class="img-fluid" />
					</a>
				</div>
				<div class="zioea <?php echo get_field('listing_type');?>_default_listing_image" style="display: none;">
					<a href="<?php the_permalink(); ?>">
						<img src="<?php if(!empty($dtsimg)) { echo $dtsimg; } else { echo bloginfo("template_url").'/images/Listing-Placeholder.png'; } ?>" alt="<?php the_title(); ?>" class="img-fluid" />
					</a>
				</div>
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
				  
	<?php endwhile; wp_reset_query(); ?>
</div><!--row-->
<?php endif; ?>   	
			
	<?php 
		if(function_exists('wp_pagenavi'))
		{
			wp_pagenavi( array( 'query' => $fl ) );
		}
	?>			
			
		</div><!--listing_ajax-->	
		
	</div><!--inner-->
	
	<div id="loading-icon"></div><!--loading-->
		
</section>
<?php
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


<script>
	jQuery('#cp-container img').addClass('img-fluid');
	jQuery(window).load(function(){	   
		var business_nm = "<?php echo $business_nm; ?>";		
		var add_nm = "<?php echo $add_nm; ?>";
		var listing_category = "<?php echo $listing_category; ?>";
		if(business_nm != '' || add_nm != '' || listing_category != ''){
			document.getElementById("listing_keyword").value = business_nm;
			document.getElementById("listing_address").value = add_nm;
			jQuery("#listing_category option[value='"+listing_category+"']").prop('selected', true);
			//document.getElementById("keyword-search").click();
		}
		
	});
	
</script>



<?php get_footer();