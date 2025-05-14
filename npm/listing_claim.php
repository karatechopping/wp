<?php

/* Notify admin on email when someone (user) claim on any listing */	

//notify admin
add_action('acf/save_post', 'my1_save_post');

function my1_save_post( $post_id ) {
	
	if( get_post_type($post_id) !== 'claim_listing' ) {
		return;	
    }
	if( is_admin() ) {		
		return;		
	}
	$claim_listingid = get_field('claim_listingid', $post_id);
	$claim_lst_per = get_permalink( $claim_listingid );
	$claim_new_url = get_permalink( $post_id );
	$post = get_post( $post_id );	
	$listname = get_the_title($post_id);
	$listnm = htmlspecialchars_decode($listname); 
	$firstnm = get_field('claim_firstnm', $post_id);
	$lastnm = get_field('claim_lastnm', $post_id);
	$email_id = get_field('claim_email', $post_id);
	$phno = get_field('claim_phone', $post_id);
	$details = get_field('claim_message', $post_id);
	$sitenm = get_bloginfo('name');
	$siteurl = get_bloginfo('url');
	
	$to = get_field('admin_cemail','option');

	$subject = 'Claim Listing Notification';
	
	if($to ==""){
		$to = get_bloginfo('admin_email');
	}	
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	 $headers .= 'From: '. $to . "\r\n" .
     'Reply-To: ' . $email_id . "\r\n";

	$body = "
			<html>
			<head>
			<title>Claim Listing Notification</title>
			</head>
			<body>
			<p>Your List <a href='$claim_lst_per' target='_blank'>$listnm</a> is Claimed by $firstnm $lastnm</p>
			<p>Email: $email_id</p>
			<p>Phone: $phno</p> 	
			<p>$details</p>
			<p>If You want to know more about this claim listing please Click here  : <a href='$siteurl' target='_blank'>$sitenm</a></p>					
			</body>
			</html>
		";
	
	# send email	
	wp_mail( $to, $subject, $body, $headers );
	
	//exit();
		
}
//end notify admin


?>
