<?php
/* This code is used for display review on listing.
   Screenshort : https://prnt.sc/1vogdiy */
	$args = array(
		'post_id' => $post->ID,
	);
	$comments = get_comments($args);
	foreach($comments as $comment) :   
		if( get_field('rate', $comment) ): 
			$rating = get_field('rate', $comment); 
?>
			<input type="hidden" class="rating_value<?php echo $post->ID; ?>" value="<?php echo $rating; ?>" />
<?php 
		endif; 
	endforeach;
	global $reviews_num ;
	if($reviews_num!="0") : 
?>
		<script>
    		var ave=0;
    		var sum = 0;
    		jQuery('.rating_value<?php echo $post->ID; ?>').each(function() {
        		sum += Number(jQuery(this).val());
				total_rev = <?php echo get_comments_number($post->ID); ?>*5;
				ave = (sum/total_rev)*5;
    		});
    		jQuery('.ave-review<?php echo $post->ID; ?>').append(ave.toFixed(1));
			var title_ave = ave.toFixed(1);
			if(title_ave <= 1){
				 jQuery('.detail-overview-rating-title<?php echo $post->ID; ?>').append('<i class="fa fa-star" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" />');
			}
			if(title_ave <= 1.9){
				 jQuery('.detail-overview-rating-title<?php echo $post->ID; ?>').append('<i class="fa fa-star" /> <i class="fa fa-star-half-o" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" />');
			}
			else if(title_ave <= 2){
				 jQuery('.detail-overview-rating-title<?php echo $post->ID; ?>').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" />');
			}
			else if(title_ave <= 2.9){
				 jQuery('.detail-overview-rating-title<?php echo $post->ID; ?>').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star-half-o" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" />');
			}
			else if(title_ave <= 3){
				 jQuery('.detail-overview-rating-title<?php echo $post->ID; ?>').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" />');
			}
			else if(title_ave <= 3.9){
				 jQuery('.detail-overview-rating-title<?php echo $post->ID; ?>').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star-half-o" /> <i class="fa fa-star-o" />');
			}
			else if(title_ave <= 4){
				 jQuery('.detail-overview-rating-title<?php echo $post->ID; ?>').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star-o" />');
			}
			else if(title_ave <= 4.9){
				 jQuery('.detail-overview-rating-title<?php echo $post->ID; ?>').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star-half-o" />');
			}
			else if(title_ave <= 5){
				 jQuery('.detail-overview-rating-title<?php echo $post->ID; ?>').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" />');
			}
		</script>
<?php 
	endif; 
?>