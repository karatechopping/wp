<?php acf_form_head();
/**
 * Template Name: Contact
 */

get_header();

/* this code is used for if captcha value in field in backend
	Screenshort : https://prnt.sc/1ubuu9e
*/
$enable_captcha_key = get_option('options_recaptch_field', true);
$captcha_site_key   = get_option('options_form_recaptcha_site_key', true);
$captcha_secret_key   = get_option('options_form_recaptcha_secret_key', true);
$recapcha_version = get_option('options_google_recapcha_version',true);
$captcha_site_key_v3   = get_option('options_form_recaptcha_site_key_v3', true);
$captcha_secret_key_v3   = get_option('options_form_recaptcha_secret_key_v3', true); 

if($enable_captcha_key == '1' && $recapcha_version == 'v2'){
	?>
	<!-- TODO Move the script to functions.php -->
	<script src='https://www.google.com/recaptcha/api.js?<?php echo time(); ?>' async defer></script>
	<script>
		function isEmail(email) {
		  	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		  	return regex.test(email);
		}
		function submitUserForm() {
			var email 		  =  jQuery('.acf-field-email input[type=email]').val();
    		var contact_title = jQuery('input#acf-_post_title').val();
			if( contact_title == '' ){
         		jQuery('.acf-error-message').first().html('<span style="color:red;">Name field is required.</span>');
         		jQuery('.acf-error-message').first().show();
         		return false;
     		} else {
         		jQuery('.acf-error-message').first().hide();
     		}
    		if(!isEmail(email)){
        		jQuery('.acf-field-email .acf-error-message').show();
        		jQuery('.acf-field-email .acf-error-message').html('<span style="color:red;">Email field is required.</span>');
        		return false;
    		} else {
        		jQuery('.acf-field-email .acf-error-message').hide();
    		}
    		<?php
    			$contact_captcha_key = get_field('captcha_contact','option');
    			if($enable_captcha_key == '1' && $contact_captcha_key == '1'){
    				if( !empty($captcha_site_key) && !empty($captcha_secret_key)){ 
    					?>
						var response = grecaptcha.getResponse();
						if( response.length == 0 ){
							document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">This field is required.</span>';
							return false;
						}
						return true;
						function verifyCaptcha() {
			    			document.getElementById('g-recaptcha-error').innerHTML = '';
						}
						<?php
					}
				} 
			?>
		}
	</script>
	<?php
}else if($enable_captcha_key == '1' && $recapcha_version == 'v3'){
	?>
	<!-- TODO Move the script to functions.php -->
	<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $captcha_site_key_v3; ?>"></script>
	<script>
		function isEmail(email) {
		  	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		  	return regex.test(email);
		}
		grecaptcha.ready(function () {
    		grecaptcha.execute('<?php echo $captcha_site_key_v3; ?>', { action: 'validate_captcha' }).then(function (token) {
      			document.getElementById('recaptcha_token').value = token;
        	});
    	});
	  	function submitUserForm() {
	  		var email 		  =  jQuery('.acf-field-email input[type=email]').val();
    		var contact_title = jQuery('input#acf-_post_title').val();
			if( contact_title == '' ){
         		jQuery('.acf-error-message').first().html('<span style="color:red;">Name field is required.</span>');
         		jQuery('.acf-error-message').first().show();
         		return false;
     		} else {
         		jQuery('.acf-error-message').first().hide();
     		}
    		if(!isEmail(email)){
        		jQuery('.acf-field-email .acf-error-message').show();
        		jQuery('.acf-field-email .acf-error-message').html('<span style="color:red;">Email field is required.</span>');
        		return false;
    		} else {
        		jQuery('.acf-field-email .acf-error-message').hide();
    		}

    		
	  	}
	</script>

	<?php
}else{
	?>
		<script>
			function isEmail(email) {
			  	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			  	return regex.test(email);
			}
			function submitUserForm() {
				var email 		  =  jQuery('.acf-field-email input[type=email]').val();
	    		var contact_title = jQuery('input#acf-_post_title').val();
				if( contact_title == '' ){
	         		jQuery('.acf-error-message').first().html('<span style="color:red;">Name field is required.</span>');
	         		jQuery('.acf-error-message').first().show();
	         		return false;
	     		} else {
	         		jQuery('.acf-error-message').first().hide();
	     		}
	    		if(!isEmail(email)){
	        		jQuery('.acf-field-email .acf-error-message').show();
	        		jQuery('.acf-field-email .acf-error-message').html('<span style="color:red;">Email field is required.</span>');
	        		return false;
	    		} else {
	        		jQuery('.acf-field-email .acf-error-message').hide();
	    		}
	    	}
		</script>
	<?php
}

?>
<?php get_template_part('template-parts/header/page-banner'); ?>

<div id="cp-container" class="cp-section">
	<div class="inner">
    	<div class="full-content">
			<?php
			if (have_posts()):
				while (have_posts()) : the_post(); ?>
				<div class="entry contact_content">
					<?php the_content(); ?>
				</div>
				<?php
				endwhile;
			endif;

			$maichimp_enable  = get_field('mailchimp_field', 'option');
			$api_key 		  = get_field('mailchimp_api_key', 'option');
			$list_id		  = get_field('mailchimp_list_id', 'option');
			$form_title 	  = get_option('options_form_title',true);
			$form_content 	  = get_option('options_form_content',true);
			$enable_phone 	  = get_field('enable_phone','option');
			$enable_subject   = get_field('enable_subject','option');
			$enable_message   = get_field('message_field','option');
			$form_button_text = get_option('options_form_button_text', true);
			$btn_txt 	      = !empty($form_button_text) ? $form_button_text : 'Submit Message';

			$apply_recaptcha_v3 = false;
			$apply_recaptcha_v2 = false;
			if(isset($_POST['recaptcha_version']) && $_POST['recaptcha_version'] == 'v2'){
				$apply_recaptcha_v2 = true;
			}
			if(isset($_POST['recaptcha_version']) && $_POST['recaptcha_version'] == 'v3'){
				$apply_recaptcha_v3 = true;
			}
                    
			if(isset($_POST['Action']) && $_POST['Action'] == 'contact_inq'):
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
					        add_contact_inquery();
					    } else {
					        echo '<div class="alert alert-info text-center" role="alert">Google captcha not varified.</div>';
					    }
					}else{
						?>
						<div class="alert alert-info text-center" role="alert">
							<?php _e('Site Key Or Secret key Missing','directorytheme');?>
						</div>
						<?php
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
						    add_contact_inquery();
						} else {
						    echo '<div class="alert alert-info text-center" role="alert">Google captcha not varified.</div>';
						}
					}else{
						?>
						<div class="alert alert-info text-center" role="alert">
							<?php _e('Site Key Or Secret key Missing','directorytheme'); ?>
						</div>
						<?php
					}
				}else{
					add_contact_inquery();
				}
			endif;

function add_contact_inquery(){
	$enable_phone 	  = get_field('enable_phone','option');
	$enable_subject   = get_field('enable_subject','option');
	$enable_message   = get_field('message_field','option');
	$my_post_arg = array(
		'post_title' => sanitize_text_field(wp_strip_all_tags($_POST['acf']['_post_title'])),
		'post_type'		=> 'contacts',
		'post_status'		=> 'publish'
	);
	$id = wp_insert_post( $my_post_arg );
	if(!empty($id)):
		update_post_meta($id,'c_email', sanitize_text_field($_POST['acf']['field_5a56fd92872ea']));
		update_post_meta($id,'_c_email', 'field_5a56fd92872ea');
		update_post_meta($id,'c_phone', sanitize_text_field($_POST['acf']['field_5a56fd9f872eb']));
		update_post_meta($id,'_c_phone', 'field_5a56fd9f872eb');
		update_post_meta($id,'c_subject', sanitize_text_field($_POST['acf']['field_5a56fda9872ec']));
		update_post_meta($id,'_c_subject', 'field_5a56fda9872ec');
		update_post_meta($id,'c_message', sanitize_text_field($_POST['acf']['field_5a56fdb0872ed']));
		update_post_meta($id,'_c_message', 'field_5a56fdb0872ed');
		update_post_meta($id,'_validate_email', '');
		update_post_meta($id,'__validate_email', '_validate_email');

		$to = get_field('contact_notification', 'option');
		if( $to == "") $to = get_bloginfo('admin_email');
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers = 'From: '. sanitize_text_field($_POST['acf']['field_5a56fd92872ea']) . "\r\n" .'Reply-To: ' . sanitize_text_field($_POST['acf']['field_5a56fd92872ea']) . "\r\n";
		$headers = array('Content-Type: text/html; charset=UTF-8','Reply-To: <'.sanitize_text_field($_POST['acf']['field_5a56fd92872ea']).'>');
		$subval  = sanitize_text_field($_POST['acf']['field_5a56fda9872ec']);
		$phnval  = sanitize_text_field($_POST['acf']['field_5a56fd9f872eb']);
		$msgval  = sanitize_text_field($_POST['acf']['field_5a56fdb0872ed']);
		$subt    =($enable_subject=='1'? $subval : "");
		$phnt    =($enable_phone=='1'? $phnval : "");
		$mesgt   =($enable_message=='1'? $msgval : "");
		$body 	.= '<p>Below are the details of Contact</p>';
		$body 	.= '<p>Name : '.sanitize_text_field($_POST['acf']['_post_title']). "</p>";
		$body 	.= '<p>Email : '.sanitize_text_field($_POST['acf']['field_5a56fd92872ea']). "</p>";
		$new 	 = get_option('options_contact_notification', true);

		if($subt != '') $body .= '<p>Subject : '.$subt. "</p>";
		if($phnt != '') $body .= '<p>Phone : '.$phnt. "</p>";
		if($mesgt != '' && strlen($mesgt) != 0) $body .= '<p>Message : '.$mesgt. "</p>";
		
		wp_mail(get_option('options_contact_notification', true), "Contact Request", $body, $headers);

		if(isset($_POST['mailchimp_action']) && $_POST['mailchimp_action'] == 'mailchimpsubscribe'):
			$id;
			$email   = get_post_meta( $id, 'c_email', true );
			$fnm     = get_the_title( $id );
			$ph_no   = get_post_meta( $id, 'c_phone', true );
			$api_key = get_field('mailchimp_api_key', 'option');
			$list_id = get_field('mailchimp_list_id', 'option');
			$status  = 'subscribed';
			$mch_api = curl_init(); // initialize cURL connection
			$data    = array(
				'apikey'        => $api_key,
				'email_address' => $email,
				'status'        => $status,
				'merge_fields'  => ['FNAME'=> $fnm]
			);

			curl_setopt($mch_api, CURLOPT_URL, 'https://' . substr($api_key,strpos($api_key,'-')+1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($data['email_address'])));
			curl_setopt($mch_api, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.base64_encode( 'user:'.$api_key )));
			curl_setopt($mch_api, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
			curl_setopt($mch_api, CURLOPT_RETURNTRANSFER, true); // return the API response
			curl_setopt($mch_api, CURLOPT_CUSTOMREQUEST, 'PUT'); // method PUT
			curl_setopt($mch_api, CURLOPT_TIMEOUT, 10);
			curl_setopt($mch_api, CURLOPT_POST, true);
			curl_setopt($mch_api, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($mch_api, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json
			$result = curl_exec($mch_api);
		endif;

		/* start sendio functionality */
		if(isset($_POST['sendio_subscribe']) && $_POST['sendio_subscribe'] == 'sandiosubscribe'):
			$id;
			$email  = get_post_meta( $id, 'c_email', true );
			$fnm 	= get_the_title( $id );
			$ph_no  = get_post_meta( $id, 'c_phone', true );

			$sendio_contactid = get_field('sendio_contactid','option');
			$sendio_formid = get_field('sendio_form_id','option');

			if($sendio_contactid != ''  && $sendio_formid != ''){

				$url = 'https://sendiio.com/callbacks/subscription/lists';
				$ch = curl_init($url);
				$jsonData = array(
					'name' => $fnm,
					'email' => $email,
					'Email' => $email,
					'list'=> $sendio_contactid,
					'form' => $sendio_formid
				);

				$jsonDataEncoded = json_encode($jsonData);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				$result = curl_exec($ch);

			}
		endif;
		/*sendio over*/

	endif;
	echo '<div class="alert alert-info text-center" role="alert">Thanks for contacting us! We will be in touch  with you shortly.</div>';
}

			

			if( $form_title == '1' || empty($form_title) ) {
				echo '<h2 style="width: 100%;text-align: center;">' . __( "Contact Us", "directorytheme" ) . '</h2>';
			} else {
				echo '<h2 style="width: 100%;text-align: center;">'.__( $form_title, "directorytheme" ).'</h2>';
			} ?>
			
			<form method="post" class="acf-form" name="contect_frm" id="contect_frm" action="" onsubmit="return submitUserForm();">
					<div class="acf-error-message" style="display:none;"><p><?php echo __('Validation failed. 2 fields require attention', 'directorytheme'); ?></p><a href="#" class="acf-icon -cancel small"></a></div>

					<div id="acf-form-data" class="acf-hidden">

					</div>
					<div class="acf-fields acf-form-fields -top">
						<div class="acf-field acf-field-text acf-field--post-title" data-name="_post_title" data-type="text" data-key="_post_title" data-required="1">
							<div class="acf-label"><label for="acf-_post_title"><?php echo __('Name:', 'directorytheme'); ?><span class="acf-required">*</span></label></div>
							<div class="acf-input">
							<div class="acf-input-wrap"><input type="text" id="acf-_post_title" name="acf[_post_title]" required="required"></div>		</div>
						</div>
					<div class="acf-field acf-field-email acf-field-5a56fd92872ea acf-error" data-name="c_email" data-type="email" data-key="field_5a56fd92872ea" data-required="1">
							<div class="acf-label"><label for="acf-field_5a56fd92872ea"><?php echo __('Email:', 'directorytheme'); ?><span class="acf-required">*</span></label></div>
							<div class="acf-input"><div class="acf-error-message" style="display:none;"><p><?php echo __('Email field is required.', 'directorytheme'); ?></p></div>
							<div class="acf-input-wrap"><input type="email" id="acf-field_5a56fd92872ea" name="acf[field_5a56fd92872ea]" required="required"></div>					</div>
					</div>

					<?php if($enable_phone == '1' && !empty($enable_phone)): ?>

						<div class="acf-field acf-field-text acf-field-5a56fd9f872eb" data-name="c_phone" data-type="text" data-key="field_5a56fd9f872eb" >
								<div class="acf-label"><label for="acf-field_5a56fd9f872eb"><?php echo __('Phone:', 'directorytheme'); ?></label></div>
								<div class="acf-input"><div class="acf-error-message" style="display:none;"><p><?php echo __('Phone number is required.', 'directorytheme'); ?></p></div>
								<div class="acf-input-wrap"><input type="text" id="acf-field_5a56fd9f872eb" name="acf[field_5a56fd9f872eb]"></div>					</div>
						</div>
					<?php
						endif;

						if($enable_subject == '1'):  ?>
							<div class="acf-field acf-field-text acf-field-5a56fda9872ec" data-name="c_subject" data-type="text" data-key="field_5a56fda9872ec">
								<div class="acf-label"><label for="acf-field_5a56fda9872ec"><?php echo __('Subject:', 'directorytheme'); ?></label></div>
								<div class="acf-input"><div class="acf-error-message" style="display:none;"><p><?php echo __('Subject is required', 'directorytheme'); ?></p></div>
								<div class="acf-input-wrap"><input type="text" id="acf-field_5a56fda9872ec" name="acf[field_5a56fda9872ec]"></div>					</div>
							</div>
						<?php
						endif;

						if($enable_message == '1'): ?>
						<div class="acf-field acf-field-textarea acf-field-5a56fdb0872ed" data-name="c_message" data-type="textarea" data-key="field_5a56fdb0872ed">
							<div class="acf-label"><label for="acf-field_5a56fdb0872ed"><?php echo __('Message:', 'directorytheme'); ?></label></div>
							<div class="acf-input">
								<div class="acf-error-message" style="display:none;"><p><?php echo __('Message is required', 'directorytheme'); ?></p></div>
								<textarea id="acf-field_5a56fdb0872ed" name="acf[field_5a56fdb0872ed]" rows="8"></textarea>
							</div>
						</div>
					<?php
						endif;
					?>
					<div class="acf-field acf-field-text acf-field--validate-email" style="display:none !important;" data-name="_validate_email" data-type="text" data-key="_validate_email">
						<div class="acf-label"><label for="acf-_validate_email"><?php echo __('Validate Email', 'directorytheme'); ?></label></div>
						<div class="acf-input">
							<div class="acf-input-wrap"><input type="text" id="acf-_validate_email" name="acf[_validate_email]"></div>
						</div>
					</div>
					</div>
					<?php
			        $enable_captcha_key1 = get_field('recaptch_field','option');
					$contact_captcha_key = get_field('captcha_contact','option');

					if($enable_captcha_key1 == '1' && $contact_captcha_key == '1' && $recapcha_version == 'v2'){
						if( !empty($captcha_site_key) && !empty($captcha_secret_key)){ ?>
						<div class="col-md-12">
							<div class="form-group">
								<div class="g-recaptcha" data-sitekey="<?php echo $captcha_site_key; ?>" data-callback="verifyCaptcha"></div>
								<div id="g-recaptcha-error"></div>
							</div>
							<input type="hidden" id="recaptcha_version" name="recaptcha_version" value="v2">
						</div>
						<?php
						}else{
							?>
							<div class="col-md-12">
								<div class="form-group">
									<div class="g-recaptcha"></div>
									<div id="g-recaptcha-error">
										<span style="color:red;"><?php _e('site key Or secret key missing','directorytheme'); ?></span>
									</div>
								</div>
								<input type="hidden" id="recaptcha_version" name="recaptcha_version" value="v2">
							</div>
							<?php
						}
					}
					if($enable_captcha_key1 == '1' && $contact_captcha_key == '1' && $recapcha_version == 'v3'){
						if( !empty($captcha_site_key_v3) && !empty($captcha_secret_key_v3)){
						?>
							<input type="hidden" id="recaptcha_token" name="recaptcha_token">
							<input type="hidden" id="recaptcha_version" name="recaptcha_version" value="v3">
						<?php
						}else{
							?>
							<div class="col-md-12">
								<div class="form-group">
									<div id="g-recaptcha-error">
										<input type="hidden" id="recaptcha_version" name="recaptcha_version" value="v3">
										<span style="color:red;"><?php _e('site key Or secret key missing','directorytheme')?></span>
									</div>
								</div>
							</div>
							<?php
						}
					}
					if(get_sendio_uid()){
						$sandio_uid1 = get_sendio_uid();
					?>
					<input type="hidden" name="sendio_subscribe" value="sandiosubscribe">
					<?php
					}
					?>

					<div class="col-md-12">
						<div class="form-group">
							<input type="button" value="<?php if($form_button_text == '1' || empty($form_button_text)) { echo "Submit Message"; } else { echo $form_button_text; } ?>" class="btn btn-primary submit-form">
							<input type="hidden" name="Action" value="contact_inq">
							<?php
							if( $maichimp_enable == 1){
								if( $api_key != '' and $list_id != ''){ ?>
								<input type="hidden" name="mailchimp_action" value="mailchimpsubscribe">
								<?php
								}
							} ?>
						</div>
					</div>
			</form>
      </div>

  </div><!--inner-->
</div><!--cp-container-->
<div class="contact_map">
	<?php
	$location = get_field('map', 'option');
	if( !empty($location) ): ?>
		<div class="acf-map">
			<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
		</div>
	<?php 
	endif; ?>
</div>

<script>
	jQuery('#cp-container img').addClass('img-fluid');
	jQuery('label[for="acf-_post_title"]').html("Name <span class='acf-required'>*</span>");
	jQuery(".submit-form").click(function(){ jQuery('#contect_frm').submit(); });
</script>
<?php get_footer(); ?>