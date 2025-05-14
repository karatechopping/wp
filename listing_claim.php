<?php

/*claim listing*/
function acf_claim_listing_before_save_post($post_id) {
	if (empty($_POST['acf']))
		return;
	$ID = sanitize_text_field($_POST['acf']['field_5a56fda9782ec']);
	if(!empty($ID)){
	$listing_nm = get_the_title( $ID );
	$_POST['acf']['_post_title'] = $listing_nm;
	$_POST['acf']['claim_listingid'] = $ID;	
	$user_id = get_current_user_id();
	$user_info = get_userdata($user_id);
		
		$first_name = $user_info->first_name;
		$last_name = $user_info->last_name;
		$_POST['acf']['claim_firstnm'] = $first_name;
		$_POST['acf']['claim_lastnm'] = $last_name;
		$_POST['acf']['claim_userid'] = $user_id;		
	global $wpdb;		
	$table2 = $wpdb->prefix.'posts';
		
		 $default = $wpdb->get_results('SELECT post_author FROM '.$table2.' WHERE ID = "'.$ID.'"');
	//print_r($default);
	 $listing_author = $default[0]->post_author;
	$_POST['acf']['listing_author'] = $listing_author;
	//exit();
	}
	return $post_id;
}
add_action('acf/pre_save_post', 'acf_claim_listing_before_save_post', -1);


function claim_listing_user()
{ 
//print_r($_POST);   
	get_template_part( 'template-parts/claim_listing/claim', 'listing' );
	die();
}
add_action("wp_ajax_claim_listing_user","claim_listing_user");
add_action("wp_ajax_nopriv_claim_listing_user","claim_listing_user"); 

function se_10441544_save_post($post_id, $post){
    //determine post type
	
    if(get_post_type( $post_id ) == 'claim_listing')
    {       
		$claim_listingid = get_post_meta( $post_id, 'claim_listingid', true );
		global $wpdb;
		$table = $wpdb->prefix.'postmeta';	
		$default = $wpdb->get_results('SELECT DISTINCT a.post_id FROM '.$table.' as a,'.$table.' as b WHERE a.meta_key = "claim_listingid" and a.meta_value = "'.$claim_listingid.'"');
		
			if(!empty($default)){
			foreach($default as $default_id){
				$a = $default_id->post_id;				
				update_post_meta($a, 'claim_status',2);							
			}
		}
		//$get_event_name = $wpdb->get_results('SELECT DISTINCT a.post_id FROM '.$table.' as a,'.$table.' as b WHERE a.post_id != "'.$post_id.'" AND a.meta_key = "claim_listingid" and a.meta_value = "'.$claim_listingid.'" AND b.meta_key = "claim_status" AND b.meta_value = "approve" OR a.meta_key = "claim_status" AND a.meta_value = "denied"');
		
		
		
		update_post_meta($post_id, 'claim_status',2,1);
			
	}
			//exit();
    
}

add_action('publish_claim_listing', 'se_10441544_save_post', 10, 2);



add_action( 'save_post', 'claim_listing_quick_edit_save',10,4); 
function claim_listing_quick_edit_save( $post_id ){
 	
	if(get_post_type( $post_id ) == 'claim_listing')
    {
		$claim_listingid = get_post_meta( $post_id, 'claim_listingid', true );
		global $wpdb;		
		$table2 = $wpdb->prefix.'posts';
		$claim_userid = get_post_meta( $post_id, 'claim_userid',true);
		$new_st = get_post_meta($post_id, 'claim_status',true);
		
		
		/*for mail purpose*/
		$claim_listingid = get_field('claim_listingid', $post_id);
		$claim_lst_per = get_permalink( $claim_listingid );
		$claim_new_url = get_permalink( $post_id );
		$post = get_post( $post_id );	
		$listname = get_the_title($post_id);
		$listnm = htmlspecialchars_decode($listname); 
		$firstnm = get_field('claim_firstnm', $post_id);
		$lastnm = get_field('claim_lastnm', $post_id);
		$to = get_field('claim_email', $post_id);
		$phno = get_field('claim_phone', $post_id);
		$details = get_field('claim_message', $post_id);
		$sitenm = get_bloginfo('name');
		$siteurl = get_bloginfo('url');
		
		$from = get_field('admin_cemail','option');
		
		/*mail end*/
		
		
		/* This code is used for confirm listing claim by user */
		if($new_st == 2){	
            $wpdb->query($wpdb->prepare('update '.$table2.' SET post_author = "'.$claim_userid.'" WHERE ID = "'.$claim_listingid.'"'));	
			$subject = get_field('pending_subject','option');
			$msg = get_field('pending_message','option');
			if($from==""){
				$from = get_bloginfo('admin_email');
			}		
			if($subject==""){
				$subject = $sitenm.' Claim Confirmation for Claim for "'.$listnm.'"';
			}
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: '. $from . "\r\n" .
					'Reply-To: ' . $from . "\r\n";
			$body = "
					<html>
					<head>
					<title>Claim Listing</title>
					</head>
					<body>
					<p>Dear $firstnm $lastnm</p>
					<p>Just a quick note to inform you that your Your Claim for '$listnm' is currently pending. Our team is actively working on it and will provide an update as soon as possible.</p>
					<p>$msg</p> 	
					<p>Thank you for your patience!</p>
					<p>Thanks</p>					
					</body>
					</html>
					";
			# send email	
			wp_mail( $to, $subject, $body, $headers );
		}
		if($new_st == 1){	
					
            $wpdb->query($wpdb->prepare('update '.$table2.' SET post_author = "'.$claim_userid.'" WHERE ID = "'.$claim_listingid.'"'));	
					
			$subject = get_field('approve_subject','option');
			$msg = get_field('approve_message','option');
			
			
			if($from==""){
				$from = get_bloginfo('admin_email');
			}		
			if($subject==""){
				$subject = $sitenm.' Claim Confirmation for Claim for "'.$listnm.'"';
			}
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: '. $from . "\r\n" .
					'Reply-To: ' . $from . "\r\n";
			
			$body = "
					<html>
					<head>
					<title>Claim Listing</title>
					</head>
					<body>
					<p>Dear $firstnm $lastnm</p>
					<p>Congratulations! Your Claim for '$listnm' has been confirmed.</p>
					<p>You can now edit it from your dashbord <a href='$claim_lst_per' target='_blank'>$listnm</a></p> 	
					<p>$msg</p>
					<p>Thanks</p>					
					</body>
					</html>
					";
			
			# send email	
			wp_mail( $to, $subject, $body, $headers );
			//exit();
					
			
		}
		/* This code is used for Declined listing claim by user */
		if($new_st == 0){		
			$listing_author = get_post_meta($post_id,'listing_author',true);
			$table2 = $wpdb->prefix.'posts';		
            $wpdb->query($wpdb->prepare('update '.$table2.' SET post_author = "'.$listing_author.'" WHERE ID = "'.$claim_listingid.'"'));	
				
				
				
				$subject = get_field('declined_subject','option');
				$msg = get_field('declined_message','option');
				
				
				if($from==""){
					$from = get_bloginfo('admin_email');
				}			
				if($subject==""){
					$subject = $sitenm.' Claim Confirmation for Claim for "'.$listnm.'"';
				}
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$headers .= 'From: '. $from . "\r\n" .
					'Reply-To: ' . $from . "\r\n";
								
				$body = "
					<html>
					<head>
					<title>Claim Listing</title>
					</head>
					<body>
					<p>Dear $firstnm $lastnm</p>
					<p>Sorry! Your Claim for '$listnm' has been Declined.</p>
					<p>Please Contact with administator.</p> 	
					<p>The Administator of <a href='$siteurl' target='_blank'>$sitenm</a></p>
					<p>$msg</p>
					<p>Thanks</p>
					</body>
					</html>
					";
				
				
				# send email	
				wp_mail( $to, $subject, $body, $headers );
				//exit();				
						
		}
	}
	//exit();
}
?>