<?php
	acf_form_head();
	/**
 	* Template Name: Add Listing
 	*/
	get_header();
?>
<script>
	window.history.forward();
</script>

<?php  get_template_part('/template-parts/header/page-banner'); ?>

<!-- Checks if user is loged in or not
Scrrenshort : https://prnt.sc/1ubbk1k
-->
<?php  if ( !is_user_logged_in() ) : ?>
	<div id="cp-container" class="cp-section">
  		<div class="inner">
			<?php
    			if ( have_posts() ) :
        			while ( have_posts() ) : the_post();
						$content = get_the_content();
						if($content){
							if(strlen($content) > 0) {
								echo '<div class="taxonomy-description">';
                   				the_content(); 
                				echo '</div>';
							}
        				}
        			endwhile;  
				endif;
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
			<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						$content = get_the_content();
						if($content){
							if(strlen($content) > 0) {
								echo '<div class="taxonomy-description">';
								the_content();
								echo '</div>';
							}
						}
					endwhile;
				endif;
			?>
			<!-- This code is used for check  for field display or not on add listing from pricing page.
				Scrrenshort : https://prnt.sc/1ubl3uw -->
			<div class="full-content add_listing_form">
				<?php
					$serialised = get_option('pricing_opt_ltype');
					/*all pages of wp*/
					$page_ids = get_all_page_ids();
					global $pricing_page;
					$choices_array = array();	
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
					} // 1st foreach loop
					$return = get_field('pay_listing_page', 'option');
				?>
				<script>
					jQuery(document).ready(function(){
						var price_option = [];
						<?php foreach($json_price_option as $key => $val): ?>
							  price_option['<?php echo $key; ?>'] = <?php echo $val; ?>;
						<?php endforeach; ?>
						jQuery.fn.myFunction = function(x) {  
							var arrayFromPHP = price_option[x];
							var exist_field = ['5a5567c297a42','5a5567c297187','5a4df4a8y3r17','5a2fb4cc6eddf','direction-on-map','5a4df4y3er02w','5a2fb4f96ede0','5a0552cd48d5f','5a556a21dc86b','5a2fb4ff6ede1','5a2fb51a6ede2','5a2fb52e6ede3','5a2fb53e6ede4','5aa8eb5906999','5aa8ec230plm4','5ba9ec231plh8','5ba9fc231poh2','5ba3gc231pod4','5ba3gc234pjl7', '5ba3gc23dfvx','5a430c5235231','5b28570780cc1'];
							var difference1 = jQuery(exist_field).not(arrayFromPHP).get();
							jQuery.each(difference1, function(index, value){   
								if(jQuery.inArray(value, arrayFromPHP)){
									jQuery(".acf-field-"+ value).css({"display": "none"});
								}	
							});	
							var difference = jQuery(exist_field).not(difference1).get();
							jQuery.each(difference, function(index, value){		
								jQuery(".acf-field-"+ value).css({"display": "block"});
							});				
						}
						var def_val = jQuery("input[type='radio']:checked").val().toLowerCase();
						jQuery.fn.myFunction(def_val); 
						jQuery(".acf-field-radio").change(function () {			
							var radiobtn = jQuery("input[type='radio']:checked").val().toLowerCase();
							jQuery.fn.myFunction(radiobtn);
						});
					});	
				</script>
				<!-- This code is used for add new listings.
					Scrrenshort : https://prnt.sc/1ubiy01 -->
				<?php 
					if(isset($_GET['listing_type']) && !empty($_GET['listing_type'])){
						$default_value = $_GET['listing_type'];
					}else{
						$default_value = '';
					}
				
					acf_add_local_field(array(
						'key' 	=> 'field_5a508d0511150',
						'label' => __( 'Listing Type', 'directorytheme' ),
						'name' 	=> 'listing_type',
						'type' 	=> 'radio',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' 	=> '',
						),
						'choices' => $choices_array,
						'allow_null' => 0,
						'other_choice' => 0,
						'save_other_choice' => 0,
						'default_value' => $default_value,
						'layout' => 'horizontal',
						'return_format' => 'value',
						'parent' => 'group_5a2df2c2e8d84'
					));

					acf_form(array(
						'post_id'		=> 'new_post',
						'new_post'		=> array(
							'post_type'		=> 'listings',
							'post_status'		=> 'draft'
						),
						'post_title' => true,
						'post_content' => false,					
						'field_groups' => array('group_5a2df2c2e8d84'),
						//'fields' => $my_field,
						'form' => true,
						'html_before_fields' => '',
						'html_after_fields'  => '<input type="hidden" name="action" value="add_listing">',
						'updated_message' => __("Success! Listings are reviewed in the order they are received.", 'acf'),
						'html_updated_message'	=> '<div class="alert alert-success text-center" role="alert">%s</div>',
						'uploader' 			 => 'wp',
						'submit_value'		=> __('Create a new Listing', 'directorytheme'),
						'html_submit_button'	=> '<input type="submit" class="acf-button button button-primary button-large" value="%s" />',
						//'return' => $return
					));		 
				?>		  
			</div>
		</div><!--inner-->
	</div><!--cp-container-->
<?php endif; ?>


<!-- #.# Embed the style and js the Wordpress way -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.0.0/jquery.datetimepicker.js?<?php echo time(); ?>"></script>  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css?<?php echo time(); ?>">
<script>
	jQuery(document).ready(function(){
		var op_no = 1;
		jQuery('.acf-field-5a508d0511150 ul li').each(function(){
			jQuery(this).find('input[type="radio"]').attr('price-op-no',op_no);
			op_no = op_no + 1;
		});
		jQuery('#acf-form #acf-form-data').append('<input type="hidden" id="listing_paid_or_not" name="listing_paid_or_not" value="1">');
	});
	const convertTime12to24 = (time12h) => {
		const time = time12h.slice(0, 5);
		const modifier = time12h.slice(-2);
		let [hours, minutes] = time.split(':');
		if (hours === '12') {
			hours = '00';
		}
		if (modifier === 'PM') {
			hours = parseInt(hours, 10) + 12;
		}
		return `${hours}:${minutes}`;
	}
  	jQuery('#cp-container img').addClass('img-fluid');
	jQuery('.acf-hl li:not(:first-child)').click(function(){
		jQuery('.acf-button').val('Purchase Your Listing');
		jQuery('#listing_paid_or_not').val(jQuery(this).find('label input').attr('price-op-no'));
	});
	jQuery('.acf-hl li:first-child').click(function(){
		jQuery('.acf-button').val('Create a new Listing');
		jQuery('#listing_paid_or_not').val('1');
	});
	jQuery(document).on('click','.acf-time-picker .input',function() {
		jQuery(".xdsoft_datetimepicker").css("width", jQuery(this).outerWidth()+"px");
		jQuery('.acf-time-picker .input').datetimepicker({
			datepicker:false,
			formatTime: 'h:i A',
			ampm: true, // FOR AM/PM FORMAT
			format : 'h:i A',
			// step: 30, // For 30min interval
			onSelectTime: function(ct, $input){
				$input.prev("input").val(ct.dateFormat('H:i:s'));
			},
			onClose:function(dp,$input){
				$input.prev("input").val(dp.dateFormat('H:i:s'));
			},
			forceParse: false,
			showLeadingZero: false,
			allowInputToggle:true,
			validateOnBlur:false
		}).on('keyup', function(){
			jQuery('.xdsoft_datetimepicker').hide();
			if(jQuery(this).val() != ''){
				jQuery(this).prev("input").val(jQuery.trim(convertTime12to24(jQuery(this).val()))+":00");
			}
		});
	});
</script>
<style>
.ui-timepicker-container {
    z-index: 99 !important;
}
</style>
<?php get_footer();