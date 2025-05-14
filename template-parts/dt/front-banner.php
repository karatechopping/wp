<?php
	/* If admin select option of banner image from backend.
		Screenshort : https://prnt.sc/1vodgtf*/
	if( have_rows('banner_content', 'option') ): 
		while( have_rows('banner_content', 'option') ): the_row(); 
			// vars
			$image = get_sub_field('background', 'option');
			$animation = get_sub_field('animation', 'option');
			$animation = ($animation == 'No') ? "animation:none" : "";
			$link_text = get_sub_field('button_text', 'option');
			$link = get_sub_field('button_url', 'option');
			$title = get_sub_field('title', 'option');
			$content = get_sub_field('content', 'option');
		?>
			<section>
				<div id="cp-banner">
			  		<div id="cp-slide">
						<div class="banner">
							<div class="banner-img" style="background: url('<?php echo $image; ?>') no-repeat scroll top / cover transparent; <?= $animation; ?>"></div>  
							<div class="inner">	  
						  		<div class="row align-items-center justify-content-center">
									<div class="col-lg-auto">
										<div class="banner-entry">
											<h2><?php echo $title; ?></h2>
											<?php echo $content; ?>
											<?php if(get_sub_field('button_text', 'option')): ?>
												<div class="banner-btn text-center">
													<a href="<?php echo $link; ?>" class="global-btn"><?php echo $link_text; ?></a>
												</div>
											<?php endif; ?>
										</div><!--banner-content-->					      
									</div><!--col-->
						  		</div><!--row-->	      	  	    	  	
							</div><!--inner-->   					  
						</div><!--banner-->
					</div>      	  
				</div><!--cp-banner-->
		  	</section>
		<?php endwhile; ?>
<?php 
	endif; 
?>