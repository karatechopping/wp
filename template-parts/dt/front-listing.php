<!--This code is used for display listing on home page.
	Screenshort : https://prnt.sc/1vofgyg -->

<section class="cp-section new-listing-section">
	<div class="inner">
		<?php
		global $choices_array1;
		$frontpage_id = get_option( 'page_on_front');
		$f_text = get_field('new_listing_text',$frontpage_id);
		$nonl = get_field('nof_listing', 'option');
		$dorfi = get_field('s_default_featured_image','option');

		if(!empty($f_text)):
		    echo '<h2>'.$f_text.'</h2>';
		 else:
		    echo '<h2>New Listings</h2>';
		endif;

		$nl = new WP_Query( array(
			'post_type' 	 => 'listings',
			'posts_per_page' => $nonl,
			'post_status' 	 => 'publish',
		) );

		if ( $nl->have_posts() ) : ?>
			<?php
			while ( $nl->have_posts() ) : $nl->the_post();
				$fid 	  = get_the_ID();
				$fimg 	  = get_the_post_thumbnail_url($fid,'img_1000x1000');
				$post_img = get_field('featured_image');
				if (is_numeric($post_img)) {
					$post_img = wp_get_attachment_url($post_img);
				} else {
					$post_img = get_field('featured_image');
				} ?>
				<div class="col-sm-6">
					<div class="nl-listing">
						<div class="row">
							<div class="col-lg-5 <?php echo get_field('listing_type');?>_5b28570780cc1">
								<div class="new-listing">
									<div class="nl-img">
										<a href="<?php the_permalink(); ?>">
											<img src="<?php
											if(!empty($fimg)){ echo $fimg; }
											else if(!empty($post_img)) { echo $post_img; }
											else if(!empty($dorfi)) { echo $dorfi; }
											else { echo bloginfo("template_url").'/images/Listing-Placeholder.png'; } ?>"
											alt="<?php the_title(); ?>"
											class="img-fluid" />
										</a>
										<?php if( get_field('listing_type') != $choices_array1[0] ) : ?>
											<img src="<?php echo get_template_directory_uri(); ?>/images/star.png" class="premium-star" />
										<?php endif; ?>
									</div><!--nl-img-->
								</div>
							</div><!--col-lg-5-->
							<div class="col-lg-5 <?php echo get_field('listing_type');?>_default_listing_image" style="display: none;">
								<div class="new-listing">
									<div class="nl-img">
									<a href="<?php the_permalink(); ?>">
										<img src="
										<?php
										if(!empty($dorfi)) { echo $dorfi; }
										else { echo bloginfo("template_url").'/images/Listing-Placeholder.png'; } ?>"
										alt="<?php the_title(); ?>"
										class="img-fluid" />
									</a>

						               </div><!--nl-img-->
								</div>
							</div>
							<div class="col-lg-7">
								<div class="new-listing-entry">
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

									<?php
									$reviews_num = get_comments_number();
									if( $reviews_num != "0") : ?>
										<div class="detail-overview-rating-title<?php echo $post->ID; ?> nl-rating"><span class="ave-review<?php echo $post->ID; ?>"></span></div>
									<?php
									endif;

									$contact_address = get_field('address');
									if(!empty($contact_address['address'])): ?>
										<address class="nl-address <?php echo get_field('listing_type');?>_5a4df4a8y3r17"> <i class="fa fa-map-o"></i>
											<?php $address = explode( "," , $contact_address['address']);
											echo isset( $address[0] ) ? $address[0] : ""; //street number
											echo isset( $address[1] ) ? ", ".$address[1] : "";
											echo isset( $address[2] ) ? ", ".$address[2] : ""; //city, state + zip
											?>
										</address>
									<?php
									endif;
									$ph_hide = get_field('hide_phn_swtchr');
									if($ph_hide != 1):
										if(get_field('phone')): ?>
											<div class="nl-info <?php echo get_field('listing_type');?>_5a4df4y3er02w">
												<a href="tel:<?php the_field('phone'); ?>"><i class="fa fa-phone"></i> <span><?php the_field('phone'); ?></span></a>
											</div><!--listing-info-item-->
										<?php
										endif;
									endif; ?>
									<div class="dkp_btn">
										<a href="<?php the_permalink(); ?>" class="global-btn btn-small"><?php echo __('View Details', 'directorytheme'); ?></a>
									</div>
								</div><!--new-listing-entry-->
							</div><!--col-lg-7-->

							<!-- category shortcode-->
							<div class="col-md-12 category_display">
								<?php echo do_shortcode( '[displ_cat]' ); ?>
							</div>

							<div class="col-md-12 mob_btn">
								<a href="<?php the_permalink(); ?>" class="global-btn btn-small"><?php echo __('View Details', 'directorytheme'); ?></a>
							</div>
						</div><!-- row -->
					</div><!-- nl-listing -->
				</div><!-- col-sm-6 -->
				<?php
				// featured listing section
				get_template_part( 'template-parts/dt/front', 'listing-script' );
			endwhile; wp_reset_postdata();
		endif; ?><!-- End posts query -->

		<div class="text-center">
			<a href="<?php echo get_permalink( get_page_by_path( 'listings' ) ); ?>" class="global-btn btn-solid"><?php echo __('View All Listings', 'directorytheme'); ?></a>
		</div>
	</div><!--inner-->
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
<script>
	var divs = jQuery(".new-listing-section .col-sm-6");
	for(var i = 0; i < divs.length; i+=2) {
	  divs.slice(i, i+2).wrapAll("<div class='row'></div>");
	}

    jQuery(document).ready(function(){
        if ( jQuery(window).width() > 991 ) {
            jQuery('.mob_btn a').hide();
        }
        if ( jQuery(window).width() <= 991 ) {
			jQuery('.dkp_btn a').hide();
			jQuery('.mob_btn a').show();
        }
    });
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