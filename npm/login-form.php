<?php
/* Login form of Theme */
define('SLF_REGISTRATION_INCLUDE_URL', plugin_dir_url(__FILE__).'includes/');
// function to login Shortcode
function slf_login_shortcode( $atts ) {
  	//if looged in rediret to home page
	if ( is_user_logged_in() ) {
	    wp_redirect( get_option('home') );// redirect to home page
		exit;
	}
   global $wpdb;
	$login_fail_msg ='';
	if(isset($_GET['login']) && sanitize_text_field( $_GET['login'] ) != ''){
		$login_fail_msg=sanitize_text_field( $_GET['login'] );
	}
?>
	<div class="alar-login-form">
		<?php if($login_fail_msg=='failed'){?>
			<div class="alert alert-danger" role="alert">
				<?php _e('Username or password is incorrect.','directorytheme');?> <span class="frg_pwd"><?php _e('Forgot Password', 'directorytheme'); ?></span>
			</div>
		<?php }?>
		<div class="frgpwd_form" style="display:none;">
			<h3><?php _e("Reset Password",'directorytheme');?></h3>
			<div class="error_message" style="display: none;"></div>
			<form method="post" name="frgpwdform" id="fp_form">
				<div class="fpwd-email">
					<div>
						<input type="email" name="fpwdemail" class="fpwdemail" id="fpwdemail">
						<label><?php _e('Enter your email address *','directorytheme');?> </label>
					</div>
					<input type="button" name="fpwdsendmail" class="fp_btn" id="fp_btn" value="Send Link">
				</div>
			</form>
			<script type="text/javascript">
				function checkEmail(value) {
					var valid = true;
					if (value.indexOf('@') == -1) {
					   valid = false;
					} else {
					   var parts = value.split('@');
					   var domain = parts[1];
					   if (domain.indexOf('.') == -1) {
					      valid = false;
					   } else {
					      var domainParts = domain.split('.');
					      var ext = domainParts[1];
					      if (ext.length > 4 || ext.length < 2) {
					         valid = false;
					      }
					   }
					}
					return valid;
				}
				jQuery('.fp_btn').click(function(){
				   var val = document.getElementById('fpwdemail').value;
				   var valid = checkEmail(val);
				   if (!valid) {
				      jQuery('.error_message').show();
				      jQuery('.error_message').html("<p class='not_valid_email'>Not a valid email address.</p>");
				   }else {
					   var email_id = val;
					   var url = "<?php echo admin_url('admin-ajax.php'); ?>";
					   var tmp = null;
					   jQuery.ajax({
						   type: "POST",
						   url: url,
						   data: {action:'send_fp_mail',email_id:email_id},
						   success: function(res){
						   	jQuery('.error_message').show();
						     	jQuery('.error_message').html("<p class='check_user'>" + res + "</p>");
						   }
						});
					}
				});
			</script>
		</div>
		<h3><?php _e("Login",'directorytheme');?></h3>
		<form method="post" action="<?php echo get_home_url(); ?>/wp-login.php" id="loginform" name="loginform" >
			<div class="ftxt">
				<input type="text" tabindex="10" size="20" value="" class="input" id="user_login" required name="log" />
				<label><?php _e('Username','directorytheme');?> </label>
			</div>
			<div class="ftxt">
				<input type="password" tabindex="20" size="20" value="" class="input" id="user_pass" required name="pwd" />
				<label><?php _e('Password','directorytheme');?> </label>
			</div>
			<span class="frg_pwd"><?php _e('Forgot Password', 'directorytheme'); ?></span>
			<div class="fbtn">
				<input type="submit" tabindex="100" value="<?php _e('Log In','directorytheme'); ?>" class="global-btn" id="wp-submit" name="wp-submit" />
				<input type="hidden" value="<?php the_permalink(); ?>" name="redirect_to">
			</div>
		</form>
	</div>
<?php
}
//add login shortcoode
add_shortcode( 'simple-login-form', 'slf_login_shortcode' );

//redirect to front end ,when login is failed
add_action( 'wp_login_failed', 'my_front_end_login_fail' );  // hook failed login
function my_front_end_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];
   // if there's a valid referrer, and it's not the default log-in screen
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
      wp_redirect( $referrer . '/?login=failed' );
      exit;
   }
}