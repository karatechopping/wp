<?php
	if(isset($_POST['npm_submit'])):
		if(sanitize_text_field( $_POST['npm_submit']) != ''){
			$captcha_secret_key   = get_option('options_form_recaptcha_secret_key', true);
			$captcha_secret_key_v3   = get_option('options_form_recaptcha_secret_key_v3', true);

	       	$apply_recaptcha_v3 = false;
			$apply_recaptcha_v2 = false;
			if(isset($_POST['recaptcha_version']) && $_POST['recaptcha_version'] == 'v2'){
				$apply_recaptcha_v2 = true;
			}
			if(isset($_POST['recaptcha_version']) && $_POST['recaptcha_version'] == 'v3'){
				$apply_recaptcha_v3 = true;
			}

			if($apply_recaptcha_v3){
				if(isset($_POST['recaptcha_token'])){
					$token  = $_POST['recaptcha_token'];
					$curlData = array(
				        'secret' => $captcha_secret_key_v3,
				        'response' => $token
				    );

				    $ch = curl_init();
				    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
				    curl_setopt($ch, CURLOPT_POST, 1);
				    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curlData));
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				    $curlResponse = curl_exec($ch);

				    $captchaResponse = json_decode($curlResponse, true);
				    if ($captchaResponse['success'] == '1' && $captchaResponse['action'] == 'validate_captcha' && $captchaResponse['score'] >= 0.5 && $captchaResponse['hostname'] == $_SERVER['SERVER_NAME']) {
				        register_user_function();
				    } else {
				        $error_msg = __('Google captcha not varified', 'directorytheme');
				    }
				}else{
					$error_msg = __('Site Key Or Secret key Missing', 'directorytheme');
				}
			}elseif($apply_recaptcha_v2){
				if(!empty($captcha_secret_key)){
					$recaptchaSecretKey = $captcha_secret_key; // Replace with your secret key
					$recaptchaResponse = $_POST['g-recaptcha-response'];
					$url = 'https://www.google.com/recaptcha/api/siteverify';
					$data = [
					    'secret' => $recaptchaSecretKey,
					    'response' => $recaptchaResponse
					];
					$options = [
					    'http' => [
					        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
					        'method' => 'POST',
					        'content' => http_build_query($data)
					    ]
					];
					$context = stream_context_create($options);
					$result = file_get_contents($url, false, $context);
					$responseKeys = json_decode($result, true);
					if ($responseKeys["success"]) {
						
					    register_user_function();
					} else {
					    $error_msg = __('Google captcha not varified', 'directorytheme');
					}
				}else{
					$error_msg = __('Site Key Or Secret key Missing', 'directorytheme');
				}
			}else{
				register_user_function();
			}	
		}		
	endif;
	function register_user_function(){
		global $wpdb;
		$firstname	= sanitize_text_field( $_REQUEST['npm_firstname'] );
		$lastname	= sanitize_text_field( $_REQUEST['npm_lastname']);
		$username 	= sanitize_text_field(  $_REQUEST['npm_username'] );
		$email 		= sanitize_text_field(  $_REQUEST['npm_email']  );
		$adminemail	= get_option('options_contact_notification', true);
		$password 	= $wpdb->escape( sanitize_text_field( $_REQUEST['npm_password']));
		$status 	= wp_create_user($username,$password,$email);
	    global $succress, $error_msg;
	   	$groupemail = array($email,$adminemail);
		if (is_wp_error($status))  {
			$error_msg = __('Username or Email already registered. Please try another one.', 'directorytheme');
		}else{
			$user_id=$status;
			update_user_meta( $user_id,'first_name', $firstname);
			update_user_meta( $user_id,'last_name', $lastname);

			$user_id_role    = new WP_User($user_id);
            $user_id_role->set_role('subscriber');

			$bloginfoname = get_bloginfo('name');
			$homeurl = get_home_url();
			$subject = 'You have successfully registered on '.$bloginfoname.'';

			$succress= __('You’ve registered successfully.', 'directorytheme');

			$email_to = sanitize_text_field(  $_REQUEST['npm_email']  );
			$body = 'Your Account info' ."\r\n"."\r\n";
			$body .= 'Name: '.$firstname. ' ' .$lastname. "\r\n";
			$body .= 'Username: '.$username. "\r\n";
			$body .= 'Password: '.$password. "\r\n";
			$body .= 'Login URL:' .$homeurl.'/account';
			$headers = "Content-Type: text/html; charset=UTF-8\r\n";
			$headers = array('Content-Type: text/html; charset=UTF-8');

			//User email
			wp_mail( $email, $subject, $body, $headers);

			$subject1 = 'New User registered on '.$bloginfoname.'';
			$body1 	  = 'User Account info' ."\r\n"."\r\n";
			$body1 	 .= 'Name: '.$firstname. ' ' .$lastname. "\r\n";
			$body1 	 .= 'Email Id: '.$email. "\r\n";

			//Admin email
			wp_mail( $adminemail, $subject1, $body1, $headers );
		}
	}
?>