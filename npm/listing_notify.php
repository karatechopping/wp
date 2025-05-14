<?php

/* Notify admin on email when someone (user) create a new listing */		

//notify admin
add_action('acf/save_post', 'my_save_post',99);
function my_save_post( $post_id ) {
	if (!empty($_POST['acf']['field_5b28570780cc1'])) {
		update_post_meta($post_id, 'featured_image', $_POST['acf']['field_5b28570780cc1']);
		update_post_meta($post_id, '_thumbnail_id', $_POST['acf']['field_5b28570780cc1']);
	}
	$key_1_value = get_post_meta( $post_id, 'premium_pay_status', true );
	$listing_paid_or_not = get_post_meta( $post_id, 'listing_paid_or_not', true );
	update_post_meta($post_id,'listing_paid_or_not',$_POST['listing_paid_or_not']);
	//if( ($_POST['listing_paid_or_not'] > $listing_paid_or_not)) {
	if( $_POST['action'] == 'add_listing' && $_POST['listing_paid_or_not'] > 1) {
		delete_post_meta( $post_id, 'premium_pay_status');
		$key_1_value = get_post_meta( $post_id, 'premium_pay_status', true );
		# bail early if editing in admin
		if( is_admin() ) {		
			return;		
		}		
		if ( ! empty( $key_1_value ) && $key_1_value == 'pay_done') {
			return;	
		} else{
			send_email_func($post_id);
			$return = get_field('pay_listing_page', 'option');
			wp_redirect($return.'?post_id='.$post_id);	
			exit;
		}
	}else{
		send_email_func($post_id);
	}

	if($_POST['action'] == 'edit_listing' && ($listing_paid_or_not == 0 || empty($listing_paid_or_not)) ){
		$listing_paid_or_not = 1;
	}

	if( $_POST['action'] == 'edit_listing' && !empty($listing_paid_or_not) && ($_POST['listing_paid_or_not'] > $listing_paid_or_not)) {
		delete_post_meta( $post_id, 'premium_pay_status');
		$key_1_value = get_post_meta( $post_id, 'premium_pay_status', true );
		# bail early if editing in admin
		if( is_admin() ) {		
			return;		
		}		
		if ( ! empty( $key_1_value ) && $key_1_value == 'pay_done') {
			return;	
		} else{
			$return = get_field('pay_listing_page', 'option');
			wp_redirect($return.'?post_id='.$post_id);	
			exit;
		}
	}
	
	//for contact form notification
	if(($_POST['acf']['field_5a56fd92872ea'] !='')) {
	
		# bail early if editing in admin
		if( is_admin() ) {		
			return;		
		}
		
		# vars
		$post = get_post( $post_id );	
	
		# get user registration details
		$name = get_the_title($post_id);
		$c_phone = get_field('c_phone', $post_id);
		$c_email = get_field('c_email', $post_id);
		$c_subject = get_field('c_subject', $post_id);
		$c_message = get_field('c_message', $post_id);
	
		# email data
		$to = get_field('contact_notification', 'option');
		
		if($to==""){
			$to = get_bloginfo('admin_email');
		}
		
		if($c_subject==""){
			$c_subject = get_field('subject', 'option');
		}
	 	$headers = 'From: '. $to . "\r\n" .'Reply-To: ' . $c_email . "\r\n";
	 	$headers = array('Content-Type: text/html; charset=UTF-8','Reply-To: <'.$c_email.'>');	 
		
		$body .= 'From: '.$name. "\r\n";	
		$body .= 'Email: '.$c_email. "\r\n\r\n";
	    $body .= 'Phone: '.$c_phone. "\r\n\r\n";
		$body .= ''.$c_message. "\r\n";		
		
		# send email
	
		wp_mail( $to, $c_subject, $body, $headers );
	}
	
}
function send_email_func($post_id){

		# add conditional to check form was triggered
		//for listing notification
		if($_POST['acf']['field_5a508d0511150'] !='' && $_POST['action'] == 'add_listing') {
			# bail early if editing in admin
			if( is_admin() ) {		
				return;		
			}
			$post = get_post( $post_id );	
		
			$companyname = $post->post_title; 
		 	
			$price = get_field('price', $post_id);
			$phone = get_field('phone', $post_id);
			$email = get_field('email_address', $post_id);
			$website = get_field('website', $post_id);

			$term = get_field('category', $post_id);
			$result="";
			foreach($term as $cat_list) :
				$result.=$cat_list->name.', '; 
			endforeach;
			$trimmed=rtrim($result, ', ');

			$contact_address = get_field('address', $post_id);		
	    	$address = explode( "," , $contact_address['address']);
	 
			//echo $trimmed.'.';
		
			# email data
			$notify_to = get_field('listing_notification', 'option');
			if($notify_to == ""){
				$notify_to = get_bloginfo('admin_email');
			}
			$current_user = wp_get_current_user();
			
			//$headers = 'From: '. $to . "\r\n" .'Reply-To: ' . $email . "\r\n";
		 	$headers = array('Content-Type: text/html; charset=UTF-8');

			$subject = get_field('listing_subject', 'option');	
			if($subject==""){
				$subject = 'New Listing Added';
			}		
			
			$body = '<p>Waiting For Approval ' ."\r\n</p>";	
			$body .= '<p>Listing Name: '.$post->post_title. "\r\n\r\n</p>";
			if($price!=""){	
				$body .= '<p>Price: '.$price. "\r\n\r\n</p>";	
			}	
			if($address!=""){		
				$body .= '<p>Address: '.$address[0].', ' .$address[1].','.$address[2] ."\r\n\r\n</p>";	
			}
			if($phone!=""){		
				$body .= '<p>Phone: '.$phone. "\r\n\r\n</p>";
			}
			if($email!=""){		
				$body .= '<p>Email: '.$email. "\r\n\r\n</p>";	
			}
			if($website!=""){		
				$body .= '<p>Website: '.$website. "\r\n\r\n</p>";
			}	

			$authorEmail = $current_user->user_email;
			$authorEmailsubject = "Listing Added successfully.";
			# send email
			wp_mail( $authorEmail, $authorEmailsubject, $body, $headers );

			$body .= "<p> Listing submited by \r\n\r\n</p>";
			$body .= '<p>Username: ' . $current_user->user_login . '</p>';
			$body .= '<p>User email: ' . $current_user->user_email . '</p>';
			wp_mail( $notify_to, $subject, $body, $headers );
			
		}
	}
//end notify admin