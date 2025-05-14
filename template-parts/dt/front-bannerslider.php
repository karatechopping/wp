<!--If admin select option of banner slider for home page from backend.
		Screenshort : https://prnt.sc/1vodwee -->
<div class="home-banner-slider">
	<?php
		$sldrspeed=get_field('bnnr_slider_speed','option');
	?>
	<input type="hidden" id="sliderspeedid" data-sliderspeed="<?php echo $sldrspeed;?>">
	<div class="owl-carousel owl-theme">	
		<?php
			// check if the repeater field has rows of data
			if( have_rows('bannersliderrepeater','option') ):
	 			// loop through the rows of data
	    		while ( have_rows('bannersliderrepeater','option') ) : the_row(); 
	       			$slider_title = get_sub_field('bsr_title','option');
	       			$slider_sub_title = get_sub_field('bsr_sub_title','option');
	       			$slider_btn_txt = get_sub_field('bsr_btn_text','option');
	    ?>
	    			<div class="item">
	    				<div class="bsr_slide_image" style="background-image: url(<?php the_sub_field('bsr_image','option'); ?>);">
	       					<div class="bsr_content">
		       					<?php if(!empty($slider_title)): ?>
		       						<h1><?php the_sub_field('bsr_title','option'); ?></h1>
		       					<?php 
		       						endif; 
		       			    		if(!empty($slider_sub_title)): 
		       			    	?>
			       					<h4><?php the_sub_field('bsr_sub_title','option'); ?></h4>
			       				<?php endif;  ?>
		       					<p><?php the_sub_field('bsr_content','option'); ?></p>
		       					<?php 
		       						$gdb=get_sub_field('bsr_btn_link','option'); 
	       			          		if(!empty($slider_btn_txt)): 
		       					?>
		       							<a href="<?php if(!empty($gdb['url'])){echo $gdb['url'];}else{echo '!#';} ?>"><?php the_sub_field('bsr_btn_text','option'); ?></a>
		       					<?php endif; ?>
	       					</div>	       			
	       				</div>
	    			</div>
	    <?php	       
	    		endwhile;
			else :
	    		// no rows found
			endif;
		?>
	</div>
</div>