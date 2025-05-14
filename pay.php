<?php
/**
 * Template Name: Pay
 *
 */

get_header(); ?>


<?php
	$side_img = get_field('featured_image');
	$sizeimg = "full"; // (thumbnail, medium, large, full or custom size)
	$side_image = wp_get_attachment_image_src( $side_img, $sizeimg );
	$content = get_the_excerpt();
?>

<div id="page-banner" class="<?php if(get_field('banner')) : ?>has-banner<?php endif; ?>" style="background: <?php if(get_field('banner')) : ?>url('<?php the_field('banner'); ?>') no-repeat <?php the_field('bg_position'); ?> transparent;<?php endif; ?>;">
  <div class="inner">
    <h1><?php the_title(); ?></h1>
  </div><!--inner-->
</div><!--page-banner-->


<?php
global $wpdb;
/*echo $wpdb->base_prefix;
echo 123;*/
?>


<?php  if ( !is_user_logged_in() ) : ?>
<div id="cp-container" class="cp-section">
  <div class="inner">
	  <div class="alert alert-info text-center" role="alert">
		  <?php echo __('You need to Register in order to pay for your business listing.', 'directorytheme'); ?>
	  </div><br />	  
      <div class="register-login">
		<div class="row">
		  <div class="col-md-6">
			 <div class="rl-form divider r-form">
				 <?php echo do_shortcode('[simple-registration-form]'); ?>
				 <div class="rl-or">
					 <span><?php echo __('OR', 'directorytheme'); ?></span>
				 </div>
			 </div><!--rl-form--> 
		  </div><!--col-->	
		  <div class="col-md-6">
			 <div class="rl-form l-form">
				 <?php echo do_shortcode('[simple-login-form]'); ?>
			 </div><!--rl-form--> 
		  </div><!--col-->		
		</div><!--row-->
      </div><!--register-login-->
  </div><!--inner-->
</div><!--cp-container-->

<?php else : ?>


<div id="cp-container" class="cp-section">
  <div class="inner">
    <div class="blog-content">

		
		<?php while ( have_posts() ) : the_post(); ?>
		   <?php the_content(); ?>

			<div class="paypal-entry">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					
<?php

$post_object = get_field('product', 'option');

if( $post_object ): 

	// override $post
	$post = $post_object;
	setup_postdata( $post ); 
	
	?>					
					<div class="item-field_name"><h3><?php the_field('paypal_name'); ?></h3></div>
					<div class="item-field_description"><?php the_field('paypal_description'); ?></div>
<div class="paypal-info">
	<?php
		foreach ($post as $value) {
			$ppid = $value->ID;
			$pptit = $value->post_title;
			if($pptit == 'Premium Listing') {
				$ppprice=get_post_meta($ppid,'paypal_price',true);
				$ppcurrency=get_post_meta($ppid,'currency',true);
				echo '<div class="item-field_price">Price: <span>'.$ppprice.' '.$ppcurrency.'</span></div> ';
			}
		}
	?>
									
					<!-- <div class="item-field_price">Price: <span><?php the_field('paypal_price'); ?> <?php the_field('currency'); ?></span></div>  -->

<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
<?php endif; ?>						


	  
<?php 
	global $current_user, $choices_array1; 

?>

<?php $paypal = new WP_Query( 
	array( 
		'post_type' => 'listings', 
		/*'author' => $current_user->ID, */	
		'posts_per_page' => '-1',
		'post_status' => array('publish'),
		'meta_query' => array(
			array(
				'key' => 'listing_type',
				'value' => $choices_array1[0],
				'compare' => '!='
			)
		)		
	) 
);

if ( $paypal->have_posts() ) : ?>
					
<!-- Provide a drop-down menu option field. -->
  <input type="hidden" name="on0" value="Listing">  
  <label><?php echo __('Please allow 48-72 hours for premium listings to be published.', 'directorytheme'); ?></label>	
  <select name="os0">
	<?php while ( $paypal->have_posts() ) : $paypal->the_post(); ?>	  
		<option value="<?php the_title(); ?>"><?php the_title(); ?></option>
	<?php endwhile; wp_reset_postdata(); ?>
  </select>
<?php endif; ?>  					

					

					<input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="business" value="<?php the_field('paypal_email', 'option'); ?>">
<?php

$post_object = get_field('product', 'option');
if( $post_object ): 

	// override $post
	$post = $post_object;
	setup_postdata( $post ); 

	?>	
	<?php 
		/*$cpid=get_the_ID();
		echo $cpid;
		if(get_field('pricing')):
			while(has_sub_field('pricing')): 
				echo '<span class="abc">'; the_sub_field('pricing'); echo '</span>'; 
			endwhile; 
		endif; 



		echo 'in if cond';

		if( have_rows('pricing') ):
			echo 'under if';		
		    while ( have_rows('pricing') ) : the_row();		    
		        the_sub_field('pricing');
		    endwhile;
		else :
			echo 'in else cond';
		endif;*/

$pppost = new WP_Query( 
	array( 
		'post_type' => 'paypal', 
		'posts_per_page' => '-1',
		'post_status' => array('publish'),		
	) 
);
$ppid=$pppost->ID;
//$ppname=;
//echo get_post_meta($ppid,'paypal_name',true);
/*echo "<pre>";
print_r($post);
echo "</pre>";*/
foreach ($post as $value) {

$ppmid=$value->ID;
$ppmname=get_post_meta($ppmid,'paypal_name',true);
$ppmdesc=get_post_meta($ppmid,'paypal_description',true);
$ppmprice=get_post_meta($ppmid,'paypal_price',true);
/*echo $ppmname;
echo $ppmprice;*/
$ppmcurrency=get_post_meta($ppmid,'currency',true);
if($ppmname == "Premium Listing") {
	echo '<input type="hidden" name="item_name" value="'.$ppmdesc.'">';
	echo '<input type="hidden" name="amount" value="'.$ppmprice.'">';
	
}
}

	?>		

                   <!--  <input type="hidden" name="item_name" value="<?php echo $ppmname; ?>">
                    <input type="hidden" name="amount" value="<?php echo $ppmprice; ?>"> -->
					<input type="hidden" name="currency_code" value="<?php echo $ppmcurrency; ?>">
<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
<?php endif; ?>					

                    <input type="hidden" name="no_shipping" value="2">
                    <input type="hidden" name="no_note" value="0">
                    
                    <input type="hidden" name="country" value="ZA"><input type="hidden" name="bn" value="PP-BuyNowBF">
					<input type="hidden" name="return" value="<?php echo home_url(); ?>/account/">
					<button type="submit" class="global-btn"><?php the_field('paypal_button', 'option'); ?></button>	
	

</div><!--paypal-info-->					
					
                </form>
			</div><!--cp-entry-->


		<?php endwhile; ?>
 
    </div><!--blog-content-->
  </div><!--inner-->
</div><!--cp-container-->

<?php endif; ?>   

<script>
	jQuery('#cp-container img').addClass('img-fluid');
</script>


<?php get_footer();