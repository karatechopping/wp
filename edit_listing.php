<?php 
	acf_form_head(); 
	/**
	 * Template Name: edit_listing
	 */
	get_header();
	global $choices_array1;
	$post_id = $_GET['list_id'];	
	//if listing type premium then don't go to again on payment page.
	$post_status = get_post_status( $post_id );
	if ($post_status == 'publish') {
		//if(get_field('listing_type',$post_id) != 'free'):		
		if(get_post_meta($post_id, 'listing_type',true) != $choices_array1[0]):		
	        update_post_meta( $post_id, 'premium_pay_status', 'pay_done' );	
	       	delete_post_meta( $post_id, 'where_to_come');
		else:
			delete_post_meta( $post_id, 'premium_pay_status');
			update_post_meta( $post_id, 'where_to_come', 'edit_listing' );
		endif;
	}
	/*end */
	$disable_banner = get_field('disable_banner','option');
	$listing_color = get_field('listing_bnbg_color','option');
	$listing_bg_img = get_field('listing_bg_img','option');
?>
<script>
window.history.forward();
</script>
<!-- This code is used for Banner section of page.
    Scrrenshort : https://prnt.sc/1ubgrwf -->
<div class="cp-header-title" style="background-color : <?php echo $listing_color; ?>";>
	<div class="inner">       
		<h1><?php if(!empty($post_id)): echo 'Edit :'; endif; echo ' '.get_the_title($post_id); ?></h1>
    </div>
</div>
<div id="cp-container" class="cp-section">
    <div class="inner">
		<?php 
			if ( is_user_logged_in() ) {
				if(!empty($post_id)): 
		?>
				    <div style="position: relative;display: block;z-index:13;">
				        <a href="<?php echo get_post_permalink($post_id);?>"><i class="fa fa-window-close my_close_btn"></i></a>
				    </div>
    	<?php 
					/* This code is used for check  for field display or not on add listing from pricing page.
    				Scrrenshort : https://prnt.sc/1ubl3uw */	
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
								var exist_field = ['5a5567c297a42','5a5567c297187','5a4df4a8y3r17','5a2fb4cc6eddf','direction-on-map','5a4df4y3er02w','5a2fb4f96ede0','5a0552cd48d5f','5a556a21dc86b','5a2fb4ff6ede1','5a2fb51a6ede2','5a2fb52e6ede3','5a2fb53e6ede4', '5aa8eb5906999','5aa8ec230plm4','5ba9ec231plh8','5ba9fc231poh2','5ba3gc231pod4','5ba3gc234pjl7','5ba3gc23dfvx','5a430c5235231','5b28570780cc1'];
								var difference1 = jQuery(exist_field).not(arrayFromPHP).get();
								jQuery.each(difference1, function(index, value){    	
									jQuery(".acf-field-"+ value).css({"display": "none"});
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
 
        <?php 
        			if ( is_user_logged_in() ) :     
        				$edit_listing = get_post_meta( $post_id, 'where_to_come', true );
        				$key_1_value = get_post_meta( $post_id, 'premium_pay_status', true );
        				$key_1_value2 = get_post_meta ( $post_id, 'g_rating', true );
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
							'default_value' => '',
							'layout' => 'horizontal',
							'return_format' => 'value',
							'parent' => 'group_5a2df2c2e8d84',
						));
						if ( ! empty( $key_1_value ) && $key_1_value == 'pay_done') {
		                    $args = array(
		                    	'post_id' => $post_id,
		                    	'field_groups' => array('group_5a2df2c2e8d84'),
		                    	'form' => true,
		                    	'html_before_fields' => '<input type="hidden" name="g_rating" value="'.$key_1_value2 .'"/><input type="hidden" name="g_review_no" value="5"/><input type="hidden" name="action" value="edit_listing"/>',
		                    	'html_after_fields' => '',
		                    	'return' => get_permalink($post_id),
		                    	'post_status'  => 'draft',
		                    	'submit_value' => 'Update',
		                    	'updated_message' => 'Update done.'
		                	);
		        			acf_form( $args );
            			} else{
            				$myfileds_array = array('field_5a508d051115011,field_5a5567c297a42','field_5a5567c297187','field_5a4df4a8y3r17','field_5a2fb4cc6eddf','field_direction-on-map','field_5a4df4y3er02w','field_5a2fb4f96ede0','field_5a0552cd48d5f','field_5a556a21dc86b','field_5a2fb4ff6ede1','field_5a2fb51a6ede2','field_5a2fb52e6ede3','field_5a2fb53e6ede4','field_5aa8eb5906999','field_5aa8ec230plm4','field_5ba9ec231plh8','field_5ba9fc231poh2','field_5ba3gc231pod4','field_5ba3gc234pjl7','field_5ba3gc23dfvx','field_5a430c5235231','field_5b28570780cc1');
		            		acf_form(array(
		                        //'id' => 'acf-form1',
		                        'post_id' => $post_id,
		                        /*'new_post'		=> array(
		                            'post_type'		=> 'listings',
		                            'post_status'		=> 'draft'
		                        ),*/
		                        'post_status'		=> 'draft',
		                        'post_title' => true,   // it's necessary for free to premium listing goes payment
		                        //'fields' => $myfileds_array,
		                        'fields' => false,
		                        //'post_content' => false,
		                        'field_groups' => array('group_5a2df2c2e8d84'),                        
		                        //'form'               => true,    
		                        'html_before_fields' => '<input type="hidden" name="g_rating" value="'.$key_1_value2 .'"/><input type="hidden" name="g_review_no" value="5"/><input type="hidden" name="action" value="edit_listing"/>',
		                        'html_after_fields'  => '',
		                        'return' =>  get_permalink($post_id),
		                        'updated_message' => __("Update Done.", 'acf'),
		                        'html_updated_message'	=> '<div class="alert alert-success text-center" role="alert">%s</div>',
		                        'uploader' 			 => 'wp',
		                        'submit_value'		=> 'Update',
		                        'html_submit_button'	=> '<input type="submit" class="acf-button button button-primary button-large" value="%s" />',
		                        
		                    ));
                		}
        			endif; 
        ?>
        			<div id="cp-header" style="display:inline-block;background:none !important;">
            			<ul class="submit-listing navbar-nav edit_cancle">
                        	<li><a href="<?php echo get_post_permalink($post_id); ?>">Cancel</a></li>
            			</ul>
        			</div>
		<?php 
				endif; 
			} //user login or not
		?>
    </div><!--- inner --->
</div>
<script>
	jQuery('label[for="acf-_post_title"]').html("Name <span class='acf-required'>*</span>");
	jQuery('label[for="acf-_post_title"],#acf-_post_title').hide();
</script>
<style>
	.my_close_btn {display: inline-block;
	    text-align: center;
	    width: 50px;
	    height: 50px;
	    float: right;
	    font-size: 30px;}
	.edit_cancle{max-width:140px;font-size: 17px;}
		#ui-datepicker-div{display: none !important;}
	#cp-header .navbar-nav.submit-listing li a{border-radius : 0px !important;text-transform:capitalize;}
	.ui-timepicker-container {
	    z-index: 99 !important;
	}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.0.0/jquery.datetimepicker.js?<?php echo time();?>"></script> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css?<?php echo time(); ?>">
 <script>
 	var selected_price = 1;
 	jQuery(document).ready(function(){
 		var op_no = 1;
		jQuery('.acf-field-5a508d0511150 ul li').each(function(){
			jQuery(this).find('input[type="radio"]').attr('price-op-no',op_no);
			if(jQuery(this).find('input[type="radio"]').is(':checked')){
				selected_price = op_no;
			}
			op_no = op_no + 1;
		});

		jQuery('#acf-form #acf-form-data').append('<input type="hidden" id="listing_paid_or_not" name="listing_paid_or_not" value="'+selected_price+'">');
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

  	jQuery('.acf-hl li').click(function(){
  			var op_no = jQuery(this).find('label input').attr('price-op-no');
  			var selected_op_val = jQuery('#listing_paid_or_not').val();
  			jQuery('#listing_paid_or_not').val(op_no);
  			if(op_no > selected_op_val){
  					jQuery('input[type="submit"].acf-button').val('Upgrade Your Listing');
  			}else{
  					jQuery('input[type="submit"].acf-button').val('Update');
  			}
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
<?php
get_footer();