<?php 
	/* Display form for claim a listing.
	Screenshot : https://prnt.sc/1vfj8bs*/
	$claim_id = sanitize_text_field($_POST['claim_id']);	
	acf_form(array(
		'id' => 'acf-form2',
		'post_id'		=> 'new_post',
		'new_post'		=> array(
			'post_type'		=> 'claim_listing',
			'post_status'		=> 'publish'
		),					
		'post_title' => false,
		'post_content' => false,
		'field_groups' => array('group_5n58fd6c45484'),
		'form'               => true,    
		'html_before_fields' => '',
		'html_after_fields' => '<input type="hidden" name="acf[field_5a56fda9782ec]" value="'.$claim_id.'"/>',
		'updated_message' => __("Success! claim_listing are reviewed in the order they are received.", 'acf'),
		'html_updated_message'	=> '<div class="alert alert-success text-center" role="alert">%s</div>',
		'uploader' 			 => 'wp',
		'submit_value'		=> 'Claim Your Business Now!',
		'html_submit_button'	=> '<input type="submit" class="acf-button button button-primary button-large" value="%s" id="my_claim" onclick="myFun()"/>',
		'return' => $return
	));
?>
<style>
.acf-field-5a66fd9f862ed,.acf-field-5a77fd9f862ea,.acf-field-5a56fda9782ec,.acf-field-5a74fda9882ac,.acf-field-6a74fda9872ac {display : none;}
</style>