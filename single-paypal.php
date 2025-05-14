<?php
/**
 * The template for displaying all single posts

 */

get_header(); 

// include Stripe
require_once 'stripe_pay.php'; 

?>

<?php
	$side_img = get_field('featured_image');
	$sizeimg = "full"; // (thumbnail, medium, large, full or custom size)
	$side_image = wp_get_attachment_image_src( $side_img, $sizeimg );
	$content = get_the_excerpt();


	$stripe_api_key = get_field('stripe_api_key', 'option');
	$stripe_publish_key = get_field('stripe_publish_key', 'option');
?>
<script src="https://js.stripe.com/v2/"></script>
<script>
// Set your publishable key
Stripe.setPublishableKey('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');

// Callback to handle the response from stripe
function stripeResponseHandler(status, response) {
    if (response.error) {
        // Enable the submit button
        jQuery('#payBtn').removeAttr("disabled");
        // Display the errors on the form
        jQuery(".payment-status").html('<p>'+response.error.message+'</p>');
    } else {
        var form$ = jQuery("#paymentFrm");
      
        var token = response.id;
        
        form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
        //$form.append($('<input type="hidden" name="stripeToken">').val(token));
        form$.get(0).submit();
    }
}

jQuery(document).ready(function() {
    var form$ = jQuery("#paymentFrm");
    jQuery("#paymentFrm").submit(function() {
        // Disable the submit button to prevent repeated clicks
        jQuery('#payBtn').attr("disabled", "disabled");
		
        // Create single-use token to charge the user
        Stripe.createToken({
            number: jQuery('#card_number').val(),
            exp_month: jQuery('#card_exp_month').val(),
            exp_year: jQuery('#card_exp_year').val(),
            cvc: jQuery('#card_cvc').val()
        }, stripeResponseHandler);
		
		//Stripe.card.createToken(form$, stripeResponseHandler);
		
        // Submit from callback
        return false;
    });
});
</script>



<div id="page-banner" class="<?php if(get_field('featured_image')) : ?>has-banner blog-banner<?php endif; ?>" style="background: <?php if(get_field('featured_image')) : ?>url('<?php echo $side_image[0]; ?>') no-repeat center center transparent;<?php else: ?>#212121<?php endif; ?>;">
  <div class="inner">
    <h1><?php the_title(); ?></h1>
  </div><!--inner-->
</div><!--page-banner-->

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


<!-- function for payment get from user -->
<div id="cp-container" class="cp-section">
  <div class="inner">
    <div class="blog-content">		
		<?php while ( have_posts() ) : the_post(); ?>
		   <?php the_content(); ?>

			<div class="paypal-entry">
				
<div class="paypal-info">

    <label><?php echo __('Purchase :', 'directorytheme'); ?></label>	
  
  <select name="listing_nm" id="listing_nm" >
  	<option value="<?php the_field('paypal_name'); ?>"><?php the_field('paypal_name'); ?></option>
  </select>

	
	               <div class="item-field_description" style="margin-top:10px;"><?php the_field('paypal_description'); ?></div>

<?php


 global $ppcurrency, $monthly_cycle,$ppprice,$ppid,$arr,$recurr;
			$arr = array();
 $Prod_nm = get_the_title(); 
		
	$ppid= get_the_ID();
				
				$variable = get_field('payment_mode', $ppid);		    	
		    	
		    	echo '<label>'.$variable.'</label>';		    	
		    	
				$ppcurrency=get_post_meta($ppid,'currency',true);
				$monthly_cycle = get_post_meta($ppid,'monthly_cycle',true);
		    	if($variable == 'Regular Price'){
					$ppprice=get_post_meta($ppid,'paypal_price',true);
					
					echo '<div class="item-field_price">Price : <span>'.$ppprice.' '.$ppcurrency.'</span></div> ';
			    }
			
			/***/
			
			if($variable == 'Recurring Price'){
				$variable1 = get_field('payment_method',$ppid); 
			    $recurr = 'recurring';
				
				echo '<form id="pay_opt">';
				/*value get in radio button*/
						echo '<div class="row">';
						 foreach ($variable1 as $data1)
                        { ?>
						<div class="col-sm-6 col-md-6 col-lg-3">
						 <?php global $rec_price,$smonth,$syear;
							    $nm = strtolower($data1);
                                $rec_price = get_field($nm.'_price', $ppid);
								$smonth = $rec_price; 
						?>
						<input type="radio" id="<?php echo $data1; ?>" name="installment_price1" data-val="<?php echo $rec_price; ?>" value="<?php echo $data1; ?>" style="margin : 10px;" class="trigger"><?php echo $data1;
							
						  
                                echo '<div class="item-field_price recu_price '.$nm.'" data-val="' . $rec_price . '"><span>' . $rec_price . ' ' . $ppcurrency . '</span>';
									$cycle = get_post_meta($ppid, $nm.'_cycle', true);
									if($nm == 'daily'){
										echo "<span class='cycle_cls'> / per day till ".$cycle." days </span>";
										array_push($arr,'daily'.$cycle); 
									}
									else{ 
										$new_str = substr_replace($nm ,"",-2); 										 
										echo "<span class='cycle_cls'> / per ". $new_str." till ".$cycle." ".$new_str."s</span>";
										
										array_push($arr,$nm.$cycle); 
									}
								echo '</div>';
						?>
						</div>
						<?php
						}
						echo '</div>';
				echo '</form>';
				
						
						
                    }
					
					//payment options
					$pay_method = get_field('pay_method', $ppid);
					echo '<div class="pay_wrap" style="margin-top:20px">';
					echo '<div class="item-field_price"><b>Payment Options</b></div>';
					if(!empty($pay_method)):
					foreach ($pay_method as $paymeth){ 
						
						if($paymeth == 'Stripe'):
							
							if(!empty($stripe_api_key) && !empty($stripe_publish_key)) :                  
						?>
								<input type="radio" value="Stripe" name="pay_method" class="pay_trigger">Stripe<br>
							<?php  endif;               
						elseif($paymeth == 'Paypal') : 
								$default_popt = 'Paypal';                                
								$paypal_email = get_field('paypal_email', 'option');
								if(!empty($paypal_email)):
						?>							
							<input type="radio" value="Paypal" name="pay_method" class="pay_trigger">Paypal<br>
						<?php endif; endif;
						
					} //stripe foreach
					else : ?>
						<input type="radio" value="Paypal" name="pay_method" class="pay_trigger">Paypal<br>
					<?php endif;
					echo '</div>';		


 wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>

	<?php 
	
global $myemail; 
$myemail = get_field('paypal_email', 'option');

// $ppcurrency;
if($variable == 'Regular Price'){ 

	$ppmid = get_the_ID();   
	$ppmname=get_post_meta($ppmid,'paypal_name',true);	
	$ppmdesc =get_post_meta($ppmid,'paypal_description',true);
	$ppmprice=get_post_meta($ppmid,'paypal_price',true);
	

 ?>
<!-- when user pay for listing using regular payment. 
	Screenshort : https://prnt.sc/1umr7v0 -->
<div class="Paypal_fm">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="on0" value="Listing">  
					<input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="business" value="<?php echo $myemail; ?>">
<?php    

		
		echo '<input type="hidden" name="amount" value="'.$ppmprice.'">';
	

	?>				
                    <input type="hidden" name="item_name" value="<?php the_field('paypal_name'); ?>">
                  
					<input type="hidden" name="currency_code" value="<?php the_field('currency'); ?>">

                    <input type="hidden" name="no_shipping" value="2">
                    <input type="hidden" name="no_note" value="0">
         
                    <input type="hidden" name="country" value="US">
					<input type="hidden" name="bn" value="PP-BuyNowBF">
					<input type="hidden" name="return" value="<?php echo home_url(); ?>/account/">
					<button type="submit" class="global-btn" id="btn_sub"><?php the_field('paypal_button', 'option'); ?></button>	
	 </form>

</div>

<?php } 

/****/
if($variable == 'Recurring Price'){ 
$val = '<p id="demo"></p>';
echo $val;
?>
<!-- when user pay for listing using recurring payment. 
	Screenshort : https://prnt.sc/1umrbwp -->
<div class="Paypal_fm">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="year">
<input type="hidden" name="business" value="<?php echo $myemail; ?>">
<input type="hidden" name="on0" value="Listing">
<input type="hidden" name="cmd" value="_xclick-subscriptions">
<input type="hidden" name="item_name" value="<?php the_field('paypal_name'); ?>">
<input type="hidden" name="item_number" value="1">
<input type="hidden" name="currency_code" value="<?php echo $ppcurrency; ?>">
<input id="srt_optional" type="hidden" name="srt" value="0">
<input id="for_srt" type="hidden" name="a3" value="1">
<input type="hidden" name="p3" value="1">
<input type="hidden" name="t3" value="">
<input type="hidden" name="src" value="1">
<input type="hidden" name="sra" value="0">
<input type="hidden" name="no_note" value="1" />
<input type="hidden" name="return" value="<?php echo home_url(); ?>/account/">
<input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
<img alt="" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
<button type="submit" class="global-btn"><?php if(!empty(get_field('paypal_button', 'option'))) the_field('paypal_button', 'option'); else echo __('Paypal Payment', 'directorytheme');?></button>
</form>
</div>
<?php } // Recurring Price

wp_reset_postdata(); 
 //endif;

 ?>					
<!--- stipe payment-->


<!--Stripe--->
<?php if(!empty($stripe_api_key) && !empty($stripe_publish_key)) :  ?>
<div class="panel Stripe_fm" style="margin-top:20px;">
 <form action="<?php echo get_site_url(); ?>/account/" method="POST" id="paymentFrm">
        <div class="panel-heading">
            <h2 style="color : #6773e6;">Stripe</h2>			
        </div>
		<?php 
		if($variable == 'Regular Price'): ?>
			<input type="hidden" name="stripe_meth" id="stripe_meth" value= "regular">
		    <input type="hidden" name="listing_snm" id="listing_snm" class="form-control" value="<?php echo $Prod_nm;?>">
			<input type="hidden" name="samt" id="samt" class="form-control" value="<?php echo $ppprice;?>">
		<?php endif;
			if($variable == 'Recurring Price'): ?>
				<input type="hidden" name="stripe_meth" id="stripe_meth" value= "recurring">
				<input type="hidden" name="listing_snm" id="listing_snm" class="form-control" value="<?php echo $Prod_nm;?>">
				<input type="hidden" name="subscr_plan" id="subscr_plan" class="form-control" value="">
				<input type="hidden" name="plannm" id="plannm" class="form-control" value="">
				<input type="hidden" name="plan_price" id="plan_price" class="form-control" value="">
				<input type="hidden" name="plan_interval" id="plan_interval" class="form-control" value="">				
				<input type="hidden" name="srt_stripe" id="srt_stripe" class="form-control" value="">
			<?php endif;
		?>
		
		
        <input type="hidden" name="scurrency" id="scurrency" class="form-control" value="<?php echo $ppcurrency;?>">
		
		<div class="panel-body">
            <!-- Display errors returned by createToken -->
            <div class="card-errors"></div>
			
            <!-- Payment form -->
			<div class="form-group row">
				<label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo __('NAME', 'directorytheme'); ?></label>
				<div class="col-sm-6">
				  <input type="text" name="name1" id="name1" class="form-control" placeholder="<?php echo __('Enter name', 'directorytheme'); ?>" required="" autofocus="">
				</div>
			</div>
			<div class="form-group row">
				<label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo __('EMAIL', 'directorytheme'); ?></label>
				<div class="col-sm-6">
				  <input type="email" name="email" id="email" placeholder="<?php echo __('Enter email', 'directorytheme'); ?>" required="" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo __('CARD NUMBER', 'directorytheme'); ?></label>
				<div class="col-sm-6">
				  <input type="text" name="card_number" id="card_number" class="form-control" placeholder="1234 1234 1234 1234" maxlength="16" autocomplete="off" required="">
				</div>
			</div>
			<div class="form-group row">
				<label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo __('EXPIRY DATE', 'directorytheme'); ?></label>
				<div class="col-sm-2">
				  <input type="text" name="card_exp_month" id="card_exp_month" placeholder="MM" maxlength="2" required="" class="form-control">
				</div>
				<div class="col-sm-2">
				  <input type="text" name="card_exp_year" id="card_exp_year" placeholder="YYYY" maxlength="4" required="" class="form-control">
				</div>
			</div>       
            
           <div class="form-group row">
				<label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo __('CVC CODE', 'directorytheme'); ?></label>
				<div class="col-sm-2">
				  <input type="text" name="card_cvc" id="card_cvc" placeholder="<?php echo __('CVC', 'directorytheme'); ?>" maxlength="3" autocomplete="off" required="" class="form-control">
				</div>
			</div>
               </div>
			   <button type="submit" class="btn btn-success" id="payBtn"><?php if(!empty($stripe_btn)) echo $stripe_btn; else echo 'Stripe Payment';?></button>
        </div>
    </form>
	</div>
<?php endif; ?>
<!--stripe payment over-->

             
</div><!--paypal-info-->					
					
               
			</div><!--cp-entry-->
		<?php endwhile; ?>		
	</div><!--blog-content-->
  </div><!--inner-->
</div><!--cp-container-->

<?php endif; ?>   

<!--- new script--->
<script>
jQuery(document).ready(function(){
 
	var arrayFromPHP = [];
	
	var arrayFromPHP = <?php echo json_encode($arr); ?>;
	
	var i, x = "",thenum = "",n = "";

	for (i = 0; i < arrayFromPHP.length; i++) {
	x += arrayFromPHP[i] + "<br>";
	thenum += arrayFromPHP[i].match(/\d+/);
   n += arrayFromPHP[i].includes("daily");
  
	}
	//document.getElementById("demo").innerHTML = n;

if(jQuery.isEmptyObject(arrayFromPHP)) {
   
    
    if(jQuery('input[name=pay_method]').val() == 'Paypal'){
        jQuery("input[name=pay_method][value='Paypal']").prop("checked",true);
        jQuery(".Stripe_fm").hide();
    }
    else{
       jQuery("input[name=pay_method][value='Stripe']").prop("checked",true);
       jQuery(".Paypal_fm").hide();
    }
        
    var default_meth = jQuery('input[name="pay_method"]:checked').val();
    //alert(default_meth);
    if(default_meth == 'Paypal'){
    	jQuery(".stripe_pay").hide();	
    }
    else if(default_meth == 'undefined'){
    	jQuery(".stripe_pay").show();
    	jQuery(".recu_cls").hide();
    }
    else{
    	jQuery(".stripe_pay").show();
    	jQuery(".recu_cls").hide();
    }
    
    jQuery('.pay_trigger').click(function()
    {
    if(jQuery(this).val()=="Paypal")
    {
    jQuery(".Paypal_fm").show();
    }
    else
    {
    jQuery(".Paypal_fm").hide();
    }
    if(jQuery(this).val()=="Stripe")
    {
    jQuery(".Stripe_fm").show();
    }
    else
    {
    jQuery(".Stripe_fm").hide();
    }
    });
    	
    
    
}//regular payment
else{
	
	jQuery(".Paypal_fm,.Stripe_fm").css("display", "none");

	jQuery('input[type=radio][name=pay_method]').change(function() {
		var a = jQuery('input[name="pay_method"]:checked').val();		
		var getSelectedValue = jQuery('input[name="installment_price1"]:checked').val(); 
		
		if(getSelectedValue == null) {   
				 alert("Please Select Payment Option.");  
				 jQuery( "input[type=radio][name=pay_method]" ).prop( "checked", false );
				 jQuery("."+ a +"_fm").css("display", "none");									 
		}
		else{
						
			if(a == 'Paypal') { 
				jQuery(".Paypal_fm").css("display", "block");
				jQuery(".Stripe_fm").css("display", "none");			
				
				jQuery.fn.myFunction = function(getSelectedValue,res){ 
						
						var text = "";
						var j = 0;
						  while (j < arrayFromPHP.length) {
							text += arrayFromPHP[j] + "<br>";
							var n = arrayFromPHP[j].includes(res);
							if(n == true){					
								var thenum = arrayFromPHP[j].match(/\d+/);
								var res1 = getSelectedValue.charAt(0);
														
								
								jQuery("input[name='p3']").val(thenum);
								
								jQuery("input[name='t3']").val(res1);
								
								var r_price = jQuery('input[name="installment_price1"]:checked').attr('data-val'); 
								jQuery("input[name='a3']").val(r_price);
								
								break;
							}
							j++;
						  } //while loop
				} //function				
				
				//default radio button val
				var res = getSelectedValue.toLowerCase();
				jQuery.fn.myFunction(getSelectedValue,res);
				
				//radio button value change
				jQuery('input[name="installment_price1"]').change(function(){						
					if (jQuery(this).is(':checked'))
					{	
						var getSelectedValue1 = jQuery(this).val();					  
						var res1 = getSelectedValue1.toLowerCase();		
						jQuery.fn.myFunction(getSelectedValue1,res1);
					}		
				});	

			  //document.getElementById("demo").innerHTML = text;
				
			}  // if paypal select
			else if(a == 'Stripe'){ 
				jQuery(".Stripe_fm").css("display", "block");
				jQuery(".Paypal_fm").css("display", "none");
				
				var Prod_nm = "<?php echo $Prod_nm;?>";				
				
				jQuery.fn.myFunction = function(res){ 
						
						var text = "";
						var j = 0;
						  while (j < arrayFromPHP.length) {
							text += arrayFromPHP[j] + "<br>";
							var n = arrayFromPHP[j].includes(res);	
							if(n == true){		
								var thenum = arrayFromPHP[j].match(/\d+/);
								var res1 = res.slice(0,-2);
								if(res1 == 'dai'){
									var res2 = res.slice(0,-3);
									var res1 = res2 + 'y';									
								}
								
								jQuery("input[name='plannm']").val(Prod_nm + ' ('+ res +' subscription)');
								var r_price = jQuery('input[name="installment_price1"]:checked').attr('data-val'); 
								jQuery("input[name='plan_price']").val(r_price);
								jQuery("input[name='plan_interval']").val(res1);
								jQuery("input[name='srt_stripe']").val(thenum);	
								jQuery("input[name='subscr_plan']").val(res +' stripe_recurring');
																	
								break;
							}
							j++;
						  } //while loop
				} //function
				
				//default radio button val
				var res = getSelectedValue.toLowerCase();
				jQuery.fn.myFunction(res);
				
				//radio button value change
				jQuery('input[name="installment_price1"]').change(function(){						
					if (jQuery(this).is(':checked'))
					{	
						var getSelectedValue1 = jQuery(this).val();					  
						var res1 = getSelectedValue1.toLowerCase();		
						jQuery.fn.myFunction(res1);
					}		
				});				
				
			} // if stripe select
			
			else jQuery("."+ a +"_fm").css("display", "none");
		}
	});

}//recurring payment
 	
});
</script>
<?php get_footer();