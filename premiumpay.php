<?php
    /**
     * Template Name: Premium Pay
     *
     */
    get_header();
    global $choices_array1;
    session_start();
    // include Stripe
    require_once 'stripe_pay.php'; 
    $side_img = get_field('featured_image');
    $sizeimg = "full"; // (thumbnail, medium, large, full or custom size)
    $side_image = wp_get_attachment_image_src($side_img, $sizeimg);
    $content = get_the_excerpt();
    /* Check stripe key set in backend or not in general setting of Theme setting.
    Screenshort : https://prnt.sc/1umiicr */
    $stripe_api_key = get_field('stripe_api_key', 'option');
    $stripe_publish_key = get_field('stripe_publish_key', 'option');
?>
<script src="https://js.stripe.com/v2/"></script>
<style type="text/css">
    form#coupon_frm {
        margin-top: 20px;
    }
    .coupon_form_inner {
        display: flex;
        flex-wrap: wrap;
    }
    .coupon_form_inner label {
        width: 100%;
    }
    input#apply_coupn {
        width: 30%;
        height: 40px;
        border: 2px solid #000;
        border-width: 2px 0 2px 2px;
        padding: 0 20px;
        border-radius: 50px 0 0 50px;
        font-weight: bold;
    }
    .coupon_form_inner button[type="submit"] {
        /*background-color: #000;
        color: #fff;*/
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: bold;
        border-radius: 0 50px 50px 0;
        transition: 0.5s;
        width: 20%;
        cursor: pointer;
    }
    .coupon_form_inner button[type="submit"]:hover {
        box-shadow: 0 8px 10px -2px rgba(0,0,0,0.5);
    }
    .coupon_form_inner .global-btn{
        margin: 0 !important;
        padding: inherit;
    }
    :focus-visible {
        outline: none;
    }
    label.discard-coupon-code {
        width: 30%;
        height: 40px;
        border: 2px solid #000;
        border-width: 2px 0 2px 2px;
        padding: 0 20px;
        border-radius: 50px 0 0 50px;
        font-weight: bold;
        margin: 0;
        line-height: 36px;
    }
    span.strached {
        text-decoration: line-through;
        font-size: 16px !important;
        color: #8b8b8b !important;
        padding-left: 5px;
    }
    button.global-btn {
        border-radius: 5px;
        letter-spacing: 0;
    }
    button.global-btn:hover,button#payBtn:hover {
        border-color: #000;
        color: #fff;
        background-color: #000;
    }
    button#payBtn {
        font-size: .8em;
        padding: 12px 30px;
        display: inline-block;
        border-radius: 5px;
        border: 2px solid transparent;
        outline: 0;
        cursor: pointer;
        font-weight: 500;
        font-family: Poppins;
    }
</style>
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
    window.history.forward();
</script>
<?php 
    //check listing goes free to premium 
    global $choices_array1;
    $post_id = $_GET['post_id'];    
    $post_status = get_post_status( $post_id );
    if ($post_status == 'publish') {
        $payment_opt = get_post_meta( $post_id, 'premium_pay_status', true );    
        if(get_field('listing_type',$post_id) == $choices_array1[0]):       
            if(empty($payment_opt)){
                wp_update_post(array(
                    'ID'    =>  $post_id,
                    'post_status'   =>  'draft'
                ));
                $edit_listing = get_post_meta( $post_id, 'where_to_come', true );
                if(!empty($edit_listing)){
                    update_post_meta( $post_id, 'listing_type', $choices_array1[0] );    
                }        
            }        
       endif;
    }
    // if not pay for listing then it goes to draft
    $listing_type = get_post_meta($post_id,'listing_type',true);
    //apply coupon
    if(isset($_POST['apply_coupn_btn']) && $_POST['apply_coupn_btn'] == 'Apply'):
        if(!empty($_POST['apply_coupn'])):
            $post_type = 'coupon';
            $post_name = $_POST['apply_coupn'];
            global $wpdb;
            $coupon_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type= %s", $post_name, $post_type ) );
            if(!empty($coupon_id)):
                /*check coupon expired or not*/
                $coupon_exp_date = get_post_meta($coupon_id,'exp_date',true);
                if(!empty($coupon_exp_date)):
                    $exp_date = date('d/m/Y',strtotime($coupon_exp_date));
                    $current_date = date('d/m/Y');
                    $message = '';
                    if($current_date > $exp_date){
                        $message = "coupon code is expired";
                    }
                endif;
                $allow_coupon = get_post_meta($coupon_id,'usage_limit_per_coupon',true);
                $applied_coupon_cnt = get_post_meta($coupon_id,'applied_coupon_cnt',true);
                if(!empty($allow_coupon) && $allow_coupon < $applied_coupon_cnt){
                    $message = "coupon usage limit has been reached";
                }
                $allow_users = get_post_meta($coupon_id,'usage_limit_per_user',true);
                if(!empty($allow_users)){
                    $current_user_id = get_current_user_id();
                    $applied_user = get_post_meta($coupon_id,'applied_user',true);
                    if(!empty($applied_user)):
                        if(array_key_exists($current_user_id, $applied_user) && $applied_user[$current_user_id] >= $allow_users){
                            $message = "coupon usage limit has been reached";
                        }
                    endif;
                }
                $coupon_type = get_post_meta($coupon_id,'discount_type',true);
                if($coupon_type == 1){
                    $discount_type = 'fixed';
                }else{
                    $discount_type = 'Percentage';
                }
                $discount_val = get_post_meta($coupon_id,'discount_amount',true);
                if(empty($message)):
                    $_SESSION['apply_coupon'] = array(
                        'apply_coupon_id' => $coupon_id,
                        'apply_coupon_code' => $_POST['apply_coupn'],
                        'apply_coupn_type' => $discount_type,
                        'discount_val' => $discount_val,
                        'message' => $message,
                    );
                else:
                    $_SESSION['apply_coupon'] = array();
                endif;
            else:
                $message = "coupon code is invalid";
            endif;
        endif;
    endif;
    if(isset($_POST['discard_coupn_btn'])):
        $_SESSION['apply_coupon'] = array();
    endif;
    ?>
    <div id="page-banner" class="<?php if (get_field('banner')): ?>has-banner<?php endif; ?>" style="background: <?php if (get_field('banner')): ?>url('<?php the_field('banner'); ?>') no-repeat <?php the_field('bg_position'); ?> transparent ;<?php endif; ?>;">
        <div class="inner">
            <h1><?php the_title(); ?></h1>
        </div><!--inner-->
    </div><!--page-banner-->
    <?php 
        /* This code is used for check user login or not
        Scrrenshort : https://prnt.sc/1ubbk1k */
        if (!is_user_logged_in()): 
            ?>
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
        <?php
        else: 
            ?>
            <div id="cp-container" class="cp-section">
                <div class="inner">
                    <div class="blog-content">
                        <?php 
                            while (have_posts()):
                                the_post(); 
                                the_content(); 
                                ?>
                                <div class="paypal-entry">
                                    <div class="paypal-info">
                                        <?php 
                                            if(!empty($message)):
                                                echo '<div>'.$message.'</div>';
                                            endif;
                                            global $current_user; 
                                            /*when listing goes free to premium*/
                                            $post_id = $_GET['post_id'];
                                            /*end when*/
                                            // function for payment get from user
                                            if(!empty($post_id)):
                                                ?>
                                                <label class='pay_txt'><?php echo __('Please allow 48-72 hours for premium listings to be published.', 'directorytheme'); ?></label>
                                                <?php global $Prod_nm; ?>
                                                <select name="os0" id="listing_premium">
                                                    <option value="<?php  echo get_the_title( $post_id ); ?>"><?php  echo get_the_title( $post_id ); ?></option>
                                                    <?php $Prod_nm =  get_the_title( $post_id ); ?>
                                                    <?php wp_reset_postdata(); ?>
                                                </select>
                                                <?php
                                            else:
                                                $paypal = new WP_Query(array(
                                                        'post_type' => 'listings',
                                                        'order' => 'DESC',
                                                        'posts_per_page' => '1',
                                                        'post_status' => array(
                                                            'draft'
                                                        ) ,
                                                        'meta_query' => array(
                                                            array(
                                                                'key' => 'listing_type',
                                                                'value' =>  $choices_array1[0],
                                                                'compare' => '!='
                                                            )
                                                        )
                                                    ));
                                                if ($paypal->have_posts()): 
                                                    ?>
                                                    <!-- Provide a drop-down menu option field. -->
                                                    <label class='pay_txt'><?php echo __('Please allow 48-72 hours for premium listings to be published.', 'directorytheme');?></label>
                                                    <?php global $Prod_nm; ?>
                                                    <select name="os0" id="listing_premium">
                                                        <?php 
                                                            while ($paypal->have_posts()):
                                                                $paypal->the_post(); 
                                                                ?>
                                                                <option value="<?php the_title(); ?>"><?php the_title(); ?></option>
                                                                <?php $Prod_nm = get_the_title(); ?>
                                                                <?php
                                                            endwhile;
                                                            wp_reset_postdata(); 
                                                        ?>
                                                    </select>
                                                    <?php
                                                endif;
                                            endif;
                                            /* coupon code form*/
                                            $post_object = get_field('product', 'option');
                                            if(empty($post_object)):
                                                $post_object = get_field('stripe_product', 'option');
                                            endif;
                                            if ($post_object):
                                                $post = $post_object;
                                                setup_postdata($post);
                                                foreach ($post as $value){
                                                    $ppid = $value->ID;
                                                    $pptit = $value->post_title;
                                                    $variable1 = get_field('paypal_name', $ppid);
                                                    if (sanitize_title($variable1) == get_field('listing_type',$post_id) || sanitize_title($pptit) == get_field('listing_type',$post_id)):
                                                        $variable = get_field('payment_mode', $ppid);
                                                        if ($variable == 'Regular Price'){
                                                            ?>
                                                            <div>
                                                                <form id="coupon_frm" name="coupon_frm" method="post">
                                                                    <div class="coupon_form_inner">
                                                                        <?php
                                                                            if(!empty($_SESSION['apply_coupon'])):
                                                                                echo '<label class="discard-coupon-code">'.$_SESSION['apply_coupon']['apply_coupon_code'].'</label><button type="submit" name="discard_coupn_btn" class="global-btn" value="Discard">Discard</button>';
                                                                            else:
                                                                                echo '<label>apply coupon</label><input type="text" name="apply_coupn" id="apply_coupn"><button type="submit" class="global-btn" name="apply_coupn_btn" value="Apply">Apply</button>';
                                                                            endif;
                                                                        ?>
                                                                        
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <?php
                                                        }else{
                                                            $_SESSION['apply_coupon'] = array();
                                                        }
                                                    endif;
                                                }
                                            endif;
                                            /*coupon code form end*/
                                            if ($post_object):
                                                // override $post
                                                $post = $post_object;
                                                setup_postdata($post);
                                                ?>
                                                <div class="item-field_name"><h3><?php the_field('paypal_name'); ?></h3></div>
                                                <div class="item-field_description"><?php the_field('paypal_description'); ?></div>
                                                <?php
                                                global $ppcurrency, $monthly_cycle,$ppprice,$ppid,$arr;
                                                $arr = array();
                                                foreach ($post as $value){
                                                    $ppid = $value->ID;
                                                    $pptit = $value->post_title;
                                                    $variable1 = get_field('paypal_name', $ppid);
                                                    //if ($variable1 == 'Premium Listing' || $pptit == 'Premium Listing'):  
                                                    if (sanitize_title($variable1) == get_field('listing_type',$post_id) || sanitize_title($pptit) == get_field('listing_type',$post_id)):    
                                                        $variable = get_field('payment_mode', $ppid);
                                                        echo '<div class="item-field_description">'.the_field("paypal_description",$ppid).'</div>';
                                                        echo '<div class="item-field_price"><b>' . $variable . '</b></div>';
                                                        $ppcurrency = get_post_meta($ppid, 'currency', true);
                                                        $monthly_cycle = get_post_meta($ppid, 'monthly_cycle', true);
                                                        /* Check which option select by admin for payment mode*/
                                                        $newppprice = get_post_meta($ppid, 'paypal_price', true);
                                                        if ($variable == 'Regular Price'){
                                                            $ppprice = get_post_meta($ppid, 'paypal_price', true);
                                                            /*apply coupon to price*/ 
                                                            $newppprice = get_post_meta($ppid, 'paypal_price', true);
                                                            if(isset($_SESSION['apply_coupon']) && !empty($_SESSION['apply_coupon'])):
                                                                if($_SESSION['apply_coupon']['apply_coupn_type'] == 'fixed'):
                                                                    $newppprice = $ppprice - $_SESSION['apply_coupon']['discount_val'];
                                                                else:
                                                                    $dis_amt = ($ppprice * $_SESSION['apply_coupon']['discount_val']) / 100;
                                                                    $newppprice = $ppprice - $dis_amt;
                                                                endif;
                                                            endif;
                                                            //if(!empty($newppprice)){
                                                            if(isset($_SESSION['apply_coupon']) && !empty($_SESSION['apply_coupon'])){
                                                                echo '<div class="item-field_price">Price : <span>' . $newppprice . ' ' . $ppcurrency . '</span><span class="strached">' . $ppprice . ' ' . $ppcurrency . '</span></div> ';    
                                                            }else{
                                                                echo '<div class="item-field_price">Price : <span>' . $ppprice . ' ' . $ppcurrency . '</span></div> ';    
                                                            }
                                                        }   
                                                        if ($variable == 'Recurring Price'){
                                                            $variable1 = get_field('payment_method', $ppid);
                                                            echo '<form id="pay_opt">';
                                                            /*value get in radio button*/
                                                            echo '<div class="row">';
                                                            foreach ($variable1 as $data1){ 
                                                                ?>
                                                                <div class="col-sm-6 col-md-6 col-lg-3">
                                                                    <?php 
                                                                        global $rec_price,$smonth,$syear;
                                                                        $nm = strtolower($data1);
                                                                        $rec_price = get_field($nm.'_price', $ppid);
                                                                        $smonth = $rec_price; 
                                                                        ?>
                                                                        <input type="radio" id="<?php echo $data1; ?>" name="installment_price1" data-val="<?php echo $rec_price; ?>" value="<?php echo $data1; ?>" style="margin : 10px;" class="trigger">
                                                                        <?php 
                                                                        echo $data1;
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
                                                        if(isset($_SESSION['apply_coupon']) && !empty($_SESSION['apply_coupon']) && $newppprice <= 0){
                                                            ?>
                                                            <form id="purchase_with_coupon" name="purchase_with_coupon" method="post" action="<?php echo get_site_url().'/account/';?>">
                                                                <input type="hidden" name="post_id" value="<?php echo $_GET['post_id']; ?>">
                                                                <input type="hidden" name="action" value="purchase_with_coupon">
                                                                <button type="submit" class="global-btn">Purchase</button>
                                                            </form>
                                                            <?php
                                                        }else{
                                                            $pay_method = get_field('pay_method', $ppid);
                                                            echo '<div class="pay_wrap" style="margin-top:20px">';
                                                            echo '<div class="item-field_price"><b>'. __('Payment Options', 'directorytheme') .'</b></div>';
                                                            if(!empty($pay_method)):
                                                                foreach ($pay_method as $paymeth){ 
                                                                    if($paymeth == 'Stripe'):
                                                                        if(!empty($stripe_api_key) && !empty($stripe_publish_key)) :     
                                                                            ?>
                                                                            <input type="radio" value="Stripe" name="pay_method" class="pay_trigger">Stripe<br>
                                                                            <?php  
                                                                        endif;               
                                                                    elseif($paymeth == 'Paypal') : 
                                                                        $default_popt = 'Paypal';                                
                                                                        $paypal_email = get_field('paypal_email', 'option');
                                                                        if(!empty($paypal_email)):
                                                                            ?>                          
                                                                            <input type="radio" value="Paypal" name="pay_method" class="pay_trigger">Paypal<br>
                                                                            <?php 
                                                                        endif; 
                                                                    endif;
                                                                } //stripe foreach
                                                            else : 
                                                                ?>
                                                                <input type="radio" value="Paypal" name="pay_method" class="pay_trigger">Paypal<br>
                                                                <?php 
                                                            endif;
                                                            echo '</div>';
                                                        }
                                                    endif;          
                                                }       
                                                wp_reset_postdata(); 
                                                // IMPORTANT - reset the $post object so the rest of the page works correctly
                                            endif;
                                            /*regular price for form*/
                                            if(isset($_SESSION['apply_coupon']) && !empty($_SESSION['apply_coupon']) && $newppprice <= 0):

                                            else:
                                            //if($newppprice > 0 ):
                                                if ($post_object):
                                                    $post = $post_object;
                                                    setup_postdata($post);
                                                    $pppost = new WP_Query(array(
                                                        'post_type' => 'paypal',               
                                                        'posts_per_page' => '-1',
                                                        'post_status' => array(
                                                                'publish'
                                                            ) ,
                                                    ));
                                                    $ppid = $pppost->ID;
                                                    global $myemail;
                                                    $myemail = get_field('paypal_email', 'option');
                                                    if ($variable == 'Regular Price'){
                                                        $sandbox_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
                                                        $paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
                                                        ?>
                                                        <div class="Paypal_fm">
                                                            <form action="<?php echo $paypal_url;?>" method="post">
                                                                <input type="hidden" name="on0" value="Listing">
                                                                <input type="hidden" name="cmd" value="_xclick">
                                                                <input type="hidden" name="business" value="<?php echo $myemail; ?>">
                                                                <?php
                                                                    foreach ($post as $value){
                                                                        $ppmid = $value->ID;
                                                                        $ppmname = $value->post_title;
                                                                        $ppmdesc = get_post_meta($ppmid, 'paypal_description', true);
                                                                        $ppmprice = get_post_meta($ppmid, 'paypal_price', true);
                                                                        $newppprice = get_post_meta($ppmid, 'paypal_price', true);;
                                                                        if(isset($_SESSION['apply_coupon']) && !empty($_SESSION['apply_coupon'])):
                                                                            if($_SESSION['apply_coupon']['apply_coupn_type'] == 'fixed'):
                                                                                $ppprice = $ppmprice - $_SESSION['apply_coupon']['discount_val'];
                                                                                $newppprice = $ppmprice - $_SESSION['apply_coupon']['discount_val'];
                                                                            else:
                                                                                $dis_amt = ($ppmprice * $_SESSION['apply_coupon']['discount_val']) / 100;
                                                                                $ppprice = $ppmprice - $dis_amt;
                                                                                $newppprice = $ppmprice - $dis_amt;
                                                                            endif;
                                                                        endif;
                                                                        if (sanitize_title($ppmname) == $listing_type){
                                                                            echo '<input type="hidden" name="item_name" value="' . $Prod_nm . '">';
                                                                            echo '<input type="hidden" name="amount" value="' . $ppprice . '">';
                                                                        }
                                                                    }
                                                                ?>
                                                                <input type="hidden" name="currency_code" value="<?php echo $ppcurrency; ?>">
                                                                <input type="hidden" name="no_shipping" value="2">
                                                                <input type="hidden" name="no_note" value="0">
                                                                <input type="hidden" name="country" value="US">
                                                                <input type="hidden" name="bn" value="PP-BuyNowBF">
                                                                <input type="hidden" name="return" value="<?php echo home_url(); ?>/account/">
                                                                <button type="submit" class="global-btn"><?php the_field('paypal_button', 'option'); ?></button>
                                                            </form>
                                                        </div>
                                                        <?php
                                                    }
                                                    if ($variable == 'Recurring Price'){
                                                        $val = '<p id="demo"></p>';
                                                        echo $val;                
                                                        $sandbox_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
                                                        $paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
                                                        ?>
                                                        <div class="Paypal_fm">
                                                            <form action="<?php echo $paypal_url; ?>" method="post" id="year">
                                                                <input type="hidden" name="business" value="<?php echo $myemail; ?>">
                                                                <input type="hidden" name="on0" value="Listing">
                                                                <input type="hidden" name="cmd" value="_xclick-subscriptions">
                                                                <input type="hidden" name="item_name" value="<?php echo $Prod_nm; ?>">
                                                                <input type="hidden" name="item_number" value="1">
                                                                <input type="hidden" name="currency_code" value="<?php echo $ppcurrency; ?>">
                                                                <input id="srt_optional" type="hidden" name="srt" value="0">
                                                                <input id="for_srt" type="hidden" name="a3" value="1">
                                                                <input type="hidden" name="p3" value="1">
                                                                <input type="hidden" name="t3" value="D">
                                                                <input type="hidden" name="src" value="1">
                                                                <input type="hidden" name="sra" value="0">
                                                                <input type="hidden" name="no_note" value="1" />
                                                                <input type="hidden" name="return" value="<?php echo home_url(); ?>/account/">
                                                                <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
                                                                <img alt="" width="1" height="1"
                                                                src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
                                                                <button type="submit" class="global-btn"><?php if(!empty(get_field('paypal_button', 'option'))) the_field('paypal_button', 'option'); else echo __('Paypal Payment', 'directorytheme');?></button>
                                                            </form>
                                                        </div>
                                                        <?php
                                                    } // Recurring Price
                                                    wp_reset_postdata();
                                                endif;
                                            endif;
                                        ?>
                                        <!--- stripe payment-->
                                        <!--Stripe--->
                                        <?php if($newppprice > 0 ): ?>
                                            <?php if(!empty($stripe_api_key) && !empty($stripe_publish_key)) : ?>
                                                <div class="panel Stripe_fm">
                                                    <form action="<?php echo get_site_url(); ?>/account/" method="POST" id="paymentFrm">
                                                        <div class="panel-heading">
                                                            <h2 style="color : #6773e6;">Stripe</h2>
                                                        </div>
                                                        <?php 
                                                            if($variable == 'Regular Price'): 
                                                                $post = $post_object;
                                                                setup_postdata($post);
                                                                foreach ($post as $value){
                                                                    $ppmid = $value->ID;
                                                                    //$ppmname = get_post_meta($ppmid, 'paypal_name', true);
                                                                    $ppmname = $value->post_title;
                                                                    $ppmdesc = get_post_meta($ppmid, 'paypal_description', true);
                                                                    $ppmprice = get_post_meta($ppmid, 'paypal_price', true);
                                                                    $newppprice = '';
                                                                    if(isset($_SESSION['apply_coupon']) && !empty($_SESSION['apply_coupon'])):
                                                                        if($_SESSION['apply_coupon']['apply_coupn_type'] == 1):
                                                                            $ppprice = $ppmprice - $_SESSION['apply_coupon']['discount_val'];
                                                                        else:
                                                                            $dis_amt = ($ppmprice * $_SESSION['apply_coupon']['discount_val']) / 100;
                                                                            $ppprice = $ppmprice - $dis_amt;
                                                                        endif;
                                                                    endif;
                                                                    if (sanitize_title($ppmname) == $listing_type){
                                                                        echo '<input type="hidden" name="samt" id="samt" class="form-control" value="'.$ppprice.'">';
                                                                    }
                                                                }
                                                                ?>
                                                                <input type="hidden" name="stripe_meth" id="stripe_meth" value= "regular">
                                                                <input type="hidden" name="listing_snm" id="listing_snm" class="form-control" value="<?php echo $Prod_nm;?>">
                                                                <?php 
                                                            endif;
                                                            if($variable == 'Recurring Price'): ?>
                                                                <input type="hidden" name="stripe_meth" id="stripe_meth" value= "recurring">
                                                                <input type="hidden" name="listing_snm" id="listing_snm" class="form-control" value="<?php echo $Prod_nm;?>">
                                                                <input type="hidden" name="subscr_plan" id="subscr_plan" class="form-control" value="">
                                                                <input type="hidden" name="plannm" id="plannm" class="form-control" value="">
                                                                <input type="hidden" name="plan_price" id="plan_price" class="form-control" value="">
                                                                <input type="hidden" name="plan_interval" id="plan_interval" class="form-control" value="">             
                                                                <input type="hidden" name="srt_stripe" id="srt_stripe" class="form-control" value="">
                                                                <?php 
                                                            endif;
                                                        ?>
                                                        <input type="hidden" name="scurrency" id="scurrency" class="form-control" value="<?php echo $ppcurrency;?>">
                                                        <div class="panel-body">
                                                            <!-- Display errors returned by createToken -->
                                                            <div class="card-errors"></div>
                                                            <!-- Payment form -->
                                                            <div class="form-group row">
                                                                <label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo __('NAME', 'directorytheme'); ?></label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" name="name1" id="name1" class="form-control" placeholder="<?php echo __('Enter Name', 'directorytheme'); ?>" required="" autofocus="">
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
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <!--stripe payment over-->
                                </div><!--paypal-info-->
                                <!-- </div> --><!--cp-entry-->
                                <?php
                            endwhile; 
                        ?>
                    </div><!--blog-content-->
                </div><!--inner-->
            </div><!--cp-container-->
            <?php
        endif; 
?>
</div>
<!--- new script--->
<script>
jQuery(document).ready(function(){
    var arrayFromPHP = [];
    var arrayFromPHP = <?php echo json_encode($arr); ?>;
    //alert(arrayFromPHP);
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
        }else{
            jQuery("input[name=pay_method][value='Stripe']").prop("checked",true);
            jQuery(".Paypal_fm").hide();
        }
        var default_meth = jQuery('input[name="pay_method"]:checked').val();
        //alert(default_meth);
        if(default_meth == 'Paypal'){
            jQuery(".stripe_pay").hide();   
        }else if(default_meth == 'undefined'){
            jQuery(".stripe_pay").show();
            jQuery(".recu_cls").hide();
        }else{
            jQuery(".stripe_pay").show();
            jQuery(".recu_cls").hide();
        }
        jQuery('.pay_trigger').click(function(){
            if(jQuery(this).val()=="Paypal"){
                jQuery(".Paypal_fm").show();
            }else{
                jQuery(".Paypal_fm").hide();
            }
            if(jQuery(this).val()=="Stripe"){
                jQuery(".Stripe_fm").show();
            }else{
                jQuery(".Stripe_fm").hide();
            }
        });
    }else{
        jQuery(".Paypal_fm,.Stripe_fm").css("display", "none");
        jQuery('input[type=radio][name=pay_method]').change(function() {
            var a = jQuery('input[name="pay_method"]:checked').val();       
            var getSelectedValue = jQuery('input[name="installment_price1"]:checked').val(); 
            if(getSelectedValue == null) {   
                alert("Please Select Payment Option.");  
                jQuery( "input[type=radio][name=pay_method]" ).prop( "checked", false );
                jQuery("."+ a +"_fm").css("display", "none");                                    
            }else{
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
                        if (jQuery(this).is(':checked')){   
                            var getSelectedValue1 = jQuery(this).val();                   
                            var res1 = getSelectedValue1.toLowerCase();     
                            jQuery.fn.myFunction(getSelectedValue1,res1);
                        }       
                    }); 
                    //document.getElementById("demo").innerHTML = text;
                } else if(a == 'Stripe'){ 
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
                        if (jQuery(this).is(':checked')){   
                            var getSelectedValue1 = jQuery(this).val();                   
                            var res1 = getSelectedValue1.toLowerCase();     
                            jQuery.fn.myFunction(res1);
                        }       
                    }); 
                }else {
                    jQuery("."+ a +"_fm").css("display", "none");
                }
            }
        });
    }//recurring payment
});
</script>
<?php get_footer();