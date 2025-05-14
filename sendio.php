<?php
/* sendio functionality code check key exist or not*/
function get_sendio_uid()
{
	$url = 'https://sendiio.com/api/v1/auth/check';
    $ch = curl_init($url);
    $sendio_btn = get_field('sendio_enable','option'); 
    
    if($sendio_btn == 1)
    {
        $sendio_token = get_field('sendio_token','option');
        $sendio_secret = get_field('sendio_secret','option');

        $jsonData = array(
        'token' => $sendio_token,
        'secret' => $sendio_secret,
        );
        $jsonDataEncoded = json_encode($jsonData);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); 
        $decoded_result = json_decode($result);       
        $sandio_uid = print_r($decoded_result->data->user_id,true);
        
        if(!empty($sandio_uid))
        {
            return $sandio_uid;
        }        
   	}
}
/*user registeration*/
add_action('user_register','my_function');

function my_function($user_id)
{  
		$sendio_regid = get_field('sendio_regid','option');
		$sendio_formid = get_field('sendio_form_id','option');
		if($sendio_regid != '' && $sendio_formid != ''){
            $user_info = get_userdata($user_id);
            $usernm = $user_info->display_name;
            $emailid = $user_info->user_email;
            $url = 'https://sendiio.com/callbacks/subscription/lists';
            $ch = curl_init($url);
            $jsonData = array(
              'name' => $usernm,
              'email' => $emailid,
              'list'=> $sendio_regid,
              'form' => $sendio_formid
            );

            $jsonDataEncoded = json_encode($jsonData);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
            $result = curl_exec($ch);

        }  
}  


/*when change listing draft to published */

function se_10441543_save_post($post_id, $post){
    global $choices_array1;
    //determine post type
    if(get_post_type( $post_id ) == 'listings')
    {        
        //echo $email_address = get_post_meta($post_id,'email_address',true);
            $listing_type = get_post_meta($post_id,'listing_type',true);
            $author_id =$post->post_author;
            if($listing_type != $choices_array1[0])
            {
                $sendio_listingid = get_field('sendio_listingid','option');
                $sendio_formid = get_field('sendio_form_id','option');
                if($sendio_listingid != '' && $sendio_formid != ''){
                    $emailid = get_post_meta($post_id,'email_address',true);
                    $user_info = get_userdata($author_id);                  
                    $usernm = $user_info->display_name;
                    if($emailid == ''):
                        $emailid = $user_info->user_email;
                    endif;
                    $url = 'https://sendiio.com/callbacks/subscription/lists';
                    $ch = curl_init($url);
                    $jsonData = array(
                      'name' => $usernm,
                      'email' => $emailid,
                      'list'=> $sendio_listingid,
                      'form' => $sendio_formid
                    );

                    $jsonDataEncoded = json_encode($jsonData);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
                    $result = curl_exec($ch);
                    $decoded_result = json_decode($result); 
                }
            }    
    }
}

add_action('publish_listings', 'se_10441543_save_post', 10, 2);
?>