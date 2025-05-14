<?php
/* This code is used for display feature listing on home page selected by admin in backend.
	Screenshort : https://prnt.sc/1voeaw1 */ 
	global $choices_array1;
	$rowbtn=get_field('f_row','option');
	$post_objects = get_field('f_listing','option');
	if( $post_objects ): 
?>
		<section class="cp-section grey">
			<div class="inner">
				<div class="cp-title">
					<?php 
						$frontpage_id = get_option( 'page_on_front');
						$f_text = get_field('featured_text',$frontpage_id);
						if(!empty($f_text)):
		    				echo '<h2>'.$f_text.'</h2>';
		 				else:
		    				echo '<h2>'.__('Featured Listings', 'directorytheme');'</h2>';
						endif; 
					?>
				</div>
				<div class="row">
    				<?php 
    					foreach( $post_objects as $post): // variable must be called $post (IMPORTANT) 
    						setup_postdata($post); 
        					$post_img = get_field('featured_image');        
        					if (is_numeric($post_img)) {        
       							$post_img = wp_get_attachment_url($post_img);
	    					} else {
	        					$post_img = get_field('featured_image');
	    					}
        					$defualt_img = get_field('s_default_featured_image','option'); 
        					$feature_img = 	get_the_post_thumbnail_url($post);
    						if($rowbtn == 'two'){ 
    				?>
          						<div id="listing_row" class="col-md-6 col-sm-6 col-xs-12"> 
      				<?php  
      						}
       						if($rowbtn == 'three'){   
       				?>
           						<div id="listing_row" class="col-md-4 col-sm-6 col-xs-12">
       				<?php 
       						}
    				?>
							<div class="featured-listing featured-home">
								<div class="fl-img">
									<a href="<?php the_permalink(); ?>">
										<img src="<?php if($feature_img != '') { the_post_thumbnail_url('img_1000x600'); } else if($post_img != ''){ echo $post_img;} else if(!empty($defualt_img)) {echo $defualt_img; } else {  echo bloginfo("template_url").'/images/Listing-Placeholder.png'; } ?>" alt="<?php the_title(); ?>" class="img-fluid" />
									</a>
               						<!-- category shortcode-->
               						<div class="col-md-12 category_display" style="padding : 0;">
               							<?php echo do_shortcode( '[displ_cat]' ); ?>
               						</div> 
									<h3 class="feature_text"><span><?php the_title(); ?></span></h3>
									<?php if(get_field('listing_type') != $choices_array1[0] ) : ?>
										<img src="<?php echo get_template_directory_uri(); ?>/images/star.png" class="premium-star" />
									<?php endif; ?>		
								</div><!--fl-img-->
							</div>
						</div><!--col-->
    			<?php 
    				endforeach; 
    			?>
				</div><!--row-->
    			<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
			</div><!--inner-->
		</section>
<?php 
	endif; 
?>