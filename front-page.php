<?php
/**
 * The front page template file
 */
get_header();

/* check which banner option section is select
Screenshort : https://prnt.sc/1uch7lt*/

$cms	= get_field('cust_bann_switcher','option');
$cbrbtn = get_field('custom_banner','option');

if( $cms == '1' ) {
	if ($cbrbtn == 'bannerimage') {
		get_template_part( 'template-parts/dt/front', 'banner' );
	}
	elseif ($cbrbtn == 'bannervideo') {
		get_template_part( 'template-parts/dt/front', 'bannervideo' );
	}
	elseif ($cbrbtn == 'bannerslider') {
		get_template_part( 'template-parts/dt/front', 'bannerslider' );
	}
	elseif ($cbrbtn == 'custommap') {
		get_template_part( 'template-parts/dt/front', 'custommap' );
	}
} else {
	get_template_part( 'template-parts/dt/front', 'banner-map' );
}

	global $wp_query;
	$pgid 		  = $wp_query->post->ID;
	$frontpage_id = get_option( 'page_on_front' );
 	
	$search_listing = get_field('search_listing','option');
	if($cms == 1 && $search_listing == 1){
		echo '<style>div#cp-banner {margin-bottom:0px;}</style>';

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			echo $abc = get_site_url().'/listings/';
			if(isset($_POST['listing_keyword'])){
				$listing_key = sanitize_text_field($_POST['listing_keyword']);
			}
			if(isset($_POST['add_listing_keyword1'])){
				$add_listing_keyword1 = sanitize_text_field($_POST['add_listing_keyword1']);
			}
			if(isset($_POST['dropdown_category4'])){
				$s_listing_category = sanitize_text_field($_POST['dropdown_category4']);
			}
			header( "Location:".$abc."?listing_keyword=".$listing_key."&add_listing_keyword1=".$add_listing_keyword1."&s_listing_category=".$s_listing_category);
		}
	?>
	<section class="listing_search">
		<div class="inner">
			<form id="home_search" method="post">
				<?php get_template_part( 'template-parts/dt/front', 'search-listing' ); ?>
			</form>
		</div>
	</section>
	<?php }  
	if(get_field('wc_title',$frontpage_id)) :
	?>

	<!--- welcome section of home page 
	Screenshort : https://prnt.sc/1uchsbw -->
	<section class="cp-section welcome-section">
		<div class="inner">
			<div class="cp-title">
				<h2><?php the_field('wc_title', $frontpage_id); ?></h2>
			</div>
			<div class="cp-entry-content text-center">
				<?php the_field('wc_content', $frontpage_id); ?>
			</div>
			<div class="welcome-btn text-center">
				<?php
				if( get_field('button', $frontpage_id) ): ?>
					<?php while( has_sub_field('button', $frontpage_id) ): ?>
						<?php $val = get_sub_field('url',get_the_ID()); ?>
						<?php if(empty($val)):?>
							<a href="#!" class="global-btn"><?php the_sub_field('button_text', $frontpage_id); ?></a> 
						<?php else: ?>
							<a href="<?php echo $val['url']; ?>" class="global-btn <?php if($val['button_style'] =="Border"): ?> btn-solid <?php endif; ?>"> <?php the_sub_field('button_text', $frontpage_id); ?></a>
						<?php endif; ?>
					<?php endwhile;
				endif; ?>
			</div>
		</div>
	</section>
	<?php endif;

	if( !get_field('enable_featured_listing',$frontpage_id) ) get_template_part( 'template-parts/dt/front', 'featured' );
	if( !get_field('enable_new_listing',$frontpage_id) ) get_template_part( 'template-parts/dt/front', 'listing' ); ?>

<script>
	jQuery(document).mouseup(function(e) {
		var container = jQuery(".gm-style-iw");
		// if the target of the click isn't the container nor a descendant of the container
		if (!container.is(e.target) && container.has(e.target).length === 0) jQuery('.gm-ui-hover-effect').click();
	});
</script>

<?php 
wp_enqueue_script( 'carousel', get_template_directory_uri() . '/js/owl.carousel.min.js?'.time(), array( 'jquery' ) );
wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.13.1/jquery-ui.min.js?'.time(), array( 'jquery' ) );
get_footer();