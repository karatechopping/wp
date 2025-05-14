<?php 
// Subscription plans 
/* get stripe key from backend 
	Screenshort : https://prnt.sc/1umtyll
	*/
$stripe_api_key = get_field('stripe_api_key','option');
$stripe_publish_key = get_field('stripe_publish_key','option');
$stripe_btn = get_field('stripe_button','option');
// Stripe API configuration  
define('STRIPE_API_KEY', $stripe_api_key ); 
define('STRIPE_PUBLISHABLE_KEY', $stripe_publish_key );