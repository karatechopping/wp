<?php
/* Registration form */
ob_start();
// function to registration Shortcode
function srf_registration_shortcode( $atts ) {
    global $wpdb, $user_ID;
	$firstname='';
	$lastname='';
	$username='';
	$email='';
	$error_msg	= '';
	$success = '';
	//if looged in rediret to home page
	if ( is_user_logged_in() ) {
	    wp_redirect( get_option('home') );// redirect to home page
		exit;
	}
?>
	<div class="alar-registration-form">
		<h3><?php _e("Register",'directorytheme');?></h3>
		<?php if($error_msg!='') { ?>
			<div class="alert alert-danger" role="alert">
		  		<?php echo $error_msg; ?>
			</div>
		<?php }  ?>
		<?php if($succress!='') { ?>
			<div class="alert alert-success" role="alert">
				<?php echo $succress; ?>
			</div>
		<?php }  ?>
		<?php
			$enable_captcha_key = get_option('options_recaptch_field', true);
			$captcha_site_key   = get_option('options_form_recaptcha_site_key', true);
			$captcha_secret_key   = get_option('options_form_recaptcha_secret_key', true);
			$recapcha_version = get_option('options_google_recapcha_version',true);
			$captcha_site_key_v3   = get_option('options_form_recaptcha_site_key_v3', true);
			$captcha_secret_key_v3   = get_option('options_form_recaptcha_secret_key_v3', true);


			if($enable_captcha_key == '1' && $recapcha_version == 'v2'){
				?>
					<script>
						function submitUserForm1() {
							<?php
								$register_captcha_key = get_field('captcha_home','option');

			    				if($enable_captcha_key == '1' && $register_captcha_key == '1'){
									if(!empty($captcha_site_key) && !empty($captcha_secret_key)){   
								?>
										var response = grecaptcha.getResponse();
										if(response.length === 0){
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
					<script src='https://www.google.com/recaptcha/api.js?'.time() async defer></script>
				<?php
			}else if($enable_captcha_key == '1' && $recapcha_version == 'v3'){
				?>
					<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $captcha_site_key_v3; ?>"></script>
					<script>
						grecaptcha.ready(function () {
				    		grecaptcha.execute('<?php echo $captcha_site_key_v3; ?>', { action: 'validate_captcha' }).then(function (token) {
				      			document.getElementById('recaptcha_token').value = token;
				        	});
				    	});
					</script>
				<?php
			}
		?>
		<form  name="form" id="registration"  method="post" onsubmit="return submitUserForm1()">
			<div class="row">
				<div class="col-md-6">
					<div class="ftxt">
					 	<input id="npm_firstname" name="npm_firstname" type="text" class="input" required value="" >
					 	<label><?php _e("First Name", 'directorytheme');?></label>
					</div>
				</div>
				<div class="col-md-6">
					<div class="ftxt">
					 	<input id="npm_lastname" name="npm_lastname" type="text" class="input" required value="" >
					 	<label><?php _e("Last name",'directorytheme');?></label>
					</div>
				</div>
			</div>
			<div class="ftxt">
			 	<input id="npm_username" name="npm_username" type="text" class="input" required value="" >
			 	<label><?php _e("Username",'directorytheme');?></label>
			</div>
			<div class="ftxt">
			 	<input id="npm_email" name="npm_email" type="email" class="input" required value="" >
			 	<label><?php _e("E-mail",'directorytheme');?> </label>
			</div>
			<div class="ftxt">
			 	<input id="password1" name="npm_password" type="password" required class="input" />
			 	<label><?php _e("Password",'directorytheme');?></label>
			</div>
			<div class="ftxt">
			 	<input id="password2" name="c_password" type="password" class="input" />
			 	<label><?php _e("Confirm Password ",'directorytheme');?></label>
			</div>
			<?php
				$enable_captcha_key1 = get_field('recaptch_field','option');
				$contact_captcha_key = get_field('captcha_contact','option');

				if($enable_captcha_key1 == '1' && $contact_captcha_key == '1' && $recapcha_version == 'v2'){
					if( !empty($captcha_site_key) && !empty($captcha_secret_key)){
						?>
							<div class="ftxt">
								<div class="g-recaptcha" data-sitekey="<?php echo $captcha_site_key; ?>" data-callback="verifyCaptcha"></div>
								<div id="g-recaptcha-error"></div>
							</div>
							<input type="hidden" id="recaptcha_version" name="recaptcha_version" value="v2">
						<?php
					}else{
						?>
							<div class="ftxt">
								<div class="g-recaptcha"></div>
								<div id="g-recaptcha-error">
									<span style="color:red;"><?php _e('site key Or secret key missing','directorytheme'); ?></span>
								</div>
							</div>
							<input type="hidden" id="recaptcha_version" name="recaptcha_version" value="v2">
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
						<div class="ftxt">
							<div id="g-recaptcha-error">
								<input type="hidden" id="recaptcha_version" name="recaptcha_version" value="v3">
								<span style="color:red;"><?php _e('site key Or secret key missing','directorytheme')?></span>
							</div>
						</div>
						<?php
					}
				} 
			?>
			<input type="submit" id="npm_submit" name="npm_submit" class="global-btn"  value="<?php _e('Register','directorytheme'); ?>"/>
		</form>
	</div>
<?php
}
//add registration shortcoode
add_shortcode( 'simple-registration-form', 'srf_registration_shortcode' );