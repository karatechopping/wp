<?php
/* This code is used for display review on listing.
   Screenshort : https://prnt.sc/1vogdiy */
	global $reviews_number;
	if($reviews_number!="0") : 
?>
		<script>
		    var ave=0;
		    var sum = 0;
		    jQuery('.rating_value').each(function() {
		        sum += Number(jQuery(this).val());
				total_rev = <?php echo get_comments_number($post->ID); ?>*5;
				ave = (sum/total_rev)*5;
		    });
		    jQuery('.ave-review').append(ave.toFixed(1));
			var title_ave = ave.toFixed(1);
			if(title_ave <= 1){
				 jQuery('.detail-overview-rating-title').append('<i class="fa fa-star" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" />');
			}
			if(title_ave <= 1.9){
				 jQuery('.detail-overview-rating-title').append('<i class="fa fa-star" /> <i class="fa fa-star-half-o" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" />');
			}
			else if(title_ave <= 2){
				 jQuery('.detail-overview-rating-title').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" />');
			}
			else if(title_ave <= 2.9){
				 jQuery('.detail-overview-rating-title').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star-half-o" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" />');
			}
			else if(title_ave <= 3){
				 jQuery('.detail-overview-rating-title').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star-o" /> <i class="fa fa-star-o" />');
			}
			else if(title_ave <= 3.9){
				 jQuery('.detail-overview-rating-title').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star-half-o" /> <i class="fa fa-star-o" />');
			}
			else if(title_ave <= 4){
				 jQuery('.detail-overview-rating-title').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star-o" />');
			}
			else if(title_ave <= 4.9){
				 jQuery('.detail-overview-rating-title').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star-half-o" />');
			}
			else if(title_ave <= 5){
				 jQuery('.detail-overview-rating-title').append('<i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" /> <i class="fa fa-star" />');
			}
		</script>
<?php 
	endif; 
?>