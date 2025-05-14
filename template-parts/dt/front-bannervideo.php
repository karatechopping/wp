<?php
	/* If admin select option of banner video from backend.
		Screenshort : https://prnt.sc/g6TRzqfo4JIv */
	if( have_rows('banner_content', 'option') ): 
		while( have_rows('banner_content', 'option') ): the_row(); 
			$search_listing = get_sub_field('banner_video', 'option');
			$ext = pathinfo($search_listing, PATHINFO_EXTENSION);
			$link_text = get_sub_field('button_text', 'option');
			$link = get_sub_field('button_url', 'option');
			$title = get_sub_field('title', 'option');
			$content = get_sub_field('content', 'option');
		?>
			<section>
				<div id="cp-bannervideo">
			  		<div id="cp-video">
						<div class="bannervideo">
							<video id="banner_video" autoplay muted loop>
							  	<source src="<?php echo $search_listing; ?>" type="video/<?php echo $ext; ?>">
							</video>
							<!-- <div class="banner-img" style="background: url('<?php echo $image; ?>') no-repeat scroll top / cover transparent; <?= $animation; ?>"></div>  -->
							<div class="inner banner-hading-text">	  
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
										</div>				      
									</div>
						  		</div>
							</div>
						</div>
					</div>      	  
				</div>
		  	</section>
		  	<script type="text/javascript">
		  		jQuery(document).ready(function(){
		  			jQuery('#banner_video').trigger('play');
		  		});
		  	</script>
		<?php endwhile; 
	endif; 
?>