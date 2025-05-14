<?php acf_form_head(); ?>
<?php
/**
 * Template Name: Account
 */
?>
<?php get_header(); ?>

<?php
session_start();
if (isset($_POST['action']) && $_POST['action'] === 'purchase_with_coupon') {
    if (!empty($_SESSION['apply_coupon']['apply_coupon_id'])) {
        $current_user_id = get_current_user_id();
        $coupon_id = $_SESSION['apply_coupon']['apply_coupon_id'];
        $applied_user = get_post_meta($coupon_id, 'applied_user', true);

        if (empty($applied_user)) {
            $applied_user = [];
        }

        if (array_key_exists($current_user_id, $applied_user)) {
            $applied_user[$current_user_id]++;
        } else {
            $applied_user[$current_user_id] = 1;
        }

        update_post_meta($coupon_id, 'applied_user', $applied_user);

        $applied_coupon_cnt = get_post_meta($coupon_id, 'applied_user_cnt', true);

        if (empty($applied_coupon_cnt)) {
            $applied_coupon_cnt = 1;
        } else {
            $applied_coupon_cnt++;
        }

        update_post_meta($coupon_id, 'applied_user_cnt', $applied_coupon_cnt);
        $_SESSION['apply_coupon'] = [];
    }
}


/* This code is used for when payment sucess of listing.*/
if (isset($_GET['st']) || isset($_GET['PayerID'])) {
    if (isset($_GET['st']) && $_GET['st'] == 'Completed' || isset($_GET['PayerID'])) {
        $success_msg = __('Thank you, your payment is successfully done.', 'directorytheme');
        echo '<div class="pptymsg">' . $success_msg . '</div>';
        $current_user_id = [];

        if (!empty($_SESSION['apply_coupon']['apply_coupon_id'])) {
            $current_user_id = get_current_user_id();
            $coupon_id = $_SESSION['apply_coupon']['apply_coupon_id'];
            $applied_user = get_post_meta($coupon_id, 'applied_user', true);

            if (empty($applied_user)) {
                $applied_user = [];
            }

            if (array_key_exists($current_user_id, $applied_user)) {
                $applied_user[$current_user_id]++;
            } else {
                $applied_user[$current_user_id] = 1;
            }

            update_post_meta($coupon_id, 'applied_user', $applied_user);

            $applied_coupon_cnt = get_post_meta($coupon_id, 'applied_user_cnt', true);

            if (empty($applied_coupon_cnt)) {
                $applied_coupon_cnt = 1;
            } else {
                $applied_coupon_cnt++;
            }

            update_post_meta($coupon_id, 'applied_user_cnt', $applied_coupon_cnt);
            $_SESSION['apply_coupon'] = [];
        }
    }
}

/* This code is used for check stripe payment.*/

// Include configuration file
require_once 'stripe_pay.php';

// Get user ID from current SESSION
$userID = isset($_SESSION['loggedInUserID'])?$_SESSION['loggedInUserID']:1;

$payment_id = $statusMsg = $api_error = '';
$ordStatus = 'error';

if(!empty($_POST['stripeToken']) && $_POST['stripe_meth'] == 'regular'){

    // Retrieve stripe token, card and user info from the submitted form data
    $token  = sanitize_text_field($_POST['stripeToken']);
    $name = sanitize_text_field($_POST['name1']);
    $email = sanitize_email($_POST['email']);
    $card_number = sanitize_text_field(preg_replace('/\s+/', '', $_POST['card_number']));
    $card_exp_month = sanitize_text_field($_POST['card_exp_month']);
    $card_exp_year = sanitize_text_field($_POST['card_exp_year']);
    $card_cvc = sanitize_text_field($_POST['card_cvc']);
    $description = sanitize_text_field($_POST['listing_snm']);
	$amt = sanitize_text_field($_POST['samt']);
	$curr = sanitize_text_field($_POST['scurrency']);

    require_once 'stripe-php/init.php';

    $priceCents = round($amt*100);
   // Set API key
    \Stripe\Stripe::setApiKey(STRIPE_API_KEY);

	try {
		$subscription = \Stripe\Charge::create(array(
			"amount" => $priceCents,
			"currency" => $curr,
			"source" => sanitize_text_field($_POST['stripeToken']),
			"description" => $description,
		));
	}catch(Exception $e) {
		$api_error = $e->getMessage();
	}

	if(empty($api_error) && $subscription){
		$subsData = $subscription->jsonSerialize();
		if($subsData['status'] == 'succeeded'){
			$subscrID  = $subsData['id'];
			$ordStatus = __('success', 'directorytheme');
			$statusMsg = __('Your Payment has been Successful!', 'directorytheme');

			if(!empty($_SESSION['apply_coupon']['apply_coupon_id'])){
	      		$current_user_id = get_current_user_id();
	      		$coupon_id = $_SESSION['apply_coupon']['apply_coupon_id'];
	      		$applied_user = get_post_meta($coupon_id,'applied_user',true);

	      		if(!empty($applied_user)){
	      			if(array_key_exists($current_user_id, $applied_user)){
	      				$applied_user[$current_user_id] = $applied_user[$current_user_id] + 1;
	      			}else{
	      				$applied_user = array($current_user_id => 1);
	      			}
	      		}else{
	      			$applied_user = array($current_user_id => 1);

	      		}
	      		update_post_meta($coupon_id,'applied_user',$applied_user);

	      		$applied_coupon_cnt = get_post_meta($coupon_id,'applied_user_cnt',true);
	      		if(!empty($applied_coupon_cnt)){
	      			$applied_coupon_cnt = $applied_coupon_cnt + 1;
	      		}else{
	      			$applied_coupon_cnt = 1;
	      		}
	      		update_post_meta($coupon_id,'applied_user_cnt',$applied_coupon_cnt);
	      		$_SESSION['apply_coupon'] = array();
	      	}
		} else {
			$statusMsg = __("failed!", "directorytheme");
		}
	} else {
		$statusMsg = __("Payment failed! ".$api_error."", "directorytheme");
	}
} elseif(!empty($_POST['subscr_plan']) && !empty($_POST['stripeToken'])){
    $token  = $_POST['stripeToken'];
    $name = $_POST['name1'];
    $email = sanitize_email($_POST['email']);
    $card_number = sanitize_text_field(preg_replace('/\s+/', '', $_POST['card_number']));
    $card_exp_month = sanitize_text_field($_POST['card_exp_month']);
    $card_exp_year = sanitize_text_field($_POST['card_exp_year']);
    $card_cvc = sanitize_text_field($_POST['card_cvc']);
	$description = sanitize_text_field($_POST['listing_snm']);
	$curr = sanitize_text_field($_POST['scurrency']);
	$interval_cnt = sanitize_text_field($_POST['srt_stripe']);
    $cancel_date = strtotime('+'.$interval_cnt.' months');

    // Plan info
    $planID 	  = sanitize_text_field($_POST['subscr_plan']);
    $planName 	  = sanitize_text_field($_POST['plannm']);
    $planPrice	  = sanitize_text_field($_POST['plan_price']);
    $planInterval = sanitize_text_field($_POST['plan_interval']);

    require_once 'stripe-php/init.php';

    \Stripe\Stripe::setApiKey(STRIPE_API_KEY);

    // Add customer to stripe
    $customer = \Stripe\Customer::create(array(
        'email' => $email,
        'name' => $name,
        'source'  => $token,
        'description' => $description
    ));

    $priceCents = round($planPrice*100);

    // Create a plan
    try {
		$plan = \Stripe\Plan::create(array(
			"product" => [
				"name" => $planName
			],
			"amount" => $priceCents,
			"currency" => $curr,
			"interval" => $planInterval,
			"interval_count" => 1 //compulsory pass 1 month
		));
    } catch(Exception $e) {
        $api_error = $e->getMessage();
    }

    if(empty($api_error) && $plan){
        try {
			$subscription = \Stripe\Subscription::create(array(
				"customer" => $customer->id,
				"items" => array(
					array(
						"plan" => $plan->id,
					),
				),
			));
        } catch(Exception $e) {
            $api_error = $e->getMessage();
        }
        if(empty($api_error) && $subscription){
            $subsData = $subscription->jsonSerialize();
            if($subsData['status'] == 'active'){

                $subscrID = $subsData['id'];
                $custID = $subsData['customer'];
                $planID = $subsData['plan']['id'];
                $planAmount = ($subsData['plan']['amount']/100);
                $planCurrency = $subsData['plan']['currency'];
                $planinterval = $subsData['plan']['interval'];
                $planIntervalCount = $subsData['plan']['interval_count'];
                $created = date("Y-m-d H:i:s", $subsData['created']);
                $current_period_start = date("Y-m-d H:i:s", $subsData['current_period_start']);
                $current_period_end = date("Y-m-d H:i:s", $subsData['current_period_end']);
                $status = $subsData['status'];
                $ordStatus = __('success', 'directorytheme');
                $statusMsg = __('Your Subscription Payment has been Successful!', 'directorytheme');


                if(!empty($_SESSION['apply_coupon']['apply_coupon_id'])){
		      		$current_user_id = get_current_user_id();
		      		$coupon_id = $_SESSION['apply_coupon']['apply_coupon_id'];
		      		$applied_user = get_post_meta($coupon_id,'applied_user',true);

		      		if(!empty($applied_user)){
		      			if(array_key_exists($current_user_id, $applied_user)){
		      				$applied_user[$current_user_id] = $applied_user[$current_user_id] + 1;
		      			}else{
		      				$applied_user = array($current_user_id => 1);
		      			}
		      		}else{
		      			$applied_user = array($current_user_id => 1);

		      		}
		      		update_post_meta($coupon_id,'applied_user',$applied_user);

		      		$applied_coupon_cnt = get_post_meta($coupon_id,'applied_user_cnt',true);
		      		if(!empty($applied_coupon_cnt)){
		      			$applied_coupon_cnt = $applied_coupon_cnt + 1;
		      		}else{
		      			$applied_coupon_cnt = 1;
		      		}
		      		update_post_meta($coupon_id,'applied_user_cnt',$applied_coupon_cnt);
		      		$_SESSION['apply_coupon'] = array();
		      	}
            }else{
                $statusMsg = __("Subscription activation failed!", "directorytheme");
            }
        }else{
            $statusMsg = __("Subscription creation failed! ".$api_error, "directorytheme");;
        }
    }else{
        $statusMsg = __("Plan creation failed! ".$api_error, "directorytheme");
    }

}else{
    $statusMsg = __("Error on form submission, please try again.", "directorytheme");
}
?>


<?php  get_template_part('/template-parts/header/page-banner'); ?>


<?php
/* This code is used for check user login or not
Scrrenshort : https://prnt.sc/1ubbk1k
*/
  if ( !is_user_logged_in() ) : ?>
<div id="cp-container" class="cp-section">
	<div class="inner">

		<?php
			global $error_msg, $succress;
			get_template_part('/template-parts/dt/front-registration');
			if($error_msg !='') { ?>
				<div class="alert alert-danger text-center" role="alert">
	  				<?php echo __($error_msg, 'directorytheme'); ?>
				</div>
			<?php }else if($succress!='') { ?>
				<div class="alert alert-success text-center" role="alert">
					<?php echo __($succress, 'directorytheme'); ?>
				</div>
			<?php }else{ ?>
				<div class="alert alert-info text-center" role="alert">
			    		<?php echo __('You need to Register or Be Logged in.', 'directorytheme'); ?>
		  		</div>
			<?php }  ?>
		<br />
    	<div class="register-login">
			<?php
			global $wpdb;
			if(isset($_GET['reset'])&&isset($_GET['uid'])&& !empty($_GET['uid'])) {
				if(isset($_POST['reqpwd'])){
					$fp= sanitize_text_field($_POST['rspwd']);
					$sp= sanitize_text_field($_POST['cfmrspwd']);
					if($fp==$sp) {
						$usid= substr($_GET['uid'],32);
						wp_set_password($fp,$usid);
						echo "<div class='ep_err_msg'><p class='pwd_set'>Password changed.</p></div>";
					} else {
						echo "<div class='ep_err_msg'><p class='pwd_notset'>Password doesn't match</p></div>";
					}
				}
				echo '<div class="row">
				<div class="resetfrm">
				<h3>Enter New Password</h3>
				<form method="post">
				<div style="margin-bottom:20px;">
				<input type="password" name="rspwd">
				<label>New password:</label>
				</div>
				<div style="margin-bottom:20px;">
				<input type="password" name="cfmrspwd">
				<label>Confirm password:</label>
				</div>
				<input type="submit" value="Save" name="reqpwd">
				</form>
				</div>
				</div>';
			} else  {
			?>
			<div class="row">
				<div class="col-md-6">
					<div class="rl-form divider r-form">
						<?php echo do_shortcode('[simple-registration-form]'); ?>
						<div class="rl-or">
							<span><?php _e('OR', 'directorytheme'); ?></span>
						</div>
					</div><!--rl-form-->
				</div><!--col-->
				<div class="col-md-6">
					<div class="rl-form l-form">
						<?php echo do_shortcode('[simple-login-form]'); ?>
					</div><!--rl-form-->
				</div><!--col-->
			</div><!--row-->
		<?php } ?>
      	</div><!--register-login-->
 	 </div><!--inner-->
</div><!--cp-container-->

<?php else :

if(!empty($_POST['stripeToken'])) : ?>
	<div class="status">
        <h1 class="<?php echo $ordStatus; ?>"><?php $statusMsg; ?>
		</h1>
        <script>jQuery(document).ready(function(){
			var a = '<?php echo $statusMsg; ?>';
				alert(a);
		});</script>
    </div>
<?php endif; ?>

<div class="custom-author">
	<div class="inner">
			<p><strong><?php echo __('Howdy', 'directorytheme'); ?></strong>,
				<?php
					if(!empty(get_user_meta($current_user->ID,'first_name',true)) && !empty(get_user_meta($current_user->ID,'last_name',true))):
						echo get_user_meta($current_user->ID,'first_name',true).' '.get_user_meta($current_user->ID,'last_name',true);
					elseif(!empty(get_user_meta($current_user->ID,'first_name',true)) && empty(get_user_meta($current_user->ID,'last_name',true))):
						echo get_user_meta($current_user->ID,'first_name',true).' ';
					elseif(empty(get_user_meta($current_user->ID,'first_name',true)) && !empty(get_user_meta($current_user->ID,'last_name',true))):
						echo get_user_meta($current_user->ID,'last_name',true).' ';
					else:
						echo $current_user->display_name;
					endif;
				?>
				<!-- <a href="<?php echo wp_logout_url($home_url); ?>"><?php echo __('Logout', 'directorytheme'); ?></a> -->
			</p>
	</div>
</div>
<section class="custom-tab-wrapper">
	<div class="inner">
		<!-- Tab Code Start Here -->
		<div class="tabs d-flex align-items-center justify-content-start flex-wrap">
			<input type="radio" name="tab-btn" id="tab-btn-1" value="" checked>
			<label for="tab-btn-1">Your Listing</label>
			<input type="radio" name="tab-btn" id="tab-btn-2" value="">
			<label for="tab-btn-2">Pending Listings</label>
			<input type="radio" name="tab-btn" id="tab-btn-3" value="">
			<label for="tab-btn-3">Claim Listing</label>

			<section class="new-listing-section tab-data w-100" id="content-1">
				<div class="inner">
					<?php
					global $current_user;
					wp_get_current_user();
					$home_url = get_the_permalink();
					?>
					<h2><?php echo __('Your Listings', 'directorytheme'); ?></h2>
					<?php
						$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
						$nl = new WP_Query( array(
							'post_type' 	 => 'listings',
							'posts_per_page' => '30',
							'paged' => $paged,
							'author' 		 => $current_user->ID,
							//'posts_per_page' => '-1',
							'post_status' 	 => 'publish',
						) );
						if ( $nl->have_posts() ) :
							while ( $nl->have_posts() ) : $nl->the_post();
								?>
								<div class="col-sm-6">
									<div class="nl-listing">
										<div class="row">
											<div class="col-lg-5 <?php echo get_field('listing_type');?>_5b28570780cc1">
												<div class="new-listing">
													<div class="nl-img">
														<?php
		 													$post_img = get_field('featured_image');
															if (is_numeric($post_img)) {
																$post_img = wp_get_attachment_url($post_img);
															} else {
																$post_img = get_field('featured_image');
															}
															$dtsimg  = get_field('s_default_featured_image','option');
															$ftdimg  = get_the_post_thumbnail_url();
															$post_id = get_the_ID();
															$dorfi 	 = get_field('s_default_featured_image','option');
														?>
														<a href="<?php the_permalink(); ?>">
															<img src=" <?php if(!empty($ftdimg)) { echo $ftdimg; } else if(!empty($post_img)) { echo $post_img; } else if(!empty($dtsimg)) { echo $dtsimg; } else { echo bloginfo("template_url").'/images/Listing-Placeholder.png'; } ?>" alt="<?php the_title(); ?>" class="img-fluid" />
														</a>
           											</div><!--nl-img-->
												</div>
											</div><!--col-->
											<div class="col-lg-5 <?php echo get_field('listing_type');?>_default_listing_image" style="display: none;">
												<div class="new-listing">
													<div class="nl-img">
														<?php
															$dtsimg  = get_field('s_default_featured_image','option');
															$post_id = get_the_ID();
															$dorfi 	 = get_field('s_default_featured_image','option');
														?>
														<a href="<?php the_permalink(); ?>">
															<img src=" <?php if(!empty($dtsimg)) { echo $dtsimg; } else { echo bloginfo("template_url").'/images/Listing-Placeholder.png'; } ?>" alt="<?php the_title(); ?>" class="img-fluid" />
														</a>
           											</div><!--nl-img-->
												</div>
											</div>
											<div class="col-lg-7">
												<div class="new-listing-entry">
													<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
													<?php
														$reviews_num = get_comments_number();
														if( $reviews_num!="0" ) :
															?>
															<div class="detail-overview-rating-title<?php echo $post->ID; ?> nl-rating"><span class="ave-review<?php echo $post->ID; ?>"></span></div>
															<?php
														endif;
														$contact_address = get_field('address');
														if(!empty($contact_address['address'])):
															?>
															<address class="nl-address <?php echo get_field('listing_type');?>_5a4df4a8y3r17"> <i class="fa fa-map-o"></i>
																<?php
																	$address = explode( "," , $contact_address['address']);
																	echo $address[0]; //street number
																	if(array_key_exists('1', $address)){
																		echo ','.$address[1];
																	}
																	if(array_key_exists('2', $address)){
																		echo ','.$address[2]; //city, state + zip
																	}
																?>
															</address>
															<?php
														endif;
														if(get_field('phone')):
															?>
															<div class="nl-info <?php echo get_field('listing_type');?>_5a4df4y3er02w">
																<a href="tel:<?php the_field('phone'); ?>"><i class="fa fa-phone"></i> <span><?php the_field('phone'); ?></span></a>
															</div><!--listing-info-item-->
															<?php
														endif;
													?>
													<div class="dkp_btn">
														<a href="<?php the_permalink(); ?>" class="global-btn btn-small"><?php _e('View Details', 'directorytheme'); ?></a>
													</div>
												</div><!--new-listing-entry-->
											</div><!-- col-lg-7 -->
 											<!-- category shortcode-->
 											<div class="col-md-12 category_display" style="padding : 0;">
												<?php echo do_shortcode( '[displ_cat]' ); ?>
 											</div>
											<div class="col-md-12 mob_btn">
    											<a href="<?php the_permalink(); ?>" class="global-btn btn-small"><?php _e('View Details', 'directorytheme'); ?></a>
											</div>
										</div><!--row-->
									</div><!--nl-listing-->
								</div><!--col-->
								<?php
								// featured listing section
								get_template_part( 'template-parts/dt/front', 'listing-script' );
							endwhile;
							if(function_exists('wp_pagenavi')){
								wp_pagenavi( array( 'query' => $nl ) );
							}
							wp_reset_postdata();
						else :
							?>
							<p>
								<?php echo __('No listing added yet...', 'directorytheme'); ?>
							</p>
							<?php
						endif;
					?>
				</div><!--inner-->
			</section>
			<!--This code is used for display all listing on page.
			Scrrenshort : https://prnt.sc/1ubdxep -->
			<section class="new-listing-section-draft tab-data w-100" id="content-2">
				<div class="inner">
					<h2><?php _e('Your Pending Listings', 'directorytheme'); ?></h2>
					<?php
						$pnl = new WP_Query( array(
							'post_type' 	 => 'listings',
							'author' 		 => $current_user->ID,
							'posts_per_page' => '-1',
							'post_status' 	 => 'draft',
						) );
						if ( $pnl->have_posts() ) :
							while ( $pnl->have_posts() ) : $pnl->the_post();
								?>
								<div class="col-sm-6">
									<div class="nl-listing">
										<div class="row">
											<div class="col-lg-5">
												<div class="new-listing">
													<div class="nl-img">
														<?php
															$post_img = get_field('featured_image');
															if (is_numeric($post_img)) {
																$post_img = wp_get_attachment_url($post_img);
															} else {
																$post_img = get_field('featured_image');
															}
															$dtsimg  = get_field('s_default_featured_image','option');
															$ftdimg  = get_the_post_thumbnail_url();
															$post_id = get_the_ID();
															$dorfi   = get_field('s_default_featured_image','option');
														?>
														<a href="<?php the_permalink(); ?>">
															<img src="<?php if(!empty($ftdimg)) { echo $ftdimg; } else if(!empty($post_img)) { echo $post_img; } else if(!empty($dtsimg)) { echo $dtsimg; } else{ echo bloginfo("template_url").'/images/Listing-Placeholder.png'; } ?>" alt="<?php the_title(); ?>" class="img-fluid" />
														</a>
													</div><!--nl-img-->
												</div>
											</div><!--col-->
											<div class="col-lg-7">
												<div class="new-listing-entry">
													<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
													<?php
														$reviews_num = get_comments_number();
														if($reviews_num!="0") :
															?>
															<div class="detail-overview-rating-title<?php echo $post->ID; ?> nl-rating">
																<span class="ave-review<?php echo $post->ID; ?>"></span>
															</div>
															<?php
														endif;
														$contact_address = get_field('address');
														if(!empty($contact_address['address'])):
															?>
															<address class="nl-address"> <i class="fa fa-map-o"></i>
																<?php $address = explode( "," , $contact_address['address']);
																echo $address[0].', '; //street number
																echo $address[1].','.$address[2]; //city, state + zip
																?>
															</address>
															<?php
														endif;
														if(get_field('phone')):
															?>
															<div class="nl-info">
																<a href="tel:<?php the_field('phone'); ?>"><i class="fa fa-phone"></i> <span><?php the_field('phone'); ?></span></a>
															</div><!--listing-info-item-->
															<?php
														endif;
													?>
													<div class="dkp_btn">
														<a href="<?php the_permalink(); ?>" class="global-btn btn-small"><?php echo __('View Details', 'directorytheme'); ?></a>
													</div>
												</div><!--new-listing-entry-->
											</div><!-- col-lg-7 -->
											<div class="col-md-12 category_display" style="padding : 0;">
												<?php echo do_shortcode( '[displ_cat]' ); ?>
											</div>
											<div class="col-md-12 mob_btn">
												<a href="<?php the_permalink(); ?>" class="global-btn btn-small"><?php echo __('View Details', 'directorytheme'); ?></a>
											</div>
										</div><!--row-->
									</div><!--nl-listing-->
								</div><!--END col-sm-6 -->
								<?php
									// featured listing section
									get_template_part( 'template-parts/dt/front', 'listing-script' );
							endwhile; wp_reset_postdata();
							// This code is used for when no listing created.
							// Scrrenshort : https://prnt.sc/1ubdljg
						else :
							?>
							<p> <?php echo __('No listing added yet...', 'directorytheme'); ?> </p>
							<?php
						endif;
					?>
				</div><!--inner-->
			</section>

			<section id="content-3" class="tab-data w-100">
				<table class="table custom-table">
  					<thead>
    					<tr>
      						<th scope="col">Listing Title</th>
      						<th scope="col">Claim Listing Date</th>
      						<th scope="col">Claim Status</th>
    					</tr>
  					</thead>
  					<tbody>
  						<?php
							$all_clim_listings = new WP_Query( array(
								'post_type' 	 => 'claim_listing',
								'author' 		 => $current_user->ID,
								'posts_per_page' => '-1',
							) );
							if ( $all_clim_listings->have_posts() ) :
								while ( $all_clim_listings->have_posts() ) : $all_clim_listings->the_post();
									$clim_status = get_field('claim_status');
									?>
									<tr>
			      						<th><?php the_title(); ?></th>
			      						<td><?php echo get_the_date( 'Y/m/d' )." at ".get_the_date( 'h:i a' ); ?></td>
			      						<?php
			      							if($clim_status == 2){
      											echo '<td class="pending"><span>Pending</span></td>';
    										}else if($clim_status == 1){
												echo '<td class="approved"><span>Approved</span></td>';
											}else{
												echo '<td class="decline"><span>Declined</span></td>';
											}
			      						?>
			      					</tr>
									<?php
								endwhile; wp_reset_postdata();
							else :
								?>
								<tr> <th colspan="3" class="text-center"><?php echo __('No listing claimed yet...', 'directorytheme'); ?> </tr>
								<?php
							endif;
						?>
    				</tbody>
				</table>
			</section>
		</div>
		<!-- Tab Code End Here -->
	</div>
</section>
<script>
	var divs = jQuery(".new-listing-section").find('.col-sm-6');
	for(var i = 0; i < divs.length; i+=2) {
	  divs.slice(i, i+2).wrapAll("<div class='row'></div>");
	}
    var divs_draft = jQuery(".new-listing-section-draft").find('.col-sm-6');
	for(var i = 0; i < divs_draft.length; i+=2) {
	  divs_draft.slice(i, i+2).wrapAll("<div class='row'></div>");
	}
</script>
<?php endif; ?>
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
		                  	if($paymeth == 'social_media') array_push($my_field,'5ba9ec231plh8','5ba9fc231poh2','5ba3gc231pod4','5ba3gc234pjl7', '5ba3gc23dfvx');
                		}//foreach
              		endif;
              		$json_price_option[sanitize_title($a)]  = json_encode($my_field);
				}
				endif;
		} // if page is pricing
	}
?>
<script>
window.onload = function () {
	document.getElementById("password1").onchange = validatePassword;
	document.getElementById("password2").onchange = validatePassword;
}
function validatePassword(){
	var pass2 = document.getElementById("password2").value;
	var pass1 = document.getElementById("password1").value;
	if( pass1!=pass2 )
		document.getElementById("password2").setCustomValidity(<?php __("Passwords Don't Match", 'directorytheme') ?>);
	else
		document.getElementById("password2").setCustomValidity('');
		//empty string means no validation error
}
jQuery('#cp-container img').addClass('img-fluid');
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
<?php get_footer();